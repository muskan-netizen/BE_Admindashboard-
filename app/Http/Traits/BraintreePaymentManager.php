<?php
namespace App\Http\Traits;
use App\Models\PaymentOption;
use Auth, Log, Config;
trait BraintreePaymentManager{

  public function init(){
    return Braintree\Gateway([
      'environment' => 'sandbox',
      'merchantId' => $this->merchant_id,
      'publicKey' => $this->public_key,
      'privateKey' => $this->private_key
    ]);
  }
  public function createCustomer()
  {
    $gateway = $this->init();
    $result = $gateway->customer()->create([
      'firstName' => 'Mike',
      'lastName' => 'Jones',
      'company' => 'Jones Co.',
      'email' => 'mike.jones@example.com',
      'phone' => '281.330.8004',
      'fax' => '419.555.1235',
      'website' => 'http://example.com'
    ]);
    dd($result);
    $result->success;
    # true

    $result->customer->id;
  }


}
