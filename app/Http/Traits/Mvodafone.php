<?php
namespace App\Http\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\{PaymentOption, UserAddress, Vendor};
use Illuminate\Support\Facades\Auth;

trait Mvodafone{

  
    private $secret_key;
    private $client_id;
    private $url;
    private $test_mode;


    public function __construct()
   {
        $viva = PaymentOption::select('credentials', 'test_mode','status')->where('code', 'mvodafone')->where('status', 1)->first();
        $json = json_decode($viva->credentials);
        $this->secret_key = $json->secret_key;
        $this->client_id = $json->client_id;
        $this->test_mode = $viva->test_mode;
   }

    public function credentials()
    {
         $viva = PaymentOption::select('credentials', 'test_mode','status')->where('code', 'mvodafone')->where('status', 1)->first();
         $json = json_decode($viva->credentials);
         $this->secret_key = $json->secret_key;
         $this->client_id = $json->client_id;
         $this->test_mode = $viva->test_mode;
    }
   
    public function callHandShakeApi($data){
        $this->credentials();
            if($this->test_mode=='1'){
                $this->tokenUrl = 'https://pay.mpaisa.vodafone.com.fj/API/';            
                }else{
                $this->tokenUrl = 'https://pay.mpaisa.vodafone.com.fj/live/API/';
            }
        $endUrl = $this->tokenUrl.'?url='.$data['returnUrl'].'&&tID='.$data['order_no'].'&&amt='.$data['amount'].'&&cID='.$this->client_id.'&&iDet=websiteTesting';
        $response = $this->getCurl($endUrl,null,null);
        $response = json_decode($response);
        if($response->response == 101){
            return $response;
        }
        return false;
    }

    public function createPaymentLinkVodafone($data = null){
        $handShake = $this->callHandShakeApi($data);
        // $handShake = json_decode($responseApi);
        if($handShake->response == 101){
            $endUrl = $handShake->destinationurl.'?url='.$data['returnUrl'].'&&tID='.$data['order_no'].'&&amt='.$data['amount'].'&&cID='.$this->client_id.'&&iDet=websiteTesting&&rID='.$handShake->requestID;
            
            return  (object)(array('url'=>$endUrl,'reqId'=>$handShake->requestID));
        }
            return false;
    }


    private function postCurlToken($token):object{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->tokenUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,"grant_type=client_credentials");
        $headers = array();
        $headers[] = 'Accept: */*';
        $headers[] = "Authorization: Basic ${token}";
         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return json_decode($result); 
    }




    public function getTransactionDetails($tid){
        $token = $this->getAuthTokenViva();
            // if($this->test_mode=='1'){
            //     $this->api_url = 'https://demo-api.vivapayments.com/checkout/v2/transactions/'.$tid;            
            //     }else{
            //     $this->api_url = 'https://api.vivapayments.com/checkout/v2/transactions/'.$tid;
            // }
         //dd($token);
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => "https://demo-api.vivapayments.com/checkout/v2/transactions/47825180",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_HTTPHEADER     => array(
                "Authorization: Bearer $token->access_token"
            ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        echo $response;
    }


    public function verificationWebhookKey():object{
        $this->credentials();
        $token = base64_encode($this->merchant_id.':'.$this->merchant_key);
            if($this->test_mode=='1'){
                $this->api_url = 'https://demo.vivapayments.com/api/messages/config/token';            
                }else{
                $this->api_url = 'https://vivapayments.com/api/messages/config/token';
            }
        $response=$this->getCurl('','',$token);
        return $response;
    }


    private function postCurl($endpoint,$data,$token=null):object{
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $endpoint);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_ENCODING, '');
                curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
                curl_setopt($ch, CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
                $headers = array();
                $headers[] = 'Accept: */*';
                $headers[] = "Authorization: Bearer ${token}";
                $headers[] = 'Content-Type: application/json';
                 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);
                return json_decode($result); 
    }

    private function getCurl($endpoint,$data=null,$token=null){

        $curl = curl_init();
          
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

            $result = curl_exec($ch);
            // if (curl_errno($ch)) {
            //     echo 'Error:' . curl_error($ch);
            // }
            return $result; 

            curl_close($ch);
    }

   

}