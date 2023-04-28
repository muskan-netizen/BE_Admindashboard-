<?php

namespace App\Http\Traits\HomePage;

use App\Models\{Category, HomeProduct, OrderProductRating, OrderVendorProduct, Product, ProductCategory, ProductRecentlyViewed, Vendor, VendorCategory, VendorCities, PromoCodeDetail, Promocode};
use Carbon\Carbon;
use Session, DB, Auth;
use Illuminate\Support\Str;


trait HomePageTrait
{


    public function getMostSellingVendors($preferences, $vendor_ids)
    {
        $latitude = Session::get('latitude');
        $longitude = Session::get('longitude');
        $mostSellingVendors = Vendor::with('slot.day', 'slotDate', 'products')->select('vendors.*', DB::raw('count(vendor_id) as max_sales'))->join('order_vendors', 'vendors.id', '=', 'order_vendors.vendor_id')->whereIn('vendors.id', $vendor_ids)->where('vendors.status', 1)->groupBy('order_vendors.vendor_id')->orderBy(DB::raw('count(vendor_id)'), 'desc');

        // add hyperlocal check to get vendors
        if (($preferences->is_hyperlocal == 1) && ($latitude) && ($longitude)) {

            if (!empty($latitude) && !empty($longitude)) {
                $mostSellingVendors = $mostSellingVendors->whereHas('serviceArea', function ($query) use ($latitude, $longitude) {
                    $query->select('vendor_id')
                        ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $latitude . " " . $longitude . ")'))");
                });
            }
        }
        $mostSellingVendors = $mostSellingVendors->get();

        if ((!empty($mostSellingVendors) && count($mostSellingVendors) > 0)) {
            foreach ($mostSellingVendors as $key => $value) {
                $value->vendorRating = $this->vendorRatings($value->products);
                // $value->name = Str::limit($value->name, 15, '..');
                if (($preferences) && ($preferences->is_hyperlocal == 1)) {
                    $value = $this->getVendorDistanceWithTime($latitude, $longitude, $value, $preferences);
                }
                $vendorCategories = VendorCategory::with('category.translation_one')->where('vendor_id', $value->id)->where('status', 1)->get();
                $categoriesList = '';
                foreach ($vendorCategories as $key => $category) {
                    if ($category->category) {
                        $categoriesList = $categoriesList . @$category->category->translation_one->name;
                        if ($key !=  $vendorCategories->count() - 1) {
                            $categoriesList = $categoriesList . ', ';
                        }
                    }
                }
                $value->categoriesList = $categoriesList;

                $value->is_vendor_closed = 0;
                if ($value->show_slot == 0) {
                    if (($value->slotDate->isEmpty()) && ($value->slot->isEmpty())) {
                        $value->is_vendor_closed = 1;
                    } else {
                        $value->is_vendor_closed = 0;
                        if ($value->slotDate->isNotEmpty()) {
                            $value->opening_time = Carbon::parse($value->slotDate->first()->start_time)->format('g:i A');
                            $value->closing_time = Carbon::parse($value->slotDate->first()->end_time)->format('g:i A');
                        } elseif ($value->slot->isNotEmpty()) {
                            $value->opening_time = Carbon::parse($value->slot->first()->start_time)->format('g:i A');
                            $value->closing_time = Carbon::parse($value->slot->first()->end_time)->format('g:i A');
                        }
                    }
                }
            }
        }
        if (($preferences) && ($preferences->is_hyperlocal == 1)) {
            $mostSellingVendors = $mostSellingVendors->sortBy('lineOfSightDistance')->values()->all();
        }
        return $mostSellingVendors;
    }


    public function getSpotLight($preferences, $vendor_ids, $language_id, $currency_id, $p_dim)
    {
        $spotlight_products = [];
        $products = Product::with([
            'category.categoryDetail.translation' => function ($q) use ($language_id) {
                $q->where('category_translations.language_id', $language_id);
            },
            'vendor',
            'media' => function ($q) {
                $q->groupBy('product_id');
            }, 'media.image',
            'translation' => function ($q) use ($language_id) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $language_id);
            },

        ])
            ->selectRaw('product_variants.sku, product_id, quantity,compare_at_price,  price, barcode, (compare_at_price - price) as discount_amount, ((compare_at_price - price)/compare_at_price)*100 as discount_percentage,   products.id, products.sku, url_slug, weight_unit, weight, vendor_id, has_variant, has_inventory, sell_when_out_of_stock, requires_shipping, Requires_last_mile, averageRating, inquiry_only
        ')

            ->join('product_variants', 'products.id', 'product_variants.product_id');

        $products = $products->whereHas('vendor', function ($q) use ($vendor_ids) {
            $q->where('status', 1);
            $q->whereIn('vendors.id', $vendor_ids);
        })->where('is_live', 1)
            ->orderBy(DB::raw("((product_variants.compare_at_price - product_variants.price)/product_variants.compare_at_price)*100"), 'desc')
            ->take(8)->get();

        if (!empty($products)) {
            foreach ($products as $key => $product) {

                $multiply =  Session::get('currencyMultiplier') ?? 1;
                $title = $product->translation->first() ? $product->translation->first()->title : $product->sku;
                $image_url = $product->media->first() && !is_null($product->media->first()->image) ? $product->media->first()->image->path['image_fit'] . $p_dim . $product->media->first()->image->path['image_path'] : $this->loadDefaultImage();
                $spotlight_products[] = array(
                    'id' => $product->id,
                    'tag_title' => $spotlight_products_title ?? '0',
                    'image_url' => $image_url,
                    'media' => $product->media ,
                    'variant' => $product->variant ,
                    'sku' => $product->sku,
                    'title' => Str::limit($title, 18, '..'),
                    'url_slug' => $product->url_slug,
                    'discount_percentage' => (int) $product->discount_percentage,
                    'averageRating' => number_format($product->averageRating, 1, '.', ''),
                    'inquiry_only' => $product->inquiry_only,
                    'vendor_name' => $product->vendor ? $product->vendor->name : '',
                    'vendor' => $product->vendor,
                    'price' => Session::get('currencySymbol') . ' ' . (decimal_format(@$product->variant->first()->price * $multiply, ',')),
                    'compare_price' =>@$product->variant->first()->compare_at_price * $multiply,
                    'compare_price_numeric' =>@$product->variant->first()->compare_at_price * $multiply,
                    'price_numeric' =>@$product->variant->first()->price * $multiply,
                    'category' => (@$product->category->categoryDetail->translation) ? @$product->category->categoryDetail->translation->first()->name : @$product->category->categoryDetail->slug,
                    'categoryDetail' => (@$product->category->categoryDetail) ? @$product->category->categoryDetail: []
                );
            }
        }
        // $spotlight_products = [];

        return $spotlight_products;
    }

    public function getSingleCategoryProducts()
    {
        $product_ids = [];
        if (checkTableExists('home_products')) {
            $single_category_products = HomeProduct::whereSlug('single_category_products')->first();
            if (@$single_category_products) {
                $product_ids = ProductCategory::select('product_id')->where('category_id', $single_category_products->category_id)->get();
            }
        }
        
        return $product_ids;
    }

    public function getSingleCategoryWithProducts()
    {
        $product_ids = [];
        if (checkTableExists('home_products')) {
            $single_category_products = HomeProduct::with(['categoryDetail.products.variants','categoryDetail.products.media.image'])->whereSlug('single_category_products')->first();
        }
        
        return $single_category_products;
    }

    public function getSpotlightProducts()
    {
        $spotlight_products = Product::with(['variants','media.image'
            ])->select('id', 'sku','title', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating', 'inquiry_only','spotlight_deals')->where('spotlight_deals', 1)->take(9)->get();
        return $spotlight_products; 
    }

    public function getSelectedProduct($layout_id)
    {
        $selected_products = HomeProduct::with(['products.variants','products.media.image'])->where('layout_id',$layout_id)->get();
        return $selected_products;
    }

    public function getProducts($preferences, $vendor_ids, $language_id, $currency_id = 'USD', $p_dim, $product_ids, $take = 8)
    {
        $productFiltered = [];
        if (@$product_ids) {
            $products = Product::with([
                'category.categoryDetail.translation' => function ($q) use ($language_id) {
                    $q->where('category_translations.language_id', $language_id);
                },
                'vendor',
                'media' => function ($q) {
                    $q->groupBy('product_id');
                }, 'media.image',
                'translation' => function ($q) use ($language_id) {
                    $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $language_id);
                },

            ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating', 'inquiry_only');


            $products = $products->where('is_live', 1);
            // ->whereHas('vendor', function ($q) use ($vendor_ids) {
            //     $q->where('status', 1);
            //     $q->whereIn('vendors.id', $vendor_ids);
            // });
            $products = $products->whereIn('id', $product_ids)
                ->take($take)->get();

            
            if (!empty($products)) {
                foreach ($products as  $product) {
                    $multiply =  Session::get('currencyMultiplier') ?? 1;
                    $title = $product->translation->first() ? $product->translation->first()->title : $product->sku;
                    $image_url = $product->media->first() && !is_null($product->media->first()->image) ? $product->media->first()->image->path['image_fit'] . $p_dim . $product->media->first()->image->path['image_path'] : $this->loadDefaultImage();
                    $is_p2p = 0;
                    if(@$product->category->categoryDetail->type_id && @$product->category->categoryDetail->type_id == 13){
                        $is_p2p = 1;
                    }
                    $productFiltered[] = array(
                        'id' => $product->id,
                        'tag_title' => $spotlight_products_title ?? 'Single Category Products',
                        'image_url' => $image_url,
                        'media' => $product->media ,
                        'variant' => $product->variant ,
                        'sku' => $product->sku,
                        'title' => Str::limit($title, 18, '..'),
                        'url_slug' => $product->url_slug,
                        'discount_percentage' => (int) $product->discount_percentage,
                        'averageRating' => number_format($product->averageRating, 1, '.', ''),
                        'inquiry_only' => $product->inquiry_only,
                        'vendor_name' => $product->vendor ? $product->vendor->name : '',
                        'vendor' => $product->vendor,
                        'price' => Session::get('currencySymbol') . ' ' . (decimal_format(@$product->variant->first()->price * $multiply, ',')),
                        'compare_price' =>@$product->variant->first()->compare_at_price * $multiply,
                        'compare_price_numeric' =>@$product->variant->first()->compare_at_price * $multiply,
                        'price_numeric' =>@$product->variant->first()->price * $multiply,
                        'categoryDetail' => (@$product->category->categoryDetail) ? @$product->category->categoryDetail: [],
                        'category' => (@$product->category->categoryDetail->translation) ? @$product->category->categoryDetail->translation->first()->name : @$product->category->categoryDetail->slug,
                        'is_p2p' => $is_p2p
                    );
                }
            }
        }
        return $productFiltered;
    }

    public function getSelectedProducts()
    {
        $product_ids = [];
        if (checkTableExists('home_products')) {
            $single_category_products = HomeProduct::whereSlug('selected_products')->first();
            if(@$single_category_products){
                $product_ids = json_decode($single_category_products->products);
            }
            
        }
        return $product_ids;
    }

    public function getMostPopularProducts()
    {
        $product_ids = [];
        $most_sold = OrderVendorProduct::selectRaw('id, product_id, count(product_id) as total')->whereHas('statusDelievered')->groupBy('product_id')->orderBy('total', 'DESC')->take(5)->get()->pluck('product_id');
        // dd($most_sold);
        $most_viewed = ProductRecentlyViewed::selectRaw('id, product_id, count(product_id) as total')->groupBy('product_id')->orderBy('total', 'DESC')->take(5)->get()->pluck('product_id');

        $product_ids = $most_sold->merge($most_viewed);
        return $product_ids;
    }


    public function getTopRatedProducts()
    {
        
        $product_ids = OrderProductRating::selectRaw('id, product_id, count(product_id) as total')->groupBy('product_id')->orderBy('total', 'DESC')->take(5)->get()->pluck('product_id');
        return $product_ids;
    }

     /* Get vendor rating from its products rating */
     public function vendorRatings($vendorProducts)
     {
         $vendor_rating = 0;
         if($vendorProducts->isNotEmpty()){
             $product_rating = 0;
             $product_count = 0;
             foreach($vendorProducts as $product){
                 if($product->averageRating > 0){
                     $product_rating = $product_rating + $product->averageRating;
                     $product_count++;
                 }
             }
             if($product_count > 0){
                 $vendor_rating = $product_rating / $product_count;
             }
         }
         return number_format($vendor_rating, 1, '.', '');
     }

     /* Get vendor rating from its products rating */
     public function vendorNoOfRatings($vendorProducts)
     {
         $vendor_rating = 0;
         $product_rating = 0;
         $product_count = 0;
         if($vendorProducts->isNotEmpty()){
            
             foreach($vendorProducts as $product){
                 if($product->averageRating > 0){
                     $product_count++;
                 }
             }
         }
         return $product_count;
     }

     public function getVendorWisePromoCodes($vendor_id){
        $now = Carbon::now()->toDateTimeString();
        $vendor_promo_code_details = PromoCodeDetail::whereHas('promocode')->where('refrence_id', $vendor_id)->pluck('promocode_id');
        $result2 = Promocode::whereIn('id', $vendor_promo_code_details->toArray())->where('restriction_on', 1)->whereHas('details', function($q) use($vendor_id){
            $q->where('refrence_id', $vendor_id);
        })->where('restriction_on', 1)->where('is_deleted', 0)->whereDate('expiry_date', '>=', $now)->get();
        return $result2;
     }

     public function getRefrenceWisePromoCodes($vendor_ids = [], $product_ids = []){
        $promo_codes = new \Illuminate\Database\Eloquent\Collection;
        $now = Carbon::now()->toDateTimeString();

        $firstOrderCheck = 0;
        if( Auth::user()){
            $userOrder = auth()->user()->orders->first();
            if($userOrder){
                $firstOrderCheck = 1;
            }
        }
        if (!empty($product_ids)) {
            $promo_code_details = PromoCodeDetail::whereIn('refrence_id', $product_ids)->pluck('promocode_id');
            $result1 = Promocode::whereDate('expiry_date', '>=', $now)->where('restriction_on', 0)->where(function ($query) use ($promo_code_details ) {
                $query->where(function ($query2) use ($promo_code_details) {
                    $query2->where('restriction_type', 1);
                    if (!empty($promo_code_details->toArray())) {
                        $query2->whereNotIn('id', $promo_code_details->toArray());
                    }
                });

                $query->orWhere(function ($query1) use ($promo_code_details) {
                    $query1->where('restriction_type', 0);
                    if (!empty($promo_code_details->toArray())) {
                        $query1->whereIn('id', $promo_code_details->toArray());
                    } else {
                        $query1->where('id', 0);
                    }
                });
            });
            if($firstOrderCheck){
                $result1->where('first_order_only', 0);
            }

            $result1->where(['promo_visibility' => 'public']);
    
            $result1 = $result1->where('is_deleted', 0)->get();

            $promo_codes = $promo_codes->merge($result1);
        
        }

        if(!empty($vendor_ids)){
            $vendor_promo_code_details = PromoCodeDetail::whereHas('promocode')->whereIn('refrence_id', $vendor_ids)->pluck('promocode_id');
            $result2 = Promocode::where('restriction_on', 1)->where(function ($query) use ($vendor_promo_code_details ) {
                $query->where(function ($query2) use ($vendor_promo_code_details) {
                    $query2->where('restriction_type', 1);
                    if (!empty($vendor_promo_code_details->toArray())) {
                        $query2->whereNotIn('id', $vendor_promo_code_details->toArray());
                    }
                });

                $query->orWhere(function ($query1) use ($vendor_promo_code_details) {
                    $query1->where('restriction_type', 0);
                    if (!empty($vendor_promo_code_details->toArray())) {
                        $query1->whereIn('id', $vendor_promo_code_details->toArray());
                    } else {
                        $query1->where('id', 0);
                    }
                });
            });
            if($firstOrderCheck){
                $result2->where('first_order_only', 0);
            }

            $result2->where(['promo_visibility' => 'public']);

            $result2 = $result2->where('is_deleted', 0)->whereDate('expiry_date', '>=', $now)->get();
            $promo_codes = $promo_codes->merge($result2);
        }
        return $promo_codes;
     }

     public function vendorProducts_v2($venderIds, $langId, $currency = 'USD', $where = '', $type)
     {
         $products = Product::byProductCategoryServiceType($type)->with([
             'category.categoryDetail.translation' => function ($q) use ($langId) {
                 $q->where('category_translations.language_id', $langId);
             },
             'vendor',
             'media' => function ($q) {
                 $q->groupBy('product_id');
             }, 'media.image',
             'translation' => function ($q) use ($langId) {
                 $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
             },
             'variant' => function ($q) use ($langId) {
                 $q->select('sku', 'product_id', 'quantity', 'price', 'barcode','compare_at_price');
                 $q->groupBy('product_id');
             },
         ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating', 'inquiry_only');
         if ($where !== '' && $where !== 'on_sale') {
             $products = $products->where($where, 1);
         }
         if($where == 'on_sale'){
            $products = $products->whereHas('variant' , function($q){
                $q->where('compare_at_price',  '>',  0);
            });
         }
         $pndCategories = Category::where('type_id', 7)->pluck('id');
         // if (is_array($venderIds)) {
         //     $products = $products->whereIn('vendor_id', $venderIds);
         // }
         if ($pndCategories) {
             $products = $products->whereNotIn('category_id', $pndCategories);
         }
         $products = $products->whereHas('vendor', function($q) use ($type,$venderIds){
                     $q->where('status',1);
                     $q->whereIn('id',$venderIds);
                    //  $q->where($type, 1);
                 })->where('is_live', 1)->take(10)->inRandomOrder()->get();
         if (!empty($products)) {
             foreach ($products as $key => $value) {
                 foreach ($value->variant as $k => $v) {
                     $value->variant[$k]->multiplier = Session::get('currencyMultiplier');
                 }
             }
         }
        return $products;
         //pr( $products->toArray());
     }

    

}
