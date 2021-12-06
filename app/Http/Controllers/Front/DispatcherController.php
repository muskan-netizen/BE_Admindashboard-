<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Requests\DispatchOrderStatusUpdateRequest;
use App\Http\Controllers\Front\FrontController;
use Carbon\Carbon;
use Auth;
use Session;
use DB;
use App\Http\Traits\ApiResponser;
use App\Models\{Order, OrderProduct, OrderTax, Cart, CartAddon, CartProduct, CartProductPrescription, Product, OrderProductAddon, ClientPreference, ClientCurrency, OrderVendor, UserAddress, CartCoupon, VendorOrderStatus, VendorOrderDispatcherStatus, OrderStatusOption, Vendor, LoyaltyCard, NotificationTemplate, User, Payment, SubscriptionInvoicesUser, UserDevice, Client, UserVendor, LuxuryOption, EmailTemplate,ProductVariantSet};

class DispatcherController extends FrontController
{
    use ApiResponser;
   
   
    /******************    ---- order status update from dispatch (Need to dispatcher_status_option_id ) -----   ******************/
    public function dispatchOrderStatusUpdate(DispatchOrderStatusUpdateRequest $request, $domain = '', $web_hook_code)
    {
        try {
            DB::beginTransaction();
            $checkiftokenExist = OrderVendor::where('web_hook_code',$web_hook_code)->first();
            if($checkiftokenExist){
                $update = VendorOrderDispatcherStatus::updateOrCreate(['dispatcher_id' => null,
                    'order_id' =>  $checkiftokenExist->order_id,
                    'dispatcher_status_option_id' =>  $request->dispatcher_status_option_id,
                    'vendor_id' =>  $checkiftokenExist->vendor_id ]);
                    
                $dispatch_status = $request->dispatcher_status_option_id;

                    switch ($dispatch_status) {
                        case 2:
                            $request->status_option_id = 2;
                            break;
                      case 3:
                        $request->status_option_id = 4;
                        break;
                      case 4:
                        $request->status_option_id = 5;
                        break;
                      case 5:
                        $request->status_option_id = 6;
                        break;
                      default:
                       $request->status_option_id = null;
                    }
                    if(isset($request->status_option_id) && !empty($request->status_option_id)){
                        
                        $checkif= VendorOrderStatus::where(['order_id' =>  $checkiftokenExist->order_id,
                        'order_status_option_id' =>  $request->status_option_id,
                        'vendor_id' =>  $checkiftokenExist->vendor_id,
                        'order_vendor_id' =>  $checkiftokenExist->id])->count();
                        if($checkif == 0){
                            $update_vendor = VendorOrderStatus::updateOrCreate([
                                'order_id' =>  $checkiftokenExist->order_id,
                                'order_status_option_id' =>  $request->status_option_id,
                                'vendor_id' =>  $checkiftokenExist->vendor_id,
                                'order_vendor_id' =>  $checkiftokenExist->id ]);   
        
                                OrderVendor::where('vendor_id', $checkiftokenExist->vendor_id)->where('order_id', $checkiftokenExist->order_id)->update(['order_status_option_id' => $request->status_option_id]);
                 
                        }
                     
                    }
           
                

            if(isset($request->dispatch_traking_url) && !empty($request->dispatch_traking_url))
            {
                $update_tr = OrderVendor::where('web_hook_code',$web_hook_code)->update(['dispatch_traking_url' =>  $request->dispatch_traking_url]);
            }
            OrderVendor::where('vendor_id', $checkiftokenExist->vendor_id)->where('order_id', $checkiftokenExist->order_id)->update(['dispatcher_status_option_id' => $request->dispatcher_status_option_id]);
            
                    DB::commit();
                    $message = "Order status updated.";
                    return $this->successResponse($update, $message);
                   
            }else{
                DB::rollback();
                $message = "Invalid Order Token";
                return $this->errorResponse($message, 400);
               }
            
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), $e->getCode());
            
        }
    }


    /******************    ---- pickup delivery status update (Need to dispatcher_status_option_id ) -----   ******************/
    public function dispatchPickupDeliveryUpdate(Request $request, $domain = '', $web_hook_code)
    {
        try {
            DB::beginTransaction();
            $checkiftokenExist = OrderVendor::where('web_hook_code',$web_hook_code)->first();
            if($checkiftokenExist){

                $dispatch_status = $request->dispatcher_status_option_id;

                switch ($dispatch_status) {
                  case 2:
                        $request->status_option_id = 2;
                        break;
                  case 3:
                    $request->status_option_id = 4;
                    break;
                  case 4:
                    $request->status_option_id = 5;
                    break;
                  case 5:
                    $request->status_option_id = 6;
                    break;
                  default:
                   $request->status_option_id = null;
                }
                if(isset($request->status_option_id) && !empty($request->status_option_id)){
                    
                    $checkif= VendorOrderStatus::where(['order_id' =>  $checkiftokenExist->order_id,
                    'order_status_option_id' =>  $request->status_option_id,
                    'vendor_id' =>  $checkiftokenExist->vendor_id,
                    'order_vendor_id' =>  $checkiftokenExist->id])->count();
                    if($checkif == 0){
                        $update_vendor = VendorOrderStatus::updateOrCreate([
                            'order_id' =>  $checkiftokenExist->order_id,
                            'order_status_option_id' =>  $request->status_option_id,
                            'vendor_id' =>  $checkiftokenExist->vendor_id,
                            'order_vendor_id' =>  $checkiftokenExist->id ]);   
    
                            OrderVendor::where('vendor_id', $checkiftokenExist->vendor_id)->where('order_id', $checkiftokenExist->order_id)->update(['order_status_option_id' => $request->status_option_id]);
             
                    }
                }

                $update = VendorOrderDispatcherStatus::updateOrCreate(['dispatcher_id' => null,
                    'order_id' =>  $checkiftokenExist->order_id,
                    'dispatcher_status_option_id' =>  $request->dispatcher_status_option_id,
                    'vendor_id' =>  $checkiftokenExist->vendor_id ]);

            if(isset($request->dispatch_traking_url) && !empty($request->dispatch_traking_url))
            {
                $update_tr = OrderVendor::where('web_hook_code',$web_hook_code)->update(['dispatch_traking_url' =>  $request->dispatch_traking_url]);
            }

            OrderVendor::where('vendor_id', $checkiftokenExist->vendor_id)->where('order_id', $checkiftokenExist->order_id)->update(['dispatcher_status_option_id' => $request->dispatcher_status_option_id]);
          
              
            
                    DB::commit();
                    $message = "Order status updated.";
                    return $this->successResponse($update, $message);
                   
            }else{
                DB::rollback();
                $message = "Invalid Order Token";
                return $this->errorResponse($message, 400);
               }
            
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), $e->getCode());
            
        }
    }



    /******************    ---- share all details of order for dispatcher -----   ******************/
    public function dispatchOrderDetails(Request $request, $domain = '', $web_hook_code)
    {
        try {
            $user = Auth::user();
            $order_item_count = 0;
            $order_vendor = OrderVendor::where('web_hook_code',$web_hook_code)->first();
            if(isset($order_vendor) && !empty($order_vendor)){
                $order = Order::where('id',$order_vendor->order_id)->first();
                $user = User::where('id',$order->user_id)->first();
                $language_id = $user->language;
                $order_id = $order_vendor->order_id;
                $vendor_id = $order_vendor->vendor_id;
                if ($vendor_id) {
                    $order = Order::with([
                        'vendors' => function ($q) use ($vendor_id) {
                            $q->where('vendor_id', $vendor_id);
                        },
                        'vendors.dineInTable.translations' => function ($qry) use ($language_id) {
                            $qry->where('language_id', $language_id);
                        }, 'vendors.dineInTable.category',
                        'vendors.products' => function ($q) use ($vendor_id) {
                            $q->where('vendor_id', $vendor_id);
                        },
                        'vendors.products.translation' => function ($q) use ($language_id) {
                            $q->select('id', 'product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                            $q->where('language_id', $language_id);
                        },
                        'vendors.products.pvariant.vset.optionData.trans', 'vendors.products.addon', 'vendors.coupon', 'address', 'vendors.products.productRating', 'vendors.allStatus'
                    ])
                    ->where(function ($q1) {
                        $q1->where('payment_status', 1)->whereNotIn('payment_option_id', [1]);
                        $q1->orWhere(function ($q2) {
                            $q2->where('payment_option_id', 1);
                        });
                    })
                    ->where('id', $order_id)->select('*','id as total_discount_calculate')->first();
                } else {
                    $order = Order::with(
                        [
                            'vendors.vendor',
                            'vendors.products.translation' => function ($q) use ($language_id) {
                                $q->select('id', 'product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                                $q->where('language_id', $language_id);
                            },
                            'vendors.products.pvariant.vset.optionData.trans', 'vendors.products.addon', 'vendors.coupon', 'address', 'vendors.products.productRating',
                            'vendors.dineInTable.translations' => function ($qry) use ($language_id) {
                                $qry->where('language_id', $language_id);
                            }, 'vendors.dineInTable.category'
                        ]
                    )
                    ->where(function ($q1) {
                        $q1->where('payment_status', 1)->whereNotIn('payment_option_id', [1]);
                        $q1->orWhere(function ($q2) {
                            $q2->where('payment_option_id', 1);
                        });
                    })
                    ->where('user_id', $user->id)->where('id', $order_id)->select('*','id as total_discount_calculate')->first();
                }
                if ($order) {
                    $order->user_name = $order->user->name;
                    $order->user_image = $order->user->image;
                    $order->payment_option_title = __($order->paymentOption->title);
                    $order->created_date = dateTimeInUserTimeZone($order->created_at, $user->timezone);
                    $order->tip_amount = $order->tip_amount;
                    $order->tip = array(
                        ['label' => '5%', 'value' => number_format((0.05 * ($order->payable_amount - $order->total_discount_calculate)), 2, '.', '')],
                        ['label' => '10%', 'value' => number_format((0.1 * ($order->payable_amount - $order->total_discount_calculate)), 2, '.', '')],
                        ['label' => '15%', 'value' => number_format((0.15 * ($order->payable_amount - $order->total_discount_calculate)), 2, '.', '')]
                    );
                    foreach ($order->vendors as $vendor) {
                        $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order_id)->where('vendor_id', $vendor->vendor->id)->orderBy('id', 'DESC')->first();
                        if ($vendor_order_status) {
                            $vendor->order_status =  ['current_status' => ['id' => $vendor_order_status->OrderStatusOption->id, 'title' => __($vendor_order_status->OrderStatusOption->title)]];
                        } else {
                            $vendor->current_status = null;
                        }
                        $couponData = [];
                        $payable_amount = 0;
                        $discount_amount = 0;
                        $product_addons = [];
                        $vendor->vendor_name = $vendor->vendor->name;
                        foreach ($vendor->products as  $product) {
                            $product_addons = [];
                            $variant_options = [];
                            $order_item_count += $product->quantity;
                            $product->image_path = $product->media->first() ? $product->media->first()->image->path : $product->image;
                            if ($product->pvariant) {
                                foreach ($product->pvariant->vset as $variant_set_option) {
                                    $variant_options[] = array(
                                        'option' => $variant_set_option->optionData->trans->title,
                                        'title' => $variant_set_option->variantDetail->trans->title,
                                    );
                                }
                            }
                            $product->variant_options = $variant_options;
                            if (!empty($product->addon)) {
                                foreach ($product->addon as $addon) {
                                    $product_addons[] = array(
                                        'addon_id' =>  $addon->addon_id,
                                        'addon_title' =>  $addon->set->title,
                                        'option_title' =>  $addon->option->title,
                                    );
                                }
                            }
                            $product->product_addons = $product_addons;
                        }
                        if($vendor->delivery_fee > 0){
                            $order_pre_time = ($vendor->order_pre_time > 0) ? $vendor->order_pre_time : 0;
                            $user_to_vendor_time = ($vendor->user_to_vendor_time > 0) ? $vendor->user_to_vendor_time : 0;
                            $ETA = $order_pre_time + $user_to_vendor_time;
                            $vendor->ETA = ($ETA > 0) ? $this->formattedOrderETA($ETA, $vendor->created_at, $order->scheduled_date_time,$user) : dateTimeInUserTimeZone($vendor->created_at, $user->timezone);
                        }
                        if($vendor->dineInTable){
                            $vendor->dineInTableName = $vendor->dineInTable->translations->first() ? $vendor->dineInTable->translations->first()->name : '';
                            $vendor->dineInTableCapacity = $vendor->dineInTable->seating_number;
                            $vendor->dineInTableCategory = $vendor->dineInTable->category->title; //$vendor->dineInTable->category->first() ? $vendor->dineInTable->category->first()->title : '';
                        }
                    }
                    if(!empty($order->scheduled_date_time)){
                        $order->scheduled_date_time = dateTimeInUserTimeZone($order->scheduled_date_time, $user->timezone);
                    }
                    $luxury_option_name = '';
                    if($order->luxury_option_id > 0){
                        $luxury_option = LuxuryOption::where('id', $order->luxury_option_id)->first();
                        if($luxury_option->title == 'takeaway'){
                            $luxury_option_name = $this->getNomenclatureName('Takeaway', $user->language, false);
                        }elseif($luxury_option->title == 'dine_in'){
                            $luxury_option_name = 'Dine-In';
                        }else{
                            $luxury_option_name = 'Delivery';
                        }
                    }
                    $order->luxury_option_name = $luxury_option_name;
                    $order->order_item_count = $order_item_count;
                }
               
    
                return $this->successResponse($order, null, 201);
            }
           
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
