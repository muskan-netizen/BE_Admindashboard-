<?php

namespace App\Http\Controllers\Front;

use Session;
use Carbon\Carbon;
use GuzzleHttp\Client as GCLIENT;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Front\FrontController;
use Illuminate\Contracts\Session\Session as SessionSession;
use App\Models\{Currency, Banner, MobileBanner, FaqTranslations, Category, Brand, Product, ClientLanguage, Vendor, VendorCategory, ClientCurrency,Client, ClientPreference, DriverRegistrationDocument, HomePageLabel, Page, VendorRegistrationDocument, Language, OnboardSetting, CabBookingLayout, WebStylingOption, SubscriptionInvoicesVendor, Order, VendorOrderStatus,CabBookingLayoutTranslation,ShowSubscriptionPlanOnSignup, TaxCategory, VendorCities, UserWishlist};
use Illuminate\Contracts\View\View;
use Illuminate\View\View as ViewView;
use Redirect;
use DB;
use Illuminate\Http\Response;
use Cookie;
use App\Http\Traits\{OrderTrait,ProductActionTrait};
use App\Http\Traits\HomePage\{HomePageTrait};

class UserhomeController extends FrontController
{
    use ApiResponser, OrderTrait,ProductActionTrait, HomePageTrait;
    private $field_status = 2;
    public $cities = [];
    public $additionalPreference =[];
    
    public function __construct(Request $request)
    {
     //   $this->additionalPreference = getAdditionalPreference(['is_token_currency_enable', 'token_currency']);
    }


    public function setTheme(Request $request)
    {
        $clientData = Client::select('id', 'logo','dark_logo','socket_url')->where('id', '>', 0)->first();
        if ($request->theme_color == "dark") {
            Session::put('config_theme', $request->theme_color);
            return response()->json(['success' => true, 'logo' => $clientData->dark_logo['original']]);
        } else {
            Session::forget('config_theme');
            return response()->json(['success' => true, 'logo' => $clientData->logo['original']]);
        }
    }
    public function getConfig()
    {
        $client_preferences = ClientPreference::first()->makeHidden(['customer_support_key','delivery_service_key','fcm_server_key','fcm_api_key','mail_username','mail_password','sms_key','sms_secret','sms_credentials','fb_client_secret','fcm_storage_bucket','customer_support_application_id','pickup_delivery_service_key']);
        return response()->json(['success' => true, 'client_preferences' => $client_preferences]);

    }

    public function getLastMileTeams()
    {
        try {
            $dispatch_domain = $this->checkIfLastMileOn();
            if ($dispatch_domain && $dispatch_domain != false) {
                $unique = Auth::user()->code;
                $client = new GCLIENT([
                    'headers' => [
                        'personaltoken' => $dispatch_domain->delivery_service_key,
                        'shortcode' => $dispatch_domain->delivery_service_key_code,
                        'content-type' => 'application/json'
                    ]
                ]);
                $url = $dispatch_domain->delivery_service_key_url;
                $res = $client->get($url . '/api/get-all-teams');
                $response = json_decode($res->getBody(), true);
                if ($response && $response['message'] == 'success') {
                    return $response['teams'];
                }
            }
        } catch (\Exception $e) {
        }
    }

    public function getAgentTags()
    {
        try {
            $dispatch_domain = $this->checkIfLastMileOn();
            if ($dispatch_domain && $dispatch_domain != false) {
                $unique = Auth::user()->code;
                $client = new GCLIENT([
                    'headers' => [
                        'personaltoken' => $dispatch_domain->delivery_service_key,
                        'shortcode' => $dispatch_domain->delivery_service_key_code,
                        'content-type' => 'application/json'
                    ]
                ]);
                $url = $dispatch_domain->delivery_service_key_url;
                $res = $client->get($url . '/api/get-all-teams');
                $response = json_decode($res->getBody(), true);
                if ($response && $response['message'] == 'success') {
                    return $response['teams'];
                }
            }
        } catch (\Exception $e) {
        }
    }

    public function checkIfLastMileDeliveryOn()
    {
        $preference = ClientPreference::first();

        if($preference->business_type == 'taxi'){
            if ($preference->need_dispacher_ride == 1 && !empty($preference->pickup_delivery_service_key) && !empty($preference->pickup_delivery_service_key_code) && !empty($preference->pickup_delivery_service_key_url))
                return $preference;
            else
                return false;
        }elseif($preference->business_type == 'laundry'){
            if ($preference->need_laundry_service == 1 && !empty($preference->laundry_service_key) && !empty($preference->laundry_service_key_code) && !empty($preference->laundry_service_key_url))
                return $preference;
            else
                return false;
        } else{
            if ($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
                return $preference;
            else
                return false;
        }

    }

    public function driverDocuments()
    {
        try {
            $dispatch_domain = $this->checkIfLastMileDeliveryOn();

             if($dispatch_domain->business_type == 'taxi'){

                $url = $dispatch_domain->pickup_delivery_service_key_url;
                $endpoint =$url . "/api/send-documents";
                 $client = new GCLIENT(['headers' => ['personaltoken' => $dispatch_domain->pickup_delivery_service_key, 'shortcode' => $dispatch_domain->pickup_delivery_service_key_code]]);

                $response = $client->post($endpoint);
                $response = json_decode($response->getBody(), true);
                return json_encode($response['data']);
            } elseif($dispatch_domain->business_type == 'laundry'){
                    $url = $dispatch_domain->laundry_service_key_url;
                    $endpoint =$url . "/api/send-documents";
                    $client = new GCLIENT(['headers' => ['personaltoken' => $dispatch_domain->laundry_service_key, 'shortcode' => $dispatch_domain->laundry_service_key_code]]);

                    $response = $client->post($endpoint);
                    $response = json_decode($response->getBody(), true);
                    return json_encode($response['data']);
            } else{

                $url = $dispatch_domain->delivery_service_key_url;
                $endpoint =$url . "/api/send-documents";
                 $client = new GCLIENT(['headers' => ['personaltoken' => $dispatch_domain->delivery_service_key, 'shortcode' => $dispatch_domain->delivery_service_key_code]]);

                $response = $client->post($endpoint);
                $response = json_decode($response->getBody(), true);
                return json_encode($response['data']);
            }

        } catch (\Exception $e) {
            $data = [];
            $data['status'] = 400;
            $data['message'] =  $e->getMessage();
            return $data;
        }
    }

    public function driverSignup()
    {
        $user = Auth::user();
        $language_id = Session::get('customerLanguage');
        $client_preferences = ClientPreference::first();
        $navCategories = $this->categoryNav($language_id);
        $client = Auth::user();
        $page_detail = Page::with(['translations' => function ($q) {
            $q->where('language_id', session()->get('customerLanguage'));
        }])->where('slug', 'driver-registration')->firstOrFail();
        $last_mile_teams = [];

        $tag = [];

        $showTag = implode(',', $tag);
        $driver_registration_documents = json_decode($this->driverDocuments());
        return view('frontend.driver-registration', compact('page_detail', 'navCategories', 'user', 'showTag', 'driver_registration_documents'));
    }

    public function checkIfLastMileOn()
    {
        $preference = ClientPreference::first();
        if ($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
            return $preference;
        else
            return false;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getExtraPage(Request $request)
    {
        $user = Auth::user();
        $language_id = Session::get('customerLanguage');
        $client_preferences = ClientPreference::first();
        $navCategories = $this->categoryNav($language_id);
        $page_detail = Page::with(['translations' => function ($q) {
            $q->where('language_id', session()->get('customerLanguage'));
        }])->where('slug', $request->slug)->firstOrFail();
        if ($page_detail->primary->type_of_form != 2) {
            if($page_detail->primary->type_of_form == 3){
             $faq =   FaqTranslations::where('page_id',$page_detail->id)->where('language_id', $language_id)->get();
             $page_detail->faqs_details = $faq;
            }
            $vendor_registration_documents = VendorRegistrationDocument::with(['primary','options','options.translation' => function($query) use($language_id) {
                $query->where('language_id', session()->get('customerLanguage'));
            }])->get();
            $builds = array();
            $categories = Category::with('translation_one')->select('id', 'icon', 'slug', 'type_id', 'is_visible', 'status', 'is_core', 'vendor_id', 'can_add_products', 'parent_id')
                                    ->where('id', '>', '1')
                                    // ->where('is_core', 1)
                                    ->whereNotIn('type_id', [4, 5])
                                    ->where(function ($q) {
                                        $q->whereNull('vendor_id');
                                    })->orderBy('position', 'asc')
                                    ->orderBy('id', 'asc')
                                    ->where('status', 1)
                                    ->orderBy('parent_id', 'asc')->get();
                if ($categories) {
                    $builds = $this->buildTree($categories->toArray());
                }

                $VendorCategory =array();

                $templetes  = \DB::table('vendor_templetes')->where('status', 1)->get();
                $server = env('APP_ENV', 'development');
                $langId = session()->get('customerLanguage');
                $privacy = Page::with(['translations' => function ($q) use($langId) {
                    $q->where('language_id', $langId)->where('type_of_form',[4]);   # get privacy & terms url
                }])->whereHas('translations', function ($q) use($langId) {
                    $q->where('language_id', $langId)->where('type_of_form',[4]);   # get privacy & terms url
                })->first();

                $terms = Page::with(['translations' => function ($q) use($langId) {
                    $q->where('language_id', $langId)->where('type_of_form',[5]);   # get privacy & terms url
                }])->whereHas('translations', function ($q) use($langId) {
                    $q->where('language_id', $langId)->where('type_of_form',[5]);   # get privacy & terms url
                })->first();
                return view('frontend.extrapageNew', compact('page_detail','templetes','VendorCategory','builds','navCategories', 'client_preferences', 'user', 'vendor_registration_documents','terms','privacy'));

        }else {
                $tag = [];
                    $showTag = implode(',', $tag);
                    $client = Client::with('country')->first();
                    // pr( $this->driverDocuments());
                    $driverDocs = json_decode($this->driverDocuments());
                    $driver_registration_documents = $driverDocs->documents;
                    foreach ($driverDocs->documents as $key => $doc) {
                        $name = str_replace(" ", "_", $doc->name);
                        $doc->slug = $name;
                    }
                $teams = $driverDocs->all_teams;
                $tags = $driverDocs->agent_tags;
                return view('frontend.driver-registration', compact('page_detail', 'navCategories', 'user', 'showTag', 'driver_registration_documents','client', 'teams', 'tags'));
        }
    }
    public function indexTest(Request $request, $domain='')
    {

        try {
            $home = array();
            $vendor_ids = array();
            if ($request->has('ref')) {
                session(['referrer' => $request->query('ref')]);
            }
            $latitude = Session::get('latitude') ?? null;
            $longitude = Session::get('longitude') ?? null;
            $curId = Session::get('customerCurrency');
            $preferences = Session::get('preferences');
            $langId = Session::get('customerLanguage');
            $client_config = Session::get('client_config');
            $selectedAddress = Session::get('selectedAddress');
            $navCategories = $this->categoryNav($langId);
            Session::put('navCategories', $navCategories);
            $clientPreferences = ClientPreference::first();
            $count = 0;
            $vendor_type = $request->has('type') ? $request->type : Session::get('vendorType');

            if(count($navCategories) > 0 && $vendor_type =='pick_drop' ){
                $categoriesSlug = $navCategories[0]->slug;
                return redirect()->route('categoryDetail',$categoriesSlug);
            }

            if ($clientPreferences) {
                foreach(config('constants.VendorTypes') as $vendor_typ_key => $vendor_typ_value){
                    $clientVendorTypes = $vendor_typ_key.'_check';
                    if($clientPreferences->$clientVendorTypes == 1){
                        $count++;
                    }
                }

                if(empty($latitude) && empty($longitude)){
                    $latitude = $clientPreferences->Default_latitude;
                    $longitude = $clientPreferences->Default_longitude;
                }
            }
            $banners = Banner::with(['category', 'vendor'])->where('status', 1)->where('validity_on', 1)
            ->where(function ($q) {
                $q->whereNull('start_date_time')->orWhere(function ($q2) {
                    $q2->whereDate('start_date_time', '<=', Carbon::now())
                        ->whereDate('end_date_time', '>=', Carbon::now());
                });
            });
            if(isset($clientPreferences->is_service_area_for_banners) && ($clientPreferences->is_service_area_for_banners == 1) && ($clientPreferences->is_hyperlocal == 1)){
                if(!empty($latitude) && !empty($longitude)){
                    $banners = $banners->whereHas('geos.serviceArea', function($query) use ($latitude, $longitude) {
                        $query->select('id')->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $latitude . " " . $longitude . ")'))");
                    });
                }
            }
            $banners = $banners->orderBy('sorting', 'asc')->get();

            $mobile_banners = MobileBanner::with(['category', 'vendor'])->where('status', 1)->where('validity_on', 1)
            ->where(function ($q) {
                $q->whereNull('start_date_time')->orWhere(function ($q2) {
                    $q2->whereDate('start_date_time', '<=', Carbon::now())
                        ->whereDate('end_date_time', '>=', Carbon::now());
                });
            });
            if(isset($clientPreferences->is_service_area_for_banners) && ($clientPreferences->is_service_area_for_banners == 1) && ($clientPreferences->is_hyperlocal == 1)){
                if(!empty($latitude) && !empty($longitude)){
                    $mobile_banners = $mobile_banners->whereHas('geos.serviceArea', function($query) use ($latitude, $longitude) {
                        $query->select('id')->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $latitude . " " . $longitude . ")'))");
                    });
                }
            }
            $mobile_banners = $mobile_banners->orderBy('sorting', 'asc')->get();


            $home_page_labels = CabBookingLayout::where('is_active', 1)->where('for_no_product_found_html',0)->orderBy('order_by')->web();


            if (isset($langId) && !empty($langId))
                $home_page_labels = $home_page_labels->with(['translations' => function ($q) use ($langId) {
                    $q->where('language_id', $langId);
                }]);

            $home_page_labels = $home_page_labels->get();

            if (count($home_page_labels) == 0)
                $home_page_labels = HomePageLabel::with('translations')->where('is_active', 1)->orderBy('order_by')->get();


            $only_cab_booking = OnboardSetting::where('key_value', 'home_page_cab_booking')->count();
            if ($only_cab_booking == 1)
                return Redirect::route('categoryDetail', 'cabservice');

            $home_page_pickup_labels = CabBookingLayout::with('translations')->web();
             
            
            $home_page_pickup_labels = $home_page_pickup_labels->where('is_active', 1)->where('for_no_product_found_html',0)->orderBy('order_by')->get();

            $set_template = WebStylingOption::where('web_styling_id', 1)->where('is_selected', 1)->first();

            $for_no_product_found_html = CabBookingLayout::with('translations')->where('is_active', 1)->web();
           
            $for_no_product_found_html = $for_no_product_found_html->where('for_no_product_found_html',1)->orderBy('order_by')->get();
            $enable_layout = CabBookingLayout::where('is_active',1)->web();
            
            $enable_layout = $enable_layout->orderBy('order_by','asc')->pluck('slug')->toArray();

            // $last_mile = $this->checkIfLastMileDeliveryOn();
            $view_page ="home-template-one";
            if (isset($set_template)  && $set_template->template_id == 1){
                $view_page = 'home-template-one';
            }elseif(isset($set_template)  && $set_template->template_id == 2){
                $view_page = "home-template-two";
            }elseif(isset($set_template)  && $set_template->template_id == 3){
                $view_page = "home-template-three";
            }elseif(isset($set_template)  && $set_template->template_id == 4){
                $view_page = "home-template-four";
            }elseif(isset($set_template)  && $set_template->template_id == 5){
                $view_page = "home-template-five";
            }elseif(isset($set_template)  && $set_template->template_id == 6){
                $view_page = "home-template-six";
            }
            //pr($set_template->toArray());exit();
            return view('frontend.'.$view_page)->with(['home' => $home,  'count' => $count, 'for_no_product_found_html' => $for_no_product_found_html,'homePagePickupLabels' => $home_page_pickup_labels, 'homePageLabels' => $home_page_labels, 'clientPreferences' => $clientPreferences, 'banners' => $banners,'mobile_banners'=>$mobile_banners, 'navCategories' => $navCategories, 'selectedAddress' => $selectedAddress, 'latitude' => $latitude, 'longitude' => $longitude,'enable_layout'=>$enable_layout]);

        } catch (Exception $e) {
            pr($e->getCode());
            die;
        }
    }
    public function index(Request $request, $domain='')
    {
        try {
            $home = array();
            $vendor_ids = array();
            if ($request->has('ref')) {
                session(['referrer' => $request->query('ref')]);
            }
            $latitude = Session::get('latitude') ?? null;
            $longitude = Session::get('longitude') ?? null;
            $curId = Session::get('customerCurrency');
            $preferences = Session::get('preferences');
            $langId = Session::get('customerLanguage');
            $client_config = Session::get('client_config');
            $selectedAddress = Session::get('selectedAddress');
            $_REQUEST['request_from'] = 1;

            $navCategories = $this->categoryNav($langId);
            Session::put('navCategories', $navCategories);
            $clientPreferences = ClientPreference::first();
            $vendor_type = $request->has('type') ? $request->type : Session::get('vendorType');


            $count = 0;
            if ($clientPreferences) {
                foreach(config('constants.VendorTypes') as $vendor_typ_key => $vendor_typ_value){
                    $clientVendorTypes = $vendor_typ_key.'_check';
                    if($clientPreferences->$clientVendorTypes == 1){
                        $count++;
                    }
                }

                if(empty($latitude) && empty($longitude)){
                    $latitude = $clientPreferences->Default_latitude;
                    $longitude = $clientPreferences->Default_longitude;
                }

            }
            if(count($navCategories) > 0 && ($vendor_type =='pick_drop') &&  ($count!=1) ){
                $categoriesSlug = $navCategories[0]->slug;
                return redirect()->route('categoryDetail',$categoriesSlug);
            }

            $banners = Banner::with(['category', 'vendor'])->where('status', 1)->where('validity_on', 1)
            ->where(function ($q) {
                $q->whereNull('start_date_time')->orWhere(function ($q2) {
                    $q2->whereDate('start_date_time', '<=', Carbon::now())
                        ->whereDate('end_date_time', '>=', Carbon::now());
                });
            });
            if(isset($clientPreferences->is_service_area_for_banners) && ($clientPreferences->is_service_area_for_banners == 1) && ($clientPreferences->is_hyperlocal == 1)){
                if(!empty($latitude) && !empty($longitude)){
                    $banners = $banners->whereHas('geos.serviceArea', function($query) use ($latitude, $longitude) {
                        $query->select('id')->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $latitude . " " . $longitude . ")'))");
                    });
                }
            }
            $banners = $banners->orderBy('sorting', 'asc')->get();

            $mobile_banners = MobileBanner::with(['category', 'vendor'])->where('status', 1)->where('validity_on', 1)
            ->where(function ($q) {
                $q->whereNull('start_date_time')->orWhere(function ($q2) {
                    $q2->whereDate('start_date_time', '<=', Carbon::now())
                        ->whereDate('end_date_time', '>=', Carbon::now());
                });
            });
            if(isset($clientPreferences->is_service_area_for_banners) && ($clientPreferences->is_service_area_for_banners == 1) && ($clientPreferences->is_hyperlocal == 1)){
                if(!empty($latitude) && !empty($longitude)){
                    $mobile_banners = $mobile_banners->whereHas('geos.serviceArea', function($query) use ($latitude, $longitude) {
                        $query->select('id')->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $latitude . " " . $longitude . ")'))");
                    });
                }
            }
            $mobile_banners = $mobile_banners->orderBy('sorting', 'asc')->get();


            $home_page_labels = CabBookingLayout::where('is_active', 1)->web()->where('for_no_product_found_html',0)->orderBy('order_by');


            if (isset($langId) && !empty($langId))
                $home_page_labels = $home_page_labels->with(['translations' => function ($q) use ($langId) {
                    $q->where('language_id', $langId);
                }]);

            $home_page_labels = $home_page_labels->get();
            // if nothing in enblead for home page then show all 
            // if (count($home_page_labels) == 0)
            //     $home_page_labels = HomePageLabel::with('translations')->where('is_active', 1)->orderBy('order_by')->get();
            $request->request->add(['type'=>Session::get('vendorType')??'delivery','noTinJson'=>1] );
            $homePageData = $this->postHomePageData($request);

            $home_page_labels = $home_page_labels->map(function($da) use ($homePageData, $navCategories) {
                if($da->slug!='pickup_delivery' && $da->slug!='dynamic_page' ){
                    $da[$da->slug] = $homePageData[$da->slug] ?? '';
                }
                if( $da->slug == 'nav_categories'  ){
                    // dd($da->slug);
                    $da['nav_categories'] = $navCategories ?? '';
                   // dd($da[$da->slug]);
                }

                return $da;

            });

            // dd($homePageData);
            $only_cab_booking = OnboardSetting::where('key_value', 'home_page_cab_booking')->count();
            if ($only_cab_booking == 1)
                return Redirect::route('categoryDetail', 'cabservice');

            $home_page_pickup_labels = CabBookingLayout::with('translations')->web();
           
            $home_page_pickup_labels = $home_page_pickup_labels->where('is_active', 1)->where('for_no_product_found_html',0)->orderBy('order_by')->get();

            $set_template = WebStylingOption::where('web_styling_id', 1)->where('is_selected', 1)->first();

            $for_no_product_found_html = CabBookingLayout::with('translations')->web()->where('is_active', 1)->where('for_no_product_found_html',1)->orderBy('order_by')->get();
            $enable_layout = CabBookingLayout::where('is_active',1)->web()->orderBy('order_by','asc')->pluck('slug')->toArray();
            $categories = [];
            if(isset($set_template)  && ($set_template->template_id == 8 || $set_template->template_id == 9)){
                $categories = Category::with('translation_one')->select('id', 'icon', 'slug', 'type_id', 'is_visible', 'status', 'is_core', 'vendor_id', 'can_add_products', 'parent_id')
                ->where('id', '>', '1')
                // ->where('is_core', 1)
                ->whereNotIn('type_id', [4, 5])
                ->where(function ($q) {
                    $q->whereNull('vendor_id');
                })->orderBy('position', 'asc')
                ->orderBy('id', 'asc')
                ->where('status', 1)
                ->orderBy('parent_id', 'asc')->get();
            }
            // dd($categories);

            // $last_mile = $this->checkIfLastMileDeliveryOn();
            $view_page ="home-template-one";
            if (isset($set_template)  && $set_template->template_id == 1){
                // $view_page = 'home-template-one';
                $view_page = 'home-template-test-one';
            }elseif(isset($set_template)  && $set_template->template_id == 2){
                // $view_page = "home-template-two";
                $view_page = 'home-template-test-two';
            }elseif(isset($set_template)  && $set_template->template_id == 3){
                // $view_page = "home-template-three";
                $view_page = 'home-template-test-three';
            }elseif(isset($set_template)  && $set_template->template_id == 4){
                // $view_page = "home-template-four";
                $view_page = 'home-template-test-four';
            }elseif(isset($set_template)  && $set_template->template_id == 5){
                $view_page = "home-template-five";
            }elseif(isset($set_template)  && $set_template->template_id == 6){
                // $view_page = "home-template-six";
                $view_page = "home-template-test-six";
            }
            elseif(isset($set_template)  && $set_template->template_id == 8){
                // $view_page = "home-template-six";
                $view_page = "home-template-test-eight";
            }
            elseif(isset($set_template)  && $set_template->template_id == 9){
                $view_page = "home-template-test-nine";
            }
            // dd($homePageData);
            //pr($set_template->toArray());exit();
            //pr(Session::get('latitude'));
            return view('frontend.'.$view_page)->with(['categories' => $categories,'home' => $home,  'count' => $count, 'for_no_product_found_html' => $for_no_product_found_html,'homePagePickupLabels' => $home_page_pickup_labels, 'homePageLabels' => $home_page_labels, 'clientPreferences' => $clientPreferences, 'banners' => $banners,'mobile_banners'=>$mobile_banners, 'navCategories' => $navCategories, 'selectedAddress' => $selectedAddress, 'latitude' => $latitude, 'longitude' => $longitude,'enable_layout'=>$enable_layout,'homePageData'=>$homePageData]);

        } catch (Exception $e) {
            pr($e->getCode());
            die;
        }
    }


    /**
     * setHyperlocalAddress
     *
     * @param  mixed $lat
     * @param  mixed $long
     * @param  mixed $address
     * @return void
     */
    public function setHyperlocalAddress(Request $request)
    {
        $latitude        =  $request->latitude;
        $longitude       =  $request->longitude;
        $selectedAddress =  $request->address;
        $selectedPlaceId =  $request->place_id;
        //if ((!empty($latitude)) && (!empty($longitude)) && (!empty($selectedAddress))) {
            Session::put('latitude', $latitude);
            Session::put('longitude', $longitude);
            if($selectedAddress)
            Session::put('selectedAddress', $selectedAddress);
            if($selectedPlaceId)
            Session::put('selectedPlaceId', $selectedPlaceId);
       // }
        return redirect()->route('userHome');
    }
    /**
     * postHomePageData
     *
     * @param  mixed $request
     * @return void
     */
    public function postHomePageData(Request $request)
    {
        $additionalPreference = getAdditionalPreference(['is_token_currency_enable', 'token_currency','is_long_term_service']);
        $vendor_ids = [];
        $new_products = [];
        $feature_products = [];
        $on_sale_products = [];
        $long_term_service_products = [];
        $recently_viewed = [];
        $set_template = WebStylingOption::where('web_styling_id', 1)->where('is_selected', 1)->first();
        $p_dim = '260/180';
        if (isset($set_template)  && $set_template->template_id == 3){
            $p_dim = '300/350';
        }elseif(isset($set_template)  && $set_template->template_id == 2){
            $p_dim = '260/180';
        }
        $latitude = Session::get('latitude');
        $longitude = Session::get('longitude');

        //pr($latitude);
        if($request->has('latitude') ){
            $latitude = $request->latitude;
            Session::put('latitude', $latitude);
        }
        if ($request->has('longitude')) {
            $longitude = $request->longitude;
            Session::put('longitude', $longitude);
        }
        $selectedAddress = ($request->has('selectedAddress')) ? Session::put('selectedAddress', $request->selectedAddress) : Session::get('selectedAddress');
        $selectedPlaceId = ($request->has('selectedPlaceId')) ? Session::put('selectedPlaceId', $request->selectedPlaceId) : Session::get('selectedPlaceId');
        $preferences = !empty(Session::get('preferences')) ? (object)Session::get('preferences'): ClientPreference::first();
        $currency_id = Session::get('customerCurrency');
        $language_id = Session::get('customerLanguage');

        $currency_id = $this->setCurrencyInSesion();

        $featured_products_title = CabBookingLayoutTranslation::where('language_id',$language_id)->whereHas('layout',function($q){$q->where('slug','featured_products');})->value('title');

        $vendors_title = CabBookingLayoutTranslation::where('language_id',$language_id)->whereHas('layout',function($q){$q->where('slug','vendors');})->value('title');

        $new_products_title = CabBookingLayoutTranslation::where('language_id',$language_id)->whereHas('layout',function($q){$q->where('slug','new_products');})->value('title');

        $on_sale_title = CabBookingLayoutTranslation::where('language_id',$language_id)->whereHas('layout',function($q){$q->where('slug','on_sale');})->value('title');

        $brands_title = CabBookingLayoutTranslation::where('language_id',$language_id)->whereHas('layout',function($q){$q->where('slug','brands');})->value('title');

        $best_sellers_title = CabBookingLayoutTranslation::where('language_id',$language_id)->whereHas('layout',function($q){$q->where('slug','best_sellers');})->value('title');

        $trending_vendors_title = CabBookingLayoutTranslation::where('language_id',$language_id)->whereHas('layout',function($q){$q->where('slug','trending');})->value('title');

        $recent_orders_title = CabBookingLayoutTranslation::where('language_id',$language_id)->whereHas('layout',function($q){$q->where('slug','recent_orders');})->value('title');

        $enable_layout = CabBookingLayout::where('is_active',1)->web()->pluck('slug')->toArray();
        $home_page_labels = HomePageLabel::with('translations')->get();
        if (in_array('brands', $enable_layout)) {     # if enable brands section in
            $brands = Brand::select('id', 'image', 'title')->with(['translation' => function ($q) use ($language_id) {
                $q->where('language_id', $language_id);
            }])->where('status', '!=', $this->field_status)->orderBy('position', 'asc')->get();
            foreach ($brands as $brand) {
                $brand->redirect_url = route('brandDetail', $brand->id);
                $brand->translation_title = $brand->translation->first() ? $brand->translation->first()->title : $brand->title;
            }
        }else{
            $brands = [];
        }

        $categories = Category::with('translation_one')->select('id', 'icon', 'slug', 'type_id', 'is_visible', 'status', 'is_core', 'vendor_id', 'can_add_products', 'parent_id')
                ->where('id', '>', '1')
                // ->where('is_core', 1)
                ->whereNotIn('type_id', [4, 5])
                ->where(function ($q) {
                    $q->whereNull('vendor_id');
                })->orderBy('position', 'asc')
                ->orderBy('id', 'asc')
                ->where('status', 1)
                ->orderBy('parent_id', 'asc')->get();


        Session::forget('vendorType');
        Session::put('vendorType', $request->type);
        $vendors = Vendor::with('products')->with('slot.day', 'slotDate')->select('id', 'name', 'banner', 'address', 'order_pre_time', 'order_min_amount', 'logo', 'slug', 'latitude', 'longitude','show_slot')->where($request->type, 1);
        if ($preferences) {
            if ((empty($latitude)) && (empty($longitude)) && (empty($selectedAddress))) {
                $selectedAddress = $preferences->Default_location_name;
                $latitude = $preferences->Default_latitude??null;
                $longitude = $preferences->Default_longitude??null;
                Session::put('latitude', $latitude);
                Session::put('longitude', $longitude);
                Session::put('selectedAddress', $selectedAddress);
            } else {
                if ($preferences && ($latitude == $preferences->Default_latitude) && ($longitude == $preferences->Default_longitude)) {
                    Session::put('selectedAddress', $preferences->Default_location_name);
                }
            }


            if (($preferences->is_hyperlocal == 1) && ($latitude) && ($longitude)) {
                if (!empty($latitude) && !empty($longitude)) {
                    $vendors = $vendors->whereHas('serviceArea', function ($query) use ($latitude, $longitude) {
                        $query->select('vendor_id')
                        ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $latitude . " " . $longitude . ")'))");
                    });                                                                     
                }
            }
        }

        /**
         * put a limit to get vendors.
         */
        $vendors = $vendors->where('status', 1)
                    ->inRandomOrder()
                    ->limit(10)->get();
        // dd($vendors);

        foreach ($vendors as $key => $value) {
            $vendor_ids[] = $value->id;
            // $value->vendorRating = $this->vendorRating($value->products);

            // get or update rating
            $value->vendorRating = $this->getVendorRating($value->id);

            // $value->name = Str::limit($value->name, 15, '..');
            if (($preferences) && ($preferences->is_hyperlocal == 1)) {
                $value = $this->getVendorDistanceWithTime($latitude, $longitude, $value, $preferences);
            }
            $vendorCategories = VendorCategory::with('category.translation_one')->where('vendor_id', $value->id)->where('status', 1)->get();
            $categoriesList = '';
            foreach ($vendorCategories as $key => $category) {
                if ($category->category) {
                    $categoriesList = $categoriesList . @$category->category->translation_one->name ?? '';
                    if ($key !=  $vendorCategories->count() - 1) {
                        $categoriesList = $categoriesList . ', ';
                    }
                }
            }
            $value->categoriesList = $categoriesList;
            $value->type_title = $categoriesList;

            $value->is_vendor_closed = 0;
            if($value->show_slot == 0){
                if( ($value->slotDate->isEmpty()) && ($value->slot->isEmpty()) ){
                    $value->is_vendor_closed = 1;
                }else{
                    $value->is_vendor_closed = 0;
                    if($value->slotDate->isNotEmpty()){
                        if($value->slotDate->first()->start_time!='' && $value->slotDate->first()->end_time!=''){
                            $value->opening_time  = date('g:i A',strtotime($value->slotDate->first()->start_time));
                            $value->closing_time = date('g:i A',strtotime($value->slotDate->first()->end_time));
                        }

                    }elseif($value->slot->isNotEmpty()){
                        \Log::info( date('g:i A',strtotime($value->slot->first()->end_time)));
                        if($value->slot->first()->start_time && $value->slot->first()->end_time){
                            $value->opening_time = date('g:i A',strtotime($value->slot->first()->start_time));
                            $value->closing_time = date('g:i A',strtotime($value->slot->first()->end_time));
                        }
                    }
                }
            }
        }
        if (($preferences) && ($preferences->is_hyperlocal == 1)) {
            $vendors = $vendors->sortBy('lineOfSightDistance')->values()->all();
        }
        $now = Carbon::now()->toDateTimeString();
        $subscribed_vendors_for_trending = SubscriptionInvoicesVendor::with('features')->whereHas('features', function ($query) {
            $query->where(['subscription_invoice_features_vendor.feature_id' => 1]);
        })
            ->select('id', 'vendor_id', 'subscription_id')
            ->where('end_date', '>=', $now)
            ->pluck('vendor_id')->toArray();

        if (($latitude) && ($longitude)) {

            Session::put('vendors', $vendor_ids);
        }

        $trendingVendors = Vendor::with('slot.day', 'slotDate')->whereIn('id', $subscribed_vendors_for_trending)->where('status', 1)->inRandomOrder();

        // add hyperlocal check to get vendors
        if (($preferences->is_hyperlocal == 1) && ($latitude) && ($longitude)) {

            if (!empty($latitude) && !empty($longitude)) {
                $trendingVendors = $trendingVendors->whereHas('serviceArea', function ($query) use ($latitude, $longitude) {
                    $query->select('vendor_id')
                    ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $latitude . " " . $longitude . ")'))");
                });
            }
        }

        $trendingVendors = $trendingVendors->get();

        if ((!empty($trendingVendors) && count($trendingVendors) > 0)) {
            foreach ($trendingVendors as $key => $value) {
                $value->tag_title = $trending_vendors_title??'0';
                $value->vendorRating = $this->vendorRating($value->products);
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
                if($value->show_slot == 0){
                    if( ($value->slotDate->isEmpty()) && ($value->slot->isEmpty()) ){
                        $value->is_vendor_closed = 1;
                    }else{
                        $value->is_vendor_closed = 0;
                        if($value->slotDate->isNotEmpty()){
                            $value->opening_time = Carbon::parse($value->slotDate->first()->start_time)->format('g:i A');
                            $value->closing_time = Carbon::parse($value->slotDate->first()->end_time)->format('g:i A');
                        }elseif($value->slot->isNotEmpty()){
                            $value->opening_time = Carbon::parse($value->slot->first()->start_time)->format('g:i A');
                            $value->closing_time = Carbon::parse($value->slot->first()->end_time)->format('g:i A');
                        }
                    }
                }
            }
        }
        if (($preferences) && ($preferences->is_hyperlocal == 1)) {
            $trendingVendors = $trendingVendors->sortBy('lineOfSightDistance')->values()->all();
        }
        
        //get Most Selling Vendors
        $mostSellingVendors = $this->getMostSellingVendors($preferences, $vendor_ids);
        $on_sale_product_details = $this->vendorProducts($vendor_ids, $language_id, 'USD', '', $request->type);
        $new_product_details = $this->vendorProducts($vendor_ids, $language_id, $currency_id, 'is_new', $request->type);
        $feature_product_details = $this->vendorProducts($vendor_ids, $language_id, $currency_id, 'is_featured', $request->type);

        foreach ($new_product_details as  $new_product_detail) {
            $multiply = $new_product_detail->variant->first()->multiplier?? 1;
            $title = $new_product_detail->translation->first() ? $new_product_detail->translation->first()->title : $new_product_detail->sku;
            $image_url = $new_product_detail->media->first() ? ( !empty($new_product_detail->media->first()->image ) ?( $new_product_detail->media->first()->image->path['proxy_url'] . $p_dim . $new_product_detail->media->first()->image->path['image_path']) : $this->loadDefaultImage() ): $this->loadDefaultImage();
            $user_id = Auth::user()->id??'';
            $product_id = $new_product_detail->id;
            $is_inwishlist_btn = 0;
            $userWishlistProd = UserWishlist::where(['user_id' => $user_id, 'product_id' => $product_id])->first();
            if(@$userWishlistProd){
                $is_inwishlist_btn = 1;
            }
            $new_products[] = array(
                'tag_title' => $new_products_title??0,
                'image_url' => $image_url,
                'id' => $new_product_detail->id,
                'sku' => $new_product_detail->sku,
                'updated_at' => $new_product_detail->updated_at,
                'is_inwishlist_btn' => $is_inwishlist_btn,
                'title' => Str::limit($title, 18, '..'),
                'url_slug' => $new_product_detail->url_slug,
                'averageRating' => number_format($new_product_detail->averageRating, 1, '.', ''),
                'inquiry_only' => $new_product_detail->inquiry_only,
                'vendor_name' => $new_product_detail->vendor ? $new_product_detail->vendor->name : '',
                'vendor' => $new_product_detail->vendor,
                'ProductAttribute' => $new_product_detail->ProductAttribute,
                'price_numeric' =>@$new_product_detail->variant->first()->price??0 * $multiply,
                'compare_price' =>@$new_product_detail->variant->first()->compare_at_price??0 * $multiply,
                'compare_at_price' =>@$additionalPreference['is_token_currency_enable'] ? "<i class='fa fa-money' aria-hidden='true'></i> ".getInToken(@$new_product_detail->variant->first()->compare_at_price??0 * $multiply): Session::get('currencySymbol') . ' ' . (decimal_format(@$new_product_detail->variant->first()->compare_at_price??0 * $multiply,',')),
                'price' => @$additionalPreference['is_token_currency_enable'] ? "<i class='fa fa-money' aria-hidden='true'></i> ".getInToken(@$new_product_detail->variant->first()->price??0 * $multiply): Session::get('currencySymbol') . ' ' . (decimal_format(@$new_product_detail->variant->first()->price??0 * $multiply,',')),
                'category' => (@$new_product_detail->category->categoryDetail->translation) ? @$new_product_detail->category->categoryDetail->translation->first()->name : @$new_product_detail->category->categoryDetail->slug
            );
        }
        foreach ($feature_product_details as  $feature_product_detail) {
            $multiply = $feature_product_detail->variant->first()->multiplier ?? 1;
            $title = $feature_product_detail->translation->first() ? $feature_product_detail->translation->first()->title : $feature_product_detail->sku;
            $image_url = $feature_product_detail->media->first() ? $feature_product_detail->media->first()->image->path['proxy_url'] . $p_dim . $feature_product_detail->media->first()->image->path['image_path'] : $this->loadDefaultImage();
            $user_id = Auth::user()->id??'';
            $product_id = $feature_product_detail->id;
            $is_inwishlist_btn = 0;
            $userWishlistProd = UserWishlist::where(['user_id' => $user_id, 'product_id' => $product_id])->first();
            if(@$userWishlistProd){
                $is_inwishlist_btn = 1;
            }
            $feature_products[] = array(
                'tag_title' => $featured_products_title??'0',
                'image_url' => $image_url,
                'id' => $feature_product_detail->id,
                'sku' => $feature_product_detail->sku,
                'updated_at' => $feature_product_detail->updated_at,
                'is_inwishlist_btn' => $is_inwishlist_btn,
                'title' => Str::limit($title, 18, '..'),
                'url_slug' => $feature_product_detail->url_slug,
                'averageRating' => number_format($feature_product_detail->averageRating, 1, '.', ''),
                'inquiry_only' => $feature_product_detail->inquiry_only,
                'vendor_name' => $feature_product_detail->vendor ? $feature_product_detail->vendor->name : '',
                'vendor' => $feature_product_detail->vendor,
                'ProductAttribute' => $feature_product_detail->ProductAttribute,
                'price_numeric' =>@$feature_product_detail->variant->first()->price??0 * $multiply,
                'compare_price' =>@$feature_product_detail->variant->first()->compare_at_price??0 * $multiply,
                'price' => @$additionalPreference['is_token_currency_enable'] ? "<i class='fa fa-money' aria-hidden='true'></i> ".getInToken(@$feature_product_detail->variant->first()->price??0 * $multiply): Session::get('currencySymbol') . ' ' . (decimal_format(@$feature_product_detail->variant->first()->price * $multiply,',')),
                'category' => (@$feature_product_detail->category->categoryDetail->translation) ? @$feature_product_detail->category->categoryDetail->translation->first()->name : @$feature_product_detail->category->categoryDetail->slug
            );
        }
        
        foreach ($on_sale_product_details as  $on_sale_product_detail) {
            $multiply = $on_sale_product_detail->variant->first()->multiplier ?? 1;
            $title = $on_sale_product_detail->translation->first() ? $on_sale_product_detail->translation->first()->title : $on_sale_product_detail->sku;
            $image_url = $on_sale_product_detail->media->first() ? $on_sale_product_detail->media->first()->image->path['proxy_url'] . $p_dim . $on_sale_product_detail->media->first()->image->path['image_path'] : $this->loadDefaultImage();
            $user_id = Auth::user()->id??'';
            $product_id = $on_sale_product_detail->id;
            $is_inwishlist_btn = 0;
            $userWishlistProd = UserWishlist::where(['user_id' => $user_id, 'product_id' => $product_id])->first();
            if(@$userWishlistProd){
                $is_inwishlist_btn = 1;
            }
            $on_sale_products[] = array(
                'tag_title' => $on_sale_title??'0',
                'image_url' => $image_url,
                'id' => $on_sale_product_detail->id,
                'sku' => $on_sale_product_detail->sku,
                'updated_at' => $on_sale_product_detail->updated_at,
                'is_inwishlist_btn' => $is_inwishlist_btn,
                'title' => Str::limit($title, 18, '..'),
                'url_slug' => $on_sale_product_detail->url_slug,
                'averageRating' => number_format($on_sale_product_detail->averageRating, 1, '.', ''),
                'inquiry_only' => $on_sale_product_detail->inquiry_only,
                'vendor_name' => $on_sale_product_detail->vendor ? $on_sale_product_detail->vendor->name : '',
                'vendor' => $on_sale_product_detail->vendor,
                'ProductAttribute' => $on_sale_product_detail->ProductAttribute,
                'price' => @$additionalPreference['is_token_currency_enable'] ? "<i class='fa fa-money' aria-hidden='true'></i> ".getInToken(@$on_sale_product_detail->variant->first()->price??0 * $multiply): Session::get('currencySymbol') . ' ' . (decimal_format(@$on_sale_product_detail->variant->first()->price??0 * $multiply,',')),
                'compare_at_price' => @$additionalPreference['is_token_currency_enable'] ? "<i class='fa fa-money' aria-hidden='true'></i> ".getInToken(@$on_sale_product_detail->variant->first()->compare_at_price??0 * $multiply): Session::get('currencySymbol') . ' ' . (decimal_format(@$on_sale_product_detail->variant->first()->compare_at_price??0 * $multiply,',')),
                'compare_price' => @$on_sale_product_detail->variant->first()->compare_at_price??0 * $multiply,
                'price_numeric' =>@$on_sale_product_detail->variant->first()->price??0 * $multiply,
                'category' => (!empty($on_sale_product_detail->category) && !empty($on_sale_product_detail->category->categoryDetail) 
                && !empty($on_sale_product_detail->category->categoryDetail->translation)) ? ( $on_sale_product_detail->category->categoryDetail->translation->first()->name ?? $on_sale_product_detail->category->categoryDetail->slug): $on_sale_product_detail->category->categoryDetail->slug??''
            );
        }
        $top_rated_products = '';

         //get long term service 
        $long_term_service_products =[];
        if( @$additionalPreference['is_long_term_service'] == 1){
            $long_term_service_products = $this->longTermServiceProducts($vendor_ids, $language_id, $currency_id,'', $request->type,$p_dim);
        }
          
        if($this->checkTemplateForAction(8)){
            $recently_viewed = $this->productvendorProducts($vendor_ids, $language_id, $currency_id, '', $request->type,$p_dim);
            $spot_light_products = $this->getSpotLight($preferences, $vendor_ids, $language_id, $currency_id, $p_dim); // get spotlight product i.e. max discounted products

            $single_category_product_ids = $this->getSingleCategoryProducts(); // get single selected category's products
            $single_category_products = $this->getProducts($preferences, $vendor_ids, $language_id, $currency_id, $p_dim, $single_category_product_ids);
            // dd($single_category_products);
            $selected_product_ids = $this->getSelectedProducts(); // get single selected category's products
            $selected_products = $this->getProducts($preferences, $vendor_ids, $language_id, $currency_id, $p_dim, $selected_product_ids);

            $popular_product_ids = $this->getMostPopularProducts();  // get selected products to display 
            $popular_products = $this->getProducts($preferences, $vendor_ids, $language_id, $currency_id, $p_dim, $popular_product_ids);

            $top_rated_products_ids = $this->getTopRatedProducts();  // get selected products to display 
            $top_rated_products = $this->getProducts($preferences, $vendor_ids, $language_id, $currency_id, $p_dim, $top_rated_products_ids);
        }
        /**  Recent order */
            $activeOrders = [];
            $user = Auth::user();

            if ($user) {
                    $activeOrders = Order::with([
                        'vendors' => function ($q) {
                            $q->where('order_status_option_id', '!=', 6);
                        },
                        'vendors.dineInTable.translations' => function ($qry) use ($language_id) {
                            $qry->where('language_id', $language_id);
                        }, 'vendors.dineInTable.category', 'vendors.products', 'vendors.products.media.image', 'vendors.products.pvariant.media.pimage.image', 'user', 'address'
                    ])->whereHas('vendors', function ($q) {
                        $q->where('order_status_option_id', '!=', 6);
                    })
                        ->where('orders.user_id', $user->id)->take(10)
                        ->orderBy('orders.id', 'DESC')->get();
                        foreach ($activeOrders as $order) {
                            foreach ($order->vendors as $vendor) {
                                // dd($vendor->toArray());
                                $vendor->tag_title = $vendor_title??'0';
                                $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order->id)->where('vendor_id', $vendor->vendor_id)->orderBy('id', 'DESC')->first();
                                $vendor->order_status = $vendor_order_status ? strtolower($vendor_order_status->OrderStatusOption->title) : '';
                                foreach ($vendor->products as $product) {
                                    if (isset($product->pvariant) && $product->pvariant->media->isNotEmpty()) {
                                        $product->image_url = $product->pvariant->media->first()->pimage->image->path['image_fit'] . '74/100' . $product->pvariant->media->first()->pimage->image->path['image_path'];
                                    } elseif ($product->media->isNotEmpty() && isset($product->media->first()->image)) {
                                        $product->image_url = $product->media->first()->image->path['image_fit'] . '74/100' . $product->media->first()->image->path['image_path'];
                                    } else {
                                        $product->image_url = ($product->image) ? $product->image['image_fit'] . '74/100' . $product->image['image_path'] : '';
                                    }
                                    $product->pricedoller_compare = 1;
                                }
                                if ($vendor->delivery_fee > 0) {
                                    $order_pre_time = ($vendor->order_pre_time > 0) ? $vendor->order_pre_time : 0;
                                    $user_to_vendor_time = ($vendor->user_to_vendor_time > 0) ? $vendor->user_to_vendor_time : 0;
                                    $ETA = $order_pre_time + $user_to_vendor_time;
                                    $vendor->ETA = ($ETA > 0) ? $this->formattedOrderETA($ETA, $vendor->created_at, $order->scheduled_date_time) : dateTimeInUserTimeZone($vendor->created_at, $user->timezone);
                                }
                                if ($vendor->dineInTable) {
                                    $vendor->dineInTableName = $vendor->dineInTable->translations->first() ? $vendor->dineInTable->translations->first()->name : '';
                                    $vendor->dineInTableCapacity = $vendor->dineInTable->seating_number;
                                    $vendor->dineInTableCategory = $vendor->dineInTable->category->first() ? $vendor->dineInTable->category->first()->title : '';
                                }
                            }
                            $order->converted_scheduled_date_time = dateTimeInUserTimeZone($order->scheduled_date_time, $user->timezone);
                        }
            }
        /**  Recent order end */

        /**  Get cities */
        if($preferences->is_hyperlocal==1){
            $this->getCities($language_id);
        }
        /**  Get cities end */


        /** Respose data */

        $data = [
            'brands' => $brands,
            'vendors' => $vendors,
            'new_products' => $new_products,
            'homePageLabels' => $home_page_labels,
            'feature_products' => $feature_products,
            'on_sale_products' => $on_sale_products,
            'trending_vendors' => (!empty($trendingVendors) && count($trendingVendors) > 0)?$trendingVendors:$mostSellingVendors,
            'active_orders' => $activeOrders,
            
        ];
       
        if($request->has('noTinJson') && $request->noTinJson == 1){
            $data = [
                'brands' => $brands,
                'vendors' => $vendors,
                'new_products' => $new_products,
                'top_rated'       => $top_rated_products ?? '',
                'recently_viewed' => $recently_viewed,
                'homePageLabels' => $home_page_labels,
                'featured_products' => $feature_products,
                'on_sale' => $on_sale_products,
                'cities' => $this->cities,
                'long_term_service' => $long_term_service_products,
                'trending_vendors' => (!empty($trendingVendors) && count($trendingVendors) > 0)?$trendingVendors:[],
                'best_sellers'     => (!empty($mostSellingVendors) && count($mostSellingVendors) > 0)?$mostSellingVendors:[],
                'spotlight_deals'  => (!empty($spot_light_products) && count($spot_light_products) > 0)?$spot_light_products:[],
                'single_category_products'  => (!empty($single_category_products) && count($single_category_products) > 0)?$single_category_products:[],
                'selected_products'  => (!empty($selected_products) && count($selected_products) > 0)?$selected_products:[],
                'most_popular_products'  => (!empty($popular_products) && count($popular_products) > 0)?$popular_products:[],
                'recent_orders' => $activeOrders,
            ];
            // dd( $data);
            return $data ;
        }



        return $this->successResponse($data);
    }

    /**
     * getCities
     *
     * @param  mixed $language_id
     * @return $cities
     */
    public function getCities($language_id){
        $this->cities =  VendorCities::with(['translations'=> function ($q) use($language_id) {
                            $q->where('language_id', $language_id);
                        }])->where(function ($q)  {
                            $q->where('latitude','!=', null);
                            $q->where('longitude','!=', null);
                        })->get();

        $this->cities = $this->cities->map(function($da) {
            $da->title = $da->translations->first() ? $da->translations->first()->name : $da->slug ;
            unset($da->translations);
            return $da;
         });
         return $this->cities;
    }

    public function vendorProducts($venderIds, $langId, $currency = 'USD', $where = '', $type)
    {
        $products = Product::byProductCategoryServiceType($type)->byProductWhereCheck()->with([
            'category.categoryDetail.translation' => function ($q) use ($langId) {
                $q->where('category_translations.language_id', $langId);
            },
            'vendor', 'ProductAttribute' => function ($q) {
                $q->where('key_name', 'Location');
            },
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
        ]);
        if ($where !== '') {
            $products = $products->where($where, 1);
        }
        if(checkColumnExists('products','is_long_term_service')){
            $products = $products->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating', 'inquiry_only','updated_at', 'id','is_long_term_service');
        }else{
            $products = $products->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating', 'inquiry_only','updated_at', 'id');
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
                    $q->where($type, 1);
                });
                if ($where == 'is_featured') {
                         $products = $products->take(20);  
                    }else{
                        $products = $products->take(10);  
                    }
                $products = $products->inRandomOrder()->get();
                //get 20 product in template-8
        if (!empty($products)) {
            foreach ($products as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant[$k]->multiplier = Session::get('currencyMultiplier');
                }
            }
        }
        //  dd($products);
       return $products;
        //pr( $products->toArray());
    }

    // public function longTermServiceProducts($venderIds, $langId, $currency = 'USD', $where = '', $type,$p_dim ='260/100' )
    // {
       
    //     $products = Product::byLongTermProductCategoryServiceType($type)->byProductLongTerm()->with([
    //         'vendor','LongTermProducts.product',
    //         'media' => function ($q) {
    //             $q->groupBy('product_id');
    //         }, 'media.image',
    //         'translation' => function ($q) use ($langId) {
    //             $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
    //         },
    //         'variant' => function ($q) use ($langId) {
    //             $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
    //             $q->groupBy('product_id');
    //         },
    //     ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating', 'inquiry_only','is_long_term_service')
    //     ->whereHas('LongTermProducts.product', function($q){$q->where('is_live',1); });
       
    //     if ($where !== '') {
    //         $products = $products->where($where, 1);
    //     }
     
    //    //$venderIds = ['8'];
    //     $products = $products->whereHas('vendor', function($q) use ($type,$venderIds){
    //                 $q->where('status',1);
    //                 $q->whereIn('id',$venderIds);
    //                 $q->where($type, 1);
    //             })->take(10)->inRandomOrder()->get();
     
    //     $return = [];
    //     if (!empty($products)) {
    //         foreach ($products as $key => $value) {
    //             $multiply = Session::get('currencyMultiplier') ?? 1;
    //             $title = $value->translation->first() ? $value->translation->first()->title : $value->sku;
    //             $image_url = $value->media->first() ? $value->media->first()->image->path['proxy_url'] . $p_dim . $value->media->first()->image->path['image_path'] : $this->loadDefaultImage();
    //             $return[] = array(
    //                 'tag_title' => $title??'0',
    //                 'image_url' => $image_url,
    //                 'sku' => $value->sku,
    //                 'title' => Str::limit($title, 18, '..'),
    //                 'url_slug' => $value->url_slug,
    //                 'averageRating' => number_format($value->averageRating, 1, '.', ''),
    //                 'inquiry_only' => $value->inquiry_only,
    //                 'vendor_name' => $value->vendor ? $value->vendor->name : '',
    //                 'vendor' => $value->vendor,
    //                 'price' => Session::get('currencySymbol') . ' ' . (decimal_format(@$value->variant->first()->price * $multiply,',')),
    //                 'category' => ''
    //             );
    //         }
    //     }
    //    return $return;
        
    // }

    public function changePrimaryData(Request $request)
    {
        if ($request->has('type') && $request->type == 'language') {
            $clientLanguage = ClientLanguage::where('language_id', $request->value1)->first();
            if ($clientLanguage) {
                $lang_detail = Language::where('id', $request->value1)->first();
                App::setLocale($lang_detail->sort_code);
                session()->put('locale', $lang_detail->sort_code);
                Session::put('customerLanguage', $request->value1);
            }
        }
        if ($request->has('type') && $request->type == 'currency') {
            $clientCurrency = ClientCurrency::where('currency_id', $request->value1)->first();
            if ($clientCurrency) {
                $currency_detail = Currency::where('id', $request->value1)->first();
                Session::put('currencySymbol', $request->value2);
                Session::put('customerCurrency', $request->value1);
                Session::put('iso_code', $currency_detail->iso_code);
                Session::put('currencyMultiplier', $clientCurrency->doller_compare);
            }
        }
        $data['customerLanguage'] = Session::get('customerLanguage');
        $data['customerCurrency'] = Session::get('customerCurrency');
        $data['currencySymbol'] = Session::get('currencySymbol');
        return response()->json(['status' => 'success', 'message' => 'Saved Successfully!', 'data' => $data]);
    }

    public function changePaginate(Request $request)
    {
        $perPage = 12;
        if ($request->has('itemPerPage')) {
            $perPage = $request->itemPerPage;
        }
        Session::put('cus_paginate', $perPage);
        return response()->json(['status' => 'success', 'message' => 'Saved Successfully!', 'data' => $perPage]);
    }

    public function getClientPreferences(Request $request)
    {
        $clientPreferences = ClientPreference::first();
        if ($clientPreferences) {
            $dinein_check = $clientPreferences->dinein_check;
            $delivery_check = $clientPreferences->delivery_check;
            $takeaway_check = $clientPreferences->takeaway_check;
            $age_restriction = $clientPreferences->age_restriction;
            return response()->json(["age_restriction" => $age_restriction, "dinein_check" => $dinein_check, "delivery_check" => $delivery_check, "takeaway_check" => $takeaway_check]);
        }
    }


    /////    new home page
    public function indexTemplateOne(Request $request)
    {
        try {
            $home = array();
            $vendor_ids = array();
            if ($request->has('ref')) {
                session(['referrer' => $request->query('ref')]);
            }
            $latitude = Session::get('latitude');
            $longitude = Session::get('longitude');
            $curId = Session::get('customerCurrency');
            $preferences = Session::get('preferences');
            $langId = Session::get('customerLanguage');
            $client_config = Session::get('client_config');
            $selectedAddress = Session::get('selectedAddress');
            $navCategories = $this->categoryNav($langId);
            Session::put('navCategories', $navCategories);
            $clientPreferences = ClientPreference::first();
            $count = 0;
            if ($clientPreferences) {
                if ($clientPreferences->dinein_check == 1) {
                    $count++;
                }
                if ($clientPreferences->takeaway_check == 1) {
                    $count++;
                }
                if ($clientPreferences->delivery_check == 1) {
                    $count++;
                }
            }
            // if ($preferences) {
            //     if ((empty($latitude)) && (empty($longitude)) && (empty($selectedAddress))) {
            //         $selectedAddress = $preferences->Default_location_name;
            //         $latitude = $preferences->Default_latitude;
            //         $longitude = $preferences->Default_longitude;
            //         Session::put('latitude', $latitude);
            //         Session::put('longitude', $longitude);
            //         Session::put('selectedAddress', $selectedAddress);
            //     }
            // }
            $banners = Banner::where('status', 1)->where('validity_on', 1)
                ->where(function ($q) {
                    $q->whereNull('start_date_time')->orWhere(function ($q2) {
                        $q2->whereDate('start_date_time', '<=', Carbon::now())
                            ->whereDate('end_date_time', '>=', Carbon::now());
                    });
                })->orderBy('sorting', 'asc')->with('category')->with('vendor')->get();
            $home_page_labels = HomePageLabel::with('translations')->where('is_active', 1)->orderBy('order_by')->get();

            $only_cab_booking = OnboardSetting::where('key_value', 'home_page_cab_booking')->count();
            if ($only_cab_booking == 1)
                return Redirect::route('categoryDetail', 'cabservice');
            $home_page_pickup_labels = CabBookingLayout::with(['translations' => function ($q) use ($langId) {
                $q->where('language_id', $langId);
            }])->where('is_active', 1)->orderBy('order_by')->where('for_no_product_found_html',0)->get();
            $for_no_product_found_html = CabBookingLayout::with('translations')->where('is_active', 1)->where('for_no_product_found_html',1)->orderBy('order_by')->get();

            $for_no_product_found_html = CabBookingLayout::with('translations')->where('is_active', 1)->where('for_no_product_found_html',1)->orderBy('order_by')->get();

            return view('frontend.home-template-one')->with(['home' => $home, 'count' => $count, 'for_no_product_found_html' => $for_no_product_found_html,'homePagePickupLabels' => $home_page_pickup_labels, 'homePageLabels' => $home_page_labels, 'clientPreferences' => $clientPreferences, 'banners' => $banners, 'navCategories' => $navCategories, 'selectedAddress' => $selectedAddress, 'latitude' => $latitude, 'longitude' => $longitude]);
        } catch (Exception $e) {
            pr($e->getCode());
            die;
        }
    }

    # category menu

    public function homePageDataCategoryMenu(Request $request)
    {
        if ($request->has('latitude')) {
            $latitude = $request->latitude;
            Session::put('latitude', $latitude);
        } else {
            $latitude = Session::get('latitude');
        }
        if ($request->has('longitude')) {
            $longitude = $request->longitude;
            Session::put('longitude', $longitude);
        } else {
            $longitude = Session::get('longitude');
        }
        $selectedAddress = ($request->has('selectedAddress')) ? Session::put('selectedAddress', $request->selectedAddress) : Session::get('selectedAddress');
        $selectedPlaceId = ($request->has('selectedPlaceId')) ? Session::put('selectedPlaceId', $request->selectedPlaceId) : Session::get('selectedPlaceId');
        $preferences = Session::get('preferences');
        $currency_id = Session::get('customerCurrency');
        $language_id = Session::get('customerLanguage');

        $currency_id = $this->setCurrencyInSesion();


        Session::forget('vendorType');
        Session::put('vendorType', $request->type);

        $now = Carbon::now()->toDateTimeString();


        $navCategories = $this->categoryNav($language_id);
        Session::put('navCategories', $navCategories);

        $user = Auth::user();

        $data = [
           'navCategories' => $navCategories,
        ];
        return $this->successResponse($data);
    }

    #post Home Page Data New
    public function postHomePageDataNew(Request $request)
    {

        $enable_layout = CabBookingLayout::where('is_active',1)->orderBy('order_by','asc')->pluck('slug')->toArray();

        $data = [
            'data' => $enable_layout
        ];


        return $this->successResponse($data);
    }



    #post Home Page Data Single
    public function postHomePageDataSingle(Request $request)
    {
        Session::put('selectedDate', '07/25/2022');
        $slug = $request->slug??null;
        $vendor_ids = [];
        $new_products = [];
        $feature_products = [];
        $on_sale_products = [];
        if ($request->has('latitude')) {
            $latitude = $request->latitude;
            Session::put('latitude', $latitude);
        } else {
            $latitude = Session::get('latitude');
        }
        if ($request->has('longitude')) {
            $longitude = $request->longitude;
            Session::put('longitude', $longitude);
        } else {
            $longitude = Session::get('longitude');

        }
        $preferences = Session::has('preferences') ? Session::get('preferences') : ClientPreference::first();
        $set_template = WebStylingOption::where('web_styling_id', 1)->where('is_selected', 1)->first();
        $p_dim = '300/300';
        if (isset($set_template)  && $set_template->template_id == 3){
            $p_dim = '300/300';
        }elseif(isset($set_template)  && $set_template->template_id == 2){
            $p_dim = '300/300';
        }
        elseif(isset($set_template)  && $set_template->template_id == 6){
            $p_dim = '300/300';
        }
        $selectedAddress = ($request->has('selectedAddress')) ? Session::put('selectedAddress', $request->selectedAddress) : Session::get('selectedAddress');
        $selectedPlaceId = ($request->has('selectedPlaceId')) ? Session::put('selectedPlaceId', $request->selectedPlaceId) : Session::get('selectedPlaceId');
        //$preferences = ClientPreference::first();
        $currency_id = Session::get('customerCurrency');
        $language_id = Session::get('customerLanguage');

        $currency_id = $this->setCurrencyInSesion();

        if(isset($slug) && $slug == 'featured_products')
        $featured_products_title = CabBookingLayoutTranslation::where('language_id',$language_id)->whereHas('layout',function($q){$q->where('slug','featured_products');})->value('title');
        if(isset($slug) && $slug == 'vendors')
        $vendors_title = CabBookingLayoutTranslation::where('language_id',$language_id)->whereHas('layout',function($q){$q->where('slug','vendors');})->value('title');
        if(isset($slug) && $slug == 'new_products')
        $new_products_title = CabBookingLayoutTranslation::where('language_id',$language_id)->whereHas('layout',function($q){$q->where('slug','new_products');})->value('title');
        if(isset($slug) && $slug == 'on_sale')
        $on_sale_title = CabBookingLayoutTranslation::where('language_id',$language_id)->whereHas('layout',function($q){$q->where('slug','on_sale');})->value('title');
        if(isset($slug) && $slug == 'brands')
        $brands_title = CabBookingLayoutTranslation::where('language_id',$language_id)->whereHas('layout',function($q){$q->where('slug','brands');})->value('title');
        if(isset($slug) && $slug == 'best_sellers')
        $best_sellers_title = CabBookingLayoutTranslation::where('language_id',$language_id)->whereHas('layout',function($q){$q->where('slug','best_sellers');})->value('title');
        if(isset($slug) && $slug == 'trending_vendors')
        $trending_vendors_title = CabBookingLayoutTranslation::where('language_id',$language_id)->whereHas('layout',function($q){$q->where('slug','trending');})->value('title');
        if(isset($slug) && $slug == 'recent_orders')
        $recent_orders_title = CabBookingLayoutTranslation::where('language_id',$language_id)->whereHas('layout',function($q){$q->where('slug','recent_orders');})->value('title');


         if(isset($slug) && $slug == 'brands'){     # if enable brands section in
            $brands = Brand::select('id', 'image', 'title')->with(['translation' => function ($q) use ($language_id) {
                $q->where('language_id', $language_id);
            }])->where('status', '!=', $this->field_status)->orderBy('position', 'asc')->get();
            foreach ($brands as $brand) {
                $brand->redirect_url = route('brandDetail', $brand->id);
                $brand->translation_title = $brand->translation->first() ? $brand->translation->first()->title : $brand->title;
            }
        }else{
            $brands = [];
        }



        Session::forget('vendorType');
        Session::put('vendorType', $request->type);

        if(isset($slug)){
            $categoryTypes = getServiceTypesCategory($request->type);
            $vendors = Vendor::whereHas('getAllCategory.category',function($q)use ($categoryTypes){
                $q->whereIn('type_id',$categoryTypes);
            })->with('products')->with('slot.day', 'slotDate')->select('id', 'name', 'banner', 'address', 'order_pre_time', 'order_min_amount', 'logo', 'slug', 'latitude', 'longitude', 'show_slot')->where($request->type, 1);
            if ($preferences) {
                if ((empty($latitude)) && (empty($longitude)) && (empty($selectedAddress))) {
                    $selectedAddress = $preferences->Default_location_name;
                    $latitude = $preferences->Default_latitude;
                    $longitude = $preferences->Default_longitude;
                    Session::put('latitude', $latitude);
                    Session::put('longitude', $longitude);
                    Session::put('selectedAddress', $selectedAddress);
                } else {
                    if($preferences){
                        if (($latitude == $preferences->Default_latitude) && ($longitude == $preferences->Default_longitude)) {
                            Session::put('selectedAddress', $preferences->Default_location_name);
                        }
                    }

                }
                if (($preferences->is_hyperlocal == 1) && ($latitude) && ($longitude)) {
                    if (!empty($latitude) && !empty($longitude)) {
                        $vendors = $vendors->whereHas('serviceArea', function ($query) use ($latitude, $longitude) {
                            $query->select('vendor_id')
                        ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $latitude . " " . $longitude . ")'))");
                        });

                        if (isset($preferences->slots_with_service_area) && ($preferences->slots_with_service_area == 1)) {
                            $slot_vendors = clone $vendors;
                            $data = $slot_vendors->get();
                            foreach ($data as $key => $value) {
                                $vendors = $vendors->when(($value->show_slot == 0), function($query) use ($latitude, $longitude) {
                                    return $query->where(function($query1) use ($latitude, $longitude) {
                                        $query1->whereHas('slot.geos.serviceArea', function ($q) use ($latitude, $longitude) {
                                            $q->select('vendor_id')->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $latitude . " " . $longitude . ")'))")->where('is_active_for_vendor_slot', 1);
                                        })
                                        ->orWhereHas('slotDate.geos.serviceArea', function ($q) use ($latitude, $longitude) {
                                            $q->select('vendor_id')->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $latitude . " " . $longitude . ")'))")->where('is_active_for_vendor_slot', 1);
                                        });
                                    });
                                });
                            }
                        }
                    }
                }
            }
            $vendors = $vendors->where('status', 1)->inRandomOrder();
            $vendor_set = clone $vendors;

            $vendors = $vendors->take(25)->get();
            $vendor_ids = $vendor_set->pluck('id');


            foreach ($vendors as $key => $value) {
                $value->vendorRating = $this->vendorRating($value->products);
                // $value->name = Str::limit($value->name, 15, '..');
                if (($preferences) && ($preferences->is_hyperlocal == 1)) {
                    $value = $this->getVendorDistanceWithTime($latitude, $longitude, $value, $preferences);
                }
                $vendorCategories = VendorCategory::with('category.translation_one')->where('vendor_id', $value->id)->where('status', 1)->get();
                $categoriesList = '';
                foreach ($vendorCategories as $key => $category) {
                    if ($category->category) {
                        $categoriesList = $categoriesList . @$category->category->translation_one->name ?? '';
                        if ($key !=  $vendorCategories->count() - 1) {
                            $categoriesList = $categoriesList . ', ';
                        }
                    }
                }
                $value->categoriesList = $categoriesList;
                $value->type_title = $categoriesList;

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
            if (($preferences) && ($preferences->is_hyperlocal == 1)) {
                $vendors = $vendors->sortBy('lineOfSightDistance')->values()->all();
            }


            if (($latitude) && ($longitude)) {
                Session::put('vendors', $vendor_ids);
            }
        }else{
            $vendors = [];
        }

        if (isset($slug) && $slug == 'trending_vendors') {
            $now = Carbon::now()->toDateTimeString();
            $subscribed_vendors_for_trending = SubscriptionInvoicesVendor::with('features')->whereHas('features', function ($query) {
                $query->where(['subscription_invoice_features_vendor.feature_id' => 1]);
            })
            ->select('id', 'vendor_id', 'subscription_id')
            ->where('end_date', '>=', $now)
            ->pluck('vendor_id')->toArray();

            $trendingVendors = Vendor::with('slot.day', 'slotDate')->whereIn('id', $subscribed_vendors_for_trending)->where('status', 1)->inRandomOrder()->get();

            if ((!empty($trendingVendors) && count($trendingVendors) > 0)) {
                foreach ($trendingVendors as $key => $value) {
                    $value->tag_title = $trending_vendors_title??'0';
                    $value->vendorRating = $this->vendorRating($value->products);
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
                $trendingVendors = $trendingVendors->sortBy('lineOfSightDistance')->values()->all();
            }
        }else{
            $trendingVendors = [];
        }


        if (isset($slug) && $slug == 'best_sellers') {
            $mostSellingVendors = Vendor::with('slot.day', 'slotDate')->select('vendors.*', DB::raw('count(vendor_id) as max_sales'))->join('order_vendors', 'vendors.id', '=', 'order_vendors.vendor_id')->whereIn('vendors.id', $vendor_ids)->where('vendors.status', 1)->groupBy('order_vendors.vendor_id')->orderBy(DB::raw('count(vendor_id)'), 'desc')->get();
            if ((!empty($mostSellingVendors) && count($mostSellingVendors) > 0)) {
                foreach ($mostSellingVendors as $key => $value) {
                    $value->vendorRating = $this->vendorRating($value->products);
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
        }
        else{
            $mostSellingVendors = [];
        }

        if (isset($slug) && $slug == 'on_sale'){
            $on_sale_product_details = $this->vendorProducts($vendor_ids, $language_id, 'USD', '', $request->type);
            foreach ($on_sale_product_details as  $on_sale_product_detail) {
                $multiply = $on_sale_product_detail->variant->first()->multiplier ?? 1;
                $title = $on_sale_product_detail->translation->first() ? $on_sale_product_detail->translation->first()->title : $on_sale_product_detail->sku;
                $image_url = $on_sale_product_detail->media->first() && !is_null($on_sale_product_detail->media->first()->image)? $on_sale_product_detail->media->first()->image->path['image_fit'] . $p_dim . $on_sale_product_detail->media->first()->image->path['image_path'] : $this->loadDefaultImage();
                $on_sale_products[] = array(
                    'tag_title' => $on_sale_title??'0',
                    'image_url' => $image_url,
                    'sku' => $on_sale_product_detail->sku,
                    'title' => Str::limit($title, 18, '..'),
                    'url_slug' => $on_sale_product_detail->url_slug,
                    'averageRating' => number_format($on_sale_product_detail->averageRating, 1, '.', ''),
                    'inquiry_only' => $on_sale_product_detail->inquiry_only,
                    'vendor_name' => $on_sale_product_detail->vendor ? $on_sale_product_detail->vendor->name : '',
                    'vendor' => $on_sale_product_detail->vendor,
                    'price' => Session::get('currencySymbol') . ' ' . (decimal_format(@$on_sale_product_detail->variant->first()->price??0 * $multiply,',')),
                    'category' => ($on_sale_product_detail->category->categoryDetail->translation) ? ( $on_sale_product_detail->category->categoryDetail->translation->first()->name ?? $on_sale_product_detail->category->categoryDetail->slug): $on_sale_product_detail->category->categoryDetail->slug??''
                );
            }
        }
        else{
            $on_sale_product_detail = [];
        }
        if (isset($slug) && $slug == 'new_products'){
            $new_product_details = $this->vendorProducts($vendor_ids, $language_id, $currency_id, 'is_new', $request->type);
            foreach ($new_product_details as  $new_product_detail) {
            $multiply = $new_product_detail->variant->first()->multiplier?? 1;
            $title = $new_product_detail->translation->first() ? $new_product_detail->translation->first()->title : $new_product_detail->sku;
            $image_url = $new_product_detail->media->first() && !is_null($new_product_detail->media->first()->image) ? $new_product_detail->media->first()->image->path['image_fit'] . $p_dim . $new_product_detail->media->first()->image->path['image_path'] : $this->loadDefaultImage();
            $new_products[] = array(
                'tag_title' => $new_products_title??0,
                'image_url' => $image_url,
                'sku' => $new_product_detail->sku,
                'title' => Str::limit($title, 18, '..'),
                'url_slug' => $new_product_detail->url_slug,
                'averageRating' => number_format($new_product_detail->averageRating, 1, '.', ''),
                'inquiry_only' => $new_product_detail->inquiry_only,
                'vendor_name' => $new_product_detail->vendor ? $new_product_detail->vendor->name : '',
                'vendor' => $new_product_detail->vendor,
                'price' => Session::get('currencySymbol') . ' ' . (decimal_format(@$new_product_detail->variant->first()->price??0 * $multiply, ',')),
                'category' => (@$new_product_detail->category->categoryDetail->translation) ? @$new_product_detail->category->categoryDetail->translation->first()->name : @$new_product_detail->category->categoryDetail->slug
            );
            }
        }
        else
        $new_product_detail  = [];
        if (isset($slug) && $slug == 'featured_products'){

            $feature_product_details = $this->vendorProducts($vendor_ids, $language_id, $currency_id, 'is_featured', $request->type);

            foreach ($feature_product_details as  $feature_product_detail) {
                $multiply = $feature_product_detail->variant->first()->multiplier ?? 1;
                $title = $feature_product_detail->translation->first() ? $feature_product_detail->translation->first()->title : $feature_product_detail->sku;
                $image_url = $feature_product_detail->media->first() && !is_null($feature_product_detail->media->first()->image) ? $feature_product_detail->media->first()->image->path['image_fit'] . $p_dim . $feature_product_detail->media->first()->image->path['image_path'] : $this->loadDefaultImage();
                $feature_products[] = array(
                    'tag_title' => $featured_products_title??'0',
                    'image_url' => $image_url,
                    'sku' => $feature_product_detail->sku,
                    'title' => Str::limit($title, 18, '..'),
                    'url_slug' => $feature_product_detail->url_slug,
                    'averageRating' => number_format($feature_product_detail->averageRating, 1, '.', ''),
                    'inquiry_only' => $feature_product_detail->inquiry_only,
                    'vendor_name' => $feature_product_detail->vendor ? $feature_product_detail->vendor->name : '',
                    'vendor' => $feature_product_detail->vendor,
                    'price' => Session::get('currencySymbol') . ' ' . (decimal_format(@$feature_product_detail->variant->first()->price * $multiply, ',')),
                    'category' => (@$feature_product_detail->category->categoryDetail->translation) ? @$feature_product_detail->category->categoryDetail->translation->first()->name : @$feature_product_detail->category->categoryDetail->slug
                );
            }
        }
        else{
            $feature_product_detail = [];
        }


        $activeOrders = [];

        if (isset($slug) && $slug == 'recent_orders'){    # if enable recent_orders section in

            $user = Auth::user();

            if ($user) {
                    $activeOrders = Order::whereHas('vendors', function ($q) {
                        $q->where('order_status_option_id', '!=', 6);
                    })->with([
                        'vendors' => function ($q) {
                            $q->where('order_status_option_id', '!=', 6)->with('products','products.media.image', 'products.pvariant.media.pimage.image');
                        },
                        'vendors.dineInTable.translations' => function ($qry) use ($language_id) {
                            $qry->where('language_id', $language_id);
                        }, 'vendors.dineInTable.category',
                        'address'
                    ])->where('orders.user_id', $user->id)
                        ->orderBy('orders.id', 'DESC')
                        ->take(5)->get();
                        foreach ($activeOrders as $order) {
                            foreach ($order->vendors as $vendor) {
                                // dd($vendor->toArray());
                                $vendor->tag_title = $vendor_title??'0';
                                $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order->id)->where('vendor_id', $vendor->vendor_id)->orderBy('id', 'DESC')->first();
                                $vendor->order_status = $vendor_order_status ? strtolower($vendor_order_status->OrderStatusOption->title) : '';
                                foreach ($vendor->products as $product) {
                                    if (isset($product->pvariant) && $product->pvariant->media->isNotEmpty()) {
                                        $product->image_url = $product->pvariant->media->first()->pimage->image->path['image_fit'] . '74/100' . $product->pvariant->media->first()->pimage->image->path['image_path'];
                                    } elseif ($product->media->isNotEmpty()) {
                                        $product->image_url = $product->media->first()->image->path['image_fit'] . '74/100' . $product->media->first()->image->path['image_path'];
                                    } else {
                                        $product->image_url = ($product->image) ? $product->image['image_fit'] . '74/100' . $product->image['image_path'] : '';
                                    }
                                    $product->pricedoller_compare = 1;
                                }
                                if ($vendor->delivery_fee > 0) {
                                    $order_pre_time = ($vendor->order_pre_time > 0) ? $vendor->order_pre_time : 0;
                                    $user_to_vendor_time = ($vendor->user_to_vendor_time > 0) ? $vendor->user_to_vendor_time : 0;
                                    $ETA = $order_pre_time + $user_to_vendor_time;
                                    $vendor->ETA = ($ETA > 0) ? $this->formattedOrderETA($ETA, $vendor->created_at, $order->scheduled_date_time) : dateTimeInUserTimeZone($vendor->created_at, $user->timezone);
                                }
                                if ($vendor->dineInTable) {
                                    $vendor->dineInTableName = $vendor->dineInTable->translations->first() ? $vendor->dineInTable->translations->first()->name : '';
                                    $vendor->dineInTableCapacity = $vendor->dineInTable->seating_number;
                                    $vendor->dineInTableCategory = $vendor->dineInTable->category->first() ? $vendor->dineInTable->category->first()->title : '';
                                }
                            }
                            $order->converted_scheduled_date_time = dateTimeInUserTimeZone($order->scheduled_date_time, $user->timezone);
                        }
            }


        }else{

        }

        $cities = [];

        if ((isset($slug) && $slug == 'cities') && ($preferences->is_hyperlocal ==1) ){    # if enable cities section in

            $cities =  VendorCities::with(['translations'=> function ($q) use($language_id) {
                                $q->where('language_id', $language_id);
                            }])->where(function ($q)  {
                                $q->where('latitude','!=', null);
                                $q->where('longitude','!=', null);
                            })->get();
            $cities = $cities->map(function($da) {
                $da->title = $da->translations->first() ? $da->translations->first()->name : $da->slug ;
                unset($da->translations);
                return $da;
            });
        }else{

        }
        //pr($cities->toArray());
        $data = [
            'brands' => $brands,
            'banners' => [],
            'vendors' => $vendors,
            'new_products' => $new_products,
            'mobile_banners' => [],
            'feature_products' => $feature_products,
            'on_sale_products' => $on_sale_products,
            'trending_vendors' => (!empty($trendingVendors) && count($trendingVendors) > 0)?$trendingVendors:$mostSellingVendors,
            'active_orders' => $activeOrders,
            'cities' => $cities
        ];

        return $this->successResponse($data);
    }

    public function postHomePageDataBanners(Request $request)
    {
        $preferences = ClientPreference::select('is_service_area_for_banners', 'is_hyperlocal', 'Default_latitude', 'Default_longitude')->first();
        $latitude = $request->has('latitude') ? $request->get('latitude') : null;
        $longitude = $request->has('longitude') ? $request->get('longitude') : null;

        if(empty($latitude) && empty($longitude)){
            $latitude = $preferences->Default_latitude;
            $longitude = $preferences->Default_longitude;
        }

        // Start Web Banners
        $banners = Banner::with(['category', 'vendor'])->where('status', 1)->where('validity_on', 1)
        ->where(function ($q) {
            $q->whereNull('start_date_time')->orWhere(function ($q2) {
                $q2->whereDate('start_date_time', '<=', Carbon::now())
                    ->whereDate('end_date_time', '>=', Carbon::now());
            });
        });
        if(isset($preferences->is_service_area_for_banners) && ($preferences->is_service_area_for_banners == 1) && ($preferences->is_hyperlocal == 1)){
            if(!empty($latitude) && !empty($longitude)){
                $banners = $banners->whereHas('geos.serviceArea', function($query) use ($latitude, $longitude) {
                    $query->select('id')->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $latitude . " " . $longitude . ")'))");
                });
            }
        }
        $banners = $banners->orderBy('sorting', 'asc')->get();
        // End Web Banners

        // Start Mobile Banners
        $mobile_banners = MobileBanner::with(['category','vendor'])->where('status', 1)->where('validity_on', 1)
        ->where(function ($q) {
            $q->whereNull('start_date_time')->orWhere(function ($q2) {
                $q2->whereDate('start_date_time', '<=', Carbon::now())
                    ->whereDate('end_date_time', '>=', Carbon::now());
            });
        });
        if(isset($preferences->is_service_area_for_banners) && ($preferences->is_service_area_for_banners == 1) && ($preferences->is_hyperlocal == 1)){
            if(!empty($latitude) && !empty($longitude)){
                $mobile_banners = $mobile_banners->whereHas('geos.serviceArea', function($query) use ($latitude, $longitude) {
                    $query->select('id')->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $latitude . " " . $longitude . ")'))");
                });
            }
        }
        $mobile_banners = $mobile_banners->orderBy('sorting', 'asc')->get();
        // End Mobile Banners

        $data = [
            'banners' => $banners,
            'mobile_banners' => $mobile_banners
        ];

        return $this->successResponse($data);
    }

    public function confirmation(){
        return view('confirmatin');
    }

    public function setSessionIndex(Request $request, $domain='')
    {
        
        Session::forget('vendorType');
        Session::put('vendorType', $request->type);

        return response()->json(["status" => true]);
    }
   

    public function homePageSection()
    {
        $vendors = Vendor::where('status', 1)->select('id', 'name', 'slug');
        if (Auth::user()->is_superadmin == 0) {
            $vendors = $vendors->whereHas('permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $vendors = $vendors->get();
        $taxCategory = TaxCategory::all();

        $p_categories = Category::with(['parent', 'translation_one'])
            ->whereIn('type_id', ['1', '3', '7', '8', '9'])
            ->where('id', '>', '1')
            ->where('deleted_at', NULL)
            ->where('status', 1)
            ->orderBy('parent_id', 'asc')
            ->orderBy('position', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('backend.tools.index')->with(['vendors' => $vendors, 'taxCategory' => $taxCategory, 'categories' => $p_categories]);
    }
}
