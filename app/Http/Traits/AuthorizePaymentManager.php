<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\PaymentOption;
use Log;
trait AuthorizePaymentManager{

  private $login_id;
  private $client_key;
  public function __construct()
  {
    $anet_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'authorize_net')->where('status', 1)->first();
    $creds_arr = json_decode($anet_creds->credentials);
    $this->login_id = $creds_arr->login_id??'';
    $this->client_key = $creds_arr->client_key??'';
  }

  public function init()
  {
    Simplify::$publicKey = $this->public_key;
    Simplify::$privateKey = $this->private_key;
  }

  public function create_payment($data)
  {
    $this->init();
    try {
      // dd($data);
      $requestData = [
        'reference' => $data['reference'], // Order reference
        'amount' => (int)$data['amount'], // 10 AED multiplied by 100
        'description' => $data['description'],
        'currency' => 'QAR',
        'token' => $data['simplifyToken'], // Card token you received
        'order' => [
          'source' => 'WEB',
          'customerEmail' => $data['email'],
          'customerName' => $data['username']
        ]
      ];

      $rakAuthCheck = Simplify::createPayment($requestData); 
      if($rakAuthCheck){
            //Success call
        Log::info(print_r($rakAuthCheck, true)); // Printing reponse to your log file.
        return $rakAuthCheck;
      }
    }catch(Exception $e) {
        // Failed call
        return [
            'auth_message' => $e->getMessage()
        ];
    }
  }

}
