<?php

namespace App\Http\Controllers\Front;

use App\Models\Payment;
use App\Helpers\Mastercard\Mastercard;
use App\Helpers\Mastercard\Models\Authorization;
use App\Helpers\Mastercard\Models\Customer;
use App\Helpers\Mastercard\Models\Order;
use App\Helpers\Mastercard\Models\Purchase;
use App\Helpers\Mastercard\Models\Verify;
use App\Helpers\Mastercard\Operation;
use App\Http\Controllers\Api\v1\PickupDeliveryController;
use App\Http\Controllers\Api\v1\UserSubscriptionController;
use App\Http\Controllers\Controller;
use App\Http\Traits\OrderTrait;
use App\Models\CaregoryKycDoc;
use App\Models\Cart;
use App\Models\CartAddon;
use App\Models\CartCoupon;
use App\Models\CartProductPrescription;
use App\Models\ClientCurrency;
use App\Models\Currency;
use App\Models\Order as ModelsOrder;
use App\Models\PaymentOption;
use App\Models\User;
use App\Models\UserVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class MastercardPaymentController extends Controller
{
    use OrderTrait;

    private Mastercard $client;
    private object $credentials;
    private int $payopt_id;

    public function __construct()
    {
        $pay_option        = PaymentOption::where('code', 'mastercard')->where('status', 1)->get(['credentials', 'test_mode', 'status', 'id'])->firstOrFail();
        $this->credentials = json_decode($pay_option->credentials);

        $gateway = $pay_option->test_mode == 1
            ? 'test-gateway.mastercard.com'
            : $this->credentials->mastercard_gateway;

        $this->payopt_id = $pay_option->id;
        $this->client    = new Mastercard(
            (($pay_option->test_mode == 1) ? 'TEST' : '') . $this->credentials->mastercard_merchant_id,
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

        $customer = (new Customer($user->name))
            ->setEmail($user->email)
            ->setMobilePhone($user->phone_number);

        $reference_id = $this->orderNumber($request);

        $currency_id = Session::get('customerCurrency');
        $currency    = Currency::find($currency_id);

        if (!$currency) {
            $client_primary_currency = ClientCurrency::where('is_primary', true)->get(['currency_id'])->first()->currency_id;
            $currency                = Currency::find($client_primary_currency);
        }

        $order_model         = new Order($reference_id, $currency->iso_code, (int)$payment_info->amount);
        $authorization_model = (new Purchase($this->credentials->mastercard_merchant_id))
            ->setOrder($order_model)
            ->setCustomer($customer);

        $authorization_model
            ->getInteraction()
            ->setReturnUrl(route('payment.mastercard.return', ['order_id' => $reference_id]));

        switch ($payment_info->payment_from) {
            case 'wallet':
                $authorization_model->getOrder()->setDescription("Recharge your wallet");
            case 'cart':
                break;
            case 'subscription':
                $authorization_model
                    ->getInteraction()
                    ->setReturnUrl(route('payment.mastercard.return', [
                        'order_id' => $reference_id,
                        'subscription_id' => $request->subscription_id,
                    ]));
                break;

            default:
                return back()->withErrors(['generic' => 'unknown payment info']);
        }

        $sessionResponse = $this->client->request(Operation::INITIATE_CHECKOUT, $authorization_model);
        if (!$sessionResponse) return response()->json($this->client->error(), 500);

        $session_id = $sessionResponse->session->id;
        $success_indicator = $sessionResponse->successIndicator;

        Session::put('order-' . $reference_id, compact('session_id', 'success_indicator'));

        return response()->json($sessionResponse);
    }

    public function postPayment(Request $request, string $domain = '', string $order_id, ?string $subscription_id = null)
    {
        $session_data = Session::get('order-' . $order_id);
        Session::forget('order-' . $order_id);

        if (!$session_data) return redirect()->back();

        list(
            'session_id' => $session_id,
            'success_indicator' => $success_indicator
        ) = $session_data;

        if ($success_indicator != $request->resultIndicator) {
            return back();
        }

        $payment = Payment::where('transaction_id', $order_id)->first();
        $payment->viva_order_id = $order_id;
        $payment->payment_option_id = $this->payopt_id;

        $user = auth()->user();

        switch ($payment->type) {
            case 'wallet':
                $wallet = $user->wallet;
                $wallet->depositFloat(
                    $payment->balance_transaction,
                    [sprintf('Wallet has been credited <b>credited</b> for order number <b>%s</b>', $payment->transaction_id)]
                );
                return redirect()->route('user.wallet');

            case 'cart':
                $order = ModelsOrder::where('order_number', $order_id)->first();
                $order->payment_status = 1;
                $order->save();

                $this->orderSuccessCartDetail($order);

                return redirect()->route('order.success', $order->id);

            case 'subscription':
                $request = new Request([
                    'transaction_id' => $payment->transaction_id,
                    'payment_option_id' => $this->payopt_id,
                    'subsid' => $payment->transaction_id,
                    'subscription_id' => $payment->transaction_id,
                    'amount' => $payment->amount,
                ]);

                (new UserSubscriptionController)->purchaseSubscriptionPlan($request, $request->subscription_id);
                return redirect()->route('user.subscription.plans', $subscription_id);
            case 'pickup_delivery':
                $request = new Request([
                    'transaction_id' => $order_id,
                    'payment_option_id' => $this->payopt_id,
                    'amount' => $payment->amount,
                    'order_number' => $order_id,
                    'reload_route' => $payment->reload_route,
                ]);

                (new PickupDeliveryController)->orderUpdateAfterPaymentPickupDelivery($request);
                return redirect()->route('front.booking.details', $payment->transaction_id);
            case 'tip':
                $request = new Request([
                    'tip_amount' => $payment->amount,
                    'order_number' => $payment->order_number,
                    'transaction_id' => $order_id,
                ]);

                (new OrderController)->tipAfterOrder($request);

                return redirect()->route('user.orders');
            default:
                return back();
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
                $time = sprintf('%s_%d', $request->subscription_id, time());
                $payment = Payment::create([
                    'amount' => 0,
                    'transaction_id' => $time,
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
