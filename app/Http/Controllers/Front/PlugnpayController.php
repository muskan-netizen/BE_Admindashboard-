<?php
namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Front\{FrontController, OrderController, PickupDeliveryController};
use App\Http\Traits\ApiResponser;
use App\Http\Traits\PlugnpaypaymentManager;
use App\Models\CaregoryKycDoc;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\CartAddon;
use App\Models\CartCoupon;
use App\Models\CartProduct;
use App\Models\CartProductPrescription;
use App\Models\Order;
use App\Models\User;
use App\Models\UserVendor;
use Illuminate\Support\Facades\Redirect;

class PlugnpayController extends FrontController
{
    use PlugnpaypaymentManager;
    use ApiResponser;

    public function orderNumber($request)
   {
        $time = time();
        if($request->from == 'cart')
        {
            $time = $request->order_number;
            Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$request->amt,'type'=>'cart','date'=>date('Y-m-d'),'user_id'=>auth()->user()->id]);

        }elseif($request->from == 'wallet')
        {
            $time = $request->transaction_id??time();
            Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$request->amt,'type'=>'wallet','date'=>date('Y-m-d'),'user_id'=>auth()->id()]);

        }elseif($request->from == 'tip')
        {
             $time = time();
             Payment::create(['amount'=>0,'transaction_id'=>$request->order_number.'_'.$time,'balance_transaction'=>$request->amt,'type'=>'tip','date'=>date('Y-m-d'),'user_id'=>auth()->id()]);

        }elseif($request->from == 'subscription')
        {
            $time = time();
            Payment::create(['amount'=>0,'transaction_id'=>$request->subsid.'_'.$time,'balance_transaction'=>$request->amt,'type'=>'subscription','date'=>date('Y-m-d'),'user_id'=>auth()->id()]);
            
        }
        return $time;
   }

    public function beforePayment(Request $request)
    {
        $number =  $this->orderNumber($request);
    	$response = $this->createPaymentRequest($request->all());
        $dataResponse = json_decode($response);
        \Log::info($dataResponse->FinalStatus);

        if(isset($dataResponse->FinalStatus) && $dataResponse->FinalStatus == 'success')
        {           
        \Log::info('Done');

            $payment = Payment::where('transaction_id',$dataResponse->address2)->first();
            if($payment->type=='cart'){
            return $this->completeOrderCart($dataResponse,$payment);
            }elseif($payment->type=='wallet'){
                return $this->completeOrderWallet($dataResponse,$payment);
            }elseif($payment->type=='tip'){
                return $this->completeOrderTip($dataResponse,$payment);
            }elseif($payment->type=='subscription'){
                return $this->completeOrderSubs($dataResponse,$payment);
            }

        }else{
            \Log::info('fail--'.$dataResponse->FinalStatus.'--');
            return response($dataResponse->FinalStatus. ', Somthing went wrong.',400);
            // return Redirect::to(route('showCart'))->with('error',$request->FinalStatus. ', Somthing went wrong.');
        }


    }


    public function completeOrderCart($request,$payment)
    {
      $order = Order::where('order_number',$payment->transaction_id)->first();
          if(isset($request->FinalStatus) && $request->FinalStatus == 'success')
          {
            $order->payment_status = '1';
            $order->order_id = $order->id;
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
            CaregoryKycDoc::where('cart_id',$cartid)->update(['ordre_id'=> $order->id,'cart_id'=>'' ]);
            CartAddon::where('cart_id', $cartid)->delete();
            CartCoupon::where('cart_id', $cartid)->delete();
            CartProduct::where('cart_id', $cartid)->delete();
            CartProductPrescription::where('cart_id', $cartid)->delete();
            
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

          if(isset($request->auth_token) && !empty($request->auth_token))
          {
           // $returnUrl = route('payment.gateway.return.response').'/?gateway=mvodafone'.'&status=200&order='.$order->order_number;
            return response('Done',200);
          }else{
            return response('Done',200);
          }

          }else{
            $user = auth()->user();
            $wallet = $user->wallet;
            if(isset($order->wallet_amount_used)){
              $wallet->depositFloat($order->wallet_amount_used, ['Wallet has been <b>refunded</b> for cancellation of order #'. $order->order_number]);
            }
            if(isset($request->auth_token) && !empty($request->auth_token))
            {
              $returnUrl = route('payment.gateway.return.response').'/?gateway=mvodafone'.'&status=00&order='.$order->order_number;
            //  return Redirect::to($returnUrl);  
            return response('Somthing went wrong.',400);

            }else{
            return response('Somthing went wrong.',400);

              //return Redirect::to(route('showCart'))->with('error',$request->message);
            }

          }

        return $this->successResponse($request->getTransactionReference());

    }



}
