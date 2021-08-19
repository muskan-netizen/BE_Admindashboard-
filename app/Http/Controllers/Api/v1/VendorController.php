<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Validation;
use Carbon\Carbon;
use Client;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\v1\BaseController;
use App\Models\{Type, User, Product, Category, ProductVariantSet, ProductVariant, ProductAddon, ProductRelated, ProductUpSell, ProductCrossSell, ClientCurrency, Vendor, Brand, VendorCategory};

class VendorController extends BaseController{
    use ApiResponser;
    private $field_status = 2;

    public function postVendorCategoryList(Request $request){
        try {
            $vendor_ids = [];
            $category_details = [];
            $vendor_id = $request->vendor_id;
            $type = Type::where('title' ,'Vendor')->first();
            $vendor_products = Product::with('category.categoryDetail')->where('vendor_id', $vendor_id)->where('is_live', 1)->get(['id']);
            foreach ($vendor_products as $vendor_product) {
                if(!in_array($vendor_product->category->categoryDetail->id, $vendor_ids)){
                    if($vendor_product->category->categoryDetail->id != $type->id){
                        $vendor_ids[] = $vendor_product->category->categoryDetail->id;
                        $category_details[] = array(
                            'id' => $vendor_product->category->categoryDetail->id,
                            'slug' => $vendor_product->category->categoryDetail->slug,
                            'name' => $vendor_product->category->categoryDetail->translation_one ? $vendor_product->category->categoryDetail->translation_one->name :$vendor_product->category->categoryDetail->slug,
                            'icon' => $vendor_product->category->categoryDetail->icon,
                            'image' => $vendor_product->category->categoryDetail->image
                        );
                    }
                }
            }
            return $this->successResponse($category_details, '', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function productsByVendor(Request $request, $vid = 0){
        try {
            if($vid == 0){
                return response()->json(['error' => 'No record found.'], 404);
            }
            $userid = Auth::user()->id;
            $paginate = $request->has('limit') ? $request->limit : 12;
            $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
            $langId = Auth::user()->language;
            $vendor = Vendor::select('id', 'name', 'desc', 'logo', 'banner', 'address', 'latitude', 'longitude', 
                        'order_min_amount', 'order_pre_time', 'auto_reject_time', 'dine_in', 'takeaway', 'delivery')
                        ->where('id', $vid)->first();
            if(!$vendor){
                return response()->json(['error' => 'No record found.'], 200);
            }
            $variantSets =  ProductVariantSet::with(['options' => function($zx) use($langId){
                                $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id');
                                $zx->select('variant_options.*', 'vt.title');
                                $zx->where('vt.language_id', $langId);
                            }])->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id')
                            ->join('variant_translations as vt','vt.variant_id','vr.id')
                            ->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title')
                            ->where('vt.language_id', $langId)
                            ->whereIn('product_id', function($qry) use($vid){ 
                            $qry->select('id')->from('products')
                                ->where('vendor_id', $vid);
                            })
                        ->groupBy('product_variant_sets.variant_type_id')->get();
                    // ->join('product_categories as pc', 'pc.product_id', 'products.id')
                    // ->whereNotIn('pc.category_id', function($qr) use($vid){ 
                    //             $qr->select('category_id')->from('vendor_categories')
                    //                 ->where('vendor_id', $vid)->where('status', 0);
                    // })
            $products = Product::with(['category.categoryDetail', 'inwishlist' => function($qry) use($userid){
                            $qry->where('user_id', $userid);
                        },
                        'media.image', 'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId){
                            $q->select('id','sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                        },
                    ])->select('products.id', 'products.sku', 'products.requires_shipping', 'products.sell_when_out_of_stock', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.Requires_last_mile', 'products.averageRating', 'products.category_id')
                    ->where('products.vendor_id', $vid)
                    ->where('products.is_live', 1)->paginate($paginate);
            if(!empty($products)){
                foreach ($products as $key => $product) {
                    $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
                    foreach ($product->variant as $k => $v) {
                        $product->variant[$k]->multiplier = $clientCurrency->doller_compare;
                    }
                }
            }
            $response['vendor'] = $vendor;
            $response['products'] = $products;
            $response['filterData'] = $variantSets;
            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage().''.$e->getLineNo(), $e->getCode());
        }
    }

    /**
     * Display product By Vendor Category
     *
     * @return \Illuminate\Http\Response
     */
    public function vendorCategoryProducts(Request $request, $slug1 = 0, $slug2 = 0){
        try{
            $paginate = $request->has('limit') ? $request->limit : 12;
            // $preferences = Session::get('preferences');
            $vendor = Vendor::select('id', 'name', 'slug', 'desc', 'logo', 'banner', 'address', 'latitude', 'longitude', 'order_min_amount', 'order_pre_time', 'auto_reject_time', 'dine_in', 'takeaway', 'delivery', 'vendor_templete_id')->where('slug', $slug1)->where('status', 1)->firstOrFail();
            $langId = Auth::user()->language;
            $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
            
            $products = Product::with(['media.image',
                        'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                        },
                    ])->select('id', 'sku', 'description', 'requires_shipping', 'sell_when_out_of_stock', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'Requires_last_mile', 'averageRating', 'inquiry_only');
            if(!empty($slug2)){
                $category = Category::select('id')->where('slug', $slug2)->firstOrFail();
                $products = $products->where('category_id', $category->id);
            }
            $products = $products->where('is_live', 1)->where('vendor_id', $vendor->id)->paginate($paginate);
            if(!empty($products)){
                foreach ($products as $key => $value) {
                    foreach ($value->variant as $k => $v) {
                        $value->variant[$k]->multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                    }
                }
            }
            $vid = $vendor->id;
            $categoryId = $category->id;
            $variantSets =  ProductVariantSet::with(['options' => function($zx) use($langId){
                $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id');
                $zx->select('variant_options.*', 'vt.title');
                $zx->where('vt.language_id', $langId);
            }])->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id')
            ->join('variant_translations as vt','vt.variant_id','vr.id')
            ->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title')
            ->where('vt.language_id', $langId)
            ->whereIn('product_id', function($qry) use($vid, $categoryId){ 
                $qry->select('id')->from('products')->where('vendor_id', $vid)->where('category_id', $categoryId);
            })
            ->groupBy('product_variant_sets.variant_type_id')->get();
            $response['vendor'] = $vendor;
            $response['products'] = $products;
            $response['filterData'] = $variantSets;
            return response()->json(['data' => $response]);
        }
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage().''.$e->getLineNo(), $e->getCode());
        }
    }

    /**
     * Product filters on category Page
     * @return \Illuminate\Http\Response
     */
    public function vendorFilters(Request $request, $vid = 0){
        if($vid == 0 || $vid < 0){
            return response()->json(['error' => 'No record found.'], 404);
        }
        $langId = Auth::user()->language;
        $curId = Auth::user()->currency;
        $setArray = $optionArray = array();
        $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();
        if($request->has('variants') && !empty($request->variants)){
            $setArray = array_unique($request->variants);
        }
        $startRange = 0; $endRange = 20000;
        if($request->has('range') && !empty($request->range)){
            $range = explode(';', $request->range);
            $clientCurrency->doller_compare;
            $startRange = $range[0] * $clientCurrency->doller_compare;
            $endRange = $range[1] * $clientCurrency->doller_compare;
        }
        $multiArray = array();
        if($request->has('options') && !empty($request->options)){
            foreach ($request->options as $key => $value) {
                $multiArray[$request->variants[$key]][] = $value;
            }
        }
        $variantIds = $productIds = array();
        if(!empty($multiArray)){
            foreach ($multiArray as $key => $value) {
                $new_pIds = $new_vIds = array();
                $vResult = ProductVariantSet::select('product_variant_sets.product_variant_id', 'product_variant_sets.product_id')
                    ->where('product_variant_sets.variant_type_id', $key)
                    ->whereIn('product_variant_sets.variant_option_id', $value);
                if(!empty($variantIds)){
                    $vResult  = $vResult->whereIn('product_variant_sets.product_variant_id', $variantIds);
                }
                $vResult  = $vResult->groupBy('product_variant_sets.product_variant_id')->get();
                if($vResult){
                    foreach ($vResult as $key => $value) {
                        $new_vIds[] = $value->product_variant_id;
                        $new_pIds[] = $value->product_id;
                    }
                }
                $variantIds = $new_vIds;
                $productIds = $new_pIds;
            }
        }
        $order_type = $request->has('order_type') ? $request->order_type : '';
        $products = Product::with(['category.categoryDetail', 'media.image', 'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },'variant' => function($q) use($langId, $variantIds){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            if(!empty($variantIds)){
                                $q->whereIn('id', $variantIds);
                            }
                            if(!empty($order_type) && $order_type == 'low_to_high'){
                                $q->orderBy('price', 'asc');
                            }
                            if(!empty($order_type) && $order_type == 'high_to_low'){
                                $q->orderBy('price', 'desc');
                            }
                            $q->groupBy('product_id');
                        },
                    ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating')
                    ->where('vendor_id', $vid)
                    ->where('is_live', 1)
                    ->whereIn('id', function($qr) use($startRange, $endRange){
                        $qr->select('product_id')->from('product_variants')->where('price',  '>=', $startRange)->where('price',  '<=', $endRange);
                    });
        if(!empty($productIds)){
            $products = $products->whereIn('id', $productIds);
        }
        if($request->has('brands') && !empty($request->brands)){
            $products = $products->whereIn('brand_id', $request->brands);
        }
        if(!empty($order_type) && $request->order_type == 'rating'){
            $products = $products->orderBy('averageRating', 'desc');
        }
        $paginate = $request->has('limit') ? $request->limit : 12;
        $products = $products->paginate($paginate);
        if(!empty($products)){
            foreach ($products as $key => $product) {
                $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
                foreach ($product->variant as $k => $v) {
                    $product->variant[$k]->multiplier = $clientCurrency->doller_compare;
                }
            }
        }
        return response()->json([
            'data' => $products,
        ]);
    }

    /**
     * Product filters on category Page
     * @return \Illuminate\Http\Response
     */
    // public function vendorCategoryProductsFilters(Request $request, $vid = 0, $cid = 0){
    //     if( ($vid == 0 || $vid < 0) && ($cid == 0 || $cid < 0) ){
    //         return response()->json(['error' => 'No record found.'], 404);
    //     }
    //     $langId = Auth::user()->language;
    //     $curId = Auth::user()->currency;
    //     $setArray = $optionArray = array();
    //     $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();
    //     if($request->has('variants') && !empty($request->variants)){
    //         $setArray = array_unique($request->variants);
    //     }
    //     $startRange = 0; $endRange = 20000;
    //     if($request->has('range') && !empty($request->range)){
    //         $range = explode(';', $request->range);
    //         $clientCurrency->doller_compare;
    //         $startRange = $range[0] * $clientCurrency->doller_compare;
    //         $endRange = $range[1] * $clientCurrency->doller_compare;
    //     }
    //     $multiArray = array();
    //     if($request->has('options') && !empty($request->options)){
    //         foreach ($request->options as $key => $value) {
    //             $multiArray[$request->variants[$key]][] = $value;
    //         }
    //     }
    //     $variantIds = $productIds = array();
    //     if(!empty($multiArray)){
    //         foreach ($multiArray as $key => $value) {
    //             $new_pIds = $new_vIds = array();
    //             $vResult = ProductVariantSet::select('product_variant_sets.product_variant_id', 'product_variant_sets.product_id')
    //                 ->where('product_variant_sets.variant_type_id', $key)
    //                 ->whereIn('product_variant_sets.variant_option_id', $value);
    //             if(!empty($variantIds)){
    //                 $vResult  = $vResult->whereIn('product_variant_sets.product_variant_id', $variantIds);
    //             }
    //             $vResult  = $vResult->groupBy('product_variant_sets.product_variant_id')->get();
    //             if($vResult){
    //                 foreach ($vResult as $key => $value) {
    //                     $new_vIds[] = $value->product_variant_id;
    //                     $new_pIds[] = $value->product_id;
    //                 }
    //             }
    //             $variantIds = $new_vIds;
    //             $productIds = $new_pIds;
    //         }
    //     }
    //     $order_type = $request->has('order_type') ? $request->order_type : '';
    //     $products = Product::with(['category.categoryDetail', 'media.image', 'translation' => function($q) use($langId){
    //                     $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
    //                     },'variant' => function($q) use($langId, $variantIds){
    //                         $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
    //                         if(!empty($variantIds)){
    //                             $q->whereIn('id', $variantIds);
    //                         }
    //                         if(!empty($order_type) && $order_type == 'low_to_high'){
    //                             $q->orderBy('price', 'asc');
    //                         }
    //                         if(!empty($order_type) && $order_type == 'high_to_low'){
    //                             $q->orderBy('price', 'desc');
    //                         }
    //                         $q->groupBy('product_id');
    //                     },
    //                 ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating')
    //                 ->where('vendor_id', $vid)
    //                 ->where('category_id', $cid)
    //                 ->where('is_live', 1)
    //                 ->whereIn('id', function($qr) use($startRange, $endRange){
    //                     $qr->select('product_id')->from('product_variants')->where('price',  '>=', $startRange)->where('price',  '<=', $endRange);
    //                 });
    //     if(!empty($productIds)){
    //         $products = $products->whereIn('id', $productIds);
    //     }
    //     if($request->has('brands') && !empty($request->brands)){
    //         $products = $products->whereIn('brand_id', $request->brands);
    //     }
    //     if(!empty($order_type) && $request->order_type == 'rating'){
    //         $products = $products->orderBy('averageRating', 'desc');
    //     }
    //     $paginate = $request->has('limit') ? $request->limit : 12;
    //     $products = $products->paginate($paginate);
    //     if(!empty($products)){
    //         foreach ($products as $key => $product) {
    //             $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
    //             foreach ($product->variant as $k => $v) {
    //                 $product->variant[$k]->multiplier = $clientCurrency->doller_compare;
    //             }
    //         }
    //     }
    //     return response()->json([
    //         'data' => $products,
    //     ]);
    // }
}