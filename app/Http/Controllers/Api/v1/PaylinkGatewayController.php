<?php

namespace App\Http\Controllers\Api\v1;

use Log;
use Auth;
//use WebhookCall;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Controllers\Api\v1\OrderController;
use App\Models\{User, UserVendor, Cart, CartAddon, CartCoupon, CartProduct, CartProductPrescription, Payment, PaymentOption, Client, ClientPreference, ClientCurrency, Order, OrderProduct, OrderProductAddon, OrderProductPrescription, VendorOrderStatus, OrderVendor, OrderTax, SubscriptionPlansUser};
use Illuminate\Support\Facades\Auth as FacadesAuth;

class PaylinkGatewayController extends BaseController
{
    use ApiResponser;
    public $API_KEY;
    public $API_SECRET_KEY;
    public $test_mode;
    public $currency;

    public function __construct()
    {
        $paylink_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'paylink')->where('status', 1)->first();
        $creds_arr = json_decode($paylink_creds->credentials);
        $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
        $api_secret_key = (isset($creds_arr->api_secret_key)) ? $creds_arr->api_secret_key : '';
        $this->test_mode = (isset($paylink_creds->test_mode) && ($paylink_creds->test_mode == '1')) ? true : false;
        $this->API_KEY = $api_key;
        $this->API_SECRET_KEY = $api_secret_key;

        $primaryCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
        $this->currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'USD';
    }

    public function paylinkPurchase(Request $request)
    {
        try {
            $user = Auth::user();
            $amount = $this->getDollarCompareAmount($request->amount);

            $request->request->add(['payment_form' => $request->action]);
            $uniqid = uniqid();
            $customer_data = array(
                'firstName' => $user->name,
                'lastName' => '-',
                'email' => $user->email,
                'phone' => $user->phone_number
                // 'identification' => '12123123'
            );
            $reference_number = $description = '';
            $returnUrlParams = '?gateway=paylink&amount=' . $request->amount . '&payment_form=' . $request->payment_form . '&auth_token=' . $user->auth_token;

            if($request->payment_form == 'cart'){
                $description = 'Order Checkout';
                $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
                $request->request->add(['cart_id' => $cart->id]);
                $customer_data['cart_id'] = $cart->id;
                $reference_number = $request->order_number;
                $returnUrlParams = $returnUrlParams . '&cart_id=' . $cart->id . '&order=' . $request->order_number;
            }
            elseif($request->payment_form == 'wallet'){
                $description = 'Wallet Checkout';
                $reference_number = $user->id;
            }
            if($request->payment_form == 'tip'){
                $description = 'Tip Checkout';
                $customer_data['order_number'] = $request->order_number;
                if($request->has('order_number')){
                    $reference_number = $request->order_number;
                }
                $returnUrlParams = $returnUrlParams . '&order=' . $request->order_number;
            }
            elseif($request->payment_form == 'subscription'){
                $description = 'Subscription Checkout';
                if($request->has('subscription_id')){
                    $slug = $request->subscription_id;
                    $subscription_plan = SubscriptionPlansUser::with('features.feature')->where('slug', $slug)->where('status', '1')->first();
                    $customer_data['subscription_id'] = $subscription_plan->id;
                    $reference_number = $request->subscription_id;
                }
            }

            $data = array(
                'requestId' => 'CHK-' . $uniqid,
                'orderId' => $reference_number,
                'amount' => $amount,
                'currency' => 'AED', //$this->currency
                'description' => $description,
                'reference' => $reference_number,
                'returnUrl' => url($request->serverUrl.'payment/paylink/return/app' . $returnUrlParams),
                'redirect' => false,
                'test' => $this->test_mode, // True, testing, false, production
                'customer' => $customer_data,
                'billingAddress' => array(
                    'name' => $user->address->first()->address,
                    'address1' => $user->address->first()->address,
                    'address2' => $user->address->first()->address,
                    'street' =>  $user->address->first()->street,
                    'city' => $user->address->first()->city,
                    'state' => $user->address->first()->state,
                    'zip' => $user->address->first()->pincode,
                    'country' => 'AED'
                ),
                'items' => array(
                    'name' => 'Demo item',
                    'sku' => 'sku-demo',
                    'unitprice' => $amount,
                    'quantity' => 1,
                    'linetotal' => 100
                )
            );

            $ch = curl_init($this->getCheckoutUrl() . '/web');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'X-PointCheckout-Api-Key:' . $this->API_KEY,
                    'X-PointCheckout-Api-Secret:' . $this->API_SECRET_KEY
                )
            );

            $result = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($result);
            if ($result->success == true) {
                return $this->successResponse($result->result->redirectUrl, ['status' => $result->result->status]);
            } else {
                return $this->errorResponse($result->error, 400);
            }
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    private function getCheckoutUrl(){
        if ($this->test_mode == true){
            return 'https://api.test.pointcheckout.com/mer/v2.0/checkout';
        }elseif($this->test_mode == false){
            return 'https://api.pointcheckout.com/mer/v2.0/checkout';
        }
        return 'https://api.staging.pointcheckout.com/mer/v2.0/checkout';
    }
}
