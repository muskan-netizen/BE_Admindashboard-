<?php

namespace App\Http\Controllers\Front;

use DB;
use Auth;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Client as GCLIENT;
use App\Http\Traits\{ApiResponser,CartManager};
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Front\PromoCodeController;
use App\Http\Controllers\Front\LalaMovesController;
use App\Models\{AddonSet, Cart, CartAddon, CartProduct, CartCoupon, CartDeliveryFee, TempCart, TempCartAddon, TempCartProduct, TempCartCoupon, TempCartDeliveryFee, User, Product, ClientCurrency, ClientLanguage, CartProductPrescription, ProductVariantSet, Country, UserAddress, Client, ClientPreference, Vendor, Order, OrderProduct, OrderProductAddon, OrderProductPrescription, VendorOrderStatus, OrderVendor,PaymentOption, OrderTax, LuxuryOption, UserWishlist, SubscriptionInvoicesUser, LoyaltyCard, VendorDineinCategory, VendorDineinTable, VendorDineinCategoryTranslation, VendorDineinTableTranslation, VendorSlot};
use Log;
class TempCartController extends FrontController
{
    use ApiResponser,CartManager;

    /**
     * get products from cart
     *
     * @return \Illuminate\Http\Response
     */
    public function getCartProducts($domain = '')
    {
        $cart_details = [];
        $user = Auth::user();
        $curId = Session::get('customerCurrency');
        $langId = Session::get('customerLanguage');
        if ($user) {
            $cart = Cart::select('id', 'is_gift', 'item_count', 'schedule_type', 'scheduled_date_time')->with('coupon.promo')->where('status', '0')->where('user_id', $user->id)->first();
        } else {
            $cart = Cart::select('id', 'is_gift', 'item_count', 'schedule_type', 'scheduled_date_time')->with('coupon.promo')->where('status', '0')->where('unique_identifier', session()->get('_token'))->first();
        }
        if ($cart) {
            $cart_details = $this->getCart($cart);
        }
        if ($cart_details && !empty($cart_details)) {
            return response()->json([
                'data' => $cart_details,
            ]);
        }
        return response()->json([
            'message' => "No product found in cart",
            'data' => $cart_details,
        ]);
    }

    /**
     * Get Cart Items
     *
     */
    public function getCart($cart, $address_id=0 , $code = null)
    {
        $address = [];
        $cart_id = $cart->id;
        $user = Auth::user();
        $langId = Session::has('customerLanguage') ? Session::get('customerLanguage') : 1;
        $curId = Session::get('customerCurrency');
        $preferences = ClientPreference::with(['client_detail:id,code,country_id'])->first();
        $countries = Country::get();
        $cart->pharmacy_check = $preferences->pharmacy_check;
        $customerCurrency = ClientCurrency::where('currency_id', $curId)->first();
        $latitude = '';
        $longitude = '';
        $user_allAddresses = collect();
        $upSell_products = collect();
        $crossSell_products = collect();
        if($user){
            $user_allAddresses = UserAddress::where('user_id', $user->id)->get();
            if($address_id > 0){
                $address = UserAddress::where('user_id', $user->id)->where('id', $address_id)->first();
            }else{
                $address = UserAddress::where('user_id', $user->id)->where('is_primary', 1)->first();
                $address_id = ($address) ? $address->id : 0;
            }
        }
        $latitude = ($address) ? $address->latitude : '';
        $longitude = ($address) ? $address->longitude : '';

        $delifproductnotexist = CartProduct::where('cart_id', $cart_id)->doesntHave('product')->delete();
 
        $cartData = CartProduct::with([
            'vendor','vendor.slots','vendor.slot.day', 'vendor.slotDate', 'coupon' => function ($qry) use ($cart_id) {
                $qry->where('cart_id', $cart_id);
            }, 'vendorProducts.pvariant.media.pimage.image', 'vendorProducts.product.media.image',
            'vendorProducts.pvariant.vset.variantDetail.trans' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.pvariant.vset.optionData.trans' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.product.translation_one' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                $q->where('language_id', $langId);
            },
            'vendorProducts' => function ($qry) use ($cart_id) {
                $qry->where('cart_id', $cart_id);
            },
            'vendorProducts.addon.set' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.addon.option' => function ($qry) use ($langId) {
                $qry->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                $qry->select('addon_options.id', 'addon_options.price', 'apt.title', 'addon_options.addon_id', 'apt.language_id');
                $qry->where('apt.language_id', $langId)->groupBy(['addon_options.id', 'apt.language_id']);
                // $qry->where('language_id', $langId);
            }, 'vendorProducts.product.taxCategory.taxRate',
        ])->select('vendor_id', 'luxury_option_id', 'vendor_dinein_table_id')->where('status', [0, 1])->where('cart_id', $cart_id)->groupBy('vendor_id')->orderBy('created_at', 'asc')->get();
        $loyalty_amount_saved = 0;
        $redeem_points_per_primary_currency = '';
        $loyalty_card = LoyaltyCard::where('status', '0')->first();
        if ($loyalty_card) {
            $redeem_points_per_primary_currency = $loyalty_card->redeem_points_per_primary_currency;
        }
        $subscription_features = array();
        if($user){
            $order_loyalty_points_earned_detail = Order::where('user_id', $user->id)->select(DB::raw('sum(loyalty_points_earned) AS sum_of_loyalty_points_earned'), DB::raw('sum(loyalty_points_used) AS sum_of_loyalty_points_used'))->first();
            if ($order_loyalty_points_earned_detail) {
                $loyalty_points_used = $order_loyalty_points_earned_detail->sum_of_loyalty_points_earned - $order_loyalty_points_earned_detail->sum_of_loyalty_points_used;
                if ($loyalty_points_used > 0 && $redeem_points_per_primary_currency > 0) {
                    $loyalty_amount_saved = $loyalty_points_used / $redeem_points_per_primary_currency;
                    if( ($customerCurrency) && ($customerCurrency->is_primary != 1) ){
                        $loyalty_amount_saved = $loyalty_amount_saved * $customerCurrency->doller_compare;
                    }
                }
            }
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

            $cart->scheduled_date_time = convertDateTimeInTimeZone($cart->scheduled_date_time, $user->timezone, 'Y-m-d\TH:i');
        }
        $total_payable_amount = $total_subscription_discount = $total_discount_amount = $total_discount_percent = $total_taxable_amount = 0.00;
        if ($cartData) {
            $cart_dinein_table_id = NULL;
            $action = (Session::has('vendorType')) ? Session::get('vendorType') : 'delivery';
            $vendor_details = [];
            $delivery_status = 1;
            $is_vendor_closed = 0;
            $deliver_charge = 0;
            $deliveryCharges = 0;
            $delay_date = 0;
            $pickup_delay_date = 0;
            $dropoff_delay_date = 0;
            $total_service_fee = 0;
            $product_out_of_stock = 0;
            $PromoFreeDeliver = 0;
            $PromoDelete = 0;
            $d = 0;
            foreach ($cartData as $ven_key => $vendorData) {
                $is_promo_code_available = 0;
                $vendor_products_total_amount = $payable_amount = $taxable_amount = $subscription_discount = $discount_amount = $discount_percent = $deliver_charge = $delivery_fee_charges = $delivery_fee_charges_static =  $deliver_charges_lalmove = 0.00;
                $delivery_count = 0;
                $delivery_count_lm = 0;
                $coupon_amount_used = 0;
         
                if(Session::has('vendorTable')){
                    if((Session::has('vendorTableVendorId')) && (Session::get('vendorTableVendorId') == $vendorData->vendor_id)){
                        $cart_dinein_table_id = Session::get('vendorTable');
                    }
                    Session::forget(['vendorTable', 'vendorTableVendorId']);
                }else{
                    $cart_dinein_table_id = $vendorData->vendor_dinein_table_id;
                }

                if($action != 'delivery'){
                    $vendor_details['vendor_address'] = $vendorData->vendor->select('id','latitude','longitude','address')->where('id', $vendorData->vendor_id)->first();
                    if($action == 'dine_in'){
                        $vendor_tables = VendorDineinTable::where('vendor_id', $vendorData->vendor_id)->with('category')->get();
                        foreach ($vendor_tables as $vendor_table) {
                            $vendor_table->qr_url = url('/vendor/'.$vendorData->vendor->slug.'/?id='.$vendorData->vendor_id.'&name='.$vendorData->vendor->name.'&table='.$vendor_table->id);
                        }
                        $vendor_details['vendor_tables'] = $vendor_tables;
                    }
                }
                else{
                    if( (isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1) ){
                        if($address_id > 0){
                            $serviceArea = $vendorData->vendor->whereHas('serviceArea', function($query) use($latitude, $longitude){
                                $query->select('vendor_id')
                                ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(".$latitude." ".$longitude.")'))");
                            })->where('id', $vendorData->vendor_id)->get();
                        }
                    }
                }
                
                foreach ($vendorData->vendorProducts as $ven_key => $prod) {
                    if($prod->product->sell_when_out_of_stock == 0 && $prod->product->has_inventory == 1){
                        $quantity_check = productvariantQuantity($prod->variant_id);
                        if($quantity_check < $prod->quantity ){
                            $delivery_status = 0;
                            $product_out_of_stock = 1;
                        }
                    }
                    $prod->product_out_of_stock =  $product_out_of_stock; 

                    if($cart_dinein_table_id > 0){
                        $prod->update(['vendor_dinein_table_id' => $cart_dinein_table_id]);
                    }
                    $quantity_price = 0;
                    $divider = (empty($prod->doller_compare) || $prod->doller_compare < 0) ? 1 : $prod->doller_compare;
                    $price_in_currency = $prod->pvariant->price;
                    $price_in_doller_compare = $prod->pvariant->price;
                    if($customerCurrency){
                        $price_in_currency = $prod->pvariant->price / $divider;
                        $price_in_doller_compare = $price_in_currency * $customerCurrency->doller_compare;
                    }
                    $quantity_price = $price_in_doller_compare * $prod->quantity;
                    $prod->pvariant->price_in_cart = $prod->pvariant->price;
                    $prod->pvariant->price = number_format($price_in_currency, 2, '.', '');
                    $prod->image_url = $this->loadDefaultImage();
                    $prod->pvariant->media_one = isset($prod->pvariant->media) ? $prod->pvariant->media->first() : [];
                    $prod->pvariant->media_second = isset($prod->product->media) ? $prod->product->media->first() : [];
                    $prod->pvariant->multiplier = ($customerCurrency) ? $customerCurrency->doller_compare : 1;
                    $prod->quantity_price = number_format($quantity_price, 2, '.', '');
                    $payable_amount = $payable_amount + $quantity_price;
                    $vendor_products_total_amount = $vendor_products_total_amount + $quantity_price;
                    $taxData = array();
                    if (!empty($prod->product->taxCategory) && count($prod->product->taxCategory->taxRate) > 0) {
                        foreach ($prod->product->taxCategory->taxRate as $tckey => $tax_value) {
                            $rate = round($tax_value->tax_rate);
                            $tax_amount = ($price_in_doller_compare * $rate) / 100;
                            $product_tax = $quantity_price * $rate / 100;
                            $taxData[$tckey]['identifier'] = $tax_value->identifier;
                            $taxData[$tckey]['rate'] = $rate;
                            $taxData[$tckey]['tax_amount'] = number_format($tax_amount, 2, '.', '');
                            $taxData[$tckey]['product_tax'] = number_format($product_tax, 2, '.', '');
                            $taxable_amount = $taxable_amount + $product_tax;
                            $payable_amount = $payable_amount + $product_tax;
                        }
                        unset($prod->product->taxCategory);
                    }
                    $prod->taxdata = $taxData;
                    if($prod->addon->isNotEmpty()){
                        foreach ($prod->addon as $ck => $addons) {
                            if(isset($addons->option)){
                                $opt_price_in_currency = $addons->option->price;
                                $opt_price_in_doller_compare = $addons->option->price;
                                if($customerCurrency){
                                    $opt_price_in_currency = $addons->option->price / $divider;
                                    $opt_price_in_doller_compare = $opt_price_in_currency * $customerCurrency->doller_compare;
                                }
                                $opt_quantity_price = number_format($opt_price_in_doller_compare * $prod->quantity, 2, '.', '');
                                $addons->option->price_in_cart = $addons->option->price;
                                $addons->option->price = number_format($opt_price_in_currency, 2, '.', '');
                                $addons->option->multiplier = ($customerCurrency) ? $customerCurrency->doller_compare : 1;
                                $addons->option->quantity_price = $opt_quantity_price;
                                $payable_amount = $payable_amount + $opt_quantity_price;
                            }
                        }
                    }
                    if (isset($prod->pvariant->image->imagedata) && !empty($prod->pvariant->image->imagedata)) {
                        $prod->cartImg = $prod->pvariant->image->imagedata;
                    } else {
                        $prod->cartImg = (isset($prod->product->media[0]) && !empty($prod->product->media[0])) ? $prod->product->media[0]->image : '';
                    }

                    if($prod->product->delay_hrs_min != 0){
                        if($prod->product->delay_hrs_min > $delay_date)
                        $delay_date = $prod->product->delay_hrs_min;
                    }
                    if($prod->product->pickup_delay_hrs_min != 0){
                        if($prod->product->pickup_delay_hrs_min > $delay_date)
                        $pickup_delay_date = $prod->product->pickup_delay_hrs_min;
                    }

                    if($prod->product->dropoff_delay_hrs_min != 0){
                        if($prod->product->dropoff_delay_hrs_min > $delay_date)
                        $dropoff_delay_date = $prod->product->dropoff_delay_hrs_min;
                    }
                    
                    if($action == 'delivery'){
                        $delivery_fee_charges = 0;
                        $deliver_charges_lalmove =0;
                       // $deliveryCharges = 0;

                        if (!empty($prod->product->Requires_last_mile) && ($prod->product->Requires_last_mile == 1)) {

                            //Dispatcher Delivery changes code
                            $deliver_charge = $this->getDeliveryFeeDispatcher($vendorData->vendor_id);
                            if (!empty($deliver_charge) && $delivery_count == 0) {
                                $delivery_count = 1;
                                $prod->deliver_charge = number_format($deliver_charge, 2, '.', '');
                                // $payable_amount = $payable_amount + $deliver_charge;

                                $shipping_delivery_type = 'D';

                            }
                            $delivery_fee_charges = $deliver_charge;
                            $deliveryCharges = $delivery_fee_charges;
                            //Lalamove Delivery changes code
                            $lalamove = new LalaMovesController();
                            $deliver_lalmove_fee = $lalamove->getDeliveryFeeLalamove($vendorData->vendor_id);
                            if($deliver_lalmove_fee>0 && $delivery_count_lm == 0)
                            {   
                                $delivery_count_lm = 1;
                                $prod->deliver_charge_lalamove = number_format($deliver_lalmove_fee, 2, '.', '');
                                $shipping_delivery_type = 'L';
                            }
                            $deliver_charges_lalmove = $deliver_lalmove_fee;
                            //End Lalamove Delivery changes code
                            if($code =='L' && $deliver_lalmove_fee>0)
                            {
                                $deliveryCharges = $deliver_charges_lalmove;
                            }

                            # for static fees 
                            if($preferences->static_delivey_fee == 1 &&  $vendorData->vendor->order_amount_for_delivery_fee != 0)
                            {
                                if( $payable_amount >= (float)($vendorData->vendor->order_amount_for_delivery_fee)){ 
                                    $deliveryCharges = number_format($vendorData->vendor->delivery_fee_maximum, 2, '.', '');
                                }

                                if($payable_amount < (float)($vendorData->vendor->order_amount_for_delivery_fee)){
                                    $deliveryCharges = number_format($vendorData->vendor->delivery_fee_minimum, 2, '.', '');
                                }

                                $delivery_fee_charges_static =  $deliveryCharges;
                                $delivery_fee_charges =  $delivery_fee_charges_lalamove = $deliveryCharges;
                            }
                        }
                    }
                    
                    $product = Product::with([
                        'variant' => function ($sel) {
                            $sel->groupBy('product_id');
                        },
                        'variant.media.pimage.image', 'upSell', 'crossSell', 'vendor', 'media.image', 'translation' => function ($q) use ($langId) {
                            $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                            $q->where('language_id', $langId);
                        }])->select('id', 'sku', 'inquiry_only', 'url_slug', 'weight', 'weight_unit', 'vendor_id', 'has_variant', 'has_inventory', 'averageRating','minimum_order_count','batch_count')
                        ->where('url_slug', $prod->product->url_slug)
                        ->where('is_live', 1)
                        ->first();
                    $doller_compare = ($customerCurrency) ? $customerCurrency->doller_compare : 1;
                    $up_prods = $this->metaProduct($langId, $doller_compare, 'upSell', $product->upSell);
                    if($up_prods){
                        $upSell_products->push($up_prods);
                    }
                    $cross_prods = $this->metaProduct($langId, $doller_compare, 'crossSell', $product->crossSell);
                    if($cross_prods){
                        $crossSell_products->push($cross_prods);
                    }
                }
                if (isset($vendorData->coupon) && !empty($vendorData->coupon) ) {
                    //pr($vendorData->coupon->promo);

                    if (isset($vendorData->coupon->promo) && !empty($vendorData->coupon->promo)) {
                        if($vendorData->coupon->promo->first_order_only==1){
                            if(Auth::user()){
                                $userOrder = auth()->user()->orders->first();
                                if($userOrder){
                                    $cart->coupon()->delete();
                                    $vendorData->coupon()->delete();
                                    unset($vendorData->coupon);
                                    $PromoDelete =1;
                                }
                            }
                        }
                        if ( $PromoDelete !=1) {
                            if( $vendorData->coupon->promo->minimum_spend <= $payable_amount && $vendorData->coupon->promo->maximum_spend >= $payable_amount  )
                            {
                                if ($vendorData->coupon->promo->promo_type_id == 2) {
                                    $total_discount_percent = $vendorData->coupon->promo->amount;

                                    $payable_amount -= $total_discount_percent;
                                    $coupon_amount_used = $total_discount_percent;
                                } else {
                                    $gross_amount = number_format(($payable_amount - $taxable_amount), 2, '.', '');
                                    $percentage_amount = ($gross_amount * $vendorData->coupon->promo->amount / 100);
                                    $payable_amount -= $percentage_amount;
                                    $coupon_amount_used = $percentage_amount;
                                }
                            }
                            else{

                                $cart->coupon()->delete();
                                $vendorData->coupon()->delete();
                                unset($vendorData->coupon);
                                $PromoDelete =1;
                            }
                        }
                        if ( $PromoDelete !=1) {
                            if($vendorData->coupon->promo->allow_free_delivery ==1   ){
                                $PromoFreeDeliver = 1;
                                $coupon_amount_used = $coupon_amount_used +  $deliveryCharges;
                                $payable_amount = $payable_amount - $deliveryCharges;
                               // pr($payable_amount);
                            }
                        }
                    }
                }

                if (in_array(1, $subscription_features)) {
                    $subscription_discount = $subscription_discount + $deliveryCharges;
                }
               // pr($PromoFreeDeliver);

                $subtotal_amount = $payable_amount;
                if($PromoFreeDeliver != 1){
                    $payable_amount = $payable_amount + $deliveryCharges;
                }
                //$payable_amount = $payable_amount + $deliver_charge;
                //Start applying service fee on vendor products total
                $vendor_service_fee_percentage_amount = 0;
                if($vendorData->vendor->service_fee_percent > 0){
                    $vendor_service_fee_percentage_amount = ($vendor_products_total_amount * $vendorData->vendor->service_fee_percent) / 100 ;
                    $payable_amount = $payable_amount + $vendor_service_fee_percentage_amount;
                }

                if(isset($deliveryCharges) && !empty($deliveryCharges)){
                     CartDeliveryFee::updateOrCreate(['cart_id' => $cart->id, 'vendor_id' => $vendorData->vendor->id],['delivery_fee' => $deliveryCharges,'shipping_delivery_type' => $code??'D']);
                }
               
                //end applying service fee on vendor products total
                $total_service_fee = $total_service_fee + $vendor_service_fee_percentage_amount;
                $vendorData->coupon_amount_used = number_format($coupon_amount_used, 2, '.', '');
                $vendorData->service_fee_percentage_amount = number_format($vendor_service_fee_percentage_amount, 2, '.', '');
                $vendorData->delivery_fee_charges = number_format($delivery_fee_charges, 2, '.', '');
                $vendorData->delivery_fee_charges_static = number_format($delivery_fee_charges_static, 2, '.', '');;
                $vendorData->delivery_fee_charges_lalamove = number_format($deliver_charges_lalmove, 2, '.', '');
                $vendorData->payable_amount = number_format($payable_amount, 2, '.', '');
                $vendorData->discount_amount = number_format($discount_amount, 2, '.', '');
                $vendorData->discount_percent = number_format($discount_percent, 2, '.', '');
                $vendorData->taxable_amount = number_format($taxable_amount, 2, '.', '');
                $vendorData->product_total_amount = number_format(($payable_amount - $taxable_amount), 2, '.', '');
                $vendorData->product_sub_total_amount = number_format($subtotal_amount, 2, '.', '');
                $vendorData->isDeliverable = 1;
                $vendorData->promo_free_deliver = $PromoFreeDeliver;
                $vendorData->is_vendor_closed = $is_vendor_closed;
                // if (!empty($subscription_features)) {
                //     $vendorData->product_total_amount = number_format(($payable_amount - $taxable_amount - $subscription_discount), 2, '.', '');
                // }
                if(isset($serviceArea)){
                    if($serviceArea->isEmpty()){
                        $vendorData->isDeliverable = 0;
                        $delivery_status = 0;
                    }
                }
                if($vendorData->vendor->show_slot == 0){
                    if( ($vendorData->vendor->slotDate->isEmpty()) && ($vendorData->vendor->slot->isEmpty()) ){
                        $vendorData->is_vendor_closed = 1;
                        if($delivery_status != 0){
                            $delivery_status = 0;
                        }
                    }else{
                        $vendorData->is_vendor_closed = 0;
                    }
                }
                if($vendorData->vendor->$action == 0){
                    $vendorData->is_vendor_closed = 1;
                    $delivery_status = 0;
                }
               
                if((float)($vendorData->vendor->order_min_amount) > $subtotal_amount){  # if any vendor total amount of order is less then minimum order amount
                    $delivery_status = 0;
                }
                
                $total_payable_amount = $total_payable_amount + $payable_amount;
                $total_taxable_amount = $total_taxable_amount + $taxable_amount;
                $total_discount_amount = $total_discount_amount + $discount_amount;
                $total_discount_percent = $total_discount_percent + $discount_percent;
                $total_subscription_discount = $total_subscription_discount + $subscription_discount;

                $promoCodeController = new PromoCodeController();
                $promoCodeRequest = new Request();
                $promoCodeRequest->setMethod('POST');
                $promoCodeRequest->request->add(['vendor_id' => $vendorData->vendor_id, 'amount' => $vendorData->product_total_amount]);
                $promoCodeResponse = $promoCodeController->postPromoCodeList($promoCodeRequest)->getData();
                if($promoCodeResponse->status == 'Success'){
                    if(!empty($promoCodeResponse->data)){
                        $is_promo_code_available = 1;
                    }
                }
                $vendorData->is_promo_code_available = $is_promo_code_available;
            }
            $is_percent = 0;
            $amount_value = 0;
            if ($cart->coupon) {
                foreach ($cart->coupon as $ck => $coupon) {
                    if (isset($coupon->promo)) {
                        if ($coupon->promo->promo_type_id == 1) {
                            $is_percent = 1;
                            $total_discount_percent = $total_discount_percent + round($coupon->promo->amount);
                        }
                    }
                }
            }
            if ($is_percent == 1) {
                $total_discount_percent = ($total_discount_percent > 100) ? 100 : $total_discount_percent;
                $total_discount_amount = $total_discount_amount + ($total_payable_amount * $total_discount_percent) / 100;
            }
            if ($amount_value > 0) {
                if($customerCurrency){
                    $amount_value = $amount_value * $customerCurrency->doller_compare;
                }
                $total_discount_amount = $total_discount_amount + $amount_value;
            }
            if (!empty($subscription_features)) {
                $total_discount_amount = $total_discount_amount + $total_subscription_discount;
                $cart->total_subscription_discount = number_format($total_subscription_discount, 2, '.', '');
            }
            $total_payable_amount = $total_payable_amount - $total_discount_amount;
            if ($loyalty_amount_saved > 0) {
                if ($loyalty_amount_saved > $total_payable_amount) {
                    $loyalty_amount_saved =  $total_payable_amount;
                }
                $total_payable_amount = $total_payable_amount - $loyalty_amount_saved;
            }
            $wallet_amount_used = 0;
            if($user){
                if($user->balanceFloat > 0){
                    $wallet_amount_used = $user->balanceFloat;
                    if($customerCurrency){
                        $wallet_amount_used = $user->balanceFloat * $customerCurrency->doller_compare;
                    }
                    if($wallet_amount_used > $total_payable_amount){
                        $wallet_amount_used = $total_payable_amount;
                    }
                    $total_payable_amount = $total_payable_amount - $wallet_amount_used;
                    $cart->wallet_amount_used = number_format($wallet_amount_used, 2, '.', '');
                }
            }
            
            $scheduled = (object)array(
                'scheduled_date_time'=>(($cart->scheduled_slot)?date('Y-m-d',strtotime($cart->scheduled_date_time)):$cart->scheduled_date_time),'slot'=>$cart->scheduled_slot,
            );

            $cart->vendorCnt = $cartData->count();
            $cart->scheduled = $scheduled;
            if($cart->vendorCnt==1){
                $vendorId = $cartData[0]->vendor_id;
                //type must be a : delivery , takeaway,dine_in
                $duration = Vendor::where('id',$vendorId)->select('slot_minutes')->first();
                $slots = (object)$this->showSlot('',$vendorId,'delivery',$duration->slot_minutes);
                $cart->slots = $slots;
                $cart->vendor_id =  $vendorId;
            }else{
                $slots = [];
                $cart->slots = [];
                $cart->vendor_id =  0;
            }
            $cart->schedule_type =  $cart->schedule_type;
            $cart->slotsCnt = count((array)$slots);
            $cart->total_service_fee = number_format($total_service_fee, 2, '.', '');
            $cart->loyalty_amount = number_format($loyalty_amount_saved, 2, '.', '');
            $cart->gross_amount = number_format(($total_payable_amount + $total_discount_amount + $loyalty_amount_saved + $wallet_amount_used - $total_taxable_amount), 2, '.', '');
            $cart->new_gross_amount = number_format(($total_payable_amount + $total_discount_amount), 2, '.', '');
            $cart->total_payable_amount = number_format($total_payable_amount, 2, '.', '');
            $cart->total_discount_amount = number_format($total_discount_amount, 2, '.', '');
            $cart->total_taxable_amount = number_format($total_taxable_amount, 2, '.', '');
            $cart->tip_5_percent = number_format((0.05 * $total_payable_amount), 2, '.', '');
            $cart->tip_10_percent = number_format((0.1 * $total_payable_amount), 2, '.', '');
            $cart->tip_15_percent = number_format((0.15 * $total_payable_amount), 2, '.', '');
            $cart->deliver_status = $delivery_status;
            $cart->action = $action;
            $cart->left_section = view('frontend.cartnew-left')->with(['action' => $action,  'vendor_details' => $vendor_details, 'addresses'=> $user_allAddresses, 'countries'=> $countries, 'cart_dinein_table_id'=> $cart_dinein_table_id, 'preferences' => $preferences])->render();
            $cart->upSell_products = ($upSell_products) ? $upSell_products->first() : collect();
            $cart->crossSell_products = ($crossSell_products) ? $crossSell_products->first() : collect(); 
            
            if($cart->slotsCnt>0){
                $cart->delay_date =  (($delay_date>0)?$delay_date:date('Y-m-d'));
            }else{
                $cart->delay_date =  $delay_date??0;
            }
            
            $cart->pickup_delay_date =  $pickup_delay_date??0;
            $cart->dropoff_delay_date =  $dropoff_delay_date??0;
            $cart->delivery_type =  $code??'D';

            // dd($cart->toArray());
            $cart->products = $cartData->toArray();
        }
        return $cart;
    }

     /**
     * Get Last added product variant
     *
     * @return \Illuminate\Http\Response
     */
    public function getLastAddedProductVariant(Request $request, $domain='')
    {
        try{
            $cartProduct = CartProduct::with('addon')
                ->where('cart_id', $request->cart_id)
                ->where('product_id', $request->product_id)
                ->orderByDesc('created_at')->first();

            return $this->successResponse($cartProduct, '', 200);
        }
        catch(Exception $ex){
            return $this->errorResponse($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Update Quantity
     *
     * @return \Illuminate\Http\Response
     */
    public function updateQuantity(Request $request, $domain = '')
    {
        $cartProduct = TempCartProduct::find($request->cartproduct_id);
        $variant_id = $cartProduct->variant_id;
        $productDetail = Product::with([
            'variant' => function ($sel) use($variant_id) {
                $sel->where('id', $variant_id);
                $sel->groupBy('product_id');
            }
        ])->find($cartProduct->product_id);

        if($productDetail->category->categoryDetail->type_id != 8 && $productDetail->sell_when_out_of_stock == 0){
            if($productDetail->variant[0]->quantity < $request->quantity){
                return $this->errorResponse(__('Maximum quantity already added in your cart'), 422);
            }
        }
        $cartProduct->quantity = $request->quantity;
        $cartProduct->save();
        return $this->successResponse('', 'Successfully Updated', 200);
    }

    /**
     * Delete Cart Product
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteCartProduct(Request $request, $domain = '')
    {
        TempCartProduct::where('id', $request->cartproduct_id)->delete();
        TempCartCoupon::where('vendor_id', $request->vendor_id)->delete();
        TempCartAddon::where('cart_product_id', $request->cartproduct_id)->delete();
        return response()->json(['status' => 'success', 'message' => __('Product removed from cart successfully.') ]);
    }

    /**
     * Empty Cart
     *
     * @return \Illuminate\Http\Response
     */
    public function emptyCartData(Request $request, $domain = '')
    {
        $cart_id = $request->cart_id;
        if (($cart_id != '') && ($cart_id > 0)) {
            // Cart::where('id', $cart_id)->delete();
            CartProduct::where('cart_id', $cart_id)->delete();
            CartCoupon::where('cart_id', $cart_id)->delete();
            CartAddon::where('cart_id', $cart_id)->delete();

         
            return response()->json(['status' => 'success', 'message' => 'Cart has been deleted successfully.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Cart cannot be deleted.']);
        }
    }


    

    /**
     * get Cart Data
     *
     * @return \Illuminate\Http\Response
     */
    public function getCartData(Request $request, $domain = '')
    {
        $cart_details = [];
        $user = Auth::user();
        $curId = Session::get('customerCurrency');
        $langId = Session::get('customerLanguage');
        $address_id = 0;
        if ($user) {
            $cart = Cart::select('id', 'is_gift', 'item_count', 'schedule_type', 'scheduled_date_time','schedule_pickup','schedule_dropoff','scheduled_slot')->with('coupon.promo')->where('status', '0')->where('user_id', $user->id)->first();
        } else {
            $cart = Cart::select('id', 'is_gift', 'item_count', 'schedule_type', 'scheduled_date_time','schedule_pickup','schedule_dropoff','scheduled_slot')->with('coupon.promo')->where('status', '0')->where('unique_identifier', session()->get('_token'))->first();
        }

        if (isset($request->address_id) && !empty($request->address_id)) {
            $address_id = $request->address_id;
            $address = UserAddress::where('user_id', $user->id)->update(['is_primary' => 0]);
            $address = UserAddress::where('user_id', $user->id)->where('id', $address_id)->update(['is_primary' => 1]);
        }
        
        if ($cart) {
            $cart_details = $this->getCart($cart, $address_id,$request->code);
        }
        $client_preference_detail = ClientPreference::first();

        return response()->json(['status' => 'success', 'cart_details' => $cart_details, 'client_preference_detail' => $client_preference_detail]);
    }


    # get delivery fee from dispatcher
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
                        'latitude' => $vendor_details->latitude ?? 30.71728880,
                        'longitude' => $vendor_details->longitude ?? 76.80350870
                    );
                    $location[] = array(
                        'latitude' => $cus_address->latitude ?? 30.717288800000,
                        'longitude' => $cus_address->longitude ?? 76.803508700000
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

    // add ones add in cart for ondemand
    public function postAddToCartAddons(Request $request, $domain = '')
    {
        try {
            $user = Auth::user();
             $addon_ids = $request->addonID;
             $addon_options_ids = $request->addonoptID;
             $langId = Session::get('customerLanguage');

           $addonSets = $addon_ids = $addon_options = array();
            if($request->has('addonID')){
                $addon_ids = $request->addonID;
            }
            if($request->has('addonoptID')){
                $addon_options = $request->addonoptID;
            }
            foreach($addon_options as $key => $opt){
                $addonSets[$addon_ids[$key]][] = $opt;
            }

            if($request->has('addonoptID')){
                $addon = AddonSet::join('addon_set_translations as ast', 'ast.addon_id', 'addon_sets.id')
                ->select('addon_sets.id', 'addon_sets.min_select', 'addon_sets.max_select', 'ast.title')
                ->where('ast.language_id', $langId)
                ->where('addon_sets.status', '!=', '2')
                ->where('addon_sets.id', $request->addonID[0])->first();
                if (!$addon) {
                    return response()->json(['error' => 'Invalid addon or delete by admin. Try again with remove some.'], 404);
                }
                if ($addon->min_select > count($request->addonID)) {
                    return response()->json([
                        'error' => 'Select minimum ' . $addon->min_select . ' options of ' . $addon->title,
                        'data' => $addon
                    ], 404);
                }
                if ($addon->max_select < count($request->addonID)) {
                    return response()->json([
                        'error' => 'You can select maximum ' . $addon->max_select . ' options of ' . $addon->title,
                        'data' => $addon
                    ], 404);
                }
            }

            if(isset($addon_ids) && !empty($addon_ids[0]))
                CartAddon::where('cart_id',$request->cart_id)->where('cart_product_id',$request->cart_product_id)->where('addon_id',$addon_ids[0])->delete();
            else
                CartAddon::where('cart_id',$request->cart_id)->where('cart_product_id',$request->cart_product_id)->delete();

            if (count($addon_options) > 0) {
                $saveAddons = array();
                foreach ($addon_options as $key => $opts) {
                    $saveAddons[] = [
                    'option_id' => $opts,
                    'cart_id' => $request->cart_id,
                    'addon_id' => $addon_ids[$key],
                    'cart_product_id' => $request->cart_product_id,
                ];
                }
                CartAddon::insert($saveAddons);
            }

            return response()->json(['status' => 'success', 'message' => 'Addons Added Successfully!']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    ////////////////// Edit order functions /////////////////////
    public function getProductsInCart(Request $request, $domain='')
    {
        try{
            $order_vendor_id = $request->order_vendor_id;
            $getallproduct = OrderVendor::with(['products.addon'])->where('id', $order_vendor_id)->first();
            $request->request->add(['user_id' => $getallproduct->user_id]);
            foreach($getallproduct->products as $data){
                $request->request->add([
                    'vendor_id' => $data->vendor_id,
                    'product_id' => $data->product_id,
                    'quantity' => $data->quantity,
                    'variant_id' => $data->variant_id
                ]);
                $addonID = OrderProductAddon::where('order_product_id',$data->id)->pluck('addon_id');
                $addonoptID = OrderProductAddon::where('order_product_id',$data->id)->pluck('option_id');
                if(count($addonID)){
                    $request->request->add(['addonID' => $addonID->toArray()]);
                }
                if(count($addonoptID)){
                    $request->request->add(['addonoptID' => $addonoptID->toArray()]);
                }
                $this->postAddToTempCart($request);
            }
            return $this->successResponse('', 'Order added to cart.', 201);
        }
        catch(Exception $ex){
            return $this->errorResponse($ex->getMessage(), $ex->getCode());
        }
    }

    public function postAddToTempCart(Request $request)
    {
        $preference = ClientPreference::first();
        $luxury_option = LuxuryOption::where('title', 'delivery')->first();
        try {
            $cart_detail = [];
            $user_id = $request->user_id;
            $user = User::find($user_id);
            // $addon_ids = $request->addonID;
            // $addon_options_ids = $request->addonoptID;
            $langId = ClientLanguage::where(['is_primary' => 1, 'is_active' => 1])->value('language_id');
            // $new_session_token = session()->get('_token');
            $client_currency = ClientCurrency::where('is_primary', '=', 1)->first();
            $user_id = $user ? $user->id : '';
            $variant_id = $request->variant_id;
            if ($user) {
                $cart_detail['user_id'] = $user_id;
                $cart_detail['created_by'] = $user_id;
            }
            $cart_detail = [
                'is_gift' => 1,
                'status' => '0',
                'item_count' => 0,
                'currency_id' => $client_currency->currency_id,
                'unique_identifier' => '', //!$user ? $new_session_token : '',
            ];
            if ($user) {
                $cart_detail = TempCart::updateOrCreate(['user_id' => $user->id], $cart_detail);
                $already_added_product_in_cart = TempCartProduct::where(["product_id" => $request->product_id, 'cart_id' => $cart_detail->id])->first();
            } else {
                return $this->errorResponse(__('Invalid user data'), 422);
            }
            $productDetail = Product::with([
                'variant' => function ($sel) use($variant_id) {
                    $sel->where('id', $variant_id);
                    $sel->groupBy('product_id');
                }
            ])->find($request->product_id);

            # if product type is not equal to on demand
            if($productDetail->category->categoryDetail->type_id != 8  && $productDetail->sell_when_out_of_stock == 0){
                if(!empty($already_added_product_in_cart)){
                    if($productDetail->variant[0]->quantity <= $already_added_product_in_cart->quantity){
                        return $this->errorResponse(__('Maximum quantity already added in your cart s'), 422);
                    }
                    if($productDetail->variant[0]->quantity <= ($already_added_product_in_cart->quantity + $request->quantity)){
                        $request->quantity = $productDetail->variant[0]->quantity - $already_added_product_in_cart->quantity;
                    }
                }
                if($productDetail->variant[0]->quantity < $request->quantity){
                    if($productDetail->variant[0]->quantity == 0){
                        $productDetail->variant[0]->quantity = 1;
                    }
                     $request->quantity = $productDetail->variant[0]->quantity;
                }
            }


            $addonSets = $addon_ids = $addon_options = array();
            if($request->has('addonID')){
                $addon_ids = $request->addonID;
            }
            if($request->has('addonoptID')){
                $addon_options = $request->addonoptID;
            }
            foreach($addon_options as $key => $opt){
                $addonSets[$addon_ids[$key]][] = $opt;
            }
            foreach($addonSets as $key => $value){
                $addon = AddonSet::join('addon_set_translations as ast', 'ast.addon_id', 'addon_sets.id')
                            ->select('addon_sets.id', 'addon_sets.min_select', 'addon_sets.max_select', 'ast.title')
                            ->where('ast.language_id', $langId)
                            ->where('addon_sets.status', '!=', '2')
                            ->where('addon_sets.id', $key)->first();
                if(!$addon){
                    return $this->errorResponse(__('Invalid addon or delete by admin. Try again with remove some.'), 422);
                }
                if($addon->min_select > count($value)){
                    return $this->errorResponse('Select minimum ' . $addon->min_select .' options of ' .$addon->title, 422);
                }
                if($addon->max_select < count($value)){
                    return $this->errorResponse('You can select maximum ' . $addon->min_select .' options of ' .$addon->title, 422);
                }
            }
            $oldquantity = $isnew = 0;
            $cart_product_detail = [
                'status'  => '0',
                'is_tax_applied'  => '1',
                'created_by'  => $user_id,
                'cart_id'  => $cart_detail->id,
                'quantity'  => $request->quantity,
                'vendor_id'  => $request->vendor_id,
                'product_id' => $request->product_id,
                'variant_id'  => $request->variant_id,
                'currency_id' => $client_currency->currency_id,
                'luxury_option_id' => ($luxury_option) ? $luxury_option->id : 0
            ];

            $checkVendorId = TempCartProduct::where('cart_id', $cart_detail->id)->where('vendor_id', '!=', $request->vendor_id)->first();

            if ($luxury_option) {
                $checkCartLuxuryOption = TempCartProduct::where('luxury_option_id', '!=', $luxury_option->id)->where('cart_id', $cart_detail->id)->first();
                if ($checkCartLuxuryOption) {
                    TempCartProduct::where('cart_id', $cart_detail->id)->delete();
                }
                if ($luxury_option->id == 2 || $luxury_option->id == 3) {
                    if ($checkVendorId) {
                        TempCartProduct::where('cart_id', $cart_detail->id)->delete();
                    }else{
                        $checkVendorTableAdded = TempCartProduct::where('cart_id', $cart_detail->id)->where('vendor_id', $request->vendor_id)->whereNotNull('vendor_dinein_table_id')->first();
                        $cart_product_detail['vendor_dinein_table_id'] = ($checkVendorTableAdded) ? $checkVendorTableAdded->vendor_dinein_table_id : NULL;
                    }
                }
            }
            if ( (isset($preference->isolate_single_vendor_order)) && ($preference->isolate_single_vendor_order == 1) ) {
                if ($checkVendorId) {
                    TempCartProduct::where('cart_id', $cart_detail->id)->delete();
                }
            }

            $cartProduct = TempCartProduct::where('product_id', $request->product_id)->where('variant_id', $request->variant_id)->where('cart_id', $cart_detail->id)->first();
            if(!$cartProduct){
                $isnew = 1;
            }else{
                $checkaddonCount = TempCartAddon::where('cart_product_id', $cartProduct->id)->count();
                if(count($addon_ids) != $checkaddonCount){
                    $isnew = 1;
                }else{
                    foreach ($addon_options as $key => $opts) {
                        $cart_addon = TempCartAddon::where('cart_product_id', $cartProduct->id)
                                    ->where('addon_id', $addon_ids[$key])
                                    ->where('option_id', $opts)->first();

                        if(!$cart_addon){
                            $isnew = 1;
                        }
                    }
                }
            }

            if($isnew == 1){
                $cartProduct = TempCartProduct::create($cart_product_detail);
                if(!empty($addon_ids) && !empty($addon_options)){
                    $saveAddons = array();
                    foreach ($addon_options as $key => $opts) {
                        $saveAddons[] = [
                            'option_id' => $opts,
                            'cart_id' => $cart_detail->id,
                            'addon_id' => $addon_ids[$key],
                            'cart_product_id' => $cartProduct->id,
                        ];
                    }
                    if(!empty($saveAddons)){
                        TempCartAddon::insert($saveAddons);
                    }
                }
            }else{
                $cartProduct->quantity = $cartProduct->quantity + $request->quantity;
                $cartProduct->save();
            }

            return $this->successResponse('', __('Product Added Successfully!'), 201);
        }
        catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), $ex->getCode());
        }
    }

}
