<?php
namespace App\Http\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Models\PaymentOption;
use Illuminate\Support\Facades\Log;

trait PesapalPaymentTrait
{
    use ApiResponser;

    public $consumer_secret;
    public $consumer_key;
    public $test_mode;

    public function __construct()
    {
        $this->creds = PaymentOption::where('code', 'pesapal')->where('status', 1)->first();
        $this->creds_arr = json_decode($this->creds->credentials);
        $this->test_mode = $this->creds->test_mode;
        $this->consumer_key = $this->creds_arr->pesapal_consumer_key;
        $this->consumer_secret = $this->creds_arr->pesapal_consumer_secret;
    }

    public function token()
    {
        $url = $this->test_mode ? 'https://cybqa.pesapal.com/pesapalv3/api/Auth/RequestToken' : 'https://pay.pesapal.com/v3/api/Auth/RequestToken';

        return Http::post($url,[
            "consumer_key" =>  $this->consumer_key,
            "consumer_secret" =>  $this->consumer_secret
        ]);
    }

    public function pinId()
    {
        $url = $this->test_mode ? 'https://cybqa.pesapal.com/pesapalv3/api/URLSetup/RegisterIPN' : 'https://pay.pesapal.com/v3/api/URLSetup/RegisterIPN';

        return Http::withHeaders([
            'Content-Type' =>'application/json',
            'Authorization' => 'Bearer '.$this->token()['token']
        ])->post($url,[
            "url" => "https://www.myapplication.com/ipn",
            "ipn_notification_type" => "GET"
        ]);
    }

    public function pinList()
    {
        $url = $this->test_mode ? 'https://cybqa.pesapal.com/pesapalv3/api/URLSetup/GetIpnList' : 'https://pay.pesapal.com/v3/api/URLSetup/GetIpnList';

        return Http::withHeaders([
            'Content-Type' =>'application/json',
            'Authorization' => 'Bearer '.$this->token()['token']
        ])->get($url);
    }

    public function pesaPalTransApi(Request $request,$description)
    {
        $token = $this->token()['token'];
        $redirct_url = route('payment.pesapal.success');

        $url = $this->test_mode ? 'https://cybqa.pesapal.com/pesapalv3/api/Transactions/SubmitOrderRequest' : 'https://pay.pesapal.com/v3/api/Transactions/SubmitOrderRequest';
        $name = explode(' ',auth()->user()->name);

        return Http::withHeaders([
            'Content-Type' =>'application/json',
            'Authorization' => 'Bearer '.$token
        ])->post($url,[
            "id" => $request->order_number,
            "currency" => "KES",
            "amount" => number_format($request->total_amount,2),
            "description" => $description,
            "callback_url" => $redirct_url,
            "notification_id" => "ce3d8206-fdff-4ca8-8ebb-decc885104e3",
            "billing_address" => [
                "email_address" => auth()->user()->email ?? '',
                "phone_number" => auth()->user()->phone_number ?? '',
                "country_code" => "KE",
                "first_name" => $name[0] ?? '',
                "middle_name" => "",
                "last_name" => $name[1] ?? '',
                "line_1" => "",
                "line_2" => "",
                "city" => "",
                "state" => "",
                "postal_code" => "",
                "zip_code" => ""
            ]
        ]);
    }

    public function transactionStatus()
    {
        $url = $this->test_mode ? 'https://cybqa.pesapal.com/pesapalv3/api/Transactions/GetTransactionStatus' : 'https://pay.pesapal.com/v3/api/Transactions/GetTransactionStatus';

        return Http::withHeaders([
            'Content-Type' =>'application/json',
            'Authorization' => 'Bearer '.$this->token()['token']
        ])->get($url,[
            "orderTrackingId" => "6bc85e7b-cea7-4088-b643-deaeea5f2505"
        ]);
    }

    public function PesapalPayment(Request $request)
    {
        if ($request->payment_from == 'cart') {
            $description = "Oder-".$request->order_number;
          
        } elseif ($request->payment_from == 'pickup_delivery') {
            $description = "Pickup Delivery";
          
        } elseif ($request->payment_from == 'wallet') {
            $description = "Wallet-Credit";
            $request->merge(['order_number' => time() ]);

        } elseif ($request->payment_from == 'tip') {
            $description = "Tip Amount";
            $request->merge(['order_number' => $request->order_number.'-'.time() ]);
  
        } elseif ($request->payment_from == 'subscription') {
            $description = "subscription";      
            $request->merge(['order_number' => $request->order_number.'-'.time() ]);
        }

       return $this->pesaPalTransApi($request,$description);
    }
}