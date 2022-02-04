<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\PaymentOption;
use Omnipay\Omnipay;
use Log;
trait AuthorizePaymentManager{

  private $login_id;
  private $client_key;
  public function __construct()
  {
    $anet_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'authorize_net')->where('status', 1)->first();
    $creds_arr = json_decode($anet_creds->credentials);
    $this->login_id = $creds_arr->login_id??'';
    $this->transaction_key = $creds_arr->transaction_key??'';
    $this->client_key = $creds_arr->client_key??'';
  }

  public function init()
  {
    $gateway = Omnipay::create('AuthorizeNetApi_Api');
    $gateway->setAuthName($this->login_id);
    $gateway->setTransactionKey($this->transaction_key);
    $gateway->setTestMode(true);
    return $gateway;
  }

  public function create_payment($data)
  {
    $gateway = $this->init();
    $transactionId = rand(100000000, 999999999);
    try {
      $request = $gateway->authorize([
        'amount' => '7.99',
        'currency' => 'USD',
        'transactionId' => $transactionId,
      ]);
      $request->setOpaqueData($data['opaqueDataDescriptor'], $data['opaqueDataValue']);
      $request->setToken($$data['opaqueDataDescriptor'] . ':' . $data['opaqueDataValue']);
      $response = $request->send();
      dd($response);

      if($response->isSuccessful()) {
        // Captured from the authorization response.
        $transactionReference = $response->getTransactionReference();
        $response = $gateway->capture([
          'amount' => '7.99',
          'currency' => 'USD',
          'transactionReference' => $transactionReference,
        ])->send();
        $transaction_id = $response->getTransactionReference();
      }
    }catch(Exception $e) {
        return [
            'auth_message' => $e->getMessage()
        ];
    }
  }

}
