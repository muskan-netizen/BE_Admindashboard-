<?php

namespace App\Http\Controllers\Front;

use App\Models\Payment;
use App\Helpers\Mastercard\Mastercard;
use App\Http\Controllers\Controller;
use App\Models\ClientCurrency;
use App\Models\Currency;
use App\Models\PaymentOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Ramsey\Uuid\Uuid;

class MastercardPaymentController extends Controller
{
    private Mastercard $client;
    private object $credentials;

    public function __construct()
    {
        $pay_option        = PaymentOption::where('code', 'mastercard')->where('status', 1)->get(['credentials', 'test_mode', 'status'])->firstOrFail();
        $this->credentials = json_decode($pay_option->credentials);

        $gateway = $pay_option->test_mode == 1
            ? 'test-gateway.mastercard.com'
            : $this->credentials->mastercard_gateway;

        $this->client = new Mastercard(
            $this->credentials->mastercard_merchant_id,
            $this->credentials->mastercard_merchant_key,
            $gateway
        );
    }

    public function createSession(Request $request)
    {
        $payment_info = (object)$request->validate([
            'payment_from' => 'required',
            'amount' => 'numeric|required',
        ]);

        $user = Auth::user();

        $firstName = explode(' ', $user->name, 1)[0];
        $lastName  = explode(' ', $user->name, 2)[1];

        $customer = compact('firstName', 'lastName');
        $customer['email']       = $user->email;
        $customer['mobilePhone'] = $user->phone_number;

        $reference_id = $this->orderNumber($request);

        $currency_id = Session::get('customerCurrency');
        $currency    = Currency::find($currency_id);

        if (!$currency) {
            $client_primary_currency = ClientCurrency::where('is_primary', true)->get(['currency_id'])->first()->currency_id;
            $currency                = Currency::find($client_primary_currency);
        }

        $session_metadata = [
            "interaction" => [
                "operation" => "AUTHORIZE",
                "merchant" => [
                    "name" => 'TEST' . $this->credentials->mastercard_merchant_id,
                ]
            ],
            "order" => [
                "id" => $reference_id,
                "amount" => $payment_info->amount,
                "currency" => $currency->iso_code,
                "description" => "Recharge Onebasket wallet",
            ],
            'customer' => $customer,
        ];

        switch ($payment_info->payment_from) {
            case 'wallet':
                $sessionResponse = $this->client->initiateHostedCheckout($session_metadata);
                if (!$sessionResponse) return response()->json($this->client->error(), 500);

                return response()->json($sessionResponse);
            default:
                break;
        }
    }


    public function orderNumber($request)
    {
        try {
            $time = isset($request->transaction_id) ? $request->transaction_id : time();
            $user_id = auth()->id();
            $amount  = $request->amount ? $request->amount : $request->amt;
            if (isset($request->action)) {
                $request->request->add(['payment_from' => $request->action, 'come_from' => 'app']);
            }
            if ($request->payment_from == 'cart') {
                $time = $request->order_number;
                Payment::create([
                    'amount' => $amount,
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
                $time = $request->subscription_id ? $request->subscription_id : time();
                $payment = Payment::create([
                    'amount' => 0,
                    'transaction_id' => $request->subscription_id . '_' . $time,
                    'balance_transaction' => round($amount, 2),
                    'type' => 'subscription',
                    'date' => date('Y-m-d'),
                    'user_id' => $user_id,
                    'payment_from' => $request->come_from ?? 'web',
                ]);
            } elseif ($request->payment_from == 'tip') {
                $time = time();
                $res =  Payment::create([
                    'amount' => 0,
                    'transaction_id' =>  $time,
                    'balance_transaction' => $request->amount,
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
}
