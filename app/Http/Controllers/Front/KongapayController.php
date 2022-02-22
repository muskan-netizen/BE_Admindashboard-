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
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Redirect;

class KongapayController extends Controller
{
   use ApiResponser;

   private $api_key;
   private $merchant_id;
   private $url;

   public function __construct()
   {
      $konga = PaymentOption::select('credentials', 'test_mode','status')->where('code', 'kongapay')->where('status', 1)->first();
      $json = json_decode($konga->credentials);
      $this->api_key = $json->api_key;
      $this->merchant_id = $json->merchant_id;
   }


   public function createHash(Request $request)
   {
     $time = '';
     $user = auth()->user();
     $name = explode(' ',$user->name);
     $returnUrl = '';
     if($request->from == 'cart')
     {
      $request->amt = $request->amt*100;
      $time = $request->order_number;
      $returnUrl = route('kongapay.successCart');
     }elseif($request->from == 'wallet')
     {
      $time = 'W_'.time();
      //Save transaction before payment success for get information only
      Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$request->amt,'type'=>'wallet','date'=>date('Y-m-d')]);

      $request->amt = $request->amt*100;
      $returnUrl = route('kongapay.successWallet');
     }elseif($request->from == 'tip')
     {
      $time = 'T_'.time().'_'.$request->order_number;
      Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$request->amt,'type'=>'tip','date'=>date('Y-m-d')]);
     
      $request->amt = $request->amt*100;
      $returnUrl = route('kongapay.successTip');
     }elseif($request->from == 'subscription')
     {
      $time = 'S_'.time().'_'.$request->subsid;
      Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$request->amt,'type'=>'subscription','date'=>date('Y-m-d')]);

      $request->amt = number_format($request->amt,2)*100;
      $returnUrl = route('kongapay.successSubs');
     }   
     $key = $request->amt.'|'.$this->api_key.'|'.$time;

     //Need to save entry in payment table

     $data = array(
            "hash"=> hash('Sha512',$key),
            "amount"=> $request->amt??0,
            "description"=> "payment",
            "email"=> $user->email??'',
            "merchantId"=> $this->merchant_id,
            "reference"=> $time,
            "firstname" => $name[0]??'',
            "lastname" => $name[1]??'last name',
            "phone" => $user->phone_number,
            "enableFrame"=> true,
            "callback" => $returnUrl,
            "customerId" => $user->email
        );

      return json_encode($data);
   }  


   public function completeOrderCart(Request $request)
    {
      $order = Order::where('order_number',$request->merchant_reference)->first();
          if(isset($request->merchant_reference) && $request->code=='00' && $request->status == 'success')
          {
            $order->payment_status = '1';
            $order->save();
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
            Payment::create(['amount'=>0,'transaction_id'=>$request->merchant_reference,'balance_transaction'=>$order->payable_amount,'type'=>'cart','date'=>date('Y-m-d'),'order_id'=>$order->id]);
            return Redirect::to(route('order.success',[$order->id]));
          }else{
            $user = auth()->user();
            $wallet = $user->wallet;
            $wallet->depositFloat($order->wallet_amount_used, ['Wallet has been <b>refunded</b> for cancellation of order #'. $order->order_number]);

            return Redirect::to(route('showCart'))->with('error',$request->message);
          }

        return $this->successResponse($request->getTransactionReference());

    }


    public function completeOrderWallet(Request $request)
    {
          if(isset($request->merchant_reference) && $request->code=='00' && $request->status == 'success')
          {
            $data = Payment::where('transaction_id',$request->merchant_reference)->first();
            $user = auth()->user();
            $wallet = $user->wallet;
            $wallet->depositFloat($data->balance_transaction, ['Wallet has been <b>credited</b> for order number <b>' . $request->merchant_reference . '</b>']);

            return Redirect::to(route('user.wallet'));
          }else{
            $data = Payment::where('transaction_id',$request->merchant_reference)->first();
            $data->delete();
            return Redirect::to(route('user.wallet'))->with('error',$request->message);
          }
        return $this->successResponse($request->getTransactionReference());

    }


    public function completeOrderSubs(Request $request)
    {
      $user = auth()->user();
      $data = Payment::where('transaction_id',$request->merchant_reference)->first();
      if(isset($request->merchant_reference) && $request->code=='00' && $request->status == 'success')
          {
            $subscription = explode('_',$request->merchant_reference);
            $request->request->add(['user_id' => $user->id, 'payment_option_id' => 20, 'amount' => $data->balance_transaction, 'transaction_id' => $request->merchant_reference]);
            $subscriptionController = new UserSubscriptionController();
            $subscriptionController->purchaseSubscriptionPlan($request, '', $subscription[2]);
            return Redirect::to(route('user.subscription.plans'))->with('error',$request->message);
          }else{
            $data->delete();
            return Redirect::to(route('user.subscription.plans'))->with('error',$request->message);
          }
        return $this->successResponse($request->getTransactionReference());

    }

    public function completeOrderTip(Request $request)
    {
      $data = Payment::where('transaction_id',$request->merchant_reference)->first();
      if(isset($request->merchant_reference) && $request->code=='00' && $request->status == 'success')
          {
            $order_number = explode('_',$request->merchant_reference);
            $request->request->add(['user_id' => auth()->id(), 'order_number' => $order_number[2], 'tip_amount' => $data->balance_transaction, 'transaction_id' => $request->merchant_reference]);
            $orderController = new OrderController();
            $orderController->tipAfterOrder($request);

            return Redirect::to(route('user.orders'))->with('success', $request->message);
          }else{
            $data->delete();
            return Redirect::to(route('user.orders'))->with('error', $request->message);
          }
        return $this->successResponse($request->getTransactionReference());

    }

}
