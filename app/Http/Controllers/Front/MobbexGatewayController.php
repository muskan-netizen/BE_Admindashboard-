<?php

namespace App\Http\Controllers\Front;

use Auth;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Front\FrontController;
use App\Models\{PaymentOption, Client, ClientPreference, ClientCurrency};

class MobbexGatewayController extends FrontController
{
    use ApiResponser;
    public $API_KEY;
    public $API_ACCESS_TOKEN;

    public function __construct()
    {
        // $paystack_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'paystack')->where('status', 1)->first();
        // $creds_arr = json_decode($paystack_creds->credentials);
        // $secret_key = (isset($creds_arr->secret_key)) ? $creds_arr->secret_key : '';
        // $public_key = (isset($creds_arr->public_key)) ? $creds_arr->public_key : '';
        // $testmode = (isset($paystack_creds->test_mode) && ($paystack_creds->test_mode == '1')) ? true : false;
        // $this->gateway = Omnipay::create('Paystack');
        // $this->gateway->setSecretKey($secret_key);
        // $this->gateway->setPublicKey($public_key);
        // $this->gateway->setTestMode($testmode); //set it to 'false' when go live

        $this->API_KEY = '9u2ZVG2Jyj3WHdboDGWrM5IJRmk1kZt8eVcDWMf0';
        $this->API_ACCESS_TOKEN ='a1eee705-86be-45d9-8280-864914a1f63e';

        try {
            $mb = new MB($this->API_KEY, $this->API_ACCESS_TOKEN);
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), $ex->getCode());
        }

        // dd($this->gateway);
    }

    public function mobbexPurchase(Request $request){
        try{
            $user = Auth::user();
            $amount = $this->getDollarCompareAmount($request->amount);
            $returnUrlParams = '?amount='.$amount;
            if($request->has('tip')){
                $tip = $request->tip;
                $returnUrlParams = $returnUrlParams.'&tip='.$tip;
            }
            if( ($request->has('address_id')) && ($request->address_id > 0) ){
                $address_id = $request->address_id;
                $returnUrlParams = $returnUrlParams.'&address_id='.$address_id;
            }
            $returnUrlParams = $returnUrlParams.'&gateway=mobbex';

            $returnUrl = route('order.return.success');
            if($request->payment_form == 'wallet'){
                $returnUrl = route('user.wallet');
            }

            $response = $this->gateway->purchase([
                'total' => $amount,
                'currency' => 'ARS',
                'email' => $user->email,
                'returnUrl' => url($request->returnUrl . $returnUrlParams),
                'cancelUrl' => url($request->cancelUrl),
                'metadata' => ['user_id' => $user->id],
                'description' => 'This is a test purchase transaction.',
            ])->send();
            if ($response->isSuccessful()) {
                return $this->successResponse($response->getData());
            }
            elseif ($response->isRedirect()) {
                $this->failMail();
                return $this->successResponse($response->getRedirectUrl());
            }
            else {
                $this->failMail();
                return $this->errorResponse($response->getMessage(), 400);
            }
        }
        catch(\Exception $ex){
            $this->failMail();
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    public function mobbexNotify(Request $request, $domain = '')
    {
        // Notify Mobbex that information has been received
        header( 'HTTP/1.0 200 OK' );
        flush();

        // Posted variables from ITN
        $pfData = $request;
        $pfData->payment_status = 'COMPLETE';
        //update db
        switch( $pfData->payment_status )
        {
        case 'COMPLETE':
            // If complete, update your application, email the buyer and process the transaction as paid
            $pfData->request->add([
                'user_id' => $pfData->custom_int1,
                'payment_option_id' => $pfData->custom_int3,
                'transaction_id' => $pfData->pf_payment_id
            ]);
            if($pfData->custom_str2 == 'cart'){
                $pfData->request->add([
                    'address_id' => $pfData->custom_int2,
                    'tip' => $pfData->custom_str1,
                ]);
                $order = new OrderController();
                $placeOrder = $order->placeOrder($pfData);
                $response = $placeOrder->getData();
            }
            elseif($pfData->custom_str2 == 'wallet'){
                $pfData->request->add([
                    'wallet_amount' => $pfData->amount_gross
                ]);
                $wallet = new WalletController();
                $creditWallet = $wallet->creditWallet($pfData);
                $response = $creditWallet->getData();
            }

            if($response->status == 'Success'){
                $this->successMail();
                return $this->successResponse($response->data, 'Payment completed successfully.', 200);
            }else{
                $this->failMail();
                return $this->errorResponse($response->message, 400);
            }
        break;
        case 'FAILED':
            $this->failMail();
            // There was an error, update your application
            return $this->errorResponse('Payment failed', 400);
        break;
        default:
        $this->failMail();
            // If unknown status, do nothing (safest course of action)
            // return $this->errorResponse($response->getMessage(), 400);
        break;
        }
    }
}
