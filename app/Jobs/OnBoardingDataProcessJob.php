<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;
use Carbon\Carbon;
use App\Models\{Client, AddonOption, AddonOptionTranslation, AddonSet, AddonSetTranslation, OrderVendorProduct, Banner, MobileBanner, Brand, BrandCategory, BrandTranslation, Cart, CartAddon, CartCoupon, CartProduct, CartProductPrescription, Category, CategoryHistory, CategoryTranslation, Celebrity, CsvProductImport, CsvVendorImport, LoyaltyCard, Order, OrderProductAddon, OrderProductPrescription, OrderProductRating, OrderProductRatingFile, OrderReturnRequest, OrderReturnRequestFile, OrderTax, OrderVendor, Payment, PaymentOption, Product, ProductAddon, ProductCategory, ProductCelebrity, ProductCrossSell, ProductImage, ProductInquiry, ProductRelated, ProductTranslation, ProductUpSell, ProductVariant, ProductVariantImage, ProductVariantSet, Promocode, PromoCodeDetail, PromocodeRestriction, ServiceArea, SlotDay, SocialMedia, Transaction, User, UserAddress, UserDevice, UserLoyaltyPoint, UserPermissions, UserRefferal, UserVendor, UserWishlist, Variant, VariantCategory, VariantOption, VariantOptionTranslation, VariantTranslation, Vendor, VendorCategory, VendorMedia, VendorOrderStatus, VendorSlot, VendorSlotDate, Wallet, CabBookingLayout, CabBookingLayoutCategory, CabBookingLayoutTranslation, AppStyling, AppStylingOption, Tag, TagTranslation, ProductTag, CartProductCoupon, WebStylingOption};
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Jobs\CreateDummyDbProcessJob;
use App\Http\Traits\OnBoardingProcessManager;

class OnBoardingDataProcessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $onboard_data;

    public $failOnTimeout = true;

    public $timeout = 120000;


    public function __construct($onboard_data)
    {
        $this->onboard_data = $onboard_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        set_time_limit(800); //300 seconds = 5 minutes
        Log::info('startOnboarding OnBoardingProcessJob ');
        $updateAiData = $this->updateAiData($this->onboard_data);
        return false;
    }

    public function updateAiData($dummy_onboard_data)
    {
        Log::info('dummy_onboard_data start step two - with or without ai data');
        $fdata = $dummy_onboard_data->fulldata != "{}" ? json_decode(@$dummy_onboard_data->fulldata, true) : '';

        if (
            $dummy_onboard_data->fulldata != NULL && is_array($fdata)
            &&
            (count(@$fdata['categories']) > 0 && count(@$fdata['stores']) > 0)
            &&
            (!in_array(@$dummy_onboard_data->fetch_data, [1, 2]))
        ) {
            DB::connection('god')->table('dummy_onboard_data')->where('id', $dummy_onboard_data->id)->update(['status' => 3]);
        } else if ((in_array(@$dummy_onboard_data->fetch_data, [1, 2]))) {
            DB::connection('god')->table('dummy_onboard_data')->where('id', $dummy_onboard_data->id)->update(['status' => 3]);
        } else if ((!in_array(@$dummy_onboard_data->fetch_data, [1, 2])) && @$dummy_onboard_data->status == 2) {
            DB::connection('god')->table('dummy_onboard_data')->where('id', $dummy_onboard_data->id)->update(['status' => 3]);
        } else {
            return 0;
        }

        $client = Client::on('god')->where('id', $dummy_onboard_data->client_id)->first(['name', 'email', 'password', 'phone_number', 'database_path', 'database_name', 'database_username', 'database_password', 'business_type', 'logo', 'company_name', 'company_address', 'custom_domain', 'status', 'code', 'country_id', 'sub_domain'])->toarray();

        $schemaName = 'royo_' . $client['database_name'] ?: config("database.connections.mysql.database");
        $default = [
            'driver' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'database' => $schemaName,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
            'engine' => null
        ];

        Config::set("database.connections.$schemaName", $default);
        config(["database.connections.mysql.database" => $schemaName]);

        $dinein = config('constants.VendorTypesAccordingBusiness.' . $client['business_type'] . '.dinein');
        $takeaway = config('constants.VendorTypesAccordingBusiness.' . $client['business_type'] . '.takeaway');
        $delivery = config('constants.VendorTypesAccordingBusiness.' . $client['business_type'] . '.delivery');
        $rental = config('constants.VendorTypesAccordingBusiness.' . $client['business_type'] . '.rental');
        $pick_drop = config('constants.VendorTypesAccordingBusiness.' . $client['business_type'] . '.pick_drop');
        $on_demand = config('constants.VendorTypesAccordingBusiness.' . $client['business_type'] . '.on_demand');
        $laundry = config('constants.VendorTypesAccordingBusiness.' . $client['business_type'] . '.laundry');
        $appointment = config('constants.VendorTypesAccordingBusiness.' . $client['business_type'] . '.appointment');
        $p2p = config('constants.VendorTypesAccordingBusiness.' . $client['business_type'] . '.appointment');

        $settingsUpdate = [
            'business_type'         =>  @$dummy_onboard_data->business_type,
            'isolate_single_vendor_order' => @$dummy_onboard_data->vendor_type  == 0 ? 1 : 0,
            'web_color'             =>  @$dummy_onboard_data->web_color ?? '#2E8EFF',
            'primary_color'         =>  @$dummy_onboard_data->primarycolor ?? '#32B5FC', //'#32B5FC',
            'secondary_color'       =>  @$dummy_onboard_data->secondarycolor ?? '#41A2E6', //'#41A2E6'
            'delivery_check'        => $delivery,
            'dinein_check'          => $dinein,
            'takeaway_check'        => $takeaway,
            'rental_check'          => $rental,
            'pick_drop_check'       => $pick_drop,
            'on_demand_check'       => $on_demand,
            'laundry_check'         => $laundry,
            'appointment_check'     => $appointment
        ];

        DB::connection($schemaName)->table('client_preferences')->update($settingsUpdate);

        if (!in_array(@$dummy_onboard_data->fetch_data, [1])) {
            Log::info("fetch_data");
            Log::info("categroy root insert");
            $main_category = [
                'id' => '1',
                'slug' => 'root',
                'type_id' => 1,
                'is_visible' => 0,
                'status' => 1,
                'position' => 1,
                'is_core' => 1,
                'can_add_products' => 1,
                'display_mode' => 1,
                'parent_id' => NULL
            ];
            $main_trans = [
                'id' => 1,
                'name' => 'root',
                'trans-slug' => '',
                'meta_title' => 'root',
                'meta_description' => '',
                'meta_keywords' => '',
                'category_id' => 1,
                'language_id' => $dummy_onboard_data->primary_language ?? 1,
            ];
            $categories = DB::connection($schemaName)->table('categories')->where('id',1)->first();
            if(!$categories){
                DB::connection($schemaName)->table('categories')->insert($main_category);
                DB::connection($schemaName)->table('category_translations')->insert($main_trans);
            }
            // DB::connection($schemaName)->table('categories')->truncate();
            // DB::connection($schemaName)->table('category_translations')->truncate();
            // DB::connection($schemaName)->table('categories')->insert($main_category);
            // DB::connection($schemaName)->table('category_translations')->insert($main_trans);
            Log::info("categroy root insert done");
        }

        $storage_file_path = '';
        /* if ($dummy_onboard_data->logojson != '') {

           $logodata = json_decode(@$dummy_onboard_data->logojson, true);
            if(count($logodata) > 0){
                 $imageurl = $logodata['data'][0]['url'];
                if($imageurl != '') {
                    $pathParts = pathinfo(parse_url($imageurl, PHP_URL_PATH));

                    if (isset($pathParts['extension'])) {
                        $extension = $pathParts['extension'];
                    } else {
                        $extension = 'png'; //echo "File extension not found in the URL.";
                    }
        
                    $filePath = 'clientlogo';
                    $storage_file_path = $filePath . '/' . time() . '.' . $extension;
                    $data = file_get_contents($imageurl); //, false, $context
                    // save file to s3
                    $file = Storage::disk('s3')->put($storage_file_path, $data, 'public');
                }
            }
        }*/

        $clientDataLogo = $storage_file_path ?? 'Clientlogo/656834e183bed.png';
        //$clientDataLogo = ($dummy_onboard_data->logo && $dummy_onboard_data->logo != '')  ? $dummy_onboard_data->logo : 'Clientlogo/612e24163debe.png';

        DB::connection($schemaName)->table('clients')->update(['logo' => 'Clientlogo/656834e183bed.png']);

        if (isset($dummy_onboard_data->bannerimages) && $dummy_onboard_data->bannerimages != '' && $dummy_onboard_data->business_type != 'food' && $dummy_onboard_data->business_type != 'grocery') {
            $bannerimagesdata = json_decode(@$dummy_onboard_data->bannerimages, true);

            $foundImages = 0;
            foreach ($bannerimagesdata as $image) {
                if (OnBoardingProcessManager::isValidImage($image['image_url'])) {
                    $foundImages++;
                    //echo $image['image_url']; echo '<br>';
                    $imageurl = $image['image_url']; //$bannerimagesdata[0]['image_url'];
                    $banner_storage_file_path = '';
                    if ($imageurl != '') {
                        // $pathParts = pathinfo(parse_url($imageurl, PHP_URL_PATH));

                        // if (isset($pathParts['extension'])) {
                        //     $extension = $pathParts['extension'];
                        // } else {
                        //     $extension = 'png';
                        // }
                        // $banner_storage_file_path = 'banner/' . time() . '.' . $extension;
                        // $dataimg = @file_get_contents($imageurl);
                        // $file = Storage::disk('s3')->put($banner_storage_file_path, $dataimg, 'public');    

                        $banner_storage_file_path = $imageurl;
                    }

                    $validity_on = 1;
                    $bannername = 'Banner';
                    $link = 'url';
                    $redirect_category_id = NULL;
                    $redirect_vendor_id = NULL;
                    $link_url = '/';

                    $user_timezone = $dummy_onboard_data->timezone ?? 'UTC';
                    //$banner->start_date_time = DateTime::createFromFormat('Y-m-d H:i', $request->start_date_time, new DateTimeZone('UTC'));//->setTimezone('UTC');
                    $start_datetime = Carbon::now();
                    $end_datetime  = Carbon::now()->addMonths(5);
                    $start_date_time = Carbon::parse($start_datetime, $user_timezone)->setTimezone('UTC')->format('Y-m-d H:i');;
                    $end_date_time = Carbon::parse($end_datetime, $user_timezone)->setTimezone('UTC')->format('Y-m-d H:i');;

                    $banner_position = 1;
                    $banner_pos = DB::connection($schemaName)->table('banners')->where('sorting', \DB::raw("(select max(`sorting`) from banners)"))->first();
                    if ($banner_pos) {
                        $banner_position = $banner_pos->sorting + 1;
                    }

                    $bannerid = DB::connection($schemaName)->table('banners')->insertGetId([
                        'name' => $bannername,
                        'image' => $banner_storage_file_path != '' ? $banner_storage_file_path : 'default/default_images.png',
                        'validity_on' => $validity_on,
                        'sorting' => $banner_position,
                        'status' => 1,
                        'redirect_category_id' => $redirect_category_id,
                        'redirect_vendor_id' => $redirect_vendor_id,
                        'link' => $link,
                        'link_url' => $link_url,
                        'image_mobile' => NULL,
                        'start_date_time' => $start_date_time,
                        'end_date_time' => $end_date_time
                    ]);
                    //Log::info('banner image upload insert done: '.$bannerid);

                    $mobile_banner_position = 1;
                    $mobile_banner_pos = DB::connection($schemaName)->table('mobile_banners')->where('sorting', \DB::raw("(select max(`sorting`) from mobile_banners)"))->first();
                    if ($mobile_banner_pos) {
                        $mobile_banner_position = $mobile_banner_pos->sorting + 1;
                    }

                    $mobile_banner_id = DB::connection($schemaName)->table('mobile_banners')->insertGetId([
                        'name' => $bannername,
                        'image' => $banner_storage_file_path != '' ? $banner_storage_file_path : 'default/default_images.png',
                        'validity_on' => $validity_on,
                        'sorting' => $mobile_banner_position,
                        'status' => 1,
                        'redirect_category_id' => $redirect_category_id,
                        'redirect_vendor_id' => $redirect_vendor_id,
                        'link' => $link,
                        'link_url' => $link_url,
                        'start_date_time' => $start_date_time,
                        'end_date_time' => $end_date_time
                    ]);

                    // If two images are found, exit the loop
                    if ($foundImages == 3) {
                        break;
                    }
                }
            }
        }

        $bannerimages = config('constants.BusinessTypes.' . (@$dummy_onboard_data->business_type ?? "food") . '.bannerimage');
        if (isset($bannerimages) && $bannerimages != '')  //&& $dummy_onboard_data->business_type != 'food'
        {
            foreach ($bannerimages as $image) {
                //if (OnBoardingProcessManager::isValidImage($image['image_url'])) {
                $imageurl = $image['image_url']; //$bannerimagesdata[0]['image_url'];
                $banner_storage_file_path = '';
                if ($imageurl != '') {
                    $banner_storage_file_path = $imageurl;
                }

                $validity_on = 1;
                $bannername = 'Banner';
                $link = 'url';
                $redirect_category_id = NULL;
                $redirect_vendor_id = NULL;
                $link_url = '/';

                $user_timezone = $dummy_onboard_data->timezone ?? 'UTC';
                $start_datetime = Carbon::now();
                $end_datetime  = Carbon::now()->addMonths(5);
                $start_date_time = Carbon::parse($start_datetime, $user_timezone)->setTimezone('UTC')->format('Y-m-d H:i');;
                $end_date_time = Carbon::parse($end_datetime, $user_timezone)->setTimezone('UTC')->format('Y-m-d H:i');;

                $banner_position = 1;
                $banner_pos = DB::connection($schemaName)->table('banners')->where('sorting', \DB::raw("(select max(`sorting`) from banners)"))->first();
                if ($banner_pos) {
                    $banner_position = $banner_pos->sorting + 1;
                }

                $bannerid = DB::connection($schemaName)->table('banners')->insertGetId([
                    'name' => $bannername,
                    'image' => $banner_storage_file_path != '' ? $banner_storage_file_path : 'default/default_images.png',
                    'validity_on' => $validity_on,
                    'sorting' => $banner_position,
                    'status' => 1,
                    'redirect_category_id' => $redirect_category_id,
                    'redirect_vendor_id' => $redirect_vendor_id,
                    'link' => $link,
                    'link_url' => $link_url,
                    'image_mobile' => NULL,
                    'start_date_time' => $start_date_time,
                    'end_date_time' => $end_date_time
                ]);
                //Log::info('banner image upload insert done: '.$bannerid);

                $mobile_banner_position = 1;
                $mobile_banner_pos = DB::connection($schemaName)->table('mobile_banners')->where('sorting', \DB::raw("(select max(`sorting`) from mobile_banners)"))->first();
                if ($mobile_banner_pos) {
                    $mobile_banner_position = $mobile_banner_pos->sorting + 1;
                }

                $mobile_banner_id = DB::connection($schemaName)->table('mobile_banners')->insertGetId([
                    'name' => $bannername,
                    'image' => $banner_storage_file_path != '' ? $banner_storage_file_path : 'default/default_images.png',
                    'validity_on' => $validity_on,
                    'sorting' => $mobile_banner_position,
                    'status' => 1,
                    'redirect_category_id' => $redirect_category_id,
                    'redirect_vendor_id' => $redirect_vendor_id,
                    'link' => $link,
                    'link_url' => $link_url,
                    'start_date_time' => $start_date_time,
                    'end_date_time' => $end_date_time
                ]);
                //}
            }
        }

        $web_styling_id = config('constants.BusinessTypes.' . (@$dummy_onboard_data->business_type ?? "food") . '.web_styling_id');

        $option_change  = WebStylingOption::on($schemaName)->update(array('is_selected' => 0));
        $font           = WebStylingOption::on($schemaName)->where('id', $web_styling_id)->update(array('is_selected' => 1));

        $app_template_theme = config('constants.BusinessTypes.' . (@$dummy_onboard_data->business_type ?? "food") . '.app_template_theme');
        Log::info($app_template_theme);
        $app_theme = AppStylingOption::on($schemaName)->where('id', $app_template_theme)->first();
        Log::info($app_theme);
        $app_theme_change = AppStylingOption::on($schemaName)->where('app_styling_id', '=', $app_theme->app_styling_id)->update(array('is_selected' => 0));
        $app_theme->is_selected = 1;
        $app_theme->save();

        $app_template_bar = config('constants.BusinessTypes.' . (@$dummy_onboard_data->business_type ?? "food") . '.app_template_bar');

        $app_bar = AppStylingOption::on($schemaName)->where('id', $app_template_bar)->first();
        $app_bar_change = AppStylingOption::on($schemaName)->where('app_styling_id', '=', $app_bar->app_styling_id)->update(array('is_selected' => 0));
        $app_bar->is_selected = 1;
        $app_bar->save();

        $catFolderName = $client['code'] . '/category/icon';
        $prodFolderName = $client['code'] . '/prods';
        $brandFolderName = $client['code'] . '/brand';

        //Log::info($dummy_onboard_data->fulldata);

        $data = $dummy_onboard_data->fulldata != "{}" ? json_decode(@$dummy_onboard_data->fulldata, true) : '';
        $catdataArray = [];
        $proddataArray = [];
        $variantdataArray = [];
        $optiondataArray = [];
        $branddataArray = [];
        // Log::info($data);
        // (!in_array(@$dummy_onboard_data->fetch_data, [1, 2]))
        if ((@$dummy_onboard_data->fetch_data != 1) && is_array($data) && (count(@$data['categories']) > 0 && count(@$data['stores']) > 0 && @$dummy_onboard_data->aistatus == 1)) {
            try {
                DB::connection($schemaName)->beginTransaction();
                // Log::info('json data comes');
                $nameToIdMap = [];
                $storeNameMap = [];
                $prodNameMap = [];
                $brandNameMap = [];
                if (isset($dummy_onboard_data->catimages) && $dummy_onboard_data->catimages != '') {
                    $catimagesdata = json_decode(@$dummy_onboard_data->catimages, true);
                    foreach ($catimagesdata as $catimage) {
                        $nameToIdMap[$catimage['name']] = $catimage['image_url'];
                    }
                }
                if (isset($dummy_onboard_data->storeimages) && $dummy_onboard_data->storeimages != '') {
                    $storeimagesdata = json_decode(@$dummy_onboard_data->storeimages, true);
                    foreach ($storeimagesdata as $storeimage) {
                        $storeNameMap[$storeimage['name']] = $storeimage['image_url'];
                    }
                }

                if (isset($dummy_onboard_data->prodimages) && $dummy_onboard_data->prodimages != '') {
                    $prodimagesdata = json_decode(@$dummy_onboard_data->prodimages, true);
                    foreach ($prodimagesdata as $prodimage) {
                        $prodNameMap[$prodimage['name']] = $prodimage['image_url'];
                    }
                }

                if (isset($dummy_onboard_data->brandimages) && $dummy_onboard_data->brandimages != '') {
                    $brandimagesdata = json_decode(@$dummy_onboard_data->brandimages, true);
                    foreach ($brandimagesdata as $brandimage) {
                        $brandNameMap[$brandimage['name']] = $brandimage['image_url'];
                    }
                }
                // Log::info($nameToIdMap);
                $t = 0;

                $allBannerImage = config('constants.BusinessTypes.' . $client['business_type'] . '.bannerimage');
                // Log::info('business type---------'.$client['business_type']);     
                // Log::info($allBannerImage);
                // Log::info($allBannerImage[0]);
                // Log::info($allBannerImage[0]['image_url']);
                foreach ($data['categories'] as $c_key => $category) {
                    $name = $category['name'];
                    $cat_storage_file_path = '';
                    if (isset($nameToIdMap) && isset($nameToIdMap[$name])) {
                        $imageurl = $nameToIdMap[$name];

                        // $pathParts = pathinfo(parse_url($imageurl, PHP_URL_PATH));

                        // if (isset($pathParts['extension'])) {
                        //     $extension = $pathParts['extension'];
                        // } else {
                        //     $extension = 'png';
                        // }
                        // $filePath = 'clientlogo';
                        // $cat_storage_file_path = $catFolderName . '/' . time() . '.' . $extension;
                        // $dataimg = @file_get_contents($imageurl);
                        // $file = Storage::disk('s3')->put($cat_storage_file_path, $dataimg, 'public');
                        if (OnBoardingProcessManager::isValidImage($imageurl))
                            $cat_storage_file_path = $imageurl;
                    }

                    //$types = DB::connection($schemaName)->table('types')->whereIn('id', [1, 3, 7, 8, 10])->inRandomOrder()->first();
                    $types = array(1, 3, 7, 8, 10);

                    $typeindex = $t % count($types);
                    $catname = preg_replace('/[^a-zA-Z0-9-_\.]/', '', $category['url_slug']);
                    $catSlug = OnBoardingProcessManager::checkProductSku($catname, $schemaName);
                    //$category_type =  $client['business_type'] == 'super_app' ? $types[$typeindex] : config('constants.CategoryTypesAccordingBusiness.'.$client['business_type']);

                    $category_type =  $client['business_type'] == 'super_app' ? $types[$typeindex] : config('constants.BusinessTypes.' . $client['business_type'] . '.category_type_id');

                    if ($client['business_type'] == "food") {
                        $category_type = config('constants.BusinessTypes.' . $client['business_type'] . '.category_type_id');
                    }else if($client['business_type'] == "home_service"){
                        $category_type =  (($c_key%2)==0) ? 12 : 8;
                    }
                    

                    $catid = DB::connection($schemaName)->table('categories')->insertGetId([
                        'slug' => $category['url_slug'],
                        'icon' => $cat_storage_file_path != '' ? $cat_storage_file_path : 'default/default_images.png',
                        //'type_id' => $client['business_type'] == 'super_app' ? $types->id : config('constants.CategoryTypesAccordingBusiness.'.$client['business_type']),
                        'type_id' => $category_type,
                        'is_visible' => 1,
                        'status' => 1,
                        'position' => 1,
                        'is_core' => 1,
                        'can_add_products' => 1,
                        'display_mode' => 1,
                        'parent_id' => 1,
                        'image' => $allBannerImage ? $allBannerImage[0]['image_url'] : 'NULL'
                        //'type_id'   => $types->id
                    ]);
                    $t++;

                    $catdataArray[] = [
                        'name' => $category['name'],
                        'category_id' => $catid,
                        'table' => 'category_translations'
                    ];

                    $catTranslationid = DB::connection($schemaName)->table('category_translations')->insertGetId([
                        'name' => $category['name'],
                        'trans-slug' => '',
                        'meta_title' => $category['name'],
                        'meta_description' => '',
                        'meta_keywords' => '',
                        'category_id' => $catid,
                        'language_id' => @$dummy_onboard_data->primary_language,
                    ]);

                    $faker = \Faker\Factory::create();

                    if (count($category['variants']) > 0) {
                        foreach ($category['variants'] as $variant) {
                            if (isset($variant) && isset($variant['title']) && isset($variant['type'])) {
                                $variantid = DB::connection($schemaName)->table('variants')->insertGetId([
                                    'title' => $variant['title'],
                                    'type' => $variant['type'],
                                    'position' => 1,
                                    'status' => 1
                                ]);

                                $variantCatid = DB::connection($schemaName)->table('variant_categories')->insertGetId([
                                    'variant_id' => $variantid,
                                    'category_id' => $catid
                                ]);

                                $catTranslationid = DB::connection($schemaName)->table('variant_translations')->insertGetId([
                                    'title' => $variant['title'],
                                    'variant_id' => $variantid,
                                    'language_id' => @$dummy_onboard_data->primary_language,
                                ]);

                                $variantdataArray[] = [
                                    'title' => $variant['title'],
                                    'variant_id' => $variantid,
                                    'table' => 'variant_translations'
                                ];

                                if (isset($variant['option'])) {
                                    // Use "option" key
                                    $variant['option'] = $variant['option'];
                                } elseif (isset($variant['options'])) {
                                    // Use "options" key
                                    $variant['option'] = $variant['options'];
                                } else {
                                    // Handle the case where neither "option" nor "options" is present
                                    $variant['option'] = [];
                                }

                                if (count($variant['option']) > 0) {
                                    foreach ($variant['option'] as $option) {
                                        if (isset($option) && isset($option['title'])) {
                                            $optionid = DB::connection($schemaName)->table('variant_options')->insertGetId([
                                                'title' => $option['title'],
                                                'variant_id' => $variantid,
                                                'hexacode' => (isset($variant['type']) && $variant['type'] == 2) ? $option['colorcode'] : '',
                                                'position' => 1,
                                            ]);

                                            $optionTranslationid = DB::connection($schemaName)->table('variant_option_translations')->insertGetId([
                                                'title' => $option['title'],
                                                'variant_option_id' => $optionid,
                                                'language_id' => @$dummy_onboard_data->primary_language,
                                            ]);

                                            $optiondataArray[] = [
                                                'title' => $option['title'],
                                                'variant_option_id' => $optionid,
                                                'table' => 'variant_option_translations'
                                            ];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                //Log::info($data);
                // Log::info('stores');
                // Log::info($data['stores']);
                // Log::info('storesimages');
                // Log::info('business_type : ' . $client['business_type']);
                // Log::info(config('constants.VendorTypesAccordingBusiness.' . $client['business_type'] . '.dinein'));
                /*$dinein = config('constants.VendorTypesAccordingBusiness.'.$client['business_type'].'.dinein');
                $takeaway = config('constants.VendorTypesAccordingBusiness.'.$client['business_type'].'.takeaway');
                $delivery = config('constants.VendorTypesAccordingBusiness.'.$client['business_type'].'.delivery');
                $rental = config('constants.VendorTypesAccordingBusiness.'.$client['business_type'].'.rental');
                $pick_drop = config('constants.VendorTypesAccordingBusiness.'.$client['business_type'].'.pick_drop');
                $on_demand = config('constants.VendorTypesAccordingBusiness.'.$client['business_type'].'.on_demand');
                $laundry = config('constants.VendorTypesAccordingBusiness.'.$client['business_type'].'.laundry');
                $appointment = config('constants.VendorTypesAccordingBusiness.'.$client['business_type'].'.appointment');
                $p2p = config('constants.VendorTypesAccordingBusiness.'.$client['business_type'].'.appointment');*/
                $vendor_template_id = 1;
                // if ($client['business_type'] == "food") {
                //     $vendor_template_id = config('constants.BusinessTypes.' . $client['business_type'] . '.vendor_template_id');
                // }

                $vendor_template_id =  $client['business_type'] == 'taxi' ? $vendor_template_id : config('constants.BusinessTypes.' . $client['business_type'] . '.vendor_template_id');

                foreach ($data['stores'] as $store) {
                    $storename = $store['store_name'];
                    //Log::info('storename: ' . $storename);
                    //Log::info('dine check');
                    //Log::info(config('constants.VendorTypesAccordingBusiness.'.$client['business_type'].'.dinein'));
                    $store_storage_file_path = '';
                    if (isset($storeNameMap) && isset($storeNameMap[$storename])) {
                        $imageurl = $storeNameMap[$storename];

                        // $pathParts = pathinfo(parse_url($imageurl, PHP_URL_PATH));

                        // if (isset($pathParts['extension'])) {
                        //     $extension = $pathParts['extension'];
                        // } else {
                        //     $extension = 'png';
                        // }
                        // $store_storage_file_path = 'vendor/' . time() . '.' . $extension;
                        // $dataimg = @file_get_contents($imageurl);
                        // $file = Storage::disk('s3')->put($store_storage_file_path, $dataimg, 'public');
                        if (OnBoardingProcessManager::isValidImage($imageurl))
                            $store_storage_file_path = $imageurl;
                    }
                    if (@$dummy_onboard_data->vendor_type == 1) {
                        $storeid = DB::connection($schemaName)->table('vendors')->insertGetId([
                            'name' => $store['store_name'],
                            'slug' => $store['slug'],
                            'desc' => NULL,
                            'logo' => $store_storage_file_path != '' ? $store_storage_file_path : 'default/default_images.png',
                            'banner' => $allBannerImage ? $allBannerImage[0]['image_url'] : 'default/default_images.png',
                            'address' => @$dummy_onboard_data->default_location_name ?? 'Chandigarh, Punjab, India',
                            'latitude' => @$dummy_onboard_data->default_latitude ?? '30.733315',
                            'longitude' => @$dummy_onboard_data->default_longitude ?? '76.779419',
                            'order_min_amount' => '0.00',
                            'order_pre_time' => NULL,
                            'auto_reject_time' => NULL,
                            'commission_percent' => 1,
                            'commission_fixed_per_order' => '0.00',
                            'commission_monthly' => '0.00',
                            'dine_in' =>  $dinein, //config('constants.VendorTypesAccordingBusiness.'.$client['business_type'].'.dinein'),
                            'takeaway' => $takeaway, //config('constants.VendorTypesAccordingBusiness.'.$client['business_type'].'.takeaway'),
                            'delivery' => $delivery, //config('constants.VendorTypesAccordingBusiness.'.$client['business_type'].'.delivery'),
                            'rental' => $rental, //config('constants.VendorTypesAccordingBusiness.'.$client['business_type'].'.rental'),
                            'pick_drop' => $pick_drop, //config('constants.VendorTypesAccordingBusiness.'.$client['business_type'].'.pick_drop'),
                            'on_demand' => $on_demand, //config('constants.VendorTypesAccordingBusiness.'.$client['business_type'].'.on_demand'),
                            'laundry' => $laundry, //config('constants.VendorTypesAccordingBusiness.'.$client['business_type'].'.laundry'),
                            'appointment' => $appointment, //config('constants.VendorTypesAccordingBusiness.'.$client['business_type'].'.appointment'),
                            'p2p' => $p2p, //config('constants.VendorTypesAccordingBusiness.'.$client['business_type'].'.appointment'),
                            'status' => 1,
                            'add_category' => 1,
                            'setting' => 0,
                            'vendor_templete_id' => $vendor_template_id,
                        ]);
                    } else {
                        $storeid = 1;

                        $vendor_update = DB::connection($schemaName)->table('vendors')->where('id', 1)->update(
                            [
                                'vendor_templete_id' => $vendor_template_id,
                                'dine_in'       => $dinein,
                                'takeaway'      => $takeaway,
                                'delivery'      => $delivery,
                                'rental'        => $rental,
                                'pick_drop'     => $pick_drop,
                                'on_demand'     => $on_demand,
                                'laundry'       => $laundry,
                                'appointment'   => $appointment,
                            ]
                        );
                    }

                    $client = DB::connection($schemaName)->table('categories')->where('slug', '=', $store['categoryslug'])->first();
                    $vendorCatid = DB::connection($schemaName)->table('vendor_categories')->insertGetId([
                        'vendor_id' => $storeid,
                        'category_id' => $client ? $client->id : 1
                    ]);

                    if (count($store['products']) > 0) {
                        foreach ($store['products'] as $product) {
                            //Log::info('productname: ' . $product['product_name']);

                            $skuname = preg_replace('/[^a-zA-Z0-9-_\.]/', '', $product['product_name']);

                            $sku = OnBoardingProcessManager::checkProductSku($skuname, $schemaName);

                            $productid = DB::connection($schemaName)->table('products')->insertGetId([
                                'sku' => $sku, //$product['sku'],
                                'title' => $product['product_name'],
                                'url_slug' => $product['url_slug'],
                                'vendor_id' => $storeid,
                                'type_id' => 1,
                                'is_new' => 1,
                                'is_featured' => 1,
                                'is_live' => 1,
                                'is_physical' => 1,
                                'mode_of_service' => in_array($client->type_id,[12,8]) ? "schedule" : NULL,
                                'category_id' => $client->id
                            ]);

                            $pname = $product['product_name'];
                            //Log::info(var_dump($prodNameMap));
                            //Log::info('product images: '.$prodNameMap[$pname]);
                            $prod_storage_file_path = '';
                            if (isset($prodNameMap) && isset($prodNameMap[$pname])) {
                                $imageurl = $prodNameMap[$pname];

                                // $pathParts = pathinfo(parse_url($imageurl, PHP_URL_PATH));

                                // if (isset($pathParts['extension'])) {
                                //     $extension = $pathParts['extension'];
                                // } else {
                                //     $extension = 'png';
                                // }

                                // $prod_storage_file_path = $prodFolderName . '/' . time() . '.' . $extension;
                                // $dataimg = @file_get_contents($imageurl);
                                // $file = Storage::disk('s3')->put($prod_storage_file_path, $dataimg, 'public');
                                if (OnBoardingProcessManager::isValidImage($imageurl))
                                    $prod_storage_file_path = $imageurl;

                                $vendorMediaid = DB::connection($schemaName)->table('vendor_media')->insertGetId([
                                    'media_type' => 1,
                                    'vendor_id' => $storeid,
                                    'path' => $prod_storage_file_path != '' ? $prod_storage_file_path : 'default/default_images.png',
                                ]);

                                $productImagesid = DB::connection($schemaName)->table('product_images')->insertGetId([
                                    'product_id' => $productid,
                                    'media_id' => $vendorMediaid,
                                    'is_default' => 1
                                ]);
                            }

                            $prodTranslationid = DB::connection($schemaName)->table('product_variants')->insertGetId([
                                'sku' => $sku, //$product['sku'],
                                'title' => $sku, //$product['sku'],
                                'product_id' => $productid,
                                'barcode' => $faker->bothify('?#?#?#?#?#?#??'),
                                'price' => $product['price'],
                                'quantity' => 10,
                                'compare_at_price' => $product['price'] + 1
                            ]);

                            /*if (Cache::has('categroies_ai')) {
                                $cachedData = Cache::get('categroies_ai');
                                if (isset($cachedData['name']) && $cachedData['name'] === $store['categoryname']) {
                                    $categoryId = $cachedData['id'];
                                    $vendorCatid = DB::connection($schemaName)->table('product_categories')->insertGetId([
                                        'product_id' => $productid,
                                        'category_id' => $client->id//$categoryId
                                    ]);
                                }
                            }*/

                            $vendorCatid = DB::connection($schemaName)->table('product_categories')->insertGetId([
                                'product_id' => $productid,
                                'category_id' => $client->id //$categoryId
                            ]);

                            $prodTranslationid = DB::connection($schemaName)->table('product_translations')->insertGetId([
                                'title' => $product['product_name'],
                                'body_html' => $product['description'],
                                'meta_title' => $product['product_name'],
                                'meta_keyword' => $product['product_name'],
                                'meta_description' => $product['description'],
                                'product_id' => $productid,
                                'language_id' => @$dummy_onboard_data->primary_language,
                            ]);

                            $proddataArray[] = [
                                'title' => $product['product_name'],
                                'meta_description' => $product['description'],
                                'product_id' => $productid,
                                'table' => 'product_translations'
                            ];
                        }
                    }
                }
                // Log::info('product end ');
                $prevBrand = null;
                if (isset($data['brand']) && count(@$data['brand']) > 0) {
                    // Log::info('brand start ');
                    foreach ($data['brand'] as $brand) {
                        $brandname = $brand['name'];
                        //Log::info('brandname: ' . $brandname);

                        $brand_data = DB::connection($schemaName)->table('brands')->where('title', $brandname)->first();

                        if (isset($brand_data) && $brand_data != '') {
                            $brandid = $brand_data->id;
                        } else {
                            $brand_storage_file_path = 'default/default_images.png';
                            if (isset($brandNameMap) && isset($brandNameMap[$brandname])) {
                                $imageurl = $brandNameMap[$brandname];

                                // $pathParts = pathinfo(parse_url($imageurl, PHP_URL_PATH));

                                // if (isset($pathParts['extension'])) {
                                //     $extension = $pathParts['extension'];
                                // } else {
                                //     $extension = 'png';
                                // }
                                // $brand_storage_file_path = $brandFolderName . '/' . time() . '.' . $extension;
                                // $dataimg = @file_get_contents($imageurl);
                                // $file = Storage::disk('s3')->put($brand_storage_file_path, $dataimg, 'public');
                                if (OnBoardingProcessManager::isValidImage($imageurl))
                                    $brand_storage_file_path = $imageurl;
                            }

                            $brand_position = 1;
                            $brand_pos = DB::connection($schemaName)->table('brands')->where('position', \DB::raw("(select max(`position`) from brands)"))->first();
                            if ($brand_pos) {
                                $brand_position = $brand_pos->position + 1;
                            }

                            $brandid = DB::connection($schemaName)->table('brands')->insertGetId([
                                'title' => $brandname,
                                'position' => $brand_position,
                                'image' => $brand_storage_file_path != '' ? $brand_storage_file_path : 'default/default_images.png',
                                'image_banner' => $allBannerImage ? $allBannerImage[0]['image_url'] : 'default/default_images.png',
                            ]);
                        }

                        if ($brandid > 0) {
                            $category = DB::connection($schemaName)->table('categories')->where('slug', '=', $brand['categoryslug'])->first();
                            $brandCatid = DB::connection($schemaName)->table('brand_categories')->insertGetId([
                                'brand_id' => $brandid,
                                'category_id' => $category ? $category->id : 1
                            ]);

                            $brandTranslationid = DB::connection($schemaName)->table('brand_translations')->insertGetId([
                                'title' => $brandname,
                                'brand_id' => $brandid,
                                'language_id' => @$dummy_onboard_data->primary_language,
                            ]);

                            $branddataArray[] = [
                                'title' => $brandname,
                                'brand_id' => $brandid,
                                'table' => 'brand_translations'
                            ];

                            $client = DB::connection($schemaName)->table('products')->where('url_slug', $brand['productslug'])->update(['brand_id' => $brandid]);
                        }
                    }
                    // Log::info('brand end ');
                }
                DB::connection($schemaName)->commit();
            } catch (\Exception $ex) {
                DB::connection($schemaName)->rollBack();
                Log::info('dummy_onboard_data OnBoardingDataProcessJob error');
                Log::info($ex->getMessage());
                Log::info("OnBoardingDataProcessJob error database start comment");
                $sqlEmport = OnBoardingProcessManager::updateSQl($schemaName, $dummy_onboard_data->business_type);
                //$sqlEmport = OnBoardingProcessManager::updateSQl($schemaName, $client['business_type']);
                Log::info("OnBoardingDataProcessJob error done comment");

            }

        } else {
            Log::info("import database start comment");
            $sqlEmport = OnBoardingProcessManager::updateSQl($schemaName, $dummy_onboard_data->business_type);
            //$sqlEmport = OnBoardingProcessManager::updateSQl($schemaName, $client['business_type']);
            Log::info("import database done comment");
        }

        // $resultArray = [
        //     'brand_data' => $branddataArray,
        //     'product_data' => $proddataArray,
        //     'option_data' => $optiondataArray,
        //     'cat_data' => $catdataArray,
        //     'variant_data' => $variantdataArray,
        // ];
        // $jsonString = json_encode($resultArray, JSON_PRETTY_PRINT);
        // Log::info('json for translation');
        // Log::info($jsonString);

        $clientmain = Client::on($schemaName)->update(['status' => 1]);


        //$client = Client::on('god')->where('id', $dummy_onboard_data->client_id)->update(['status' => 1]);
        $client = DB::connection('god')->table('clients')->where('id', $dummy_onboard_data->client_id)->update(['status' => 1]);

        $client = DB::connection('god')->table('clients')->where('id', $dummy_onboard_data->client_id)->first();

        if (!empty($client->custom_domain)) {
            $domain = $client->custom_domain;
        } else {
            $domain = $client->sub_domain . env('SUBMAINDOMAIN');
        }

        $details = [
            'name' => @$dummy_onboard_data->name,
            'email' => @$dummy_onboard_data->email,
            'link'    => "https://$dummy_onboard_data->domainname.vendsuite.ai",
            'password' => 'password',
            'subject' => 'Vendsuite - Onboarding Successfully Completed',
            'message' => 'Thank you for registration',
            'mail_from' => env('MAIL_FROM_ADDRESS')
        ];

        try {
            // Mail::to($details['email'])->send(new OnBoardAccountCompletedMail($details)); // Mail class not available
        } catch (\Exception $e) {
            Log::info('getting error in Send Email to this email: ' . $dummy_onboard_data->email);
            Log::info($e->getMessage());
        }

        //$addDynamicHtml = OnBoardingProcessManager::insertDynamicHtml($schemaName, @$dummy_onboard_data->business_type);
        $addDynamicHtml = OnBoardingProcessManager::insertDynamicHtml($schemaName, @$dummy_onboard_data->primary_language, @$dummy_onboard_data->business_type, @$dummy_onboard_data->whychooseustext);


        DB::disconnect($schemaName);

        $json_creds = json_encode(array(
            'cod_min_amount' => 0
        ));
        PaymentOption::on($schemaName)->where('id', 1)->update([
            'status' => 1,
            'credentials' => $json_creds,
            'test_mode' => 0
        ]);

        DB::connection('god')->table('dummy_onboard_data')->where('id',   $dummy_onboard_data->id)->update(['status' => 1]);
        Log::info('onboarding is done ');

        CreateDummyDbProcessJob::dispatch();
    }
}
