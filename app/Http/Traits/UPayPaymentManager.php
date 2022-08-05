<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

use Log;
trait UPayPaymentManager{

  private function postCurl($data,$token=null):object{
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://api-public.sandbox.exchange.coinbase.com');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
      $headers = array();
      $headers[] = 'Content-Type: application/json';
      $headers[] = 'X-CC-Api-Key: m/qQJP1aUY4iZE59yHLs2BjoAsCF+Z8hmNCOIE4f/YyRrRL2m5o8G410dm0cOfCzHBvwZkSMybdhhBbuhPUJFg==';
      $headers[] = 'X-CC-Version:  2022-05-01';
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      $result = curl_exec($ch);
      if (curl_errno($ch)) {
          echo 'Error:' . curl_error($ch);
      }
      curl_close($ch);
      return json_decode($result);
  }
  private function getCurl($endpoint):object{
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $endpoint);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
      $headers = array();
      $headers[] = 'Accept: */*';
      $headers[] = 'Content-Type: application/json';
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      $result = curl_exec($ch);
      if (curl_errno($ch)) {
          echo 'Error:' . curl_error($ch);
      }
      curl_close($ch);
      return $result;
      return json_decode($result);

      $curl = curl_init();
  }
  public function createPaymentRequest($data)
  {
    $formData = [
      'Amt' => $data['amount'],
      'Email' => $data['user_email'],
      'Mobile' => $data['user_phone'],
      'Redir' => $data['redirect_url']??'http://192.168.1.3:8060',
      'References' => $data['references']??[]
    ];
    $info = json_encode($formData);
    return $info;
    // $encrypted_text = $this->test($info);
    // $redirect_url = $this->endpoint.'/WhiteLabel/'.$this->uidd.'?s='.$encrypted_text;
    // return $redirect_url;
  }

  public function test($information)
  {
    $cipher = "aes-256-cbc";
    $secret = $this->aes_key;
    $option = 0;
    $iv = str_repeat("0",openssl_cipher_iv_length($cipher));
    $encrypted_text = openssl_encrypt($information, $cipher, $secret, $option, $iv);
    // $encrypted_text = $this->aes256_cbc_encrypt($secret, $information, $iv);
    $final_text = substr(base64_encode($iv), 0, 24).base64_encode($encrypted_text);
    return $final_text;
  }
}
