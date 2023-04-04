<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use App\Models\PaymentOption;
use Illuminate\Http\Request;
use Auth;
use App\Http\Traits\{ApiResponser,NmiPaymentTrait,OrderTrait};
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
   use ApiResponser,NmiPaymentTrait,OrderTrait;

   private $merchant_key;
   private $merchant_id;
   private $url;
   public $user;
   public $domain;
   public $ip;

   public function __construct()
   {
      $payOpt = PaymentOption::select('credentials', 'test_mode','status')->where('code', 'nmi')->where('status', 1)->first();
      $json = json_decode($payOpt->credentials);
      $this->merchant_id = $json->nmi_client_id;
      $this->merchant_key = $json->nmi_key_id;
      $this->url = "https://secure.nmi.com/api/transact.php"; 
      $this->setLogin($this->merchant_key);
      $this->domain = request()->getHttpHost();
      $this->domain = request()->ip();
   }

   
   public function orderNumber($request)
   {
       $time = time();
       $user_id = auth()->id();

       if ($request->from == 'cart') {
           $time = $request->order_number;
           Payment::create([
               'amount' => 0,
               'transaction_id' => $time,
               'balance_transaction' => $request->amt,
               'type' => 'cart',
               'date' => date('Y-m-d'),
               'user_id' => $user_id
           ]);
       } elseif ($request->from == 'wallet') {
           $time = $request->transaction_id ?? time();
           Payment::create([
               'amount' => 0,
               'transaction_id' => $time,
               'balance_transaction' => $request->amt,
               'type' => 'wallet',
               'date' => date('Y-m-d'),
               'user_id' => $user_id
           ]);
       } elseif ($request->from == 'tip') {
           $time = time();
           Payment::create([
               'amount' => 0,
               'transaction_id' => $request->order_number . '_' . $time,
               'balance_transaction' => $request->amt,
               'type' => 'tip',
               'date' => date('Y-m-d'),
               'user_id' => $user_id
           ]);
       } elseif ($request->from == 'subscription') {
           $time = time();
           Payment::create([
               'amount' => 0,
               'transaction_id' => $request->subsid . '_' . $time,
               'balance_transaction' => $request->amt,
               'type' => 'subscription',
               'date' => date('Y-m-d'),
               'user_id' => $user_id
           ]);
       } else if ($request->from == 'pickup_delivery') {
           $time = $request->order_number;
           Payment::create([
               'amount' => 0,
               'transaction_id' => $time,
               'balance_transaction' => $request->amt,
               'type' => 'pickup_delivery',
               'date' => date('Y-m-d'),
               'user_id' => $user_id
           ]);
       }
       return $time;
   }

   public function beforePayment(Request $request)
   {

       $response = [];
       $user = auth()->user();
       $number = $this->orderNumber($request);

       if ($request->from == 'wallet') {
           $request->request->add([
               'order_number' => $number,
               'amount' => $request->amount
           ]);
       }
       if ($request->from == 'subscription') {
           $request->request->add([
               'order_number' => $number,
               'amount' => $request->amount
           ]);
       }

       $expDate = substr($request->dt,0,2).'/'.substr($request->dt,-2);

       $address = UserAddress::where('is_primary','1')->first();

       $this->setBilling($user->name,$user->name,$user->name,$address->address,$address->address,$address->city, $address->state,$address->pincode,$address->country,$user->phone_number,$user->phone_number,$user->email,$this->domain);

       $this->setShipping($user->name,$user->name,$user->name,$address->address,$address->address,$address->city, $address->state,$address->pincode,$address->country,$user->email);
       $this->setOrder($number,"Royo Order",0, 0,$user->phone_number,$this->ip);
       $dataResponse = $this->doSale($request->amount??$request->amt,$request->cno,$expDate);

       if ($dataResponse['response'] == 3) {
           $response['status'] = 'Fail';
           $response['msg'] = $dataResponse['responsetext'];
           $response['payment_from'] = $request->from;
           $response['route'] = '';
           return $response;
       }
       if (isset($dataResponse['response']) && $dataResponse['response'] == 1) {

           if ($request->from == 'tip') {
               $payment = Payment::where('transaction_id', $request->order_number . '_' . $number)->first();
           } else if ($request->from == 'subscription') {
               $payment = Payment::where('transaction_id', $request->subsid . '_' . $number)->first();
           } else {
               $payment = Payment::where('transaction_id', $request->order_number)->first();
           }

           if ($payment) {
               $payment->viva_order_id = $dataResponse['transactionid'];
               $payment->save();
           }

           if ($payment->type == 'cart') {
               return $this->completeOrderCart($dataResponse, $payment);
           } elseif ($payment->type == 'wallet') {
               return $this->completeOrderWallet($dataResponse, $payment, $request->amount);
           } elseif ($payment->type == 'tip') {
               return $this->completeOrderTip($dataResponse, $payment, $request->amount, $request);
           } elseif ($payment->type == 'subscription') {
               return $this->completeOrderSubs($dataResponse, $payment, $request);
           } elseif ($payment->type == 'pickup_delivery') {
               return $this->completePickupDelivery($dataResponse, $payment, $request);
           }
       } else {

           $returnUrl = route('order.return.success');
           $response['status'] = 'Fail';
           $response['msg'] = $dataResponse['responsetext'];
           $response['payment_from'] = 'cart';
           $response['route'] = $returnUrl;
           return $response;
       }
   }


   public function completeOrderCart($request, $payment)
   {
      $order = Order::where('order_number', $payment->transaction_id)->first();
      if (isset($request['response']) && $request['response'] == 1) 
      {
               $order->payment_status = '1';
               $order->save();

               $this->orderSuccessCartDetail($order);
               if($payment->payment_from == 'web'){
                       $returnUrl = route('order.success',[$order->id]);
                       $response['status'] = 'Success';
                       $response['msg'] = 'Success Order.';
                       $response['payment_from'] = 'cart';
                       $response['route'] = $returnUrl;
                       
               return $response;

               }else{
                   $returnUrl = route('payment.gateway.return.response').'/?gateway=skip_cash'.'&status=00&order='.$order->order_number;
                   return Redirect::to($returnUrl);  
               }
               

      } else {

               $this->failedOrderWalletRefund($order);

               if (isset($request->auth_token) && ! empty($request->auth_token)) {
                   $returnUrl = route('order.return.success');
                   $response['status'] = 'Fail';
                   $response['msg'] = 'Failed Order.';
                   $response['payment_from'] = 'cart';
                   $response['route'] = $returnUrl;

                   return $response;
               } else {

                   $returnUrl = route('order.return.success');
                   $response['status'] = 'Fail';
                   $response['msg'] = 'Failed Order.';
                   $response['payment_from'] = 'cart';
                   $response['route'] = $returnUrl;

                   return $response;
               }
      }
  }

  public function completeOrderWallet($request, $payment)
  {
      if (isset($request['response']) && $request['response'] == 1) {
           $user = auth()->user();
           $wallet = $user->wallet;
           $wallet->depositFloat($payment->balance_transaction, ['Wallet has been <b>credited</b> for order number <b>' . $payment->transaction_id . '</b>']);

          if ($payment->payment_from == 'app') {
              $returnUrl = route('payment.gateway.return.response') . '/?gateway=nmi' . '&status=200&transaction_id=' . $payment->transaction_id;
              $response['route'] = $returnUrl;
          } else {
              $returnUrl = route('user.wallet');
              $response['route'] = $returnUrl;
          }
          return $response;
      }
  }

  public function completeOrderTip($request, $payment,$amount,$requestdata)
  {
      if (isset($request['response']) && $request['response'] == 1) {
          $data['tip_amount'] = $amount;
          $data['order_number'] = $requestdata->order_number;
          $data['transaction_id'] = $payment->transaction_id;

          $request = new \Illuminate\Http\Request($data);

          $orderController = new OrderController();
          $orderController->tipAfterOrder($request);
          if ($request['from'] == 'app') {
              $returnUrl = route('payment.gateway.return.response') . '/?gateway=nmi' . '&status=200&transaction_id=' . $payment->transaction_id;
              $response['route'] = $returnUrl;
          } else {
              $returnUrl = route('user.orders');
              $response['route'] = $returnUrl;
          }
          return $response;
      }
  }

  public function completeOrderSubs($request, $payment, $requestdata)
  {
      if (isset($request['response']) && $request['response'] == 1) {

          $data['transaction_id'] = $payment->transaction_id;
          $data['payment_option_id'] = 50;
          $data['subsid'] = $requestdata['subsid'];
          $data['subscription_id'] = $requestdata['subsid'];
          $data['amount'] = $requestdata['amt'];

          $request = new \Illuminate\Http\Request($data);

          $subscriptionController = new UserSubscriptionController();
          $subscriptionController->purchaseSubscriptionPlan($request, '', $requestdata->subsid);


          if ($request['from'] == 'app') {
              $returnUrl = route('payment.gateway.return.response') . '/?gateway=nmi' . '&status=200&transaction_id=' . $payment->transaction_id;
              $response['route'] = $returnUrl;
          } else {
              $returnUrl = route('user.subscription.plans');
              $response['route'] = $returnUrl;
          }
          return $response;
      }
  }

  public function completePickupDelivery($request, $payment, $requestdata)
  {
      if (isset($request['response']) && $request['response'] == 1) {

          $data['payment_option_id'] = 53;
          $data['transaction_id'] = $payment->transaction_id;
          $data['amount'] = $requestdata['amt'];
          $data['order_number'] = $requestdata['order_number'];
          $data['reload_route'] = $requestdata['reload_route'];
          $request = new \Illuminate\Http\Request($data);
          $plaseOrderForPickup = new PickupDeliveryController();
          $res = $plaseOrderForPickup->orderUpdateAfterPaymentPickupDelivery($request);
          $returnUrl = $request->reload_route;
          $response['route'] = $returnUrl;
          if ($request->come_from == 'app') {
              $returnUrl = route('payment.gateway.return.response') . '/?gateway=nmi' . '&status=200&transaction_id=' . $payment->transaction_id;
              $response['route'] = $returnUrl;
          }

          return $response;
      }
  }


}
