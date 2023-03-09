<?php
namespace App\Http\Traits;
use App\Models\{ProductRecentlyViewed,WebStylingOption,Product,Category,HomeProduct,ProductCategory,OrderVendorProduct,OrderProductRating};
use Illuminate\Support\Str;
use Auth;
use Session;
use Carbon\Carbon;
use DB;

trait ProductActionTrait{

      
     /**
     * getRecentProductIds
     *
     * @param  mixed $user_id
     * @return void
     */
    public function getRecentProductIds()
    {
        try {
            if(checkColumnExists('product_recently_viewed','product_id')){
                $query =  ProductRecentlyViewed::query();
                if(Auth::check()){
                    $query =  $query->where('user_id', Auth::user()->id);
                } else{
                    $query = $query->where('token_id', session()->get('_token'));
                }
                $return = $query->orderBy('updated_at','DESC')->pluck('product_id');
                if(sizeof($return) > 0){
                    $return = $return->toArray();
                } 
                return $return;
            } else{
                return [];
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
      
       
    }
    /**
     * RecentView
     *
     * @param  mixed $p_id
     * @return void
     */
    public function RecentView($p_id)
    {
        try {
            $token_id = session()->get('_token');
            $user_id = 0;
            $update_by['product_id'] = $p_id;
             if(Auth::check()){
                $user_id = Auth::user()->id;
                $update_by['user_id'] = $user_id;
            } else{
                $update_by['token_id'] = $token_id;
            }
            $RecentlyViewed = [
                'product_id' => $p_id,
                'token_id' => $token_id,
                'user_id' => $user_id,
                'updated_at' => Carbon::now()
            ];
            if(checkColumnExists('product_recently_viewed','product_id')){
                ProductRecentlyViewed::updateOrCreate(
                    $update_by
                ,$RecentlyViewed);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    
      
    }
    
    /**
     * LoginActionRecentView
     *
     * @param  mixed $user_id
     * @return void
     */
    public function LoginActionRecentView($user_id)
    {
        try {
            if(checkColumnExists('product_recently_viewed','product_id')){
                ProductRecentlyViewed::where('token_id', session()->get('_token'))->update(['user_id' => $user_id, 'token_id' => '']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }


    /**
     * LoginActionRecentView
     *
     * @param  mixed $user_id
     * @return void
     */
    public function checkTemplateForAction($t_id)
    {
        try {
            $set_template = WebStylingOption::where('is_selected', 1)->first();
            $val = 0;
            if(isset($set_template)  && $set_template->template_id == $t_id){
                $val = 1;
            }
            return $val;
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function productvendorProducts($venderIds, $langId, $currency = 'USD', $where = '', $type,$p_dim,$is_paginate = '')
    {
        try {
                $recent_ids = $this->getRecentProductIds();
                $rc_ids = [];
                if(sizeof($recent_ids) > 0){
                $rc_ids = $recent_ids->toArray();
                } else {
                    return [];
                }
                $pagiNate = (Session::has('cus_paginate')) ? Session::get('cus_paginate') : 30;
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
                        $q->select('sku', 'product_id', 'quantity', 'price', 'barcode', 'compare_at_price');
                        $q->groupBy('product_id');
                    },
                ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating', 'inquiry_only');
                if ($where !== '') {
                    $products = $products->where($where, 1);
                }
                $products = $products->whereIn('id', $rc_ids);

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
                            $q->where($type, 1);
                        })->where('is_live', 1);
                if($is_paginate ==1){
                    $products  =  $products->paginate($pagiNate);
                    foreach ($products as $key => $value) {
                        $multiply = Session::get('currencyMultiplier') ?? 1;
                        $title = $value->translation->first() ? $value->translation->first()->title : $value->sku;
                        $value->image_url = $value->media->first() ? $value->media->first()->image->path['proxy_url'] . $p_dim . $value->media->first()->image->path['image_path'] : $this->loadDefaultImage();
                        
                        $value->title = Str::limit($title, 18, '..');
                        $value->averageRating = number_format($value->averageRating, 1, '.', '');
                        $value->inquiry_only = $value->inquiry_only;
                        $value->vendor_name = $value->vendor ? $value->vendor->name : '';
                        $value->price = Session::get('currencySymbol') . ' ' . (decimal_format(@$value->variant->first()->price??0 * $multiply,','));
                        $value->compare_at_price = Session::get('currencySymbol') . ' ' . (decimal_format(@$value->variant->first()->compare_at_price??0 * $multiply,','));
                        $value->compare_price_numeric = decimal_format(@$value->variant->first()->compare_at_price??0 * $multiply,',');
                        $value->category =  (@$value->category->categoryDetail->translation) ? @$value->category->categoryDetail->translation->first()->name : @$value->category->categoryDetail->slug;
                    }
                    return $products;
                }else{
                    $products  =  $products->take(10)->inRandomOrder()->get();
                }
               
                $productArray = [];
                if (!empty($products)) {

                    foreach ($products as $key => $value) {
                        $multiply = Session::get('currencyMultiplier') ?? 1;
                        $title = $value->translation->first() ? $value->translation->first()->title : $value->sku;
                        $image_url = $value->media->first() ? $value->media->first()->image->path['proxy_url'] . $p_dim . $value->media->first()->image->path['image_path'] : $this->loadDefaultImage();
                        $productArray[] = array(
                            'tag_title' => $products_tag_title??0,
                            'image_url' => $image_url,
                            'sku' => $value->sku,
                            'title' => Str::limit($title, 18, '..'),
                            'url_slug' => $value->url_slug,
                            'averageRating' => number_format($value->averageRating, 1, '.', ''),
                            'inquiry_only' => $value->inquiry_only,
                            'vendor_name' => $value->vendor ? $value->vendor->name : '',
                            'vendor' => $value->vendor,
                            'price' => Session::get('currencySymbol') . ' ' . (decimal_format(@$value->variant->first()->price??0 * $multiply,',')),
                            'compare_price' =>@$value->variant->first()->compare_at_price * $multiply,
                            'compare_price_numeric' =>@$value->variant->first()->compare_at_price * $multiply,
                            'price_numeric' =>@$value->variant->first()->price * $multiply,
                            'category' => (@$value->category->categoryDetail->translation) ? @$value->category->categoryDetail->translation->first()->name : @$value->category->categoryDetail->slug
                        );
                        
                    }
                }
            
            return $productArray;
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
       
    }

     public function longTermServiceProducts($long_term_vendors, $langId, $currency = '', $where = '', $type,$p_dim ='260/100',$requestFrom='web' )
    {
        $venderIds = $long_term_vendors->where('status', 1)
        ->whereHas('long_term_products')
        ->inRandomOrder()
        ->limit(10)->get()->pluck('id');
        $products = Product::byLongTermProductCategoryServiceType($type)->byProductLongTerm()->with([
            'vendor','LongTermProducts.product',
            'media' => function ($q) {
                $q->groupBy('product_id');
            }, 'media.image',
            'translation' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
            },
            'variant' => function ($q) use ($langId) {
                $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                $q->groupBy('product_id');
            },
        ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating', 'inquiry_only','is_long_term_service')
        ->whereHas('LongTermProducts.product', function($q){$q->where('is_live',1); });
       
        if ($where !== '') {
            $products = $products->where($where, 1);
        }
     
       //$venderIds = ['8'];
        $products = $products->whereHas('vendor', function($q) use ($type,$venderIds){
                    $q->where('status',1);
                    $q->whereIn('id',$venderIds);
                    $q->where($type, 1);
                })->take(10)->inRandomOrder()->get();
     
       
        $return = [];
        if (!empty($products)) {
            // return response from to app
            if($requestFrom == 'app'){
                foreach ($products as $key => $value) {
                    foreach ($value->variant as $k => $v) {
                        $value->variant[$k]->multiplier = $currency ? $currency->doller_compare : 1;
                    }
                }
                return $products;
            }
            $additionalPreference = getAdditionalPreference(['is_token_currency_enable','token_currency']);
            foreach ($products as $key => $value) {
                $multiply = Session::get('currencyMultiplier') ?? 1;
                $title = $value->translation->first() ? $value->translation->first()->title : $value->sku;
                $image_url = $value->media->first() ? $value->media->first()->image->path['proxy_url'] . $p_dim . $value->media->first()->image->path['image_path'] : $this->loadDefaultImage();
                $return[] = array(
                    'tag_title' => $title??'0',
                    'image_url' => $image_url,
                    'sku' => $value->sku,
                    'title' => Str::limit($title, 18, '..'),
                    'url_slug' => $value->url_slug,
                    'averageRating' => number_format($value->averageRating, 1, '.', ''),
                    'inquiry_only' => $value->inquiry_only,
                    'vendor_name' => $value->vendor ? $value->vendor->name : '',
                    'vendor' => $value->vendor,
                    'price' => @$additionalPreference['is_token_currency_enable'] ? "<i class='fa fa-money' aria-hidden='true'></i> ".getInToken(@$value->variant->first()->price * $multiply) : Session::get('currencySymbol') . ' ' . (decimal_format(@$value->variant->first()->price * $multiply)),
                    'category' => ''
                );
            }
        }
       return $return;
        
    }

    public function getProductsId($type='')
    {
        $product_ids = [];
        if (checkTableExists('home_products')) {
            $single_category_products = HomeProduct::whereSlug($type)->first();
            if (@$single_category_products) {
                if(@$single_category_products){
                    if($type == 'single_category_products'){
                        $product_ids = ProductCategory::select('product_id')->where('category_id', $single_category_products->category_id)->get();

                    } elseif($type == 'selected_products'){
                        $product_ids = json_decode($single_category_products->products);
                    } elseif($type == 'popular_products'){
                        $most_sold = OrderVendorProduct::selectRaw('id, product_id, count(product_id) as total')->whereHas('statusDelievered')->groupBy('product_id')->orderBy('total', 'DESC')->take(5)->get()->pluck('product_id');
                        // dd($most_sold);
                        $most_viewed = ProductRecentlyViewed::selectRaw('id, product_id, count(product_id) as total')->groupBy('product_id')->orderBy('total', 'DESC')->take(5)->get()->pluck('product_id');

                        $product_ids = $most_sold->merge($most_viewed);
                    } elseif($type == 'top_rated_products'){
                        $product_ids = OrderProductRating::selectRaw('id, product_id, count(product_id) as total')->groupBy('product_id')->orderBy('total', 'DESC')->take(5)->get()->pluck('product_id');
                    } elseif($type == 'recent_viewed'){
                        $product_ids = $this->getRecentProductIds();
                    }
                    
                }
            }
        }
        
        return $product_ids;
    }

    public function vendorProducts($venderIds, $langId, $currency = 'USD', $where = '', $type,$Products_title, $p_dim)
    {

     
        //$additionalPreference = getAdditionalPreference(['is_token_currency_enable']);
        // $products = $products->whereHas('vendor', function($q) use ($type,$venderIds){
        //             $q->where('status',1);
        //             $q->whereIn('id',$venderIds);
        //             $q->where($type, 1);
        //         });
        //         if ($where == 'is_featured') {
        //                  $products = $products->take(20);  
        //             }else{
        //                 $products = $products->take(10);  
        //             }
        //         $products = $products->inRandomOrder()->get();
        $vendorWhereIN = ' ';
        $completeWhere = ' ';
        if(!empty($venderIds)){
            $venid = implode(',',$venderIds);
            $vendorWhereIN = ' AND `vendors`.`id` IN ('.$venid.')';

        }
        if($where!=='all'){
            
                if($where =='single_category_products' || $where == 'selected_products' || $where == 'popular_products' || $where == 'top_rated_products' ||  $where == 'recent_viewed'){
                    $single_category_product_ids = $this->getProductsId($where);
                    if(count($single_category_product_ids) > 0){
                        $single_category_product_ids = @implode(',',$single_category_product_ids);
                        $completeWhere = ' AND  `products`.`id` IN  ('.$single_category_product_ids.')';
            
                    }    
                } else {
                    $completeWhere = ' AND `products`.'.$where.' = 1';
                    //die($where);
                }
        }

        $raw_query = "SELECT 
            `products`.`id`, 
            `products`.`sku`, 
            `products`.`url_slug`, 
            `products`.`weight_unit`, 
            `products`.`weight`, 
            `products`.`vendor_id`, 
            `products`.`has_variant`, 
            `products`.`has_inventory`, 
            `products`.`sell_when_out_of_stock`, 
            `products`.`requires_shipping`, 
            `products`.`Requires_last_mile`, 
            `products`.`inquiry_only`, 
            `products`.`updated_at`, 
            `products`.`is_featured`,
            `products`.`is_new`,  
            `products`.`category_id`,  
            -- `products`.`inwishlist` as `is_inwishlist_btn`,
            `categories`.`id` as `category_id` ,
            `categories`.`type_id`,
            `product_images`.`media_id`,
            `vendor_media`.`path`,
            `product_translation`.`title`,
            `product_translation`.`meta_title`,
            `product_translation`.`meta_keyword`,
            `product_translation`.`meta_description`,
            `product_translation`.`language_id`,
            `product_variant`.`compare_at_price` as `compare_price_numeric`,
            `product_variant`.`price` as `price_numeric`,
            `category_translation`.`name` as `category_name` ,
            `category_translation`.`meta_title` as `category_meta_title` ,
            `category_translation`.`meta_keywords` as `category_meta_keyword` ,
            `category_translation`.`meta_description` as `category_meta_description`,
            CAST((`products`.`averageRating`) AS DECIMAL(2,1)) AS averageRating,
            CASE 
                when `product_variant`.`compare_at_price` > 0 then CAST((`product_variant`.`compare_at_price` - `product_variant`.`price`)/`product_variant`.`compare_at_price`*100 as decimal(12,2))
                else 0
            end as discount_percentage,
            `vendors`.`name` as `vendor_name`,
            `vendors`.`slug` as `vendor_slug`,

            IFNULL(`products`.`is_long_term_service`, 0) AS `is_long_term_service`
            FROM 
                `products` LEFT JOIN   `categories` as `categories` ON `products`.`category_id` = `categories`.`id`  AND `categories`.`type_id` != 7
                 LEFT JOIN   `product_images` as `product_images` ON `product_images`.`product_id` = `products`.`id` 
                 LEFT JOIN   `vendors` as `vendors` ON `vendors`.`id` = `products`.`vendor_id` AND `vendors`.`status` = 1 $vendorWhereIN
                 LEFT JOIN   `vendor_media` as `vendor_media` ON `vendor_media`.`id` = `product_images`.`media_id`
                 LEFT JOIN   `product_translations` as `product_translation` ON `product_translation`.`product_id` = `products`.`id`
                 LEFT JOIN   `product_variants` as `product_variant` ON `product_variant`.`product_id` = `products`.`id`
                 LEFT JOIN   `category_translations` as `category_translation` ON `category_translation`.`category_id` = `products`.`category_id`
                 
            WHERE 
                `products`.`deleted_at` IS NULL 
                    AND `vendors`.`status` = 1 
                    AND `products`.`is_live` = 1

                    $completeWhere
                                
                    $vendorWhereIN 
                
                    GROUP BY `products`.`id`

                    ORDER BY 
                        RAND()
            
                    LIMIT 
                        10";
                       // echo '<pre>';
                       // print_r($raw_query); die;
       $products = DB::select( DB::raw($raw_query));

       $returnArray = $products;
       return $returnArray;
    }
    
   
}
