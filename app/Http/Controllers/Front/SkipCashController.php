<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\PaymentOption;
use Str;
use Auth;
class SkipCashController extends Controller
{

    public function showSkipCashPage(Request $request){
        // // $data = $request->all();
        // // //  dd($data);
        // // return view('frontend.payment_gatway.skip_cash',compact('data'));
        // $this->checkPayment($request);
        $creds = PaymentOption::where('code', 'skip_cash')
        ->where('status', 1)
        ->first();
        $creds_arr = json_decode($creds->credentials);
        $skipCashClientId=$creds_arr->skip_cash_client_id;
        $url = 'https://skipcashtest.azurewebsites.net/api/v1/payments';
        $keyId = $creds_arr->skip_cash_key_id;
        // dd($keyId);
        $secretKey = $creds_arr->skip_cash_api_secret;
        //  dd($kesecretKeyyId);
        $return_url  = 'https://example.com/success';
        
        // Define the request fields
        $fields = [
            "Uid" => Str::uuid()->toString(),
            'KeyId' => $keyId,
            'Amount' => $request->amount, 
            'FirstName' => Auth::user()->name,
            'LastName' =>  Auth::user()->name,
            'Phone' => Auth::user()->phone_number,
            'Email' =>  Auth::user()->email,
            'Street' => '123',
            'City' => 'Anytown',
            'State' => 'CA',
            'Country' => 'US',
            'PostalCode' => '12345',
            'TransactionId' => $request->order_id,
            //'return_url' => $return_url,

            //'Custom1' => '',
            //  'ClientID' => $client_id,
            //  'keyId'=>$keyId,
        ];
        $signatureString = '';
        foreach ($fields as $key => $value) {
            if (!empty($value)) {
                $signatureString .= "$key=$value,";
            }
        }
        $signatureString = rtrim($signatureString, ',');
        // dd($signatureString);

        // Encrypt the signature string using HMACSHA256 with the secret key
        $signature = hash_hmac('sha256', $signatureString, $secretKey, true);
        // echo $signature;
        // die;
        // Convert the encrypted result to base64 format
        $signatureBase64 = base64_encode($signature);
        // dd($signatureBase64);
        // Set the headers
        // echo "$keyId:$signatureBase64";
        $headers = [
            'Content-Type: application/json',
            "Authorization: $signatureBase64",
            'x-client-id: ' . $skipCashClientId

        ];

        // Set the request body
        $body = json_encode($fields);

        // Create the cURL handle
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Execute the request
        $response = curl_exec($ch);
        //  dd($response);
        $error = curl_error($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        // dd($response);
        // Check for errors
        if ($error) {
            //  dd($info);
            echo "cURL Error: $error\n";
        } elseif ($info['http_code'] !== 200) {
          //  dd("not");
        //   dd($info);
            echo "HTTP Error: {$info['http_code']}\n";
        } else {
            // dd("succ");
            // echo "Response: $response\n";
            $responseObj = json_decode($response);
          //  dd($responseObj);    
            $payUrl = $responseObj->resultObj->payUrl;
            //  dd($payUrl);
           return redirect($payUrl);
        }
    }
    


   


    // public function checkPayment(Request $request)
    // {
    //     $creds = PaymentOption::where('code', 'skip_cash')
    //     ->where('status', 1)
    //     ->first();
    //     $creds_arr = json_decode($creds->credentials);
    //     $skipCashClientId=$creds_arr->skip_cash_client_id;
    //     $url = 'https://skipcashtest.azurewebsites.net/api/v1/payments';
    //     $keyId = $creds_arr->skip_cash_key_id;
    //     // dd($keyId);
    //     $secretKey = $creds_arr->skip_cash_api_secret;
    //     //  dd($kesecretKeyyId);
    //     $return_url  = 'https://example.com/success';
        
    //     // Define the request fields
    //     $fields = [
    //         "Uid" => Str::uuid()->toString(),
    //         'KeyId' => $keyId,
    //         'Amount' => $request->amount, 
    //         'FirstName' => Auth::user()->name,
    //         'LastName' =>  Auth::user()->name,
    //         'Phone' => Auth::user()->phone_number,
    //         'Email' =>  Auth::user()->email,
    //         'Street' => '123',
    //         'City' => 'Anytown',
    //         'State' => 'CA',
    //         'Country' => 'US',
    //         'PostalCode' => '12345',
    //         'TransactionId' => $request->order_id,
    //         //'return_url' => $return_url,

    //         //'Custom1' => '',
    //         //  'ClientID' => $client_id,
    //         //  'keyId'=>$keyId,
    //     ];
    //     $signatureString = '';
    //     foreach ($fields as $key => $value) {
    //         if (!empty($value)) {
    //             $signatureString .= "$key=$value,";
    //         }
    //     }
    //     $signatureString = rtrim($signatureString, ',');
    //     // dd($signatureString);

    //     // Encrypt the signature string using HMACSHA256 with the secret key
    //     $signature = hash_hmac('sha256', $signatureString, $secretKey, true);
    //     // echo $signature;
    //     // die;
    //     // Convert the encrypted result to base64 format
    //     $signatureBase64 = base64_encode($signature);
    //     // dd($signatureBase64);
    //     // Set the headers
    //     // echo "$keyId:$signatureBase64";
    //     $headers = [
    //         'Content-Type: application/json',
    //         "Authorization: $signatureBase64",
    //         'x-client-id: ' . $skipCashClientId

    //     ];

    //     // Set the request body
    //     $body = json_encode($fields);

    //     // Create the cURL handle
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    //     // Execute the request
    //     $response = curl_exec($ch);
    //     //  dd($response);
    //     $error = curl_error($ch);
    //     $info = curl_getinfo($ch);
    //     curl_close($ch);
    //     // dd($response);
    //     // Check for errors
    //     if ($error) {
    //         //  dd($info);
    //         echo "cURL Error: $error\n";
    //     } elseif ($info['http_code'] !== 200) {
    //       //  dd("not");
    //     //   dd($info);
    //         echo "HTTP Error: {$info['http_code']}\n";
    //     } else {
    //         // dd("succ");
    //         // echo "Response: $response\n";
    //         $responseObj = json_decode($response);
    //       //  dd($responseObj);    
    //         $payUrl = $responseObj->resultObj->payUrl;
    //         //  dd($payUrl);
    //        return redirect($payUrl);
    //     }
        
    // }


    
}
