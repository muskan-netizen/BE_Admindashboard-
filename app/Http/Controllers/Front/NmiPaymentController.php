<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use App\Models\PaymentOption;
use Illuminate\Http\Request;
use Auth;
use App\Http\Traits\ApiResponser;
use App\Models\Cart;
use App\Models\CartAddon;
use App\Models\CartCoupon;
use App\Models\CartProduct;
use App\Models\CartProductPrescription;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserVendor;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Redirect;
use JWT\Token;
use Log;

class NmiPaymentController extends Controller
{
   use ApiResponser;

   private $merchant_key;
   private $merchant_id;
   private $url;
   private $access_code;

   public function __construct()
   {
      $payOpt = PaymentOption::select('credentials', 'test_mode','status')->where('code', 'nmi')->where('status', 1)->first();
      $json = json_decode($payOpt->credentials);
      $this->merchant_id = $json->nmi_key_id;
      $this->merchant_key = $json->nmi_client_id;
    if($payOpt->test_mode =='1')
    {
        $this->url = 'https://secure.ccavenue.ae/transaction/transaction.do?command=initiateTransaction';
    }else{
        $this->url = 'https://secure.ccavenue.ae/transaction/transaction.do?command=initiateTransaction';
    }

   }

   public function orderNumber($request)
   {
        if($request->from == 'cart')
        {
            $time = $request->order_number;

        }elseif($request->from == 'wallet')
        {
            $time = ($request->transaction_id)??'W_'.time();
            Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$request->amt,'type'=>'wallet','date'=>date('Y-m-d')]);

        }elseif($request->from == 'tip')
        {
             $time = 'T_'.time().'_'.$request->order_number;
             Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$request->amt,'type'=>'tip','date'=>date('Y-m-d')]);

        }elseif($request->from == 'subscription')
        {
            $time = ($request->subscription_id)??'S_'.time().'_'.$request->subsid;
            Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$request->amt,'type'=>'subscription','date'=>date('Y-m-d')]);
            
        }
        return $time;
   }

   public function createUserToken()
   {
    $user = auth()->user();
    $token1 = new Token;
    $token = $token1->make([
        'key' => 'royoorders-jwt',
        'issuer' => 'royoorders.com',
        'expiry' => strtotime('+1 month'),
        'issuedAt' => time(),
        'algorithm' => 'HS256',
    ])->get();
    $token1->setClaim('user_id', $user->id);
    $this->token = $token;
    $user->auth_token = $token;
    $user->save();
    return $user;
   }

   public function payForm(Request $request)
   {
    $user = $this->createUserToken();
    $merchant_data='';
    $number = $this->orderNumber($request); // order no
    $working_key=$this->access_key;//Shared by CCAVENUES
    $access_code=$this->access_code;//Shared by CCAVENUES
    $url=$this->url;//Shared by CCAVENUES
	
    $address = UserAddress::where('is_primary','1')->first();
    $merchant_data = 'merchant_id='.$this->merchant_id.'&order_id='.$number.'&amount='.$request->amt.'&currency='.getPrimaryCurrencyName().'&redirect_url='.route('ccavenue.success').'&cancel_url='.route('ccavenue.success').'&language=EN&billing_name='.$user->name.'&billing_address='.$address->address.'&billing_city='.$address->city.'&billing_state='.$address->state.'&billing_zip='.$address->pincode.'&billing_country='.$address->country.'&billing_tel='.$user->phone_number.'&billing_email='.$user->email.'&delivery_name='.$user->name.'&delivery_address='.$address->address.'&delivery_city='.$address->city.'&delivery_state='.$address->state.'&delivery_zip='.$address->pincode.'&delivery_country='.$address->country.'&delivery_tel='.$user->phone_number.'&merchant_param1='.$number.'&merchant_param2='.$request->from.'&merchant_param3=web&merchant_param4='.auth()->id().'&merchant_param5='.$this->token.'&promo_code=&customer_identifier=&';
    $encrypted_data=$this->encrypt($merchant_data,$working_key); // Method for encrypting the data.


    return view('frontend.payment_gatway.ccavenue_view', compact('encrypted_data','access_code','url'));
   }

   public function payFormWebView(Request $request)
   {
    if(isset($request->auth_token) && !empty($request->auth_token)){
        $user = User::where('auth_token', $request->auth_token)->first();
        Auth::login($user);
        $user->auth_token = $request->auth_token;
        $user->save();
     }
     //eyJ0eXAiOiJqd3QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NDY3NDQ0OTgsImV4cCI6MTY0OTQyMjg5OCwiaXNzIjoicm95b29yZGVycy5jb20ifQ.60ADhLV0rlRHQjWtGUD1xgW6Eezs3DIwjyZoV4jILhI
     
    $merchant_data='';
    $number = $this->orderNumber($request); // order no
    $working_key=$this->access_key;//Shared by CCAVENUES
    $access_code=$this->access_code;//Shared by CCAVENUES
    $url=$this->url;//Shared by CCAVENUES
    $user = auth()->user();
    $address = UserAddress::where('is_primary','1')->first();
    $merchant_data = 'merchant_id='.$this->merchant_id.'&order_id='.$number.'&amount='.$request->amt.'&currency='.getPrimaryCurrencyName().'&redirect_url='.route('ccavenue.success').'&cancel_url='.route('ccavenue.success').'&language=EN&billing_name='.$user->name.'&billing_address='.$address->address.'&billing_city='.$address->city.'&billing_state='.$address->state.'&billing_zip='.$address->pincode.'&billing_country='.$address->country.'&billing_tel='.$user->phone_number.'&billing_email='.$user->email.'&delivery_name='.$user->name.'&delivery_address='.$address->address.'&delivery_city='.$address->city.'&delivery_state='.$address->state.'&delivery_zip='.$address->pincode.'&delivery_country='.$address->country.'&delivery_tel='.$user->phone_number.'&merchant_param1='.$number.'&merchant_param2='.$request->from.'&merchant_param3=mob&merchant_param4=&merchant_param5='.$user->auth_token.'&promo_code=&customer_identifier=&';
    //echo $merchant_data;die;
    $encrypted_data=$this->encrypt($merchant_data,$working_key); // Method for encrypting the data.


    return view('frontend.payment_gatway.ccavenue_view', compact('encrypted_data','access_code','url'));
   }

   public function CcavenuePurchase(Request $request)
   {
       $amount = $request->amount;
       $user = auth()->user();
       $action = isset($request->action) ? $request->action : ''; 
       $params = '?amt=' . $amount.'&auth_token='.$user->auth_token.'&from='.$action;
       if($action == 'cart'){
           $params = $params . '&order_number=' . $request->order_number.'&app=1';
       }elseif($action == 'wallet'){
         //app = 2 is for wallet
        $params = $params .'&app=2&transaction_id=W_'.time();
       }elseif($action == 'subscription'){
        //app = 2 is for wallet
       $params = $params .'&app=3&subscription_id='.'S_'.time().'_'.$request->subscription_id;
      }elseif($action == 'tip'){
        //app = 2 is for wallet
       $params = $params .'&app=3&order_number='.$request->order_number;
      }

       return $this->successResponse(url($request->serverUrl.'payment/ccavenue/api/'.$params)); 
   }

   public function successForm(Request $request)
   {
    $encResponse=$request->encResp;			//This is the response sent by the CCAvenue Server
	  $rcvdString=$this->decrypt($encResponse,$this->access_key);		//Crypto Decryption used as per the specified working key.
	  $order_status="";
	  $decryptValues=explode('&', $rcvdString);

	  $dataSize=sizeof($decryptValues);
    $dataArray = array();
    for($i = 0; $i < $dataSize; $i++) 
    {
      $information=explode('=',$decryptValues[$i]);
      $request->request->add([$information[0] => $information[1]]);
    }
    
    if(isset($request->merchant_param5) && !empty($request->merchant_param5)){
        $user = User::where('auth_token',$request->merchant_param5)->first();
        Auth::login($user);
     }
  
        if($request->merchant_param2=='cart'){
            return $this->completeOrderCart($request);
        }elseif($request->merchant_param2=='wallet'){
            return $this->completeOrderWallet($request);
        }elseif($request->merchant_param2=='tip'){
            return $this->completeOrderTip($request);
        }elseif($request->merchant_param2=='subscription'){
            return $this->completeOrderSubs($request);
        }

   }



   public function completeOrderCart($request)
   {
    $order = Order::where('order_number',$request->order_id)->first();
       if(isset($request->order_status) && $request->order_status == 'Success')
       {
            //Success from cart  
                $order->payment_status = '1';
                $order->save();
                // Auto accept order
                $orderController = new OrderController();
                $orderController->autoAcceptOrderIfOn($order->id);
                $cart = Cart::where('user_id',auth()->id())->select('id')->first();
                $cartid = $cart->id;
                Cart::where('id', $cartid)->update([
                    'schedule_type' => null, 'scheduled_date_time' => null,
                    'comment_for_pickup_driver' => null, 'comment_for_dropoff_driver' => null, 'comment_for_vendor' => null, 'schedule_pickup' => null, 'schedule_dropoff' => null, 'specific_instructions' => null
                ]);

                CartAddon::where('cart_id', $cartid)->delete();
                CartCoupon::where('cart_id', $cartid)->delete();
                CartProduct::where('cart_id', $cartid)->delete();
                CartProductPrescription::where('cart_id', $cartid)->delete();

                Payment::create(['amount'=>0,'transaction_id'=>$request->tracking_id,'balance_transaction'=>$order->payable_amount,'type'=>'cart','date'=>date('Y-m-d'),'order_id'=>$order->id]);

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

                if(isset($request->merchant_param3) && $request->merchant_param3=='mob')
                {
                $returnUrl = route('payment.gateway.return.response').'/?gateway=ccavenue'.'&status=200&order='.$order->order_number;
                return Redirect::to($returnUrl); 
                }else{
                return Redirect::to(route('order.success',[$order->id]));
                }

        }else{
        
                //Failed from cart
                $user = auth()->user();
                $wallet = $user->wallet;
                if(isset($order->wallet_amount_used)){
                $wallet->depositFloat($order->wallet_amount_used, ['Wallet has been <b>refunded</b> for cancellation of order #'. $order->order_number]);
                }
                if(isset($request->merchant_param3) && $request->merchant_param3=='mob')
                {
                $returnUrl = route('payment.gateway.return.response').'/?gateway=kongapay'.'&status=00&order='.$order->order_number;
                return Redirect::to($returnUrl);  
                }else{
                return Redirect::to(route('showCart'))->with('error',$request->message);
                }

        }   
   }


   public function completeOrderWallet($request)
   {
        if(isset($request->order_status) && $request->order_status == 'Success')
         {
           $data = Payment::where('transaction_id',$request->order_id)->first();
           $user = auth()->user();
           $wallet = $user->wallet;
           $wallet->depositFloat($data->balance_transaction, ['Wallet has been <b>credited</b> for order number <b>' . $request->order_id . '</b>']);

           if(isset($request->merchant_param3) && $request->merchant_param3=='mob')
           {
             $returnUrl = route('payment.gateway.return.response').'/?gateway=kongapay'.'&status=200&transaction_id='.$request->order_id.'&action=wallet';
             return Redirect::to($returnUrl); 
           }else{
             return Redirect::to(route('user.wallet'));
           }

           
         }else{
           $data = Payment::where('transaction_id',$request->order_id)->first();
           $data->delete();

           if(isset($request->merchant_param3) && $request->merchant_param3=='mob')
           {
             $returnUrl = route('payment.gateway.return.response').'/?gateway=kongapay'.'&status=00&transaction_id='.$request->order_id.'&action=wallet';
             return Redirect::to($returnUrl); 
           }else{
             return Redirect::to(route('user.wallet'))->with('error',$request->message);
           }

          
         }
       return $this->successResponse($request->getTransactionReference());

   }


   public function completeOrderSubs($request)
   {
     $user = auth()->user();
     $data = Payment::where('transaction_id',$request->order_id)->first();
     if(isset($request->order_status) && $request->order_status == 'Success')
         {
           $subscription = explode('_',$request->order_id);
           $request->request->add(['user_id' => $user->id, 'payment_option_id' => 22, 'amount' => $data->balance_transaction, 'transaction_id' => $request->order_id]);
           $subscriptionController = new UserSubscriptionController();
           $subscriptionController->purchaseSubscriptionPlan($request, '', $subscription[2]);

           if(isset($request->merchant_param3) && $request->merchant_param3=='mob')
           {
             $returnUrl = route('payment.gateway.return.response').'/?gateway=kongapay'.'&status=200&transaction_id='.$request->order_id.'&action=subscription';
             return Redirect::to($returnUrl); 
           }else{
             return Redirect::to(route('user.subscription.plans'))->with('error',$request->message);
           }
         }else{
           $data->delete();

           if(isset($request->merchant_param3) && $request->merchant_param3=='mob')
           {
             $returnUrl = route('payment.gateway.return.response').'/?gateway=kongapay'.'&status=00&transaction_id='.$request->order_id.'&action=subscription';
             return Redirect::to($returnUrl); 
           }else{
             return Redirect::to(route('user.subscription.plans'))->with('error',$request->message);
           }

         }
       return $this->successResponse($request->getTransactionReference());

   }

   public function completeOrderTip($request)
   {
     $data = Payment::where('transaction_id',$request->order_id)->first();
     if(isset($request->order_status) && $request->order_status == 'Success')
         {
           $order_number = explode('_',$request->order_id);
           $request->request->add(['user_id' => auth()->id(), 'order_number' => $order_number[2], 'tip_amount' => $data->balance_transaction, 'transaction_id' => $request->order_id]);
           $orderController = new OrderController();
           $orderController->tipAfterOrder($request);

           if(isset($request->merchant_param3) && $request->merchant_param3=='mob')
             {
               $returnUrl = route('payment.gateway.return.response').'/?gateway=kongapay'.'&status=200&order='.$order_number[2].'&action=tip';
               return Redirect::to($returnUrl); 
             }else{
               return Redirect::to(route('user.orders'))->with('success', $request->message);
             }

         }else{
           $data->delete();

           if(isset($request->merchant_param3) && $request->merchant_param3=='mob')
             {
               $returnUrl = route('payment.gateway.return.response').'/?gateway=kongapay'.'&status=00&transaction_id='.$request->order_id.'&action=tip';
               return Redirect::to($returnUrl); 
             }else{
               return Redirect::to(route('user.orders'))->with('error', $request->message);
             }

         }
       return $this->successResponse($request->getTransactionReference());

   }




 //*********** Function *********************

        // Initial Setting Functions

        function setLogin($security_key) {
            $this->login['security_key'] = $security_key;
        }

        function setOrder($orderid,
                $orderdescription,
                $tax,
                $shipping,
                $ponumber,
                $ipaddress) {
            $this->order['orderid']          = $orderid;
            $this->order['orderdescription'] = $orderdescription;
            $this->order['tax']              = $tax;
            $this->order['shipping']         = $shipping;
            $this->order['ponumber']         = $ponumber;
            $this->order['ipaddress']        = $ipaddress;
        }

        function setBilling($firstname,
                $lastname,
                $company,
                $address1,
                $address2,
                $city,
                $state,
                $zip,
                $country,
                $phone,
                $fax,
                $email,
                $website) {
            $this->billing['firstname'] = $firstname;
            $this->billing['lastname']  = $lastname;
            $this->billing['company']   = $company;
            $this->billing['address1']  = $address1;
            $this->billing['address2']  = $address2;
            $this->billing['city']      = $city;
            $this->billing['state']     = $state;
            $this->billing['zip']       = $zip;
            $this->billing['country']   = $country;
            $this->billing['phone']     = $phone;
            $this->billing['fax']       = $fax;
            $this->billing['email']     = $email;
            $this->billing['website']   = $website;
        }

        function setShipping($firstname,
                $lastname,
                $company,
                $address1,
                $address2,
                $city,
                $state,
                $zip,
                $country,
                $email) {
            $this->shipping['firstname'] = $firstname;
            $this->shipping['lastname']  = $lastname;
            $this->shipping['company']   = $company;
            $this->shipping['address1']  = $address1;
            $this->shipping['address2']  = $address2;
            $this->shipping['city']      = $city;
            $this->shipping['state']     = $state;
            $this->shipping['zip']       = $zip;
            $this->shipping['country']   = $country;
            $this->shipping['email']     = $email;
        }

        // Transaction Functions

        function doSale($amount, $ccnumber, $ccexp, $cvv="") {

            $query  = "";
            // Login Information
            $query .= "security_key=" . urlencode($this->login['security_key']) . "&";
            // Sales Information
            $query .= "ccnumber=" . urlencode($ccnumber) . "&";
            $query .= "ccexp=" . urlencode($ccexp) . "&";
            $query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
            $query .= "cvv=" . urlencode($cvv) . "&";
            // Order Information
            $query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
            $query .= "orderid=" . urlencode($this->order['orderid']) . "&";
            $query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
            $query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
            $query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
            $query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
            // Billing Information
            $query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
            $query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
            $query .= "company=" . urlencode($this->billing['company']) . "&";
            $query .= "address1=" . urlencode($this->billing['address1']) . "&";
            $query .= "address2=" . urlencode($this->billing['address2']) . "&";
            $query .= "city=" . urlencode($this->billing['city']) . "&";
            $query .= "state=" . urlencode($this->billing['state']) . "&";
            $query .= "zip=" . urlencode($this->billing['zip']) . "&";
            $query .= "country=" . urlencode($this->billing['country']) . "&";
            $query .= "phone=" . urlencode($this->billing['phone']) . "&";
            $query .= "fax=" . urlencode($this->billing['fax']) . "&";
            $query .= "email=" . urlencode($this->billing['email']) . "&";
            $query .= "website=" . urlencode($this->billing['website']) . "&";
            // Shipping Information
            $query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
            $query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
            $query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
            $query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
            $query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
            $query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
            $query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
            $query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
            $query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
            $query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
            $query .= "type=sale";
            return $this->_doPost($query);
        }

        function doAuth($amount, $ccnumber, $ccexp, $cvv="") {

            $query  = "";
            // Login Information
            $query .= "security_key=" . urlencode($this->login['security_key']) . "&";
            // Sales Information
            $query .= "ccnumber=" . urlencode($ccnumber) . "&";
            $query .= "ccexp=" . urlencode($ccexp) . "&";
            $query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
            $query .= "cvv=" . urlencode($cvv) . "&";
            // Order Information
            $query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
            $query .= "orderid=" . urlencode($this->order['orderid']) . "&";
            $query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
            $query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
            $query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
            $query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
            // Billing Information
            $query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
            $query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
            $query .= "company=" . urlencode($this->billing['company']) . "&";
            $query .= "address1=" . urlencode($this->billing['address1']) . "&";
            $query .= "address2=" . urlencode($this->billing['address2']) . "&";
            $query .= "city=" . urlencode($this->billing['city']) . "&";
            $query .= "state=" . urlencode($this->billing['state']) . "&";
            $query .= "zip=" . urlencode($this->billing['zip']) . "&";
            $query .= "country=" . urlencode($this->billing['country']) . "&";
            $query .= "phone=" . urlencode($this->billing['phone']) . "&";
            $query .= "fax=" . urlencode($this->billing['fax']) . "&";
            $query .= "email=" . urlencode($this->billing['email']) . "&";
            $query .= "website=" . urlencode($this->billing['website']) . "&";
            // Shipping Information
            $query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
            $query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
            $query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
            $query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
            $query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
            $query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
            $query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
            $query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
            $query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
            $query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
            $query .= "type=auth";
            return $this->_doPost($query);
        }

        function doCredit($amount, $ccnumber, $ccexp) {

            $query  = "";
            // Login Information
            $query .= "security_key=" . urlencode($this->login['security_key']) . "&";
            // Sales Information
            $query .= "ccnumber=" . urlencode($ccnumber) . "&";
            $query .= "ccexp=" . urlencode($ccexp) . "&";
            $query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
            // Order Information
            $query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
            $query .= "orderid=" . urlencode($this->order['orderid']) . "&";
            $query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
            $query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
            $query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
            $query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
            // Billing Information
            $query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
            $query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
            $query .= "company=" . urlencode($this->billing['company']) . "&";
            $query .= "address1=" . urlencode($this->billing['address1']) . "&";
            $query .= "address2=" . urlencode($this->billing['address2']) . "&";
            $query .= "city=" . urlencode($this->billing['city']) . "&";
            $query .= "state=" . urlencode($this->billing['state']) . "&";
            $query .= "zip=" . urlencode($this->billing['zip']) . "&";
            $query .= "country=" . urlencode($this->billing['country']) . "&";
            $query .= "phone=" . urlencode($this->billing['phone']) . "&";
            $query .= "fax=" . urlencode($this->billing['fax']) . "&";
            $query .= "email=" . urlencode($this->billing['email']) . "&";
            $query .= "website=" . urlencode($this->billing['website']) . "&";
            $query .= "type=credit";
            return $this->_doPost($query);
        }

        function doOffline($authorizationcode, $amount, $ccnumber, $ccexp) {

            $query  = "";
            // Login Information
            $query .= "security_key=" . urlencode($this->login['security_key']) . "&";
            // Sales Information
            $query .= "ccnumber=" . urlencode($ccnumber) . "&";
            $query .= "ccexp=" . urlencode($ccexp) . "&";
            $query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
            $query .= "authorizationcode=" . urlencode($authorizationcode) . "&";
            // Order Information
            $query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
            $query .= "orderid=" . urlencode($this->order['orderid']) . "&";
            $query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
            $query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
            $query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
            $query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
            // Billing Information
            $query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
            $query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
            $query .= "company=" . urlencode($this->billing['company']) . "&";
            $query .= "address1=" . urlencode($this->billing['address1']) . "&";
            $query .= "address2=" . urlencode($this->billing['address2']) . "&";
            $query .= "city=" . urlencode($this->billing['city']) . "&";
            $query .= "state=" . urlencode($this->billing['state']) . "&";
            $query .= "zip=" . urlencode($this->billing['zip']) . "&";
            $query .= "country=" . urlencode($this->billing['country']) . "&";
            $query .= "phone=" . urlencode($this->billing['phone']) . "&";
            $query .= "fax=" . urlencode($this->billing['fax']) . "&";
            $query .= "email=" . urlencode($this->billing['email']) . "&";
            $query .= "website=" . urlencode($this->billing['website']) . "&";
            // Shipping Information
            $query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
            $query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
            $query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
            $query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
            $query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
            $query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
            $query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
            $query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
            $query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
            $query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
            $query .= "type=offline";
            return $this->_doPost($query);
        }

        function doCapture($transactionid, $amount =0) {

            $query  = "";
            // Login Information
            $query .= "security_key=" . urlencode($this->login['security_key']) . "&";
            // Transaction Information
            $query .= "transactionid=" . urlencode($transactionid) . "&";
            if ($amount>0) {
                $query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
            }
            $query .= "type=capture";
            return $this->_doPost($query);
        }

        function doVoid($transactionid) {

            $query  = "";
            // Login Information
            $query .= "security_key=" . urlencode($this->login['security_key']) . "&";
            // Transaction Information
            $query .= "transactionid=" . urlencode($transactionid) . "&";
            $query .= "type=void";
            return $this->_doPost($query);
        }

        function doRefund($transactionid, $amount = 0) {

            $query  = "";
            // Login Information
            $query .= "security_key=" . urlencode($this->login['security_key']) . "&";
            // Transaction Information
            $query .= "transactionid=" . urlencode($transactionid) . "&";
            if ($amount>0) {
                $query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
            }
            $query .= "type=refund";
            return $this->_doPost($query);
        }

        function _doPost($query) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://secure.nmi.com/api/transact.php");
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
            curl_setopt($ch, CURLOPT_POST, 1);

            if (!($data = curl_exec($ch))) {
                return ERROR;
            }
            curl_close($ch);
            unset($ch);
            print "\n$data\n";
            $data = explode("&",$data);
            for($i=0;$i<count($data);$i++) {
                $rdata = explode("=",$data[$i]);
                $this->responses[$rdata[0]] = $rdata[1];
            }
            return $this->responses['response'];
        }


       
//         $gw = new NmiPaymentController();
// $gw->setLogin("zC4Wr4g4A3UGdd7MWpe88buJdHt5SDPN");
// $gw->setBilling("John","Smith","Acme, Inc.","123 Main St","Suite 200", "Beverly Hills",
//         "CA","90210","US","555-555-5555","555-555-5556","support@example.com",
//         "www.example.com");
// $gw->setShipping("Mary","Smith","na","124 Shipping Main St","Suite Ship", "Beverly Hills",
//         "CA","90210","US","support@example.com");
// $gw->setOrder("1234","Big Order",1, 2, "PO1234","65.192.14.10");

// $r = $gw->doSale("50.00","4111111111111111","1010");
// dd($gw->responses);

// "response" => "3"
//   "responsetext" => "Duplicate transaction REFID:1206192251"
//   "authcode" => ""
//   "transactionid" => ""
//   "avsresponse" => ""
//   "cvvresponse" => ""
//   "orderid" => "1234"
//   "type" => "sale"
//   "response_code" => "300"
//   "amount_authorized" => ""
//   "first_name" => "John"
//   "last_name" => "Smith"
//   "address_1" => "123 Main St"
//   "city" => "Beverly Hills"
//   "postal_code" => "90210"
//   "state" => "CA"
//   "phone" => "555-555-5555"
//   "shipping_address_1" => "124 Shipping Main St"
//   "shipping_city" => "Beverly Hills"
//   "shipping_postal_code" => "90210"
//   "shipping_state" => "CA"
//   "shipping_country" => "US"
//   "shipping_amount" => "2.00"
//   "shipping_company" => "na"
//   "shipping_email" => "support@example.com"
//   "date" => ""
//   "currency" => "USD"

}
