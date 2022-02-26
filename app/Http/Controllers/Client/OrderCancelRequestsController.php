<?php

namespace App\Http\Controllers\Client;

use DB;
use Log;
use Auth;
use Session;
use DataTables;
use Carbon\Carbon;
use GuzzleHttp\Client;
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
                            <a title='Approve' class='action-icon text-success' href='#'><i class='fa fa-check'></i></a>
                            <a title='Reject' class='action-icon text-danger ml-2' href='#'><i class='fa fa-times'></i></a>
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
   
    
}
