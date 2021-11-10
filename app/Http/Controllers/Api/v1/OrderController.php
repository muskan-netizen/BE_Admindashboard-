<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use GuzzleHttp\Client as GCLIENT;
use App\Http\Controllers\Api\v1\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\OrderStoreRequest;
use Log;
use App\Models\{Order, OrderProduct, Cart, CartAddon, CartProduct, CartProductPrescription, Product, OrderProductAddon, ClientPreference, ClientCurrency, OrderVendor, UserAddress, CartCoupon, VendorOrderStatus, VendorOrderDispatcherStatus, OrderStatusOption, Vendor, LoyaltyCard, NotificationTemplate, User, Payment, SubscriptionInvoicesUser, UserDevice, Client, UserVendor, LuxuryOption};
use App\Models\AutoRejectOrderCron;

class OrderController extends BaseController {
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDeliveryFeeDispatcher($vendor_id)
    {
        try {
            $dispatch_domain = $this->checkIfLastMileOn();
            if ($dispatch_domain && $dispatch_domain != false) {
                $customer = User::find(Auth::id());
                $cus_address = UserAddress::where('user_id', Auth::id())->orderBy('is_primary', 'desc')->first();
                if ($cus_address) {
                    $tasks = array();
                    $vendor_details = Vendor::find($vendor_id);
                    $location[] = array(
                        'latitude' => $vendor_details->latitude ?? '',
                        'longitude' => $vendor_details->longitude ?? ''
                    );
                    $location[] = array(
                        'latitude' => $cus_address->latitude ?? '',
                        'longitude' => $cus_address->longitude ?? ''
                    );
                    $postdata =  ['locations' => $location];
                    $client = new GClient([
                        'headers' => [
                            'personaltoken' => $dispatch_domain->delivery_service_key,
                            'shortcode' => $dispatch_domain->delivery_service_key_code,
                            'content-type' => 'application/json'
                        ]
                    ]);
                    $url = $dispatch_domain->delivery_service_key_url;
                    $res = $client->post(
                        $url . '/api/get-delivery-fee',
                        ['form_params' => ($postdata)]
                    );
                    $response = json_decode($res->getBody(), true);
                    if ($response && $response['message'] == 'success') {
                        return $response['total'];
                    }
                }
            }
        } catch (\Exception $e) {
        }
    }
    # check if last mile delivery on 
    public function checkIfLastMileOn()
    {
        $preference = ClientPreference::first();
        if ($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
            return $preference;
        else
            return false;
    }
    public function postPlaceOrder(OrderStoreRequest $request)
    {
        try {
            $total_amount = 0;
            $total_discount = 0;
            $taxable_amount = 0;
            $payable_amount = 0;
            $user = Auth::user();
            if ($user) {
                DB::beginTransaction();
                $subscription_features = array();
                $now = Carbon::now()->toDateTimeString();
                $user_subscription = SubscriptionInvoicesUser::with('features')
                    ->select('id', 'user_id', 'subscription_id')
                    ->where('user_id', $user->id)
                    ->where('end_date', '>', $now)
                    ->orderBy('end_date', 'desc')->first();
                if ($user_subscription) {
                    foreach ($user_subscription->features as $feature) {
                        $subscription_features[] = $feature->feature_id;
                    }
                }
                $loyalty_amount_saved = 0;
                $redeem_points_per_primary_currency = '';
                $loyalty_card = LoyaltyCard::where('status', '0')->first();
                if ($loyalty_card) {
                    $redeem_points_per_primary_currency = $loyalty_card->redeem_points_per_primary_currency;
                }
                $client_preference = ClientPreference::first();
                // if ($client_preference->verify_email == 1) {
                //     if ($user->is_email_verified == 0) {
                //         return response()->json(['error' => 'Your account is not verified.'], 404);
                //     }
                // }
                if ($client_preference->verify_phone == 1) {
                    if ($user->is_phone_verified == 0) {
                        return response()->json(['error' => 'Your phone is not verified.'], 404);
                    }
                }
                $user_address = UserAddress::where('id', $request->address_id)->first();
                if (!$user_address) {
                    return response()->json(['error' => 'Invalid address id.'], 404);
                }
                $action = ($request->has('type')) ? $request->type : 'delivery';
                $luxury_option = LuxuryOption::where('title', $action)->first();
                $cart = Cart::where('user_id', $user->id)->first();
                if ($cart) {
                    $loyalty_points_used;
                    $order_loyalty_points_earned_detail = Order::where('user_id', $user->id)->select(DB::raw('sum(loyalty_points_earned) AS sum_of_loyalty_points_earned'), DB::raw('sum(loyalty_points_used) AS sum_of_loyalty_points_used'))->first();
                    if ($order_loyalty_points_earned_detail) {
                        $loyalty_points_used = $order_loyalty_points_earned_detail->sum_of_loyalty_points_earned - $order_loyalty_points_earned_detail->sum_of_loyalty_points_used;
                        if ($loyalty_points_used > 0 && $redeem_points_per_primary_currency > 0) {
                            $loyalty_amount_saved = $loyalty_points_used / $redeem_points_per_primary_currency;
                        }
                    }
                    $order = new Order;
                    $order->user_id = $user->id;
                    $order->order_number = generateOrderNo();
                    $order->address_id = $request->address_id;
                    $order->payment_option_id = $request->payment_option_id;
                    $order->save();
                    $customerCurrency = ClientCurrency::where('currency_id', $user->currency)->first();
                    $clientCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
                    $cart_products = CartProduct::with('product.pimage', 'product.variants', 'product.taxCategory.taxRate', 'coupon', 'product.addon')->where('cart_id', $cart->id)->where('status', [0, 1])->where('cart_id', $cart->id)->orderBy('created_at', 'asc')->get();
                    $total_subscription_discount = $total_delivery_fee = 0;
                    foreach ($cart_products->groupBy('vendor_id') as $vendor_id => $vendor_cart_products) {
                        $delivery_fee = 0;
                        $deliver_charge = $delivery_fee_charges = 0.00;
                        $delivery_count = 0;
                        $vendor_payable_amount = 0;
                        $vendor_discount_amount = 0;
                        $order_vendor = new OrderVendor;
                        $order_vendor->status = 0;
                        $order_vendor->user_id = $user->id;
                        $order_vendor->order_id = $order->id;
                        $order_vendor->vendor_id = $vendor_id;
                        $order_vendor->vendor_dinein_table_id = $vendor_cart_products->unique('vendor_dinein_table_id')->first()->vendor_dinein_table_id;
                        $order_vendor->save();
                        foreach ($vendor_cart_products as $vendor_cart_product) {
                            $variant = $vendor_cart_product->product->variants->where('id', $vendor_cart_product->variant_id)->first();
                            $quantity_price = 0;
                            $divider = (empty($vendor_cart_product->doller_compare) || $vendor_cart_product->doller_compare < 0) ? 1 : $vendor_cart_product->doller_compare;
                            $price_in_currency = $variant->price / $divider;
                            $price_in_dollar_compare = $price_in_currency * $clientCurrency->doller_compare;
                            $quantity_price = $price_in_dollar_compare * $vendor_cart_product->quantity;
                            $payable_amount = $payable_amount + $quantity_price;
                            $vendor_payable_amount = $vendor_payable_amount + $quantity_price;
                            $product_taxable_amount = 0;
                            $product_payable_amount = 0;
                            $vendor_taxable_amount = 0;
                            if ($vendor_cart_product->product['taxCategory']) {
                                foreach ($vendor_cart_product->product['taxCategory']['taxRate'] as $tax_rate_detail) {
                                    $rate = round($tax_rate_detail->tax_rate);
                                    $tax_amount = ($price_in_dollar_compare * $rate) / 100;
                                    $product_tax = $quantity_price * $rate / 100;
                                    $taxable_amount = $taxable_amount + $product_tax;
                                    $payable_amount = $payable_amount + $product_tax;
                                    $vendor_payable_amount = $vendor_payable_amount;
                                }
                            }
                            if ($action == 'delivery') {
                                if ((!empty($vendor_cart_product->product->Requires_last_mile)) && ($vendor_cart_product->product->Requires_last_mile == 1)) {
                                    $delivery_fee = $this->getDeliveryFeeDispatcher($vendor_cart_product->vendor_id, $user->id);
                                    if (!empty($delivery_fee) && $delivery_count == 0) {
                                        $delivery_count = 1;
                                        $vendor_cart_product->delivery_fee = number_format($delivery_fee, 2, '.', '');
                                        // $payable_amount = $payable_amount + $delivery_fee;
                                        $delivery_fee_charges = $delivery_fee;
                                        $latitude = $request->header('latitude');
                                        $longitude = $request->header('longitude');
                                        $vendor_cart_product->vendor = $this->getVendorDistanceWithTime($latitude, $longitude, $vendor_cart_product->vendor, $client_preference);
                                        $order_vendor->order_pre_time = ($vendor_cart_product->vendor->order_pre_time > 0) ? $vendor_cart_product->vendor->order_pre_time : 0;
                                        if($vendor_cart_product->vendor->timeofLineOfSightDistance > 0){
                                            $order_vendor->user_to_vendor_time = $vendor_cart_product->vendor->timeofLineOfSightDistance - $order_vendor->order_pre_time;
                                        }
                                    }
                                }
                            }
                            $vendor_taxable_amount += $taxable_amount;
                            $total_amount += $vendor_cart_product->quantity * $variant->price;
                            $order_product = new OrderProduct;
                            $order_product->order_vendor_id = $order_vendor->id;
                            $order_product->order_id = $order->id;
                            $order_product->price = $variant->price;
                            $order_product->quantity = $vendor_cart_product->quantity;
                            $order_product->vendor_id = $vendor_cart_product->vendor_id;
                            $order_product->product_id = $vendor_cart_product->product_id;
                            $order_product->created_by = $vendor_cart_product->created_by;
                            $order_product->variant_id = $vendor_cart_product->variant_id;
                            $order_product->product_name = $vendor_cart_product->product->title ?? $vendor_cart_product->product->sku;
                            $order_product->product_dispatcher_tag = $vendor_cart_product->product->tags;
                            if ($vendor_cart_product->product->pimage) {
                                $order_product->image = $vendor_cart_product->product->pimage->first() ? $vendor_cart_product->product->pimage->first()->path : '';
                            }
                            $order_product->save();
                            if (!empty($vendor_cart_product->addon)) {
                                foreach ($vendor_cart_product->addon as $ck => $addon) {
                                    $opt_quantity_price = 0;
                                    $opt_price_in_currency = $addon->option->price;
                                    $opt_price_in_doller_compare = $opt_price_in_currency * $clientCurrency->doller_compare;
                                    $opt_quantity_price = $opt_price_in_doller_compare * $order_product->quantity;
                                    $total_amount = $total_amount + $opt_quantity_price;
                                    $payable_amount = $payable_amount + $opt_quantity_price;
                                    $vendor_payable_amount = $vendor_payable_amount + $opt_quantity_price;
                                }
                            }
                            $cart_addons = CartAddon::where('cart_product_id', $vendor_cart_product->id)->get();
                            if ($cart_addons) {
                                foreach ($cart_addons as $cart_addon) {
                                    $orderAddon = new OrderProductAddon;
                                    $orderAddon->addon_id = $cart_addon->addon_id;
                                    $orderAddon->option_id = $cart_addon->option_id;
                                    $orderAddon->order_product_id = $order_product->id;
                                    $orderAddon->save();
                                }
                                if(($request->payment_option_id != 7) && ($request->payment_option_id != 6)){ // if not mobbex, payfast
                                    CartAddon::where('cart_product_id', $vendor_cart_product->id)->delete();
                                }
                            }
                        }
                        $coupon_id = null;
                        $coupon_name = null;
                        $actual_amount = $vendor_payable_amount;
                        if ($vendor_cart_product->coupon && !empty($vendor_cart_product->coupon->promo)) {
                            $coupon_id = $vendor_cart_product->coupon->promo->id;
                            $coupon_name = $vendor_cart_product->coupon->promo->name;
                            if ($vendor_cart_product->coupon->promo->promo_type_id == 2) {
                                $coupon_discount_amount = $vendor_cart_product->coupon->promo->amount;
                                $total_discount += $coupon_discount_amount;
                                $vendor_payable_amount -= $coupon_discount_amount;
                                $vendor_discount_amount += $coupon_discount_amount;
                            } else {
                                $coupon_discount_amount = ($quantity_price * $vendor_cart_product->coupon->promo->amount / 100);
                                $final_coupon_discount_amount = $coupon_discount_amount * $clientCurrency->doller_compare;
                                $total_discount += $final_coupon_discount_amount;
                                $vendor_payable_amount -= $final_coupon_discount_amount;
                                $vendor_discount_amount += $final_coupon_discount_amount;
                            }
                        }
                        $total_delivery_fee += $delivery_fee;
                        $order_vendor->coupon_id = $coupon_id;
                        $order_vendor->coupon_code = $coupon_name;
                        $order_vendor->order_status_option_id = 1;
                        $order_vendor->delivery_fee = $delivery_fee;
                        $order_vendor->subtotal_amount = $actual_amount;
                        $order_vendor->payable_amount = $vendor_payable_amount + $delivery_fee;
                        $order_vendor->taxable_amount = $vendor_taxable_amount;
                        $order_vendor->discount_amount = $vendor_discount_amount;
                        $order_vendor->payment_option_id = $request->payment_option_id;
                        $vendor_info = Vendor::where('id', $vendor_id)->first();
                        if ($vendor_info) {
                            if (($vendor_info->commission_percent) != null && $vendor_payable_amount > 0) {
                                $order_vendor->admin_commission_percentage_amount = round($vendor_info->commission_percent * ($vendor_payable_amount / 100), 2);
                            }
                            if (($vendor_info->commission_fixed_per_order) != null && $vendor_payable_amount > 0) {
                                $order_vendor->admin_commission_fixed_amount = $vendor_info->commission_fixed_per_order;
                            }
                        }
                        $order_vendor->save();
                        $order_status = new VendorOrderStatus();
                        $order_status->order_id = $order->id;
                        $order_status->vendor_id = $vendor_id;
                        $order_status->order_status_option_id = 1;
                        $order_status->order_vendor_id = $order_vendor->id;
                        $order_status->save();
                    }
                    $loyalty_points_earned = LoyaltyCard::getLoyaltyPoint($loyalty_points_used, $payable_amount);
                    if (in_array(1, $subscription_features)) {
                        $total_subscription_discount = $total_subscription_discount + $total_delivery_fee;
                    }
                    $total_discount = $total_discount + $total_subscription_discount;
                    $order->total_amount = $total_amount;
                    $order->total_discount = $total_discount;
                    $order->taxable_amount = $taxable_amount;
                    $payable_amount = $payable_amount + $total_delivery_fee - $total_discount;
                    if ($loyalty_amount_saved > 0) {
                        if ($loyalty_amount_saved > $payable_amount) {
                            $loyalty_amount_saved = $payable_amount;
                            $loyalty_points_used = $payable_amount * $redeem_points_per_primary_currency;
                        }
                    }
                    $payable_amount = $payable_amount - $loyalty_amount_saved;
                    $wallet_amount_used = 0;
                    if ($user->balanceFloat > 0) {
                        $wallet = $user->wallet;
                        $wallet_amount_used = $user->balanceFloat;
                        if ($wallet_amount_used > $payable_amount) {
                            $wallet_amount_used = $payable_amount;
                        }
                        $order->wallet_amount_used = $wallet_amount_used;
                        if ($wallet_amount_used > 0) {
                            $wallet->withdrawFloat($order->wallet_amount_used, ['Wallet has been <b>debited</b> for order number <b>' . $order->order_number . '</b>']);
                        }
                    }
                    $payable_amount = $payable_amount - $wallet_amount_used;
                    $tip_amount = 0;
                    if ((isset($request->tip)) && ($request->tip != '') && ($request->tip > 0)) {
                        $tip_amount = $request->tip;
                        $tip_amount = ($tip_amount / $customerCurrency->doller_compare) * $clientCurrency->doller_compare;
                        $order->tip_amount = number_format($tip_amount, 2);
                    }
                    $payable_amount = $payable_amount + $tip_amount;
                    $order->total_delivery_fee = $total_delivery_fee;
                    $order->loyalty_points_used = $loyalty_points_used;
                    $order->loyalty_amount_saved = $loyalty_amount_saved;
                    $order->loyalty_points_earned = $loyalty_points_earned['per_order_points'];
                    $order->loyalty_membership_id = $loyalty_points_earned['loyalty_card_id'];
                    $order->scheduled_date_time = $cart->schedule_type == 'schedule' ? $cart->scheduled_date_time : null;
                    $order->subscription_discount = $total_subscription_discount;
                    $order->luxury_option_id = $luxury_option->id;
                    $order->payable_amount = $payable_amount;
                    if ( ($payable_amount == 0) || (($request->has('transaction_id')) && (!empty($request->transaction_id))) ) {
                        $order->payment_status = 1;
                    }
                    $order->save();
                    $this->sendSuccessSMS($request, $order);
                    $ex_gateways = [6,7,8]; // if mobbex, payfast, yoco
                    if(!in_array($request->payment_option_id, $ex_gateways)){
                        Cart::where('id', $cart->id)->update(['schedule_type' => NULL, 'scheduled_date_time' => NULL]);
                        CartCoupon::where('cart_id', $cart->id)->delete();
                        CartProduct::where('cart_id', $cart->id)->delete();
                        CartProductPrescription::where('cart_id', $cart->id)->delete();
                    }
                    if (($request->payment_option_id != 1) && ($request->payment_option_id != 2) && ($request->has('transaction_id')) && (!empty($request->transaction_id))) {
                        Payment::insert([
                            'date' => date('Y-m-d'),
                            'order_id' => $order->id,
                            'transaction_id' => $request->transaction_id,
                            'balance_transaction' => $order->payable_amount,
                            'type' => 'cart'
                        ]);
                    }
                    $order = $order->with(['vendors:id,order_id,dispatch_traking_url,vendor_id', 'user_vendor', 'vendors.vendor'])->where('order_number', $order->order_number)->first();
                    if(!in_array($request->payment_option_id, $ex_gateways)){
                        $code = $request->header('code');
                        if (!empty($order->vendors)) {
                            foreach ($order->vendors as $vendor_value) {
                                $vendor_order_detail = $this->minimize_orderDetails_for_notification($order->id, $vendor_value->vendor_id);
                                $user_vendors = UserVendor::where(['vendor_id' => $vendor_value->vendor_id])->pluck('user_id');
                                $vendorDetail = $vendor_value->vendor;
                                if ($vendorDetail->auto_accept_order == 0 && $vendorDetail->auto_reject_time > 0) {
                                    $clientDetail = Client::on('mysql')->where(['code' => $client_preference->client_code])->first();
                                    AutoRejectOrderCron::on('mysql')->create(['database_host' => $clientDetail->database_path, 'database_name' => $clientDetail->database_name, 'database_username' => $clientDetail->database_username, 'database_password' => $clientDetail->database_password, 'order_vendor_id' => $vendor_value->id, 'auto_reject_time' => Carbon::now()->addMinute($vendorDetail->auto_reject_time)]);
                                }
                                $this->sendOrderPushNotificationVendors($user_vendors, $vendor_order_detail, $code);
                            }
                        }
                        $vendor_order_detail = $this->minimize_orderDetails_for_notification($order->id);
                        $super_admin = User::where('is_superadmin', 1)->pluck('id');
                        $this->sendOrderPushNotificationVendors($super_admin, $vendor_order_detail, $code);
                        // $user_admins = User::where(function ($query) {
                        //     $query->where(['is_superadmin' => 1]);
                        // })->pluck('id')->toArray();
                        // $user_vendors = [];
                        // if (!empty($order->user_vendor) && count($order->user_vendor) > 0) {
                        //     $user_vendors = $order->user_vendor->pluck('user_id')->toArray();
                        // }
                        // $order->admins = array_unique(array_merge($user_admins, $user_vendors));

                        // // $this->sendOrderNotification($user->id);
                        // $this->sendOrderPushNotificationVendors($order->admins, ['id' => $order->id], $code);
                    }

                    # if payment type cash on delivery or payment status is 'Paid'
                    if( ($order->payment_option_id == 1) || (($order->payment_option_id != 1) && ($order->payment_status == 1)) ){
                        # if vendor selected auto accept
                        $autoaccept = $this->autoAcceptOrderIfOn($order->id);
                    }

                    DB::commit();

                    return $this->successResponse($order, __('Order placed successfully.'), 201);
                }
            } else {
                return $this->errorResponse(['error' => __('Empty cart.')], 404);
            }
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    # if vendor selected auto accepted order  
    public function autoAcceptOrderIfOn($order_id)
    {
        $order_vendors = OrderVendor::where('order_id', $order_id)->whereHas('vendor', function ($q) {
            $q->where('auto_accept_order', 1);
        })->get();
         foreach ($order_vendors as $ov) {
             $request = $ov;

            DB::beginTransaction();
            //try {

            $request->order_id = $ov->order_id;
            $request->vendor_id = $ov->vendor_id;
            $request->order_vendor_id = $ov->id;
            $request->status_option_id = 2;
            $timezone = Auth::user()->timezone;
            $vendor_order_status_check = VendorOrderStatus::where('order_id', $request->order_id)->where('vendor_id', $request->vendor_id)->where('order_status_option_id', $request->status_option_id)->first();
             if (!$vendor_order_status_check) {
                $vendor_order_status = new VendorOrderStatus();
                $vendor_order_status->order_id = $request->order_id;
                $vendor_order_status->vendor_id = $request->vendor_id;
                $vendor_order_status->order_vendor_id = $request->order_vendor_id;
                $vendor_order_status->order_status_option_id = $request->status_option_id;
                $vendor_order_status->save();
                if ($request->status_option_id == 2) {
                    $order_dispatch = $this->checkIfanyProductLastMileon($request);
                    if ($order_dispatch && $order_dispatch == 1)
                        $stats = $this->insertInVendorOrderDispatchStatus($request);
                }
                OrderVendor::where('vendor_id', $request->vendor_id)->where('order_id', $request->order_id)->update(['order_status_option_id' => $request->status_option_id]);
                DB::commit();
                // $this->sendSuccessNotification(Auth::user()->id, $request->vendor_id);
            }
            // } catch(\Exception $e){
            // DB::rollback();
            // Log::info($e->getMessage());
            // }
        }
    }

    /// ******************  check If any Product Last Mile on   ************************ ///////////////
    public function checkIfanyProductLastMileon($request)
    {   
       

        $order_dispatchs = 2;
        $checkdeliveryFeeAdded = OrderVendor::where(['order_id' => $request->order_id, 'vendor_id' => $request->vendor_id])->first();
        $dispatch_domain = $this->getDispatchDomain();
        if ($dispatch_domain && $dispatch_domain != false) {
            if ($checkdeliveryFeeAdded && $checkdeliveryFeeAdded->delivery_fee > 0.00)
                $order_dispatchs = $this->placeRequestToDispatch($request->order_id, $request->vendor_id, $dispatch_domain);


            if ($order_dispatchs && $order_dispatchs == 1)
                return 1;
        }


        $dispatch_domain_ondemand = $this->getDispatchOnDemandDomain();
        if ($dispatch_domain_ondemand && $dispatch_domain_ondemand != false) {
        
            $ondemand = 0;
       
            foreach ($checkdeliveryFeeAdded->products as $key => $prod) {
                if (isset($prod->product_dispatcher_tag) && !empty($prod->product_dispatcher_tag) && $prod->product->category->categoryDetail->type_id == 8) {
                    $dispatch_domain_ondemand = $this->getDispatchOnDemandDomain();
                    if ($dispatch_domain_ondemand && $dispatch_domain_ondemand != false && $ondemand == 0  && $checkdeliveryFeeAdded->delivery_fee <= 0.00) {
                        $order_dispatchs = $this->placeRequestToDispatchOnDemand($request->order_id, $request->vendor_id, $dispatch_domain_ondemand);
                        if ($order_dispatchs && $order_dispatchs == 1) {
                            $ondemand = 1;
                            return 1;
                        }
                    }
                }
            }
        }

        return 2;
    }


    // place Request To Dispatch
    public function placeRequestToDispatch($order, $vendor, $dispatch_domain)
    {
        try {

            $order = Order::find($order);
            $customer = User::find($order->user_id);
            $cus_address = UserAddress::find($order->address_id);
            $tasks = array();
            if ($order->payment_method == 1) {
                $cash_to_be_collected = 'Yes';
                $payable_amount = $order->payable_amount;
            } else {
                $cash_to_be_collected = 'No';
                $payable_amount = 0.00;
            }
            $dynamic = uniqid($order->id . $vendor);
            $client = Client::orderBy('id','asc')->first();
            if(isset($client->custom_domain) && !empty($client->custom_domain) && $client->custom_domain != $client->sub_domain)
            $call_back_url = "https://".$client->custom_domain."/dispatch-order-status-update/".$dynamic;
            else
            $call_back_url = "https://".$client->sub_domain.env('SUBMAINDOMAIN')."/dispatch-order-status-update/".$dynamic;
         //   $call_back_url = route('dispatch-order-update', $dynamic);
            $vendor_details = Vendor::where('id', $vendor)->select('id', 'name', 'latitude', 'longitude', 'address')->first();
            $tasks = array();
            $meta_data = '';

            $team_tag = null;
            if (!empty($dispatch_domain->last_mile_team))
                $team_tag = $dispatch_domain->last_mile_team;


            $tasks[] = array(
                'task_type_id' => 1,
                'latitude' => $vendor_details->latitude ?? '',
                'longitude' => $vendor_details->longitude ?? '',
                'short_name' => '',
                'address' => $vendor_details->address ?? '',
                'post_code' => '',
                'barcode' => '',
            );

            $tasks[] = array(
                'task_type_id' => 2,
                'latitude' => $cus_address->latitude ?? '',
                'longitude' => $cus_address->longitude ?? '',
                'short_name' => '',
                'address' => $cus_address->address ?? '',
                'post_code' => $cus_address->pincode ?? '',
                'barcode' => '',
            );

            $postdata =  [
                'customer_name' => $customer->name ?? 'Dummy Customer',
                'customer_phone_number' => $customer->phone_number ?? rand(111111, 11111),
                'customer_email' => $customer->email ?? null,
                'recipient_phone' => $customer->phone_number ?? rand(111111, 11111),
                'recipient_email' => $customer->email ?? null,
                'task_description' => "Order From :" . $vendor_details->name,
                'allocation_type' => 'a',
                'task_type' => 'now',
                'cash_to_be_collected' => $payable_amount ?? 0.00,
                'barcode' => '',
                'order_team_tag' => $team_tag,
                'call_back_url' => $call_back_url ?? null,
                'task' => $tasks
            ];


            $client = new GCLIENT([
                'headers' => [
                    'personaltoken' => $dispatch_domain->delivery_service_key,
                    'shortcode' => $dispatch_domain->delivery_service_key_code,
                    'content-type' => 'application/json'
                ]
            ]);

            $url = $dispatch_domain->delivery_service_key_url;
            $res = $client->post(
                $url . '/api/task/create',
                ['form_params' => ($postdata)]
            );
            $response = json_decode($res->getBody(), true);
            if ($response && $response['task_id'] > 0) {
                $dispatch_traking_url = $response['dispatch_traking_url']??'';
                $up_web_hook_code = OrderVendor::where(['order_id' => $order->id, 'vendor_id' => $vendor])
                    ->update(['web_hook_code' => $dynamic,'dispatch_traking_url' => $dispatch_traking_url]);

                return 1;
            }
            return 2;
        } catch (\Exception $e) {
            return 2;
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }



    // place Request To Dispatch for On Demand
    public function placeRequestToDispatchOnDemand($order, $vendor, $dispatch_domain)
    {
        try {
           
            $order = Order::find($order);
            $customer = User::find($order->user_id);
            $cus_address = UserAddress::find($order->address_id);
            $tasks = array();
            if ($order->payment_method == 1) {
                $cash_to_be_collected = 'Yes';
                $payable_amount = $order->payable_amount;
            } else {
                $cash_to_be_collected = 'No';
                $payable_amount = 0.00;
            }
            $dynamic = uniqid($order->id . $vendor);
            $client = Client::orderBy('id','asc')->first();
            if(isset($client->custom_domain) && !empty($client->custom_domain) && $client->custom_domain != $client->sub_domain)
            $call_back_url = "https://".$client->custom_domain."/dispatch-order-status-update/".$dynamic;
            else
            $call_back_url = "https://".$client->sub_domain.env('SUBMAINDOMAIN')."/dispatch-order-status-update/".$dynamic;
            // $call_back_url = route('dispatch-order-update', $dynamic);

            $vendor_details = Vendor::where('id', $vendor)->select('id', 'name', 'latitude', 'longitude', 'address')->first();
            $tasks = array();
            $meta_data = '';

            $unique = Auth::user()->code;
            $team_tag = $unique . "_" . $vendor;

            $tasks[] = array(
                'task_type_id' => 1,
                'latitude' => $vendor_details->latitude ?? '',
                'longitude' => $vendor_details->longitude ?? '',
                'short_name' => '',
                'address' => $vendor_details->address ?? '',
                'post_code' => '',
                'barcode' => '',
            );

            $tasks[] = array(
                'task_type_id' => 2,
                'latitude' => $cus_address->latitude ?? '',
                'longitude' => $cus_address->longitude ?? '',
                'short_name' => '',
                'address' => $cus_address->address ?? '',
                'post_code' => $cus_address->pincode ?? '',
                'barcode' => '',
            );

            $postdata =  [
                'customer_name' => $customer->name ?? 'Dummy Customer',
                'customer_phone_number' => $customer->phone_number ?? rand(111111, 11111),
                'customer_email' => $customer->email ?? null,
                'recipient_phone' => $customer->phone_number ?? rand(111111, 11111),
                'recipient_email' => $customer->email ?? null,
                'task_description' => "Order From :" . $vendor_details->name,
                'allocation_type' => 'a',
                'task_type' => 'now',
                'cash_to_be_collected' => $payable_amount ?? 0.00,
                'barcode' => '',
                'order_team_tag' => $team_tag,
                'call_back_url' => $call_back_url ?? null,
                'task' => $tasks
            ];


            $client = new GClient([
                'headers' => [
                    'personaltoken' => $dispatch_domain->dispacher_home_other_service_key,
                    'shortcode' => $dispatch_domain->dispacher_home_other_service_key_code,
                    'content-type' => 'application/json'
                ]
            ]);

            $url = $dispatch_domain->dispacher_home_other_service_key_url;
            $res = $client->post(
                $url . '/api/task/create',
                ['form_params' => ($postdata)]
            );
            $response = json_decode($res->getBody(), true);
          
            if ($response && $response['task_id'] > 0) {
              
                $dispatch_traking_url = $response['dispatch_traking_url']??'';
                $up_web_hook_code = OrderVendor::where(['order_id' => $order->id, 'vendor_id' => $vendor])
                    ->update(['web_hook_code' => $dynamic,'dispatch_traking_url' => $dispatch_traking_url]);

               
                return 1;
            }
           
            return 2;
        } catch (\Exception $e) {
          
            return 2;
        }
    }


    # get prefereance if last mile on or off and all details updated in config
    public function getDispatchDomain()
    {
        $preference = ClientPreference::first();
        if ($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
            return $preference;
        else
            return false;
    }


    # get prefereance if on demand on in config
    public function getDispatchOnDemandDomain()
    {
        $preference = ClientPreference::first();
        if ($preference->need_dispacher_home_other_service == 1 && !empty($preference->dispacher_home_other_service_key) && !empty($preference->dispacher_home_other_service_key_code) && !empty($preference->dispacher_home_other_service_key_url))
            return $preference;
        else
            return false;
    }

    /// ******************   insert In Vendor Order Dispatch Status   ************************ ///////////////
    public function insertInVendorOrderDispatchStatus($request)
    {
        $update = VendorOrderDispatcherStatus::updateOrCreate([
            'dispatcher_id' => null,
            'order_id' =>  $request->order_id,
            'dispatcher_status_option_id' => 1,
            'vendor_id' =>  $request->vendor_id
        ]);
    }
    
    public function sendSuccessSMS($request, $order, $vendor_id = ''){
        try{
            $prefer = ClientPreference::select('sms_provider', 'sms_key', 'sms_secret', 'sms_from')->first();

            $user = Auth::user();
            if($user){
                $customerCurrency = ClientCurrency::join('currencies as cu', 'cu.id', 'client_currencies.currency_id')->where('client_currencies.currency_id', $user->currency)->first();
                $currSymbol = $customerCurrency->symbol;
                if($user->dial_code == "971"){
                    $to = '+'.$user->dial_code."0".$user->phone_number;
                } else {
                    $to = '+'.$user->dial_code.$user->phone_number;
                }
                $provider = $prefer->sms_provider;
                $body = "Hi ".$user->name.", Your order of amount ".$currSymbol.$order->payable_amount." for order number ".$order->order_number." has been placed successfully.";
                if(!empty($prefer->sms_key) && !empty($prefer->sms_secret) && !empty($prefer->sms_from)){
                    $send = $this->sendSms($provider, $prefer->sms_key, $prefer->sms_secret, $prefer->sms_from, $to, $body);
                }
            }
        }
        catch(\Exception $ex){
        }
    }
    public function sendOrderNotification($id)
    {
        $token = UserDevice::whereNotNull('device_token')->pluck('device_token')->where('user_id', $id)->toArray();
        $from = env('FIREBASE_SERVER_KEY');

        $notification_content = NotificationTemplate::where('id', 1)->first();
        if ($notification_content) {
            $headers = [
                'Authorization: key=' . $from,
                'Content-Type: application/json',
            ];
            $data = [
                "registration_ids" => $token,
                "notification" => [
                    'title' => $notification_content->label,
                    'body'  => $notification_content->content,
                ]
            ];
            $dataString = $data;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataString));
            $result = curl_exec($ch);
            curl_close($ch);
        }
    }
    public function getOrdersList(Request $request)
    {
        $user = Auth::user();
        $order_status_options = [];
        $paginate = $request->has('limit') ? $request->limit : 12;
        $type = $request->has('type') ? $request->type : 'active';
        $orders = OrderVendor::where('user_id', $user->id)->orderBy('id', 'DESC');
        switch ($type) {
            case 'active':
                $orders->whereNotIn('order_status_option_id', [6, 3]);
                break;
            case 'past':
                $orders->whereIn('order_status_option_id', [6, 3]);
                break;
            case 'schedule':
                $order_status_options = [10];
                $orders->whereHas('status', function ($query) use ($order_status_options) {
                    $query->whereIn('order_status_option_id', $order_status_options);
                });
                break;
        }
        $orders = $orders->with(['orderDetail','vendor:id,name,logo,banner'])
        ->whereHas('orderDetail',function($q1){
            $q1->where('orders.payment_status', 1)->whereNotIn('orders.payment_option_id', [1]);
            $q1->orWhere(function ($q2) {
                $q2->where('orders.payment_option_id', 1);
            });
        })
        ->paginate($paginate);
        foreach ($orders as $order) {
            $order_item_count = 0;
            $order->user_name = $user->name;
            $order->user_image = $user->image;
            $order->date_time = convertDateTimeInTimeZone($order->orderDetail->created_at, $user->timezone);
            $order->payment_option_title = __($order->orderDetail->paymentOption->title??'');
            $order->order_number = $order->orderDetail->order_number;
            $product_details = [];
            $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order->orderDetail->id)->where('vendor_id', $order->vendor_id)->orderBy('id', 'DESC')->first();
            if ($vendor_order_status) {
                $order->order_status =  ['current_status' => ['id' => $vendor_order_status->OrderStatusOption->id, 'title' => __($vendor_order_status->OrderStatusOption->title)]];
            } else {
                $order->current_status = null;
            }
            foreach ($order->products as $product) {
                $order_item_count += $product->quantity;
                $product_details[] = array(
                    'image_path' => $product->media->first() ? $product->media->first()->image->path : $product->image,
                    'price' => $product->price,
                    'qty' => $product->quantity,
                    'category_type' => $product->product->category->categoryDetail->type->title ?? '',
                    'product_id' => $product->product_id,
                    'title' => $product->product_name,
                );
            }
            if($order->delivery_fee > 0){
                $order_pre_time = ($order->order_pre_time > 0) ? $order->order_pre_time : 0;
                $user_to_vendor_time = ($order->user_to_vendor_time > 0) ? $order->user_to_vendor_time : 0;
                $ETA = $order_pre_time + $user_to_vendor_time;
                $order->ETA = ($ETA > 0) ? $this->formattedOrderETA($ETA, $order->created_at, $order->orderDetail->scheduled_date_time) : convertDateTimeInTimeZone($order->created_at, $user->timezone, 'h:i A');
            }
            if(!empty($order->orderDetail->scheduled_date_time)){
                $order->scheduled_date_time = convertDateTimeInTimeZone($order->orderDetail->scheduled_date_time, $user->timezone, 'M d, Y h:i A');
            }
            $luxury_option_name = '';
            if($order->orderDetail->luxury_option_id > 0){
                $luxury_option = LuxuryOption::where('id', $order->orderDetail->luxury_option_id)->first();
                if($luxury_option->title == 'takeaway'){
                    $luxury_option_name = $this->getNomenclatureName('Takeaway', $user->language, false);
                }elseif($luxury_option->title == 'dine_in'){
                    $luxury_option_name = __('Dine-In');
                }else{
                    $luxury_option_name = __('Delivery');
                }
            }
            $order->luxury_option_name = $luxury_option_name;
            $order->product_details = $product_details;
            $order->item_count = $order_item_count;
            unset($order->user);
            unset($order->products);
            unset($order->paymentOption);
            unset($order->payment_option_id);
            unset($order->orderDetail);
        }
        return $this->successResponse($orders, '', 201);
    }

    public function postOrderDetail(Request $request)
    {
        try {
            $user = Auth::user();
            $order_item_count = 0;
            $language_id = $user->language;
            $order_id = $request->order_id;
            $vendor_id = $request->vendor_id;
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
                $order->created_date = Carbon::parse($order->created_at)->setTimezone($user->timezone)->format('M d, Y h:i A');
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
                        $vendor->ETA = ($ETA > 0) ? $this->formattedOrderETA($ETA, $vendor->created_at, $order->scheduled_date_time) : convertDateTimeInTimeZone($vendor->created_at, $user->timezone, 'h:i A');
                    }
                    if($vendor->dineInTable){
                        $vendor->dineInTableName = $vendor->dineInTable->translations->first() ? $vendor->dineInTable->translations->first()->name : '';
                        $vendor->dineInTableCapacity = $vendor->dineInTable->seating_number;
                        $vendor->dineInTableCategory = $vendor->dineInTable->category->first() ? $vendor->dineInTable->category->first()->title : '';
                    }
        		}
                if(!empty($order->scheduled_date_time)){
                    $order->scheduled_date_time = convertDateTimeInTimeZone($order->scheduled_date_time, $user->timezone, 'M d, Y h:i A');
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
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
    public function postVendorOrderStatusUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $order_id = $request->order_id;
            $vendor_id = $request->vendor_id;
            $reject_reason = $request->reject_reason;
            $order_vendor_id = $request->order_vendor_id;
            $order_status_option_id = $request->order_status_option_id;
            $client_preference = ClientPreference::first();
            if ($order_status_option_id == 7) {
                $order_status_option_id = 2;
            } else if ($order_status_option_id == 8) {
                $order_status_option_id = 3;
            }
            $vendor_order_status = VendorOrderStatus::where('order_id', $order_id)->where('vendor_id', $vendor_id)->first();
            $vendor_order_status_detail = VendorOrderStatus::where('order_id', $order_id)->where('vendor_id', $vendor_id)->where('order_status_option_id', $order_status_option_id)->first();
            if (!$vendor_order_status_detail) {
                $vendor_order_status = new VendorOrderStatus();
                $vendor_order_status->order_id = $order_id;
                $vendor_order_status->vendor_id = $vendor_id;
                $vendor_order_status->order_status_option_id = $order_status_option_id;
                $vendor_order_status->order_vendor_id = $vendor_order_status->order_vendor_id;
                $vendor_order_status->save();
                $currentOrderStatus = OrderVendor::where('vendor_id', $vendor_id)->where('order_id', $order_id)->first();
                OrderVendor::where('vendor_id', $vendor_id)->where('order_id', $order_id)->update(['order_status_option_id' => $order_status_option_id, 'reject_reason' => $reject_reason]);
                if($order_status_option_id == 2 || $order_status_option_id == 3){
                    $clientDetail = Client::on('mysql')->where(['code' => $client_preference->client_code])->first();
                    AutoRejectOrderCron::on('mysql')->where(['database_name' => $clientDetail->database_name,'order_vendor_id' => $currentOrderStatus->id])->delete();
                }
                $current_status = OrderStatusOption::select('id', 'title')->find($order_status_option_id);
                if ($order_status_option_id == 2) {
                    $upcoming_status = OrderStatusOption::select('id', 'title')->where('id', '>', 3)->first();
                } elseif ($order_status_option_id == 3) {
                    $upcoming_status = null;
                } elseif ($order_status_option_id == 6) {
                    $upcoming_status = null;
                } else {
                    $upcoming_status = OrderStatusOption::select('id', 'title')->where('id', '>', $order_status_option_id)->first();
                }
                $order_status = [
                    'current_status' => $current_status,
                    'upcoming_status' => $upcoming_status,
                ];
                $orderData = Order::find($order_id);
                
                if(!empty($currentOrderStatus->dispatch_traking_url) && ($request->order_status_option_id == 3)){
                    $dispatch_traking_url = str_replace('/order/','/order-cancel/', $currentOrderStatus->dispatch_traking_url);
                    $response = Http::get($dispatch_traking_url);
                }
                DB::commit();
                // $this->sendSuccessNotification(Auth::user()->id, $request->vendor_id);
                $code = $request->header('code');
                $this->sendStatusChangePushNotificationCustomer([$orderData->user_id], $orderData, $order_status_option_id, $code);
                return response()->json([
                    'status' => 'success',
                    'order_status' => $order_status,
                    'message' => 'Order Status Updated Successfully.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function sendOrderPushNotificationVendors($user_ids, $orderData, $header_code)
    {
        $devices = UserDevice::whereNotNull('device_token')->whereIn('user_id', $user_ids)->pluck('device_token')->toArray();
       
        $client_preferences = ClientPreference::select('fcm_server_key', 'favicon')->first();
        if (!empty($devices) && !empty($client_preferences->fcm_server_key)) {
            $from = $client_preferences->fcm_server_key;
            $notification_content = NotificationTemplate::where('id', 4)->first();
            if ($notification_content) {
                $code = $header_code;
                $client = Client::where('code', $code)->first();
                $redirect_URL = "https://" . $client->sub_domain . env('SUBMAINDOMAIN') . "/client/order";
                $headers = [
                    'Authorization: key=' . $from,
                    'Content-Type: application/json',
                ];
                $data = [
                    "registration_ids" => $devices,
                    "notification" => [
                        'title' => $notification_content->subject,
                        'body'  => $notification_content->content,
                        'sound' => "notification.wav",
                        "icon" => (!empty($client_preferences->favicon)) ? $client_preferences->favicon['proxy_url'] . '200/200' . $client_preferences->favicon['image_path'] : '',
                        'click_action' => $redirect_URL,
                        "android_channel_id" => "sound-channel-id"
                    ],
                    "data" => [
                        'title' => $notification_content->subject,
                        'body'  => $notification_content->content,
                        'data' => $orderData,
                        'type' => "order_created"
                    ],
                    "priority" => "high"
                ];
                $dataString = $data;
                 $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataString));
                $result = curl_exec($ch);
                 curl_close($ch);
            }
        }
    }

    public function sendStatusChangePushNotificationCustomer($user_ids, $orderData, $order_status_id, $header_code)
    {
        $devices = UserDevice::whereNotNull('device_token')->whereIn('user_id', $user_ids)->pluck('device_token')->toArray();
    
        $client_preferences = ClientPreference::select('fcm_server_key', 'favicon')->first();
        if (!empty($devices) && !empty($client_preferences->fcm_server_key)) {
            $from = $client_preferences->fcm_server_key;
            if ($order_status_id == 2 || $order_status_id == 7) {
                $notification_content = NotificationTemplate::where('id', 5)->first();
            } elseif ($order_status_id == 3 || $order_status_id == 8) {
                $notification_content = NotificationTemplate::where('id', 6)->first();
            } elseif ($order_status_id == 4) {
                $notification_content = NotificationTemplate::where('id', 7)->first();
            } elseif ($order_status_id == 5) {
                $notification_content = NotificationTemplate::where('id', 8)->first();
            } elseif ($order_status_id == 6) {
                $notification_content = NotificationTemplate::where('id', 9)->first();
            }
            if ($notification_content) {
                $code = $header_code;
                $client = Client::where('code', $code)->first();
                $redirect_URL = "https://" . $client->sub_domain . env('SUBMAINDOMAIN') . "/user/orders";
                $headers = [
                    'Authorization: key=' . $from,
                    'Content-Type: application/json',
                ];
                $body_content = str_ireplace("{order_id}", "#" . $orderData->order_number, $notification_content->content);
                $data = [
                    "registration_ids" => $devices,
                    "notification" => [
                        'title' => $notification_content->subject,
                        'body'  => $body_content,
                        'sound' => "default",
                        "icon" => (!empty($client_preferences->favicon)) ? $client_preferences->favicon['proxy_url'] . '200/200' . $client_preferences->favicon['image_path'] : '',
                        'click_action' => $redirect_URL,
                        "android_channel_id" => "default-channel-id"
                    ],
                    "data" => [
                        'title' => $notification_content->subject,
                        'body'  => $body_content,
                        "type" => "order_status_change"
                    ],
                    "priority" => "high"
                ];
                $dataString = $data;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataString));
                $result = curl_exec($ch);
                 curl_close($ch);
            }
        }
    }

    public function minimize_orderDetails_for_notification($order_id, $vendor_id = "")
    {
        $order = Order::with(['vendors.vendor:id,name,auto_accept_order'])->select('id', 'order_number', 'payable_amount', 'payment_option_id', 'user_id', 'address_id', 'loyalty_amount_saved', 'total_discount', 'total_delivery_fee', 'total_amount', 'taxable_amount','created_at');
        $order = $order->whereHas('vendors', function ($query) use ($vendor_id) {
            if(!empty($vendor_id)){
                $query->where('vendor_id', $vendor_id);
            }
        })->with('vendors', function ($query) use ($vendor_id) {
            $query->select('id', 'order_id', 'vendor_id');
            if(!empty($vendor_id)){
                $query->where('vendor_id', $vendor_id);
            }
        });
        $order = $order->find($order_id);
        return $order;
    }

    public function orderDetails_for_notification($order_id, $vendor_id = "")
    {
        $user = Auth::user();
        if($user->is_superadmin != 1){
            $userVendorPermissions = UserVendor::where(['user_id' => $user->id])->pluck('vendor_id')->toArray();
            $vendor_id = OrderVendor::where(['order_id' => $order_id])->whereIn('vendor_id',$userVendorPermissions)->pluck('vendor_id')->first();
            if (!$vendor_id) {
                return response()->json(['error' => __('No order found')], 404);
            }
        }
        $language_id = (!empty($user->language))?$user->language:1;
        $order = Order::with(['vendors.products:id,product_name,product_id,order_id,order_vendor_id,variant_id,quantity,price', 'vendors.vendor:id,name,auto_accept_order,logo', 'vendors.products.addon:id,order_product_id,addon_id,option_id', 'vendors.products.pvariant:id,sku,product_id,title,quantity', 'user:id,name,timezone,dial_code,phone_number', 'address:id,user_id,address','vendors.products.addon.option:addon_options.id,addon_options.title,addon_id,price','vendors.products.addon.set:addon_sets.id,addon_sets.title', 'luxury_option', 'vendors.products.translation' => function ($q) use ($language_id) {
            $q->select('id', 'product_id', 'title');
            $q->where('language_id', $language_id);
        },
        'vendors.products.addon.option.translation_one' => function ($q) use ($language_id) {
            $q->select('id', 'addon_opt_id', 'title');
            $q->where('language_id', $language_id);
        },
        'vendors.products.addon.set.translation_one' => function ($q) use ($language_id) {
            $q->select('id', 'addon_id', 'title');
            $q->where('language_id', $language_id);
        }
        ])->select('id', 'order_number', 'payable_amount', 'payment_option_id', 'user_id', 'address_id', 'loyalty_amount_saved', 'total_discount', 'total_delivery_fee', 'total_amount', 'taxable_amount', 'wallet_amount_used', 'scheduled_date_time', 'payment_method', 'payment_status', 'luxury_option_id', 'created_at');
        $order = $order->whereHas('vendors', function ($query) use ($vendor_id) {
            if(!empty($vendor_id)){
                $query->where('vendor_id', $vendor_id);
            }
        })->with('vendors', function ($query) use ($vendor_id) {
            $query->select('id', 'order_id', 'vendor_id');
            if(!empty($vendor_id)){
                $query->where('vendor_id', $vendor_id);
            }
        });
        $order = $order->find($order_id);
        $order->admin_profile = Client::select('company_name', 'code', 'sub_domain', 'logo')->first();
        $order_item_count = 0;
        $order->payment_option_title = $order->paymentOption->title;
        $order->item_count = $order_item_count;
        foreach ($order->products as $product) {
            $order_item_count += $product->quantity;
        }
        $order->item_count = $order_item_count;
        unset($order->products);
        unset($order->paymentOption);
        return $this->successResponse($order, __('Order detail.'), 201);
    }


    /**
     * Credit Money Into order tip
     *
     * @return \Illuminate\Http\Response
     */
    public function tipAfterOrder(Request $request)
    {
       $user = Auth::user();
        if($user){
            $order_number = $request->order_number;
            if ($order_number > 0) {
                $order = Order::select('id', 'tip_amount')->where('order_number',$order_number)->first();
                if(($order->tip_amount == 0) || empty($order->tip_amount)){
                    $tip = Order::where('order_number',$order_number)->update(['tip_amount' => $request->tip_amount]);
                    Payment::insert([
                        'date' => date('Y-m-d'),
                        'order_id' => $order->id,
                        'transaction_id' => $request->transaction_id,
                        'balance_transaction' => $request->tip_amount,
                        'type' => 'tip'
                    ]);
                }
                $message = 'Tip has been submitted successfully';
                $response['tip_amount'] = $request->tip_amount;
                return $this->successResponse($response, $message, 200);
            }
            else{
                return $this->errorResponse('Amount is not sufficient', 400);
            }
        }
        else{
            return $this->errorResponse('Invalid User', 400);
        }
    }
    


}
