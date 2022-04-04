<?php

namespace App\Http\Controllers\Front;
use Log;
use Auth;
use App\Helpers\Easebuzz;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Redirect;
use App\Models\{PaymentOption, Order, Cart, CartAddon, CartProduct, User,  Payment,  CartCoupon, CartProductPrescription, UserVendor, Transaction};

use App\Http\Controllers\Front\{FrontController, OrderController, WalletController, UserSubscriptionController};

class EasebuzzController  extends FrontController
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

    function easebuzz_gateway (){
        return view('frontend.payment_gatway.easebuzz');
    }

    function order (Request $request){
     //  pr($request->all());
       
        $user = Auth::user();
        // pr($request->all());
        $amount =  $this->getDollarCompareAmount($request->amount);
        $amount = number_format($amount,2);
        
        $customerName = $user->name;
        $customerPhone =  $user->phone_number ;
        $customerEmail = $user->email;
        $now = new \DateTime();
        $created_at = $now->format('Y-m-d H:i:s');
        $orderId = $request->order_number;
        $cart_id  = '';
        //udf1 for payment_form
        //udf2 for user id 
        $payment_form = $request->payment_form ?? 'cart';
        if($payment_form = 'cart'){
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
        // pr($response);
        if($response->status == 1){
            return $this->successResponse($response, 'Order has been created successfully');
        }else{
            return $this->errorResponse($response->data, 400);
        }
       
    }

    function easebuzz_respont(Request $request){
        //login user with user id 
        $user_id = $request->udf2;
        Auth::loginUsingId( $user_id);

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
            if($status == 'success'){
                if($request->udf1 == 'cart'){
                    
                    $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
                   
                    if ($order) {
                        $returnUrlParams = '';
                        $returnUrl = route('order.success', $order->id);
                        return Redirect::to(url($returnUrl . $returnUrlParams))->with('success', 'Transaction has been completed successfully');
    
                        // Send Email
                        //  $this->successMail();
                    }
                } elseif($request->udf1 == 'wallet'){
                    $returnUrl = route('user.wallet');
                    return Redirect::to(url($returnUrl))->with('success', 'Transaction has been completed successfully');
                }
                elseif($request->udf1 == 'tip'){
                    $returnUrl = route('user.orders');
                    return Redirect::to(url($returnUrl))->with('success', 'Transaction has been completed successfully');
                }
                elseif($request->udf1 == 'subscription'){
                    $returnUrl = route('user.subscription.plans');
                    return Redirect::to(url($returnUrl))->with('success', 'Transaction has been completed successfully');
                }
            }
            else{
             
                Log::info(json_encode($data));
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
                                return Redirect::to(route('showCart'))->with('error', 'Your order has already been cancelled');
                            }
                        }
                    }
                    
                    return Redirect::to(route('showCart'))->with('error', 'Your order has been cancelled');
                } elseif($request->udf1 == 'wallet'){
                    return Redirect::to(route('user.wallet'))->with('error', 'Transaction has been cancelled');
                } elseif($request->udf1 == 'tip'){
                    return Redirect::to(route('user.orders'))->with('error', 'Transaction has been cancelled');
                } elseif($request->udf1 == 'subscription'){
                    return Redirect::to(route('user.subscription.plans'))->with('error', 'Transaction has been cancelled');
                }
            }
            // if ($status == 'success'){
            //   //  Order::where('id', $orderId)->update(['status_id' => 1]);
            //     \Session::flash('successMessage', 'Successful..!');
            //     return redirect()->route('easebuzz-gateway');
            // }else{
            //     \Session::flash('errorMessage',  $data->error_Message);
            //     return redirect()->route('easebuzz-gateway');
            // }
        }
    }

    public function easybuzzNotify(Request $request, $domain = '')
    {
      
        // Log::info($request->getContent());
        // Log::info('json:=');
        // Log::info(json_encode($request->getContent()));
       
        try{
            $easebuzzObj = new Easebuzz($MERCHANT_KEY = null, $this->SALT, $ENV = null);
            $result = $easebuzzObj->easebuzzResponse($request->all());
            $response = json_decode($result);
            Log::info('json:=');
            Log::info( $response);
            $status = $response->status;
            if ($status == 1){  
                // udf1 for payment_form
                // udf2 for user id 
    
                // pr($request->all());
                
                $data = $response->data;
                $order_number = $data->txnid;
                Log::info('order_number:=');
                Log::info(  $order_number);

                $payment_form = $request->udf1;
                $cart_id = $data->udf3;
                $status = $data->status;
                $amount =  $data->amount ;
                $user_id = $data->udf2 ;
                $transactionId = $data->easepayid ;
                $subscription_id = $data->udf4;
                if($status == 'success'){
                    if($payment_form == 'cart'){
                        $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
                        if ($order) {
                            $order->payment_status = 1;
                            $order->save();
                            $payment_exists = Payment::where('transaction_id', $transactionId)->first();
                            if (!$payment_exists) {
                                $payment = new Payment();
                                $payment->date = date('Y-m-d');
                                $payment->order_id = $order->id;
                                $payment->transaction_id = $transactionId;
                                $payment->balance_transaction = $amount;
                                $payment->type = 'cart';
                                $payment->save();
        
                                // Auto accept order
                                $orderController = new OrderController();
                                $orderController->autoAcceptOrderIfOn($order->id);
        
                                // Remove cart
                                Cart::where('id', $cart_id)->update(['schedule_type' => null, 'scheduled_date_time' => null]);
                                CartAddon::where('cart_id', $cart_id)->delete();
                                CartCoupon::where('cart_id', $cart_id)->delete();
                                CartProduct::where('cart_id', $cart_id)->delete();
                                CartProductPrescription::where('cart_id', $cart_id)->delete();
        
                                // Send Notification
                                if (!empty($order->vendors)) {
                                    foreach ($order->vendors as $vendor_value) {
                                        $vendor_order_detail = $orderController->minimize_orderDetails_for_notification($order->id, $vendor_value->vendor_id);
                                        $user_vendors = UserVendor::where(['vendor_id' => $vendor_value->vendor_id])->pluck('user_id');
                                        $orderController->sendOrderPushNotificationVendors($user_vendors, $vendor_order_detail);
                                    }
                                }
                                $vendor_order_detail = $orderController->minimize_orderDetails_for_notification($order->id);
                                $super_admin = User::where('is_superadmin', 1)->pluck('id');
                                $orderController->sendOrderPushNotificationVendors($super_admin, $vendor_order_detail);
                            }
        
                            // Send Email
                            //   $this->successMail();
                        }
                    } elseif($payment_form == 'wallet'){
                        $request->request->add(['user_id' => $user_id, 'wallet_amount' => $amount, 'transaction_id' => $transactionId]);
                        $walletController = new WalletController();
                        $walletController->creditWallet($request);
                    }
                    elseif($payment_form == 'tip'){
                        $request->request->add(['user_id' => $user_id, 'order_number' => $order_number, 'tip_amount' => $amount, 'transaction_id' => $transactionId]);
                        $orderController = new OrderController();
                        $orderController->tipAfterOrder($request);
                    }
                    elseif($payment_form == 'subscription'){
                        $request->request->add(['user_id' => $user_id, 'payment_option_id' => 24, 'amount' => $amount, 'transaction_id' => $transactionId]);
                        $subscriptionController = new UserSubscriptionController();
                        $subscriptionController->purchaseSubscriptionPlan($request, '', $subscription_id);
                    }
                }
                else{
                    $user = User::find($user_id);
                    Log::info(json_encode($data));
                    if($payment_form == 'cart'){
                        $order = Order::where('order_number', $order_number)->first();
                        if($order){
                            $wallet_amount_used = $order->wallet_amount_used;
                            if($wallet_amount_used > 0){
                                $transaction = Transaction::where('type', 'deposit')->where('meta', 'LIKE', '%'.$order->order_number.'%')->first();
                                if(!$transaction){
                                    $wallet = $user->wallet;
                                    $wallet->depositFloat($wallet_amount_used, ['Wallet has been <b>refunded</b> for cancellation of order <b>'. $order->order_number. '</b>']);
                                }
                            }
                        }
                    } 
                }
            
            }
           
        }
        catch(Exception $ex){
            \Log::info($ex->getMessage());
        }
        http_response_code(200);
    }
}
