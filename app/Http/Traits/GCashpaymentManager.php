<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\PaymentOption;
use Log;
trait GCashpaymentManager{

  private $api_key;
  private $secret_key;
  private $merchant_account;
  public function __construct()
  {
    $gcash_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'gcash')->where('status', 1)->first();
    $creds_arr = json_decode($gcash_creds->credentials);
    $this->api_key = $creds_arr->api_key??'';
    $this->secret_key = $creds_arr->secret_key??'';
    $this->merchant_account = $creds_arr->merchant_account??'';
    $this->api_url = "https://g.payx.ph";
  }

  public function gcash_payment($data)
  {
    // Set your X-API-KEY with the API key from the Customer Area.
    $client = new \Adyen\Client();
    $client->setXApiKey($this->api_key);
    $client->setEnvironment(\Adyen\Environment::TEST);
    $service = new \Adyen\Service\Checkout($client);
     
    $params = array(
      "amount" => array(
        "currency" => "PHP",
        "value" => (int)$data['amount'] * 100,
      ),
      "reference" => rand(100000,99999),
      "paymentMethod" => array(
        "type" => "gcash"
      ),
      "returnUrl" => asset($data['returnUrl']),
      "merchantAccount" => $this->merchant_account
    );
    $result = $service->payments($params);
    return $result;
  }
  public function createSession($data)
  {
    $client = new \Adyen\Client();
    $client->setApplicationName('Test Application');
    $client->setEnvironment(\Adyen\Environment::TEST);
    $client->setXApiKey($this->api_key);
    $service = new \Adyen\Service\Checkout($client);
    $params = array(
       'amount' => array(
           'currency' => "EUR",
           'value' => (int)$data['amount'] * 100
       ),
       'countryCode' => 'NL',
       'merchantAccount' => $this->merchant_account,
       'reference' => rand(100000,99999),
       'returnUrl' => asset($data['returnUrl'])
    );
    $result = $service->sessions($params);
    $result['secret_key'] = $this->secret_key;
    return $result;
  }
  public function createLink($data)
  {
    $endpoint="/paymentLinks";
    $request = [
      "reference" => rand(100000,999999),
      "amount"=> [
        "value"=> 4200,
        "currency"=> "EUR"
      ],
      "shopperReference"=> "eryetrgfggfhfgh",
      "description"=> "Blue Bag - ModelM671",
      "countryCode"=> "NL",
      "merchantAccount"=> $this->merchant_account,
      "shopperLocale"=> "nl-NL"
    ];
    $response=$this->postCurl($endpoint,$request);
    return $response;
  }
  public function createPaymentRequest($data)
  {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://g.payx.ph/payment_request',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array(
      'x-public-key' => 'pk_test_940c5f00bdb441f436fa5e96eb2a7864',
      'amount' => '100',
      'description' => 'Payment for services rendered',
      'customername' => Auth::user()->name??'',
      'customermobile' => Auth::user()->phone_number,
      'customeremail' => Auth::user()->email??'',
      'merchantname' => getClientDetail()->company_name,
      'merchantlogourl' => getClientDetail()->logo_image_url,
      // 'redirectsuccessurl' => asset($data['returnSuccessUrl']),
      // 'redirectfailurl' => asset($data['returnFailureUrl'])
      'redirectsuccessurl' => 'https://sales.alerthire.com',
      'redirectfailurl' => 'https://sales.alerthire.com',
      'returnUrl' => 'https://sales.alerthire.com'
      ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response);


  }
  private function postCurl($endpoint,$data,$token=null):object{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->api_url.''.$endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
    $headers = array();
    $headers[] = 'Accept: */*';
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'x-API-key: '.$this->api_key;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    return json_decode($result); 
  }

    private function getCurl($endpoint,$token=null):object{
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, env('SHIP_ROCKET_API_URL').''.$endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            $headers = array();
            $headers[] = 'Accept: */*';
            if(!is_null($token)){
                $headers[] = "Authorization: Bearer $token";
            }
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
            return json_decode($result); 
}

private function patchCurl($endpoint,$data,$token=null):object{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, env('SHIP_ROCKET_API_URL').''.$endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
    $headers = array();
    $headers[] = 'Accept: */*';
    if(!is_null($token)){
        $headers[] = "Authorization: Bearer $token";
    }
    $headers[] = 'Content-Type: application/json';
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    return json_decode($result); 

}

}
