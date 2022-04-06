<?php

namespace App\Http\Controllers\Api\v1;
use Log;
use Auth;
use App\Helpers\Easebuzz;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Redirect;
use App\Models\{PaymentOption,ClientCurrency,SubscriptionPlansUser, Order, Cart, CartAddon, CartProduct, User,  Payment,  CartCoupon, CartProductPrescription, UserVendor, Transaction};
use App\Http\Controllers\Api\v1\{BaseController, OrderController, WalletController, UserSubscriptionController};

class EasebuzzController  extends BaseController
{
    use ApiResponser;

    private $MERCHANT_KEY;
    private $SALT;
   

    public function __construct() {
        $payOpt = PaymentOption::select('credentials', 'test_mode', 'status')->where('code', 'easebuzz')->where('status', 1)->first();
        $json = json_decode($payOpt->credentials);
        $this->MERCHANT_KEY =  $json->easebuzz_merchant_key;
        $this->SALT =  $json->easebuzz_salt;
        $this->ENV = ($payOpt->test_mode == 1) ?  "test" : 'prod' ; 
    }

    public function order (Request $request){
        $primaryCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
        if($primaryCurrency->currency->iso_code != 'INR' ) {
            $error =  __(' Currency format error!');
            return $this->errorResponse($error, 400);
        }
        $user = Auth::user();
        // pr($request->all());
        $amount =  $this->getDollarCompareAmount($request->amount);
        $amount = number_format($amount,2);
        
        $customerName = $user->name;
        $customerPhone =  $user->phone_number ;
        $customerEmail = $user->email;
        $now = new \DateTime();
        $created_at = $now->format('Y-m-d H:i:s');
        $orderId = $request->order_number ?? generateOrderNo();
        $cart_id  = '';
        //udf1 for payment_form
        //udf2 for user id 
        
        $payment_form = $request->action ?? 'cart';
        
        $returnUrlParams = '?order_id={order_id}&order_token={order_token}&gateway=easebuzz&amount=' . $request->amount . '&payment_form=' . $payment_form;
        
        if($payment_form == 'cart'){
            $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
            $cart_id =   $cart->id;
            $returnUrlParams = $returnUrlParams . '&cart_id=' .$cart->id; //. '&order_id={order_id}' .$reference_number. '&order_token=' .$reference_number;
          
        }
        elseif($payment_form == 'subscription'){
            $description = 'Subscription Checkout';
            if($request->has('subscription_id')){
                $slug = $request->subscription_id;
                $subscription_plan = SubscriptionPlansUser::with('features.feature')->where('slug', $slug)->where('status', '1')->first();
                $customer_data['subscription_id'] = $subscription_plan->id;
                // $reference_number = $request->subscription_id;
                $returnUrlParams = $returnUrlParams . '&subscription=' . $request->subscription_id;
                $cart_id = $request->subscription_id;
            }
        }
        $postData = array (
            "txnid" => $orderId,
            "amount" =>  $amount,
            "firstname" => $customerName,
            "email" => $customerEmail,
            "phone" => $customerPhone,
            "productinfo" => "test", 
            "surl" =>  url($request->serverUrl.'payment/easebuzz/api' . $returnUrlParams),
            "furl" =>  url($request->serverUrl.'payment/easebuzz/api' . $returnUrlParams),
            "udf1" => $payment_form,
            "udf2" => $user->id,
            "udf3" => $cart_id, 
             //subscription_id
            "udf4" => "aaaa", 
            "udf5" =>  'aaa',
            "address1" =>  $user->address->first()->address,
            "address2" =>  $user->address->first()->address,
            "city" => $user->address->first()->city,
            "state" =>$user->address->first()->state,
            "country" => "India",
            "zipcode" => $user->address->first()->pincode,
        );
       
        $easebuzzObj = new Easebuzz($this->MERCHANT_KEY, $this->SALT, $this->ENV);
        $response = $easebuzzObj->initiatePaymentAPI($postData);
        // echo "order";
        //pr($response );
        if($response->status == 1){
           // $res['payment_url']=$response->data;
            // pr($res);
            return $this->successResponse($response->data, 'Payment Url has been created successfully');
        }else{
            return $this->errorResponse($response->data, 400);
        }
       
    }
 
}
