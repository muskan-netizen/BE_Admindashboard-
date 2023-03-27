<?php
namespace App\Http\Traits;
use App\Models\{ProductRecentlyViewed,WebStylingOption,Product,Category,HomeProduct,ProductCategory,OrderVendorProduct,OrderProductRating, VendorCategory, Vendor, SubscriptionInvoicesVendor};
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
            ProductRecentlyViewed::updateOrCreate(
                    $update_by
                ,$RecentlyViewed);
            
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
            ProductRecentlyViewed::where('token_id', session()->get('_token'))->update(['user_id' => $user_id, 'token_id' => '']);
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
                if(sizeof($recent_ids) <= 0){
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
                $products = $products->whereIn('id', $recent_ids);

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

     public function longTermServiceProducts($long_term_vendors, $additionalPreference, $langId, $currency = '', $where = '', $type,$p_dim ='260/100',$requestFrom='web' )
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

    public function vendorProducts($venderIds, $langId, $currency = 'USD', $where = '', $type, $Products_title, $p_dim)
    {

     
        
        $vendorWhereIN = ' ';
        $completeWhere = ' ';
        $whereProductType = ' ';
        if(!empty($venderIds)){
            $venid = implode(',',$venderIds);
            $vendorWhereIN = ' AND `vendors`.`id` IN ('.$venid.')';

        }
        if($where!=='all'){
            
                if($where =='single_category_products' || $where == 'selected_products' || $where == 'popular_products' || $where == 'top_rated_products' ||  $where == 'recent_viewed'){
                    $single_category_product_ids = $this->getProductsId($where);
                    if(!empty($single_category_product_ids)){
                        $single_category_product_ids = @implode(',',$single_category_product_ids);
                        if($single_category_product_ids){
                            $completeWhere = ' AND  `products`.`id` IN  ('.$single_category_product_ids.')';
                        }
                       
            
                    }    
                } else {
                    $completeWhere = ' AND `products`.'.$where.' = 1';
                }
        }
        //Check product of selected category type
        $whereProductType = '';
        $categoryTypesArray = @getServiceTypesCategory($type);
        if(!empty($categoryTypesArray)){
            $categoryTypesArray = implode(',',$categoryTypesArray);
            $whereProductType = ' and `categories`.`type_id`  IN ('.$categoryTypesArray.')';
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
            `vendors`.`id` as `vendor_id`,
            `vendors`.`slug` as `vendor_slug`,
            `product_attribute`.`key_name` as `attribute_key_name`,
            `product_attribute`.`key_value` as `attribute_key_value`,

            IFNULL(`products`.`is_long_term_service`, 0) AS `is_long_term_service`
            FROM 
                `products` LEFT JOIN   `categories` as `categories` ON `products`.`category_id` = `categories`.`id`  AND `categories`.`type_id` != 7
                 LEFT JOIN   `product_images` as `product_images` ON `product_images`.`product_id` = `products`.`id` 
                 LEFT JOIN   `vendors` as `vendors` ON `vendors`.`id` = `products`.`vendor_id` AND `vendors`.`status` = 1 
                 LEFT JOIN   `vendor_media` as `vendor_media` ON `vendor_media`.`id` = `product_images`.`media_id`
                 LEFT JOIN   `product_translations` as `product_translation` ON `product_translation`.`product_id` = `products`.`id`
                 LEFT JOIN   `product_variants` as `product_variant` ON `product_variant`.`product_id` = `products`.`id`
                 LEFT JOIN   `category_translations` as `category_translation` ON `category_translation`.`category_id` = `products`.`category_id`
                 LEFT JOIN   `product_attributes` as `product_attribute` ON `product_attribute`.`product_id` = `products`.`id` AND `product_attribute`.`key_name` = 'Location'
                 
            WHERE 
                `products`.`deleted_at` IS NULL 
                    AND `vendors`.`status` = 1 
                    AND `products`.`is_live` = 1

                    $completeWhere
                                
                    $vendorWhereIN 

                    $whereProductType
                
                    GROUP BY `products`.`id`

                    ORDER BY 
                        RAND()
            
                    LIMIT 
                        10";
     
       $products = DB::select( DB::raw($raw_query));

       $returnArray = $products;
       return $returnArray;
    }
    
    
    public function getVendorForHomePage($preferences, $vendor_title, $timezone, $is_admin_vendor_rating = '', $type, $language_id, $latitude , $longitude, $vendor_ids = [])
    {
        $mytime = Carbon::now()->setTimezone($timezone);
        $current_time = $mytime->toTimeString();

        if( (empty($latitude)) && (empty($longitude)) ){
            $latitude = (!empty($preferences->Default_latitude)) ? floatval($preferences->Default_latitude) : 0;
            $longitude = (!empty($preferences->Default_latitude)) ? floatval($preferences->Default_longitude) : 0;
        }

        //------ ids of subscription vendors in case of vendor_title is "trending_vendors"
        $trending_vendors = [];
        if($vendor_title == "trending_vendors"){
            $now = Carbon::now()->toDateTimeString();
            $trending_vendors = SubscriptionInvoicesVendor::whereHas('features', function ($query) {
                $query->where(['subscription_invoice_features_vendor.feature_id' => 1]);
            })
            ->select('id', 'vendor_id', 'subscription_id')
            ->where('end_date', '>=', $now)
            ->pluck('vendor_id')->toArray();
        }
        
        $selectQuery = "`vendors`.`id`, 
            `vendors`.`name`, 
            `vendors`.`banner`, 
            `vendors`.`address`, 
            `vendors`.`order_pre_time`, 
            `vendors`.`order_min_amount`, 
            `vendors`.`logo`, 
            `vendors`.`slug`, 
            `vendors`.`latitude`, 
            `vendors`.`longitude`, 
            `vendors`.`show_slot`,
            `vendors`.`admin_rating`,
            `vendors`.`rating`,
            GROUP_CONCAT(DISTINCT `category_translations`.`name` SEPARATOR ', ') AS `categoriesList`,
            (SELECT count(`order_vendors`.`id`) FROM `order_vendors` WHERE `order_vendors`.`vendor_id` = `vendors`.`id`) AS `selling_count`,
            (SELECT CONCAT(`vendor_slot_dates`.`start_time`, '##', `vendor_slot_dates`.`end_time`) FROM `vendor_slot_dates` WHERE `vendor_slot_dates`.`vendor_id` = `vendors`.`id` LIMIT 0,1) AS `slotdate_start_end_time`,
            (SELECT CONCAT(`vendor_slots`.`start_time`, '##', `vendor_slots`.`end_time`) FROM `vendor_slots` WHERE `vendor_slots`.`vendor_id` = `vendors`.`id` AND `vendor_slots`.`start_time` < CAST('".$current_time."' AS time) AND `vendor_slots`.`end_time` > CAST('".$current_time."' AS time)  LIMIT 0,1) AS `slot_start_end_time`,
            6371 * acos(cos(radians(" . $latitude . ")) 
                                        * cos(radians(`vendors`.`latitude`)) 
                                        * cos(radians(`vendors`.`longitude`) - radians(" . $longitude . ")) 
                                        + sin(radians(" .$latitude. ")) 
                                        * sin(radians(`vendors`.`latitude`))) AS `lineOfSightDistance`
            ";
    
            $joinQuery  = " LEFT JOIN `vendor_categories` ON `vendor_categories`.`vendor_id`= `vendors`.`id` ";
            $joinQuery .= " LEFT JOIN `categories` ON `categories`.`id`= `vendor_categories`.`category_id` ";
            $joinQuery .= " LEFT JOIN `category_translations` ON `category_translations`.`category_id`= `categories`.`id` AND `category_translations`.`language_id` = $language_id ";
            $whereQuery  = " where `vendors`.`status` = 1 AND `vendor_categories`.`status` = 1";

        $whereInQuery = '';
        if(count($trending_vendors) > 0)
        {
            $whereInQuery = " AND `vendors`.`id` IN (".implode(',',$trending_vendors).") ";
        }else{
            $whereInQuery = " AND `vendors`.`id` IN (".implode(',', $vendor_ids).") ";
        }
        $mainQuery = "SELECT $selectQuery FROM `vendors` $joinQuery $whereQuery $whereInQuery";
        

        
        
        $mainQuery .= " GROUP BY `vendors`.`id` ";

        //------based on hyper location------------
        if (($preferences->is_hyperlocal == 1) && ($latitude) && ($longitude)) {

            $distance_unit = (!empty($preferences->distance_unit_for_time)) ? $preferences->distance_unit_for_time : 'kilometer';
            $unit_abbreviation = ($distance_unit == 'mile') ? 'miles' : 'km';
            $distance_to_time_multiplier = ($preferences->distance_to_time_multiplier > 0) ? $preferences->distance_to_time_multiplier : 2;

            $mainQuery .= " HAVING (SELECT COUNT(`service_areas`.`id`) FROM `service_areas` WHERE `service_areas`.`vendor_id` = `vendors`.`id` AND ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT($latitude $longitude)'))) > 0 ";
        
        }

        if ($vendor_title == "best_sellers") {
            $mainQuery.= " ORDER BY `selling_count` DESC";
        }
        if (($preferences->is_hyperlocal == 1) && ($latitude) && ($longitude) && $vendor_title == "trending_vendors") {
            $mainQuery.= " ORDER BY `lineOfSightDistance` DESC";
        }else{
            //-------------if admin rating is on otherwise random---------------
            if($is_admin_vendor_rating == 1){
                $mainQuery.= " ORDER BY admin_rating DESC";
            }
        }
        
        $mainQuery .= " LIMIT 10";

        
        $vendors = DB::select( DB::raw($mainQuery));

        $vendor_ids = [];

        foreach ($vendors as $key => $value) {
            $vendor_ids[] = $value->id;
            $value->img_path = get_file_path($value->logo,'FILL_URL','200','200');
            // get or update rating
            $value->vendorRating = ($value->rating == null) ? $this->getVendorRating($value->id) : number_format($value->rating, 1);

            if(($preferences) && ($preferences->is_hyperlocal == 1)) 
            {
                if($type == 'delivery')
                {
                    $pretime =  number_format(floatval($value->order_pre_time), 0, '.', '') + number_format(($value->lineOfSightDistance * $distance_to_time_multiplier), 0, '.', '');
                }else{
                    $pretime =  number_format(floatval($value->order_pre_time), 0, '.', '') + 0;
                }
                $pretime = $this->getEvenOddTime($pretime);
                if($pretime >= 60){
                    $value->timeofLineOfSightDistance =  '~ '.$this->vendorTime($pretime) .' '. __('hour');
                }else{
                    $value->timeofLineOfSightDistance = $pretime . '-' . (intval($pretime) + 5).' '. __('min');
                }
                $value->lineOfSightDistance = $value->lineOfSightDistance.' '.$unit_abbreviation;
            }
            
            $value->type_title = $value->categoriesList;

            $value->is_vendor_closed = 0;
            if($value->show_slot == 0){
                if(empty($value->slotdate_start_end_time) && empty($value->slot_start_end_time)){
                    $value->is_vendor_closed = 1;
                }else{
                    $value->is_vendor_closed = 0;
                    if(!empty($value->slotdate_start_end_time)){
                        $slotdate_start_end_time = explode('##', $value->slotdate_start_end_time);
                        if($slotdate_start_end_time[0]!='' && $slotdate_start_end_time[1]!=''){
                            $value->opening_time  = date('g:i A',strtotime($slotdate_start_end_time[0]));
                            $value->closing_time = date('g:i A',strtotime($slotdate_start_end_time[1]));
                        }

                    }elseif(!empty($value->slot_start_end_time)){
                        $slot_start_end_time = explode('##', $value->slot_start_end_time);
                        if($slot_start_end_time[0]!='' && $slot_start_end_time[1]!=''){
                            $value->opening_time  = date('g:i A',strtotime($slot_start_end_time[0]));
                            $value->closing_time = date('g:i A',strtotime($slot_start_end_time[1]));
                        }
                    }
                }
            }
        }
        if (($latitude) && ($longitude)) {
            Session::put('vendors', $vendor_ids);
        }
        //pr($vendors);
        
        return $vendors;
    }

    public function getBrandsForHomePage($language_id, $field_status)
    {
        $redirect_url = route('brandDetail', "brands_id");
        $mainQuery = "SELECT `br`.`id`,
         `br`.`image`, 
         `br`.`title`, 
         REPLACE('".$redirect_url."', 'brands_id', `br`.`id`) AS `redirect_url`,
         (CASE WHEN `bt`.`title` IS NULL THEN `br`.`title` ELSE `bt`.`title` END) AS `translation_title`
         FROM `brands` AS `br` 

        LEFT JOIN `brand_translations` AS `bt` ON `bt`.`brand_id` = `br`.`id` AND `bt`.`language_id` = $language_id 

        WHERE `br`.`status` !=$field_status
        GROUP BY `br`.`id`";
     
        $brands = DB::select( DB::raw($mainQuery));
        return $brands;
    }


    public function getBannersForHomePage($client_preferences, $banner_type, $latitude, $longitude)
    {
        $carbon_now = Carbon::now();

        if($banner_type == 'banners'){
            $banner_table                   = 'banners';
            $banner_service_areas_table     = 'banner_service_areas';
            $service_area_for_banners_table = 'service_area_for_banners';
            $type                           = 1;
        }else{
            $banner_table                   = 'mobile_banners';
            $banner_service_areas_table     = 'mobile_banner_service_areas';
            $service_area_for_banners_table = 'service_area_for_banners';
            $type                           = 2;
        }

        $mainQuery = "SELECT 
            `ba`.`image`,
            `ba`.`link`, 
            `ba`.`link_url`, 
            `ct`.`slug` AS `category_slug`, 
            `vn`.`slug` AS `vendor_slug`
            FROM $banner_table AS `ba`";

        $joinQuery = "LEFT JOIN `categories` AS `ct` ON `ct`.`id` = `ba`.`redirect_category_id` AND `ct`.`deleted_at` IS NULL ";
        $joinQuery.= "LEFT JOIN `vendors` AS `vn` ON `vn`.`id` = `ba`.`redirect_vendor_id` ";

        $mainQuery.= " $joinQuery WHERE `ba`.`status` =1 AND `ba`.`validity_on` = 1 AND (`ba`.`start_date_time` is null or (date(`ba`.`start_date_time`) <= '".$carbon_now."' and date(`ba`.`end_date_time`) >= '".$carbon_now."'))  ";

        if(isset($client_preferences->is_service_area_for_banners) && ($client_preferences->is_service_area_for_banners == 1) && ($client_preferences->is_hyperlocal == 1) && (!empty($latitude) && !empty($longitude))){
            $mainQuery .= " HAVING (SELECT `id` FROM `$banner_service_areas_table` AS `bsa` where `ba`.`id` = `bsa`.`banner_id` HAVING (select `id` from `$service_area_for_banners_table` AS `safb` WHERE `bsa`.`service_area_id` = `safb`.`id` AND ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT($latitude $longitude)')) and `type` = $type) > 0) > 0 ";
        }
        
        $mainQuery.= " ORDER BY `ba`.`sorting` ASC";
        
     
        $banners = DB::select( DB::raw($mainQuery));
        return $banners;
    }

    public function getRandomVendorIdsForHomePage($preferences, $type, $is_admin_vendor_rating = 0, $latitude, $longitude)
    {
        $vendors = Vendor::select('id')->where('status', 1)->where($type, 1);
        if (($preferences->is_hyperlocal == 1) && ($latitude) && ($longitude)) {

            $vendors = $vendors->havingRaw(" HAVING (SELECT COUNT(`service_areas`.`id`) FROM `service_areas` WHERE `service_areas`.`vendor_id` = `vendors`.`id` AND ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT($latitude $longitude)'))) > 0 ");
            
        }

        if($is_admin_vendor_rating == 1){
            $vendors = $vendors->orderBy('admin_rating', 'DESC');
        }else{
            $vendors = $vendors->inRandomOrder();
        }
        return $vendors->limit(10)->pluck('id')->toArray();
    }
    
}
