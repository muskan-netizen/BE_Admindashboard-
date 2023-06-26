<?php

namespace App\Http\Controllers;

// use Core\Authentication\Auth;

use Algolia\AlgoliaSearch\Http\GuzzleHttpClient;
use App\Http\Traits\ApiResponser;
use App\Models\ClientCurrency;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentOption;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use OrderTrait;
use Predis\Protocol\Text\Handler\ErrorResponse;

class LiveePaymentController extends Controller
{
    use ApiResponser;
    public $currency;
    private $livee_business_name;
    private $livee_client_id;
    private $livee_key_id;
    private $livee_market_place_id;
    private $testMode;
    const trade_key = 'sa4b4km6c0l9eq7y6od88cnjp62efvr6ix59u5taz2ghw0193';
    const TOKEN_API           = "";
    const resource_key   = "bj65bih1kzo740snwbru2q9px3v5503fetfdaaegmc64yle58";
    const URL_API             = "";

    public function __construct()
    {
        $payOption = PaymentOption::select('credentials', 'test_mode', 'status')->where('code', 'livee')->where('status', 1)->first();

        $credentials = json_decode($payOption->credentials);
        // $this->livee_business_name   = $credentials->livee_business_name;
        // $this->livee_client_id       = $credentials->livee_client_id;
        // $this->livee_key_id          = $credentials->livee_key_id;
        // $this->livee_market_place_id = $credentials->livee_market_place_id;
        // $this->testMode            = $payOption->test_mode;

        $primaryCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
        $this->currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'USD';
    }


    public function beforePayment(Request $request, $domain = '', $app = '')
    {
        try {
            // $client=new GuzzleHttpClient();
            $tokenData =  $this->token();
            // dd($request->all());
            if (isset($tokenData)) {
                // dd("here");
                    // user details
                    $user = auth()->user();
                    $userEmail       = $user->email;
                    $userPhone       = $user->dial_code . $user->phone_number;
                    $userFirstName   = strtok($user->name, " ");
                    $userLastName    = substr(strstr($user->name, " "), 1);
                    $urlParams='';
                    $apiUrl = "https://www.livees.net/Checkout/api4";
                    $orderNumber = $this->orderNumber($request);
                    if ($request->payment_from == 'cart') {
                        $urlParams   = "transactionid=$orderNumber&paymentfrom=cart&success=true";
                    } elseif ($request->payment_from == 'wallet') {
                        $urlParams   = "transactionid=$orderNumber&paymentfrom=wallet&success=true";
                    } elseif ($request->payment_from == 'subscription') {
                        $urlParams   = "transactionid=$orderNumber&subscription_id=$request->subscription_id&amount=$request->amount&success=true";
                    } elseif ($request->payment_from == 'pickup_delivery') {
                        $urlParams   = "transactionid=$orderNumber&paymentfrom=pickup_delivery&reload_route=$request->reload_route&amount=$request->amount&success=true";
                    } elseif ($request->payment_from == 'tip') {
                        $urlParams   = "transactionid=$orderNumber&order_number=$request->order_number&paymentfrom=tip&amount=$request->amount&success=true";
                    }

                    $header = [
                        'Content-Type' => 'x-www-form-urlencoded ',
                    ];
                    // $input = [
                    //     "amt2"        => $request->amount,
                    //     "currency"      => $this->currency,
                    //     "email"         => $userEmail,
                    //     "phone"         => $userPhone,
                    //     "reference_id"  => $orderNumber,
                    //     "name"    => $userFirstName,
                    //     "lastname"     => $userLastName,
                    //     "_"     => self::trade_key,
                    //     "__"  => self::resource_key,
                    //     "cancel_url"    => url(($request->cancelUrl) ?? ('after-payment/livee' . '?success=false')),
                    //     "postURL"    => url('after-payment/livee' . '?' . $urlParams),
                    //     "invno"=>"101",
                    //     "pais"=>'BO'

                    // ];

                    // dd($input);
                    // $response = Http::withBody($input, 'x-www-form-urlencoded ')->withHeaders($header)->post($apiUrl);

                    //  dd($apiUrl);

                    $response = Http::asForm()->get($apiUrl, [
                    //    "amt2"=>'200',
                       "currency"      => $this->currency,
                       "amt2"        => $request->amount,
                           "currency"      => $this->currency,
                           "email"         => $userEmail,
                           "phone"         => $userPhone,
                           "reference_id"  => $orderNumber,
                           "name"    => $userFirstName,
                           "lastname"     => $userLastName,
                           "_"     => self::trade_key,
                           "__"  => self::resource_key,
                           "cancel_url"    => url(($request->cancelUrl) ?? ('after-payment/livee' . '?success=false')),
                           "postURL"    => url('after-payment/livee' . '?' . $urlParams),
                           "invno"=>"101",
                           "pais"=>'BO'
                    ]);

                    // if ($response->status() >= 300 && $response->status() <= 399) {
                    //     $redirectUrl = $response->headers()['Location'][0];

                    //     // Send a new request to the redirect URL with the form data
                    //     $response = Http::asForm()->post($redirectUrl, [
                    //         "amt2" => '200',
                    //         "currency" => $this->currency,
                    //     ]);
                    // }
                    // dd($input);
                    $responseData = $response;
                    // dd($response);
                    if (isset($responseData)) {
                        $redirectUrl = $apiUrl;
                        return response()->json([
                            'status' => 'Success',
                            'data'   => $redirectUrl
                        ], 200);
                    } else {
                        return $this->errorResponse("Url Is Not Generated", 400);
                    }

            } else {
                return $this->errorResponse('Token Error', 400);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        // $user = auth()->user();
        // $curl = curl_init();
        // $userEmail       = $user->email;
        // $userPhone       = $user->dial_code . $user->phone_number;
        // $userFirstName   = strtok($user->name, " ");
        // $userLastName    = substr(strstr($user->name, " "), 1);
        // $urlParams = '';
        // $apiUrl = "https://www.livees.net/Checkout/api4";
        // $orderNumber = $this->orderNumber($request);
        // $input = [
        //     "amt2"        => $request->amount,
        //     "currency"      => $this->currency,
        //     "email"         => $userEmail,
        //     "phone"         => $userPhone,
        //     "reference_id"  => $orderNumber,
        //     "name"    => $userFirstName,
        //     "lastname"     => $userLastName,
        //     "_"     => self::trade_key,
        //     "__"  => self::resource_key,
        //     "cancel_url"    => url(($request->cancelUrl) ?? ('after-payment/livee' . '?success=false')),
        //     "postURL"    => url('after-payment/livee' . '?' . $urlParams),
        //     "invno" => "101",
        //     "pais" => 'BO'

        // ];
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => "https://www.livees.net/Checkout/api4", // your preferred url
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 30000,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "POST",
        //     CURLOPT_POSTFIELDS => json_encode($input),
        //     CURLOPT_HTTPHEADER => array(


        //         "content-type: application/x-www-form-urlencoded",
        //     ),
        // ));

        // $response = curl_exec($curl);
        // $err = curl_error($curl);

        // curl_close($curl);


        // if ($err) {
        //     echo "cURL Error #:" . $err;
        // } else {
        //     print_r(json_decode($response));
        // }
    }

    public function token()
    {
        try {
            $apiUrl = "https://www.livees.net/Checkout/api4";
            $input = json_encode([
                "trade_key" => $this::trade_key,
                "resource_key" => $this::resource_key
            ]);

            $header = [
                'Content-Type' => 'application/json'
            ];
            $response = Http::withBody($input, 'application/json')->withHeaders($header)->post($apiUrl);

            return $response;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function afterPayment(Request $request)
    {
        try {
            if ($request->has('success') && $request->success === "true") {
                $transactionId = $request->transactionid;
                $payment = Payment::where('transaction_id', $transactionId)->first();
                if ($payment) {
                    $payment->viva_order_id = $transactionId;
                    $payment->payment_option_id = 59;
                    $payment->save();
                }
                if ($request->paymentfrom == 'cart') {
                    $order = Order::where('order_number', $transactionId)->first();
                    if ($order) {
                        $order->payment_status = '1';
                        $order->save();
                        $this->orderSuccessCartDetail($order);
                        if ($payment->payment_from == 'web') {
                            return redirect()->route('order.success', $order->id);
                        } else {

                            $returnUrl = route('payment.gateway.return.response') . '/?gateway=livee' . '&status=200&order=' . $order->order_number;
                            return redirect($returnUrl);
                        }
                    }
                } elseif ($request->paymentfrom == 'wallet') {
                    if ($payment->payment_from == 'app') {
                        $user = User::findOrFail($payment->user_id);
                        Auth::login($user);
                        $returnUrl = route('payment.gateway.return.response') . '/?gateway=livee' . '&status=200&transaction_id=' . $payment->transaction_id . '&action=wallet';
                    } else {
                        $user      = auth()->user();
                        $returnUrl = route('user.wallet');
                    }
                    $wallet  = $user->wallet;
                    $wallet->depositFloat($payment->balance_transaction, ['Wallet has been <b>credited</b> for order number <b>' . $payment->transaction_id . '</b>']);
                    return redirect($returnUrl);
                } elseif (isset($request->subscription_id)) {
                    $data['transaction_id'] = $payment->transaction_id;
                    $data['payment_option_id'] = 59;
                    $data['subsid'] = $request->subscription_id;
                    $data['subscription_id'] = $request->subscription_id;
                    $data['amount'] = $request->amount;
                    $request = new \Illuminate\Http\Request($data);
                    $subscriptionController = new UserSubscriptionController();
                    $subscriptionController->purchaseSubscriptionPlan($request, '', $request->subscription_id);
                    if ($payment->payment_from == 'web') {
                        return redirect()->route('user.subscription.plans');
                    } else {
                        $returnUrl = route('payment.gateway.return.response') . '/?gateway=livee' . '&status=200&transaction_id=' . $payment->transaction_id . '&action=subscription';
                        return redirect($returnUrl);
                    }
                } elseif ($request->paymentfrom == 'pickup_delivery') {
                    $data['payment_option_id'] = 56;
                    $data['transaction_id'] = $transactionId;
                    $data['amount'] = $request->amount;
                    $data['order_number'] = $transactionId;
                    $data['reload_route'] = $request->reload_route;
                    $request = new \Illuminate\Http\Request($data);
                    $plaseOrderForPickup = new PickupDeliveryController();
                    $res = $plaseOrderForPickup->orderUpdateAfterPaymentPickupDelivery($request);
                    if ($payment->payment_from == 'web') {
                        return redirect()->route('front.booking.details', $transactionId);
                    } else {
                        $returnUrl = route('payment.gateway.return.response') . '/?gateway=livee' . '&status=200&order=' . $transactionId;
                        return redirect($returnUrl);
                    }
                } elseif ($request->paymentfrom == 'tip') {
                    $data['tip_amount'] = $request->amount;
                    $data['order_number'] = $request->order_number;
                    $data['transaction_id'] = $transactionId;
                    $request = new \Illuminate\Http\Request($data);
                    $orderController = new OrderController();
                    $orderController->tipAfterOrder($request);
                    if ($payment->payment_from == 'web') {
                        return redirect()->route('user.orders');
                    } else {
                        $returnUrl = route('payment.gateway.return.response') . '/?gateway=ono' . '&status=200&order=' . $transactionId . '&action=tip';
                        return redirect($returnUrl);
                    }
                }
            } else {
                return "error";
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function orderNumber($request)
    {
        try {
            $time    = time();
            $user_id = auth()->id();
            $amount  = $request->amount;
            if ($request->payment_from == 'cart') {
                $time = $request->order_number;
                Payment::create([
                    'amount' => 0,
                    'transaction_id' => $time,
                    'balance_transaction' => $amount,
                    'type' => 'cart',
                    'date' => date('Y-m-d'),
                    'user_id' => $user_id,
                    'payment_from' => $request->user_from ?? 'web'
                ]);
            } elseif ($request->payment_from == 'wallet') {
                Payment::create([
                    'amount' => 0,
                    'transaction_id' => $time,
                    'balance_transaction' => $amount,
                    'type' => 'wallet',
                    'date' => date('Y-m-d'),
                    'user_id' => $user_id,
                    'payment_from' => $request->user_from ?? 'web'
                ]);
            } elseif ($request->payment_from == 'subscription') {
                Payment::create([
                    'amount' => 0,
                    'transaction_id' => $time,
                    'balance_transaction' => $amount,
                    'type' => 'subscription',
                    'date' => date('Y-m-d'),
                    'user_id' => $user_id,
                    'payment_from' => $request->user_from ?? 'web'
                ]);
            } elseif ($request->payment_from == 'tip') {
                Payment::create([
                    'amount' => 0,
                    'transaction_id' => $time,
                    'balance_transaction' => $amount,
                    'type' => 'tip',
                    'date' => date('Y-m-d'),
                    'user_id' => $user_id,
                    'payment_from' => $request->user_from ?? 'web'
                ]);
            } else if ($request->payment_from == 'pickup_delivery') {
                $time = $request->order_id  ?? $request->order_number;
                Payment::create([
                    'amount' => 0,
                    'transaction_id' => $time,
                    'balance_transaction' => $amount,
                    'type' => 'pickup_delivery',
                    'date' => date('Y-m-d'),
                    'user_id' => $user_id,
                    'payment_from' => $request->user_from ?? 'web'
                ]);
            }
            return $time;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function index(){
        return view('backend.payment.liveePay');
     }

    //  public function afterPayment(){
    //     return redirect()->back();
    //  }
}
