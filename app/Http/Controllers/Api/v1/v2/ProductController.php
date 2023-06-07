<?php

namespace App\Http\Controllers\Api\v1\v2;

use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Controllers\Front\PromoCodeController;
use Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{User,ClientLanguage,ProductFaq, Product, Category, ProductVariantSet, ProductVariant, ProductAddon, ProductRelated, ProductUpSell, ProductCrossSell, ClientCurrency, Vendor, Brand, ProductBooking, ProductFaqSelectOption, TagTranslation,Tag,DeliverySlotProduct, DeliverySlot,UserAddress};
use Validation;
use DB;
use App\Http\Traits\{ApiResponser,ProductTrait, ProductActionTrait};

class ProductController extends BaseController
{
    private $field_status = 2;
    use ApiResponser,ProductTrait, ProductActionTrait;

    public function productById(Request $request, $pid)
    {
        try{
            $pvIds = array();
            $user = Auth::user();
            $langId = $user->language;
            $userid = $user->id;
            $limit = 6; // Number of frequently bought products to retrieve

            $product = Product::with(['inwishlist' => function($qry) use($userid){
                            $qry->where('user_id', $userid);
                        },
                        'category.categoryDetail', 'category.categoryDetail.translation' => function($q) use($langId){
                            $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
                            ->where('category_translations.language_id', $langId);
                        },
                        'variant.media.pimage.image', 'vendor', 'media.image', 'related', 'upSell', 'crossSell', 'reviews.user' => function($rev) {
                            $rev->select('users.id', 'users.name', 'users.email', 'users.image');
                        }, 'reviews.reviewFiles',
                        'addOn' => function($q1) use($langId){
                            $q1->join('addon_sets as set', 'set.id', 'product_addons.addon_id');
                            $q1->join('addon_set_translations as ast', 'ast.addon_id', 'set.id');
                            $q1->select('product_addons.product_id', 'set.min_select', 'set.max_select', 'ast.title', 'product_addons.addon_id');
                            $q1->where('set.status', 1)->where('ast.language_id', $langId);
                        },
                        'variantSetNew' => function($z) use($langId,$pvIds, $pid){
                            $z->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id');
                            $z->join('variant_translations as vt','vt.variant_id','vr.id');
                            $z->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title');
                            $z->where('vt.language_id', $langId);
                            $z->with(['options' => function($zx) use($langId, $pvIds, $pid){
                                $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id')
                                ->join('product_variants','pvs.product_variant_id','product_variants.id')
                                ->select('variant_options.*', 'vt.title', 'pvs.product_variant_id', 'pvs.variant_type_id', 'product_variants.quantity', 'product_variants.price')
                                ->where('pvs.product_id', $pid)
                                ->where('vt.language_id', $langId)
                                ->orderBy('position', 'Asc')
                                ->addSelect(DB::raw('(CASE WHEN product_variants.quantity = 0 THEN 1 ELSE 0 END) as is_disabled'));
                            },]);
                        },
                        
                        'translation' => function($q) use($langId){
                            $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                            $q->where('language_id', $langId);
                        },
                        'addOn.setoptions' => function($q2) use($langId){
                            $q2->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                            $q2->select('addon_options.id', 'addon_options.title', 'addon_options.price', 'apt.title', 'addon_options.addon_id');
                            $q2->where('apt.language_id', $langId)->groupBy(['addon_options.id', 'apt.language_id']);
                        },

                    ]);
                    $product = $product->select('id', 'sku', 'url_slug', 'weight', 'weight_unit', 'vendor_id', 'is_new', 'is_featured', 'is_physical', 'has_inventory', 'has_variant', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating','minimum_order_count','batch_count','minimum_duration','minimum_duration_min','additional_increments','additional_increments_min','buffer_time_duration','buffer_time_duration_min', 'returnable', 'replaceable', 'return_days', 'is_long_term_service','service_duration','is_show_dispatcher_agent','is_slot_from_dispatch','tags','mode_of_service','is_recurring_booking');
                    
                    $product = $product->where('id', $pid)->first();
                    
                    if($product->variantSetNew){
                        foreach ($product->variantSetNew->options as $set_key => $set_value) {
                            $option3 =  ProductVariantSet::
                            where('variant_type_id', '=', $set_value->variant_type_id)
                            ->where('product_id', $product->variantSetNew->product_id)
                            ->where('variant_option_id', $set_value->id)
                            ->get()->pluck('product_variant_id');
                            $set_value->option3 =  ProductVariantSet::with(['options1' => function($qz) use($set_value){
                                $qz->where('variant_type_id', '<>', $set_value->variant_type_id);
                            }
                            ])
                            ->whereIn('product_variant_id', $option3)
                            ->where('variant_type_id', '<>', $set_value->variant_type_id)
                            ->where('product_id', $product->variantSetNew->product_id)
                            ->get()->toArray();
                        }
                    }
            
            if(!$product){
                return response()->json(['error' => 'No record found.'], 404);
            }
            if ($this->checkTemplateForAction(8)) {
            $this->RecentView($pid);
            }
            $product->vendor->is_vendor_closed = 0;
            if($product->vendor->show_slot == 0){
                if( ($product->vendor->slotDate->isEmpty()) && ($product->vendor->slot->isEmpty()) ){
                    $product->vendor->is_vendor_closed = 1;
                }else{
                    $product->vendor->is_vendor_closed = 0;
                    if($product->vendor->slotDate->isNotEmpty()){
                        $product->vendor->opening_time = Carbon::parse($product->vendor->slotDate->first()->start_time)->format('g:i A');
                        $product->vendor->closing_time = Carbon::parse($product->vendor->slotDate->first()->end_time)->format('g:i A');
                    }elseif($product->vendor->slot->isNotEmpty()){
                        $product->vendor->opening_time = Carbon::parse($product->vendor->slot->first()->start_time)->format('g:i A');
                        $product->vendor->closing_time = Carbon::parse($product->vendor->slot->first()->end_time)->format('g:i A');
                    }
                }
            }

            $slotsDate = 0;
            if($product->vendor->is_vendor_closed){
                $slotsDate = findSlot('',$product->vendor->id,'');
                $product->delaySlot = $slotsDate;
                $product->vendor->closed_store_order_scheduled = (($slotsDate)?$product->vendor->closed_store_order_scheduled:0);
            }else{
                $product->delaySlot  = 0;
                $product->vendor->closed_store_order_scheduled = 0;
            }


            $product->is_wishlist = @$product->category->categoryDetail->show_wishlist;
            $clientCurrency = ClientCurrency::where('currency_id', $user->currency)->first();
            foreach ($product->variant as $key => $value) {
                $product->variant[$key]->multiplier = $clientCurrency->doller_compare;
            }
            $addonList = array();
            foreach ($product->addOn as $key => $value) {
                foreach ($value->setoptions as $k => $v) {
                    if($v->price == 0){
                        $v->is_free = true;
                    }else{
                        $v->is_free = false;
                    }
                    $v->multiplier = $clientCurrency->doller_compare;
                }
            }
            $data_image = array();
            /*  if variant has image return variant images else product images  */
            $variant_id = 0;
            foreach ($product->variant as $key => $value) {
                $variant_id = $value->id;
                if($product->sell_when_out_of_stock == 1){
                    $value->stock_check = '1';
                }elseif($value->quantity > 0){
                    $value->stock_check = '1';
                }else{
                    $value->stock_check = 0;
                }
                if($value->media && count($value->media) > 0){
                    foreach ($value->media as $media_key => $media_value) {
                        $data_image[$media_key]['product_variant_id'] = $media_value->product_variant_id;
                        $data_image[$media_key]['media_id'] = $media_value->product_image_id;
                        $data_image[$media_key]['is_default'] = 0;
                        $data_image[$media_key]['image'] = $media_value->pimage->image;
                    }
                }else{
                    foreach ($product->media as $media_key => $media_value) {
                        $data_image[$media_key]['product_id'] = $media_value->product_id;
                        $data_image[$media_key]['media_id'] = $media_value->media_id;
                        $data_image[$media_key]['is_default'] = $media_value->is_default;
                        $data_image[$media_key]['image'] = $media_value->image;
                    }
                }
            }
           


            if($product->variantSet){
                foreach ($product->variantSet as $set_key => $set_value) {
                    foreach ($set_value->options as $opt_key => $opt_value) {
                        $opt_value->value = $opt_value->product_variant_id == $variant_id ? true : false;
                    }
                }
            }

            $product->product_media = $data_image;
            $product->share_link = getServerURL() . $product->vendor->slug . '/product/' . $product->url_slug;

            $promoCodeController = new PromoCodeController();
            $coupon_list = $promoCodeController->coupon_code_list($product->id, $product->vendor_id);


            $response['coupon_list'] = $coupon_list;
            if($product->is_long_term_service == 1){
                $product_id = $product->LongTermProducts->product_id;
                $url_slug   = $product->LongTermProducts->product->url_slug;
                $vendor_slug   = $product->vendor->slug;

                $LongTermProducts                    = $this->getProduct($product->LongTermProducts->product_id,$vendor_slug,$url_slug,$user,$langId);
                $LongTermProducts->long_term_product = $product->LongTermProducts;
                $addon =  $product->LongTermProducts->addons->pluck('option_id','addon_id')->toArray() ?? [];
                if($product->ServicePeriod){
                    $product->ServicePeriods = $product->ServicePeriod->pluck('service_period')->toArray();
                }
                $LongTermProducts->period =config('constants.Period');

                $LongTermProducts->product_addon     =  $addon;
                $product->longTermServiceProduct     = $LongTermProducts;
                $response['products'] = $product;
                return response()->json([
                    'data' => $response,
                ]);
            }
            // Product Attribute
            $product_attr = [];
            if( !empty($product->ProductAttribute) ) {
                foreach( $product->ProductAttribute as $key => $value ) {
                    if( !empty($value->attribute) && !empty($value->attribute->status) && $value->attribute->status == 1 ) {
                        $product_attr[$key]['title'] = optional($value->attribute)->title ?? '';
                        $product_attr[$key]['attribute_id'] = $value->attribute_id ?? '';
                        
                        if( !empty($value->attribute) && $value->attribute->type != 4 && $value->attribute->type != 6) {
                            $product_attr[$key]['value'] = optional($value->attributeOption)->title ?? '';
                        }
                        else {
                            $product_attr[$key]['value'] = $value['key_value'] ?? '';
                        }
                    }
                }
            }
            
            $attr_id = '';
            $attr_array = [];
            foreach($product_attr as $pro_att_key => $pro_att_val) {

                if( empty($attr_id) || ($pro_att_val['attribute_id'] != $attr_id) ) {
                    $attr_id = $pro_att_val['attribute_id'];
                    $attr_array[$pro_att_val['title']][$pro_att_key]['title'] = $pro_att_val['title'];
                    $attr_array[$pro_att_val['title']][$pro_att_key]['attribute_id'] = $pro_att_val['attribute_id'];
                    $attr_array[$pro_att_val['title']][$pro_att_key]['value'] = $pro_att_val['value'];
                }
                else {
                    $attr_id = $pro_att_val['attribute_id'];
                    $attr_array[$pro_att_val['title']][$pro_att_key]['title'] = $pro_att_val['title'];
                    $attr_array[$pro_att_val['title']][$pro_att_key]['attribute_id'] = $pro_att_val['attribute_id'];
                    $attr_array[$pro_att_val['title']][$pro_att_key]['value'] = $pro_att_val['value'];
                }
            }

            $suggested_category_products = $suggested_brand_products = $suggested_vendor_products = $frequentlyBoughtProducts = [];

            $suggested_product = Product::with(['media.image','vendor', 'translation', 'variant', 'productVariantByRoles']);
            if( !empty($product->category->category_id) ) {
                $suggested_category_products = $suggested_product->where('category_id', $product->category->category_id)->groupBy('id')->orderby('id', 'desc')->limit(20)->get();
            }
            
            $frequentlyBoughtProducts = Product::with(['media.image', 'vendor', 'translation', 'variant', 'productVariantByRoles'])->join('order_vendor_products', 'products.id', '=', 'order_vendor_products.product_id')->join('orders', 'order_vendor_products.order_id', '=', 'orders.id')->where('products.vendor_id', $product->vendor->id)->select('products.*')
            ->groupBy('products.id')->orderByRaw('COUNT(products.id) DESC')->limit($limit)->get();

            foreach($suggested_category_products as $r_product){
                foreach ($r_product->variant as $key => $value) {
                    if(isset($r_product->variant[$key])){
                        $r_product->variant[$key]->multiplier = $clientCurrency ? $clientCurrency->doller_compare : '1.00';
                    }
                }
            }

            if( !empty($product->brand_id) ) {
                $suggested_product = Product::with(['media.image', 'vendor', 'translation', 'variant']);
                $suggested_brand_products = $suggested_product->where('brand_id', $product->brand_id)->orderby('id', 'desc')->limit(20)->get();
            }


                foreach($suggested_brand_products as $r_product){
                foreach ($r_product->variant as $key => $value) {
                    if(isset($r_product->variant[$key])){
                    $r_product->variant[$key]->multiplier = $clientCurrency ? $clientCurrency->doller_compare : '1.00';
                    }
                }
            }

            if( !empty($product->vendor_id) ) {
                $suggested_product = Product::with(['media.image', 'vendor', 'translation', 'variant']);
                $suggested_vendor_products = $suggested_product->where('vendor_id', $product->vendor_id)->orderby('id', 'desc')->limit(20)->get();
            }


                foreach($suggested_vendor_products as $r_product){
                foreach ($r_product->variant as $key => $value) {
                    if(isset($r_product->variant[$key])){
                    $r_product->variant[$key]->multiplier = $clientCurrency ? $clientCurrency->doller_compare : '1.00';
                    }
                }
            }

            $response['suggested_category_products'] =  $suggested_category_products;
            $response['suggested_brand_products'] =  $suggested_brand_products;
            $response['suggested_vendor_products'] =  $suggested_vendor_products;
            $response['products'] = $product;
            $response['frequently_bought'] = $frequentlyBoughtProducts;
            $response['relatedProducts'] = $this->metaProduct($langId, $clientCurrency->doller_compare, 'relate', $product->related);
            $response['upSellProducts'] = $this->metaProduct($langId, $clientCurrency->doller_compare, 'upSell', $product->upSell);
            $response['crossProducts'] = $this->metaProduct($langId, $clientCurrency->doller_compare, 'cross', $product->crossSell);
            $response['product_attribute'] = $product_attr;
            // $response['product_variant'] = ProductVariant::select('id', 'sku', 'product_id', 'title', 'quantity','price','markup_price','cost_price','barcode','tax_category_id')->where('product_id',$pid)->get();
            /* group by in query return data only for key - 0 so using 0 */
            $is_return_days = 0;
            if(((@$product->returnable && @$product->vendor->return_request) || $product->replaceable) && ($product->return_days > 0)){
                $is_return_days = 1;
                $product->is_return_days = $is_return_days;
            }
            if(isset($product->variant[0]->media) && !empty($product->variant[0]->media)){
                unset($product->variant[0]->media);
            }
            unset($product->related);
            unset($product->media);
            unset($product->upSell);
            unset($product->crossSell);
            $response['products'] = $product;
            return response()->json([
                'data' => $response,
            ]);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
