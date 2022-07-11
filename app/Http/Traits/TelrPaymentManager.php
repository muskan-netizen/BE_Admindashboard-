<?php
namespace App\Http\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException; 
use Log;
trait TelrPaymentManager{

  public function createPaymentpage($data) 
  {
    $cart_id =  uniqid();
    $after_url = $data['payment_from']."/".$data['come_from'].'/'.$data['amount']."/".($data["order_number"]??0);

    $formParams = [
        "ivp_method" => "create",
        "ivp_store" => (int)$this->merchant_id,
        "ivp_authkey" => $this->api_key,
        "ivp_cart" => $cart_id,
        "ivp_test" => true,
        "ivp_amount" => "100",
        "ivp_currency" => "AED",
        "ivp_desc" => "DESCRIPTION ...",
        "ivp_lang" => "en",
        "return_auth" => $this->url."/success/".$after_url."?cart_id=".$cart_id,
        "return_can" => $this->url."/cancel/".$after_url."?cart_id=".$cart_id,
        "return_decl" => $this->url."/declined/".$after_url."?cart_id=".$cart_id,
        "bill_fname" => "Moustafa Gouda",
        "bill_sname" => "Bafi",
        "bill_addr1" => "Gnaklis",
        "bill_addr2" => "Gnaklis 2",
        "bill_city" => "Alexandria",
        "bill_region" => "San Stefano",
        "bill_zip" => "11231",
        "bill_country" => "EG",
        "bill_email" => "example@company.com"
    ];
    // dd($formParams);
    $endPoint = "https://secure.telr.com/gateway/order.json";
    $client = new Client();
    $result = $client->post($endPoint, ['form_params' => $formParams]);
    if ($result->getStatusCode() == 200) {
        return \GuzzleHttp\json_decode($result->getBody()->getContents());
    }
  }
}
