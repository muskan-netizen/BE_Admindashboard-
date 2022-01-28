<?php

namespace App\Http\Controllers\Api\v1;
use DB;
use App;
use Mail;
use Config;
use Session;
use Carbon\Carbon;
use App\Models\User;
use ConvertCurrency;
use App\Models\Cart;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use GuzzleHttp\Client as GCLIENT;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client as TwilioClient;
use App\Models\{Client, Category, Product, ClientPreference, ClientCurrency, Wallet, UserLoyaltyPoint, LoyaltyCard, Order, Nomenclature, Vendor, VendorCategory};

class BaseController extends Controller{

    use \App\Http\Traits\smsManager;

    private $field_status = 2;
	protected function sendSms($provider, $sms_key, $sms_secret, $sms_from, $to, $body){
        try{
            $client_preference =  getClientPreferenceDetail();
            if($client_preference->sms_provider == 1)
            {
                $client = new TwilioClient($sms_key, $sms_secret);
                $client->messages->create($to, ['from' => $sms_from, 'body' => $body]);
            }elseif($client_preference->sms_provider == 2) //for mtalkz gateway
            {
                $crendentials = json_decode($client_preference->sms_credentials);
                $send = $this->mTalkz_sms($to,$body,$crendentials);
            }elseif($client_preference->sms_provider == 3) //for mazinhost gateway
            {
                $crendentials = json_decode($client_preference->sms_credentials);
                $send = $this->mazinhost_sms($to,$body,$crendentials);
            }else{
                $client = new TwilioClient($sms_key, $sms_secret);
                $client->messages->create($to, ['from' => $sms_from, 'body' => $body]);
            }
        }
        catch(\Exception $e){
            return '2';
        }
        return '1';
	}

	public function buildTree($elements, $parentId = 1) {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    public function getChildCategoriesForVendor($category_id, $langId=1, $vid=0)
    {
        $category_list = array();

        $categories = Category::with(['translation' => function($q) use($langId){
                $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
                ->where('category_translations.language_id', $langId);
            }, 'childs'])
            ->select('id', 'icon', 'image', 'slug', 'type_id', 'can_add_products', 'parent_id')
            ->where('parent_id', $category_id)->where('status', 1)->get();
        if($categories){
            foreach($categories as $cate){
                if($cate->childs){
                    foreach($cate->childs as $child){
                        $vendorCategory = VendorCategory::with(['category.translation' => function($q) use($langId){
                            $q->where('category_translations.language_id', $langId);
                        }])->where('vendor_id', $vid)->where('category_id', $child->id)->where('status', 1)->first();
                        if($vendorCategory){
                            $category_list[] = $vendorCategory;
                        }
                        $this->getChildCategoriesForVendor($child->id, $langId, $vid);
                    }
                }

                $vendorCategory = VendorCategory::with(['category.translation' => function($q) use($langId){
                    $q->where('category_translations.language_id', $langId);
                }])->where('vendor_id', $vid)->where('category_id', $cate->id)->where('status', 1)->first();
                if($vendorCategory){
                    $category_list[] = $vendorCategory;
                }
                $this->getChildCategoriesForVendor($cate->id, $langId, $vid);
            }
        }
        return $category_list;
    }

    public function categoryNav($lang_id, $vends=[]) {
        $preferences = ClientPreference::select('is_hyperlocal', 'client_code', 'language_id', 'celebrity_check')->first();
        $categories = Category::join('category_translations as cts', 'categories.id', 'cts.category_id')
                    ->select('categories.id', 'categories.icon', 'categories.image', 'categories.slug', 'categories.parent_id', 'cts.name', 'categories.warning_page_id', 'categories.template_type_id', 'types.title as redirect_to')->distinct('categories.slug');

        $status = $this->field_status;
        $include_categories = [4,8]; // type 4 for brands
        $celebrity_check = 0;
        if ($preferences) {
            if((isset($preferences->celebrity_check)) && ($preferences->celebrity_check == 1)){
                $celebrity_check = 1;
                $include_categories[] = 5; // type 5 for celebrity
            }
            if ((isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1)) {
                $categories = $categories->leftJoin('vendor_categories as vct', 'categories.id', 'vct.category_id')
                    ->where(function ($q1) use ($vends, $include_categories) {
                        $q1->whereIn('vct.vendor_id', $vends)
                            ->where('vct.status', 1)
                            ->orWhere(function ($q2) use($include_categories) {
                                $q2->whereIn('categories.type_id', $include_categories);
                            });
                    });
            }
        }
        $categories = $categories->leftjoin('types', 'types.id', 'categories.type_id')
                        ->where('categories.id', '>', '1')
                        ->whereNotNull('categories.type_id');
        if($celebrity_check == 0){
            $categories = $categories->where('categories.type_id', '!=', 5);
        }
        $categories = $categories->where('categories.is_visible', 1)
                        ->where('categories.status', '!=', $status)
                        ->where('categories.is_core', 1)
                        ->where('cts.language_id', $lang_id)
                        ->orderBy('categories.parent_id', 'asc')
                        ->withCount('products')->orderBy('categories.position', 'asc')->groupBy('id')->get();
        if($categories){
            $categories = $this->buildTree($categories->toArray());
        }
        return $categories;
    }

    public function metaProduct($langId, $multiplier, $for = 'related', $productArray = []){
        if(empty($productArray)){
            return $productArray;
        }
        $productIds = array();
        foreach ($productArray as $key => $value) {
            if($for == 'related'){
                $productIds[] = $value->related_product_id;
            }
            if($for == 'upSell'){
                $productIds[] = $value->upsell_product_id;
            }
            if($for == 'crossSell'){
                $productIds[] = $value->cross_product_id;
            }
        }
        $products = Product::with([
            'category.categoryDetail.translation' => function ($q) use ($langId) {
                $q->where('category_translations.language_id', $langId);
            },
            'vendor', 'media' => function($q){
                $q->groupBy('product_id');
            }, 'media.image',
            'translation' => function($q) use($langId){
            $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
            },
            'variant' => function($q) use($langId){
                $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                $q->groupBy('product_id');
            },
        ])->select('id', 'sku', 'averageRating', 'url_slug', 'is_new', 'is_featured', 'vendor_id', 'inquiry_only','minimum_order_count','batch_count')
        ->whereIn('id', $productIds);
        $products = $products->get();
        if(!empty($products)){
            foreach ($products as $key => $value) {
                if($value->is_new == 1){
                    $value->product_type = 'New Product';
                }elseif($value->is_featured == 1){
                    $value->product_type = 'Featured Product';
                }else{
                    $value->product_type ='On Sale';
                }
                $value->product_media = $value->media ? $value->media->first() : NULL;
                $value->vendor_name = $value->vendor ? $value->vendor->name : '';
                $value->translation_title = (!empty($value->translation->first())) ? $value->translation->first()->title : $value->sku;
                $value->translation_description = (!empty($value->translation->first())) ? $value->translation->first()->body_html : $value->sku;
                $value->variant_multiplier = $multiplier ? $multiplier : 1;
                $value->variant_price = (!empty($value->variant->first())) ? number_format(($value->variant->first()->price * $multiplier),2,'.','') : 0;
                $value->averageRating = number_format($value->averageRating, 1, '.', '');
                $value->category_name = $value->category->categoryDetail->translation->first()->name??null;
                // foreach ($value->variant as $k => $v) {
                //     $value->variant[$k]->multiplier = $multiplier;
                // }
            }
        }
        return $products;
    }

    function getVendorDistanceWithTime($userLat='', $userLong='', $vendor, $preferences, $type = 'delivery'){
        if(($preferences) && ($preferences->is_hyperlocal == 1)){
            if( (empty($userLat)) && (empty($userLong)) ){
                $userLat = (!empty($preferences->Default_latitude)) ? floatval($preferences->Default_latitude) : 0;
                $userLong = (!empty($preferences->Default_latitude)) ? floatval($preferences->Default_longitude) : 0;
            }

            $lat1   = $userLat;
            $long1  = $userLong;
            $lat2   = $vendor->latitude;
            $long2  = $vendor->longitude;
            $distance_unit = (!empty($preferences->distance_unit_for_time)) ? $preferences->distance_unit_for_time : 'kilometer';
            $unit_abbreviation = ($distance_unit == 'mile') ? 'miles' : 'km';
            $distance_to_time_multiplier = (!empty($preferences->distance_to_time_multiplier)) ? $preferences->distance_to_time_multiplier : 2;
            $distance = $this->calulateDistanceLineOfSight($lat1, $long1, $lat2, $long2, $distance_unit);
            $vendor->lineOfSightDistance = number_format($distance, 1, '.', '') .' '. $unit_abbreviation;
            if($type == 'delivery')
            {
                $pretime =  number_format(floatval($vendor->order_pre_time), 0, '.', '') + number_format(($distance * $distance_to_time_multiplier), 0, '.', '');
                // distance is multiplied by distance time multiplier to calculate travel time
            }else{
                $pretime =  number_format(floatval($vendor->order_pre_time), 0, '.', '') + 0;
            }
            // if($pretime >= 60){
            //     $vendor->timeofLineOfSightDistance =  $this->vendorTime($pretime) . '-' . $this->vendorTime((intval($pretime) + 5)).' '. __('hour');
            // }else{
            //     $vendor->timeofLineOfSightDistance = $pretime . '-' . (intval($pretime) + 5).' '. __('min');
            // }
            $vendor->timeofLineOfSightDistance = $pretime ;
        }
        return $vendor;
    }

    function getLineOfSightDistanceAndTime($vendor, $preferences){
        if (($preferences) && ($preferences->is_hyperlocal == 1)) {
            $distance_unit = (!empty($preferences->distance_unit_for_time)) ? $preferences->distance_unit_for_time : 'kilometer';
            $unit_abbreviation = ($distance_unit == 'mile') ? 'miles' : 'km';
            $distance_to_time_multiplier = ($preferences->distance_to_time_multiplier > 0) ? $preferences->distance_to_time_multiplier : 2;
            $distance = $vendor->vendorToUserDistance;
            $vendor->lineOfSightDistance = number_format($distance, 1, '.', '') .' '. $unit_abbreviation;
            $pretime = number_format(floatval($vendor->order_pre_time), 0, '.', '') + number_format(($distance * $distance_to_time_multiplier), 0, '.', ''); // distance is multiplied by distance time multiplier to calculate travel time
            // if($pretime >= 60){
            //     $vendor->timeofLineOfSightDistance =  $this->vendorTime($pretime) . '-' . $this->vendorTime((intval($pretime) + 5)).' '. __('hour');
            // }else{
            //     $vendor->timeofLineOfSightDistance = $pretime . '-' . (intval($pretime) + 5).' '. __('min');
            // }
            $vendor->timeofLineOfSightDistance =  $pretime;
            // $pretime = $this->getEvenOddTime($vendor->timeofLineOfSightDistance);
            // $vendor->timeofLineOfSightDistance = $pretime . '-' . (intval($pretime) + 5);
        }
        return $vendor;
    }

    protected function in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y){
      $i = $j = $c = 0;
      for ($i = 0, $j = $points_polygon-1 ; $i < $points_polygon; $j = $i++) {
        if ( (($vertices_y[$i] > $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
        ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) ) {
            $c = !$c;
        }
      }
      return $c;
    }

    public function getServiceAreaVendors($lat=0, $lng=0, $type='delivery'){
        $preferences = ClientPreference::where('id', '>', 0)->first();
        $user = Auth::user();
        $latitude = ($user->latitude) ? $user->latitude : $lat;
        $longitude = ($user->longitude) ? $user->longitude : $lng;
        $vendorType = $user->vendorType ? $user->vendorType : $type;
        $serviceAreaVendors = Vendor::select('id');
        $vendors = [];
        if($vendorType){
            $serviceAreaVendors = $serviceAreaVendors->where($vendorType, 1);
        }
        if( (isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1) ){
            $latitude = ($latitude) ? $latitude : $preferences->Default_latitude;
            $longitude = ($longitude) ? $longitude : $preferences->Default_longitude;

            if(!empty($latitude) && !empty($longitude) ){
                $serviceAreaVendors = $serviceAreaVendors->whereHas('serviceArea', function($query) use($latitude, $longitude){
                    $query->select('vendor_id')
                    ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(".$latitude." ".$longitude.")'))");
                });
            }
           
        }
        $serviceAreaVendors = $serviceAreaVendors->where('status', 1)->get();

        if($serviceAreaVendors->isNotEmpty()){
            foreach($serviceAreaVendors as $value){
                $vendors[] = $value->id;
            }
        }
        return $vendors;
    }

    public function loadDefaultImage(){
        $proxy_url = \Config::get('app.IMG_URL1');
        $image_path = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url('default/default_image.png');
        $image_fit = \Config::get('app.FIT_URl');
        $default_url = $image_fit .'300/300'. $image_path.'@webp';
        return $default_url;
    }

    protected function contains($point, $polygon){
        if($polygon[0] != $polygon[count($polygon)-1]){
            $polygon[count($polygon)] = $polygon[0];
            $j = 0;
            $oddNodes = false;
            $x = $point[1];
            $y = $point[0];
            $n = count($polygon);
            for ($i = 0; $i < $n; $i++){
                $j++;
                if ($j == $n){
                    $j = 0;
                }
                if ((($polygon[$i]['lat'] < $y) && ($polygon[$j]['lat'] >= $y)) || (($polygon[$j]['lat'] < $y) && ($polygon[$i]['lat'] >=
                    $y))){
                    if ($polygon[$i]['lng'] + ($y - $polygon[$i]['lat']) / ($polygon[$j]['lat'] - $polygon[$i]['lat']) * ($polygon[$j]['lng'] -
                        $polygon[$i]['lng']) < $x)
                    {
                        $oddNodes = !$oddNodes;
                    }
                }
            }
        }
        return $oddNodes;
    }

    public function sendNotification(Request $request)
    {
        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
        $SERVER_API_KEY = 'XXXXXX';
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
            ]
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
        dd($response);

    }

    protected function changeCurrency($curr, $price)
    {
        $currency = ConvertCurrency::convert('USD',[$curr], $price);
        return $currency[0]['convertedAmount'];
    }

    public function setMailDetail($mail_driver, $mail_host, $mail_port, $mail_username, $mail_password, $mail_encryption){
        // $config = array(
        //     'driver' => $mail_driver,
        //     'host' => $mail_host,
        //     'port' => $mail_port,
        //     'encryption' => $mail_encryption,
        //     'username' => $mail_username,
        //     'password' => $mail_password,
        //     'sendmail' => '/usr/sbin/sendmail -bs',
        //     'pretend' => false,
        // );
        // Config::set('mail', $config);
        $app = App::getInstance();
        $app->register('Illuminate\Mail\MailServiceProvider');
        return '1';
    }

    /**     * check if cookie already exist     */
    public function checkCookies($userid){
        if (isset(Auth::user()->system_user) && !empty(Auth::user()->system_user)) {
            $userFind = User::where('system_id', Auth::user()->system_user)->first();
            if($userFind){
                $cart = Cart::where('user_id', $userFind->id)->first();
                if($cart){
                    $cart->user_id = $userid;
                    $cart->save();
                }
                $userFind->delete();
            }
        }
        return $userid;
    }

    /**     * check if cookie already exist     */
    public function getLoyaltyPoints($userid, $multiplier){
        $loyalty_earned_amount = 0;
        $redeem_points_per_primary_currency = '';
        $loyalty_card = LoyaltyCard::where('status', '0')->first();
        if ($loyalty_card) {
            $redeem_points_per_primary_currency = $loyalty_card->redeem_points_per_primary_currency;
        }
        $order_loyalty_points_earned_detail = Order::where('user_id', $userid)->select(DB::raw('sum(loyalty_points_earned) AS sum_of_loyalty_points_earned'), DB::raw('sum(loyalty_points_used) AS sum_of_loyalty_points_used'))->first();
        if ($order_loyalty_points_earned_detail) {
            $loyalty_points_used = $order_loyalty_points_earned_detail->sum_of_loyalty_points_earned - $order_loyalty_points_earned_detail->sum_of_loyalty_points_used;
            if ($loyalty_points_used > 0 && $redeem_points_per_primary_currency > 0) {
                $loyalty_earned_amount = $loyalty_points_used / $redeem_points_per_primary_currency;
            }
        }
        return $loyalty_earned_amount;
    }

    /**     * check if cookie already exist     */
    public function getWallet($userid, $multiplier, $currency = 147){
        $wallet = Wallet::where('user_id', $userid)->first();
        if(!$wallet){
            $wallet = new Wallet();
            $wallet->user_id = $userid;
            $wallet->type = 1;
            $wallet->balance = 0;
            $wallet->card_id = $this->randomData('wallets');
            $wallet->card_qr_code = $this->randomBarcode('wallets');
            $wallet->meta_field = '';
            $wallet->currency_id = $currency;
            $wallet->save();
        }
        $balance = $wallet->balance * $multiplier;
        return $balance;
    }

    /* Create random and unique client code*/
    public function randomData($table){
        $random_string = substr(md5(microtime()), 0, 6);
        // after creating, check if string is already used
        while(\DB::table($table)->where('refferal_code', $random_string)->exists()){
            $random_string = substr(md5(microtime()), 0, 6);
        }
        return $random_string;
    }

    public function randomBarcode($table){
        $barCode = substr(md5(microtime()), 0, 14);
        while( \DB::table($table)->where('card_qr_code', $barCode)->exists()){
            $barCode = substr(md5(microtime()), 0, 14);
        }
        return $barCode;
    }

    /**     * check if cookie already exist     */
    public function userMetaData($userid, $device_type = 'web', $device_token = 'web', $currency = 147){
        $device = UserDevice::where('user_id', $userid)->first();
        if(!$device){
            $user_device[] = [
                'user_id' => $userid,
                'device_type' => $device_type,
                'device_token' => $device_token,
                'access_token' => ''
            ];
            UserDevice::insert($user_device);
        }
        $loyaltyPoints = UserLoyaltyPoint::where('user_id', $userid)->first();
        if(!$loyaltyPoints){
            $loyalty[] = [
                'user_id' => $userid,
                'points' => 0
            ];
            UserLoyaltyPoint::insert($loyalty);
        }
        $wallet = Wallet::where('user_id', $userid)->first();
        if(!$wallet){
            $walletData[] = [
                'user_id' => $userid,
                'type' => 1,
                'balance' => 0,
                'card_id' => $this->randomData('wallets'),
                'card_qr_code' => $this->randomBarcode('wallets'),
                'meta_field' => '',
                'currency_id' => $currency,
            ];
            Wallet::insert($walletData);
        }
        return 1;
    }

    // Find distance between two lat long points
    function calulateDistanceLineOfSight($lat1, $lon1, $lat2, $lon2, $unit) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
          return 0;
        }
        else {
          $theta = $lon1 - $lon2;
          $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
          $dist = acos($dist);
          $dist = rad2deg($dist);
          $miles = $dist * 60 * 1.1515;
          $unit = strtolower($unit);

          if ($unit == "kilometer") {
            return ($miles * 1.609344);
          } else if ($unit == "nautical mile") {
            return ($miles * 0.8684);
          } else {
            return $miles;
          }
        }
    }

    public function formattedOrderETA($minutes, $order_vendor_created_at, $scheduleTime='', $user=''){
        $d = floor ($minutes / 1440);
        $h = floor (($minutes - $d * 1440) / 60);
        $m = $minutes - ($d * 1440) - ($h * 60);
        // return (($d > 0) ? $d.' days ' : '') . (($h > 0) ? $h.' hours ' : '') . (($m > 0) ? $m.' minutes' : '');

        // if($scheduleTime != ''){
        //     $datetime = Carbon::parse($scheduleTime)->setTimezone(Auth::user()->timezone)->toDateTimeString();
        // }else{
        //     $datetime = Carbon::parse($order_vendor_created_at)->setTimezone(Auth::user()->timezone)->addMinutes($minutes)->toDateTimeString();
        // }

        // if(Carbon::parse($datetime)->isToday()){
        //     $format = 'h:i A';
        // }else{
        //     $format = 'M d, Y h:i A';
        // }
        // // $time = convertDateTimeInTimeZone($datetime, Auth::user()->timezone, $format);
        // $time = Carbon::parse($datetime)->format($format);



        if(isset($user) && !empty($user))
        $user =  $user;
        else
        $user = Auth::user();

        $timezone = $user->timezone;
        $preferences = ClientPreference::select('date_format', 'time_format')->where('id', '>', 0)->first();
        $date_format = $preferences->date_format;
        $time_format = $preferences->time_format;

        if($scheduleTime != ''){
            $datetime = Carbon::parse($scheduleTime)->addMinutes($minutes);
            $datetime = dateTimeInUserTimeZone($datetime, $timezone);
        }else{
            $datetime = Carbon::parse($order_vendor_created_at)->addMinutes($minutes);
            $datetime = dateTimeInUserTimeZone($datetime, $timezone);
        }
        if(Carbon::parse($datetime)->isToday()){
            if($time_format == '12'){
                $time_format = 'hh:mm A';
            }else{
                $time_format = 'HH:mm';
            }
            $datetime = Carbon::parse($datetime)->isoFormat($time_format);
        }
        return $datetime;
    }

    public function getNomenclatureName($searchTerm, $langId, $plural = true){
        $result = Nomenclature::with(['translations' => function($q) use($langId) {
                    $q->where('language_id', $langId);
                }])->where('label', 'LIKE', "%{$searchTerm}%")->first();
        if($result){
            $searchTerm = $result->translations->count() != 0 ? $result->translations->first()->name : ucfirst($searchTerm);
        }
        return $searchTerm;
        // return $plural ? $searchTerm : rtrim($searchTerm, 's');
    }

    /* doller compare amount */
    public function getDollarCompareAmount($amount, $customerCurrency='')
    {
        $user = Auth::user();
        $customerCurrency = $user->currency ? $user->currency : '';
        $primaryCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
        if(empty($customerCurrency)){
            $clientCurrency = $primaryCurrency;
        }else{
            $clientCurrency = ClientCurrency::where('currency_id', $customerCurrency)->first();
        }
        $divider = (empty($clientCurrency->doller_compare) || $clientCurrency->doller_compare < 0) ? 1 : $clientCurrency->doller_compare;
        $amount = ($amount / $divider) * $primaryCurrency->doller_compare;
        $amount = number_format($amount, 2);
        return $amount;
    }

    public function checkIfLastMileDeliveryOn()
    {
        $preference = ClientPreference::first();
        if ($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url)) {
            return $preference;
        } else {
            return false;
        }
    }

    public function driverDocuments()
    {
        try {
            $dispatch_domain = $this->checkIfLastMileDeliveryOn();
            $url = $dispatch_domain->delivery_service_key_url;
            $endpoint = $url . "/api/send-documents";
            // $dispatch_domain->delivery_service_key_code = '649a9a';
            // $dispatch_domain->delivery_service_key = 'icDerSAVT4Fd795DgPsPfONXahhTOA';
            $client = new GCLIENT(['headers' => ['personaltoken' => $dispatch_domain->delivery_service_key, 'shortcode' => $dispatch_domain->delivery_service_key_code]]);

            $response = $client->post($endpoint);
            $response = json_decode($response->getBody(), true);

            return json_encode($response['data']);
        } catch (\Exception $e) {
            $data = [];
            $data['status'] = 400;
            $data['message'] =  $e->getMessage();
            return $data;
        }
    }
    public function vendorTime($minutes){
        $hours = intdiv($minutes, 60).':'. ($minutes % 60);

        return $hours;
    }

    public function testOrderMail($emailData){
        $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        $data = ClientPreference::select('sms_key', 'sms_secret', 'sms_from', 'mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();

        if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
            $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
            $client_name = $emailData['client_name'];
            $mail_from = $emailData['mail_from'];
            $sendto = $emailData['email'];
            try{
                Mail::send([], [],
                function ($message) use($sendto, $client_name, $mail_from, $emailData) {
                    $message->from($mail_from, $client_name);
                    $message->to($sendto)->subject('Order mail');
                    $message->setBody($emailData['email_template_content'], 'text/html'); // for HTML rich messages
                });
                $response['send_email'] = 1;
                return count(Mail::failures());
            }
            catch(\Exception $e){
                return response()->json(['data' => $e->getMessage()]);
            }
        }
    }

}
