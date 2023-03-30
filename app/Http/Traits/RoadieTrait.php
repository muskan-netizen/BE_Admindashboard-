<?php

namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\ShippingOption;
use App\Models\Webhook;
use Illuminate\Support\Facades\Http;


trait RoadieTrait{

  private $api_access_token;
  private $api_base_url;


  public function configDetails()
  {
    $simp_creds = ShippingOption::select('credentials', 'test_mode')->where('code', 'roadie')->where('status', 1)->first();
    $creds_arr = json_decode($simp_creds->credentials);
    $this->api_access_token = $creds_arr->api_access_token??'';
    $this->api_base_url = $creds_arr->api_base_url ?? '';
  }


  //Quotation Function Api
  public function getQuotations($data)
  {
    $this->configDetails();
    $path = '/v1/estimates';
    $response = Http::withHeaders([
      'Content-Type' => 'application/json',
      'Authorization' => 'Bearer e12005b2acd50631664dc802675883c8bfbddd2e'
    ])->post($this->api_base_url.$path, $data);
    $statusCode = $response->getStatusCode();
    // pr(json_decode($response));
    if ($statusCode == 200) {
      $price = json_decode($response)->price;
      return array('code'=>$statusCode,'price'=>$price);       
    }
}

//Order Array Body
public function getOrderBody($data)
{
  $quotedFee = array(
        'amount' => $data->totalFee,
        'currency' => $data->totalFeeCurrency
    );
  
  $quotedTotalFee = array(
    'quotedTotalFee' => $quotedFee,
    'sms' => true, //Send delivery updates via SMS to THE recipient, or the recipient of the LAST STOP for multi-stop orders once the order has been picked-up by the driver. default 'true'
    'pod' => false, //Request driver to carry out "Proof Of Delivery" for all stops in the order. Default to false. See Proof Of Delivery for details.
  );

  return json_encode($quotedTotalFee);

  }


public function placeOrders($data,$quotation,$order_id = '')
{
    $this->configDetails();
    $method = 'POST';
    $path = '/v2/orders';
    $bodyQut = $this->getQuotationBody($data);
    $bodyOrder = $this->getOrderBody($quotation);
    $body=json_encode(array_merge(json_decode($bodyQut, true),json_decode($bodyOrder, true)));
    $token = $this->token($method,$path,$body);

  
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $this->base_url.$path,
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
        //"Accept: application/json",
        "X-LLM-Market: $this->region" // Please note to which city are you trying to make API call
    ),
));

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

// echo "Total elapsed http request/response time in milliseconds: ".floor((microtime(true) - $this->startTime)*1000)."\r\n";
//\Log::info('Place Order response Mail');
//\Log::info('orderRef = '.json_decode($response)->orderRef);
//\Log::info($response);
//\Log::info('End Place Order response Mail');
$resp = json_decode($response);
Webhook::create(['tracking_order_id'=>$order_id,'response'=>$response]);
if($resp->orderRef){
  return $resp;
 }
 return false;




// Response
// {
//   "orderRef": "193400800238",
//   "totalFee": "43.60",
//   "totalFeeCurrency": "MYR",
//   "distance": {
//       "text": "51.2 km",
//       "value": 51222
//   }
// }
  
} 

public function testing()
{

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://rest.sandbox.lalamove.com/v2/orders',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{"serviceType":"MOTORCYCLE","specialRequests":[],"requesterContact":{"name":"General Electric","phone":"8965745236"},"stops":[{"location":{"lat":"3.115825684565","lng":"101.666775521484"},"addresses":{"en_MY":{"displayString":"Malaysia","market":"MY_KUL"}}},{"location":{"lat":"3.144354220271","lng":"101.710811322266"},"addresses":{"en_MY":{"displayString":"g107, Jln Bukit Bintang, Bukit Bintang, 55100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia","market":"MY_KUL"}}}],"deliveries":[{"toStop":1,"toContact":{"name":"pankaj","phone":"7520822619"},"remarks":"Delivery vendor message remarks"}],"quotedTotalFee":{"amount":"11.00","currency":"MYR"},"sms":false,"pod":false}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: hmac pk_test_11c917c792586a46bef122660d6e04b9:1640684549815:5aa5d38a69dad05defa0943d4308559d30f8fdc20651bf85f98e9acaa60db64e',
    'X-LLM-market: MY_KUL'
  ),
));

$response = curl_exec($curl);
curl_close($curl);
return $response;
}


public function orderDetails($orderReff)
  {
  $this->configDetails();
  $method = 'GET';
  $path = '/v2/orders/'.$orderReff;
  $token = $this->token($method,$path);
  $curl = curl_init();
  curl_setopt_array($curl, array(
  CURLOPT_URL => $this->base_url.$path,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => $method,
  CURLOPT_HTTPHEADER => array(
    "Content-type: application/json; charset=utf-8",
    "Authorization: hmac ".$token,
    "X-LLM-Market: {$this->region}"
    ),
  ));

  $response = curl_exec($curl);
  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  curl_close($curl);

  return array('code'=>$httpCode,'response'=>$response);

// Response
//   {
//     "driverId": "",
//     "shareLink": "https://share.sandbox.lalamove.com?MY100211216170231071410010085148835&lang=en_MY&sign=dd4390429b25b1bd6e24eaf8c1bef13b&source=api_wrapper",
//     "status": "ASSIGNING_DRIVER",
//     "pod": null,
//     "price": {
//         "amount": "43.60",
//         "currency": "MYR"
//     },
//     "distance": {
//         "text": "51.2 km",
//         "value": 51222
//     }
// }


  }

  public function orderDriverDetail($orderid,$driverId)
  {

    $this->configDetails();

    $method = 'GET';
    $path = '/v2/orders/'.$orderid.'/drivers/'.$driverId;
    $token = $this->token($method,$path);
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $this->base_url.$path,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_HTTPHEADER => array(
        "Content-type: application/json; charset=utf-8",
        "Authorization: hmac ".$token,
        "X-LLM-Market: {$this->region}"
      ),
    ));

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
   return array('code'=>$httpCode,'response'=>$response);


  }





  public function orderCancel($orderReff)
  {
    $this->configDetails();

    $method = 'GET';
    $path = '/v2/orders/'.$orderReff.'/cancel';
    $token = $this->token($method,$path);

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $this->base_url.$path,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_HTTPHEADER => array(
        "Content-type: application/json; charset=utf-8",
        "Authorization: hmac ".$token,
        "X-LLM-Market: {$this->region}"
      ),
    ));

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return array('code'=>$httpCode,'response'=>$response);


  }






}