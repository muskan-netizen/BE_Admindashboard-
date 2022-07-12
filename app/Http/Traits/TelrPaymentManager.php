<?php
namespace App\Http\Traits;

use Log;
trait TelrPaymentManager{

  public function createPaymentpage($data) 
  {
    $telrManager = new \TelrGateway\TelrManager();

    $billingParams = [
        'first_name' => 'Moustafa Gouda',
        'sur_name' => 'Bafi',
        'address_1' => 'Gnaklis',
        'address_2' => 'Gnaklis 2',
        'city' => 'Alexandria',
        'region' => 'San Stefano',
        'zip' => '11231',
        'country' => 'EG',
        'email' => 'example@company.com',
    ];
    // dd($telrManager);
    $response = $telrManager->pay('ORDERID'.mt_rand(10000000,99999999), '100', 'DESCRIPTION ...', $billingParams);
    dd($response);
    return $telrManager->pay('ORDERID'.mt_rand(10000000,99999999), '100', 'DESCRIPTION ...', $billingParams)->redirect();

  }
}
