<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\{ApiResponser, OrderTrait};
use App\Models\{Order, Payment, PaymentOption, User};

class OrangePaymentController extends Controller
{
    public function __construct()
    {
        $payOption = PaymentOption::select('credentials', 'test_mode', 'status')->where('code', 'orangepay')->where('status', 1)->first();
        if (@$payOption && !empty($payOption->credentials)) {
            $credentials = json_decode($payOption->credentials);
            $this->orangepay_MerchantKey = $credentials->orangepay_MerchantKey;
            $this->orangepay_MerchantToken = $credentials->orangepay_MerchantToken;
        }
    }

    public function create_token(Request $request){

        $curl = curl_init();
        $bearer=$this->orangepay_MerchantToken;
        $headers = [
            'Authorization' => 'Basic '.$bearer,
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Cookie' => 'BIGipServer~naomi-ginefa~PoolOCProuter-OCP-EFA-HTTP-NORMANDIE=!bMhHGM1WQJ1AYQU3UzhIVdohv0ViBgAcRlF4ofhzU+EHb/PyP6lvieVDAfKrSraeI165DBPfBSERU1soW8q/2i86sytoghA2NSjLcpI=; BIGipServer~naomi-ginefa~PoolOCProuter-OCP-EFA-HTTP-RUEIL=!vkJSjroHRb2WlZEkioRitpNtJV/P2h3jGIk+uQTUWrMoxexXvPdZT4xTqPSJLM2dcETW0u8QH7B0mu5DnY0HBylZRac/EOgBJVy3h+4=; aa18925415715d69ee149f9d943dc7f8=3488b5c2d8e3b589a38818d14ae4ae74',
        ];
        $formattedHeaders = [];
        foreach ($headers as $key => $value) {
            $formattedHeaders[] = $key . ': ' . $value;
        }
        \Log::info(['headers'=>$formattedHeaders]);

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.orange.com/oauth/v3/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
        CURLOPT_HTTPHEADER => $formattedHeaders,
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function web_payment(Request $request){
        try{
        \Log::info('web payment 1');
        \Log::info($request->all());
        $response=$this->create_token($request);
        $data = json_decode($response, true);
        $accessToken = $data['access_token'];
        $curl = curl_init();
        $headers = [
            'Authorization' => 'Bearer '.$accessToken,
            'Content-Type' => 'application/json',
            'Cookie' => 'BIGipServer~naomi-ginefa~PoolOCProuter-OCP-EFA-HTTP-NORMANDIE=!bMhHGM1WQJ1AYQU3UzhIVdohv0ViBgAcRlF4ofhzU+EHb/PyP6lvieVDAfKrSraeI165DBPfBSERU1soW8q/2i86sytoghA2NSjLcpI=; BIGipServer~naomi-ginefa~PoolOCProuter-OCP-EFA-HTTP-RUEIL=!vkJSjroHRb2WlZEkioRitpNtJV/P2h3jGIk+uQTUWrMoxexXvPdZT4xTqPSJLM2dcETW0u8QH7B0mu5DnY0HBylZRac/EOgBJVy3h+4=; aa18925415715d69ee149f9d943dc7f8=3488b5c2d8e3b589a38818d14ae4ae74'
        ];
        $data = [
            "merchant_key" => $this->orangepay_MerchantKey,
            "currency" => "OUV",
            "order_id" => "454545",
            "amount" => $request->amount,
            "return_url" => route('success.orangepayment'),
            "cancel_url" => "http://myvirtualshop.webnode.es/txncncld/",
            "notif_url" => "http://www.merchant-example2.org/notif",
            "lang" => "fr",
            "reference" => "4545",
        ];
        $formattedHeaders = [];
        foreach ($headers as $key => $value) {
            $formattedHeaders[] = $key . ': ' . $value;
        }
        $jsonData = json_encode($data);
        \Log::info(['web_payment data'=>$jsonData]);
        \Log::info(['web_payment header'=>$formattedHeaders]);
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.orange.com/orange-money-webpay/dev/v1/webpayment',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$jsonData,
        CURLOPT_HTTPHEADER => $formattedHeaders,
        ));
        $response = curl_exec($curl);
        \Log::info(['web_payment response'=>$response]);
        curl_close($curl);
        return $response;
    }catch(\Exception $e){
        \Log::error($e->getMessage());
        \Log::error($e->getLine());
    }
    }

    
}
