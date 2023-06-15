<?php

namespace App\Http\Controllers\Front;

use Auth;
use Carbon\Carbon;
use App\Models\UserVendor;
use App\Models\OrderVendor;
use App\Models\LuxuryOption;
use Illuminate\Http\Request;
use App\Models\ClientPreference;
use App\Http\Traits\{OrderTrait};
use App\Models\OrderStatusOption;
use App\Models\VendorOrderStatus;
use App\Http\Controllers\Controller;

class RentalOrderController extends Controller
{
    use OrderTrait;
    public function rentalOrders(Request $request)
    {
        $user = Auth::user();
        $order_status_options = [];
        $paginate = $request->has('limit') ? $request->limit : 12;
        $type = $request->has('type') ? $request->type : 'upcoming';
        // dd($type);
        $user_type = $request->has('user_type') ? $request->user_type : '';
        $orders = OrderVendor::with('products')->orderBy('id', 'DESC');
        $additionalPreference = getAdditionalPreference(['is_service_product_price_from_dispatch']);
        $vendorUser = UserVendor::select('vendor_id')->where('user_id', $user->id)->first();
        if(@$vendorUser){
            if ($user_type == 'borrower') {
                $orders->where('user_id', $user->id);
            } elseif ($user_type == 'lender') {
                $orders->where('vendor_id', $vendorUser->vendor_id);
            } else {
                $orders->where(function ($q) use ($vendorUser, $user) {
                    $q->where('vendor_id', $vendorUser->vendor_id)->orWhere('user_id', $user->id);
                });
            }
            switch ($type) {
                case 'all': // which order not assign yet indriver

                    $orders->whereHas('products');
                    break;
                case 'upcoming': // which order not assign yet indriver

                    $orders->whereHas('products');
                    $orders->whereIn('order_status_option_id', [1, 2]);
                    break;
                case 'ongoing': // which order not assign yet indriver

                    $orders->whereHas('products');
                    $orders->whereIn('order_status_option_id', [4]);
                    break;
                case 'pending': // which order not assign yet indriver

                    $orders->whereHas('products', function ($q1) {
                        $q1->where('dispatcher_status_option_id', 1);
                    });
                    break;
                case 'active':
                    $orders->whereNotIn('order_status_option_id', [6, 3, 9]);
                    $orders->whereHas('products', function ($q) use ($additionalPreference) {
                        if ($additionalPreference['is_service_product_price_from_dispatch'] == 1) {
                            $q->whereNotIn('dispatcher_status_option_id', [1, 5, 6]); //1=pending,5= complete,6 reject
                        }
                    });
                    break;
                case 'past':
                    $orders->whereIn('order_status_option_id', [6, 3, 9]);
                    if ($additionalPreference['is_service_product_price_from_dispatch'] == 1) {
                        $orders->whereHas('products', function ($q) {
                            $q->where('dispatcher_status_option_id', 5); //1=pending,5= complete,6 reject
                        });
                    }
                    break;
                case 'schedule':
                    $order_status_options = [10];
                    $orders->whereHas('status', function ($query) use ($order_status_options) {
                        $query->whereIn('order_status_option_id', $order_status_options);
                    });
                    break;
            }
            $orders = $orders->with([
                'orderDetail.editingInCart',
                'vendor:id,name,logo,banner,return_request,cancel_order_in_processing',
                'products.productReturn',
                'exchanged_of_order.orderDetail',
                'exchanged_to_order.orderDetail',
                'cancel_request',
                'products.Routes',
                'products.order_product_status',
                'products.product.category.categoryDetail' => function ($q) {
                    $q->select('id', 'type_id');
                },
                'products.product.translation'
            ])->paginate($paginate);
            $orders = $this->orderlistLoop($orders, $user, $request);
        }else{
            $orders = [];
        }
        // pr($orders);
        return view('frontend.account.rental-orders')->with([]);
    }

    public function orderlistLoop($orders,   $user ,$request){
        $additionalPreferences   =  @getAdditionalPreference(['is_postpay_enable','is_order_edit_enable','order_edit_before_hours']);
        $is_postpay_enable       =  $additionalPreferences['is_postpay_enable'];
        $is_order_edit_enable    =  $additionalPreferences['is_order_edit_enable'];
        $order_edit_before_hours =  $additionalPreferences['order_edit_before_hours'];
        $editlimit_datetime = Carbon::now()->addHours($order_edit_before_hours)->toDateTimeString();
        $dispatch_domain_OnDemand = $this->getDispatchOnDemandDomain();
        $dispatch_domain  = [];
        if ($dispatch_domain_OnDemand && $dispatch_domain_OnDemand != false) {
            $dispatch_domain = [
                'service_key'      => $dispatch_domain_OnDemand->dispacher_home_other_service_key,
                'service_key_code' => $dispatch_domain_OnDemand->dispacher_home_other_service_key_code,
                'service_key_url'  => $dispatch_domain_OnDemand->dispacher_home_other_service_key_url,
                'service_type'     => 'on_demand'
            ];
        }
        foreach ($orders as $order) {
            if(@$order->order_id){
                $order_item_count = 0;
                $order->user_name = $user->name;
                $order->user_image = $user->image;
                $total_total_payable = $order->orderDetail->total_amount + $order->orderDetail->wallet_amount_used + $order->orderDetail->loyalty_amount_saved + $order->orderDetail->taxable_amount + $order->orderDetail->total_delivery_fee + $order->orderDetail->tip_amount + $order->orderDetail->total_service_fee - $order->orderDetail->total_discount;
                $order->date_time = dateTimeInUserTimeZone($order->orderDetail->created_at, $user->timezone);
                $order->payment_option_title = ($order->orderDetail->wallet_amount_used >= ceil($total_total_payable)) ? __("Wallet") : __($order->orderDetail->paymentOption->title ?? '');
                $order->order_number = $order->orderDetail->order_number;
                $order->schedule_pickup = date('d/m/Y',strtotime($order->orderDetail->schedule_pickup));
                $order->scheduled_slot  = $order->orderDetail->scheduled_slot;
                $order->schedule_dropoff = date('d/m/Y',strtotime($order->orderDetail->schedule_dropoff));
                $order->dropoff_scheduled_slot  = $order->orderDetail->dropoff_scheduled_slot;
                $order->payable_amount = $order->total_price;
                if(checkColumnExists('orders', 'is_postpay')){
                    $order->is_postpay = (isset($request->is_postpay))?$request->is_postpay:0;
                }
                if(checkColumnExists('orders', 'is_edited')){
                    $order->is_edited   = (isset($order->orderDetail->is_edited)) ? $order->orderDetail->is_edited : 0;
                }
                if(!empty($order->orderDetail->scheduled_date_time) && $is_order_edit_enable == 1 && $order_edit_before_hours > 0 && ($order->orderDetail->payment_option_id==1 || $order->orderDetail->payment_status !=1)){
                    if((strtotime($order->orderDetail->scheduled_date_time) - strtotime($editlimit_datetime)) > 0){
                        $order->is_editable  = 1;
                    }else{
                        $order->is_editable  = 0;
                    }
                }else{
                    $order->is_editable  = 0;
                }

                if(!empty($order->orderDetail->editingInCart)){
                    $order->is_editable  = 2;
                }

                $product_details = [];
                $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order->orderDetail->id)->where('vendor_id', $order->vendor_id)->orderBy('id', 'DESC')->first();
                if ($vendor_order_status) {
                    $order_sts = OrderStatusOption::where('id',$order->order_status_option_id)->first();
                // $order->order_status =  ['current_status' => ['id' => $vendor_order_status->OrderStatusOption->id, 'title' => __($vendor_order_status->OrderStatusOption->title)]];
                if(@$order->exchanged_to_order->order_status_option_id && $order->exchanged_to_order->order_status_option_id== 6){
                    $order->order_status =  ['current_status' => ['id' => 6, 'title' => __("Replaced")]];
                    // $order->order_status->current_status->title = "Replaced";
                    }else{
                        $order->order_status =  ['current_status' => ['id' => @$order_sts->id ?? '', 'title' => __(@$order_sts->title)]];
                    }

                } else {
                    $order->current_status = null;
                }
                $return_request_status = 0;
                $returnable = 0;
                $replaceable = 0;

                foreach ($order->products as $product) {
                    $dispatch_agent_id = $product->dispatch_agent_id;
                    $dispatcher_traking_url = $product->routes->isNotEmpty() ? ( $product->routes->first() ? $product->routes->first()->dispatch_traking_url : '' ) : '' ;
                    $dispatcher_agent = [];
                    $category_type_id = @$product->product->category->categoryDetail->type_id ?? '';
                    if( ( $category_type_id ==8 && !empty($dispatch_domain) )  && ($dispatch_agent_id && $dispatcher_traking_url ) ){
                        $dispatch_domain['driver_id'] = $dispatch_agent_id;
                        $dispatcher_agent = $this->getAgentDetailFromDispatcher($dispatch_domain);

                    }

                    if($this->checkOrderDaysForReturn($order, @$product->product->return_days) && $order->is_exchanged_or_returned==0){


                        if(@$product->product->replaceable && $product->product->replaceable == 1){
                            $replaceable = $product->product->replaceable;
                        }

                        if(@$product->product->returnable && $order->vendor->return_request == 1 && $product->product->returnable == 1){
                            $returnable = $product->product->returnable;
                        }
                    }
                    // dd($product->productReturn->status);
                    if(@$product->productReturn &&  $return_request_status== 0 && $order->is_exchanged_or_returned!=1){
                        if($product->productReturn->status == 'Accepted'){
                            $return_request_status = 1;
                        }
                        if($product->productReturn->status == 'Rejected'){
                            $return_request_status = 2;
                        }
                        if($product->productReturn->status == 'Pending'){
                            $return_request_status = 3;
                        }
                    }
                    $order_item_count += $product->quantity;

                    $product_details[] = array(
                        'image_path' => $product->media->first() ? $product->media->first()->image->path : $product->image,
                        'price' => $product->price,
                        'qty' => $product->quantity,
                        'category_type' => $product->product->category->categoryDetail->type->title ?? '',
                        'translation' => $product->product->translation ?? '',
                        'product_id' => $product->product_id,
                        'title' => $product->product_name,
                        'routes' => $product->routes,
                        'dispatcher_agent' => $dispatcher_agent,
                        'scheduled_date_time' => dateTimeInUserTimeZone($product->scheduled_date_time, $user->timezone),
                        'schedule_slot' => $product->schedule_slot
                    );

                }
                if ($order->delivery_fee > 0) {
                    $order_pre_time = ($order->order_pre_time > 0) ? $order->order_pre_time : 0;
                    $user_to_vendor_time = ($order->user_to_vendor_time > 0) ? $order->user_to_vendor_time : 0;
                    $ETA = $order_pre_time + $user_to_vendor_time;
                    $order->ETA = ($ETA > 0) ? $this->formattedOrderETA($ETA, $order->created_at, $order->orderDetail->scheduled_date_time) : dateTimeInUserTimeZone($order->created_at, $user->timezone);
                }
                if (!empty($order->orderDetail->scheduled_date_time)) {
                    $order->scheduled_date_time = dateTimeInUserTimeZone($order->orderDetail->scheduled_date_time, $user->timezone);
                }
                if(!empty($order->orderDetail->scheduled_slot) ){
                    $slot_time = explode("-",$order->orderDetail->scheduled_slot);
                    $start_time = $slot_time[0];
                    $end_time = !empty($slot_time[1]) ? $slot_time[1]: $slot_time[0];
                    $order->schedule_slot =date('d-m-Y h:i A',strtotime( date('Y-m-d',strtotime($order->scheduled_date_time)). " " . $start_time)) . ' - ' . date('h:i A',strtotime($end_time));
                }
                $luxury_option_name = '';
                if ($order->orderDetail->luxury_option_id > 0) {
                    $luxury_option = LuxuryOption::where('id', $order->orderDetail->luxury_option_id)->first();

                    if ($luxury_option->title == 'takeaway') {
                        $luxury_option_name = $this->getNomenclatureName('Takeaway', $user->language, false);
                    } elseif ($luxury_option->title == 'dine_in') {
                        $luxury_option_name = __('Dine-In');
                    }elseif ($luxury_option->title == 'on_demand') {
                        $luxury_option_name = $this->getNomenclatureName('Services', $user->language, false);
                    } else {
                        //$luxury_option_name = __('Delivery');
                        $luxury_option_name = getNomenclatureName($luxury_option->title);
                    }
                }
                $order->is_long_term  =0;

                $order->is_long_term  = $order->orderDetail->is_long_term;

                $order->luxury_option_name = $luxury_option_name;
                $order->luxury_option_name = $luxury_option_name;
                $order->product_details = $product_details;
                $order->item_count = $order_item_count;
                $order->return_request_status = $return_request_status;


                //product returnable and replaceble

                $order->returnable = $returnable;
                $order->replaceable = $replaceable;

                unset($order->user);
                unset($order->products);
                unset($order->paymentOption);
                unset($order->payment_option_id);
                unset($order->orderDetail);
            }
        }
        return $orders;
    }

    public function getDispatchOnDemandDomain()
    {
        $preference = ClientPreference::first();
        if ($preference->need_dispacher_home_other_service == 1 && !empty($preference->dispacher_home_other_service_key) && !empty($preference->dispacher_home_other_service_key_code) && !empty($preference->dispacher_home_other_service_key_url))
            return $preference;
        else
            return false;
    }
}