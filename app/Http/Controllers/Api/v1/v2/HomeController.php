<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\v1\BaseController;
use App\Models\{Category, Client, ClientPreference,Vendor, VendorCategory, Product, ClientCurrency, UserVendor};
use DateTime;
use DateTimeZone;

/**
 * HomeController
 */
class HomeController extends BaseController{
    use ApiResponser;

    private $curLang = 0;
    private $field_status = 2;    
    /**
     * vendorProductsV2
     *
     * @param  mixed $venderIds
     * @param  mixed $langId
     * @param  mixed $currency
     * @param  mixed $where
     * @param  mixed $type
     * @return void
     */
    // Get vendor products for a list of vendor IDs
    public function vendorProductsV2($venderIds, $langId, $currency = '', $where = '', $type)
    {
        $products = Product::byProductCategoryServiceType($type)->with([
            'category.categoryDetail.translation' => function ($q) use ($langId) {
                $q->where('category_translations.language_id', $langId);
            },
            'vendor' => function ($q) use ($type) {
                $q->where($type, 1);
            },
            'media' => function ($q) {
                $q->groupBy('product_id');
            }, 'media.image',
            'translation' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
            },
            'variant' => function ($q) use ($langId) {
                $q->select('sku', 'product_id', 'quantity', 'price','markup_price', 'barcode');
                $q->groupBy('product_id');
            },
        ])
            ->whereHas('category.categoryDetail', function ($q) {
                $q->whereNull('categories.deleted_at');
            })
            ->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating', 'inquiry_only');
        if ($where !== '') {
            $products = $products->where($where, 1);
        }
        $pndCategories = Category::where('type_id', 7)->pluck('id');
        if (is_array($venderIds)) {
            $products = $products->whereIn('vendor_id', $venderIds);
        }
        if ($pndCategories) {
            $products = $products->whereNotIn('category_id', $pndCategories);
        }
        $products = $products->whereNotNull('category_id')->where('is_live', 1)->take(10)->get();
        $new_products = array();
    
        if (!empty($products)) {
            foreach ($products as $key => $value) {
                $value->variant->map(function($da) use($currency) {
                    $da->multiplier = $currency ? $currency->doller_compare : 1;
                    return $da;
                });
            
            }
        }
    
        return $products;
    }

    /** return dashboard content like categories, vendors, brands, products     */    
    /**
     * homepageV2
     *
     * @param  mixed $request
     * @return void
     */
    public function homepageV2(Request $request)
    {
        try {
            $vends = [];
            $venderIds = [];
            $homeData = [];
            $user = Auth::user();
            $langId = $user->language;
            $currency_id = $user->currency;
            $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
            $preferences = ClientPreference::select('distance_to_time_multiplier', 'distance_unit_for_time', 'is_hyperlocal', 'Default_location_name', 'Default_latitude', 'Default_longitude', 'is_service_area_for_banners')->first();
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $paginate = $request->has('limit') ? $request->limit : 12;
            //filter
            $venderFilterClose   = $request->has('close_vendor') && $request->close_vendor ? $request->close_vendor : null;
            $venderFilterOpen   = $request->has('open_vendor') && $request->open_vendor ? $request->open_vendor : null;
            $venderFilterbest   = $request->has('best_vendor') && $request->best_vendor ? $request->best_vendor : null;
            $venderFilternear   = $request->has('near_me') && $request->near_me ? $request->near_me : null;

            $type = $request->has('type') ? $request->type : 'delivery';

            if (empty($type))
            $type = 'delivery';


            $categoryTypes = getServiceTypesCategory($type);
            
        
            $vendorData = Vendor::whereHas('getAllCategory.category',function($q)use ($categoryTypes){
                $q->whereIn('type_id',$categoryTypes);
            })->select('id', 'slug', 'name', 'desc', 'banner', 'order_pre_time', 'order_min_amount', 'vendor_templete_id', 'show_slot', 'latitude', 'longitude','id as is_vendor_closed' ,'closed_store_order_scheduled')->withAvg('product', 'averageRating','closed_store_order_scheduled')->where($type, 1);


        

            if (($preferences) && ($preferences->is_hyperlocal == 1)) {
                $latitude = ($latitude) ? $latitude : $preferences->Default_latitude;
                $longitude = ($longitude) ? $longitude : $preferences->Default_longitude;
                $distance_unit = (!empty($preferences->distance_unit_for_time)) ? $preferences->distance_unit_for_time : 'kilometer';
                //3961 for miles and 6371 for kilometers
                $calc_value = ($distance_unit == 'mile') ? 3961 : 6371;
                $vendorData = $vendorData->select('*', DB::raw(' ( ' .$calc_value. ' * acos( cos( radians(' . $latitude . ') ) *
                        cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $longitude . ') ) +
                        sin( radians(' . $latitude . ') ) *
                        sin( radians( latitude ) ) ) )  AS vendorToUserDistance'))->withAvg('product', 'averageRating');
                $ses_vendors = $this->getServiceAreaVendors($latitude, $longitude, $type);
                $vendorData = $vendorData->whereIn('id', $ses_vendors);
                //if($venderFilternear && ($venderFilternear == 1) ){
                    //->orderBy('vendorToUserDistance', 'ASC')
                    $vendorData =   $vendorData->orderBy('vendorToUserDistance', 'ASC');
                //}
            }
        
            //filter on ratings
            if($venderFilterbest && ($venderFilterbest == 1) ){
                $vendorData =   $vendorData->orderBy('product_avg_average_rating', 'desc');
            }
            $allVendorData = clone $vendorData;
            $client = Client::first();
            $mytime = Carbon::now()->setTimezone($client->timezone);
            $current_time = $mytime->toTimeString();
            $sortBy = "sortBy";
            if($venderFilterClose && ($venderFilterClose == 1) ){
                $sortBy = "sortByDesc";
            }
            if($venderFilterOpen && ($venderFilterOpen == 1) ){
                $sortBy =  "sortBy";
            }
            $vendorData = $vendorData->with('slot', 'slotDate')->where('status', 1)->get()->$sortBy('is_vendor_closed')->take(5);
            
            $venderIds  = $allVendorData->where('status', 1)->pluck('id');
        
            
        
            $timezone = $user->timezone ?? 'Asia/Kolkata';
            $start_date = new DateTime("now", new  DateTimeZone($timezone) );
            $start_date =  $start_date->format('Y-m-d');
            $end_date = Date('Y-m-d', strtotime('+13 days'));
            $vel = VendorCategory::getQuery();
        
            foreach ($vendorData as $vendor) {

                $slotsDate = 0;
                $vendor->date_with_slots = [];
                if($vendor->closed_store_order_scheduled == 1){
                    $slotsDate = findSlot('',$vendor->id,'');
                    $vendor->delaySlot = $slotsDate;
                    $vendor->closed_store_order_scheduled = (($slotsDate)?$vendor->closed_store_order_scheduled:0);

                    if(!empty($slotsDate)){
                        $period = CarbonPeriod::create($start_date, $end_date);
                        $slotWithDate = [];
                        foreach($period as $key => $date){
                            $slotDate = trim(date('Y-m-d', strtotime($date)));
                            $slots = showSlot($slotDate,$vendor->id,'delivery');
                            if(!empty($slots)){
                                $slotData['date']  =  $slotDate;
                                $slotData['slots'] = $slots;
                                $slotWithDate[] = $slotData;
                            }
                        }
                        $vendor->date_with_slots = $slotWithDate;
                    }
                }else{
                    $vendor->delaySlot = 0;
                    $vendor->closed_store_order_scheduled = 0;
                }


                $vendor->is_show_category = ($vendor->vendor_templete_id == 2 || $vendor->vendor_templete_id == 4) ? 1 : 0;

                // Returns a comma - separated list of categories for a given vendor.
                $vendorCategories = VendorCategory::with('category.translation_one')->where('vendor_id', $vendor->id)->where('status', 1)->get();
                $categoriesList = $vendorCategories;
                foreach ($vendorCategories as $key => $category) {
                    if ($category->category) {
                        $cat_name = isset($category->category->translation_one) ? $category->category->translation_one->name : $category->category->slug;
                        $categoriesList = $categoriesList . $cat_name ?? '';
                        if ($key !=  $vendorCategories->count() - 1) {
                            $categoriesList = $categoriesList . ', ';
                        }
                    }
                }
                $vendor->categoriesList = $categoriesList;

                $vends[] = $vendor->id;
                if (($preferences) && ($preferences->is_hyperlocal == 1) && ($latitude) && ($longitude)) {
                    $vendor = $this->getVendorDistanceWithTime($latitude, $longitude, $vendor, $preferences, $type);
                }

            }

            if (($preferences) && ($preferences->is_hyperlocal == 1) && ($latitude) && ($longitude)) {
                $vendorData = $vendorData->sortBy('lineOfSightDistance')->values()->all();
            }

            $on_sale_product_details = $this->vendorProductsV2($vends, $langId, $clientCurrency, '', $type);
            $new_product_details     = $this->vendorProductsV2($vends, $langId, $clientCurrency, 'is_new', $type);
            $feature_product_details = $this->vendorProductsV2($vends, $langId, $clientCurrency, 'is_featured', $type);
        
            $isVendorArea = 0;
            
            $categories = $this->categoryNav($langId,  $venderIds,$type);
            $homeData['vendors'] = $vendorData;
            $homeData['categories'] = $categories;
            $homeData['reqData'] = $request->all();
            //$homeData['mobile_banners'] = $mobile_banners;
            $homeData['on_sale_products'] = $on_sale_product_details;
            $homeData['new_products'] = $new_product_details;
            $homeData['featured_products'] = $feature_product_details;
            $user_vendor_count = UserVendor::where('user_id', $user->id)->count();
            $homeData['is_admin'] = $user_vendor_count > 0 ? 1 : 0;
            return $this->successResponse($homeData);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
