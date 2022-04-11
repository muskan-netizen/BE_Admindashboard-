<?php
namespace App\Http\Traits;
use App\Models\PaymentOption;
use Paytabscom\Laravel_paytabs\Facades\paypage; 
use Auth, Log;
trait PaytabPaymentManager{

  public function createPaymentpage($data,$user,$address = null)
  {
    if(is_null($address))
    {
      $address = (object)[];
    }
    $pay = paypage::sendPaymentCode('all')
        ->sendTransaction('Auth')
        ->sendCart(mt_rand(10000000,99999999),(int)$data['amount'],'test1')
        ->sendCustomerDetails($user->name??'', $user->email??'', '0101111111', $address->address??'', $address->city??'', $address->state??'', $address->country_code??'', $address->pincode??'','100.279.20.11')
        ->sendShippingDetails('same as billing')
        ->sendURLs(route('payment.paytab.callback'), route('payment.paytab.callback'))  
        ->sendLanguage('en')
        ->create_pay_page();
    return $pay;
  }
  public function createPaytabPayment($data)
  {
    $client = $this->init();
    $amount_money = new \Square\Models\Money();
    $amount_money->setAmount($data['amount']);
    $amount_money->setCurrency($data['currency']);

    $body = new \Square\Models\CreatePaymentRequest(
        $data['source_id'],
        Uuid::uuid4(),
        $amount_money
    );
    $body->setReferenceId($data['reference']);
    $body->setLocationId($data['location_id']);
    $body->setAutocomplete(true);
    $body->setNote($data['description']);

    $api_response = $client->getPaymentsApi()->createPayment($body);
    $payment_id = null;
    if ($api_response->isSuccess()) {
        $result = $api_response->getResult();
        if($result->getPayment()->getStatus() == "COMPLETED")
        {
          $payment_id = $result->getPayment()->getId();
          return $payment_id;
        }
    } else {
        $errors = $api_response->getErrors();
    }
    Log::info("Payment ID");
    Log::info($payment_id);
    return $payment_id;
  }
}
