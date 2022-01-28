<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Illuminate\Support\Facades\Session;
use Validation;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Country;
use Client;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Controllers\Api\v1\PromoCodeController;
use App\Models\{User, Product, Cart, ProductVariantSet, ProductVariant, CartProduct, CartCoupon, ClientCurrency, Brand, CartAddon, UserDevice, AddonSet, Client as ModelsClient, UserAddress, ClientPreference, LuxuryOption, Vendor, LoyaltyCard, SubscriptionInvoicesUser, VendorDineinCategory, VendorDineinTable, VendorDineinCategoryTranslation, VendorDineinTableTranslation, OrderVendor, OrderProductAddon, OrderTax, OrderProduct, OrderProductPrescription, VendorOrderStatus, VendorSlot};
use GuzzleHttp\Client as GCLIENT;
use Log;
class CartController extends BaseController
{
    use ApiResponser;

    private $field_status = 2;

    public function index(Request $request)
    {
        try {

            if(($request->has('gateway')) && ($request->gateway != '')){
                if($request->has('order')){
                    $order = Order::where('order_number', $request->order)->first();
                    if($order){
                        if($request->status == 0){
                            $order_products = OrderProduct::select('id')->where('order_id', $order->id)->get();
                            foreach($order_products as $order_prod){
                                OrderProductAddon::where('order_product_id', $order_prod->id)->delete();
                            }
                            OrderProduct::where('order_id', $order->id)->delete();
                            OrderProductPrescription::where('order_id', $order->id)->delete();
                            VendorOrderStatus::where('order_id', $order->id)->delete();
                            OrderVendor::where('order_id', $order->id)->delete();
                            OrderTax::where('order_id', $order->id)->delete();
                            $order->delete();
                        }
                    }
                }
            }

            $user = Auth::user();
            if (!$user->id) {
                $cart = Cart::where('unique_identifier', $user->system_user);
            } else {
                $cart = Cart::where('user_id', $user->id);
            }
            $cart = $cart->first();
            if ($cart) {
                $cartData = $this->getCart($cart, $user->language, $user->currency, $request->type);
                return $this->successResponse($cartData);
            }
            return $this->successResponse($cart);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**   check auth and system user to add product in cart    */
    public function userCheck()
    {
        $user = Auth::user();
        if ($user->id && $user->id > 0) {
            $user_id = $user->id;
        } else {
            if (empty($user->system_user)) {
                return response()->json(['error' => 'System id should not be empty.'], 404);
            }
            $user = User::where('system_id', Auth::user()->system_user)->first();
            $val = Auth::user()->system_user;
            if (!$user) {
                $user = new User;
                $user->name = "System User";
                $user->email = $val . "@email.com";
                $user->password = Hash::make($val);
                $user->system_id = $val;
                $user->save();
            }
            $user_id = $user->id;
        }
        return $user_id;
    }

    /**     * Add product In Cart    *           */
    public function add(Request $request)
    {
        try {
            $preference = ClientPreference::first();
            $luxury_option = LuxuryOption::where('title', $request->type)->first();
            $user = Auth::user();
            $langId = $user->language;
            $user_id = $user->id;
            $unique_identifier = '';
            if (!$user_id) {
                if (empty($user->system_user)) {
                    return $this->errorResponse(__('System id should not be empty.'), 404);
                }
                $unique_identifier = $user->system_user;
            }

            $product = Product::where('sku', $request->sku)->first();
            if (!$product) {
                return $this->errorResponse(__('Invalid product.'), 404);
            }
            $productVariant = ProductVariant::where('product_id', $product->id)->where('id', $request->product_variant_id)->first();
            if (!$productVariant) {
                return $this->errorResponse(__('Invalid product variant.'), 404);
            }

            if ($product->category->categoryDetail->type_id == 8) {
            } else {
                if ( ($product->sell_when_out_of_stock == 0) && ($productVariant->quantity < $request->quantity) ) {
                    return $this->errorResponse('You Can not order more than ' . $productVariant->quantity . ' quantity.', 404);
                }
            }


            $addonSets = $addon_ids = $addon_options = array();
            if ($request->has('addon_ids')) {
                $addon_ids = $request->addon_ids;
            }
            if ($request->has('addon_options')) {
                $addon_options = $request->addon_options;
            }
            foreach ($addon_options as $key => $opt) {
                $addonSets[$addon_ids[$key]][] = $opt;
            }
            foreach ($addonSets as $key => $value) {
                $addon = AddonSet::join('addon_set_translations as ast', 'ast.addon_id', 'addon_sets.id')
                    ->select('addon_sets.id', 'addon_sets.min_select', 'addon_sets.max_select', 'ast.title')
                    ->where('ast.language_id', $langId)
                    ->where('addon_sets.status', '!=', '2')
                    ->where('addon_sets.id', $key)->first();
                if (!$addon) {
                    return $this->errorResponse(__('Invalid addon or delete by admin. Try again with remove some.'), 404);
                }
                if ($addon->min_select > count($value)) {
                    return response()->json([
                        "status" => "Error",
                        'message' => 'Select minimum ' . $addon->min_select . ' options of ' . $addon->title,
                        'data' => $addon
                    ], 404);
                }
                if ($addon->max_select < count($value)) {
                    return response()->json([
                        "status" => "Error",
                        'message' => 'You can select maximum ' . $addon->min_select . ' options of ' . $addon->title,
                        'data' => $addon
                    ], 404);
                }
            }
            $client_currency = ClientCurrency::where('is_primary', '=', 1)->first();
            $cart_detail = [
                'is_gift' => 0,
                'status' => '0',
                'item_count' => 0,
                'user_id' => $user->id,
                'created_by' => $user->id,
                'unique_identifier' => $unique_identifier,
                'currency_id' => $client_currency->currency_id,
            ];
            if (!empty($user_id)) {
                $cart_detail = Cart::updateOrCreate(['user_id' => $user->id], $cart_detail);
            } else {
                $cart_detail = Cart::updateOrCreate(['unique_identifier' => $unique_identifier], $cart_detail);
            }

            $checkVendorId = CartProduct::where('cart_id', $cart_detail->id)->where('vendor_id', '!=', $product->vendor_id)->first();

            if ($luxury_option) {
                $checkCartLuxuryOption = CartProduct::where('luxury_option_id', '!=', $luxury_option->id)->where('cart_id', $cart_detail->id)->first();
                if ($checkCartLuxuryOption) {
                    return $this->errorResponse(['error' => __('You are adding products in different mods'), 'alert' => '1'], 404);
                }
                if ($luxury_option->id == 2 || $luxury_option->id == 3) {
                    if ($checkVendorId) {
                        return $this->errorResponse(['error' => __('Your cart has existing items from another vendor'), 'alert' => '1'], 404);
                    }
                }
            }

            if ((isset($preference->isolate_single_vendor_order)) && ($preference->isolate_single_vendor_order == 1)) {
                if ($checkVendorId) {
                    CartProduct::where('cart_id', $cart_detail->id)->delete();
                }
            }

            if ($cart_detail->id > 0) {
                $oldquantity = $isnew = 0;
                $cart_product_detail = [
                    'status'  => '0',
                    'is_tax_applied'  => '1',
                    'created_by'  => $user_id,
                    'product_id' => $product->id,
                    'cart_id'  => $cart_detail->id,
                    'quantity'  => $request->quantity,
                    'vendor_id'  => $product->vendor_id,
                    'variant_id'  => $request->product_variant_id,
                    'currency_id' => $client_currency->currency_id,
                    'luxury_option_id' => $luxury_option ? $luxury_option->id : 1,
                ];
                $cartProduct = CartProduct::where('cart_id', $cart_detail->id)
                    ->where('product_id', $product->id)
                    ->where('variant_id', $productVariant->id)->first();

                if (!$cartProduct) {
                    $isnew = 1;
                } else {
                    $checkaddonCount = CartAddon::where('cart_product_id', $cartProduct->id)->count();
                    if (count($addon_ids) != $checkaddonCount) {
                        $isnew = 1;
                    } else {
                        foreach ($addon_options as $key => $opts) {
                            $cart_addon = CartAddon::where('cart_product_id', $cartProduct->id)
                                ->where('addon_id', $addon_ids[$key])
                                ->where('option_id', $opts)->first();
                            if (!$cart_addon) {
                                $isnew = 1;
                            }
                        }
                    }
                }
                if ($isnew == 1) {
                    $cartProduct = CartProduct::create($cart_product_detail);
                    if (!empty($addon_ids) && !empty($addon_options)) {
                        $saveAddons = array();
                        foreach ($addon_options as $key => $opts) {
                            $saveAddons[] = [
                                'option_id' => $opts,
                                'cart_id' => $cart_detail->id,
                                'addon_id' => $addon_ids[$key],
                                'cart_product_id' => $cartProduct->id,
                            ];
                        }
                        if (!empty($saveAddons)) {
                            CartAddon::insert($saveAddons);
                        }
                    }
                } else {
                    $cartProduct->quantity = $cartProduct->quantity + $request->quantity;
                    $cartProduct->save();
                }
            }
            $cartData = $this->getCart($cart_detail, $user->language, $user->currency, $request->type);
            if ($cartData && !empty($cartData)) {
                $cartData->cart_product_id = $cartProduct->id;
                $product_total_quantity_in_cart = CartProduct::where(['cart_id'=>$cartProduct->cart_id,'product_id'=> $product->id])->sum('quantity');
                $cartData->product_total_qty_in_cart = intval($product_total_quantity_in_cart);
                return $this->successResponse($cartData);
            } else {
                return $this->successResponse($cartData);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get Last added product variant
     *
     * @return \Illuminate\Http\Response
     */
    public function getLastAddedProductVariant(Request $request)
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
     * Get current product variants with different addons
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductVariantWithDifferentAddons(Request $request)
    {
        try{
            $langId = Session::get('customerLanguage');
            $cur_ids = Session::get('customerCurrency');
            if(isset($cur_ids) && !empty( $cur_ids)){
                $clientCurrency = ClientCurrency::where('currency_id','=', $cur_ids)->first();
            }else{
                $clientCurrency = ClientCurrency::where('is_primary','=', 1)->first();
            }

            $cartProducts = CartProduct::with(['product.translation' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId)->groupBy('product_translations.language_id');
            },
            'product.media.image',
            'pvariant.media.pimage.image',
            'vendor.slot.day', 'vendor.slotDate',
            ])
            ->where('cart_id', $request->cart_id)
            ->where('product_id', $request->product_id)
            ->select('*','id as add_on_set_and_option')->orderByDesc('created_at')->get();

            $multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
            foreach ($cartProducts as $key => $cart) {
                $cart->is_vendor_closed = 0;
                $cart->variant_multiplier = $multiplier;
                $variant_price = ($cart->pvariant) ? ($cart->pvariant->price * $multiplier) : 0;

                $product = $cart->product;
                $product->translation_title = ($product->translation->isNotEmpty()) ? $product->translation->first()->title : $product->sku;

                if($cart->pvariant && $cart->pvariant->media->isNotEmpty()){
                    $image_fit = $cart->pvariant->media->first()->pimage->image->path['image_fit'];
                    $image_path = $cart->pvariant->media->first()->pimage->image->path['image_path'];
                    $product->product_image = $image_fit . '300/300' . $image_path;
                }elseif($product->media->isNotEmpty()){
                    $image_fit = $product->media->first()->image->path['image_fit'];
                    $image_path = $product->media->first()->image->path['image_path'];
                    $product->product_image = $image_fit . '300/300' . $image_path;
                }else{
                    $product->product_image = $this->loadDefaultImage();
                }

                $addon_set = $cart->add_on_set_and_option;
                foreach ($addon_set as $skey => $set) {
                    $set->addon_set_translation_title = ($set->translation->isNotEmpty()) ? $set->translation->first()->title : $set->title;
                    foreach ($set->options as $okey => $option) {
                        $option->option_translation_title = ($option->translation->isNotEmpty()) ? $option->translation->first()->title : $option->title;
                        $opt_price_in_doller_compare = $option->price * $multiplier;
                        $variant_price = $variant_price + $opt_price_in_doller_compare;
                    }
                }
                $cart->variant_price = $variant_price;
                $cart->addon_set = $addon_set;
                $cart->total_variant_price = number_format($cart->quantity * $variant_price, 2, '.', '');

                if($cart->vendor->show_slot == 0){
                    if( ($cart->vendor->slotDate->isEmpty()) && ($cart->vendor->slot->isEmpty()) ){
                        $cart->is_vendor_closed = 1;
                    }else{
                        $cart->is_vendor_closed = 0;
                    }
                }
                unset($cartProducts[$key]->add_on_set_and_option);
            }

            return $this->successResponse($cartProducts, '', 200);
        }
        catch(Exception $ex){
            return $this->errorResponse($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     *    update quantity in cart
     **/
    public function updateQuantity(Request $request)
    {
        $user = Auth::user();
        if ($request->quantity < 1) {
            return response()->json(['error' => __('Quantity should not be less than 1')], 422);
        }
        $cart = Cart::where('user_id', $user->id)->where('id', $request->cart_id)->first();
        if (!$cart) {
            return response()->json(['error' => __('User cart not exist.')], 404);
        }
        $cartProduct = CartProduct::where('cart_id', $cart->id)->where('id', $request->cart_product_id)->first();
        if (!$cartProduct) {
            return response()->json(['error' => __('Product not exist in cart.')], 404);
        }
        $cartProduct->quantity = $request->quantity;
        $cartProduct->save();
        $totalProducts = CartProduct::where('cart_id', $cart->id)->sum('quantity');
        $cart->item_count = $totalProducts;
        $cart->save();
        $cartData = $this->getCart($cart, $user->language, $user->currency, $request->type);
        return response()->json([
            'data' => $cartData,
        ]);
    }

    public function getItemCount(Request $request)
    {
        $cart = Cart::where('user_id', Auth::user()->id)->where('id', $request->cart_id)->first();
        if (!$cart) {
            return response()->json(['error' => __('User cart not exist.')], 404);
        }
        $totalProducts = CartProduct::where('cart_id', $cart->id)->sum('quantity');
        $cart->item_count = $totalProducts;
        $cart->save();
        return response()->json([
            'total_item' => $cart->item_count,
        ]);
    }

    public function removeItem(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $cart = Cart::where('id', $request->cart_id);
        if (!$user_id || $user_id < 1) {
            if (empty($user->system_user)) {
                return $this->errorResponse('System id should not be empty.', 404);
            }
            $cart = $cart->where('unique_identifier', $user->system_user);
        } else {
            $cart = $cart->where('user_id', $user->id);
        }
        $cart = $cart->first();
        if (!$cart) {
            return response()->json(['error' => __('Cart not exist')], 404);
        }
        $cartProduct = CartProduct::where('cart_id', $cart->id)->where('id', $request->cart_product_id)->first();
        if (!$cartProduct) {
            return response()->json(['error' => __('Product not exist in cart.')], 404);
        }
        $cartProduct->delete();
        $totalProducts = CartProduct::where('cart_id', $cart->id)->sum('quantity');
        if (!$totalProducts || $totalProducts < 1) {
            $cart->delete();
            return response()->json([
                "message" => __("Product removed from cart successfully."),
                'data' => array(),
            ]);
        }
        $cart->item_count = $totalProducts;
        $cart->save();
        $cartData = $this->getCart($cart, $user->language, $user->currency, $request->type);
        return response()->json([
            "message" => __("Product removed from cart successfully."),
            'data' => $cartData,
        ]);
    }

    /**         *       Empty cart       *          */
    public function emptyCart($cartId = 0)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $cart = Cart::where('id', '>', 0);
        if (!$user_id || $user_id < 1) {
            if (empty($user->system_user)) {
                return $this->errorResponse('System id should not be empty.', 404);
            }
            $cart = $cart->where('unique_identifier', $user->system_user);
        } else {
            $cart = $cart->where('user_id', $user->id);
        }
        $cart->delete();
        return response()->json(['message' => __('Empty cart successfully.')]);
    }
    /**         *       Empty cart       *          */
    public function getCart($cart, $langId = '1', $currency = '1', $type = 'delivery')
    {
        $preferences = ClientPreference::first();
        $clientCurrency = ClientCurrency::where('currency_id', $currency)->first();
        if (!$cart) {
            return false;
        }
        $nowdate = Carbon::now()->toDateTimeString();
        $vondorCnt = 0;
        $address = [];
        $latitude = '';
        $longitude = '';
        $address_id = 0;
        $delivery_status = 1;
        $cartID = $cart->id;
        $upSell_products = collect();
        $crossSell_products = collect();
        $delifproductnotexist = CartProduct::where('cart_id', $cartID)->doesntHave('product')->delete();
        $cartData = CartProduct::with([
            'vendor', 'coupon' => function ($qry) use ($cartID) {
                $qry->where('cart_id', $cartID);
            }, 'coupon.promo.details', 'vendorProducts.pvariant.media.image', 'vendorProducts.product.media.image',
            'vendorProducts.pvariant.vset.variantDetail.trans' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.pvariant.vset.optionData.trans' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.product.translation' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                $q->where('language_id', $langId);
                $q->groupBy('product_id');
            },
            'vendorProducts' => function ($qry) use ($cartID) {
                $qry->where('cart_id', $cartID);
            },
            'vendorProducts.addon.set' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.addon.option' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            }, 'vendorProducts.product.taxCategory.taxRate',
        ])->select('vendor_id', 'vendor_dinein_table_id')->where('cart_id', $cartID)->groupBy('vendor_id')->orderBy('created_at', 'asc')->get();
        $loyalty_amount_saved = 0;
        $subscription_features = array();
        if ($cart->user_id) {
            $now = Carbon::now()->toDateTimeString();
            $user_subscription = SubscriptionInvoicesUser::with('features')
                ->select('id', 'user_id', 'subscription_id')
                ->where('user_id', $cart->user_id)
                ->where('end_date', '>', $now)
                ->orderBy('end_date', 'desc')->first();
            if ($user_subscription) {
                foreach ($user_subscription->features as $feature) {
                    $subscription_features[] = $feature->feature_id;
                }
            }
            $user = User::find($cart->user_id);
            $cart->scheduled_date_time = !empty($cart->scheduled_date_time) ? convertDateTimeInTimeZone($cart->scheduled_date_time, $user->timezone, 'Y-m-d\TH:i') : NULL;
            $cart->schedule_pickup = !empty($cart->schedule_pickup) ? convertDateTimeInTimeZone($cart->schedule_pickup, $user->timezone, 'Y-m-d\TH:i') : NULL;
            $cart->schedule_dropoff = !empty($cart->schedule_dropoff) ? convertDateTimeInTimeZone($cart->schedule_dropoff, $user->timezone, 'Y-m-d\TH:i') : NULL;
            $address = UserAddress::where('user_id', $cart->user_id)->where('is_primary', 1)->first();
            $address_id = ($address) ? $address->id : 0;
        }
        $latitude = ($address) ? $address->latitude : '';
        $longitude = ($address) ? $address->longitude : '';
        $total_payable_amount = $total_subscription_discount = $total_discount_amount = $total_discount_percent = $total_taxable_amount = 0.00;
        $total_tax = $total_paying = $total_disc_amount = 0.00;
        $item_count = 0;
        $total_delivery_amount = 0;
        $order_sub_total = 0;
        if ($cartData) {
            $cart_dinein_table_id = NULL;
            $action = $type;
            $vendor_details = [];
            $tax_details = [];
            $is_vendor_closed = 0;
            $delay_date = 0;
            $pickup_delay_date = 0;
            $dropoff_delay_date = 0;
            $total_service_fee = 0;
            $product_out_of_stock = 0;
            $PromoFreeDeliver = 0;
            $PromoDelete = 0;
            $couponApplied = 0;
            foreach ($cartData as $ven_key => $vendorData) {
                $is_promo_code_available = 0;
                $vendor_products_total_amount = $codeApplied = $is_percent = $proSum = $proSumDis = $taxable_amount = $subscription_discount = $discount_amount = $discount_percent = $deliver_charge = $delivery_fee_charges = 0.00;
                $delivery_count = 0;
                $cart_dinein_table_id = $vendorData->vendor_dinein_table_id;
                $vendorData->vendor->closed_store_order_scheduled = $vendorData->vendor->closed_store_order_scheduled;
                if ($action != 'delivery') {
                    $vendor_details['vendor_address'] = $vendorData->vendor->select('id', 'latitude', 'longitude', 'address')->where('id', $vendorData->vendor_id)->first();
                    if ($action == 'dine_in') {
                        $vendor_tables = VendorDineinTable::where('vendor_id', $vendorData->vendor_id)->with('category')->get();
                        foreach ($vendor_tables as $vendor_table) {
                            $vendor_table->qr_url = url('/vendor/' . $vendorData->vendor->slug . '/?id=' . $vendorData->vendor_id . '&name=' . $vendorData->vendor->name . '&table=' . $vendor_table->id);
                        }
                        $vendor_details['vendor_tables'] = $vendor_tables;
                    }
                } else {
                    if ((isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1)) {
                        if ($address_id > 0) {
                            $serviceArea = $vendorData->vendor->whereHas('serviceArea', function ($query) use ($latitude, $longitude) {
                                $query->select('vendor_id')
                                    ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $latitude . " " . $longitude . ")'))");
                            })->where('id', $vendorData->vendor_id)->get();
                        }
                    }
                }

                $ttAddon = $payable_amount = $is_coupon_applied = $coupon_removed = 0;
                $coupon_removed_msg = '';
                $deliver_charge = 0;
                $delivery_fee_charges = 0.00;
                $couponData = $couponProducts = array();
                // if (!empty($vendorData->coupon->promo) && ($vendorData->coupon->vendor_id == $vendorData->vendor_id)) {
                //     $now = Carbon::now()->toDateTimeString();
                //     $minimum_spend = 0;
                //     if (isset($vendorData->coupon->promo->minimum_spend)) {
                //         $minimum_spend = $vendorData->coupon->promo->minimum_spend * $clientCurrency->doller_compare;
                //     }
                //     if ($vendorData->coupon->promo->expiry_date < $now) {
                //         $coupon_removed = 1;
                //         $coupon_removed_msg = __('Coupon code is expired.');
                //     } else {
                //         $couponData['coupon_id'] =  $vendorData->coupon->promo->id;
                //         $couponData['name'] =  $vendorData->coupon->promo->name;
                //         $couponData['disc_type'] = ($vendorData->coupon->promo->promo_type_id == 1) ? 'Percent' : 'Amount';
                //         $couponData['expiry_date'] =  $vendorData->coupon->promo->expiry_date;
                //         $couponData['allow_free_delivery'] =  $vendorData->coupon->promo->allow_free_delivery;
                //         $couponData['minimum_spend'] =  $vendorData->coupon->promo->minimum_spend;
                //         $couponData['first_order_only'] = $vendorData->coupon->promo->first_order_only;
                //         $couponData['restriction_on'] = ($vendorData->coupon->promo->restriction_on == 1) ? 'Vendor' : 'Product';

                //         $is_coupon_applied = 1;
                //         if ($vendorData->coupon->promo->promo_type_id == 1) {
                //             $is_percent = 1;
                //             $discount_percent = round($vendorData->coupon->promo->amount);


                //         } else {
                //             $discount_amount = $vendorData->coupon->promo->amount * $clientCurrency->doller_compare;


                //         }
                //         if ($vendorData->coupon->promo->restriction_on == 0) {
                //             foreach ($vendorData->coupon->promo->details as $key => $value) {
                //                 $couponProducts[] = $value->refrence_id;
                //             }
                //         }

                //     }
                // }

                foreach ($vendorData->vendorProducts as $pkey => $prod) {
                    if(isset($prod->product) && !empty($prod->product)){

                        if($prod->product->sell_when_out_of_stock == 0 && $prod->product->has_inventory == 1){
                            $quantity_check = productvariantQuantity($prod->variant_id);
                            if($quantity_check < $prod->quantity ){
                                $delivery_status=0;
                                $product_out_of_stock = 1;
                            }
                        }
                        $prod->product_out_of_stock =  $product_out_of_stock;

                        $price_in_currency = $price_in_doller_compare = $pro_disc = $quantity_price = 0;
                        $variantsData = $taxData = $vendorAddons = array();
                        $divider = (empty($prod->doller_compare) || $prod->doller_compare < 0) ? 1 : $prod->doller_compare;
                        $price_in_currency = $prod->pvariant ? $prod->pvariant->price : 0;
                        $price_in_doller_compare = $price_in_currency * $clientCurrency->doller_compare;
                        $quantity_price = $price_in_doller_compare * $prod->quantity;
                        $item_count = $item_count + $prod->quantity;
                        $proSum = $proSum + $quantity_price;
                        $vendor_products_total_amount = $vendor_products_total_amount + $quantity_price;
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

                        if ($prod->pvariant) {
                            $variantsData['price']              = $price_in_currency;
                            $variantsData['id']                 = $prod->pvariant->id;
                            $variantsData['sku']                = ucfirst($prod->pvariant->sku);
                            $variantsData['title']              = $prod->pvariant->title;
                            $variantsData['barcode']            = $prod->pvariant->barcode;
                            $variantsData['product_id']         = $prod->pvariant->product_id;
                            $variantsData['multiplier']         = $clientCurrency->doller_compare;
                            $variantsData['gross_qty_price']    = $price_in_doller_compare * $prod->quantity;
                            // if (!empty($vendorData->coupon->promo) && ($vendorData->coupon->promo->restriction_on == 0) && in_array($prod->product_id, $couponProducts)) {
                            //     $pro_disc = $discount_amount;
                            //     if ($minimum_spend <= $quantity_price) {
                            //         if ($is_percent == 1) {
                            //             $pro_disc = ($quantity_price * $discount_percent) / 100;
                            //         }
                            //         $quantity_price = $quantity_price - $pro_disc;
                            //         $proSumDis = $proSumDis + $pro_disc;
                            //         if ($quantity_price < 0) {
                            //             $quantity_price = 0;
                            //         }
                            //         $codeApplied = 1;
                            //     } else {
                            //         $variantsData['coupon_msg'] = "Spend Minimum " . $minimum_spend . " to apply this coupon";
                            //         $variantsData['coupon_not_appiled'] = 1;
                            //     }


                            // }

                            $variantsData['discount_amount'] = $pro_disc;
                            $variantsData['coupon_applied'] = $codeApplied;
                            $variantsData['quantity_price'] = $quantity_price;
                            $payable_amount = $payable_amount + $quantity_price;
                            if (!empty($prod->product->taxCategory) && count($prod->product->taxCategory->taxRate) > 0) {
                                foreach ($prod->product->taxCategory->taxRate as $tckey => $tax_value) {
                                    $rate = round($tax_value->tax_rate);
                                    $tax_amount = ($price_in_doller_compare * $rate) / 100;
                                    $product_tax = $quantity_price * $rate / 100;
                                    $taxData[$tckey]['rate'] = $rate;
                                    $taxData[$tckey]['tax_amount'] = $tax_amount;
                                    $taxData[$tckey]['product_tax'] = $product_tax;
                                    $taxable_amount = $taxable_amount + $product_tax;
                                    $taxData[$tckey]['sku'] = ucfirst($prod->pvariant->sku);
                                    $taxData[$tckey]['identifier'] = $tax_value->identifier;
                                    $tax_details[] = array(
                                        'rate' => $rate,
                                        'tax_amount' => $tax_amount,
                                        'identifier' => $tax_value->identifier,
                                        'sku' => ucfirst($prod->pvariant->sku),
                                    );
                                }
                            }
                            $prod->taxdata = $taxData;
                            if ($action == 'delivery') {
                                if (!empty($prod->product->Requires_last_mile) && ($prod->product->Requires_last_mile == 1)) {
                                    $deliver_charge = $this->getDeliveryFeeDispatcher($vendorData->vendor_id);
                                    if (!empty($deliver_charge) && $delivery_count == 0) {
                                        $delivery_count = 1;
                                        $prod->deliver_charge = number_format($deliver_charge, 2, '.', '');
                                       // $payable_amount = $payable_amount + $deliver_charge;
                                        $order_sub_total = $order_sub_total + $deliver_charge;
                                        $delivery_fee_charges = $deliver_charge;
                                    }
                                }
                            }
                            if (!empty($prod->addon)) {
                                foreach ($prod->addon as $ck => $addons) {
                                    $opt_quantity_price = 0;
                                    $opt_price_in_currency = $addons->option ? $addons->option->price : 0;
                                    $opt_price_in_doller_compare = $opt_price_in_currency * $clientCurrency->doller_compare;
                                    $opt_quantity_price = $opt_price_in_doller_compare * $prod->quantity;
                                    $vendorAddons[$ck]['quantity'] = $prod->quantity;
                                    $vendorAddons[$ck]['addon_id'] = $addons->addon_id;
                                    $vendorAddons[$ck]['option_id'] = $addons->option_id;
                                    $vendorAddons[$ck]['price'] = $opt_price_in_currency;
                                    $vendorAddons[$ck]['addon_title'] = $addons->set->title;
                                    $vendorAddons[$ck]['quantity_price'] = $opt_quantity_price;
                                    $vendorAddons[$ck]['option_title'] = $addons->option ? $addons->option->title : 0;
                                    $vendorAddons[$ck]['price_in_cart'] = $addons->option->price;
                                    $vendorAddons[$ck]['cart_product_id'] = $addons->cart_product_id;
                                    $vendorAddons[$ck]['multiplier'] = $clientCurrency->doller_compare;
                                    $ttAddon = $ttAddon + $opt_quantity_price;
                                    $payable_amount = $payable_amount + $opt_quantity_price;
                                    $order_sub_total = $order_sub_total + $opt_quantity_price;
                                }
                            }
                            unset($prod->addon);
                            unset($prod->pvariant);
                        }
                        $variant_options = [];
                        if ($prod->pvariant) {
                            foreach ($prod->pvariant->vset as $variant_set_option) {
                                $variant_options[] = array(
                                    'option' => $variant_set_option->optionData->trans->title,
                                    'title' => $variant_set_option->variantDetail->trans->title,
                                );
                            }
                        }
                        $prod->variants = $variantsData;
                        $prod->variant_options = $variant_options;
                        $prod->product_addons = $vendorAddons;

                        $product = Product::with([
                            'variant' => function ($sel) {
                                $sel->groupBy('product_id');
                            },
                            'variant.media.pimage.image', 'upSell', 'crossSell', 'vendor', 'media.image', 'translation' => function ($q) use ($langId) {
                                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                                $q->where('language_id', $langId);
                            }])->select('id', 'sku', 'inquiry_only', 'url_slug', 'weight', 'weight_unit', 'vendor_id', 'has_variant', 'has_inventory', 'averageRating','minimum_order_count','batch_count')
                            ->where('url_slug', $prod->product->url_slug)
                            ->first();
                        $doller_compare = ($clientCurrency) ? $clientCurrency->doller_compare : 1;
                        $up_prods = $this->metaProduct($langId, $doller_compare, 'upSell', $product->upSell);
                        if($up_prods){
                            $upSell_products->push($up_prods);
                        }
                        $cross_prods = $this->metaProduct($langId, $doller_compare, 'crossSell', $product->crossSell);
                        if($cross_prods){
                            $crossSell_products->push($cross_prods);
                        }


                    }
                }
                // echo $payable_amount ;
                // exit();
                $couponGetAmount = $payable_amount ;
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
                        if ($PromoDelete !=1) {
                            if(!($vendorData->coupon->promo->expiry_date >= $nowdate) ){
                                $cart->coupon()->delete();
                                $vendorData->coupon()->delete();
                                unset($vendorData->coupon);
                                $PromoDelete =1;
                            }
                        }
                        if ( $PromoDelete !=1) {

                            $minimum_spend = 0;
                            if (isset($vendorData->coupon->promo->minimum_spend)) {
                                $minimum_spend = $vendorData->coupon->promo->minimum_spend * $clientCurrency->doller_compare;
                            }

                            $maximum_spend = 0;
                            if (isset($vendorData->coupon->promo->maximum_spend)) {
                                $maximum_spend = $vendorData->coupon->promo->maximum_spend * $clientCurrency->doller_compare;
                            }

                            if( ($minimum_spend <= $payable_amount ) && ($maximum_spend >= $payable_amount)    )
                            {
                                $dis_amt =0;
                                if ($vendorData->coupon->promo->promo_type_id == 2) {
                                    $dis_amt = $total_discount_percent = $vendorData->coupon->promo->amount;

                                   // $payable_amount -= $total_discount_percent;
                                    $discount_amount = $total_discount_percent;
                                } else {
                                    $dis_amt = $percentage_amount = ($payable_amount * $vendorData->coupon->promo->amount / 100);
                                   // $payable_amount -= $percentage_amount;
                                    $discount_amount = $percentage_amount;
                                }

                                $couponData['coupon_id'] =  $vendorData->coupon->promo->id;
                                $couponData['name'] =  $vendorData->coupon->promo->name;
                                $couponData['dis_amount'] =  $dis_amt;
                                $couponData['disc_type'] = ($vendorData->coupon->promo->promo_type_id == 1) ? 'Percent' : 'Amount';
                                $couponData['expiry_date'] =  $vendorData->coupon->promo->expiry_date;
                                $couponData['allow_free_delivery'] =  $vendorData->coupon->promo->allow_free_delivery;
                                $couponData['minimum_spend'] =  $vendorData->coupon->promo->minimum_spend;
                                $couponData['first_order_only'] = $vendorData->coupon->promo->first_order_only;
                                $couponData['restriction_on'] = ($vendorData->coupon->promo->restriction_on == 1) ? 'Vendor' : 'Product';
                                $is_coupon_applied = 1;
                                $couponApplied = 1;
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

                                $discount_amount = $discount_amount +  $delivery_fee_charges;
                               // $payable_amount = $payable_amount - $delivery_fee_charges;
                            // pr($payable_amount);
                            }
                        }
                    }
                }
                 //pr($payable_amount);


                // if (!empty($vendorData->coupon->promo) && ($vendorData->coupon->promo->restriction_on == 1)) {
                //     $minimum_spend = $vendorData->coupon->promo->minimum_spend * $clientCurrency->doller_compare;
                //     if ($minimum_spend < $proSum) {
                //         if ($is_percent == 1) {
                //             $discount_amount = ($proSum * $discount_percent) / 100;
                //         }
                //         $couponApplied = 1;
                //     } else {
                //         $vendorData->coupon_msg = "To apply coupon minimum spend should be greater than " . $minimum_spend . '.';
                //         $vendorData->coupon_not_appiled = 1;
                //     }
                // }




                $payable_amount = $payable_amount + $delivery_fee_charges ;

                $deliver_charge = $deliver_charge * $clientCurrency->doller_compare;
                $vendorData->proSum = $proSum;
                $vendorData->addonSum = $ttAddon;
                $vendorData->deliver_charge = $deliver_charge;
                $total_delivery_amount += $deliver_charge;
                $vendorData->coupon_apply_on_vendor = $couponApplied;
                $vendorData->is_coupon_applied = $is_coupon_applied;
                if (empty($couponData)) {
                    $vendorData->couponData = NULL;
                } else {
                    $vendorData->couponData = $couponData;
                }
                $vendor_service_fee_percentage_amount = 0;
                if($vendorData->vendor->service_fee_percent > 0){
                    $vendor_service_fee_percentage_amount = ($vendor_products_total_amount * $vendorData->vendor->service_fee_percent) / 100 ;
                    $payable_amount = $payable_amount + $vendor_service_fee_percentage_amount;
                }
                $total_service_fee = $total_service_fee + $vendor_service_fee_percentage_amount;
                $vendorData->service_fee_percentage_amount = number_format($vendor_service_fee_percentage_amount, 2, '.', '');
                $vendorData->vendor_gross_total = $payable_amount;
                $vendorData->discount_amount = $discount_amount;
                $vendorData->discount_percent = $discount_percent;
                $vendorData->taxable_amount = $taxable_amount;
                $vendorData->payable_amount = $payable_amount - $discount_amount;
                $vendorData->isDeliverable = 1;
                $total_paying = $total_paying + $payable_amount ;
                $total_tax = $total_tax + $taxable_amount;
                $total_disc_amount = $total_disc_amount + $discount_amount;
                $total_discount_percent = $total_discount_percent + $discount_percent;
                $vendorData->vendor->is_vendor_closed = $is_vendor_closed;
                if (!empty($vendorData->coupon->promo)) {
                    unset($vendorData->coupon->promo);
                }

                if (in_array(1, $subscription_features)) {
                    $subscription_discount = $subscription_discount + $deliver_charge;
                }
                $total_subscription_discount = $total_subscription_discount + $subscription_discount;
                if (isset($serviceArea)) {
                    if ($serviceArea->isEmpty()) {
                        $vendorData->isDeliverable = 0;
                        $delivery_status = 0;
                    }
                }
                if ($vendorData->vendor->show_slot == 0) {
                    if (($vendorData->vendor->slotDate->isEmpty()) && ($vendorData->vendor->slot->isEmpty())) {
                        $vendorData->vendor->is_vendor_closed = 1;
                        if ($delivery_status != 0) {
                            $delivery_status = 0;
                        }
                    } else {
                        $vendorData->vendor->is_vendor_closed = 0;
                    }
                }
                if($vendorData->vendor->$action == 0){
                    $vendorData->is_vendor_closed = 1;
                    $delivery_status = 0;
                }

                $order_sub_total = $order_sub_total + $vendor_products_total_amount;

                if((float)($vendorData->vendor->order_min_amount) > $payable_amount){  # if any vendor total amount of order is less then minimum order amount
                    $delivery_status = 0;
                }
                $promoCodeController = new PromoCodeController();
                $promoCodeRequest = new Request();
                $promoCodeRequest->setMethod('POST');
                $promoCodeRequest->request->add(['vendor_id' => $vendorData->vendor_id, 'cart_id' => $cartID ,'is_cart' => 1 ]);
                $promoCodeResponse = $promoCodeController->postPromoCodeList($promoCodeRequest)->getData();
                if($promoCodeResponse->status == 'Success'){
                    if($promoCodeResponse->data){
                        $is_promo_code_available = 1;
                    }
                }
                $vendorData->is_promo_code_available = $is_promo_code_available;
            }
            ++$vondorCnt;
        }//End cart Vendor loop

        $cart_product_luxury_id = CartProduct::where('cart_id', $cartID)->select('luxury_option_id', 'vendor_id')->first();
        if ($cart_product_luxury_id) {
            if ($cart_product_luxury_id->luxury_option_id == 2 || $cart_product_luxury_id->luxury_option_id == 3) {
                $vendor_address = Vendor::where('id', $cart_product_luxury_id->vendor_id)->select('address')->first();
                $cart->address = $vendor_address->address;
            }
        }
        if (!empty($subscription_features)) {
            $total_disc_amount = $total_disc_amount + $total_subscription_discount;
            $cart->total_subscription_discount = $total_subscription_discount * $clientCurrency->doller_compare;
        }

        if($cartData->count() == '1'){
            $vendorId = $cartData[0]->vendor_id;
            //type must be a : delivery , takeaway,dine_in
            $duration = Vendor::where('id',$vendorId)->select('slot_minutes')->first();
            $slots = showSlot('',$vendorId,'delivery',$duration->slot_minutes);
            $cart->slots = $slots;
        // $cart->vendor_id =  $vendorId;
        }else{
            $slots = [];
            $cart->slots = [];
            //$cart->vendor_id =  0;
        }

        $cart->total_service_fee = number_format($total_service_fee, 2, '.', '');
        $cart->total_tax = $total_tax;
        $cart->tax_details = $tax_details;
        // $cart->gross_paybale_amount = $total_paying;
        $cart->gross_paybale_amount = $order_sub_total;
        $cart->total_discount_amount = $total_disc_amount * $clientCurrency->doller_compare;
        $cart->products = $cartData;
        $cart->item_count = $item_count;
        $temp_total_paying = $total_paying  + $total_tax - $total_disc_amount;
        if ($cart->user_id > 0) {
            $loyalty_amount_saved = $this->getLoyaltyPoints($cart->user_id, $clientCurrency->doller_compare);
            // if($total_paying > $cart->loyalty_amount){
            //    $cart->loyalty_amount = 0.00;
            // }
            // $cart->wallet = $this->getWallet($cart->user_id, $clientCurrency->doller_compare, $currency);
        }
        if ($loyalty_amount_saved  >= $temp_total_paying) {
            $loyalty_amount_saved = $temp_total_paying;
            $cart->total_payable_amount = 0.00;
        } else {
            $cart->total_payable_amount = $total_paying  + $total_tax - $total_disc_amount - $loyalty_amount_saved;
        }
        $wallet_amount_used = 0;
        if (isset($user)) {
            if ($user->balanceFloat > 0) {
                $wallet_amount_used = $user->balanceFloat;
                if ($clientCurrency) {
                    $wallet_amount_used = $user->balanceFloat * $clientCurrency->doller_compare;
                }
                if ($wallet_amount_used > $cart->total_payable_amount) {
                    $wallet_amount_used = $cart->total_payable_amount;
                }
                $cart->total_payable_amount = $cart->total_payable_amount - $wallet_amount_used;
                $cart->wallet_amount_used = $wallet_amount_used;
            }
        }
        $cart->deliver_status = $delivery_status;
        $cart->loyalty_amount = $loyalty_amount_saved;
        $cart->tip = array(
            ['label' => '5%', 'value' => number_format((0.05 * $cart->total_payable_amount), 2, '.', '')],
            ['label' => '10%', 'value' => number_format((0.1 * $cart->total_payable_amount), 2, '.', '')],
            ['label' => '15%', 'value' => number_format((0.15 * $cart->total_payable_amount), 2, '.', '')]
        );
        $cart->vendor_details = $vendor_details;
        $cart->cart_dinein_table_id = $cart_dinein_table_id;
        $cart->upSell_products = ($upSell_products) ? $upSell_products->first() : collect();
        $cart->crossSell_products = ($crossSell_products) ? $crossSell_products->first() : collect();
        $cart->delay_date =  $delay_date??0;
        $cart->pickup_delay_date =  $pickup_delay_date??0;
        $cart->dropoff_delay_date =  $dropoff_delay_date??0;
        return $cart;
    }


    /**         *       Empty cart       *          */
    public function notWorkgetCart1($cart, $langId = '1', $currency = '1', $type = 'delivery')
    {
        $preferences = ClientPreference::first();
        $clientCurrency = ClientCurrency::where('currency_id', $currency)->first();
        if (!$cart) {
            return false;
        }
        $vondorCnt = 0;
        $address = [];
        $latitude = '';
        $longitude = '';
        $address_id = 0;
        $delivery_status = 1;
        $cartID = $cart->id;
        $upSell_products = collect();
        $crossSell_products = collect();
        $delifproductnotexist = CartProduct::where('cart_id', $cartID)->doesntHave('product')->delete();
        $cartData = CartProduct::with([
            'vendor', 'coupon' => function ($qry) use ($cartID) {
                $qry->where('cart_id', $cartID);
            }, 'coupon.promo.details', 'vendorProducts.pvariant.media.image', 'vendorProducts.product.media.image',
            'vendorProducts.pvariant.vset.variantDetail.trans' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.pvariant.vset.optionData.trans' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.product.translation' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                $q->where('language_id', $langId);
            },
            'vendorProducts' => function ($qry) use ($cartID) {
                $qry->where('cart_id', $cartID);
            },
            'vendorProducts.addon.set' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.addon.option' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            }, 'vendorProducts.product.taxCategory.taxRate',
        ])->select('vendor_id', 'vendor_dinein_table_id')->where('cart_id', $cartID)->groupBy('vendor_id')->orderBy('created_at', 'asc')->get();
        $loyalty_amount_saved = 0;
        $subscription_features = array();
        if ($cart->user_id) {
            $now = Carbon::now()->toDateTimeString();
            $user_subscription = SubscriptionInvoicesUser::with('features')
                ->select('id', 'user_id', 'subscription_id')
                ->where('user_id', $cart->user_id)
                ->where('end_date', '>', $now)
                ->orderBy('end_date', 'desc')->first();
            if ($user_subscription) {
                foreach ($user_subscription->features as $feature) {
                    $subscription_features[] = $feature->feature_id;
                }
            }
            $user = User::find($cart->user_id);
            $cart->scheduled_date_time = !empty($cart->scheduled_date_time) ? convertDateTimeInTimeZone($cart->scheduled_date_time, $user->timezone, 'Y-m-d\TH:i') : NULL;
            $cart->schedule_pickup = !empty($cart->schedule_pickup) ? convertDateTimeInTimeZone($cart->schedule_pickup, $user->timezone, 'Y-m-d\TH:i') : NULL;
            $cart->schedule_dropoff = !empty($cart->schedule_dropoff) ? convertDateTimeInTimeZone($cart->schedule_dropoff, $user->timezone, 'Y-m-d\TH:i') : NULL;
            $address = UserAddress::where('user_id', $cart->user_id)->where('is_primary', 1)->first();
            $address_id = ($address) ? $address->id : 0;
        }
        $latitude = ($address) ? $address->latitude : '';
        $longitude = ($address) ? $address->longitude : '';
        $total_payable_amount = $total_subscription_discount = $total_discount_amount = $total_discount_percent = $total_taxable_amount = 0.00;
        $total_tax = $total_paying = $total_disc_amount = 0.00;
        $item_count = 0;
        $total_delivery_amount = 0;
        $order_sub_total = 0;
        if ($cartData) {
            $cart_dinein_table_id = NULL;
            $action = $type;
            $vendor_details = [];
            $tax_details = [];
            $is_vendor_closed = 0;
            $delay_date = 0;
            $pickup_delay_date = 0;
            $dropoff_delay_date = 0;
            $total_service_fee = 0;
            $product_out_of_stock = 0;
            foreach ($cartData as $ven_key => $vendorData) {
                $is_promo_code_available = 0;
                $vendor_products_total_amount = $codeApplied = $is_percent = $proSum = $proSumDis = $taxable_amount = $subscription_discount = $discount_amount = $discount_percent = $deliver_charge = $delivery_fee_charges = 0.00;
                $delivery_count = 0;
                $cart_dinein_table_id = $vendorData->vendor_dinein_table_id;
                $slotsDate = findSlot('',$vendorData->vendor_id,'');
                $vendorData->delaySlot = (($slotsDate)?$slotsDate:'');

                if ($action != 'delivery') {
                    $vendor_details['vendor_address'] = $vendorData->vendor->select('id', 'latitude', 'longitude', 'address')->where('id', $vendorData->vendor_id)->first();
                    if ($action == 'dine_in') {
                        $vendor_tables = VendorDineinTable::where('vendor_id', $vendorData->vendor_id)->with('category')->get();
                        foreach ($vendor_tables as $vendor_table) {
                            $vendor_table->qr_url = url('/vendor/' . $vendorData->vendor->slug . '/?id=' . $vendorData->vendor_id . '&name=' . $vendorData->vendor->name . '&table=' . $vendor_table->id);
                        }
                        $vendor_details['vendor_tables'] = $vendor_tables;
                    }
                } else {
                    if ((isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1)) {
                        if ($address_id > 0) {

                            if (!empty($latitude) && !empty($longitude)) {
                                $serviceArea = $vendorData->vendor->whereHas('serviceArea', function ($query) use ($latitude, $longitude) {
                                    $query->select('vendor_id')
                                    ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $latitude . " " . $longitude . ")'))");
                                })->where('id', $vendorData->vendor_id)->get();
                            }
                        }
                    }
                }

                $ttAddon = $payable_amount = $is_coupon_applied = $coupon_removed = 0;
                $coupon_removed_msg = '';
                $deliver_charge = 0;
                $delivery_fee_charges = 0.00;
                $couponData = $couponProducts = array();
                if (!empty($vendorData->coupon->promo) && ($vendorData->coupon->vendor_id == $vendorData->vendor_id)) {
                    $now = Carbon::now()->toDateTimeString();
                    $minimum_spend = 0;
                    if (isset($vendorData->coupon->promo->minimum_spend)) {
                        $minimum_spend = $vendorData->coupon->promo->minimum_spend * $clientCurrency->doller_compare;
                    }
                    if ($vendorData->coupon->promo->expiry_date < $now) {
                        $coupon_removed = 1;
                        $coupon_removed_msg = 'Coupon code is expired.';
                    } else {
                        $couponData['coupon_id'] =  $vendorData->coupon->promo->id;
                        $couponData['name'] =  $vendorData->coupon->promo->name;
                        $couponData['disc_type'] = ($vendorData->coupon->promo->promo_type_id == 1) ? 'Percent' : 'Amount';
                        $couponData['expiry_date'] =  $vendorData->coupon->promo->expiry_date;
                        $couponData['allow_free_delivery'] =  $vendorData->coupon->promo->allow_free_delivery;
                        $couponData['minimum_spend'] =  $vendorData->coupon->promo->minimum_spend;
                        $couponData['first_order_only'] = $vendorData->coupon->promo->first_order_only;
                        $couponData['restriction_on'] = ($vendorData->coupon->promo->restriction_on == 1) ? 'Vendor' : 'Product';

                        $is_coupon_applied = 1;
                        if ($vendorData->coupon->promo->promo_type_id == 1) {
                            $is_percent = 1;
                            $discount_percent = round($vendorData->coupon->promo->amount);


                        } else {
                            $discount_amount = $vendorData->coupon->promo->amount * $clientCurrency->doller_compare;


                        }
                        if ($vendorData->coupon->promo->restriction_on == 0) {
                            foreach ($vendorData->coupon->promo->details as $key => $value) {
                                $couponProducts[] = $value->refrence_id;
                            }
                        }

                    }
                }

                foreach ($vendorData->vendorProducts as $pkey => $prod) {
                    if(isset($prod->product) && !empty($prod->product)){

                        if($prod->product->sell_when_out_of_stock == 0 && $prod->product->has_inventory == 1){
                            $quantity_check = productvariantQuantity($prod->variant_id);
                            if($quantity_check < $prod->quantity ){
                                $delivery_status=0;
                                $product_out_of_stock = 1;
                            }
                        }
                        $prod->product_out_of_stock =  $product_out_of_stock;

                        $price_in_currency = $price_in_doller_compare = $pro_disc = $quantity_price = 0;
                        $variantsData = $taxData = $vendorAddons = array();
                        $divider = (empty($prod->doller_compare) || $prod->doller_compare < 0) ? 1 : $prod->doller_compare;
                        $price_in_currency = $prod->pvariant ? $prod->pvariant->price : 0;
                        $price_in_doller_compare = $price_in_currency * $clientCurrency->doller_compare;
                        $quantity_price = $price_in_doller_compare * $prod->quantity;
                        $item_count = $item_count + $prod->quantity;
                        $proSum = $proSum + $quantity_price;
                        $vendor_products_total_amount = $vendor_products_total_amount + $quantity_price;
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

                        if ($prod->pvariant) {
                            $variantsData['price']              = $price_in_currency;
                            $variantsData['id']                 = $prod->pvariant->id;
                            $variantsData['sku']                = ucfirst($prod->pvariant->sku);
                            $variantsData['title']              = $prod->pvariant->title;
                            $variantsData['barcode']            = $prod->pvariant->barcode;
                            $variantsData['product_id']         = $prod->pvariant->product_id;
                            $variantsData['multiplier']         = $clientCurrency->doller_compare;
                            $variantsData['gross_qty_price']    = $price_in_doller_compare * $prod->quantity;
                            if (!empty($vendorData->coupon->promo) && ($vendorData->coupon->promo->restriction_on == 0) && in_array($prod->product_id, $couponProducts)) {
                                $pro_disc = $discount_amount;
                                if ($minimum_spend <= $quantity_price) {
                                    if ($is_percent == 1) {
                                        $pro_disc = ($quantity_price * $discount_percent) / 100;
                                    }
                                    $quantity_price = $quantity_price - $pro_disc;
                                    $proSumDis = $proSumDis + $pro_disc;
                                    if ($quantity_price < 0) {
                                        $quantity_price = 0;
                                    }
                                    $codeApplied = 1;
                                } else {
                                    $variantsData['coupon_msg'] = "Spend Minimum " . $minimum_spend . " to apply this coupon";
                                    $variantsData['coupon_not_appiled'] = 1;
                                }


                            }

                            $variantsData['discount_amount'] = $pro_disc;
                            $variantsData['coupon_applied'] = $codeApplied;
                            $variantsData['quantity_price'] = $quantity_price;
                            $payable_amount = $payable_amount + $quantity_price;
                            if (!empty($prod->product->taxCategory) && count($prod->product->taxCategory->taxRate) > 0) {
                                foreach ($prod->product->taxCategory->taxRate as $tckey => $tax_value) {
                                    $rate = round($tax_value->tax_rate);
                                    $tax_amount = ($price_in_doller_compare * $rate) / 100;
                                    $product_tax = $quantity_price * $rate / 100;
                                    $taxData[$tckey]['rate'] = $rate;
                                    $taxData[$tckey]['tax_amount'] = $tax_amount;
                                    $taxData[$tckey]['product_tax'] = $product_tax;
                                    $taxable_amount = $taxable_amount + $product_tax;
                                    $taxData[$tckey]['sku'] = ucfirst($prod->pvariant->sku);
                                    $taxData[$tckey]['identifier'] = $tax_value->identifier;
                                    $tax_details[] = array(
                                        'rate' => $rate,
                                        'tax_amount' => $tax_amount,
                                        'identifier' => $tax_value->identifier,
                                        'sku' => ucfirst($prod->pvariant->sku),
                                    );
                                }
                            }
                            $prod->taxdata = $taxData;
                            if ($action == 'delivery') {
                                if (!empty($prod->product->Requires_last_mile) && ($prod->product->Requires_last_mile == 1)) {
                                    $deliver_charge = $this->getDeliveryFeeDispatcher($vendorData->vendor_id);
                                    if (!empty($deliver_charge) && $delivery_count == 0) {
                                        $delivery_count = 1;
                                        $prod->deliver_charge = number_format($deliver_charge, 2, '.', '');
                                        $payable_amount = $payable_amount + $deliver_charge;
                                        $order_sub_total = $order_sub_total + $deliver_charge;
                                        $delivery_fee_charges = $deliver_charge;
                                    }
                                }
                            }
                            if (!empty($prod->addon)) {
                                foreach ($prod->addon as $ck => $addons) {
                                    $opt_quantity_price = 0;
                                    $opt_price_in_currency = $addons->option ? $addons->option->price : 0;
                                    $opt_price_in_doller_compare = $opt_price_in_currency * $clientCurrency->doller_compare;
                                    $opt_quantity_price = $opt_price_in_doller_compare * $prod->quantity;
                                    $vendorAddons[$ck]['quantity'] = $prod->quantity;
                                    $vendorAddons[$ck]['addon_id'] = $addons->addon_id;
                                    $vendorAddons[$ck]['option_id'] = $addons->option_id;
                                    $vendorAddons[$ck]['price'] = $opt_price_in_currency;
                                    $vendorAddons[$ck]['addon_title'] = $addons->set->title;
                                    $vendorAddons[$ck]['quantity_price'] = $opt_quantity_price;
                                    $vendorAddons[$ck]['option_title'] = $addons->option ? $addons->option->title : 0;
                                    $vendorAddons[$ck]['price_in_cart'] = $addons->option->price;
                                    $vendorAddons[$ck]['cart_product_id'] = $addons->cart_product_id;
                                    $vendorAddons[$ck]['multiplier'] = $clientCurrency->doller_compare;
                                    $ttAddon = $ttAddon + $opt_quantity_price;
                                    $payable_amount = $payable_amount + $opt_quantity_price;
                                    $order_sub_total = $order_sub_total + $opt_quantity_price;
                                }
                            }
                            unset($prod->addon);
                            unset($prod->pvariant);
                        }
                        $variant_options = [];
                        if ($prod->pvariant) {
                            foreach ($prod->pvariant->vset as $variant_set_option) {
                                $variant_options[] = array(
                                    'option' => $variant_set_option->optionData->trans->title,
                                    'title' => $variant_set_option->variantDetail->trans->title,
                                );
                            }
                        }
                        $prod->variants = $variantsData;
                        $prod->variant_options = $variant_options;
                        $prod->product_addons = $vendorAddons;

                        $product = Product::with([
                            'variant' => function ($sel) {
                                $sel->groupBy('product_id');
                            },
                            'variant.media.pimage.image', 'upSell', 'crossSell', 'vendor', 'media.image', 'translation' => function ($q) use ($langId) {
                                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                                $q->where('language_id', $langId);
                            }])->select('id', 'sku', 'inquiry_only', 'url_slug', 'weight', 'weight_unit', 'vendor_id', 'has_variant', 'has_inventory', 'averageRating','minimum_order_count','batch_count')
                            ->where('url_slug', $prod->product->url_slug)
                            ->first();
                        $doller_compare = ($clientCurrency) ? $clientCurrency->doller_compare : 1;
                        $up_prods = $this->metaProduct($langId, $doller_compare, 'upSell', $product->upSell);
                        if($up_prods){
                            $upSell_products->push($up_prods);
                        }
                        $cross_prods = $this->metaProduct($langId, $doller_compare, 'crossSell', $product->crossSell);
                        if($cross_prods){
                            $crossSell_products->push($cross_prods);
                        }


                    }
                }

                $couponApplied = 0;
                if (!empty($vendorData->coupon->promo) && ($vendorData->coupon->promo->restriction_on == 1)) {
                    $minimum_spend = $vendorData->coupon->promo->minimum_spend * $clientCurrency->doller_compare;
                    if ($minimum_spend < $proSum) {
                        if ($is_percent == 1) {
                            $discount_amount = ($proSum * $discount_percent) / 100;
                        }
                        $couponApplied = 1;
                    } else {
                        $vendorData->coupon_msg = "To apply coupon minimum spend should be greater than " . $minimum_spend . '.';
                        $vendorData->coupon_not_appiled = 1;
                    }
                }





                $deliver_charge = $deliver_charge * $clientCurrency->doller_compare;
                $vendorData->proSum = $proSum;
                $vendorData->addonSum = $ttAddon;
                $vendorData->deliver_charge = $deliver_charge;
                $total_delivery_amount += $deliver_charge;
                $vendorData->coupon_apply_on_vendor = $couponApplied;
                $vendorData->is_coupon_applied = $is_coupon_applied;
                if (empty($couponData)) {
                    $vendorData->couponData = NULL;
                } else {
                    $vendorData->couponData = $couponData;
                }
                $vendor_service_fee_percentage_amount = 0;
                if($vendorData->vendor->service_fee_percent > 0){
                    $vendor_service_fee_percentage_amount = ($vendor_products_total_amount * $vendorData->vendor->service_fee_percent) / 100 ;
                     $payable_amount = $payable_amount + $vendor_service_fee_percentage_amount;
                }
                $total_service_fee = $total_service_fee + $vendor_service_fee_percentage_amount;
                $vendorData->service_fee_percentage_amount = number_format($vendor_service_fee_percentage_amount, 2, '.', '');
                $vendorData->vendor_gross_total = $payable_amount;
                $vendorData->discount_amount = $discount_amount;
                $vendorData->discount_percent = $discount_percent;
                $vendorData->taxable_amount = $taxable_amount;
                $vendorData->payable_amount = $payable_amount - $discount_amount;
                $vendorData->isDeliverable = 1;
                $total_paying = $total_paying + $payable_amount;
                $total_tax = $total_tax + $taxable_amount;
                $total_disc_amount = $total_disc_amount + $discount_amount;
                $total_discount_percent = $total_discount_percent + $discount_percent;
                $vendorData->vendor->is_vendor_closed = $is_vendor_closed;


                if (!empty($vendorData->coupon->promo)) {
                    unset($vendorData->coupon->promo);
                }

                if (in_array(1, $subscription_features)) {
                    $subscription_discount = $subscription_discount + $deliver_charge;
                }
                $total_subscription_discount = $total_subscription_discount + $subscription_discount;
                if (isset($serviceArea)) {
                    if ($serviceArea->isEmpty()) {
                        $vendorData->isDeliverable = 0;
                        $delivery_status = 0;
                    }
                }
                if ($vendorData->vendor->show_slot == 0) {
                    if (($vendorData->vendor->slotDate->isEmpty()) && ($vendorData->vendor->slot->isEmpty())) {
                        $vendorData->vendor->is_vendor_closed = 1;
                        if ($delivery_status != 0) {
                            $delivery_status = 0;
                        }
                    } else {
                        $vendorData->vendor->is_vendor_closed = 0;
                    }
                }
                if($vendorData->vendor->$action == 0){
                    $vendorData->is_vendor_closed = 1;
                    $delivery_status = 0;
                }
                $slotsDate = 0;
                $slotsDate = findSlot('',$vendorData->vendor_id,'');
                if($vendorData->vendor->is_vendor_closed){
                    $vendorData->delaySlot = $slotsDate;
                    $vendorData->closed_store_order_scheduled = $vendorData->vendor->closed_store_order_scheduled;
                  }else{
                    $vendorData->delaySlot = 0;
                    $vendorData->closed_store_order_scheduled = 0;
                }
               
                $order_sub_total = $order_sub_total + $vendor_products_total_amount;

                if((float)($vendorData->vendor->order_min_amount) > $payable_amount){  # if any vendor total amount of order is less then minimum order amount
                    $delivery_status = 0;
                }
                $promoCodeController = new PromoCodeController();
                $promoCodeRequest = new Request();
                $promoCodeRequest->setMethod('POST');
                $promoCodeRequest->request->add(['vendor_id' => $vendorData->vendor_id, 'cart_id' => $cartID]);
                $promoCodeResponse = $promoCodeController->postPromoCodeList($promoCodeRequest)->getData();
                if($promoCodeResponse->status == 'Success'){
                    if($promoCodeResponse->data){
                        $is_promo_code_available = 1;
                    }
                }
                $vendorData->is_promo_code_available = $is_promo_code_available;
            }
            ++$vondorCnt;
        }//End cart Vendor loop

        $cart_product_luxury_id = CartProduct::where('cart_id', $cartID)->select('luxury_option_id', 'vendor_id')->first();
        if ($cart_product_luxury_id) {
            if ($cart_product_luxury_id->luxury_option_id == 2 || $cart_product_luxury_id->luxury_option_id == 3) {
                $vendor_address = Vendor::where('id', $cart_product_luxury_id->vendor_id)->select('address')->first();
                $cart->address = $vendor_address->address;
            }
        }
        if (!empty($subscription_features)) {
            $total_disc_amount = $total_disc_amount + $total_subscription_discount;
            $cart->total_subscription_discount = $total_subscription_discount * $clientCurrency->doller_compare;
        }

        if($cartData->count() == '1'){
            $vendorId = $cartData[0]->vendor_id;
            //type must be a : delivery , takeaway,dine_in
            $duration = Vendor::where('id',$vendorId)->select('slot_minutes','closed_store_order_scheduled')->first();

            $closed_store_order_scheduled = (($slotsDate)?$duration->closed_store_order_scheduled:0);
                $myDate  = date('Y-m-d');
                if($cart->deliver_status == 0 && $closed_store_order_scheduled == 1)
                {
                    $cart->deliver_status = $duration->closed_store_order_scheduled;
                    $cart->closed_store_order_scheduled = $duration->closed_store_order_scheduled;
                    $myDate  = date('Y-m-d',strtotime('+1 day'));
                    $cart->schedule_type =  'schedule';
                }
                $slotData = findSlotNew($myDate,$vendorId);
                $cart->slots = $slotData['slots'];
            }else{
                $slots = [];
                $cart->slots = [];
            }

        $cart->total_service_fee = number_format($total_service_fee, 2, '.', '');
        $cart->total_tax = $total_tax;
        $cart->tax_details = $tax_details;
        // $cart->gross_paybale_amount = $total_paying;
        $cart->gross_paybale_amount = $order_sub_total;
        $cart->total_discount_amount = $total_disc_amount * $clientCurrency->doller_compare;
        $cart->products = $cartData;
        $cart->item_count = $item_count;
        $temp_total_paying = $total_paying  + $total_tax - $total_disc_amount;
        if ($cart->user_id > 0) {
            $loyalty_amount_saved = $this->getLoyaltyPoints($cart->user_id, $clientCurrency->doller_compare);
            // if($total_paying > $cart->loyalty_amount){
            //    $cart->loyalty_amount = 0.00;
            // }
            // $cart->wallet = $this->getWallet($cart->user_id, $clientCurrency->doller_compare, $currency);
        }
        if ($loyalty_amount_saved  >= $temp_total_paying) {
            $loyalty_amount_saved = $temp_total_paying;
            $cart->total_payable_amount = 0.00;
        } else {
            $cart->total_payable_amount = $total_paying  + $total_tax - $total_disc_amount - $loyalty_amount_saved;
        }
        $wallet_amount_used = 0;
        if (isset($user)) {
            if ($user->balanceFloat > 0) {
                $wallet_amount_used = $user->balanceFloat;
                if ($clientCurrency) {
                    $wallet_amount_used = $user->balanceFloat * $clientCurrency->doller_compare;
                }
                if ($wallet_amount_used > $cart->total_payable_amount) {
                    $wallet_amount_used = $cart->total_payable_amount;
                }
                $cart->total_payable_amount = $cart->total_payable_amount - $wallet_amount_used;
                $cart->wallet_amount_used = $wallet_amount_used;
            }
        }
        $cart->deliver_status = $delivery_status;
        $cart->loyalty_amount = $loyalty_amount_saved;
        $cart->tip = array(
            ['label' => '5%', 'value' => number_format((0.05 * $cart->total_payable_amount), 2, '.', '')],
            ['label' => '10%', 'value' => number_format((0.1 * $cart->total_payable_amount), 2, '.', '')],
            ['label' => '15%', 'value' => number_format((0.15 * $cart->total_payable_amount), 2, '.', '')]
        );
        $cart->vendor_details = $vendor_details;
        $cart->cart_dinein_table_id = $cart_dinein_table_id;
        $cart->upSell_products = ($upSell_products) ? $upSell_products->first() : collect();
        $cart->crossSell_products = ($crossSell_products) ? $crossSell_products->first() : collect();
        $cart->delay_date =  $delay_date??0;
        $cart->pickup_delay_date =  $pickup_delay_date??0;
        $cart->dropoff_delay_date =  $dropoff_delay_date??0;
        return $cart;
    }


    public function checkScheduleSlots(Request $request)
    {
        $slot = [];
        $vendorId = $request->vendor_id??0;
        $delivery = $request->delivery??'delivery';
        //type must be a : delivery , takeaway,dine_in
        $duration = Vendor::where('id',$vendorId)->select('slot_minutes')->first();
       // $duration = $duration->slot_minutes??'';
        $slots = showSlot($request->date,$vendorId,$delivery,$duration->slot_minutes);
        if(count($slots)<=0){
            $slot = [];
        }else{
            $slot = $slots;
        }

        return response()->json($slot);
    }


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

    public function addVendorTableToCart(Request $request, $domain = '')
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            if ($user) {
                $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->firstOrFail();
                $cartData = CartProduct::where('cart_id', $cart->id)->where('vendor_id', $request->vendor_id)->update(['vendor_dinein_table_id' => $request->table]);
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Table has been selected']);
            } else {
                return response()->json(['status' => 'Error', 'message' => 'Invalid user']);
            }
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $ex->getMessage()]);
        }
    }

    public function updateSchedule(Request $request, $domain = '')
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            if ($user) {
                if ($request->task_type == 'now') {
                    $request->schedule_dt = Carbon::now()->format('Y-m-d H:i:s');
                } else {
                    if(isset($request->schedule_dt) && !empty($request->schedule_dt))
                    $request->schedule_dt = Carbon::parse($request->schedule_dt, $user->timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');
                }

                if(isset($request->schedule_pickup) && !empty($request->schedule_pickup))    # for pickup laundry
                $request->schedule_pickup = Carbon::parse($request->schedule_pickup, $user->timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');

                if(isset($request->schedule_dropoff) && !empty($request->schedule_dropoff))  # for pickup laundry
                $request->schedule_dropoff = Carbon::parse($request->schedule_dropoff, $user->timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');

                if($request->task_type!='now'){
                    if(isset($request->slot))
                    {
                        $request->schedule_dt = Carbon::parse($request->schedule_dt, $user->timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');
                        $time = $request->schedule_dt;
                        $slot = $request->slot;
                    }else{
                    $time = $request->schedule_dt;
                    $slot = null;
                    }
                    }else{
                    $time = null;
                    $slot = null;
                    }

                Cart::where('status', '0')->where('user_id', $user->id)->update(['specific_instructions' => $request->specific_instructions ?? null,
                'schedule_type' => $request->task_type??null,
                //'scheduled_date_time' => $request->schedule_dt??null,
                'scheduled_date_time' => $time??null,
                'scheduled_slot' => $slot??null,
                'comment_for_pickup_driver' => $request->comment_for_pickup_driver??null,
                'comment_for_dropoff_driver' => $request->comment_for_dropoff_driver??null,
                'comment_for_vendor' => $request->comment_for_vendor??null,
                'schedule_pickup' => $request->schedule_pickup??null,
                'schedule_dropoff' => $request->schedule_dropoff??null]);
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Cart has been scheduled']);
            } else {
                return response()->json(['status' => 'Error', 'message' => 'Invalid user']);
            }
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $ex->getMessage()]);
        }
    }

    public function checkIsolateSingleVendor(Request $request, $domain = '')
    {
        try {
            $preference = ClientPreference::first();
            $user = Auth::user();
            if ($user->id && $user->id > 0) {
                $cart_detail = Cart::where('user_id', $user->id)->first();
            } else {
                $cart_detail = Cart::where('unique_identifier', $user->system_user)->first();
            }
            if ((isset($preference->isolate_single_vendor_order)) && ($preference->isolate_single_vendor_order == 1) && (!empty($cart_detail))) {
                $checkVendorId = CartProduct::where('vendor_id', '!=', $request->vendor_id)->where('cart_id', $cart_detail->id)->first();
                return response()->json(['status' => 'Success', 'otherVendorExists' => ($checkVendorId ? 1 : 0), 'isSingleVendorEnabled' => 1]);
            } else {
                return response()->json(['status' => 'Success', 'otherVendorExists' => 0, 'isSingleVendorEnabled' => 0]);
            }
        } catch (\Exception $ex) {
            return response()->json(['status' => 'Error', 'message' => $ex->getMessage()]);
        }
    }


    public function updateProductSchedule(Request $request, $domain = '')
    {
        DB::beginTransaction();
        try{
            $user = Auth::user();
            if ($user) {
                if($request->task_type == 'now'){
                    $request->schedule_dt = Carbon::now()->format('Y-m-d H:i:s');
                }else{
                    $request->schedule_dt = Carbon::parse($request->schedule_dt, $user->timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');
                }
                CartProduct::where('id', $request->cart_product_id)->update(['schedule_type' => $request->task_type, 'scheduled_date_time' => $request->schedule_dt]);
                DB::commit();
                return response()->json(['status'=>'Success', 'message'=>'Cart has been scheduled']);
            }
            else{
                return response()->json(['status'=>'Error', 'message'=>'Invalid user']);
            }
        }
        catch(\Exception $ex){
            DB::rollback();
            return response()->json(['status'=>'Error', 'message'=>$ex->getMessage()]);
        }
    }

    # repeat order vendor wise

    public function repeatOrder($domain = '', Request $request){

        $order_vendor_id = $request->order_vendor_id;
        $cart_id = $request->cart_id;
        $getallproduct = OrderProduct::where('order_vendor_id',$order_vendor_id)->get();

        if(isset($cart_id) && !empty($cart_id)){
            CartProduct::where('cart_id', $cart_id)->delete();
            CartCoupon::where('cart_id', $cart_id)->delete();
            CartAddon::where('cart_id', $cart_id)->delete();
        }

        foreach($getallproduct as $data){
            $request->vendor_id = $data->vendor_id;
            $request->sku = $data->product->sku;
            $request->quantity = $data->quantity;
            $request->product_variant_id = $data->variant_id;

            if(isset($getallproduct->order) && !empty($getallproduct->order))
            $type = LuxuryOption::where('id',$getallproduct->order->luxury_option_id)->value('title');


            $request->type = $type ?? 'delivery';

            $addonID = OrderProductAddon::where('order_product_id',$data->id)->pluck('addon_id');
            $addonoptID = OrderProductAddon::where('order_product_id',$data->id)->pluck('option_id');

            if(count($addonID))
            $request->request->add(['addon_ids' => $addonID->toArray()]);

            if(count($addonoptID))
            $request->request->add(['addon_options' => $addonoptID->toArray()]);

            $this->add($request);

        }

        return response()->json(['status' => 'success', 'message' => 'Order added to cart.']);


    }
}
