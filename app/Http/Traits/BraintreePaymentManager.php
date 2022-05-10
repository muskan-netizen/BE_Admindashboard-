<?php
namespace App\Http\Traits;
use App\Models\PaymentOption;
use Auth, Log, Config;
trait BraintreePaymentManager{

  public function init(){
    $gateway = new Braintree\Gateway([
      'environment' => 'sandbox',
      'merchantId' => $this->merchant_id,
      'publicKey' => $this->public_key,
      'privateKey' => $this->private_key
    ]);
    return $gateway;
  }
  public function createToken(){
    $customer = $this->createCustomer();
    dd($customer);
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
    return $result;
    // $result->success;
    // $result->customer->id;
  }


}
