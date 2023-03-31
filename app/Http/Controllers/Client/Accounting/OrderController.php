<?php

namespace App\Http\Controllers\Client\Accounting;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderVendorListTaxExport;
use App\Models\{User,Vendor,OrderVendor,OrderStatusOption,DispatcherStatusOption,OrderRefund,Payment,Order};
use DB;

class OrderController extends Controller{
    use ApiResponser;
    public function index(Request $request){
          
        $dispatcher_status_options = DispatcherStatusOption::get();
        $order_status_options = OrderStatusOption::where('type', 1)->get();
        // all vendors
        $vendors = Vendor::where('status', '!=', '2')->orderBy('id', 'desc');
        if (Auth::user()->is_superadmin == 0) {
            $vendors = $vendors->whereHas('permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $vendors = $vendors->get();
        return view('backend.accounting.order', compact('vendors','order_status_options', 'dispatcher_status_options'))->with($this->getOrderVendorCalculations($request,true));
    }
    
    
    public function getOrderVendorCalculations(Request $request,$flag = false){
        $data['total_order_count'] = $this->getOrdervendors($request)->count();
        $data['total_delivery_fees'] = decimal_format($this->getOrdervendors($request)->sum('delivery_fee'));
        $data['total_cash_to_collected'] = decimal_format($this->getOrdervendors($request)->whereHas('orderDetail', function ($query) {
            return $query->where('payment_option_id', '=', 1);
        })->sum('payable_amount'));
        $data['total_earnings_by_vendors'] = decimal_format($this->getOrdervendors($request)->sum('payable_amount'));    
        
        if($flag){
            return $data;
        }
        return response()->json(['data' => $data]);       
    }
    
    
    public function getOrdervendors($request){
        $user = Auth::user();
        $search_value = $request->get('search');
        
        $vendor_orders = OrderVendor::with(['orderDetail.paymentOption', 'user','vendor','payment','orderstatus.OrderStatusOption']);
        if (!empty($request->get('date_filter'))) {
            $date_date_filter = explode(' to ', $request->get('date_filter'));
            $to_date = (!empty($date_date_filter[1]))?$date_date_filter[1]:$date_date_filter[0];
            $from_date = $date_date_filter[0];
            $vendor_orders = $vendor_orders->between($from_date." 00:00:00", $to_date." 23:59:59");
        }
        
        if (!empty($request->get('vendor_id'))) {
            $vendor_id = $request->get('vendor_id');
            $vendor_orders = $vendor_orders->where('vendor_id', $vendor_id);
        }
        
        if (!empty($request->get('status_filter'))) {
            $status_filter = $request->get('status_filter');
            $vendor_orders = $vendor_orders->where('order_status_option_id', $status_filter);
        }
        
        if ($user->is_superadmin == 0) {
            $vendor_orders = $vendor_orders->whereHas('vendor.permissionToUser', function ($query) use($user){
                $query->where('user_id', $user->id);
            });
        }
      return $vendor_orders->orderBy('id', 'DESC');
    }
    
    public function filter(Request $request){
        $user = Auth::user();
        $timezone = $user->timezone ? $user->timezone : 'Asia/Kolkata';
        $vendor_orders = $this->getOrdervendors($request);
        
        return Datatables::of($vendor_orders)
            ->addColumn('view_url', function($vendor_orders) {
                if(!empty($vendor_orders->order_id) && !empty($vendor_orders->vendor_id)){
                    return route('order.show.detail', [$vendor_orders->order_id, $vendor_orders->vendor_id]);
                }else{
                    return '';
                }
            })
            ->addColumn('created_date', function($vendor_orders) use($timezone) {
                return dateTimeInUserTimeZone($vendor_orders->created_at, $timezone);
            })
            ->addColumn('user_name', function($vendor_orders) {
                return $vendor_orders->user ? $vendor_orders->user->name : '';
            })
            ->addColumn('subtotal_amount', function($vendor_orders) {
                return number_format($vendor_orders->subtotal_amount - $vendor_orders->total_markup_price??0, 2);
            })
            ->addColumn('vendor_amount', function($vendor_orders) {
               
                return $vendor_orders->vendor_amount;
            })
            ->addColumn('admin_commission', function($vendor_orders) {
                // return number_format($vendor_orders->admin_commission_percentage_amount, 2).' ('.number_format($vendor_orders->vendor->commission_percent,2).'%)';
                return number_format($vendor_orders->admin_commission_percentage_amount, 2);
            })
            ->addColumn('fixed_fee', function($vendor_orders) {
                // return number_format($vendor_orders->admin_commission_percentage_amount, 2).' ('.number_format($vendor_orders->vendor->commission_percent,2).'%)';
                return number_format($vendor_orders->fixed_fee, 2);
            })
            ->addColumn('tip_amount', function($vendor_orders) {
                // return number_format($vendor_orders->admin_commission_percentage_amount, 2).' ('.number_format($vendor_orders->vendor->commission_percent,2).'%)';
                return !empty($vendor_orders->orderDetail)?number_format($vendor_orders->orderDetail->tip_amount, 2):0.00;
            })
            ->addColumn('order_status', function($vendor_orders) {
               return $vendor_orders->OrderStatusOption ? ($vendor_orders->OrderStatusOption->title ?? 'N/A') : "N/A";
            })
            ->addColumn('vendor_name',function($vendor_orders){
                return $vendor_orders->vendor ? __($vendor_orders->vendor->name) : '';
            })
            ->addColumn('markup_price',function($vendor_orders){
                return $vendor_orders->vendor ? __($vendor_orders->total_markup_price??0) : '0';
            })
            ->addColumn('payable_amount', function($vendor_orders) {
                return number_format($vendor_orders->payable_amount - $vendor_orders->total_markup_price??0, 2);
            })
            ->addColumn('payment_option_title',function($vendor_orders){
               
                $title = __(@$vendor_orders->orderDetail->paymentOption->title);
                if(@$vendor_orders->orderDetail->paymentOption->code == 'stripe'){
                    $title = __('Credit/Debit Card (Stripe)');
                }elseif(@$vendor_orders->orderDetail->paymentOption->code == 'kongapay'){
                    $title  = __('Pay Now');
                }elseif(@$vendor_orders->orderDetail->paymentOption->code == 'mvodafone'){
                    $title = __('Vodafone M-PAiSA');
                }
                elseif(@$vendor_orders->orderDetail->paymentOption->code == 'mobbex'){
                    $title = __('Mobbex');
                }
                elseif(@$vendor_orders->orderDetail->paymentOption->code == 'offline_manual'){
                    $json = json_decode($vendor_orders->orderDetail->paymentOption->credentials);
                    $title = $json->manule_payment_title;
                }
                return __($title);
            })
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
                if (!empty($request->get('search'))) {
                    $search = $request->get('search');
                    $instance->where(function($query) use($search) {
                        $query->whereHas('user', function($q) use($search){
                            $q->where('name', 'LIKE', '%'.$search.'%');
                        })
                        ->orWhereHas('vendor', function($q) use($search){
                            $q->where('name', 'LIKE', '%'.$search.'%');
                        })
                        ->orWhereHas('orderDetail', function($q) use($search){
                            $q->where('order_number', 'LIKE', '%'.$search.'%');
                        })
                        ->orWhereHas('orderstatus.OrderStatusOption', function($q) use($search){
                            $q->where('title', 'LIKE', '%'.$search.'%');
                        });
                    });
                }
            })
            ->rawColumns(['payment_option_title'])
            ->make(true);
    }

    public function export(Request $request) {
        return Excel::download(new OrderVendorListTaxExport($request), 'order_list.xlsx');
    }

    public function backendOrderRefund(Request $request){
        return view('backend.accounting.refund');
    }


    public function backendOrderRefundFilter(Request $request){
      
        $orderRefund = OrderRefund::whereHas('order')->get();
        $refunds=array();
        $c=1;
        foreach($orderRefund as $row){
            $refunds[$c]['user']=$row->user->name." | ".$row->user->email." | ".$row->user->phone_number;
            $refunds[$c]['amount']=$row->amount;
            $refunds[$c]['Refund_id']="None";
            $refunds[$c]['paid_to_wallet']=$row->paid_to_wallet ? "wallet": "";
            $refunds[$c]['order_id']=$row->order_id;
            $refunds[$c]['orderNumber']= isset($row->order) ? $row->order->order_number : '';
            $refunds[$c]['transactionId']=$row->transaction_id;
            $refunds[$c]['vendor_id']= OrderVendor::where('order_id',$row->order_id)->first()->vendor_id??'';
            $c++;
        }
        return Datatables::of($refunds)
        ->addIndexColumn()
        ->addColumn('view_url', function($refunds) {
            if(!empty($refunds['order_id']) && !empty($refunds['vendor_id'])){
                return route('order.show.detail', [$refunds['order_id'], $refunds['vendor_id']]);
            }else{
                return '';
            }
        })
        ->rawColumns(['action'])->make(true);
    }
}
