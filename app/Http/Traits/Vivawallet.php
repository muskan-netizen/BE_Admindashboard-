<?php
namespace App\Http\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\{PaymentOption, UserAddress, Vendor};
use Illuminate\Support\Facades\Auth;

trait Vivawallet{

    private $api_key;
    private $merchant_id;
    private $url;
    private $tokenUrl;

    public function credentials()
    {
        // $viva = PaymentOption::select('credentials', 'test_mode','status')->where('code', 'vivawallet')->where('status', 1)->first();
        // $json = json_decode($viva->credentials);
        $this->api_key = 'mo8qAqP740h172MMV8RakEepVr5PuO';
        $this->merchant_id = '8d527m2ndy9nnbmhgqgtxv7frma27e444hwdnwzipw804.apps.vivapayments.com';
        
    }
   
    public function getAuthTokenViva():object{
        $this->credentials();
            // if($this->test_mode=='1'){
            //     $this->tokenUrl = 'https://demo-accounts.vivapayments.com/connect/token';            
            //     }else{
            //     $this->tokenUrl = 'https://accounts.vivapayments.com/connect/token';
            // }
        $this->tokenUrl = 'https://demo-accounts.vivapayments.com/connect/token';    
        $token = base64_encode($this->merchant_id.':'.$this->api_key);
        $response = $this->postCurlToken($token);
        return $response;
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


    public function createOrderPaymentLink($data = ''){
        $this->credentials();
        $token = $this->getAuthTokenViva();
            // if($this->test_mode=='1'){
            //     $this->payUrl = 'https://demo-api.vivapayments.com/checkout/v2/orders';            
            //     }else{
            //     $this->payUrl = 'https://api.vivapayments.com/checkout/v2/orders';
            // }
        $this->payUrl = 'https://demo-api.vivapayments.com/checkout/v2/orders'; 


        $postFields  = [
            'amount'              => 10000,
            'customerTrns'        => 'This is a description displayed to the customer',
            'customer'            => [
                'email'       => 'test@vivawallet.com',
                'fullName'    => 'George Seferis',
                'phone'       => '697845125',
                'countryCode' => 'GR',
                'requestLang' => 'el-GR'
            ],
            'paymentTimeout'      => 0,
            'preauth'             => false,
            'allowRecurring'      => false,
            'maxInstallments'     => 0,
            'paymentNotification' => true,
            'tipAmount'           => 0,
            'disableExactAmount'  => false,
            'disableCash'         => false,
            'disableWallet'       => false,
            'sourceCode'          => 'Default',
            'merchantTrns'        => 'This is a short description that helps you uniquely identify the transaction'
        ];

        $response=$this->postCurl($this->payUrl,$postFields,$token->access_token);
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

    private function getCurl($endpoint,$data,$token=null):object{

        $curl = curl_init();
          
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->api_url.''.$endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

            if($data)
            curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($data) );


            $headers = array();
            $headers[] = 'Accept: */*';
            if(!is_null($token)){
                $headers[] = "Authorization: Bearer $token";
            }
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
            return json_decode($result); 
    }

   

}