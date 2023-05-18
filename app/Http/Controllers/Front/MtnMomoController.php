<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use Auth;
use App\Models\User;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\CartAddon;
use App\Models\UserVendor;
use App\Models\CartCoupon;
use App\Models\UserAddress;
use App\Models\CartProduct;
use Illuminate\Http\Request;
use App\Models\PaymentOption;
use App\Models\CaregoryKycDoc;
use Illuminate\Support\Carbon;
use App\Http\Traits\ApiResponser;
use App\Models\CartProductPrescription;
use App\Models\ClientCurrency;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Routing\UrlGenerator;
use Log;
use App\Http\Traits\MtnMomoPaymentManager;

class MtnMomoController extends FrontController
{
    use ApiResponser;
    use MtnMomoPaymentManager;

    private $subscription_key;

    private $appUrl;

    private $reference_id;

    private $token;

    private $environment;

    private $api_key;

    private $currency;

    public function __construct()
    {
        $payOpt = PaymentOption::select('credentials', 'test_mode', 'status')->where('code', 'mtn_momo')
            ->where('status', 1)
            ->first();
        $json = json_decode($payOpt->credentials);
        $this->subscription_key = $json->subscription_key;
        $this->reference_id = $json->reference_id;
        $this->api_key = $json->api_key;
        $this->token = base64_encode($this->reference_id . ':' . $this->api_key);
        if ($payOpt->test_mode == '1') {
            $this->appUrl = 'https://sandbox.momodeveloper.mtn.com/';
            $this->environment = 'sandbox';
        } else {
            $this->appUrl = 'https://payments.stabexinternational.com/api/mtn/Callback';
            $this->environment = 'live';
        }

        $primaryCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
        $this->currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'EUR';
    }

    public function orderNumber($request)
    {
        $time = '';
        $amt = $request->amt ?? $request->amount;
        if (isset($request->auth_token) && ! empty($request->auth_token)) {
            $user = User::where('auth_token', $request->auth_token)->first();
            FacadesAuth::login($user);
        } else {
            $user = auth()->user();
        }
        $name = explode(' ', $user->name);
        $returnUrl = '';
        if ($request->from == 'cart') {
            $request->amt = $amt;
            $time = $request->order_number;
            Payment::create([
                'amount' => 0,
                'transaction_id' => $time,
                'balance_transaction' => $amt,
                'type' => 'cart',
                'date' => date('Y-m-d')
            ]);
        } elseif ($request->from == 'pickup_delivery') {
            $request->amt = $amt;
            $time = $request->order_number;
            Payment::create([
                'amount' => 0,
                'transaction_id' => $time,
                'balance_transaction' => $amt,
                'type' => 'pickup_delivery',
                'date' => date('Y-m-d'),
                'user_id' => auth()->id(),
                'payment_from' => $request->device ?? 'web'
            ]);
        } elseif ($request->from == 'wallet') {
            $time = ($request->transaction_id) ?? 'W_' . time();
            // Save transaction before payment success for get information only
            Payment::create([
                'amount' => 0,
                'transaction_id' => $time,
                'balance_transaction' => $amt,
                'type' => 'wallet',
                'date' => date('Y-m-d')
            ]);
            $request->amt = $amt;
        } elseif ($request->from == 'tip') {
            $time = 'T_' . time() . '_' . $request->order_number;
            Payment::create([
                'amount' => 0,
                'transaction_id' => $time,
                'balance_transaction' => $amt,
                'type' => 'tip',
                'date' => date('Y-m-d')
            ]);

            $request->amt = $amt;
        } elseif ($request->from == 'subscription') {
            $time = 'S_' . time() . '_' . (! empty($request->subsid) ? $request->subsid : $request->subscription_id);
            Payment::create([
                'amount' => 0,
                'transaction_id' => $time,
                'balance_transaction' => $amt,
                'type' => 'subscription',
                'date' => date('Y-m-d')
            ]);
            $request->amt = $amt;
        }
        $request->request->add([
            'amt' => number_format($amt, 2)
        ]);
        return $time;
    }

    public function createToken(Request $request, UrlGenerator $url)
    {
        self::__init(false);

        if (! self::$_isConfigurationSet) {
            return self::response(500, 'Sorry for inconvinence. Please try again later');
        }

        $data = [];
        if ($request->from == 'cart') {
            $data['amt'] = $request->amt;
            $data['order_number'] = $request->order_number;
            $data['from'] = $request->from;
        } else if ($request->from == 'wallet') {
            $data['amt'] = $request->amt;
            $data['from'] = $request->from;
        } else if ($request->from == 'subscription') {
            $data['amt'] = $request->amt;
            $data['from'] = $request->from;
            $data['subsid'] = $request->subsid;
        } else if ($request->from == 'tip') {
            $data['amt'] = $request->amt;
            $data['from'] = $request->from;
            $data['order_number'] = $request->order_number;
        }

        // generate AccessToken
        self::GenerateAccressToken();

        if (empty(self::$_accessToken)) {
            return self::response(500, 'Sorry for inconvinence. Please try again later');
        }

        return self::RequestToPay(self::$_accessToken, $data);

        // $response = curl_exec($curl);
        // $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // Log::info('access_token' . json_encode($status));
        // if ($status == 200) {
        // $result = json_decode($response, true);

        // if ($result['token_type'] == 'access_token') {
        // $token = $result['access_token'];
        // return self::RequestToPay($token, $data);
        // }
        // } else if ($status == 401) {
        // return json_encode([
        // 'status' => 401,
        // 'message' => 'Unauthorized.'
        // ]);
        // } else if ($status == 500) {
        // return json_encode([
        // 'status' => 401,
        // 'message' => 'Internal Server Error'
        // ]);
        // }
    }

    public function RequestToPay($token, $data)
    {
        if ($data['from'] == 'cart') {
            $amount = $data['amt'];
            $from = $data['from'];
            $order_number = $data['order_number'];
        } elseif ($data['from'] == 'wallet') {
            $amount = $data['amt'];
            $from = $data['from'];
            $order_number = 'wallet';
        } else if ($data['from'] == 'subscription') {
            $amount = $data['amt'];
            $from = $data['from'];
            $subsid = $data['subsid'];
            $order_number = 'subscription';
        } else if ($data['from'] == 'tip') {
            $amount = $data['amt'];
            $from = $data['from'];
            $order_number = $data['order_number'];
        }

        $order_number = 'Transc' . implode('', range(100, 150));

        $currency = 'EUR';
        $partyId = '256761412741';

        list ($transactionId, $status) = self::paymentRequest($token, $amount, $currency, $order_number, $partyId);
        if ($status)
            self::sucessPayment($data, $transactionId);
    }

    public function GetpaymentTransaction($token, $reference_id, $data)
    {
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://proxy.momoapi.mtn.com/collection/v1_0/' . $reference_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'X-Target-Environment: ' . $envirement,
                'Ocp-Apim-Subscription-Key: ' . $subscription_key,
                'Authorization: Bearer ' . $token
            )
        ));

        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($status == 200) {
            $result = json_decode($response, true);
            $payment_status = $result['status'];
            if ($payment_status == 'SUCCESSFUL') {
                $transactionId = $result['financialTransactionId'];
                Log::info('payment' . json_encode($result));
                return self::sucessPayment($data, $transactionId);
            }
        }

        Log::info('status' . json_encode($status));
    }

    public function sucessPayment($request, $transactionId)
    {
        if ($request['from'] == "app") {
            $user = User::where('auth_token', $request->auth_token)->first();
            Auth::login($user);
        }
        $user = Auth::user();
        // $transactionId = $pamyent->id;
        if ($request['from'] == 'cart') {
            $order_number = $request['order_number'];
            $order = Order::with([
                'paymentOption',
                'user_vendor',
                'vendors:id,order_id,vendor_id'
            ])->where('order_number', $order_number)->first();
            if ($order) {
                $order->payment_status = 1;
                $order->save();
                $payment_exists = Payment::where('transaction_id', $transactionId)->first();
                if (! $payment_exists) {
                    Payment::insert([
                        'date' => date('Y-m-d'),
                        'order_id' => $order->id,
                        'transaction_id' => $transactionId,
                        'balance_transaction' => $request['amt'],
                        'type' => 'cart'
                    ]);

                    // Auto accept order
                    $orderController = new OrderController();
                    $orderController->autoAcceptOrderIfOn($order->id);
                    $cart = Cart::select('id')->where('status', '0')
                        ->where('user_id', $user->id)
                        ->first();

                    // Remove cart
                    CaregoryKycDoc::where('cart_id', $cart->id)->update([
                        'ordre_id' => $order->id,
                        'cart_id' => ''
                    ]);
                    Cart::where('id', $cart->id)->update([
                        'schedule_type' => null,
                        'scheduled_date_time' => null
                    ]);
                    CartAddon::where('cart_id', $cart->id)->delete();
                    CartCoupon::where('cart_id', $cart->id)->delete();
                    CartProduct::where('cart_id', $cart->id)->delete();
                    CartProductPrescription::where('cart_id', $cart->id)->delete();
                    // send success sms
                    $this->sendSuccessSMS($request, $order);
                    // Send Notification
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
                }
                if ($request['from'] == 'app') {
                    $returnUrl = route('payment.gateway.return.response') . '/?gateway=mtn_momo' . '&status=200&transaction_id=' . $transactionId . '&order=' . $order_number;
                } else {
                    $returnUrl = route('order.return.success');
                }

                return $returnUrl;
            }
        } elseif ($request['from'] == 'wallet') {
            $request['wallet_amount'] = $request['amt'];
            $request['transaction_id'] = $transactionId;

            $request = new \Illuminate\Http\Request($request);

            $walletController = new WalletController();
            $walletController->creditWallet($request);
            if ($request['from'] == 'app') {
                $returnUrl = route('payment.gateway.return.response') . '/?gateway=mtn_momo' . '&status=200&transaction_id=' . $transactionId;
            } else {
                $returnUrl = route('user.wallet');
            }
            return $returnUrl;
        } elseif ($request['from'] == 'tip') {

            $request['tip_amount'] = $request['amt'];
            $request['order_number'] = $request['order_number'];
            $request['transaction_id'] = $transactionId;
            $request = new \Illuminate\Http\Request($request);

            $orderController = new OrderController();
            $orderController->tipAfterOrder($request);
            if ($request['from'] == 'app') {
                $returnUrl = route('payment.gateway.return.response') . '/?gateway=mtn_momo' . '&status=200&transaction_id=' . $transactionId;
            } else {
                $returnUrl = route('user.orders');
            }
            return $returnUrl;
        } elseif ($request['from'] == 'subscription') {
            $request['transaction_id'] = $transactionId;
            $request['payment_option_id'] = 48;
            $request['subsid'] = $request['subsid'];
            $request['subscription_id'] = $request['subsid'];
            $request['amount'] = $request['amt'];

            $request = new \Illuminate\Http\Request($request);

            $subscriptionController = new UserSubscriptionController();
            $subscriptionController->purchaseSubscriptionPlan($request, '', $request->subscription_id);
            if ($request['from'] == 'app') {
                $returnUrl = route('payment.gateway.return.response') . '/?gateway=mtn_momo' . '&status=200&transaction_id=' . $transactionId;
            } else {
                $returnUrl = route('user.subscription.plans');
            }
            return $returnUrl;
        }
        return route('order.return.success');
    }
}