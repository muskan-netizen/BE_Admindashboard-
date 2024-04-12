<?php

namespace App\Http\Traits;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Error;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

trait HitpayTrait
{

    /**
     * createPaymentRequest create a request for initiate the payment
     *
     * @param  mixed $hitpay_client
     * @param  mixed $body
     * @param  mixed $url
     * @param  mixed $api_key
     * @return mixed response from the request
     */
    public function createPaymentRequest($hitpay_client, $body, $url, $api_key)
    {
        try {
            $validator = Validator::make($body, [

                'phone' => 'string',
                'redirect_url' => 'string|required',
                'reference_number' => 'required',
                'webhook' => 'string',
                'currency' => 'string|required',
                'amount' => 'required'
            ]);

            // $validator->validate();
            // if ($validator->errors()->hasAny()) {

            //     return $validator->errors();
            // }
            $header = [
                'headers' => [
                    'X-BUSINESS-API-KEY' => $api_key,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'X-Requested-With' => 'XMLHttpRequest'
                ]
            ];
            // merged array of body and header
            $data = ['form_params' => $body] + $header;
            //makeing  the request for checkout
            $response = $hitpay_client->request('POST', $url, $data);
            $responeBody = (array) json_decode($response->getBody());
            return $responeBody;
        } catch (Error $e) {
            return $e->getMessage();
        }
    }

    // /**
    //  * generateSignatureArray genrate the hmac for Handling the webhook
    //  *
    //  * @param  mixed $secret salt key from admin os hitpay
    //  * @param  array $args  array of the receviced in response expect hmac
    //  * @return string return the hash_hmac 'sha256'
    //  */
    // public static function generateSignatureArray($secret, array $args)
    // {
    //     $hmacSource = [];
    //     foreach ($args as $key => $val) {
    //         $hmacSource[$key] = "{$key}{$val}";
    //     }
    //     ksort($hmacSource);
    //     $sig = implode("", array_values($hmacSource));
    //     $calculatedHmac = hash_hmac('sha256', $sig, $secret);

    //     return $calculatedHmac;
    // }



    // /**
    //  * isPaymentSuccess function is for matching the hmac
    //  *
    //  * @param  mixed $hmac value of hmac from response
    //  * @param  mixed $paymenthash genrated hash from the receviced parameter
    //  * @return boolean true if match otherwise false
    //  */
    // public function isPaymentSuccess(string $hmac, string $paymenthash)
    // {

    //         return hash_equals($hmac, $paymenthash) ?  true : false;



    // }

    /**
     * getSuccessUrl get the success url
     *
     * @param  string $payment_form type of payment form cart, wallet etc
     * @param  mixed  $transactionId current order id
     * @return string  redirect url
     */
    public function getSuccessUrl($transactionId)
    {
        $payment = Payment::where('transaction_id', $transactionId)->first();
        if ($payment->type == 'cart') {

            $order = Order::where('order_number', $transactionId)->first();
            if ($order) {
                // route('order.return.success');
                if ($payment->payment_from == 'web') {
                    $redirectUrl  = route('order.success', $order->id);
                } else {
                    $redirectUrl  = route('payment.gateway.return.response') . '/?gateway=totalpay' . '&status=200&order=' . $order->id;
                }

                // Send Email
                //   $this->successMail();
            }
        } elseif ($payment->type == 'wallet') {
            if ($payment->payment_from == 'app') {
                $user = User::findOrFail($payment->user_id);
                Auth::login($user);
                $redirectUrl = route('payment.gateway.return.response') . '/?gateway=totalpay' . '&status=200&transaction_id=' . $payment->transaction_id . '&action=wallet';
            } else {
                $user = auth()->user();
                $redirectUrl = route('user.wallet');
            }
        } elseif ($payment->type == 'tip') {
            if ($payment->payment_from == 'web') {
                $redirectUrl = route('user.orders');
            } else {
                $redirectUrl = route('payment.gateway.return.response') . '/?gateway=totalpay' . '&status=200&order=' . $payment->transaction_id . '&action=tip';
            }
        } elseif ($payment->type == 'subscription') {
            if ($payment->payment_from == 'web') {
                $redirectUrl = route('user.subscription.plans');
            } else {
                $redirectUrl = route('payment.gateway.return.response') . '/?gateway=totalpay' . '&status=200&transaction_id=' . $payment->transaction_id . '&action=subscription';
            }
        } else if ($payment->type == 'pickup_delivery') {
            if ($payment->payment_from == 'web') {
                $redirectUrl   = route('front.booking.details', $payment->transaction_id);
            } else {
                $redirectUrl = route('payment.gateway.return.response') . '/?gateway=totalpay' . '&status=200&order=' . $payment->transaction_id;
            }
        }
        return $redirectUrl;
    }
}
