<?php

namespace App\Http\Controllers;

// use Core\Authentication\Auth;

use Algolia\AlgoliaSearch\Http\GuzzleHttpClient;
use App\Http\Controllers\Api\v1\PickupDeliveryController;
use App\Http\Controllers\Api\v1\UserSubscriptionController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Traits\ApiResponser;
use App\Models\CaregoryKycDoc;
use App\Models\Cart;
use App\Models\CartAddon;
use App\Models\CartCoupon;
use App\Models\CartProduct;
use App\Models\CartProductPrescription;
use App\Models\ClientCurrency;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentOption;
use App\Models\User;
use App\Models\UserVendor;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use JWT\Token;
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
        $primaryCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
        $this->currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'USD';
    }


    public function index(Request $request, $amt)
    {
        try {
            // \Log::info($request->all());
            // dd($request->all());
            $orderNumber = $this->orderNumber($request);
            $users = $this->createUserToken();
            // $user = Auth::loginUsingId(1);
            $user=Auth::user();
            // dd($request->all());
            $urlParams = '';
            $amount = $request->amt;
            $nameString = "name";
            $name = strtok(auth()->user()->name, " ");
            $lastname = substr(strstr(auth()->user()->name, " "), 1);
            $email = auth()->user()->email;
            $phone = auth()->user()->phone_number;
            $users = $this->createUserToken();
            if ($request->payment_from == 'cart') {
                $urlParams   = "transactionid=$orderNumber&paymentfrom=cart&success=true";
            } elseif ($request->payment_from == 'wallet') {
                $urlParams   = "transactionid=$orderNumber&paymentfrom=wallet&success=true";
            } elseif ($request->payment_from == 'subscription') {
                $urlParams   = "transactionid=$orderNumber&subscription_id=$request->subscription_id&amount=$request->amt&success=true";
            } elseif ($request->payment_from == 'pickup_delivery') {
                $urlParams   = "transactionid=$orderNumber&paymentfrom=pickup_delivery&reload_route=$request->reload_route&amount=$request->amt&success=true";
            } elseif ($request->payment_from == 'tip') {

                $urlParams   = "transactionid=$orderNumber&order_number=$request->order_number&paymentfrom=tip&amount=$request->amt&success=true";
            }
            $postURL = url('/livee/success' . '?' . $urlParams);
            \Log::info($postURL);
            // dd($postURL);
            // dd($amount);
            //         $html="<div class='d-flex justify-content-center mt-5'>".
            //         "<form  action='https://www.livees.net/Checkout/api4' method='POST'  class='d-flex flex-column gap-3 liveesForm'>".

            //             "<input type='hidden' name='_' value='sa4b4km6c0l9eq7y6od88cnjp62efvr6ix59u5taz2ghw0193' class='form-control'>".
            //          "<input type='hidden' name='__' value='bj65bih1kzo740snwbru2q9px3v5503fetfdaaegmc64yle58' class='form-control'>".
            //          "<input type='hidden' name=' postURL' value='$url' class='form-control'>".
            //          "<input type='text' name='amt2' readonly value='$amount' class='form-control'>".
            //          "<input type='hidden' name='currency' value='BOB' class='form-control'>".
            //          "<input type='hidden' name='invno' value='.' class='form-control'>".
            //          "<input type='text' name='name' value='$name' class='form-control'>".
            //          "<input type='text' name=' lastname' value='$lastname' class='form-control'>".
            //          "<input type='email' name='email' value='$email' class='form-control'>".
            //          "<input type='text' name='phone' value='$phone' class='form-control'>".
            //         //   "<input type='hidden' name='order_number' value='$orderNumber' class='form-control'>".
            //                 "<input type='text' name='ciudad' value='Santa Cruz de la Sierra' class='form-control>".
            // "<select name='pais' class='form-control invisible'><option value='BO'>Bolivia</option><option value='US'>Estados Unidos</option>".
            //          "</select>".
            //          "<select name='estado_lbl' class='form-select invisible'><option value='La Paz' class='invisible'>La Paz</option><option value='Santa Cruz' class='invisible'>Santa Cruz</option></select>".
            //                 "<input type='submit' class='btn btn-primary' value='submit'></select>".
            //             "</form>".
            //     "   </div>";
            //         return response()->json([$html]);
            return view('backend.payment.liveePay', compact('amount', 'postURL','user'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
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
            //  \Log::info("success");
              \Log::info($request->all());
            // // if ($request->has('success') ) {
            $transactionId = $request->transactionid;
            $payment = Payment::where('transaction_id', $transactionId)->first();
            // dd($transactionId);
            if ($payment) {
                \Log::info("payment");
                 \Log::info($payment);

                $payment->viva_order_id = $transactionId;
                $payment->payment_option_id = 59;
                $payment->save();
            }
            if ($request->paymentfrom == 'cart') {
                $order = Order::where('order_number', $transactionId)->first();
                // dd($request->all(),$order);
                if ($order) {
                    $order->payment_status = '1';
                    $order->save();
                    $this->orderSuccessCartDetail($order);
                    if ($payment->payment_from == 'web') {
                        return redirect()->route('order.success', $order->id);
                    } else {

                        $returnUrl = route('payment.gateway.return.response') . '/?gateway=livees' . '&status=200&order=' . $order->order_number;
                        return redirect($returnUrl);
                    }
                }
            } elseif ($request->paymentfrom == 'wallet') {
                // dd($request->all());
                if ($payment->payment_from == 'app') {
                    $user = User::findOrFail($payment->user_id);
                    Auth::login($user);
                    $returnUrl = route('payment.gateway.return.response') . '/?gateway=livees' . '&status=200&transaction_id=' . $payment->transaction_id . '&action=wallet';
                } else {
                    $user      = auth()->user();
                    $returnUrl = route('user.wallet');
                }
                $wallet  = $user->wallet;
                $wallet->depositFloat($payment->balance_transaction, ['Wallet has been <b>credited</b> for order number <b>' . $payment->transaction_id . '</b>']);
                return redirect($returnUrl);
            }
            elseif (isset($request->subscription_id)) {
                \Log::info("success",$request->all());
                $data['transaction_id'] = $payment->transaction_id;
                $data['payment_option_id'] = 59;
                $data['subsid'] = $request->subscription_id;
                $data['subscription_id'] = $request->subscription_id;
                $data['amount'] =$request->amount;
                $request = new \Illuminate\Http\Request($data);
                \Log::info($request->all());

                $subscriptionController = new UserSubscriptionController();
                $subscriptionController->purchaseSubscriptionPlan($request, $request->subscription_id);

                if ($payment->payment_from == 'web') {  \Log::info("inside webs");
                    return redirect()->route('user.subscription.plans');
                } else {
                    $returnUrl = route('payment.gateway.return.response') . '/?gateway=livee' . '&status=200&transaction_id=' . $payment->transaction_id . '&action=subscription';
                    return redirect($returnUrl);
                }
            } elseif ($request->paymentfrom == 'pickup_delivery') {
                $data['payment_option_id'] = 59;
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
\Log::info("inside tips");\Log::info($request->paymentfrom);
                $data['tip_amount'] = $request->amt;
                $data['order_number'] = $request->order_number;
                $data['transaction_id'] = $transactionId;
                $request = new \Illuminate\Http\Request($data);
                $orderController = new OrderController();
                $orderController->tipAfterOrder($request);
                if ($payment->payment_from == 'web') {
                    return redirect()->route('user.orders');
                } else {
                    $returnUrl = route('payment.gateway.return.response') . '/?gateway=livee' . '&status=200&order=' . $transactionId . '&action=tip';
                    return redirect($returnUrl);
                }
            }



            // } else {
            //     return "error";
            // }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function payFormWeb(Request $request)
    {

        try {

            // dd("$user");
            // dd($request->header());
            if (isset($request->user_id)) {
                // pr($request->auth_token);
                $user = User::where('id', $request->user_id)->first();
                Auth::login($user);
            }
            else{
                $user = Auth::loginUsingId(1);
            }
            $request->request->add(['payment_from' => isset($request->paymentfrom)?$request->paymentfrom:$request->payment_from]);
            $request->request->add(['amount' => isset($request->amt)?$request->amt:$request->amount]);

            $orderNumber = $this->orderNumber($request);
            // dd($request->all());
            $urlParams = '';
            $amount = $request->amt;
            $nameString = "name";
            // $name=User::where('transaction_id',$request->transaction_id);
            // $payment = Payment::where('transaction_id', $request->transaction_id)->first();
            // $user = $payment->user_id;
            $name = strtok(auth()->user()->name, " ");
           // $lastname = substr(strstr(auth()->user()->name, " "), 1);
            $email = auth()->user()->email;
            $phone = auth()->user()->phone_number;


            if ($request->payment_from == 'cart') {
                $urlParams   = "transactionid=$orderNumber&paymentfrom=cart&success=true";
            } elseif ($request->payment_from == 'wallet') {
                $urlParams   = "transactionid=$orderNumber&paymentfrom=wallet&success=true&come_from=app&amount=$request->amount&success=true";
            } elseif ($request->payment_from == 'subscription') {
                $urlParams   = "transactionid=$orderNumber&subscription_id=$request->subscription_id&amount=$request->amount&success=true";
            } elseif ($request->payment_from == 'pickup_delivery') {
                $urlParams   = "transactionid=$orderNumber&paymentfrom=pickup_delivery&reload_route=$request->reload_route&amount=$request->amount&success=true";
            } elseif ($request->payment_from == 'tip') {
                $urlParams   = "transactionid=$orderNumber&order_number=$request->order_number&paymentfrom=tip&amount=$request->amount&success=true";
            }
            $postURL = url('/livee/success' . '?' . $urlParams);
            \Log::info("url ".$postURL);
            return view('backend.payment.liveePay', compact('amount', 'postURL', 'user'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function orderNumber($request)
    {

        try {
            $time    = isset($request->transaction_id)?$request->transaction_id:time();
            $user_id = auth()->id();
            $amount  = $request->amt;
            // dd($request->all());
            if ($request->payment_from == 'cart') {
                $time = $request->order_number;
                Payment::create([
                    'amount' => 0,
                    'transaction_id' => $time,
                    'balance_transaction' => $amount,
                    'type' => 'cart',
                    'date' => date('Y-m-d'),
                    'user_id' => $user_id,
                    'payment_from' => $request->come_from ?? 'web',
                ]);
            } elseif ($request->payment_from == 'wallet') {
                Payment::create([
                    'amount' => $amount,
                    'transaction_id' => $time,
                    'balance_transaction' => $amount,
                    'type' => 'wallet',
                    'date' => date('Y-m-d'),
                    'user_id' => $user_id,
                    'payment_from' => $request->come_from ?? 'web',
                ]);
            } elseif ($request->payment_from == 'subscription') {
                Payment::create([
                    'amount' => 0,
                    'transaction_id' => $time,
                    'balance_transaction' => round($amount,2),
                    'type' => 'subscription',
                    'date' => date('Y-m-d'),
                    'user_id' => $user_id,
                    'payment_from' => $request->come_from ?? 'web',
                ]);
            }
            elseif ($request->payment_from == 'tip') {
                // \Log::info("inside tippppp");
                $time = time();
                Payment::create([
                    'amount' => 0,
                    'transaction_id' =>  $time,
                    'balance_transaction' => $request->amt,
                    'type' => 'tip',
                    'date' => date('Y-m-d'),
                    'user_id' => $user_id,
                    'payment_from' => $request->come_from ?? 'web',
                ]);

            } else if ($request->payment_from == 'pickup_delivery') {
                $time = $request->order_number;
                Payment::create([
                    'amount' => 0,
                    'transaction_id' => $time,
                    'balance_transaction' => $request->amt,
                    'type' => 'pickup_delivery',
                    'date' => date('Y-m-d'),
                    'user_id' => $user_id,
                    'payment_from' => $request->come_from ?? 'web',
                ]);
            }
            return $time;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }




    public function orderSuccessCartDetail($order)
    {
        // dd("ordersuccess");
        try {
            // Auto accept order
            $orderController = new OrderController();
            $orderController->autoAcceptOrderIfOn($order->id);

            $cart = Cart::where('user_id', $order->user_id)->select('id')->first();
            $cartid = $cart->id;

            Cart::where('id', $cartid)->update([
                'schedule_type' => null,
                'scheduled_date_time' => null,
                'comment_for_pickup_driver' => null,
                'comment_for_dropoff_driver' => null,
                'comment_for_vendor' => null,
                'schedule_pickup' => null,
                'schedule_dropoff' => null,
                'specific_instructions' => null
            ]);

            CaregoryKycDoc::where('cart_id', $cartid)->update([
                'ordre_id' => $order->id,
                'cart_id' => ''
            ]);
            CartAddon::where('cart_id', $cartid)->delete();
            CartCoupon::where('cart_id', $cartid)->delete();
            CartProduct::where('cart_id', $cartid)->delete();
            CartProductPrescription::where('cart_id', $cartid)->delete();

            if (! empty($order->vendors)) {
                foreach ($order->vendors as $vendor_value) {
                    $vendor_order_detail = $orderController->minimize_orderDetails_for_notification($order->id, $vendor_value->vendor_id);
                    $user_vendors = UserVendor::where([
                        'vendor_id' => $vendor_value->vendor_id
                    ])->pluck('user_id');
                    $orderController->sendOrderPushNotificationVendors($user_vendors, $vendor_order_detail);
                }
            }

            $vendor_order_detail = $orderController->minimize_orderDetails_for_notification($order->id);
            $super_admin = User::where('is_superadmin', 1)->pluck('id');
            $orderController->sendOrderPushNotificationVendors($super_admin, $vendor_order_detail);

                // send sms
                $this->sendOrderSuccessSMS($order);

        } catch (\Exception $e) {
            \Log::info('orderSuccessCartDetail error :-' . $e->getMessage());
            return true;
        }
        return true;
    }

    public function mobilePay(Request $request, $domain = '')
    {

        $message = '';
        $amount = $request->amount;
        \Log::info($request->all());
        \Log::info(['user' => auth()->user()]);

        $message = '';
        $amount = $request->amount;
        $user = auth()->user();
        $action = isset($request->action) ? $request->action : '';
        $params = '?amt=' . $amount . '&paymentfrom=' . $action."&come_from=app&user_id=".$user->id;
        if ($action == 'cart') {
            $params = $params . '&order_number=' . $request->order_number . '&app=1';
        } elseif ($action == 'wallet') {
            $params = $params . '&app=2&transaction_id=' . time();
        } elseif ($action == 'subscription') {
            $params = $params . '&app=3&subscription_id=' . $request->subscription_id;
        } elseif ($action == 'tip') {
            $params = $params . '&app=3&order_number=' . $request->order_number;
        }
        // dd($request->all());
        // try {
        //     $request->request->add(['payment_from' => $request->action, 'from' => $request->action, 'amt' => $request->amount, 'subsid' => $request->subscription_id ?? '', 'user_from' => 'app']);
        //     $data =  $this->index($request, $domain, 'app');
        //     if (isset($data) && !empty($data)) {
        //         return $data;
        //     }
        // } catch (\Exception $e) {
        //     return $e->getMessage();
        // }
        $url = url('payment/livees/api/' . $params);
            // \Log::info
        return $this->successResponse(($url));
    }

    public function createUserToken()
    {
        $user = auth()->user();
        $token1 = new Token();
        $token = $token1->make([
            'key' => 'royoorders-jwt',
            'issuer' => 'royoorders.com',
            'expiry' => strtotime('+1 month'),
            'issuedAt' => time(),
            'algorithm' => 'HS256',
        ])->get();
        $token1->setClaim('user_id', $user->id);
        $this->token = $token;
        $user->auth_token = $token;
        $user->save();
        return $user;
    }

    public function livee()
    {
        return view('backend.payment.liveePay');
    }
}
