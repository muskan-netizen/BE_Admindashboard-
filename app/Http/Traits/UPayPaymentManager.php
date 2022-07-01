<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

use Log;
trait UPayPaymentManager{
  protected function createPaymentRequest($data){
    
  }  

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


}
