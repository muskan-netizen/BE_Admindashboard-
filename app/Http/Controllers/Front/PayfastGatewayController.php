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

class PayfastGatewayController extends FrontController
{
    use ApiResponser;
    public $gateway;

    public function __construct()
    {
        $payfast_creds = PaymentOption::select('credentials')->where('code', 'payfast')->where('status', 1)->first();
        $creds_arr = json_decode($payfast_creds->credentials);
        $merchant_id = (isset($creds_arr->merchant_id)) ? $creds_arr->merchant_id : '';
        $merchant_key = (isset($creds_arr->merchant_key)) ? $creds_arr->merchant_key : '';
        $passphrase = (isset($creds_arr->passphrase)) ? $creds_arr->passphrase : '';
        $this->gateway = Omnipay::create('PayFast');
        $this->gateway->setMerchantId($merchant_id);
        $this->gateway->setMerchantKey($merchant_key);
        $this->gateway->setPassphrase($passphrase);
        $this->gateway->setTestMode(true); //set it to 'false' when go live
        // dd($this->gateway);
    }

    function generateSignature($data, $passPhrase = null) {
        // Create parameter string
        $pfOutput = '';
        foreach( $data as $key => $val ) {
            if($val !== '') {
                $pfOutput .= $key .'='. urlencode( trim( $val ) ) .'&';
            }
        }
        // Remove last ampersand
        $getString = substr( $pfOutput, 0, -1 );
        if( ($passPhrase !== null) || ($passPhrase !== '') ) {
            $getString .= '&passphrase='. urlencode( trim( $passPhrase ) );
        }
        // return $getString;
        return md5( $getString );
    }

    public function payfastPurchase(Request $request){
        try{
            $user = Auth::user();
            $amount = $this->getDollarCompareAmount($request->amount);
            $returnUrlParams = '?amount='.$amount;
            if($request->has('tip')){
                $returnUrlParams = $returnUrlParams.'&tip='.$request->tip.'&gateway=payfast';
            }

            $request_arr = array(
                'merchant_id' => $this->gateway->getMerchantId(),
                'merchant_key' => $this->gateway->getMerchantKey(),
                'return_url' => url($request->returnUrl . $returnUrlParams),
                'cancel_url' => url($request->cancelUrl),
                // 'notify_url' => url($request->returnUrl . $returnUrlParams),
                'name_first' => $user->name,
                'name_last' => $user->name,
                'email_address' => 'preetinder.pal@codebrewinnovations.com',
                'amount' => $amount,
                'item_name' => 'test item',
                // 'metadata' => ['user_id' => $user->id],
                'currency' => 'ZAR',
                'description' => 'This is a test purchase transaction'
            );
            $response = $this->gateway->purchase($request_arr)->send();
            unset($request_arr['description']);
            // $signature = md5(http_build_query($request_arr));
            $passphrase = $this->gateway->getPassphrase();
            $signature = $this->generateSignature($request_arr, $passphrase);
            // dd($signature);
            $request_arr['signature'] = $signature;

            // $response = $this->gateway->purchase($request_arr)->send();
            if ($response->isSuccessful()) {
                return $this->successResponse($response->getData());
            }
            elseif ($response->isRedirect()) {
                $data['formData'] = $request_arr;
                $data['redirectUrl'] = $response->getRedirectUrl();
                return $this->successResponse($data);
            }
            else {
                return $this->errorResponse($response->getMessage(), 400);
            }
        }
        catch(\Exception $ex){
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    public function paystackCompletePurchase(Request $request)
    {
        // Once the transaction has been approved, we need to complete it.
        if($request->has(['reference'])){
            $amount = $this->getDollarCompareAmount($request->amount);
            $transaction = $this->gateway->completePurchase(array(
                'amount'                => $amount,
                'transactionReference'  => $request->reference
            ));
            $response = $transaction->send();
            if ($response->isSuccessful()){
                return $this->successResponse($response->getTransactionReference());
            } else {
                return $this->errorResponse($response->getMessage(), 400);
            }
        } else {
            return $this->errorResponse('Transaction has been declined', 400);
        }
    }
}
