<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\PaymentOption;
use Log;
trait GCashpaymentManager{

  private $public_key;
  public function __construct()
  {
    $gcash_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'gcash')->where('status', 1)->first();
    $creds_arr = json_decode($gcash_creds->credentials);
    $this->public_key = $creds_arr->public_key??'';
    $this->api_url = "https://g.payx.ph";
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
      'x-public-key' => ' pk_test_6816779fa0fbfa26805b7cd798cc31da',
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


    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response);
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
}
