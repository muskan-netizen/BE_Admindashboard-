<?php

namespace App\Http\Controllers\Front;

use DB;
use Auth;
use Session;
use Redirect;
use Carbon\Carbon;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Front\{FrontController, OrderController, WalletController};
use App\Models\Client as CP;
use App\Models\{PaymentOption, Client, ClientPreference, Order, OrderProduct, EmailTemplate, Cart, CartAddon, OrderProductPrescription, CartProduct, User, Product, OrderProductAddon, Payment, ClientCurrency, OrderVendor, UserAddress, Vendor, CartCoupon, CartProductPrescription, LoyaltyCard, NotificationTemplate, VendorOrderStatus,OrderTax, SubscriptionInvoicesUser, UserDevice, UserVendor};

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

    public function payfastPurchase(Request $request, $domain = ''){
        try{
            $user = Auth::user();
            $amount = $this->getDollarCompareAmount($request->amount);
            $returnUrlParams = '?amount='.$amount;
            $address_id = 0;
            $tip = 0;
            if($request->has('tip')){
                $tip = $request->tip;
                $returnUrlParams = $returnUrlParams.'&tip='.$tip;
            }
            if( ($request->has('address_id')) && ($request->address_id > 0) ){
                $address_id = $request->address_id;
                $returnUrlParams = $returnUrlParams.'&address_id='.$address_id;
            }
            $returnUrlParams = $returnUrlParams.'&gateway=payfast';

            $returnUrl = route('order.return.success');
            if($request->payment_form == 'wallet'){
                $returnUrl = route('user.wallet');
            }

            $request_arr = array(
                'merchant_id' => $this->gateway->getMerchantId(),
                'merchant_key' => $this->gateway->getMerchantKey(),
                'return_url' => $returnUrl,
                'cancel_url' => url($request->cancelUrl),
                'notify_url' => url("payment/payfast/notify"),
                'amount' => $amount,
                'item_name' => 'test item',
                'custom_int1' => $user->id, // user id
                'custom_int2' => $address_id, // address id
                'custom_int3' => 6, //payment option id
                'custom_str1' => $tip, // tip amount
                'custom_str2' => $request->payment_form,
                'currency' => 'ZAR',
                'description' => 'This is a test purchase transaction',
                // 'metadata' => ['user_id' => $user->id],
            );

            $response = $this->gateway->purchase($request_arr)->send();
            unset($request_arr['description']);
            $passphrase = $this->gateway->getPassphrase();
            $signature = $this->generateSignature($request_arr, $passphrase);
            $request_arr['signature'] = $signature;

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

    public function payfastNotify(Request $request, $domain = '')
    {
        // Notify PayFast that information has been received
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
                return $this->successResponse($response->data, 'Payment completed successfully.', 200);
            }else{
                return $this->errorResponse($response->message, 400);
            }
        break;
        case 'FAILED':
            // There was an error, update your application
            return $this->errorResponse('Payment failed', 400);
        break;
        default:
            // If unknown status, do nothing (safest course of action)
            // return $this->errorResponse($response->getMessage(), 400);
        break;
        }
    }

}
