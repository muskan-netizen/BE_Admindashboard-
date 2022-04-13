<?php

namespace App\Http\Controllers\Front;
use Log;
use Auth;
use App\Helpers\Easebuzz;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Redirect;
use App\Models\{PaymentOption,ClientCurrency,CaregoryKycDoc, Order, Cart, CartAddon, CartProduct, User,  Payment,  CartCoupon, CartProductPrescription, UserVendor, Transaction};

use App\Http\Controllers\Front\{FrontController, OrderController, WalletController, UserSubscriptionController};

class VnpayController  extends FrontController
{
    use ApiResponser;

    private $MERCHANT_KEY;
    private $SALT;
    private $vnp_TmnCode;
    private $vnp_HashSecret;
    private $vnp_Url;
   
    private $vnp_apiUrl;
    private $startTime;
    private $expire;
    
    public function __construct() {
        // $payOpt = PaymentOption::select('credentials', 'test_mode', 'status')->where('code', 'easebuzz')->where('status', 1)->first();
        // $json = json_decode($payOpt->credentials);
        // $this->MERCHANT_KEY =  $json->easebuzz_merchant_key ?? null; 
        // $this->SALT =  $json->easebuzz_salt ?? null;
        // $this->ENV = ($payOpt->test_mode == 1) ?  "test" : 'prod' ; 
        $this->vnp_TmnCode = "COCOSIN"; //Website ID in VNPAY System
        $this->vnp_HashSecret = "RAOEXHYVSDDIIENYWSLDIIZTANXUXZFJ"; //Secret key
        $this->vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $this->vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
        //Config input format
        //Expire
        $this->startTime = date("YmdHis");
        $this->expire = date('YmdHis',strtotime('+15 minutes',strtotime( $this->startTime)));
    }

    function VnPay_gateway (){
        return view('frontend.payment_gatway.vnpay_view')->with([
            'expire'=>$this->expire,
            "startTime"=> $this->startTime,
        ]);
    }

    function order(Request $request){
        // pr($request->all());
        $primaryCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
        if($primaryCurrency->currency->iso_code != 'INR' ) {
            $error =  __(' Currency format error!');
            return $this->errorResponse($error, 400);
        }
        $user = Auth::user();
        // pr($request->all());
        $amount =  $this->getDollarCompareAmount($request->amount);
        $vnp_HashSecret =$this->vnp_HashSecret;
        $vnp_TmnCode = $this->vnp_TmnCode;
        $vnp_Returnurl =route('vnpay_respont');
        $vnp_TxnRef = $request->order_number ?? generateOrderNo();; // order number
        $vnp_OrderInfo = $request->order_desc ?? null ;
        $vnp_OrderType = $request->order_type ?? 'billpayment' ;
        $vnp_Amount =   $amount * 100;
        $vnp_Locale =  $request->language ?? null ;
        $vnp_BankCode = $request->bank_code ?? null  ;
        $vnp_IpAddr =  $request->REMOTE_ADDR ?? null ;
        //Add Params of 2.0.1 Version
        $vnp_ExpireDate =  $request->txtexpire ?? null ;
        //Billing
        $vnp_Bill_Mobile =  $request->txt_billing_mobile ?? null ;
        $vnp_Bill_Email =  $request->txt_billing_email ?? null ;
        $fullName = trim( $request->txt_billing_fullname ?? null);
        if (isset($fullName) && trim($fullName) != '') {
            $name = explode(' ', $fullName);
            $vnp_Bill_FirstName = array_shift($name);
            $vnp_Bill_LastName = array_pop($name);
        }
        $vnp_Bill_Address=  $request->txt_inv_addr1 ?? null ;
        $vnp_Bill_City= $request->txt_bill_city ?? null ;
        $vnp_Bill_Country= $request->txt_bill_country ?? null;
        $vnp_Bill_State= $request->txt_bill_state ?? null ;
        // Invoice
        $vnp_Inv_Phone= $request->txt_inv_mobile ?? null ;
        $vnp_Inv_Email= $request->txt_inv_email ?? null ;
        $vnp_Inv_Customer= $request->txt_inv_customer ?? null ;
        $vnp_Inv_Address= $request->txt_inv_addr1 ?? null ;
        $vnp_Inv_Company= $request->txt_inv_company ?? null ;
        $vnp_Inv_Taxcode=$request->txt_inv_taxcode ?? null ;
        $vnp_Inv_Type= $request->cbo_inv_type ?? null ;
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate"=>$vnp_ExpireDate,
            "vnp_Bill_Mobile"=>$vnp_Bill_Mobile,
            "vnp_Bill_Email"=>$vnp_Bill_Email,
            "vnp_Bill_FirstName"=>$vnp_Bill_FirstName,
            "vnp_Bill_LastName"=>$vnp_Bill_LastName,
            "vnp_Bill_Address"=>$vnp_Bill_Address,
            "vnp_Bill_City"=>$vnp_Bill_City,
            "vnp_Bill_Country"=>$vnp_Bill_Country,
            "vnp_Inv_Phone"=>$vnp_Inv_Phone,
            "vnp_Inv_Email"=>$vnp_Inv_Email,
            "vnp_Inv_Customer"=>$vnp_Inv_Customer,
            "vnp_Inv_Address"=>$vnp_Inv_Address,
            "vnp_Inv_Company"=>$vnp_Inv_Company,
            "vnp_Inv_Taxcode"=>$vnp_Inv_Taxcode,
            "vnp_Inv_Type"=>$vnp_Inv_Type
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $vnp_Url = $this->vnp_Url ;
        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        pr($vnp_Url);
        $amount =  $this->getDollarCompareAmount($request->amount);
    
        $amount =  number_format( (floor($amount *100)/100),2,'.','') ; //number_format($amount,2,'.','');
        
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
       // pr($postData);
        $easebuzzObj = new Easebuzz($this->MERCHANT_KEY, $this->SALT, $this->ENV);
        $response = $easebuzzObj->initiatePaymentAPI($postData);
        // echo "order";
        
        if($response->status == 1){
            return $this->successResponse($response, 'Order has been created successfully');
        }else{
            return $this->errorResponse($response->data, 400);
        }
       
    }

    function vnpay_respont(Request $request){
        pr($request->all());
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
            $cart_id = $data->udf3 ;
            if($status == 'success'){
                if($request->udf1 == 'cart'){
                    
                    $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
                   
                    if ($order) {
                        CaregoryKycDoc::where('cart_id',$cart_id)->update(['ordre_id'=> $order->id,'cart_id'=>'' ]);
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

    function easebuzz_respontAPP(Request $request){
        //login user with user id 
      
        $easebuzzObj = new Easebuzz($MERCHANT_KEY = null, $this->SALT, $ENV = null);
        $result = $easebuzzObj->easebuzzResponse($request->all());
        $res = json_decode($result);
        $status = $res->status;
        if ($status == 1){  
            $returnUrl = url('payment/gateway/returnResponse');
            // udf1 for payment_form
            // udf2 for user id 

            // pr($request->all());
            
            $data = $res->data;
            $order_number = $data->txnid;
            $status = $data->status;
            $user_id = $data->udf2 ;
            $cart_id = $data->udf3 ;
      
            if($status == 'success'){
                $returnUrlParams = '?status=200&gateway=easebuzz&action=' . $request->udf1;
                if($request->udf1 == 'cart'){
                    $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
                    if ($order) {
                        CaregoryKycDoc::where('cart_id',$cart_id)->update(['ordre_id'=> $order->id,'cart_id'=>'' ]);
                        $returnUrlParams = $returnUrlParams . '&order=' . $order_number;
                    }
                } 
                return Redirect::to(url($returnUrl . $returnUrlParams));
            }
            else{
                $returnUrlParams = '?status=0&gateway=easebuzz&action=' .$request->payment_form;
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
                    $returnUrlParams = $returnUrlParams . '&order=' .  $order_number;
                   
                }
                return Redirect::to(url($returnUrl . $returnUrlParams));
            }
        }
    }  

    public function easybuzzNotify(Request $request, $domain = '')
    {
        try{
            $easebuzzObj = new Easebuzz($MERCHANT_KEY = null, $this->SALT, $ENV = null);
            $result = $easebuzzObj->easebuzzResponse($request->all());
            $response = json_decode($result);
           
             Log::info('result from easebuzz:=');
            Log::info($result);
            $status = $response->status;
            if ($status == 1){  

                // udf1 for payment_form
                // udf2 for user id 
                // udf3 for cart id 
                // txnid is order_number
                
                $data = $response->data;
                $order_number = $data->txnid;
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
