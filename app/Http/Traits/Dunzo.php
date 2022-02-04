<?php

namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\ShippingOption;

trait Dunzo{

  private $api_key;
  private $app_url;
  private $base_price;
  private $distance;
  private $amount_per_km;
  public $status;

/**
 * Dunzo integrations Details
 * Req Api Key
 * Req App URl
 */

  public function __construct()
  {
    $simp_creds = ShippingOption::select('credentials', 'test_mode','status')->where('code', 'dunzo')->where('status', 1)->first();
    if($simp_creds){
        $this->status = $simp_creds->status??'0';
        $creds_arr = json_decode($simp_creds->credentials);
        $this->api_key = $creds_arr->api_key??'';
        $this->app_url = (($simp_creds->test_mode=='1')?'https://dev.adloggs.com/aa':'https://app.adloggs.com/aa'); //Live url - https://app.adloggs.com/aa
        $this->base_price = $creds_arr->base_price ?? ''; 
        $this->distance = $creds_arr->distance ?? ''; 
        $this->amount_per_km = $creds_arr->amount_per_km ?? '';
    }else{
        return 0;
    }
  }

  public function configDetails()
  {
    $simp_creds = ShippingOption::select('credentials', 'test_mode','status')->where('code', 'dunzo')->where('status', 1)->first();
    if($simp_creds){
        $this->status = $simp_creds->status??'0';
        $creds_arr = json_decode($simp_creds->credentials);
        $this->api_key = $creds_arr->api_key??'';
        $this->app_url = (($simp_creds->test_mode=='1')?'https://dev.adloggs.com/aa':'https://app.adloggs.com/aa'); //Live url - https://app.adloggs.com/aa
        $this->base_price = $creds_arr->base_price ?? ''; 
        $this->distance = $creds_arr->distance ?? ''; 
        $this->amount_per_km = $creds_arr->amount_per_km ?? '';
    }else{
        return 0;
    }
  }


  //Quotation Function Api
  public function getQuotations($data)
  {
    $this->configDetails();
    
    $method = 'POST';
    $path = '/v2/quotations';
    $body = $this->getQuotationBody($data);
    $token = $this->token($method,$path,$body);
  
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $this->app_url.$path,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 3,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HEADER => false, // Enable this option if you want to see what headers Lalamove API returning in response
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $body,
      CURLOPT_HTTPHEADER => array(
          "Content-type: application/json; charset=utf-8",
          "Authorization: hmac ".$token, // A unique Signature Hash has to be generated for EVERY API call at the time of making such call.
          "Accept: application/json",
          "X-LLM-Market: {$this->region}" // Please note to which city are you trying to make API call
      ),
  ));
  
  $response = curl_exec($curl);
  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  curl_close($curl);
  return array('code'=>$httpCode,'response'=>$response);
}


public function createOrder($data)
{
    $this->configDetails();
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => $this->app_url."/oporder/create",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => array(
        "apikey: {$this->api_key}",
        "cache-control: no-cache",
        "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
    ),
    ));

    $response = curl_exec($curl);
    //dd($response);
    $err = curl_error($curl);

    curl_close($curl);
    if ($err) {
    return  $err;
    } else {
    return $response;
    }

} 


  public function orderDetail($id)
  {
    $data = array('order_uuid'=>$id);
    $this->configDetails();
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $this->app_url."/aa/oporder/getbyid",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => array(
        "apikey: {$this->api_key}",
        "cache-control: no-cache",
        "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
    ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
    echo "cURL Error #:" . $err;
    } else {
    echo $response;
    }

  }


  public function checkAvilabilty($data)
  {
        $this->configDetails();
        $curl = curl_init();
     
        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->app_url."/oporder/dscheck",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            "apikey: ".$this->api_key,
            "cache-control: no-cache",
            "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
            "postman-token: a044fdc2-2ad4-5ce8-504c-fd5e9b2773e1"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return (object)json_decode($response,true);
        }

        // "status": true,
        // "code": 200,
        // "message": "Riders available",
        // "data": {}

  }


  public function cancelOrder($data)
  {
    $this->configDetails();
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => $this->app_url."/oporder/update",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => array(
        "apikey: {$this->api_key}",
        "cache-control: no-cache",
        "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
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






}