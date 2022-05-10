<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;

use Auth;
use App\Models\User;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\CartAddon;
use App\Models\UserVendor;
use App\Models\CartCoupon;
use App\Models\UserAddress;
use App\Models\CartProduct;
use Illuminate\Http\Request;
use App\Models\PaymentOption;
use App\Models\CaregoryKycDoc;
use Illuminate\Support\Carbon;
use App\Http\Traits\ApiResponser;
use App\Models\CartProductPrescription;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Redirect;
use Log;

class WindcaveController extends FrontController
{
    use ApiResponser;

    private $appId;
    private $app_url;
    private $app_key;
    private $token;

    public function __construct()
    {
        $payOpt = PaymentOption::select('credentials', 'test_mode', 'status')->where('code', 'windcave')->where('status', 1)->first();
        $json = json_decode($payOpt->credentials);
        $this->appId = $json->app_id;
        $this->app_key = $json->api_key;
        $this->token = base64_encode($json->appId.':'.$this->app_key);
        if ($payOpt->test_mode == '1') {
            $this->app_url = 'https://sec.windcave.com/api/v1/sessions';
        } else {
            $this->app_url = 'https://sec.windcave.com/api/v1/sessions';
        }
    }

    public function orderNumber($request)
    {
        $time = '';
        $amt = $request->amt??$request->amount;
       if(isset($request->auth_token) && !empty($request->auth_token)){
         $user = User::where('auth_token', $request->auth_token)->first();
         FacadesAuth::login($user);
       }else{
         $user = auth()->user();
       }
        $name = explode(' ',$user->name);
        $returnUrl = '';
        if($request->from == 'cart')
        {
         $request->amt = $amt;
         $time = $request->order_number;
         Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$amt,'type'=>'cart','date'=>date('Y-m-d')]);
   
        }elseif($request->from == 'wallet')
        {
         $time = ($request->transaction_id)??'W_'.time();
         //Save transaction before payment success for get information only
         Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$amt,'type'=>'wallet','date'=>date('Y-m-d')]);
         $request->amt = $amt;
   
        }elseif($request->from == 'tip')
        {
         $time = 'T_'.time().'_'.$request->order_number;
         Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$amt,'type'=>'tip','date'=>date('Y-m-d')]);
        
         $request->amt = $amt;
         
        }elseif($request->from == 'subscription')
        {
         $time = ($request->subscription_id)??'S_'.time().'_'.$request->subsid;
         Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$amt,'type'=>'subscription','date'=>date('Y-m-d')]);
   
         $request->amt = $amt;
        }

        return $time;
    }

    public function createHash(Request $request)
    {
        $order_number =  $this->orderNumber($request);
        $data = array(
            "type" => 'purchase',
            "amount" => $request->amt,
            "currency" => 'USD',
            "merchantReference" => $order_number,
            "storeCard" => true,
            "storedCardIndicator" => 'credentialonfileinitial',
            "callbackUrls" => ["approved"=> route('windcave.success'), "declined"=> route('windcave.success'), "cancelled"=> route('windcave.success') ],
            "notificationUrl" => route('windcave.success')
        );
        $url = $this->postCurl($data,$this->token);
        return json_encode($url->links[1]);
    }


    private function postCurl($data,$token=null):object{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->app_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
        $headers = array();
        $headers[] = 'Accept: */*';
        if(!is_null($token)){

           $headers[] = "Authorization: Basic ${token}";
            // dd( $headers);
        }
      $headers[] = 'Content-Type: application/json';
         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            // echo 'Error:' . curl_error($ch);
            \Log::info(curl_error($ch));
        }
        curl_close($ch);
        return json_decode($result); 
    }


    public function successPage(Request $request)
    {   
        $payment = Payment::where('transaction_id',$request->orderRefNumber)->first();
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



    public function completeOrderCart($request)
    {
        $order = Order::where('order_number', $request->orderRefNumber)->first();
        if (isset($request->status) && $request->status == '0000') {
            //Success from cart
            $order->payment_status = '1';
            $order->save();
            // Auto accept order
            $orderController = new OrderController();
            $orderController->autoAcceptOrderIfOn($order->id);
            $cart = Cart::where('user_id', auth()->id())->select('id')->first();
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
            Payment::create(['amount' => 0, 'transaction_id' => $request->orderRefNumber, 'balance_transaction' => $order->payable_amount, 'type' => 'cart', 'date' => date('Y-m-d'), 'order_id' => $order->id]);

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

            if (isset($request->auth) && $request->auth != '') {
                $returnUrl = route('payment.gateway.return.response') . '/?gateway=easypaisa' . '&status=200&order=' . $order->order_number;
                return Redirect::to($returnUrl);
            } else {
                return Redirect::to(route('order.success', [$order->id]));
            }
        } else {

            //Failed from cart
            $user = auth()->user();
            $wallet = $user->wallet;
            if (isset($order->wallet_amount_used)) {
                $wallet->depositFloat($order->wallet_amount_used, ['Wallet has been <b>refunded</b> for cancellation of order #' . $order->order_number]);
            }
            if (isset($request->auth) && $request->auth != '') {
                $returnUrl = route('payment.gateway.return.response') . '/?gateway=easypaisa' . '&status=00&order=' . $order->order_number;
                return Redirect::to($returnUrl);
            } else {
                return Redirect::to(route('showCart'))->with('error', $request->message);
            }
        }
    }


    public function completeOrderWallet($request)
    {
        if (isset($request->status) && $request->status == '0000') {
            $data = Payment::where('transaction_id', $request->orderRefNumber)->first();
            $user = auth()->user();
            $wallet = $user->wallet;
            $wallet->depositFloat($data->balance_transaction, ['Wallet has been <b>credited</b> for order number <b>' . $request->order_id . '</b>']);

            if (isset($request->auth) && $request->auth != '') {
                $returnUrl = route('payment.gateway.return.response') . '/?gateway=easypaisa' . '&status=200&transaction_id=' . $request->orderRefNumber . '&action=wallet';
                return Redirect::to($returnUrl);
            } else {
                return Redirect::to(route('user.wallet'));
            }
        } else {
            $data = Payment::where('transaction_id', $request->orderRefNumber)->first();
            $data->delete();

            if (isset($request->auth) && $request->auth != '') {
                $returnUrl = route('payment.gateway.return.response') . '/?gateway=easypaisa' . '&status=00&transaction_id=' . $request->orderRefNumber . '&action=wallet';
                return Redirect::to($returnUrl)->with('success','Wallet amount added successfuly.');
            } else {
                return Redirect::to(route('user.wallet'))->with('error', $request->message);
            }
        }
        return $this->successResponse($request->getTransactionReference());
    }


    public function completeOrderSubs($request)
    {
        $user = auth()->user();
        $data = Payment::where('transaction_id', $request->orderRefNumber)->first();
        if (isset($request->status) && $request->status == '0000') {
            $subscription = explode('_', $request->orderRefNumber);
            $request->request->add(['user_id' => $user->id, 'payment_option_id' =>23, 'amount' => $data->balance_transaction, 'transaction_id' => $request->orderRefNumber]);
            $subscriptionController = new UserSubscriptionController();
            $subscriptionController->purchaseSubscriptionPlan($request, '', $subscription[2]);

            if (isset($request->auth) && $request->auth != '') {
                $returnUrl = route('payment.gateway.return.response') . '/?gateway=easypaisa' . '&status=200&transaction_id=' . $request->orderRefNumber . '&action=subscription';
                return Redirect::to($returnUrl)->with('success','Subscription added successfuly.');;
            } else {
                return Redirect::to(route('user.subscription.plans'))->with('error', $request->message);
            }
        } else {
            $data->delete();

            if (isset($request->auth) && $request->auth != '') {
                $returnUrl = route('payment.gateway.return.response') . '/?gateway=easypaisa' . '&status=00&transaction_id=' . $request->order_id . '&action=subscription';
                return Redirect::to($returnUrl);
            } else {
                return Redirect::to(route('user.subscription.plans'))->with('error', $request->message);
            }
        }
        return $this->successResponse($request->getTransactionReference());
    }

    public function completeOrderTip($request)
    {
        $data = Payment::where('transaction_id', $request->orderRefNumber)->first();
        if (isset($request->status) && $request->status == '0000') {
            $order_number = explode('_', $request->orderRefNumber);
            $request->request->add(['user_id' => auth()->id(), 'order_number' => $order_number[2], 'tip_amount' => $data->balance_transaction, 'transaction_id' => $request->orderRefNumber]);
            $orderController = new OrderController();
            $orderController->tipAfterOrder($request);

            if (isset($request->auth) && $request->auth != '') {
                $returnUrl = route('payment.gateway.return.response') . '/?gateway=easypaisa' . '&status=200&order=' . $order_number[2] . '&action=tip';
                return Redirect::to($returnUrl)->with('success','Tip amount added successfuly.');;
            } else {
                return Redirect::to(route('user.orders'))->with('success', $request->message);
            }
        } else {
            $data->delete();

            if (isset($request->auth) && $request->auth != '') {
                $returnUrl = route('payment.gateway.return.response') . '/?gateway=easypaisa' . '&status=00&transaction_id=' . $request->order_id . '&action=tip';
                return Redirect::to($returnUrl);
            } else {
                return Redirect::to(route('user.orders'))->with('error', $request->message);
            }
        }
        return $this->successResponse($request->getTransactionReference());
    }


}
