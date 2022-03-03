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
use Log;

class CcavenueController extends Controller
{
   use ApiResponser;

   private $access_key;
   private $merchant_id;
   private $url;
   private $access_code;

   public function __construct()
   {
      $payOpt = PaymentOption::select('credentials', 'test_mode','status')->where('code', 'ccavenue')->where('status', 1)->first();
      $json = json_decode($payOpt->credentials);
      $this->access_key = $json->enc_key;
      $this->access_code = $json->access_code;
      $this->merchant_id = $json->merchant_id;
    if($payOpt->test_mode =='1')
    {
        $this->url = 'https://secure.ccavenue.ae/transaction/transaction.do?command=initiateTransaction';
    }else{
        $this->url = 'https://secure.ccavenue.ae/transaction/transaction.do?command=initiateTransaction';
    }

   }

   public function payForm(Request $request)
   {
    $merchant_data='';
    $number = $request->order_number;
	$working_key='34E2FAAFD2FE419A61B6A33F583CE99A'??$this->access_key;//Shared by CCAVENUES
	$access_code='AVWN04JB22AU70NWUA'??$this->access_code;//Shared by CCAVENUES
	$url=$this->url;//Shared by CCAVENUES
	$user = auth()->user();
    $address = UserAddress::where('is_primary','1')->first();
    $merchant_data = 'merchant_id='.$this->merchant_id.'&order_id='.$request->order_number.'&amount='.$request->amt.'&currency='.getPrimaryCurrencyName().'&redirect_url='.route('ccavenue.success').'&cancel_url='.route('ccavenue.success').'&language=EN&billing_name='.$user->name.'&billing_address='.$address->address.'&billing_city='.$address->city.'&billing_state='.$address->state.'&billing_zip='.$address->pincode.'&billing_country='.$address->country.'&billing_tel='.$user->phone_number.'&billing_email='.$user->email.'&delivery_name='.$user->name.'&delivery_address='.$address->address.'&delivery_city='.$address->city.'&delivery_state='.$address->state.'&delivery_zip='.$address->pincode.'&delivery_country='.$address->country.'&delivery_tel='.$user->phone_number.'&merchant_param1='.$number.'&merchant_param2='.$request->from.'&merchant_param3=web&merchant_param4='.auth()->id().'&merchant_param5=&promo_code=&customer_identifier=&';
    $encrypted_data=$this->encrypt($merchant_data,$working_key); // Method for encrypting the data.


    return view('frontend.payment_gatway.ccavenue_view', compact('encrypted_data','access_code','url'));
   }

   public function payFormWebView(Request $request)
   {
    $merchant_data='';
    $number = $request->order_number;
	$working_key='34E2FAAFD2FE419A61B6A33F583CE99A'??$this->access_key;//Shared by CCAVENUES
	$access_code='AVWN04JB22AU70NWUA'??$this->access_code;//Shared by CCAVENUES
	$url=$this->url;//Shared by CCAVENUES
	$user = auth()->user();
    $address = UserAddress::where('is_primary','1')->first();
    $merchant_data = 'merchant_id='.$this->merchant_id.'&order_id='.$request->order_number.'&amount='.$request->amt.'&currency='.getPrimaryCurrencyName().'&redirect_url='.route('ccavenue.success').'&cancel_url='.route('ccavenue.success').'&language=EN&billing_name='.$user->name.'&billing_address='.$address->address.'&billing_city='.$address->city.'&billing_state='.$address->state.'&billing_zip='.$address->pincode.'&billing_country='.$address->country.'&billing_tel='.$user->phone_number.'&billing_email='.$user->email.'&delivery_name='.$user->name.'&delivery_address='.$address->address.'&delivery_city='.$address->city.'&delivery_state='.$address->state.'&delivery_zip='.$address->pincode.'&delivery_country='.$address->country.'&delivery_tel='.$user->phone_number.'&merchant_param1='.$number.'&merchant_param2='.$request->from.'&merchant_param3=mob&merchant_param4=&merchant_param5=&promo_code=&customer_identifier=&';
    $encrypted_data=$this->encrypt($merchant_data,$working_key); // Method for encrypting the data.


    return view('frontend.payment_gatway.ccavenue_view', compact('encrypted_data','access_code','url'));
   }

   public function successForm(Request $request,$domain='')
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
        $dataArray[$information[0]] = $information[1];
	}
    $dataArray = (object)$dataArray;
    if($dataArray->order_status==="Success")
	{
       return $this->saveSuccess($dataArray);
		//dd("<br>Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.");
		
	}
	else if($$dataArray->order_status==="Aborted")
	{
		dd("<br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail");
	
	}
	else if($$dataArray->order_status==="Failure")
	{
		dd("<br>Thank you for shopping with us.However,the transaction has been declined.");
	}
	else
	{
		dd("<br>Security Error. Illegal access detected");
	
	}

    


   }

   public function cancelForm(Request $request)
   {
    dd($request->getContent());
   }




   public function saveSuccess($request)
   {
      $user  = User::find($request->merchant_param4);
      Auth::login($user);
      $order = Order::where('order_number',$request->order_id)->first();
      $order->payment_status = '1';
      $order->save();
      // Auto accept order
      $orderController = new OrderController();
      $orderController->autoAcceptOrderIfOn($order->id);
      $cart = Cart::where('user_id',$user->id)->select('id')->first();
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

    if(isset($request->auth_token) && !empty($request->auth_token))
    {
      $returnUrl = route('payment.gateway.return.response').'/?gateway=kongapay'.'&status=200&order='.$order->order_number;
      return Redirect::to($returnUrl); 
    }else{
      return Redirect::to(route('order.success',[$order->id]));
    }

 
   }




 //*********** Function *********************


   function encrypt($plainText,$key)
   {
       $key = $this->hextobin(md5($key));

       $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);

       $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
       $encryptedText = bin2hex($openMode);
       return $encryptedText;
   }

   function decrypt($encryptedText,$key)
   {
       $key = $this->hextobin(md5($key));
       $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
       $encryptedText = $this->hextobin($encryptedText);
       $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
       return $decryptedText;
   }
   //*********** Padding Function *********************

    function pkcs5_pad ($plainText, $blockSize)
   {
       $pad = $blockSize - (strlen($plainText) % $blockSize);
       return $plainText . str_repeat(chr($pad), $pad);
   }

   //********** Hexadecimal to Binary function for php 4.0 version ********

   function hextobin($hexString) 
       { 
           $length = strlen($hexString); 
           $binString="";   
           $count=0; 
           while($count<$length) 
           {       
               $subString =substr($hexString,$count,2);           
               $packedString = pack("H*",$subString); 
               if ($count==0)
           {
               $binString=$packedString;
           } 
               
           else 
           {
               $binString.=$packedString;
           } 
               
           $count+=2; 
           } 
             return $binString; 
         }


}
