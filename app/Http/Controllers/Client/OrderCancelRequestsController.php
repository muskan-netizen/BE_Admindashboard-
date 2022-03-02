<?php

namespace App\Http\Controllers\Client;

use DB;
use Log;
use Auth;
use Session;
use DataTables;
use Carbon\Carbon;
use GuzzleHttp\Client as GCLIENT;
use App\Models\Client as CP;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Client\BaseController;
use App\Models\{AutoRejectOrderCron, Order, OrderStatusOption, OrderCancelRequest, DispatcherStatusOption, VendorOrderStatus, ClientPreference, NotificationTemplate, OrderProduct, OrderVendor, UserAddress, Vendor, OrderReturnRequest, UserDevice, UserVendor, LuxuryOption, ClientCurrency, VendorOrderDispatcherStatus, Tax, Transaction, User};

class OrderCancelRequestsController extends BaseController
{
    use ApiResponser;
    // use \App\Http\Traits\OrderTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $order_requests = OrderCancelRequest::orderBy('id','desc');

        $pending_requests_count = OrderCancelRequest::where('status', 0);
        if ($user->is_superadmin == 0) {
            $pending_requests_count = $pending_requests_count->whereHas('order.vendors.vendor.permissionToUser', function ($query) use($user) {
                $query->where('user_id', $user->id);
            });
        }
        $pending_requests_count = $pending_requests_count->count();

        $approved_requests_count = OrderCancelRequest::where('status', 1);
        if ($user->is_superadmin == 0) {
            $approved_requests_count = $approved_requests_count->whereHas('order.vendors.vendor.permissionToUser', function ($query) use($user) {
                $query->where('user_id', $user->id);
            });
        }
        $approved_requests_count = $approved_requests_count->count();

        $rejected_requests_count = OrderCancelRequest::where('status', 2);
        if ($user->is_superadmin == 0) {
            $rejected_requests_count = $rejected_requests_count->whereHas('order.vendors.vendor.permissionToUser', function ($query) use($user) {
                $query->where('user_id', $user->id);
            });
        }
        $rejected_requests_count = $rejected_requests_count->count();
        
        $clientCurrency = ClientCurrency::where('is_primary', 1)->first();
        return view('backend.order_cancel_requests.index', compact('pending_requests_count', 'approved_requests_count', 'rejected_requests_count', 'clientCurrency'));
    }

    public function filter(Request $request, $domain = '')
    {
        $user = Auth::user();
        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        
        $req = OrderCancelRequest::with(['order', 'vendor', 'order_vendor', 'updated_by_user'])->where('status', $request->status);
        if (Auth::user()->is_superadmin == 0) {
            $req = $req->whereHas('order_vendor.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $req = $req->orderBy('id', 'desc');

        return Datatables::of($req)
            ->addColumn('order_number', function($req) {
                return $req->order ? $req->order->order_number : '';
            })
            ->addColumn('show_vendor_url', function($req) {
                return route('vendor.catalogs', $req->vendor_id);
            })
            ->addColumn('order_detail_url', function($req) {
                return route('order.show.detail', [$req->order_id, $req->vendor_id]);
            })
            ->addColumn('vendor', function($req) {
                return $req->order_vendor->vendor->name;
            })
            ->addColumn('reject_reason', function($req) {
                return $req->reject_reason;
            })
            ->editColumn('updated_by', function($req) {
                return $req->updated_by_user ? $req->updated_by_user->name : '';
            })
            ->addColumn('action', function ($req) use($request) {
                if($request->status == 0){
                    return "<div class='form-ul'>
                        <div class='inner-div d-inline-block'>
                            <a title='Approve' class='action-icon text-success complete_request_btn' href='#' data-status='1' data-id='".$req->id."'><i class='fa fa-check'></i></a>
                            <a title='Reject' class='action-icon text-danger ml-2 complete_request_btn' href='#' data-status='2' data-id='".$req->id."'><i class='fa fa-times'></i></a>
                        </div>
                    </div>";
                }else{
                    return '';
                }
            })
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
                if (!empty($request->get('search'))) {
                    $search = $request->get('search');
                    $instance->where(function($query) use($search) {
                        $query->where('reject_reason', 'LIKE', '%'.$search.'%')
                        ->orWhereHas('order', function($q){
                            $q->where('order_number', 'LIKE', '%'.$search.'%');
                        })
                        ->orWhereHas('order_vendor.vendor', function($q){
                            $q->where('name', 'LIKE', '%'.$search.'%');
                        });
                    });
                }
            }, true)
            ->make(true);
    }
   
    public function updateStatus(Request $request){
        DB::beginTransaction();
        try{
            $user = Auth::user();
            $id = $request->id;
            $status = $request->status;
            $cancel_req = OrderCancelRequest::where('id', $id)->first();
            if(!$cancel_req){
                return $this->errorResponse('Invalid Data', 422);
            }
            if ($cancel_req->status == 1) {
                return $this->errorResponse('Request has already been approved', 422);
            }
            if ($cancel_req->status == 2) {
                return $this->errorResponse('Request has already been rejected', 422);
            }
            $order_id = $cancel_req->order_id;
            $vendor_id = $cancel_req->vendor_id;
            $order_vendor_id = $cancel_req->order_vendor_id;
            $client_preferences = ClientPreference::first();
            // If cancel order request has been approved
            if($status == 1){
                $currentOrderStatus = OrderVendor::with('orderDetail', 'vendor')->where(['id'=>$order_vendor_id, 'vendor_id' => $vendor_id, 'order_id' => $order_id])->first();
                if ($currentOrderStatus->order_status_option_id == 3) {
                    return $this->errorResponse(__('Order has already been rejected'), 422);
                }
                
                if ( !empty($currentOrderStatus->dispatch_traking_url) ) {
                    $dispatch_traking_url = str_replace('/order/', '/order-cancel/', $currentOrderStatus->dispatch_traking_url);
                    $response = Http::get($dispatch_traking_url . '?reject_reason='.$cancel_req->reject_reason);
                    $response = json_decode($response->getBody(), true);
                    if($response['status'] != 'Success'){
                        return $this->errorResponse($response['message'], 400);
                    }
                }

                $currentOrderStatus->order_status_option_id = 3;
                $currentOrderStatus->reject_reason = $cancel_req->reject_reason;
                $currentOrderStatus->cancelled_by = $user->id;
                $currentOrderStatus->update();

                if($currentOrderStatus->payment_option_id != 1){
                    $order_user = User::find($currentOrderStatus->user_id);
                    $wallet = $order_user->wallet;
                    $credit_amount = $currentOrderStatus->orderDetail->payable_amount;
                    $wallet->depositFloat($credit_amount, ['Wallet has been <b>Credited</b> for return #'. $currentOrderStatus->orderDetail->order_number.' ('.$currentOrderStatus->vendor->name.')']);
                }
                $msg = 'approved';
            }
            elseif($status == 2){
                //
                $msg = 'rejected';
            }else{
                return $this->errorResponse(__('Invalid status code'), 400);
            }
            
            $cancel_req->status = $status;
            $cancel_req->updated_by = $user->id;
            $cancel_req->update();
            DB::commit();
            $this->sendCancelOrderRequestStatusNotification($currentOrderStatus, $status);
            return $this->successResponse('', __('Request has been '.$msg.' Successfully.'));
        }
        catch(\Exception $ex){
            DB::rollback();
            return $this->errorResponse($ex->getMessage(), $ex->getCode());
        }
    }

    public function sendCancelOrderRequestStatusNotification($order_vendor, $status){
        try{
            $dispatch_domain = $this->checkIfLastMileOn();
            if ($dispatch_domain && $dispatch_domain != false) {
                $postdata =  ['web_hook_code' => $order_vendor->web_hook_code, 'status' => $status];
                $client = new GCLIENT([
                    'headers' => [
                        'personaltoken' => $dispatch_domain->delivery_service_key,
                        'shortcode' => $dispatch_domain->delivery_service_key_code,
                        'content-type' => 'application/json'
                    ]
                ]);
                $url = $dispatch_domain->delivery_service_key_url;
                $res = $client->post(
                    $url . '/api/cancel-order-request-status/driver/notify',
                    ['form_params' => ($postdata)]
                );
                $response = json_decode($res->getBody(), true);
                // if ($response && $response['message'] == 'success') {
                //     return $response;
                // }
            }
        }
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
