<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponser;
use App\Models\PaymentOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OboPaymentController extends Controller
{
    use ApiResponser;

    private $obo_business_name;
    private $obo_client_id;
    private $obo_key_id;
    private $obo_market_place_id;
    private $testMode;

    public function __construct()
    {
        $payOption = PaymentOption::select('credentials', 'test_mode', 'status')->where('code', 'obo')->where('status', 1)->first();
        $credentials = json_decode($payOption->credentials);
        $this->obo_business_name   = $credentials->obo_business_name;
        $this->obo_client_id       = $credentials->obo_client_id;
        $this->obo_key_id          = $credentials->obo_key_id;
        $this->obo_market_place_id = $credentials->obo_market_place_id;
        $this->testMode            = $payOption->test_mode;
    }



    public function beforePayment(Request $request)
    { dd($request->all());
        $tokenData =  $this->token();
        if (isset($tokenData['httpStatus']) &&  $tokenData['httpStatus'] == "OK") {
            $token = $tokenData['token'];
            if (isset($token)) {
                $user = auth()->user();
                \Log::info($user);

                if ($this->testMode == 1) {
                    $apiUrl = "https://www.obo-pay.co.rw/test/payments/v1/payment";
                } else {
                    $apiUrl = "";
                }
                $header = [
                    'Content-Type' => 'application/json',
                    'token' => $token
                ];
                $input = json_encode([
                    "amount" => "1",
                    "currency" => "RWF",
                    "email" => "devlopertester@yopmail.com",
                    "phone" => "1234567890",
                    "reference_id" => "ordttt77trr_ih234",
                    "first_name" => "Test",
                    "last_name" => "User",
                    "merchant" => $this->obo_business_name,
                    "cancel_url" => url($request->url),
                    "return_url" => "viewcart",
                    "custom_pg_id" => $this->obo_market_place_id,
                ], JSON_UNESCAPED_SLASHES);
                $responce = Http::withBody($input, 'application/json')->withHeaders($header)->post($apiUrl);
                $responceData = json_decode($responce->body(), true);
                if (isset($responceData['status']) && $responceData['status'] ===  "OK") {
                    $redirectUrl =  $responceData['data']['url'];
                    return response()->json([
                        'status' => 'Success',
                        'data'   => $redirectUrl
                    ]);
                } else {
                    return $this->errorResponse($responceData['message'], 400);
                }
            }
        } else {
            return $this->errorResponse($tokenData['message'], 400);
        }
    }

    public function token()
    {
        if ($this->testMode == 1) {
            $apiUrl = 'https://www.obo-pay.co.rw/test/payments/v1/token';
        } else {
            $apiUrl = "";
        }
        $input = json_encode([
            "id" => $this->obo_client_id,
            "key" => $this->obo_key_id
        ]);
        $header = [
            'Content-Type' => 'application/json'
        ];
        $responce = Http::withBody($input, 'application/json')->withHeaders($header)->post($apiUrl);
        $data = json_decode($responce->body(),  true);
        return $data;
    }
}
