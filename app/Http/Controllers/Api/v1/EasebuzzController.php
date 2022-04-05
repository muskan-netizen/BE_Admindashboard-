<?php

namespace App\Http\Controllers\Api\v1;
use Log;
use Auth;
use App\Helpers\Easebuzz;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Redirect;
use App\Models\{PaymentOption,ClientCurrency, Order, Cart, CartAddon, CartProduct, User,  Payment,  CartCoupon, CartProductPrescription, UserVendor, Transaction};
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
       
        $payment_form = $request->payment_form ?? 'cart';
        if($payment_form == 'cart'){
            $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
            $cart_id =   $cart->id;
        }
        $postData = array (
            "txnid" => $orderId,
            "amount" =>  $amount,
            "firstname" => $customerName,
            "email" => $customerEmail,
            "phone" => $customerPhone,
            "productinfo" => "test", 
            "surl" => route('easebuzz_respont'),
            "furl" => route('easebuzz_respont'),
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
        
        if($response->status == 1){
            return $this->successResponse($response, 'Order has been created successfully');
        }else{
            return $this->errorResponse($response->data, 400);
        }
       
    }

    function easebuzz_respont(Request $request){
        //login user with user id 
        
        $easebuzzObj = new Easebuzz($MERCHANT_KEY = null, $this->SALT, $ENV = null);
        $result = $easebuzzObj->easebuzzResponse($request->all());
        $res = json_decode($result);
        $status = $res->status;
        if ($status == 1){  
            // udf1 for payment_form
            // udf2 for user id 

            // pr($request->all());
            
            $data = $res->data;
            $order_number = $data->txnid;
            $status = $data->status;
            $user_id = $data->udf2 ;
            if($status == 'success'){
                if($request->udf1 == 'cart'){
                    $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
                    if ($order) {
                        return $this->successResponse([], __('Transaction has been completed successfully'));
                    }
                } 
                return $this->successResponse([], __('Transaction has been completed successfully'));
            }
            else{
                $user = User::find($user_id);
                if($request->udf1 == 'cart'){
                    $order = Order::where('order_number', $request->order_id)->first();
                    if($order){
                        $wallet_amount_used = $order->wallet_amount_used;
                        if($wallet_amount_used > 0){
                            $transaction = Transaction::where('type', 'deposit')->where('meta', 'LIKE', '%'.$order->order_number.'%')->first();
                            if(!$transaction){
                                $wallet = $user->wallet;
                                $wallet->depositFloat($wallet_amount_used, ['Wallet has been <b>refunded</b> for cancellation of order <b>'. $order->order_number. '</b>']);
                            }else{
                                return $this->errorResponse(__('Your order has already been cancelled'), 400);
                            }
                        }
                    }
                    return $this->errorResponse(__('Your order has been cancelled'), 400);
                }
                return $this->errorResponse(__('Transaction has been cancelled'), 400);
            }
        }
    }  
}
