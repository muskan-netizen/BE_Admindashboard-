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
use App\Models\UserVendor;
use App\Models\CaregoryKycDoc;

use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Redirect;
use Log;
use App\Http\Controllers\Front\FrontController;

class PayphoneController extends FrontController
{
   use ApiResponser;

   private $id;
   private $token;
   private $url;

   public function __construct()
   {
      $payphone = PaymentOption::select('credentials', 'test_mode','status')->where('code', 'payphone')->where('status', 1)->first();
      $json = json_decode($payphone->credentials);
      $this->id = $json->id;
      $this->token = $json->token;
   }

   public function createHash(Request $request)
   {
     $time = '';
     $amt = $request->amt??$request->amount;
     $amt =  $this->getDollarCompareAmount($request->amt);
    if(isset($request->auth_token) && !empty($request->auth_token)){
      $user = User::where('auth_token', $request->auth_token)->first();
      Auth::login($user);
    }else{
      $user = auth()->user();
    }

     $name = explode(' ',$user->name);
     $returnUrl = '';
     if($request->from == 'cart')
     {
      $time = $request->order_number;
      Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$amt,'type'=>'cart','date'=>date('Y-m-d')]);

     }elseif($request->from == 'wallet')
     {
      $time = ($request->transaction_id)??'W_'.time();
      //Save transaction before payment success for get information only
      Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$amt,'type'=>'wallet','date'=>date('Y-m-d')]);

     }elseif($request->from == 'tip')
     {
      $time = 'T_'.time().'_'.$request->order_number;
      Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$amt,'type'=>'tip','date'=>date('Y-m-d')]);
      
     }elseif($request->from == 'subscription')
     {
      $time = ($request->subscription_id)??'S_'.time().'_'.$request->subsid;
      Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$amt,'type'=>'subscription','date'=>date('Y-m-d')]);
     }
     //Need to save entry in payment table
     $data = (object)array(
            "token"=> $this->token,
            "amount"=> $amt*100,
            "orderNo"=> $time,
            "returnUrl"=>route('payphone.success')
        );
      return json_encode($data);
   }  


   public function createHashApp(Request $request)
   {
     $time = '';
     $amt = $request->amt??$request->amount;
     $amt =  $this->getDollarCompareAmount($amt);
    if(isset($request->auth_token) && !empty($request->auth_token)){
      $user = User::where('auth_token', $request->auth_token)->first();
      Auth::login($user);
    }else{
      $user = auth()->user();
    }

     $name = explode(' ',$user->name);
     $returnUrl = '';
     if($request->from == 'cart')
     {
      $time = $request->order_number;
      Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$amt,'type'=>'cart','date'=>date('Y-m-d')]);

     }elseif($request->from == 'wallet')
     {
      $time = ($request->transaction_id)??'W_'.time();
      //Save transaction before payment success for get information only
      Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$amt,'type'=>'wallet','date'=>date('Y-m-d')]);

     }elseif($request->from == 'tip')
     {
      $time = 'T_'.time().'_'.$request->order_number;
      Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$amt,'type'=>'tip','date'=>date('Y-m-d')]);
      
     }elseif($request->from == 'subscription')
     {
      $time = ($request->subscription_id)??'S_'.time().'_'.$request->subsid;
      Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$amt,'type'=>'subscription','date'=>date('Y-m-d')]);
     }
     //Need to save entry in payment table
     $data = array(
            "token"=> $this->token,
            "amount"=> $amt*100,
            "orderNo"=> $time,
            "returnUrl"=>url($request->serverUrl.'payment/payphone/success'),
            "from"=>$request->from,
        );
        $params = http_build_query ( $data );  
      // return json_encode($data);
      return $this->successResponse(url($request->serverUrl.'payment/payphone/api?'.$params)); 
   }  



   public function webViewPay(Request $request)
   {
    $request->request->add(['amt'=>$request->amount,'from'=>$request->from,'order_number'=>$request->orderNo??time(),'payid'=>$this->id,'payToken'=>$this->token]);
    return view('frontend.payment_gatway.payphone_view', compact('request'));
   }



   public function successPage(Request $request)
   {   
       $payment = Payment::where('transaction_id',$request->clientTransactionId)->first();
       if($payment->type=='cart'){
          return $this->completeOrderCart($request,$payment);
        }elseif($payment->type=='wallet'){
            return $this->completeOrderWallet($request,$payment);
        }elseif($payment->type=='tip'){
            return $this->completeOrderTip($request,$payment);
        }elseif($payment->type=='subscription'){
            return $this->completeOrderSubs($request,$payment);
        }
   }


   public function completeOrderCart(Request $request)
    {

      $order = Order::where('order_number',$request->clientTransactionId)->first();
          if(isset($request->clientTransactionId) && $request->status == 'Approved')
          {
           
            $order->payment_status = '1';
            $order->save();

            // Auto accept order
            $orderController = new OrderController();
            $orderController->autoAcceptOrderIfOn($order->id);

            $cart = Cart::where('user_id',auth()->id())->select('id')->first();
            $cartid = $cart->id;
            CaregoryKycDoc::where('cart_id',$cart->id)->update(['ordre_id'=> $order->id,'cart_id'=>'' ]);
            Cart::where('id', $cartid)->update([
              'schedule_type' => null, 'scheduled_date_time' => null,
              'comment_for_pickup_driver' => null, 'comment_for_dropoff_driver' => null, 'comment_for_vendor' => null, 'schedule_pickup' => null, 'schedule_dropoff' => null, 'specific_instructions' => null
          ]);
            CartAddon::where('cart_id', $cartid)->delete();
            CartCoupon::where('cart_id', $cartid)->delete();
            CartProduct::where('cart_id', $cartid)->delete();
            CartProductPrescription::where('cart_id', $cartid)->delete();
            // send sms 
            $this->sendSuccessSMS($request, $order);
            Payment::create(['amount'=>0,'transaction_id'=>$request->id,'balance_transaction'=>$order->payable_amount,'type'=>'cart','date'=>date('Y-m-d'),'order_id'=>$order->id]);

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
            $returnUrl = route('payment.gateway.return.response').'/?gateway=payphone'.'&status=200&order='.$order->order_number;
            return Redirect::to($returnUrl); 
          }else{
            return Redirect::to(route('order.success',[$order->id]));
          }

          }else{
            $user = auth()->user();
            $wallet = $user->wallet;
            if(isset($order->wallet_amount_used)){
              $wallet->depositFloat($order->wallet_amount_used, ['Wallet has been <b>refunded</b> for cancellation of order #'. $order->order_number]);
            }
            if(isset($request->auth_token) && !empty($request->auth_token))
            {
              $returnUrl = route('payment.gateway.return.response').'/?gateway=kongapay'.'&status=00&order='.$order->order_number;
              return Redirect::to($returnUrl);  
            }else{
              return Redirect::to(route('showCart'))->with('error',$request->message);
            }

          }

        return $this->successResponse($request->getTransactionReference());

    }


    public function completeOrderWallet(Request $request)
    {
          if(isset($request->clientTransactionId) && $request->status == 'Approved')
          {
            $data = Payment::where('transaction_id',$request->clientTransactionId)->first();
            $user = auth()->user();
            $wallet = $user->wallet;
            $wallet->depositFloat($data->balance_transaction, ['Wallet has been <b>credited</b> for order number <b>' . $request->clientTransactionId . '</b>']);

            if(isset($request->auth) && !empty($request->auth))
            {
              $returnUrl = route('payment.gateway.return.response').'/?gateway=payphone'.'&status=200&transaction_id='.$request->id.'&action=wallet';
              return Redirect::to($returnUrl); 
            }else{
              return Redirect::to(route('user.wallet'));
            }

            
          }else{
            $data = Payment::where('transaction_id',$request->clientTransactionId)->first();
            $data->delete();

            if(isset($request->auth) && !empty($request->auth))
            {
              $returnUrl = route('payment.gateway.return.response').'/?gateway=payphone'.'&status=00&transaction_id='.$request->id.'&action=wallet';
              return Redirect::to($returnUrl); 
            }else{
              return Redirect::to(route('user.wallet'))->with('error',$request->message);
            }

           
          }
        return $this->successResponse($request->getTransactionReference());

    }


    public function completeOrderSubs(Request $request)
    {
      $user = auth()->user();
      $data = Payment::where('transaction_id',$request->clientTransactionId)->first();
      if(isset($request->clientTransactionId) && $request->status == 'Approved')
          {
            $subscription = explode('_',$request->clientTransactionId);
            $request->request->add(['user_id' => $user->id, 'payment_option_id' => 32, 'amount' => $data->balance_transaction, 'transaction_id' => $request->clientTransactionId]);
            $subscriptionController = new UserSubscriptionController();
            $subscriptionController->purchaseSubscriptionPlan($request, '', $subscription[2]);

            if(isset($request->auth) && !empty($request->auth))
            {
              $returnUrl = route('payment.gateway.return.response').'/?gateway=payphone'.'&status=200&transaction_id='.$request->id.'&action=subscription';
              return Redirect::to($returnUrl);
            }else{
              return Redirect::to(route('user.subscription.plans'))->with('success',$request->message);
            }
          }else{
            $data->delete();

            if(isset($request->auth) && !empty($request->auth))
            {
              $returnUrl = route('payment.gateway.return.response').'/?gateway=payphone'.'&status=00&transaction_id='.$request->id.'&action=subscription';
              return Redirect::to($returnUrl); 
            }else{
              return Redirect::to(route('user.subscription.plans'))->with('error',$request->message);
            }

          }
        return $this->successResponse($request->getTransactionReference());

    }

    public function completeOrderTip(Request $request)
    {
      $data = Payment::where('transaction_id',$request->clientTransactionId)->first();
      if(isset($request->clientTransactionId) && $request->status == 'Approved')
          {
            $order_number = explode('_',$request->clientTransactionId);
            $request->request->add(['user_id' => auth()->id(), 'order_number' => $order_number[2], 'tip_amount' => $data->balance_transaction, 'transaction_id' => $request->clientTransactionId]);
            $orderController = new OrderController();
            $orderController->tipAfterOrder($request);

            if(isset($request->order_no) && !empty($request->order_no))
              {
                $returnUrl = route('payment.gateway.return.response').'/?gateway=payphone'.'&status=200&order='.$order_number[2].'&action=tip';
                return Redirect::to($returnUrl);
              }else{
                return Redirect::to(route('user.orders'))->with('success', "Tip added Successfully.");
              }

          }else{
            $data->delete();

              if(isset($request->order_no) && !empty($request->order_no))
              {
                $returnUrl = route('payment.gateway.return.response').'/?gateway=payphone'.'&status=00&transaction_id='.$request->clientTransactionId.'&action=tip';
                return Redirect::to($returnUrl); 
              }else{
                return Redirect::to(route('user.orders'))->with('error', $request->message);
              }

          }
        return $this->successResponse($request->getTransactionReference());

    }

}
