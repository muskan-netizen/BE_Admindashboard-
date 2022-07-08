<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Log;

trait ConektaPaymentManager{

  protected function createToken()
  {
    $token = base64_encode($this->private_key);
    $client = new \GuzzleHttp\Client();
    $response = $client->request('POST', 'https://api.conekta.io/tokens', [
      'body' => '{"checkout":{"returns_control_on":"Token"}}',
      'headers' => [
        'Accept' => 'application/vnd.conekta-v2.0.0+json',
        'Authorization' => 'Basic '.$token,
        'Content-Type' => 'application/json',
      ],
    ]);
    // echo $response->getBody();
  }
  protected function createPaymentRequest($data){
    $token = base64_encode($this->private_key);
    $data = [
      "name" => "Online Shopping",
      "type" => "HostedPayment",
      "recurrent" => false,
      "needs_shipping_contact" => false,
      "expires_at" => strtotime('+7 day',strtotime(date('Y-m-d'))),
      "allowed_payment_methods" => ["cash", "card", "bank_transfer"],
      "order_template" => [
        "line_items" => [
          [
            "name" => "Red Wine",
            "unit_price" => 1000,
            "quantity" => 10
          ]
        ],
        "metadata" => [
           "mycustomkey" => "12345",
          "othercustomkey" => "abcd"
        ],
        "currency" => "MXN",
        'customer_info' => [
          'name' => "Juan Perez",
          'email' => "juan.perez@conekta.com",
          'phone' => "5566982090"
        ]
      ],
    ];

    $client = new \GuzzleHttp\Client();
    $response = $client->request('POST', 'https://api.conekta.io/checkout', [
      'body' => json_encode($data),
      'headers' => [
        'Accept' => 'application/vnd.conekta-v2.0.0+json',
         'Authorization' => 'Basic '.$token,
        'Content-Type' => 'application/json',
        'accept' => 'application/vnd.conekta-v2.0.0+json',
        'content-type' => 'application/json',
      ],
    ]);
    if($response->getStatusCode() == 200)
    {
      return json_decode((string) $response->getBody());
    }
  }
  public function createCheckout($data){
    $token = base64_encode($this->private_key);
    $data =
    [
      "name" => $data['checkout_name'],
        'line_items'=> [
            [
                'name'        => 'Box of Cohiba S1s',
                'description' => 'Imported From Mex.',
                'unit_price'  => 20000,
                'quantity'    => 1,
                'sku'         => 'cohb_s1',
                'category'    => 'food',
                'tags'        => ['food', 'mexican food']
            ]
        ],
        'currency' => 'mxn',
        'metadata' => ['test' => 'extra info'],
        'checkout'    => [
          'type' => 'HostedPayment',
          'success_url' => route('payment.conekta.afterPayment').'?status=success',
          'failure_url' => route('payment.conekta.afterPayment').'?status=failure',
          "expires_at" => strtotime('+7 day',strtotime(date('Y-m-d'))),
          'allowed_payment_methods' => ["cash", "card", "bank_transfer"],
          'monthly_installments_enabled' => true,
          'monthly_installments_options' => [3, 6, 9, 12],
        ],
        'currency' => 'mxn',
        'customer_info' => [
            'name'  => $data['customer_name'],
            'phone' => $data['customer_phone'],
            'email' => $data['customer_email'],
        ]
    ];
    $client = new \GuzzleHttp\Client();
    $response = $client->request('POST', 'https://api.conekta.io/orders', [
      'body' => json_encode($data),
      'headers' => [
        'Accept' => 'application/vnd.conekta-v2.0.0+json',
         'Authorization' => 'Basic '.$token,
        'Content-Type' => 'application/json',
        'accept' => 'application/vnd.conekta-v2.0.0+json',
        'content-type' => 'application/json',
      ],
    ]);
    
    if($response->getStatusCode() == 200)
    {
      dd(json_decode((string) $response->getBody()));
      return json_decode((string) $response->getBody());
    }
  }  
}
