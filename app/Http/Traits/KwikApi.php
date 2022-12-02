<?php

namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\ShippingOption;
use Http\Message\Cookie;
use Illuminate\Http\Response;
use Log;

trait KwikApi{

  private $api_key;
  private $app_url;
  private $base_price;
  private $distance;
  private $amount_per_km;
  public $status;
  public $test;

/**
 * Dunzo integrations Details
 * Req Api Key
 * Req App URl
 */

//   public function __construct()
//   {
//     $simp_creds = ShippingOption::select('credentials', 'test_mode','status')->where('code', 'kwikapi')->where('status', 1)->first();
//     if($simp_creds){
//         $this->status = $simp_creds->status??'0';
//         $creds_arr = json_decode($simp_creds->credentials);
//         $this->api_email = $creds_arr->api_email??'';
//         $this->api_pass = $creds_arr->api_pass??'';
//         $this->app_url = (($simp_creds->test_mode=='1')?'https://staging-api-test.kwik.delivery':'https://staging-api-test.kwik.delivery'); //Live url - 
//         $this->test = $simp_creds->test_mode; 
//         $this->base_price = $creds_arr->base_price ?? ''; 
//         $this->distance = $creds_arr->distance ?? ''; 
//         $this->amount_per_km = $creds_arr->amount_per_km ?? '';
//     }else{
//         return 0;
//     }
//   }

  public function configDetails()
  {
    $simp_creds = ShippingOption::select('credentials', 'test_mode','status')->where('code', 'kwikapi')->where('status', 1)->first();
    if($simp_creds){
        $this->status = $simp_creds->status??'0';
        $creds_arr = json_decode($simp_creds->credentials);
        $this->api_email = $creds_arr->api_email??'';
        $this->api_domain = $creds_arr->api_domain??"staging-client-panel.kwik.delivery";
        $this->api_pass = $creds_arr->api_pass??'';
        $this->app_url = (($simp_creds->test_mode=='1')?'https://staging-api-test.kwik.delivery':'https://staging-api-test.kwik.delivery'); //Live url - 
        $this->test = $simp_creds->test_mode; 
        $this->base_price = $creds_arr->base_price ?? ''; 
        $this->distance = $creds_arr->distance ?? ''; 
        $this->amount_per_km = $creds_arr->amount_per_km ?? '';
    }else{
        return 0;
    }
  }


  //Vendor Auth Login Api
  public function authLoginVendor()
  {
     
    try{
        
        $end_url = '/vendor_login';
        $this->configDetails();
        $ch = curl_init();

        $data = ["domain_name"=> $this->api_domain,
            "email"=> $this->api_email,
            "password"=> $this->api_pass,
            "api_login"=> 1];

        curl_setopt($ch, CURLOPT_URL, $this->app_url.$end_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json"
        ));

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch)) {
            $httpCode =  curl_error($ch);
        }
        curl_close($ch);
        $val = json_decode($result);
        $res = [];
        if(isset($val) && $val->status == '200')
        {
            $this->access_token=$val->data->access_token;
            $this->vendor_id=$val->data->vendor_details->vendor_id;
        }
    }catch (\Exception $e) {
        return array('code'=>400,'response'=>$e->getMessage());
    }
}


//Price estimation Api
public function getPriceEstimation()
{
      $end_url = '/send_payment_for_task';
      $this->configDetails();
      $ch = curl_init();

      $data = [      
            "custom_field_template"=> "pricing-template",
            "access_token"=> $this->access_token,
            "domain_name"=> $this->api_domain,
            "timezone"=> -330,
            "vendor_id"=> $this->vendor_id,
            "is_multiple_tasks"=> 1,
            "layout_type"=> 0,
            "pickup_custom_field_template"=> "pricing-template",
            "deliveries"=> [[
            "address"=> "Net2Source, Sector 22, Panchkula, Haryana, India",
            "name"=> "sjcdgyudts",
            "latitude"=> 30.6951827,
            "longitude"=> 76.8794589,
            "time"=> "2020-10-28 17:07:54",
            "phone"=> "+918989898989",
            "has_return_task"=> false,
            "is_package_insured"=> 0
            ]],
            "has_pickup"=> 1,
            "has_delivery"=> 1,
            "auto_assignment"=> 1,
            "user_id"=> 1,
            "pickups"=> [[
              "address"=> "CDCL, Madhya Marg, 28B, Sector 28B, Chandigarh, India",
              "name"=> "Corporate Grg",
              "latitude"=> 30.7188978,
              "longitude"=> 76.8102981,
              "time"=> "2020-10-28 17:07:54",
              "phone"=> "+919885968596",
              "email"=> "co@yopmail.com"
            ]],
            "payment_method"=> 8,
            //"form_id"=> 2,
            //"vehicle_id"=> 4,
            "delivery_instruction"=> "Hey,Please deliver the parcel with safety.Thanks in advance",
            //"delivery_images"=> "https://s3.ap-south-1.amazonaws.com/kwik-project/task_images/kjjX1603884709732-stripeconnect.png",
            "is_loader_required"=> 1,
            "loaders_amount"=> 40,
            "loaders_count"=> 4,
            // "is_cod_job"=> 0,
            "parcel_amount"=> 1000
          ];

      curl_setopt($ch, CURLOPT_URL, $this->app_url.$end_url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HEADER, FALSE);
      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Content-Type: application/json"
      ));

      $result = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      if (curl_errno($ch)) {
          $httpCode =  curl_error($ch);
      }
      curl_close($ch);
      $result = json_decode($result);
    return $result->data->per_task_cost;
}


public function createPreOrder()
{
    $this->configDetails();
    $end_url = '/create_task_via_vendor';

    $data = [
        "domain_name"=> $this->api_domain,
        "access_token"=> $this->access_token,
        "vendor_id"=> $this->vendor_id,
        "is_multiple_tasks"=> 1,
        "timezone"=> 60,
        "has_pickup"=> 1,
        "has_delivery"=> 1,
        "pickup_delivery_relationship"=> 0,
        "layout_type"=> 0,
        "auto_assignment"=> 1,
        "is_schedule_task"=> "1", ///For Schedule task
        "pickups"=> [[
        "address"=> "CDCL, Madhya Marg, 28B, Sector 28B, Chandigarh, India",
        "name"=> "Corporate Grg",
        "latitude"=> 30.7188978,
        "longitude"=> 76.8102981,
          "time"=> "2022-12-02 17:35:37",
          "phone"=> "+23467194727",
          "email"=> "charlesobum@gmail.com"
        ]],
        "deliveries"=> [[
          "address"=> "Net2Source, Sector 22, Panchkula, Haryana, India",
          "name"=> "sjcdgyudts",
          "latitude"=> 30.6951827,
          "longitude"=> 76.8794589,
          "time"=> "2022-12-03T19:55:32.800Z",
          "phone"=> "+2348067194727",
          "has_return_task"=> false,
          "is_package_insured"=> 0,
          "hadVairablePayment"=> 1,
          "hadFixedPayment"=> 0
        ]],
        "payment_method"=> 8,
        "amount"=> 100,
        "delivery_charge_by_buyer"=> 1,
        "delivery_charge"=> 20,
        "delivery_instruction"=> "Hey,Please handover parcel with safety.\nThanks",
        // "delivery_images"=> "https://s3.ap-south-1.amazonaws.com/kwik-project/task_images/wPqj1603886372690-stripeconnect.png",
        //"vehicle_id"=> 4
        ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->app_url.$end_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json"
        ));

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        $httpCode =  curl_error($ch);
    }
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return json_decode($result);
} 


public function confirmPreOrder($data)
{
    $this->configDetails();
    if($this->test==1){
        $end_url = 'https://ahoydev.azure-api.net/delivery/deliveryrequest?Subscriptionkey='.$this->api_key;
    }else{
        $end_url = 'https://ahoyapis.azure-api.net/DeliveryIntegrationAPIProd/CreateOrderFromPreOrderFunction?Subscriptionkey='.$this->api_key;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $end_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $headers = array();
    $headers[] = 'Subscriptionkey: '.$this->api_key;
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Ocp-Apim-Subscription-Key: '.$this->api_key;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (curl_errno($ch)) {
        $httpCode =  curl_error($ch);
    }
    curl_close($ch);
    \Log::info($result);
    return json_decode($result);
} 

  public function createNewLocation($data)
  {
    $this->configDetails();
    if($this->test==1){
        $end_url = 'https://ahoydev.azure-api.net/merchant/newLocaion?Subscriptionkey='.$this->api_key;
    }else{
        $end_url = 'https://ahoyapis.azure-api.net/merchant/newLocaion?Subscriptionkey='.$this->api_key;
    }   
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $end_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $headers = array();
    $headers[] = 'Subscriptionkey: '.$this->api_key;
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Ocp-Apim-Subscription-Key: '.$this->api_key;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (curl_errno($ch)) {
        $return =  curl_error($ch);
    }
    curl_close($ch);
    return array('code'=>$httpCode,'response'=>json_decode($result));
  }


  public function cancelOrder($data)
  {
    $this->configDetails();
    $curl = curl_init();

    $end_url = '/cancel_vendor_task';
    
    $data = [      
        "domain_name"=> $this->api_domain,
        "access_token"=> $this->access_token,
        "vendor_id"=> $this->vendor_id,
        "job_id"=> 1, //fetch this id from order cancel
        "job_status"=> 1
        ];
        
    curl_setopt_array($curl, array(
    CURLOPT_URL => $this->app_url.$end_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "PUT",
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        return $err;
    } else {
        return $response;
    }

  }


  public function setWebhookUrl($url)
  {
    $this->configDetails();
    if($this->test==1){
        $end_url = 'https://ahoydev.azure-api.net/delivery/orderwebhook?Subscriptionkey='.$this->api_key;
    }else{
        $end_url = 'https://ahoyapis.azure-api.net/delivery/orderwebhook?Subscriptionkey='.$this->api_key;
    } 
    $curl = curl_init($end_url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_URL, $end_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    # Request headers
    $headers = array(
        'Content-Type: application/json',
        'Cache-Control: no-cache',
        'Ocp-Apim-Subscription-Key: '.$this->api_key,);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    
    # Request body
    $request_body = '{
        "endpoint": "'.$url.'",
    }';
    curl_setopt($curl, CURLOPT_POSTFIELDS, $request_body);
    
    $resp = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $err = curl_error($curl);
    \Log::info($err);
    curl_close($curl);
    if($httpCode == '200'){
        return response()->json(['code'=>'200','msg'=>'Webhook url is set.']);
    }else{
        return response()->json(['code'=>'400','msg'=>'Error']);
    }
  }



}