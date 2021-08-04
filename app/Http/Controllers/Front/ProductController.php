<?php

namespace App\Http\Controllers\Front;
use Auth;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Front\FrontController;
use App\Models\{AddonSet, Cart, CartAddon, CartProduct, User, Product, ClientCurrency, ProductVariant, ProductVariantSet,OrderProduct,VendorOrderStatus,OrderProductRating,Category};
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
            'variant.vimage.pimage.image', 'related', 'upSell', 'crossSell', 'vendor', 'media.image', 'translation' => function ($q) use ($langId) {
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
        $clientCurrency = ClientCurrency::where('currency_id', Session::get('customerCurrency'))->first();
        $product->related_products = $this->metaProduct($langId, $clientCurrency->doller_compare, 'relate', $product->related);
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
        return view('frontend.product')->with(['sets' => $sets, 'product' => $product, 'navCategories' => $navCategories, 'newProducts' => $newProducts, 'rating_details' => $rating_details, 'is_inwishlist_btn' => $is_inwishlist_btn]);
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
        if ($request->has('options') && !empty($request->options)) {
            foreach ($request->options as $key => $value) {
                $newIds = array();
                $product_variant = ProductVariantSet::where('variant_type_id', $request->variants[$key])
                    ->where('variant_option_id', $request->options[$key]);

                if (!empty($pv_ids)) {
                    $product_variant = $product_variant->whereIn('product_variant_id', $pv_ids);
                }
                $product_variant = $product_variant->where('product_id', $product->id)->get();

                if ($product_variant) {
                    foreach ($product_variant as $key => $value) {
                        $newIds[] = $value->product_variant_id;
                    }
                }
                $pv_ids = $newIds;
            }
        }
        $clientCurrency = ClientCurrency::where('currency_id', Session::get('customerCurrency'))->first();
        $variantData = ProductVariant::select('id', 'sku', 'quantity', 'price',  'barcode', 'product_id')
            ->where('id', $pv_ids[0])->first();
        if ($variantData) {
            $variantData->productPrice = Session::get('currencySymbol') . $variantData->price * $clientCurrency->doller_compare;
            $availableSet = ProductVariantSet::where('product_id', $product->id)->get();
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
            return response()->json(array('success' => true, 'result' => $variantData->toArray(), 'set' => $sets));
        }
        return response()->json(array('error' => true, 'result' => NULL));
    }
}
