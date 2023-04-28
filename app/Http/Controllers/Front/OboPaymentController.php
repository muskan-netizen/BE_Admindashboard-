<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Traits\{ApiResponser, OrderTrait};
use App\Models\{ClientCurrency, Order, Payment, PaymentOption};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class OboPaymentController extends Controller
{
    use ApiResponser, OrderTrait;

    public $currency;
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

        $primaryCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
        $this->currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'USD';
    }



    public function beforePayment(Request $request)
    {
        // dd($request->all());
        $tokenData =  $this->token();
        if (isset($tokenData['httpStatus']) &&  $tokenData['httpStatus'] == "OK") {
            $token = $tokenData['token'];
            if (isset($token)) {
                // user details
                $user = auth()->user();
                $userEmail       = $user->email;
                $userPhone       = $user->dial_code . $user->phone_number;
                $userFirstName   = strtok($user->name, " ");
                $userLastName    = substr(strstr($user->name, " "), 1);

                $number = $this->orderNumber($request);
                if ($request->payment_from == 'cart') {
                    $orderNumber = $request->order_number;
                    $UrlParams   = "transactionid=$orderNumber&paymentfrom=cart&success=true";
                } elseif ($request->payment_from == 'wallet') {
                    $orderNumber = $number;
                    $UrlParams   = "transactionid=$orderNumber&paymentfrom=wallet&success=true";
                }elseif ($request->payment_from == 'subscription') {
                    $orderNumber = $number;
                    $UrlParams   = "transactionid=$orderNumber&paymentfrom=subscription&success=true";
                }
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
                    "amount"        => $request->amount,
                    "currency"      => $this->currency,
                    "email"         => $userEmail,
                    "phone"         => $userPhone,
                    "reference_id"  => $orderNumber,
                    "first_name"    => $userFirstName,
                    "last_name"     => $userLastName,
                    "merchant"      => $this->obo_business_name,
                    "cancel_url"    => url($request->cancelUrl),
                    "return_url"    => route('webhook.obo.pay', $UrlParams),
                    "custom_pg_id"  => $this->obo_market_place_id,
                ], JSON_UNESCAPED_SLASHES);
                \Log::info($input);
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

    public function webhook(Request $request)
    {
        \Log::info("webhook");
        \Log::info($request->all());


        if ($request->has('success') && $request->success === "true") {
            $transactionId = $request->transactionid;
            if ($request->paymentfrom == 'cart') {
                \Log::info($transactionId);
                $order = Order::where('order_number', $transactionId)->first();
                \Log::info($order);
                if ($order) {
                    $order->payment_status = '1';
                    $order->save();
                    $this->orderSuccessCartDetail($order);
                    return redirect()->route('order.success', $order->id);
                }
            } elseif ($request->paymentfrom == 'wallet') {
                $payment = Payment::where('transaction_id', $transactionId)->first();
                $user    = auth()->user();
                $wallet  = $user->wallet;
                $wallet->depositFloat($payment->balance_transaction, ['Wallet has been <b>credited</b> for order number <b>' . $payment->transaction_id . '</b>']);
                return redirect()->route('user.wallet');
            }elseif ($request->paymentfrom == 'subscription') {
                $payment = Payment::where('transaction_id', $transactionId)->first();
                

            }
        } else {
            return "error";
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

    public function orderNumber($request)
    {
        $time    = time();
        $user_id = auth()->id();
        $amount  = $request->amount;
        if ($request->payment_from == 'wallet') {
            Payment::create([
                'amount' => 0,
                'transaction_id' => $time,
                'balance_transaction' => $amount,
                'type' => 'wallet',
                'date' => date('Y-m-d'),
                'user_id' => $user_id,
                'payment_from'=>$request->user_from ?? 'web'
            ]);
        }elseif ($request->payment_from == 'subscription') {
            $time = $request->subsid??$request->subscription_id . '_' . time();
            Payment::create([
                'amount' => 0,
                'transaction_id' => $time,
                'balance_transaction' => $amount,
                'type' => 'subscription',
                'date' => date('Y-m-d'),
                'user_id' => $user_id,
                'payment_from'=>$request->user_from ??'web'
            ]);
        }
        return $time;
    }
}
