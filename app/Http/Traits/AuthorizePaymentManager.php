<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\PaymentOption;
use Omnipay\Omnipay;
use Log;
trait AuthorizePaymentManager{

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
        'opaqueDataDescriptor' => $data['opaqueDataDescriptor'],
        'opaqueDataValue' => $data['opaqueDataValue'],
      ]);
      // $request->setOpaqueData($data['opaqueDataDescriptor'], $data['opaqueDataValue']);
      $request->setToken($data['opaqueDataDescriptor'] . ':' . $data['opaqueDataValue']);
      $response = $request->send();
      dd($response, $response->isSuccessful());

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
