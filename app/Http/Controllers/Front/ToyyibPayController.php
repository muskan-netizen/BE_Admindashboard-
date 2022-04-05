<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\WalletController;
use App\Http\Controllers\Front\UserSubscriptionController;
use App\Http\Controllers\Front\PickupDeliveryController;
use App\Models\{User, UserVendor, Cart, CartAddon, CartCoupon, CartProduct, CartProductPrescription, Payment, PaymentOption, Client, ClientPreference, ClientCurrency, Order, OrderProduct, OrderProductAddon, OrderProductPrescription, VendorOrderStatus, OrderVendor, OrderTax, SubscriptionPlansUser, UserAddress};
use Toyyibpay;
use Auth;
use Illuminate\Support\Facades\Log;

class ToyyibPayController extends Controller
{
    public $api_key;
    public $currency;
    public $url;
    public $base_url;
    public function __construct()
    {
        $base_url = url('/');        
        $toyyib_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'toyyibpay')->where('status', 1)->first();        
        $creds_arr = json_decode($toyyib_creds->credentials);         
        $this->api_key = (isset($creds_arr->toyyibpay_api_key)) ? $creds_arr->toyyibpay_api_key : '';
        $this->url = (isset($creds_arr->toyyibpay_redirect_uri)) ? $creds_arr->toyyibpay_redirect_uri : '';
        
        $testmode = (isset($toyyib_creds->test_mode) && ($toyyib_creds->test_mode == '1')) ? true : false;       
        $this->gateway->setApiKey($api_key);
        $this->gateway->setTestMode($testmode); //set it to 'false' when go live
        $primaryCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
        $this->currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'USD';
        
    }



    public function createCategory($data){        

        $some_data = array(
            'catname' => $data['category_name'], //CATEGORY NAME
            'catdescription' => $data['category_name'], //PROVIDE YOUR CATEGORY DESCRIPTION
            'userSecretKey' => $this->api_key //PROVIDE USER SECRET KEY HERE
          );          
          $curl = curl_init();        
          curl_setopt($curl, CURLOPT_POST, 1);
          curl_setopt($curl, CURLOPT_URL, $this->url.'/index.php/api/createCategory');  //PROVIDE API LINK HERE
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);        
          $result = curl_exec($curl);         
          $info = curl_getinfo($curl);
          curl_close($curl);
          $obj = json_decode($result);
          if($obj){
            return $obj->CategoryCode;
          }
    }

    public function createBill($codeCategory,$data){

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

            $returnUrl = route('payment.toyyibpay.callback');
            $customer_data = array(
                'customer_id' => 'customer_'.$user->id,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone_number
            );
            $order_tags = ['user_id' => strval($user->id), 'payment_form' => $payment_form];
            $reference_number = $description = '';
            $returnUrlParams = '?order_id={order_id}&order_token={order_token}&gateway=cashfree&amount=' . $request->amount . '&payment_form=' . $payment_form;

            if($payment_form == 'cart'){
                $description = 'Order Checkout';
                $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
                $request->request->add(['cart_id' => $cart->id]);
                $reference_number = $request->order_number;
                $order_tags['cart_id'] = strval($cart->id);
                $order_tags['order_number'] = $reference_number;

                $order = Order::where('order_number', $reference_number)->first();
                $returnUrlParams = $returnUrlParams . '&cart_id=' .$cart->id; //. '&order_id={order_id}' .$reference_number. '&order_token=' .$reference_number;
            }
            elseif($payment_form == 'wallet'){
                $description = 'Wallet Checkout';
                // $reference_number = $user->id;
            }
            if($payment_form == 'tip'){
                $description = 'Tip Checkout';
                $order_tags['order_number'] = $request->order_number;
                
                $order = Order::where('order_number', $reference_number)->first();
                // $reference_number = $request->order_number;
                // $returnUrlParams = $returnUrlParams . '&order_id=' .$reference_number. '&order_token=' .$reference_number;
            }
            elseif($payment_form == 'subscription'){
                $description = 'Subscription Checkout';
                if($request->has('subscription_id')){
                    $slug = $request->subscription_id;
                    $subscription_plan = SubscriptionPlansUser::with('features.feature')->where('slug', $slug)->where('status', '1')->first();
                    $customer_data['subscription_id'] = $subscription_plan->id;
                    // $reference_number = $request->subscription_id;
                    $returnUrlParams = $returnUrlParams . '&subscription=' . $request->subscription_id;
                    $order_tags['subscription_id'] = $request->subscription_id;
                }
            }

            $validator = Validator::make($request->all(), $rules, [
                'amount.required' => 'Amount is required',
                'payment_form.required' => 'Action is required',
                'phone_number.required' => 'Phone number is required'
            ]);
            if ($validator->fails()) {
                return $this->errorResponse(__($validator->errors()->first()), 422);
            }



        }catch(\Exception $ex){
            return $this->errorResponse($ex->getMessage(), 400);
        }
        
       
        $some_data = array(
            'userSecretKey'=> $this->api_key,
            'categoryCode'=> $codeCategory,
            'billName'=> $data['product_name'],
            'billDescription'=> $data['product_name'],
            'billPriceSetting'=>0,
            'billPayorInfo'=>1,
            'billAmount'=>$data['amount']*100,
            'billReturnUrl'=> route('payment.toyyibpay.callbackSuccess',$data['payment_form']),
            'billCallbackUrl'=> route('payment.toyyibpay.callback'),
            'billExternalReferenceNo' => $data['order_number'],
            'billTo'=> $user->name,
            'billEmail' => $user->email,
            'billPhone'=>  $user->phone_number,
            'billSplitPayment'=> 0,
            'billSplitPaymentArgs'=> '',
            'billPaymentChannel'=>'0',
            'billContentEmail'=>'Thank you for purchasing our product!',
            'billChargeToCustomer'=> 1,
            'billExpiryDate'=>'',
            'billExpiryDays'=>''
          );  
          //dd($some_data);
          $curl = curl_init();
          curl_setopt($curl, CURLOPT_POST, 1);
          curl_setopt($curl, CURLOPT_URL, $this->url.'/index.php/api/createBill');  
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);
        
          $result = curl_exec($curl);
          $info = curl_getinfo($curl);
          $err = curl_error($curl);  
          curl_close($curl);


          $obj = json_decode($result);
          if($obj){
           // return $obj[0]->BillCode;
           return $this->successResponse($obj[0]->BillCode, 'Order has been created successfully');
          }else{
            return $this->errorResponse($err->message, 400);
          }

    }

  

    public function index(Request $request){

        if(!empty($request->all())){
            $data = $request->all();
            $codeCategory = $this->createCategory($data);
            if(!empty($codeCategory)){
                $bill = $this->createBill($codeCategory,$data);
                if(!empty($bill)){
                    $payUrl = $this->url.'/'.$bill;
                    return response()->json(['status' => 'Success', 'payment_link' => $payUrl]);
                }
            } 
        }
    }

    public function callback(Request $request){

       // http_response_code(200);
      \Log::info($request->all());
     // dd($request->all());
    }

    public function callbackSuccess(Request $request,$payment_form,$domain = ''){
        $toyyibPayRes = $request;     
        $user = Auth::user();      
       
            if($toyyibPayRes['status_id'] == '1' || $toyyibPayRes['status_id'] == '2' ){
                if($toyyibPayRes['payment_form'] == 'cart'){
                    $order_number = $toyyibPayRes['order_id'];
                    $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
                    if ($order) {
                        $returnUrlParams = '';
                        $returnUrl = route('order.success', $order->id);
                        $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();

                        // Remove cart
                        Cart::where('id', $cart->id)->update(['schedule_type' => null, 'scheduled_date_time' => null]);
                        CartAddon::where('cart_id', $cart->id)->delete();
                        CartCoupon::where('cart_id', $cart->id)->delete();
                        CartProduct::where('cart_id', $cart->id)->delete();
                        CartProductPrescription::where('cart_id', $cart->id)->delete();
                        if($toyyibPayRes['status_id'] == '2' ){
                            return Redirect::to(url($returnUrl . $returnUrlParams))->with('success', 'Transaction has been pending');
                        }else{
                            return Redirect::to(url($returnUrl . $returnUrlParams))->with('success', 'Transaction has been completed successfully');
                        }
    
                        // Send Email
                        //   $this->successMail();
                    }
                } elseif($toyyibPayRes['payment_form'] == 'wallet'){
                    $returnUrl = route('user.wallet');

                    if($toyyibPayRes['status_id'] == '2' ){
                        return Redirect::to(url($returnUrl))->with('success', 'Transaction has been pending');
                    }else{
                        return Redirect::to(url($returnUrl))->with('success', 'Transaction has been completed successfully');
                    }
                    
                }
                elseif($toyyibPayRes['payment_form'] == 'tip'){
                    $returnUrl = route('user.orders');
                    if($toyyibPayRes['status_id'] == '2' ){
                        return Redirect::to(url($returnUrl))->with('success', 'Transaction has been pending');
                    }else{
                        return Redirect::to(url($returnUrl))->with('success', 'Transaction has been completed successfully');
                    }
                   
                }
                elseif($request->payment_form == 'subscription'){
                    $returnUrl = route('user.subscription.plans');
                    if($toyyibPayRes['status_id'] == '2' ){
                        return Redirect::to(url($returnUrl))->with('success', 'Transaction has been pending');
                    }else{
                        return Redirect::to(url($returnUrl))->with('success', 'Transaction has been completed successfully');
                    }
                    
                }
            }else{

                if($toyyibPayRes['status_id'] == '3'){

                    if( $toyyibPayRes['payment_form'] == 'cart'){
                        $order = Order::where('order_number',  $toyyibPayRes['order_id'])->first();
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
                    } elseif( $toyyibPayRes['payment_form'] == 'wallet'){
                        return Redirect::to(route('user.wallet'))->with('error', 'Transaction has been cancelled');
                    } elseif( $toyyibPayRes['payment_form'] == 'tip'){
                        return Redirect::to(route('user.orders'))->with('error', 'Transaction has been cancelled');
                    } elseif( $toyyibPayRes['payment_form'] == 'subscription'){
                        return Redirect::to(route('user.subscription.plans'))->with('error', 'Transaction has been cancelled');
                    }

                }
              
            }

     }



}
