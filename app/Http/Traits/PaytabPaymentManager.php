<?php
namespace App\Http\Traits;
use App\Models\PaymentOption;
use Paytabscom\Laravel_paytabs\Facades\paypage; 
use Auth, Log;
trait PaytabPaymentManager{

  public function createPaymentpage($data)
  {
    $pay= paypage::sendPaymentCode('all')
        ->sendTransaction('sale')
        ->sendCart(10,1000,'test')
        ->sendCustomerDetails('Walaa Elsaeed', 'w.elsaeed@paytabs.com', '0101111111', 'test', 'Nasr City', 'Cairo', 'EG', '1234','100.279.20.10')
        ->sendShippingDetails('Walaa Elsaeed', 'w.elsaeed@paytabs.com', '0101111111', 'test', 'Nasr City', 'Cairo', 'EG', '1234','100.279.20.10')
        ->sendURLs('https://sales.focushires.com', 'https://sales.focushires.com')
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
