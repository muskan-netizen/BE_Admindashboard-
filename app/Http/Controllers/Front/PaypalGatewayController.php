<?php

namespace App\Http\Controllers\Front;

use Auth;

use Config;
use Session;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;
use App\Models\{PaymentOption, Client, ClientPreference, ClientCurrency};
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Front\FrontController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use DB;

class PaypalGatewayController extends FrontController
{
    use ApiResponser;
    public $gateway;
    public $currency;

    public function __construct()
    {
        $paypal_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'paypal')->where('status', 1)->first();
        $creds_arr = json_decode($paypal_creds->credentials);
        $username = (isset($creds_arr->username)) ? $creds_arr->username : '';
        $password = (isset($creds_arr->password)) ? $creds_arr->password : '';
        $signature = (isset($creds_arr->signature)) ? $creds_arr->signature : '';
        $testmode = (isset($paypal_creds->test_mode) && ($paypal_creds->test_mode == '1')) ? true : false;
        $this->gateway = Omnipay::create('PayPal_Express');
        $this->gateway->setUsername($username);
        $this->gateway->setPassword($password);
        $this->gateway->setSignature($signature);
        $this->gateway->setTestMode($testmode); //set it to 'false' when go live

        $primaryCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
        $this->currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'USD';
    }

    public function paypalPurchase(Request $request)
    {
        \Log::info(['paypalPurchase' => $request->all()]);
        \Log::info(['paypalPurchase amount' => $request->amount]);
        try {
            $amount = $this->getDollarCompareAmount($request->amount);
            $returnUrlParams = '?amount=' . $amount;
            \Log::info(['return amount'=>$returnUrlParams]);
            if ($request->has('tip')) {
                $returnUrlParams = $returnUrlParams . '&tip=' . $request->tip;
            }
            if ($request->has('ordernumber')) {
                $returnUrlParams = $returnUrlParams . '&ordernumber=' . $request->ordernumber;
            }
            \Log::info(['return amount again'=>$returnUrlParams]);
            if ($request->has('reload_route')) {
                $pickupRoute = $request->reload_route;
                $response = $this->gateway->purchase([
                    'currency' => $this->currency, //'USD',
                    'amount' => $amount,
                    'cancelUrl' => url($request->cancelUrl),
                    'returnUrl' => $pickupRoute. $returnUrlParams,
                ])->send();
            }else{
                $response = $this->gateway->purchase([
                    'currency' => $this->currency, //'USD',
                    'amount' => $amount,
                    'cancelUrl' => url($request->cancelUrl),
                    'returnUrl' => url($request->returnUrl . $returnUrlParams),
                ])->send();
            }

            if ($response->isSuccessful()) {
                return $this->successResponse($response->getData());
            }
            elseif ($response->isRedirect()) {
                return $this->successResponse($response->getRedirectUrl());
            } else {
                $this->failMail();
                return $this->errorResponse($response->getMessage(), 400);
            }
        } catch (\Exception $ex) {
            $this->failMail();
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    public function paypalCompletePurchase(Request $request)
    {
        // Once the transaction has been approved, we need to complete it.
        \Log::info(['paypalCompletePurchase' => $request->all()]);
        if ($request->has(['token', 'PayerID'])) {
            $amount = $this->getDollarCompareAmount($request->amount);
            $returnUrlParams = '?amount=' . $amount;
            if ($request->has('tip')) {
                $returnUrlParams = $returnUrlParams . '&tip=' . $request->tip;
            }
            \Log::info(['amount'=>$amount]);
            \Log::info(['payerid' =>$request->PayerID]);
            \Log::info(['token' =>$request->token]);
            $transaction = $this->gateway->completePurchase(array(
                'amount'                => $amount,
                'payer_id'              => $request->PayerID,
                'transactionReference'  => $request->token,
                'currency' => $this->currency, //'USD',
            //     'cancelUrl' =>  url($request->cancelUrl),
            //     'returnUrl' => url($request->returnUrl . $returnUrlParams),
             ));
            $response = $transaction->send();
            // \Log::info(['tranaction response' =>  $response]);
            if ($response->isSuccessful()) {
                // $this->successMail();
                \Log::info('success');
                return $this->successResponse($response->getTransactionReference());
            } else {
                \Log::info('fail');
                $this->failMail();
                return $this->errorResponse($response->getMessage(), 400);
            }
        } else {
            $this->failMail();
            return $this->errorResponse('Transaction has been declined', 400);
        }
    }

    public function paymentTransactionSave(Request $request, $domain = ''){
        \Log::info('paymentTransactionSave credit here');
        if( (isset($request->user_id)) && (!empty($request->user_id)) ){
            $user = User::find($request->user_id);
        }elseif( (isset($request->auth_token)) && (!empty($request->auth_token)) ){
            $user = User::whereHas('device',function  ($qu) use ($request){
                $qu->where('access_token', $request->auth_token);
            })->first();

        }else{
            $user = Auth::user();
        }
        if($user){
            $credit_amount = $request->wallet_amount;
            $wallet = $user->wallet;
            if ($credit_amount > 0) {
                $saved_transaction = Transaction::where('meta', 'like', '%'.$request->transaction_id.'%')->first();
                if($saved_transaction){
                    return $this->errorResponse('Transaction has already been done', 400);
                }

                $wallet->depositFloat($credit_amount, [__("Wallet has been").' <b>Credited</b> by transaction reference <b>'.$request->transaction_id.'</b>']);
                $payment = Payment::where('transaction_id',$request->transaction_id)->first();
                if(!$payment){
                    $payment = new Payment();
                }
                $payment->date = date('Y-m-d');
                $payment->user_id = $user->id;
                $payment->transaction_id = $request->transaction_id;
                $payment->payment_option_id = $request->payment_option_id ?? null;
                $payment->balance_transaction = $credit_amount;
                $payment->type = 'wallet_topup';
                $payment->save();

                $transactions = Transaction::where('payable_id', $user->id)->get();
                $response['wallet_balance'] = $wallet->balanceFloat;
                $response['transactions'] = $transactions;
                $message = 'Wallet has been credited successfully';
                Session::put('success', $message);
                // \Log::info('success1');
                return $this->successResponse($response, $message, 200);
            }
            else{
                return $this->errorResponse('Amount is not sufficient', 400);
            }
        }
        else{
            return $this->errorResponse('Invalid User', 400);
        }
    }
}
