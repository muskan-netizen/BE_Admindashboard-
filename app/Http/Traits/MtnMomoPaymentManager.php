<?php
namespace App\Http\Traits;
use App\Models\PaymentOption;
use Auth, Log, Config;
trait MtnMomoPaymentManager{


  public function createApiUser($subscription_key,$reference_id){
        $payOpt                 = PaymentOption::select('credentials', 'test_mode', 'status')->where('code', 'mtn_momo')->where('status', 1)->first();
        if ($payOpt->test_mode == '1') {
            $appUrl       = 'https://sandbox.momodeveloper.mtn.com/';
        } else {
            $appUrl       = 'https://payments.stabexinternational.com/api/mtn/Callback/';
        }
        $site_url     = url('/');
        $domain_name  = self::getDomainName($site_url);
        $curl         = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $appUrl.'apiuser',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
          "providerCallbackHost": "'.$domain_name.'"
        }',
          CURLOPT_HTTPHEADER => array(
            'X-Reference-Id: '.$reference_id.'',
            'Ocp-Apim-Subscription-Key: '.$subscription_key.'',
            'Content-Type: application/json',
            ': ',
            'Cookie: __RequestVerificationToken=8sRHatO_--xJsV4xfYXNJvLcGLzoxKV4xHPR_LdHJebUaBqVYSKQUEdyPVEHoYRX1C57GRY4Rl-mZjwUGv1Rz9uAXNKYqBQHeui1cBLz6O_tTEeJw789HSFsGrcna6MQyKATDIAkHGoMlZknQbi5fw2'
          ),
        ));

        $response   = curl_exec($curl);
        $status     = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if($status == 201){
        return json_encode(['status'=>201,'message'=>'Api User added.']);
        }
        else if($status == 409){
        return json_encode(['status'=>409,'message'=>'Reference id already exist.please enter new reference id.']);
        }
        else if($status == 400){
        return json_encode(['status'=>400,'message'=>'Invalid data was sent in the request.']);
        }
        else if($status == 500){
        return json_encode(['status'=>500,'message'=>'Internal Server Error.']);
        }
        else if($status == 404){
            return json_encode(['status'=>404,'message'=>'Internal Server Error.']);
        }

  }

  public function createApiKey($subscription_key,$reference_id){
    $payOpt                 = PaymentOption::select('credentials', 'test_mode', 'status')->where('code', 'mtn_momo')->where('status', 1)->first();
    if ($payOpt->test_mode == '1') {
        $appUrl       = 'https://sandbox.momodeveloper.mtn.com/';
    } else {
        $appUrl       = 'https://payments.stabexinternational.com/api/mtn/Callback/';
    }
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $appUrl.'apiuser/'.$reference_id.'/apikey',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_HTTPHEADER => array(
        'Ocp-Apim-Subscription-Key: '.$subscription_key.'',
        'Content-Type: application/json',
        'Content-Length:0'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return json_decode($response,true);
  }


  function gen_uuid_4() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

function getDomainName($url) {
    $disallowed = array('http://', 'https://');
    foreach($disallowed as $d) {
       if(strpos($url, $d) === 0) {
          return str_replace($d, '', $url);
       }
    }
    return $url;
 }
  // public function createPaymentpage($data,$user,$address = null)
  // {
  //   if(is_null($address))
  //   {
  //     $address = (object)[];
  //   }
  //   $order_number = isset($data['order_number']) ? $data['order_number'] : "";
  //   $pay = paypage::sendPaymentCode('all')
  //       ->sendTransaction('Auth')
  //       ->sendCart(mt_rand(10000000,99999999),(int)$data['amount'],'test1')
  //       // ->sendCustomerDetails('Walaa Elsaeed', 'w.elsaeed@paytabs.com', '0101111111', 'test', 'Nasr City', 'Cairo', 'EG', '1234','100.279.20.10')
  //       ->sendCustomerDetails($user->name??'', $user->email??'', '0101111111', $address->address??'', $address->city??'', $address->state??'', $address->country_code??'', $address->pincode??'','100.279.20.11')
  //       ->sendShippingDetails('same as billing')
  //       ->sendURLs(route('payment.paytab.return',['amount' => (int)$data['amount'], 'payment_from' => $data['payment_from'], 'come_from' => $data['come_from'], 'order_number' => $order_number,'auth_token'=>$user->auth_token]), route('payment.paytab.callback'))
  //       // ->sendURLs('https://619a-112-196-88-218.ngrok.io/payment/paytab/return?amount='.(int)$data['amount'].'&payment_from='.$data['payment_from'].'&come_from='.$data['come_from'].'&order_number='.$order_number.'&auth_token='.$user->auth_token, 'https://619a-112-196-88-218.ngrok.io/payment/paytab/callback')
  //       ->sendLanguage('en')
  //       ->create_pay_page();
  //   return $pay;
  // }
  // public function capturePayment($data)
  // {
  //   return  Paypage::capture($data['tranRef'],$data['cartId'],(int)$data['amount'],$data['description']);
  // }
}
