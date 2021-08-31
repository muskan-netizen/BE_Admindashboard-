<?php

namespace App\Http\Controllers\Front;

use DB;
use Auth;
use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Front\FrontController;
use Redirect;
use App\Models\{AddonSet, Cart, CartAddon, CartProduct, User, Product, ClientCurrency, ProductVariant, ProductVariantSet,OrderProduct,VendorOrderStatus,OrderProductRating,Category, Vendor};
class ProductController extends FrontController{
    private $field_status = 2;
    /**
     * Display product By Id
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $domain = '', $url_slug){
        $user = Auth::user();
        $preferences = Session::get('preferences');
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);
        $product = Product::select('id', 'vendor_id')->where('url_slug', $url_slug)->firstOrFail();
        if( (isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1) ){
            if($product){
                $productVendorId = $product->vendor_id;
                if(Session::has('vendors')){
                    $vendors = Session::get('vendors');
                    if(!in_array($productVendorId, $vendors)){
                        abort(404);
                    }
                }else{
                    // abort(404);
                }
            }
        }
        $p_id = $product->id;
        $product = Product::with([
            'variant' => function ($sel) {
                $sel->groupBy('product_id');
            },
            'variant.set' => function ($sel) {
                $sel->select('product_variant_id', 'variant_option_id');
            },
            'variant.media.pimage.image', 'related', 'upSell', 'crossSell', 'vendor', 'media.image', 'translation' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                $q->where('language_id', $langId);
            },
            'addOn' => function ($q1) use ($langId) {
                $q1->join('addon_sets as set', 'set.id', 'product_addons.addon_id');
                $q1->join('addon_set_translations as ast', 'ast.addon_id', 'set.id');
                $q1->select('product_addons.product_id', 'set.min_select', 'set.max_select', 'ast.title', 'product_addons.addon_id');
                $q1->where('ast.language_id', $langId);
            },
            'variantSet' => function ($z) use ($langId, $p_id) {
                $z->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id');
                $z->join('variant_translations as vt', 'vt.variant_id', 'vr.id');
                $z->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title');
                $z->where('vt.language_id', $langId);
                $z->where('product_variant_sets.product_id', $p_id);
            },
            'variantSet.option2' => function ($zx) use ($langId, $p_id) {
                $zx->where('vt.language_id', $langId)
                    ->where('product_variant_sets.product_id', $p_id);
            },
            'addOn.setoptions' => function ($q2) use ($langId) {
                $q2->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                $q2->select('addon_options.id', 'addon_options.title', 'addon_options.price', 'apt.title', 'addon_options.addon_id');
                $q2->where('apt.language_id', $langId);
            },
            'category.categoryDetail.allParentsAccount'
        ]);
        if($user){
            $product = $product->with('inwishlist', function ($query) use($user) {
                $query->where('user_wishlists.user_id', $user->id);
            });
        }
        $product = $product->with('related')->select('id', 'sku', 'inquiry_only', 'url_slug', 'weight', 'weight_unit', 'vendor_id', 'has_variant', 'has_inventory', 'averageRating')
            ->where('url_slug', $url_slug)
            ->where('is_live', 1)
            ->firstOrFail();
        $doller_compare = 1;
        $clientCurrency = ClientCurrency::where('currency_id', Session::get('customerCurrency'))->first();
        if($clientCurrency){
            $doller_compare = $clientCurrency->doller_compare;
        }
        $product->related_products = $this->metaProduct($langId, $doller_compare, 'relate', $product->related);
        foreach ($product->variant as $key => $value) {
            if(isset($product->variant[$key])){
            $product->variant[$key]->multiplier = $clientCurrency ? $clientCurrency->doller_compare : '1.00';
            }
        }
        $vendorIds[] = $product->vendor_id;
        $np = $this->productList($vendorIds, $langId, $curId, 'is_new');
        $newProducts = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;
        foreach ($product->addOn as $key => $value) {
            foreach ($value->setoptions as $k => $v) {
                $v->multiplier = $clientCurrency->doller_compare;
            }
        }
        $rating_details = '';
        $rating_details = OrderProductRating::select('*','created_at as time_zone_created_at')->where(['product_id' => $product->id])->get();
        $is_inwishlist_btn = 0;
        if($product->category){
            $category_detail = Category::select()->where('id',$product->category->category_id)->first();
            if($category_detail && $user){
                $is_inwishlist_btn = $category_detail ? $category_detail->show_wishlist : 0;
            }
        }

        $availableSet = ProductVariantSet::where('product_id', $p_id)->get();
        $sets = array();
        foreach($availableSet->groupBy('product_variant_id') as $avSets){
            $variant_type_id = array();
            $variant_option_id = array();
            foreach($avSets as $avSet){
                $variant_type_id[] = $avSet->variant_type_id;
                $variant_option_id[] = $avSet->variant_option_id;
            }
            $sets[] = ['variant_types' => $variant_type_id, 'variant_options' => $variant_option_id];
        } 
        if($product->category->categoryDetail->type_id == 8){
            $cartDataGet = $this->getCartOnDemand($request);
            $nlistData = clone $product;
            $nlistData = $nlistData->where('url_slug', $url_slug)->paginate(10);
            if(!empty($nlistData)){
                foreach ($nlistData as $key => $value) {
                    $value->translation_title = (!empty($value->translation->first())) ? $value->translation->first()->title : $value->sku;
                    $value->translation_description = (!empty($value->translation->first())) ? $value->translation->first()->body_html : $value->sku;
                    $value->variant_multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                    $value->variant_price = (!empty($value->variant->first())) ? $value->variant->first()->price : 0;
                 }
            }
            $listData = $nlistData;
            $category = $category_detail;

           
            return view('frontend.ondemand.index')->with(['time_slots' =>  $cartDataGet['time_slots'], 'period' =>  $cartDataGet['period'] ,'cartData' => $cartDataGet['cartData'], 'addresses' => $cartDataGet['addresses'], 'countries' => $cartDataGet['countries'], 'subscription_features' => $cartDataGet['subscription_features'], 'guest_user'=>$cartDataGet['guest_user'],'listData' => $listData, 'category' => $category,'navCategories' => $navCategories]);
        }
        elseif($product->category->categoryDetail->type_id == 7)
        {
            return Redirect::route('categoryDetail','cabservice');
        }
        else{
            $vendor_info = Vendor::where('id', $product->vendor_id)->with('slot')->first();
            if($vendor_info){
                if($vendor_info->show_slot == 1){
                    $vendor_info->show_slot_option = 1;
                }elseif ($vendor_info->slot->count() > 0) {
                    $vendor_info->show_slot_option = 1;
                }else{
                    $vendor_info->show_slot_option = 0;
                }
            }
            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
                    $url = "https://";   
            else  
                    $url = "http://";   
            // Append the host(domain name, ip) to the URL.   
            $url.= $_SERVER['HTTP_HOST'];   
            
            // Append the requested resource location to the URL   
            $url.= $_SERVER['REQUEST_URI'];    
                
            $shareComponent = \Share::page(
                $url,
                'Your share text comes here',
            )
            ->facebook()
            ->twitter()
            // ->linkedin()
            // ->telegram()
            ->whatsapp();      
            // ->reddit();
    
            // dd($shareComponent);
            $category = $product->category->categoryDetail;
            return view('frontend.product')->with(['shareComponent' => $shareComponent, 'sets' => $sets, 'vendor_info' => $vendor_info, 'product' => $product, 'navCategories' => $navCategories, 'newProducts' => $newProducts, 'rating_details' => $rating_details, 'is_inwishlist_btn' => $is_inwishlist_btn, 'category' => $category]);
        
        }
   }
    public function metaProduct($langId, $multiplier, $for = 'relate', $productArray = []){
        if(empty($productArray)){
            return $productArray;
        }
        $productIds = array();
        foreach ($productArray as $key => $value) {
            if($for == 'relate'){
                $productIds[] = $value->related_product_id;
            }
            if($for == 'upSell'){
                $productIds[] = $value->upsell_product_id;
            }
            if($for == 'cross'){
                $productIds[] = $value->cross_product_id;
            }
        }
        $products = Product::with(['media' => function($q){
                            $q->groupBy('product_id');
                        }, 'media.image',
                        'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                        },
                    ])->select('id', 'sku', 'averageRating', 'url_slug')
                    ->whereIn('id', $productIds);
        $products = $products->get();
        if(!empty($products)){
            foreach ($products as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant[$k]->multiplier = $multiplier;
                }
            }
        }
        return $products;
    }
    /**
     * Display product variant data
     *
     * @return \Illuminate\Http\Response
     */
    public function getVariantData(Request $request, $domain = '', $sku){
        $product = Product::select('id')->where('sku', $sku)->firstOrFail();
        $pv_ids = array();
        $product_variant = '';
        if ($request->has('options') && !empty($request->options)) {
            foreach ($request->options as $key => $value) {
                // $newIds = array();
                // $product_variant = ProductVariantSet::where('variant_type_id', $request->variants[$key])
                //     ->where('variant_option_id', $request->options[$key]);

                // if (!empty($pv_ids)) {
                //     $product_variant = $product_variant->whereIn('product_variant_id', $pv_ids);
                // }
                // $product_variant = $product_variant->where('product_id', $product->id)->get();
                // if ($product_variant) {
                //     foreach ($product_variant as $key => $value) {
                //         if(!in_array($value->product_variant_id, $pv_ids)){
                //             $pv_ids[] = $value->product_variant_id;
                //         }
                //     }
                // }
                // $pv_ids = $newIds;

                if ($product_variant) {
                    $pv_ids = array();
                    foreach ($product_variant as $k => $variant) {
                        if($request->options[$key]){
                            $variantSet = ProductVariantSet::whereIn('variant_type_id', $request->variants)
                            ->whereIn('variant_option_id', $request->options)
                            ->where('product_variant_id', $variant->product_variant_id)->get();
                            if(count($variantSet) == count($request->variants)){
                                // if(!in_array($variantSet->product_variant_id, $pv_ids)){
                                    $pv_ids[] = $variant->product_variant_id;
                                // }
                            }
                        }
                    }
                }
                else{
                    $product_variant = ProductVariantSet::where('variant_type_id', $request->variants[$key])
                    ->where('variant_option_id', $request->options[$key])->where('product_id', $product->id)->get();
                    if($product_variant){
                        foreach ($product_variant as $k => $variant) {
                            if(!in_array($variant->product_variant_id, $pv_ids)){
                                $pv_ids[] = $variant->product_variant_id;
                            }
                        }
                    }
                }
            }
        }
        $sets = array();
        $clientCurrency = ClientCurrency::where('currency_id', Session::get('customerCurrency'))->first();
        $availableSets = Product::with(['variantSet.variantDetail','variantSet.option2'=>function($q)use($product, $pv_ids){
            $q->where('product_id', $product->id); //->whereIn('product_variant_id', $pv_ids);
        }])
        ->select('id')
        ->where('id', $product->id)->first();

        if($pv_ids){
            $variantData = ProductVariant::with('product', 'media.pimage.image')->select('id', 'sku', 'quantity', 'price', 'compare_at_price', 'barcode', 'product_id')
                ->whereIn('id', $pv_ids)->get();
            if ($variantData) {
                foreach($variantData as $variant){
                    $variant->productPrice = Session::get('currencySymbol') . number_format(($variant->price * $clientCurrency->doller_compare), 2, '.', '');
                    // $sets[] = $availableSet->toArray();
                    // foreach($availableSet->groupBy('product_variant_id') as $avSets){
                    //     $variant_type_id = array();
                    //     $variant_option_id = array();
                    //     foreach($avSets as $avSet){
                    //         $variant_type_id[] = $avSet->variant_type_id;
                    //         $variant_option_id[] = $avSet->variant_option_id;
                    //     }
                    //     $sets[] = ['variant_types' => $variant_type_id, 'variant_options' => $variant_option_id];
                    // }
                }
                if(count($variantData) <= 1){
                    $variantData = $variantData->first()->toArray();
                }else{
                    $variantData = array();
                }
                return response()->json(array('status' => 'Success', 'variant' => $variantData, 'availableSets' => $availableSets->variantSet));
            }
        }
        return response()->json(array('status' => 'Error', 'message' => 'This option is currenty not available', 'availableSets' => $availableSets->variantSet));
    }
}
