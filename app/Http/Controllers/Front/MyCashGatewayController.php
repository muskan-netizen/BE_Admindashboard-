<?php

namespace App\Http\Controllers\Front;

use DB;
use Log;
use Auth;
use Session;
use Redirect;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Front\{FrontController, OrderController, WalletController, UserSubscriptionController};
use App\Models\Client as CP;
use App\Models\{PaymentOption, Client, CaregoryKycDoc, ClientPreference, Order, OrderProduct, EmailTemplate, Cart, CartAddon, OrderProductPrescription, CartProduct, User, Product, OrderProductAddon, Payment, ClientCurrency, OrderVendor, UserAddress, Vendor, CartCoupon, CartProductPrescription, LoyaltyCard, NotificationTemplate, VendorOrderStatus,OrderTax, SubscriptionInvoicesUser, SubscriptionPlansUser, UserDevice, UserVendor, Transaction};

class MyCashGatewayController extends FrontController
{
    use ApiResponser;
    public $api_key;
    public $username;
    public $password;
    public $test_mode;
    public $merchant_phone;
    public $currency;

    public function __construct()
    {
        $creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'mycash')->where('status', 1)->first();
        $creds_arr = isset($creds->credentials) ? json_decode($creds->credentials) : '';
        $this->api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
        $this->username = (isset($creds_arr->username)) ? $creds_arr->username : '';
        $this->password = (isset($creds_arr->password)) ? $creds_arr->password : '';
        $this->test_mode = (isset($creds->test_mode) && ($creds->test_mode == '1')) ? true : false;
        $this->merchant_phone = (isset($creds_arr->merchant_phone)) ? $creds_arr->merchant_phone : '';
        
        $primaryCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
        $this->currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'USD';
    }

    public function purchase(Request $request, $domain = ''){
        try{
            $rules = [
                'amount'   => 'required',
                'payment_form'   => 'required'
            ];

            $user = Auth::user();
            $amount = $this->getDollarCompareAmount($request->amount);
            $payment_form = $request->payment_form;

            if(empty($user->phone_number)){
                $rules['phone_number'] = 'required';
            }

            $returnUrl = route('order.return.success');
            $data = array(
                'api_key' => $this->api_key,
                'username' => $this->username,
                'password' => $this->password,
                'method' => 'paymentRequest',
                'product_id' => 233,
                'amount' => $amount,
                //method:sendOTP
                //mobile_number:6797016954
                'customer_mobile' => '6797016954',//$user->phone_number,
                'merchant_mobile' => $this->merchant_phone
            );
            // dd($data);
            $meta = 'customer_name:'.$user->name.'|payment_form:'.$payment_form;
            $description = '';

            if($payment_form == 'cart'){
                $description = 'Order Checkout';
                $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
                $request->request->add(['cart_id' => $cart->id]);
                $meta = $meta.'|cart_id:' . $cart->id;
                $meta = $meta.'|order_number:' . $request->order_number;
                $data['order_id'] = $request->order_number;
            }
            elseif($payment_form == 'wallet'){
                $description = 'Wallet Checkout';
                $data['order_id'] = 'W_'.time();
            }
            if($payment_form == 'tip'){
                $description = 'Tip Checkout';
                $meta = $meta.'|order_number:' . $request->order_number;
                $data['order_id'] = $request->order_number;
            }
            elseif($payment_form == 'subscription'){
                $description = 'Subscription Checkout';
                if($request->has('subscription_id')){
                    $meta = $meta.'|subscription_id:' . $request->subscription_id;
                    $data['order_id'] = 'S_'.time();
                }
            }

            $validator = Validator::make($request->all(), $rules, [
                'amount.required' => __('Amount is required'),
                'payment_form.required' => __('Action is required'),
                'phone_number.required' => __('Phone number is required')
            ]);
            if ($validator->fails()) {
                return $this->errorResponse(__($validator->errors()->first()), 422);
            }

            $meta = $meta.'|description:' . $description;
            $data['narration'] = $meta;

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $this->getPaymentURL(),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $data,
                // CURLOPT_HTTPHEADER => [
                //     "Accept: application/json",
                //     "Content-Type: application/json"
                // ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return $this->errorResponse($err->message, 400);
            } else {
                $response = json_decode($response);
                dd($response);
                if(isset($response->payment_link)){
                    return $this->successResponse($response, 'Order has been created successfully');
                }else{
                    return $this->errorResponse($response->message, 400);
                }
            }
        }
        catch(\Exception $ex){
            Log::info($ex->getMessage());
            return $this->errorResponse('Server Error', 400);
        }
    }

    public function cashfreeReturn(Request $request, $domain = '')
    {
        $user = Auth::user();
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->getPaymentURL() . "/orders/" .$request->order_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "Content-Type: application/json",
                "x-api-version: 2022-01-01",
                "x-client-id: ". $this->APP_ID,
                "x-client-secret: ". $this->SECRET_KEY
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $response = json_decode($response);
        // dd($response);

        if(!$err && $response){
            $order_status = strtolower($response->order_status);
            if($order_status == 'paid'){
                if($request->payment_form == 'cart'){
                    $order_number = $request->order_id;
                    $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
                    if ($order) {
                        $returnUrlParams = '';
                        $returnUrl = route('order.success', $order->id);
                        return Redirect::to(url($returnUrl . $returnUrlParams))->with('success', 'Transaction has been completed successfully');
    
                        // Send Email
                        //   $this->successMail();
                    }
                } elseif($request->payment_form == 'wallet'){
                    $returnUrl = route('user.wallet');
                    return Redirect::to(url($returnUrl))->with('success', 'Transaction has been completed successfully');
                }
                elseif($request->payment_form == 'tip'){
                    $returnUrl = route('user.orders');
                    return Redirect::to(url($returnUrl))->with('success', 'Transaction has been completed successfully');
                }
                elseif($request->payment_form == 'subscription'){
                    $returnUrl = route('user.subscription.plans');
                    return Redirect::to(url($returnUrl))->with('success', 'Transaction has been completed successfully');
                }
            }
            else{
                if($request->payment_form == 'cart'){
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
                } elseif($request->payment_form == 'wallet'){
                    return Redirect::to(route('user.wallet'))->with('error', 'Transaction has been cancelled');
                } elseif($request->payment_form == 'tip'){
                    return Redirect::to(route('user.orders'))->with('error', 'Transaction has been cancelled');
                } elseif($request->payment_form == 'subscription'){
                    return Redirect::to(route('user.subscription.plans'))->with('error', 'Transaction has been cancelled');
                }
            }
        }
    }

    public function cashfreeReturnApp(Request $request, $domain = '')
    {
        $user = Auth::user();
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->getPaymentURL() . "/orders/" .$request->order_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "Content-Type: application/json",
                "x-api-version: 2022-01-01",
                "x-client-id: ". $this->APP_ID,
                "x-client-secret: ". $this->SECRET_KEY
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $response = json_decode($response);
        // dd($response);

        if(!$err && $response){
            $order_status = strtolower($response->order_status);
            $returnUrl = url('payment/gateway/returnResponse');
            if($order_status == 'paid'){
                $returnUrlParams = '?status=200&gateway=cashfree&action=' . $request->payment_form;
                if($request->payment_form == 'cart'){
                    $order_number = $request->order_id;
                    $order = Order::where('order_number', $order_number)->first();
                    if ($order) {
                        $returnUrlParams = $returnUrlParams . '&order=' . $order_number;
                    }
                }

                return Redirect::to(url($returnUrl . $returnUrlParams));
            }
            else{
                $returnUrlParams = '?status=0&gateway=cashfree&action=' .$request->payment_form;
                if($request->payment_form == 'cart'){
                    $order = Order::where('order_number', $request->order_id)->first();
                    if($order){
                        $wallet_amount_used = $order->wallet_amount_used;
                        if($wallet_amount_used > 0){
                            $transaction = Transaction::where('type', 'deposit')->where('meta', 'LIKE', '%'.$order->order_number.'%')->first();
                            if(!$transaction){
                                $wallet = $user->wallet;
                                $wallet->depositFloat($wallet_amount_used, ['Wallet has been <b>refunded</b> for cancellation of order <b>'. $order->order_number. '</b>']);
                            }
                        }
                        $returnUrlParams = $returnUrlParams . '&order=' . $order->order_number;
                    }
                }

                return Redirect::to(url($returnUrl . $returnUrlParams));
            }
        }
    }

    public function cashfreeNotify(Request $request, $domain = '')
    {
        // Notify cashfree that information has been received
        //dd('sad');
        
        // \Log::info($request->all());

        try{
            
            // \Log::info($request->txStatus);
            $response = $request->has('data') ? $request->data : [];
            // \Log::info($response);
            // \Log::info($response['payment']);
            
            if(!empty($response) && ($response['payment']['payment_status'] == 'SUCCESS')) {
                $transactionId = $response['payment']['cf_payment_id'];
                $user_id = $cart_id = $payment_form = $order_number = $subscription_id = '';
                $amount = $response['order']['order_amount'];
                if($response['order']['order_tags']){
                    $tags = $response['order']['order_tags'];
                    $subscription_id = $tags['subscription_id'] ?? '';
                    $order_number = $tags['order_number'] ?? '';
                    $payment_form = $tags['payment_form'];
                    $user_id = intval($tags['user_id']);
                }

                if($payment_form == 'cart'){
                    $order_number = $response['order']['order_id'];
                    $cart_id = intval($response['order']['order_tags']['cart_id']) ?? '';
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
                            CaregoryKycDoc::where('cart_id',$cart_id)->update(['ordre_id'=> $order->id,'cart_id'=>'' ]);
                            Cart::where('id', $cart_id)->update(['schedule_type' => null, 'scheduled_date_time' => null]);
                            CartAddon::where('cart_id', $cart_id)->delete();
                            CartCoupon::where('cart_id', $cart_id)->delete();
                            CartProduct::where('cart_id', $cart_id)->delete();
                            CartProductPrescription::where('cart_id', $cart_id)->delete();
                            // send sms 
                            $this->sendSuccessSMS($request, $order);
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
            elseif($request->txStatus == 'FAILED'){
                if(!empty($request->orderId)){
                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => $this->getPaymentURL() . "/orders/" .$request->orderId,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        // CURLOPT_POSTFIELDS => json_encode($data),
                        CURLOPT_HTTPHEADER => [
                            "Accept: application/json",
                            "Content-Type: application/json",
                            "x-api-version: 2022-01-01",
                            "x-client-id: ". $this->APP_ID,
                            "x-client-secret: ". $this->SECRET_KEY
                        ],
                    ]);

                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);
                    $response = json_decode($response);
                    // \Log::info($response);

                    if(!$err && $response){
                        $user_id = $payment_form = $order_number = '';
                        if($response->order_tags){
                            $tags = $response->order_tags;
                            $payment_form = $tags->payment_form;
                            $user_id = intval($tags->user_id);
                        }
                        $user = User::find($user_id);
                        if($payment_form == 'cart'){
                            $order_number = $request->orderId;
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
        }
        catch(Exception $ex){
            \Log::info($ex->getMessage());
        }
        http_response_code(200);
    }

    public function getPaymentURL(){
        if($this->test_mode == false){
            return 'https://www.gifts.digicelpacific.com/mycash/';
        }else{
            return 'https://www.gifts.digicelpacific.com/mycash/';
        }
    }

}
