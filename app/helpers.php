<?php

use App\Models\CartProduct;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Nomenclature;
use App\Models\UserRefferal;
use App\Models\ProductVariant;
use App\Models\ClientPreference;
use App\Models\Client as ClientData;
use App\Models\PaymentOption;
use App\Models\ShippingOption;
use App\Models\VendorSlot;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;

function changeDateFormate($date,$date_format){
    return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format($date_format);
}

function pr($var) {
  	echo '<pre>';
	print_r($var);
  	echo '</pre>';
    exit();
}
function http_check($url) {
    $return = $url;
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $return = 'http://' . $url;
    }
    return $return;
}
function getUserDetailViaApi($user){
    $user_refferal = UserRefferal::where('user_id', $user->id)->first();
    $client_preference = ClientPreference::select('theme_admin', 'distance_unit', 'map_provider', 'date_format','time_format', 'map_key','sms_provider','verify_email','verify_phone', 'app_template_id', 'web_template_id')->first();
    $data['name'] = $user->name;
    $data['email'] = $user->email;
    $data['source'] = $user->image;
    $data['is_admin'] = $user->is_admin;
    $data['dial_code'] = $user->dial_code;
    $data['auth_token'] =  $user->auth_token;
    $data['phone_number'] = $user->phone_number;
    $data['client_preference'] = $client_preference;
    $data['cca2'] = $user->country ? $user->country->code : '';
    $data['callingCode'] = $user->country ? $user->country->phonecode : '';
    $data['refferal_code'] = $user_refferal ? $user_refferal->refferal_code: '';
    $data['verify_details'] = ['is_email_verified' => $user->is_email_verified, 'is_phone_verified' => $user->is_phone_verified];
    return $data;
}
function getMonthNumber($month_name){
    if($month_name == 'January'){
        return 1;
    }else if($month_name == 'February'){
        return 2;
    }else if($month_name=='March'){
        return 3;
    }else if($month_name=='April'){
        return 4;
    }else if($month_name=='May'){
        return 5;
    }else if($month_name=='June'){
        return 6;
    }else if($month_name=='July'){
        return 7;
    }else if($month_name=='August'){
        return 8;
    }else if($month_name=='September'){
        return 9;
    }else if($month_name=='October'){
        return 10;
    }else if($month_name=='November'){
        return 11;
    }else if($month_name=='December'){
        return 12;
    }
}
function generateOrderNo($length = 8){
    $number = '';
    do {
        for ($i=$length; $i--; $i>0) {
            $number .= mt_rand(0,9);
        }
    } while (!empty(\DB::table('orders')->where('order_number', $number)->first(['order_number'])) );
    return $number;
}
function generateWalletTransactionReference($length = 8){
    $number = '';
    do {
        $number = 'txn_' . md5(uniqid(rand(), true));
    } 
    while (!empty(\DB::table('transactions')->where('meta', 'Like', '%'. $number .'%')->first(['meta'])) );
    return $number;
}
function getNomenclatureName($searchTerm, $plural = true){
    $result = Nomenclature::with(['translations' => function($q) {
                $q->where('language_id', session()->get('customerLanguage'));
            }])->where('label', 'LIKE', "%{$searchTerm}%")->first();
    if($result){
        $searchTerm = $result->translations->count() != 0 ? $result->translations->first()->name : ucfirst($searchTerm);
    }
    return $plural ? $searchTerm : rtrim($searchTerm, 's');
}
function convertDateTimeInTimeZone($date, $timezone, $format = 'Y-m-d H:i:s'){
    $date = Carbon::parse($date, 'UTC');
    $date->setTimezone($timezone);
    return $date->format($format);
}
function getClientPreferenceDetail()
{
    $client_preference_detail = ClientPreference::first();
    list($r, $g, $b) = sscanf($client_preference_detail->web_color, "#%02x%02x%02x");
    $client_preference_detail->wb_color_rgb = "rgb(".$r.", ".$g.", ".$b.")";
    return $client_preference_detail;
}
function getClientDetail()
{
    $clientData = ClientData::first();
    $clientData->logo_image_url = $clientData ? $clientData->logo['image_fit'].'150/92'.$clientData->logo['image_path'] : " ";
    return $clientData;
}
function getRazorPayApiKey()
{
    $razorpay_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'razorpay')->where('status', 1)->first();
    $api_key_razorpay = "";
    if($razorpay_creds)
    {
        $creds_arr_razorpay = json_decode($razorpay_creds->credentials);
        $api_key_razorpay = (isset($creds_arr_razorpay->api_key)) ? $creds_arr_razorpay->api_key : '';
    }
    return $api_key_razorpay;
}

function dateTimeInUserTimeZone($date, $timezone, $showDate=true, $showTime=true, $showSeconds=false){
    $preferences = ClientPreference::select('date_format', 'time_format')->where('id', '>', 0)->first();
    $date_format = (!empty($preferences->date_format)) ? $preferences->date_format : 'YYYY-MM-DD';
    if($date_format == 'DD/MM/YYYY'){
        $date_format = 'DD-MM-YYYY';
    }
    $time_format = (!empty($preferences->time_format)) ? $preferences->time_format : '24';
    $date = Carbon::parse($date, 'UTC');
    $date->setTimezone($timezone);
    $secondsKey = '';
    $timeFormat = '';
    $dateFormat = '';
    if($showDate){
        $dateFormat = $date_format;
    }
    if($showTime){
        if($showSeconds){
            $secondsKey = ':ss';
        }
        if($time_format == '12'){
            $timeFormat = ' hh:mm'.$secondsKey.' A';
        }else{
            $timeFormat = ' HH:mm'.$secondsKey;
        }
    }

    $format = $dateFormat . $timeFormat;
    return $date->isoFormat($format);
}

function dateTimeInUserTimeZone24($date, $timezone, $showDate=true, $showTime=true, $showSeconds=false){
    $preferences = ClientPreference::select('date_format', 'time_format')->where('id', '>', 0)->first();
    $date_format = (!empty($preferences->date_format)) ? $preferences->date_format : 'YYYY-MM-DD';
    if($date_format == 'DD/MM/YYYY'){
        $date_format = 'DD-MM-YYYY';
    }
    $time_format = (!empty($preferences->time_format)) ? $preferences->time_format : '24';
    $date = Carbon::parse($date, 'UTC');
    $date->setTimezone($timezone);
    $secondsKey = '';
    $timeFormat = '';
    $dateFormat = '';
    if($showDate){
        $dateFormat = $date_format;
    }
    if($showTime){
        if($showSeconds){
            $secondsKey = ':ss';
        }
    $timeFormat = ' HH:mm'.$secondsKey; 
    }

    $format = $dateFormat . $timeFormat;
    return $date->isoFormat($format);
}

function helper_number_formet($number){
    return number_format($number,2);
}
function productvariantQuantity($variantId ,$type=1){
    if($type==1){
        $ProductVariant =  ProductVariant::where('id',$variantId)
        ->select('quantity')->first();
    }else{
        $ProductVariant =  ProductVariant::where('sku',$variantId)
        ->select('quantity')->first();
    }
    if($ProductVariant){
    return  $ProductVariant->quantity;
    }
    return "variant not found";
}
function checkImageExtension($image)
{
    $ch =  substr($image, strpos($image, ".") + 1);
    $ex = "@webp";
    if($ch == 'svg')
    {
        $ex = "";
    }
    return $ex;
}
function getDefaultImagePath()
{
    $values = array();
    $img = 'default/default_image.png';
    $values['proxy_url'] = \Config::get('app.IMG_URL1');
    $values['image_path'] = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($img).'@webp';
    $values['image_fit'] = \Config::get('app.FIT_URl');
    return $values;
}
function getImageUrl($image,$dim)
{
    $server = env('APP_ENV', 'development');
    if($server == 'local')
    {
        return $image;
    }
    return \Config::get('app.FIT_URl').$dim.\Config::get('app.IMG_URL2').'/'.$image.'@webp';
}


function getUserIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function createSlug($str, $delimiter = '-'){

    $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
    return $slug;

}

    function getBaseprice($dist)
    {

        $simp_creds = ShippingOption::select('credentials', 'test_mode','status')->where('code', 'lalamove')->where('status', 1)->first();
        if($simp_creds && $simp_creds->credentials){
            $creds_arr = json_decode($simp_creds->credentials);
            $base_price = $creds_arr->base_price??'0';
            if($base_price>0)
            {
                $distance = $creds_arr->distance??'0';
                $amount_per_km = $creds_arr->amount_per_km??'0';

            }
            $lalamove_status = $simp_creds->status??'';
        }

        
        $distance = ($dist - $distance);  
        if($distance < 1 || $base_price < 1)
        {
            return 0;    
        }

        $base_price = $base_price;
        $amount_per_km = $amount_per_km;
        $total = $base_price + ($distance * $amount_per_km);
        return  $total;
        
    // + ($paid_duration * $pricingRule->duration_price);

    }


function SplitTime($myDate,$StartTime, $EndTime, $Duration="60",$delayMin = 5)
{
     $Duration = (($Duration==0)?'60':$Duration);
    // $user = Auth::user();
    // $cr = Carbon::now()->addMinutes($delayMin);
    // $now =  dateTimeInUserTimeZone24($cr, $user->timezone);
    // $nowT = Carbon::createFromFormat('Y-m-d H:i', $now)->timestamp;
    // $nowA = Carbon::createFromFormat('Y-m-d H:i:s', $myDate.' '.$StartTime);
    // $nowS = Carbon::createFromFormat('Y-m-d H:i:s', $nowA)->timestamp;
    // $nowE = Carbon::createFromFormat('Y-m-d H:i:s', $myDate.' '.$EndTime)->timestamp;
    // if($nowT > $nowE)
    // {
    //     return [];
    // }elseif($nowT>$nowS)
    // {
    //     $StartTime = date('H:i',strtotime($now));
    // }else{
    //     $StartTime = date('H:i',strtotime($nowA));
    // }


    $ReturnArray = array ();
    $StartTime    = strtotime ($StartTime); //Get Timestamp
    $EndTime      = strtotime ($EndTime); //Get Timestamp
    $AddMins  = $Duration * 60;
    $endtm = 0;

    while ($StartTime <= $EndTime) 
    {
        $endtm = $StartTime + $AddMins;
        if($endtm>$EndTime)
        {
         $endtm =  $EndTime;
        }

        $ReturnArray[] = date ("G:i", $StartTime).' - '.date ("G:i", $endtm);
        $StartTime += $AddMins+60; 
        $endtm = 0;
    }
    //dd($ReturnArray);
    
    return $ReturnArray;
}

function showSlot($myDate = null,$vid,$type = 'delivery',$duration="60")
{
  $type = ((session()->get('vendorType'))?session()->get('vendorType'):$type);
//type must be a : delivery , takeaway,dine_in
$client = ClientData::select('timezone')->first();
$viewSlot = array();
   if(!empty($myDate))
   {        $mytime = Carbon::createFromFormat('Y-m-d', $myDate)->setTimezone($client->timezone);
   }else{ 
    //$myDate  = date('Y-m-d',strtotime('+1 days')); 
    $myDate  = date('Y-m-d'); 
    $mytime = Carbon::createFromFormat('Y-m-d', $myDate)->setTimezone($client->timezone);
    }
    $mytime =$mytime->dayOfWeek+1;
    $slots = VendorSlot::with('dayOne')->where('vendor_id',$vid)
    ->whereHas('days',function($q)use($mytime,$type){
        return $q->where('day',$mytime)->where($type,'1');
    })
    ->get();
    $min[] = '';
    $cart = CartProduct::where('vendor_id',$vid)->get();
    foreach($cart as $product)
    {
       $min[] = (($product->product->delay_order_hrs * 60) + $product->product->delay_order_min);
    }

    if(isset($slots) && count($slots)>0){
        foreach($slots as $slott){
            if(isset($slott->dayOne->id))
            {   
               $slotss[] = SplitTime($myDate,$slott->start_time,$slott->end_time,$duration,max($min));
            }else{
                $slotss[] = [];
            }
        }

    $arr = array();
    $count = count($slotss);
   // if($count>1){
        for($i=0;$i<$count;$i++){
            $arr = array_merge($arr,$slotss[$i]);
        }
   // }
    
    if(isset($arr)){
        foreach($arr as $k=> $slt)
        {
            $sl = explode(' - ',$slt);
            $viewSlot[$k]['name'] = date('h:i:A',strtotime($sl[0])).' - '.date('h:i:A',strtotime($sl[1]));
            $viewSlot[$k]['value'] = $slt;
        }
      }
    }
    
    return $viewSlot;
}

function findSlot($myDate = null,$vid,$type = 'delivery')
{
  $myDate  = date('Y-m-d',strtotime('+1 day')); 
  $type = ((session()->get('vendorType'))?session()->get('vendorType'):$type);
        $slots = showSlot($myDate,$vid,'delivery');
            if(count((array)$slots) == 0){
                $myDate  = date('Y-m-d',strtotime('+1 day')); 
                $slots = showSlot($myDate,$vid,'delivery');
            }
            //dd($myDate);
            if(count((array)$slots) == 0){
                $myDate  = date('Y-m-d',strtotime('+1 day')); 
                $slots = showSlot($myDate,$vid,'delivery');
            }

            if(count((array)$slots) == 0){
                $myDate  = date('Y-m-d',strtotime('+2 day')); 
                $slots = showSlot($myDate,$vid,'delivery');
            }

        if(isset($slots) && count((array)$slots)>0){
            $time = explode(' - ',$slots[0]['value']);
            return date('d M, Y h:i:A',strtotime($myDate.'T'.$time[0]));
        }else{
            return date('d M, Y',strtotime($myDate));
        }
}
