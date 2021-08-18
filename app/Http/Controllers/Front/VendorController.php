<?php

namespace App\Http\Controllers\Front;

use Auth;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Category, Brand, Product, ClientLanguage, Vendor, ClientCurrency, ProductVariantSet};

class VendorController extends FrontController
{
    private $field_status = 2;
    
    public function viewAll(){
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $vendors = Vendor::with('products')->select('id', 'name', 'banner', 'order_pre_time', 'order_min_amount', 'logo','slug')->where('status', 1)->get();
        foreach ($vendors as $key => $value) {
            $value->vendorRating = $this->vendorRating($value->products);
        }
        return view('frontend/vendor-all')->with(['navCategories' => $navCategories,'vendors' => $vendors]);
    }
    /**
     * Display product By Vendor
     *
     * @return \Illuminate\Http\Response
     */
    public function vendorProducts(Request $request, $domain = '', $slug = 0){
        $preferences = Session::get('preferences');
        $vendor = Vendor::select('id','email', 'name', 'slug', 'desc', 'logo', 'banner', 'address', 'latitude', 'longitude', 'order_min_amount', 'order_pre_time', 'auto_reject_time', 'dine_in', 'takeaway', 'delivery', 'vendor_templete_id', 'is_show_vendor_details', 'website')->where('slug', $slug)->where('status', 1)->firstOrFail();
        if( $request->has('table') ){
            if(!Auth::user()){
                session(['url.intended' => url()->full()]);
                return redirect()->route('customer.login');
            }else{
                if(!Session::has('vendorTable')){
                    Session::put('vendorTable', $request->table);
                    Session::put('vendorTableVendorId', $vendor->id);
                    Session::put('vendorType', 'dine_in');
                }
            }
        }
        if( (isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1) ){
            if(Session::has('vendors')){
                $vendors = Session::get('vendors');
                if(!in_array($vendor->id, $vendors)){
                    abort(404);
                }
            }else{
                // abort(404);
            }
        }
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();
        $brands = Product::with(['brand.translation'=> function($q) use($langId){
                    $q->select('title', 'brand_id')->where('brand_translations.language_id', $langId);
                }])->select('brand_id')->where('vendor_id', $vendor->id)
                ->where('brand_id', '>', 0)->groupBy('brand_id')->get();
        $variantSets = ProductVariantSet::with(['options' => function($zx) use($langId){
                            $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id');
                            $zx->select('variant_options.*', 'vt.title');
                            $zx->where('vt.language_id', $langId);
                        },
                        'variantDetail.varcategory.cate'
                    ]
                )->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id')
                    ->join('variant_translations as vt','vt.variant_id','vr.id')
                    ->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title')
                    ->where('vt.language_id', $langId)
                    ->whereIn('product_id', function($qry) use($vendor){ 
                        $qry->select('id')->from('products')
                            ->where('vendor_id', $vendor->id);
                    })->groupBy('product_variant_sets.variant_type_id')->get();
        $navCategories = $this->categoryNav($langId);
        $vendorIds[] = $vendor->id;
        $np = $this->productList($vendorIds, $langId, $curId, 'is_new');
        foreach($np as $new){
            $new->translation_title = (!empty($new->translation->first())) ? $new->translation->first()->title : $new->sku;
            $new->variant_multiplier = (!empty($new->variant->first())) ? $new->variant->first()->multiplier : 1;
            $new->variant_price = (!empty($new->variant->first())) ? $new->variant->first()->price : 0;
        }
        $newProducts = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;
        $listData = $this->listData($langId, $vendor->id, $vendor->vendor_templete_id);
        $inqury_count = 0;
        foreach($listData as $ld){
            if($ld->inquiry_only == 1){
                $inqury_count++;
            }
        }
        if($listData->count() == $inqury_count){
            $show_range = 0;
        }
        else{
            $show_range = 1;
        }
        $range_products = Product::join('product_variants', 'product_variants.product_id', '=', 'products.id')->orderBy('product_variants.price', 'desc')->select('*')->where('is_live', 1)->where('vendor_id', $vendor->id)->get();
        $page = ($vendor->vendor_templete_id == 2) ? 'categories' : 'products';
        return view('frontend/vendor-'.$page)->with(['show_range' => $show_range, 'range_products' => $range_products, 'vendor' => $vendor, 'listData' => $listData, 'navCategories' => $navCategories, 'newProducts' => $newProducts, 'variantSets' => $variantSets, 'brands' => $brands]);
    }

    /**
     * Display product By Vendor Category
     *
     * @return \Illuminate\Http\Response
     */
    public function vendorCategoryProducts(Request $request, $domain = '', $slug1 = 0, $slug2 = 0){
        $preferences = Session::get('preferences');
        $vendor = Vendor::select('id', 'name', 'slug', 'desc', 'logo', 'banner', 'address', 'latitude', 'longitude', 'order_min_amount', 'order_pre_time', 'auto_reject_time', 'dine_in', 'takeaway', 'delivery', 'vendor_templete_id')->where('slug', $slug1)->where('status', 1)->firstOrFail();
        if( (isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1) ){
            if(Session::has('vendors')){
                $vendors = Session::get('vendors');
                if(!in_array($vendor->id, $vendors)){
                    abort(404);
                }
            }else{
                // abort(404);
            }
        }
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();
        
        $brands = Product::with(['brand.translation'=> function($q) use($langId){
                    $q->select('title', 'brand_id')->where('brand_translations.language_id', $langId);
                }])->select('brand_id')->where('vendor_id', $vendor->id)
                ->where('brand_id', '>', 0)->groupBy('brand_id')->get();
                $variantSets = ProductVariantSet::with(['options' => function($zx) use($langId){
                    $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id');
                    $zx->select('variant_options.*', 'vt.title');
                    $zx->where('vt.language_id', $langId);
                }
                ])->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id')
                ->join('variant_translations as vt','vt.variant_id','vr.id')
                ->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title')
                ->where('vt.language_id', $langId)
                ->whereIn('product_id', function($qry) use($vendor){ 
                    $qry->select('id')->from('products')
                    ->where('vendor_id', $vendor->id);
                })->groupBy('product_variant_sets.variant_type_id')->get();
        $navCategories = $this->categoryNav($langId);
        $vendorIds[] = $vendor->id;
        $np = $this->productList($vendorIds, $langId, $curId, 'is_new');
        foreach($np as $new){
            $new->translation_title = (!empty($new->translation->first())) ? $new->translation->first()->title : $new->sku;
            $new->variant_multiplier = (!empty($new->variant->first())) ? $new->variant->first()->multiplier : 1;
            $new->variant_price = (!empty($new->variant->first())) ? $new->variant->first()->price : 0;
        }
        $newProducts = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;
        if(!empty($slug2)){
            $vendor->vendor_templete_id = '';
        }
        $listData = $this->listData($langId, $vendor->id, $vendor->vendor_templete_id, $slug2);
        $inqury_count = 0;
        foreach($listData as $ld){
            if($ld->inquiry_only == 1){
                $inqury_count++;
            }
        }
        if($listData->count() == $inqury_count){
            $show_range = 0;
        }
        else{
            $show_range = 1;
        }
        $page = ($vendor->vendor_templete_id == 2) ? 'categories' : 'products';
        $range_products = Product::join('product_variants', 'product_variants.product_id', '=', 'products.id')->orderBy('product_variants.price', 'desc')->select('*')->where('is_live', 1)->where('vendor_id', $vendor->id)->get();
        return view('frontend/vendor-'.$page)->with(['vendor' => $vendor, 'show_range' => $show_range, 'listData' => $listData, 'navCategories' => $navCategories, 'newProducts' => $newProducts, 'variantSets' => $variantSets, 'brands' => $brands, 'range_products' => $range_products]);
    }

    public function listData($langId, $vid, $type = '', $categorySlug = ''){
        $pagiNate = (Session::has('cus_paginate')) ? Session::get('cus_paginate') : 12;
        if($type == 2){
            // display categories
            $products = Product::select('category_id')->distinct()->where('vendor_id', $vid)->where('is_live', 1)->get();
            $vendor_categories = array();
            foreach($products as $key => $product ){
                $vendor_categories[] = $product->category_id;
            }
            $categoryData = Category::select('id', 'icon', 'slug', 'type_id', 'image')
                            ->whereIn('id', $vendor_categories);
            $categoryData = $categoryData->paginate($pagiNate);
            foreach ($categoryData as $key => $value) {
                $value->translation_name = ($value->translation->first()) ? $value->translation->first()->name : 'NA';
            }
            return $categoryData;
        }else{
            $clientCurrency = ClientCurrency::where('currency_id', Session::get('customerCurrency'))->first();
            $products = Product::with(['media.image',
                        'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                        },
                    ])->select('id', 'sku', 'description', 'requires_shipping', 'sell_when_out_of_stock', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'Requires_last_mile', 'averageRating', 'inquiry_only');
            if(!empty($categorySlug)){
                $category = Category::select('id')->where('slug', $categorySlug)->firstOrFail();
                $products = $products->where('category_id', $category->id);
            }
            $products = $products->where('is_live', 1)->where('vendor_id', $vid)->paginate($pagiNate);
            if(!empty($products)){
                foreach ($products as $key => $value) {
                    $value->translation_title = (!empty($value->translation->first())) ? $value->translation->first()->title : $value->sku;
                    $value->variant_multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                    $value->variant_price = (!empty($value->variant->first())) ? $value->variant->first()->price : 0;
                    // foreach ($value->variant as $k => $v) {
                    //     $value->variant[$k]->multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                    // }
                }
            }
            $listData = $products;
            return $listData;
        }
    }

    /**
     * Product filters on category Page
     * @return \Illuminate\Http\Response
     */
    public function vendorFilters(Request $request, $domain = '', $vid = 0){
        $setArray = $optionArray = array();
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
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
                $vResult = ProductVariantSet::join('product_categories as pc', 'product_variant_sets.product_id', 'pc.product_id')->select('product_variant_sets.product_variant_id', 'product_variant_sets.product_id')
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
        $products = Product::with(['media.image', 'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId, $variantIds){
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
                        $qr->select('product_id')->from('product_variants')
                            ->where('status', 1)
                            ->where('price',  '>=', $startRange)
                            ->where('price',  '<=', $endRange);
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
        $pagiNate = (Session::has('cus_paginate')) ? Session::get('cus_paginate') : 12;
        $products = $products->paginate($pagiNate);
        if(!empty($products)){
            foreach ($products as $key => $value) {
                $value->translation_title = (!empty($value->translation->first())) ? $value->translation->first()->title : $value->sku;
                $value->variant_multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                $value->variant_price = (!empty($value->variant->first())) ? $value->variant->first()->price : 0;
                // foreach ($value->variant as $k => $v) {
                //     $value->variant[$k]->multiplier = $clientCurrency->doller_compare;
                // }
            }
        }
        $listData = $products;
        $returnHTML = view('frontend.ajax.productList')->with(['listData' => $listData])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

}