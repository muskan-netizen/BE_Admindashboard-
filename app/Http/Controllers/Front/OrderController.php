<?php

namespace App\Http\Controllers\Front;

use DB;
use Log;
use Auth;
use Carbon\Carbon;
use Omnipay\Omnipay;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\ClientPreference;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Front\FrontController;
use App\Models\{Order, OrderProduct, Cart, CartAddon, OrderProductPrescription, CartProduct, User, Product, OrderProductAddon, Payment, ClientCurrency, OrderVendor, UserAddress, Vendor, CartCoupon, CartProductPrescription, LoyaltyCard, VendorOrderStatus,OrderTax, SubscriptionInvoicesUser};

class OrderController extends FrontController
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orders(Request $request, $domain = '')
    {
        $user = Auth::user();
        $currency_id = Session::get('customerCurrency');
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $pastOrders = Order::with(['vendors'=>function($q){
                        $q->where('order_status_option_id', 6);
                    }, 'vendors.products','products.productRating', 'user', 'address'])
                    ->whereHas('vendors',function($q){
                        $q->where('order_status_option_id', 6);
                    })
                    ->where('orders.user_id', $user->id)
                    ->orderBy('orders.id', 'DESC')->paginate(10);
        $activeOrders = Order::with(['vendors'=>function($q){
                        $q->where('order_status_option_id', '!=', 6);
                    }, 'vendors.products', 'user', 'address'])
                    ->whereHas('vendors',function($q){
                        $q->where('order_status_option_id', '!=', 6);
                    })
                    ->where('orders.user_id', $user->id)
                    ->orderBy('orders.id', 'DESC')->paginate(10);
        foreach ($activeOrders as $order) {
            foreach ($order->vendors as $vendor) {
                $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order->id)->where('vendor_id', $vendor->vendor_id)->orderBy('id', 'DESC')->first();
                $vendor->order_status = $vendor_order_status ? strtolower($vendor_order_status->OrderStatusOption->title) : '';
            }
        }
        // dd($activeOrders->toArray()['data']);
        foreach ($pastOrders as $order) {
            foreach ($order->vendors as $vendor) {
                $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order->id)->where('vendor_id', $vendor->vendor_id)->orderBy('id', 'DESC')->first();
                $vendor->order_status = $vendor_order_status ? strtolower($vendor_order_status->OrderStatusOption->title) : '';
            }
        }
        $returnOrders = Order::with(['vendors.products.productReturn','products.productRating', 'user', 'address', 'products'=>function($q){
            $q->whereHas('productReturn');
        },'vendors.products'=>function($q){
            $q->whereHas('productReturn');
        },'vendors'=>function($q){
            $q->whereHas('products.productReturn');
        }])->whereHas('vendors.products.productReturn')->whereHas('vendors.products.productReturn')
        ->where('orders.user_id', $user->id)->orderBy('orders.id', 'DESC')->paginate(20);
        $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
        return view('frontend/account/orders')->with(['navCategories' => $navCategories, 'activeOrders'=>$activeOrders, 'pastOrders'=>$pastOrders, 'returnOrders'=>$returnOrders, 'clientCurrency'=> $clientCurrency]);
    }

    public function getOrderSuccessPage(Request $request){
        $currency_id = Session::get('customerCurrency');
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $order = Order::with(['products.pvariant.vset', 'products.pvariant.translation_one', 'address'])->findOrfail($request->order_id);
        // dd($order->toArray());
        $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
        return view('frontend.order.success', compact('order', 'navCategories', 'clientCurrency'));
    }
    public function placeOrder(Request $request, $domain = '')
    {
        // if ($request->input("payment-group") == '1') {
        //     $langId = Session::get('customerLanguage');
        //     $navCategories = $this->categoryNav($langId);
        //     return view('frontend/orderPayment')->with(['navCategories' => $navCategories, 'first_name' => $request->first_name, 'last_name' => $request->last_name, 'email_address' => $request->email_address, 'phone' => $request->phone, 'total_amount' => $request->total_amount, 'address_id' => $request->address_id]);
        // }
        $order = $this->orderSave($request, "1");
        
        return $this->successResponse(['status' => 'success', 'order' => $order, 'message' => 'Order placed successfully.']);
    }
    public function orderSave($request, $paymentStatus)
    {
        try {
            DB::beginTransaction();
            $delivery_on_vendors = array();
            if( (isset($request->auth_token)) && (!empty($request->auth_token)) ){
                $user = User::where('auth_token', $request->auth_token)->first();
            }else{
                $user = Auth::user();
            }
            $loyalty_amount_saved = 0;
            $redeem_points_per_primary_currency = '';
            $loyalty_card = LoyaltyCard::where('status', '0')->first();
            if ($loyalty_card) {
                $redeem_points_per_primary_currency = $loyalty_card->redeem_points_per_primary_currency;
            }
            $currency_id = Session::get('customerCurrency');
            $language_id = Session::get('customerLanguage');
            $cart = Cart::where('user_id', $user->id)->first();
            $order_loyalty_points_earned_detail = Order::where('user_id', $user->id)->select(DB::raw('sum(loyalty_points_earned) AS sum_of_loyalty_points_earned'), DB::raw('sum(loyalty_points_used) AS sum_of_loyalty_points_used'))->first();
            if ($order_loyalty_points_earned_detail) {
                $loyalty_points_used = $order_loyalty_points_earned_detail->sum_of_loyalty_points_earned - $order_loyalty_points_earned_detail->sum_of_loyalty_points_used;
                if ($loyalty_points_used > 0 && $redeem_points_per_primary_currency > 0) {
                    $loyalty_amount_saved = $loyalty_points_used / $redeem_points_per_primary_currency;
                }
            }
            $customerCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
            $clientCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
            $order = new Order;
            $order->user_id = $user->id;
            $order->order_number = generateOrderNo();
            $order->address_id = $request->address_id;
            $order->payment_option_id = $request->payment_option_id;
            $order->save();
            $cart_prescriptions = CartProductPrescription::where('cart_id', $cart->id)->get();
            foreach ($cart_prescriptions as $cart_prescription) {
                $order_prescription = new OrderProductPrescription();
                $order_prescription->order_id = $order->id;
                $order_prescription->vendor_id = $cart_prescription->vendor_id;
                $order_prescription->product_id = $cart_prescription->product_id;
                $order_prescription->prescription = $cart_prescription->getRawOriginal('prescription');
                $order_prescription->save();
            }
            $subscription_features = array();
            if($user){
                $now = Carbon::now()->toDateTimeString();
                $user_subscription = SubscriptionInvoicesUser::with('features')
                    ->select('id', 'user_id', 'subscription_id')
                    ->where('user_id', $user->id)
                    ->where('end_date', '>', $now)
                    ->orderBy('end_date', 'desc')->first();
                if($user_subscription){
                    foreach($user_subscription->features as $feature){
                        $subscription_features[] = $feature->feature_id;
                    }
                }
            }
            $cart_products = CartProduct::select('*')->with(['product.pimage', 'product.variants', 'product.taxCategory.taxRate', 'coupon.promo', 'product.addon'])->where('cart_id', $cart->id)->where('status', [0, 1])->where('cart_id', $cart->id)->orderBy('created_at', 'asc')->get();
            $total_amount = 0;
            $total_discount = 0;
            $taxable_amount = 0;
            $payable_amount = 0;
            $tax_category_ids = [];
            $total_delivery_fee = 0;
            $total_subscription_discount = 0;
            foreach ($cart_products->groupBy('vendor_id') as $vendor_id => $vendor_cart_products) {
                $delivery_fee = 0; 
                $deliver_charge = $delivery_fee_charges = 0.00;
                $delivery_count = 0;
                $vendor_payable_amount = 0;
                $vendor_discount_amount = 0;
                $product_taxable_amount = 0;
                $product_payable_amount = 0;
                $vendor_taxable_amount = 0;
                $OrderVendor = new OrderVendor();
                $OrderVendor->status = 0;
                $OrderVendor->user_id= $user->id;
                $OrderVendor->order_id = $order->id;
                $OrderVendor->vendor_id = $vendor_id;
                $OrderVendor->save();
                $vendorProductIds = array();
                foreach ($vendor_cart_products as $vendor_cart_product) {
                    $variant = $vendor_cart_product->product->variants->where('id', $vendor_cart_product->variant_id)->first();
                    $quantity_price = 0;
                    $divider = (empty($vendor_cart_product->doller_compare) || $vendor_cart_product->doller_compare < 0) ? 1 : $vendor_cart_product->doller_compare;
                    $price_in_currency = $variant->price / $divider;
                    $price_in_dollar_compare = $price_in_currency * $clientCurrency->doller_compare;
                    $quantity_price = $price_in_dollar_compare * $vendor_cart_product->quantity;
                    $payable_amount = $payable_amount + $quantity_price;
                    $vendor_payable_amount = $vendor_payable_amount + $quantity_price;
                    if (isset($vendor_cart_product->product['taxCategory'])) {
                        foreach ($vendor_cart_product->product['taxCategory']['taxRate'] as $tax_rate_detail) {
                            if(!in_array($vendor_cart_product->product['taxCategory']['id'], $tax_category_ids)){
                                $tax_category_ids[] = $vendor_cart_product->product['taxCategory']['id'];
                            }
                            $rate = round($tax_rate_detail->tax_rate);
                            $tax_amount = ($price_in_dollar_compare * $rate) / 100;
                            $product_tax = $quantity_price * $rate / 100;
                            $product_taxable_amount += $product_tax;
                            $payable_amount = $payable_amount + $product_tax;
                        }
                    }
                    if ( (!empty($vendor_cart_product->product->Requires_last_mile)) && ($vendor_cart_product->product->Requires_last_mile == 1) ) {
                       $delivery_fee = $this->getDeliveryFeeDispatcher($vendor_cart_product->vendor_id, $user->id);
                        if(!empty($delivery_fee) && $delivery_count == 0)
                        {
                            $delivery_count = 1;
                            $vendor_cart_product->delivery_fee = number_format($delivery_fee, 2);
                            // $payable_amount = $payable_amount + $delivery_fee;
                            $delivery_fee_charges = $delivery_fee;
                        }

                    } 
                    $total_amount += $vendor_cart_product->quantity * $variant->price;
                    $order_product = new OrderProduct;
                    $order_product->order_id = $order->id;
                    $order_product->price = $variant->price;
                    $taxable_amount += $product_taxable_amount;
                    $vendor_taxable_amount += $product_taxable_amount;
                    $order_product->order_vendor_id = $OrderVendor->id;
                    $order_product->taxable_amount = $product_taxable_amount;
                    $order_product->quantity = $vendor_cart_product->quantity;
                    $order_product->vendor_id = $vendor_cart_product->vendor_id;
                    $order_product->product_id = $vendor_cart_product->product_id;
                    $product_category = Product::where('id', $vendor_cart_product->product_id)->first();
                    if ($product_category) {
                        $order_product->category_id = $product_category->category_id;
                    }
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
                        CartAddon::where('cart_product_id', $vendor_cart_product->id)->delete();
                    }
                }
                $coupon_id = null;
                $coupon_name = null;
                $actual_amount = $vendor_payable_amount;
                if ($vendor_cart_product->coupon) {
                    $coupon_id = $vendor_cart_product->coupon->promo->id;
                    $coupon_name = $vendor_cart_product->coupon->promo->name;
                    if ($vendor_cart_product->coupon->promo->promo_type_id == 2) {
                        $amount = round($vendor_cart_product->coupon->promo->amount);
                        $total_discount += $amount;
                        $vendor_payable_amount -= $amount;
                        $vendor_discount_amount += $amount;
                    } else {
                        $gross_amount = number_format(($payable_amount - $taxable_amount), 2);
                        $percentage_amount = ($gross_amount * $vendor_cart_product->coupon->promo->amount / 100);
                        $vendor_payable_amount -= $percentage_amount;
                        $vendor_discount_amount += $percentage_amount;
                    }
                }
                $total_delivery_fee += $delivery_fee;
                
                $OrderVendor->coupon_id = $coupon_id;
                $OrderVendor->coupon_code = $coupon_name;
                $OrderVendor->order_status_option_id = 1;
                $OrderVendor->delivery_fee = $delivery_fee;
                $OrderVendor->subtotal_amount = $actual_amount;
                $OrderVendor->discount_amount = $vendor_discount_amount;
                $OrderVendor->taxable_amount   = $vendor_taxable_amount;
                $OrderVendor->payment_option_id = $request->payment_option_id;
                $OrderVendor->payable_amount = $vendor_payable_amount + $delivery_fee;
                $vendor_info = Vendor::where('id', $vendor_id)->first();
                if ($vendor_info) {
                    if (($vendor_info->commission_percent) != null && $vendor_payable_amount > 0) {
                        $OrderVendor->admin_commission_percentage_amount = round($vendor_info->commission_percent * ($vendor_payable_amount / 100), 2);
                    }
                    if (($vendor_info->commission_fixed_per_order) != null && $vendor_payable_amount > 0) {
                        $OrderVendor->admin_commission_fixed_amount = $vendor_info->commission_fixed_per_order;
                    }
                }
                $OrderVendor->save();
                $order_status = new VendorOrderStatus();
                $order_status->order_id = $order->id;
                $order_status->vendor_id = $vendor_id;
                $order_status->order_vendor_id = $OrderVendor->id;
                $order_status->order_status_option_id = 1;
                $order_status->save();
            }
            $loyalty_points_earned = LoyaltyCard::getLoyaltyPoint($loyalty_points_used, $payable_amount);
            if(in_array(1, $subscription_features)){
                $total_subscription_discount = $total_subscription_discount + $total_delivery_fee;
            }
            $total_discount = $total_discount + $total_subscription_discount;
            $order->total_amount = $total_amount;
            $order->total_discount = $total_discount;
            $order->taxable_amount = $taxable_amount;
            if ($loyalty_amount_saved > 0) {
                if ($loyalty_amount_saved > $payable_amount) {
                    $loyalty_amount_saved = $payable_amount;
                    $loyalty_points_used = $payable_amount * $redeem_points_per_primary_currency;
                }
            }
            $tip_amount = 0;
            if ( (isset($request->tip)) && ($request->tip != '') && ($request->tip > 0) ) {
                $tip_amount = $request->tip;
                $tip_amount = ($tip_amount / $customerCurrency->doller_compare) * $clientCurrency->doller_compare;
                $order->tip_amount = number_format($tip_amount, 2);
            }
            $order->total_delivery_fee = $total_delivery_fee;
            $order->loyalty_points_used = $loyalty_points_used;
            $order->loyalty_amount_saved = $loyalty_amount_saved;
            $order->subscription_discount = $total_subscription_discount;
            $order->loyalty_points_earned = $loyalty_points_earned['per_order_points'];
            $order->loyalty_membership_id = $loyalty_points_earned['loyalty_card_id'];
            $order->payable_amount = $total_delivery_fee + $payable_amount + $tip_amount - $total_discount - $loyalty_amount_saved;
            $order->save();
            CartAddon::where('cart_id', $cart->id)->delete();
            CartCoupon::where('cart_id', $cart->id)->delete();
            CartProduct::where('cart_id', $cart->id)->delete();
            CartProductPrescription::where('cart_id', $cart->id)->delete();
            if(count($tax_category_ids)){
                foreach ($tax_category_ids as $tax_category_id) {
                    $order_tax = new OrderTax();
                    $order_tax->order_id = $order->id;
                    $order_tax->tax_category_id = $tax_category_id;
                    $order_tax->save();
                }
            }
            if ( ($request->payment_option_id != 1) && ($request->payment_option_id != 2) ) {
                Payment::insert([
                    'date' => date('Y-m-d'),
                    'order_id' => $order->id,
                    'transaction_id' => $request->transaction_id,
                    'balance_transaction' => $order->payable_amount,
                ]);
            }
            DB::commit();
            return $order;
        } catch (Exception $e) {
            DB::rollback();
        }
    }

    public function makePayment(Request $request)
    {
        $token = $request->stripeToken;
        $gateway = Omnipay::create('Stripe');
        $gateway->setApiKey('sk_test_51IhpwhSFHEA938FwRPiQSAH5xF6DcjO5GCASiud9cGMJ0v8UJyRfCb7IQAMbXbuPMe7JphA1izxZOsIclvmOgqUV00Zpk85xfl');
        $formData = [
            'number' => $request->card_num,
            'description' => $request->first_name,
            'expiryMonth' => $request->exp_month,
            'expiryYear' => $request->exp_year,
            'cvv' => $request->cvc
        ];
        $response = $gateway->purchase(
            [
                'amount' => $request->amount,
                'currency' => 'INR',
                'card' => $formData,
                'token' => $token,
            ]
        )->send();
        if ($response->isSuccessful()) {
            $cart = Cart::where('user_id', Auth::user()->id)->first();
            $payment = new Payment();
            $payment->amount = $request->amount;
            $payment->transaction_id = $response->getData()['id'];
            $payment->balance_transaction = $response->getData()['balance_transaction'];
            $payment->type = "card";
            $payment->cart_id = $cart->id;
            $payment->save();
            $this->orderSave($request, "2", "1");
        } elseif ($response->isRedirect()) {
            $response->redirect();
        } else {
            exit($response->getMessage());
        }
    }
    public function getDeliveryFeeDispatcher($vendor_id, $user_id)
    {
        try {
            $dispatch_domain = $this->checkIfLastMileOn();
            if ($dispatch_domain && $dispatch_domain != false) {
                $customer = User::find($user_id);
                $cus_address = UserAddress::where('user_id', $user_id)->orderBy('is_primary', 'desc')->first();
                if ($cus_address) {
                    $tasks = array();
                    $vendor_details = Vendor::find($vendor_id);
                    $location[] = array(
                        'latitude' => $vendor_details->latitude ?? 30.71728880,
                        'longitude' => $vendor_details->longitude ?? 76.80350870
                    );
                    $location[] = array(
                        'latitude' => $cus_address->latitude ?? 30.717288800000,
                        'longitude' => $cus_address->longitude ?? 76.803508700000
                    );
                    $postdata =  ['locations' => $location];
                    $client = new Client([
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
            // print_r($e->getMessage());
            //  die;

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

    public function postPaymentPlaceOrder(Request $request, $domain = '')
    {
        if( (isset($request->auth_token)) && (!empty($request->auth_token)) ){
            return $this->placeOrder($request);
        }else{
            return $this->errorResponse('Invalid User', 402);
        }
    }
}
