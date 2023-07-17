<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use App\Models\PaymentOption;
use Illuminate\Http\Request;
use Auth;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\Mpesa;
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
use Illuminate\Support\Str;
use App\Http\Traits\OrderTrait;

class MpesaSafariController extends Controller
{
    use ApiResponser,OrderTrait, Mpesa;
    
    //Initiate STK Push
    
    public function orderNumber($request)
    {
        $time = time();
        $user_id = auth()->id();
        $amount = $request->amt??$request->amount;
        if ($request->payment_from == 'cart') {
            $time = $request->order_number;
            Payment::create([
                'amount' => 0,
                'transaction_id' => $time,
                'balance_transaction' => $amount,
                'type' => 'cart',
                'date' => date('Y-m-d'),
                'user_id' => $user_id,
                'payment_from'=>$request->action??'web'
            ]);
        } elseif ($request->payment_from == 'wallet') {
            $time = $request->transaction_id ?? time();
            Payment::create([
                'amount' => 0,
                'transaction_id' => $time,
                'balance_transaction' => $amount,
                'type' => 'wallet',
                'date' => date('Y-m-d'),
                'user_id' => $user_id,
                'payment_from'=>$request->action??'web'
                
            ]);
        } elseif ($request->payment_from == 'tip') {
            $time = $request->order_number. time();
            Payment::create([
                'amount' => 0,
                'transaction_id' => $time,
                'balance_transaction' => $amount,
                'type' => 'tip',
                'date' => date('Y-m-d'),
                'user_id' => $user_id,
                'payment_from'=>$request->action??'web'
                
            ]);
        } elseif ($request->payment_from == 'subscription') {
            $time = $request->subscription_id?$request->subscription_id: time();
            Payment::create([
                'amount' => 0,
                'transaction_id' => $time,
                'balance_transaction' => $amount,
                'type' => 'subscription',
                'date' => date('Y-m-d'),
                'user_id' => $user_id,
                'payment_from'=>$request->action??'web'
                
            ]);
        } else if ($request->payment_from == 'pickup_delivery') {
            $time = $request->order_id??$request->order_number;
            Payment::create([
                'amount' => 0,
                'transaction_id' => $time,
                'balance_transaction' => $amount,
                'type' => 'pickup_delivery',
                'date' => date('Y-m-d'),
                'user_id' => $user_id,
                'payment_from'=>$request->action??'web'
                
            ]);
        }
        return $time;
    }
    
    public function createPayment(Request $request)
    {
        dd($this->lnmoQuery());
        $amount = $request->amt??$request->amount;
        $accountReference=$this->orderNumber($request);
        $phone = $this->formatPhone(auth()->user()->phone_number);
        $response = $this->express($amount,$phone,$accountReference,'Payment');
        $response = json_decode($response);
        \Log::info(json_encode($response));
        if(isset($response->ResponseCode)){
            if($response->ResponseCode == 0){
                $payment = Payment::where('transaction_id',$accountReference)->first();
                $payment->viva_order_id = $response->CheckoutRequestID;
                $payment->save();
                if($payment->type == 'cart'){
                    \Session::flash('success', 'Order placed successfully.');
                    $route = route('order.success',['order_id' => $request->order_id]);
                } elseif($payment->type == 'wallet'){
                    \Session::flash('success', 'Wallet amount updated soon.');
                    $route = route('user.wallet');
                } elseif($payment->type == 'tip'){
                    \Session::flash('success', 'Tip amount updated soon.');
                    $route = route('user.orders');
                } elseif($payment->type == 'subscription'){
                    \Session::flash('success', 'Subscription updated soon.');
                    $route = route('user.subscription.plans');
                } elseif ($payment->type == 'pickup_delivery') {
                    \Session::flash('success', 'Subscription updated soon.');
                    $route = route('front.booking.details',$request->order_number);
                }
                $resp['status']         = 'Success';
                $resp['msg']            = $response->ResponseDescription;
                $resp['payment_from']   = $request->payment_from;
                $resp['route']          = $route;
                return $resp;
            }else{
                return $this->errorResponse($response->ResponseDescription, 400);
            }
        }
        if(isset($response->errorCode)){
            return  $this->errorResponse($response->errorMessage, 400);
        }
        return $this->errorResponse('Server Error', 400);
    }
    
    public function formatPhone($phone)
    {
        $phone = 'hfhsgdgs' . $phone;
        $phone = str_replace('hfhsgdgs0', '', $phone);
        $phone = str_replace('hfhsgdgs', '', $phone);
        $phone = str_replace('+', '', $phone);
        if (strlen($phone) == 9) {
            $phone = '254' . $phone;
        }
        return $phone;
    }

    public function successPage(Request $request)
    { 
         if (isset($request->Body) && isset($request->Body['stkCallback']) && isset($request->Body['stkCallback']['ResultCode']) && $request->Body['stkCallback']['ResultCode'] == 0) {
             $payment = Payment::where('viva_order_id', $request->Body['stkCallback']['CheckoutRequestID'])->first();
                if ($payment->type == 'cart') {
                    return $this->completeOrderCart($request, $payment);
                } elseif ($payment->type == 'wallet') {
                    return $this->completeOrderWallet($request, $payment, $request->amount);
                } elseif ($payment->type == 'tip') {
                    return $this->completeOrderTip($request, $payment);
                } elseif ($payment->type == 'subscription') {
                    return $this->completeOrderSubs($request, $payment, $request);
                } elseif ($payment->type == 'pickup_delivery') {
                    return $this->completePickupDelivery($request, $payment, $request);
                }
         }else{
             
         }
    }
    
    public function completeOrderCart($request, $payment)
    {
        $order = Order::where('order_number', $payment->transaction_id)->first();
        if (!empty($order))
        {
            $order->payment_status = '1';
            $order->save();
            $this->orderSuccessCartDetail($order);
            if($payment->payment_from != 'app'){
                $returnUrl = route('order.success',[$order->id]);
                return redirect($returnUrl);
            }else{
                $returnUrl = route('payment.gateway.return.response').'/?gateway=mpesasafari'.'&status=200&order='.$order->order_number;
                return redirect($returnUrl);
            }
        } 
    }
    
    
    public function completeOrderWallet($request, $payment)
    {
        //if (isset($request) && ($request->get('statusId') == '2')){
            $user = User::findOrFail($payment->user_id);
            Auth::login($user);
            $wallet = $user->wallet;
            $wallet->depositFloat($payment->balance_transaction, ['Wallet has been <b>credited</b> for order number <b>' . $payment->transaction_id . '</b>']);
            if ($payment->payment_from == 'app') {
                $returnUrl = route('payment.gateway.return.response').'/?gateway=mpesasafari'.'&status=200&transaction_id='.$request->order_id.'&action=wallet';
                return redirect($returnUrl);
            }else{
                return redirect(route('user.wallet'))->with('success', 'Wallet amount added successfully.');
            }
//         }else{
//             if ($payment->payment_from == 'app') {
//                 $returnUrl = route('payment.gateway.return.response').'/?gateway=mpesasafari'.'&status=200&transaction_id='.$request->order_id.'&action=wallet';
//                 return redirect($returnUrl);
//             }else{
//                 return redirect(route('user.wallet'))->with('error', 'Amount Failed.');
//             }
//         }
    }
    
    public function completeOrderTip($request, $payment)
    {
       // if (isset($request) && ($request->get('statusId') == '2')){
            $data['tip_amount'] = $request->amount;
            $data['order_number'] = $request->order_number;
            $data['transaction_id'] = $payment->transaction_id;
            $request = new \Illuminate\Http\Request($data);
            $orderController = new OrderController();
            $orderController->tipAfterOrder($request);
            if ($payment->payment_from == 'app')
            {
                $returnUrl = route('payment.gateway.return.response').'/?gateway=mpesasafari'.'&status=200&order='.$request->order_id.'&action=tip';
                return redirect($returnUrl);
            }else{
                return redirect(route('user.orders'))->with('success', 'Tip given successfully.');
            }
//         }else{
//             if ($payment->payment_from == 'app') {
//                 $returnUrl = route('payment.gateway.return.response').'/?gateway=mpesasafari'.'&status=200&transaction_id='.$request->order_id.'&action=tip';
//                 return redirect($returnUrl);
//             }else{
//                 return redirect(route('user.orders'))->with('error', 'Failed.');
//             }
//         }
    }
    
    
    public function completeOrderSubs(Request $request,$payment)
    {
        //if (isset($request) && ($request->get('statusId') == '2')){
            $subscription = explode('_',$payment->transaction_id);
            $request->request->add(['user_id' => $payment->user_id, 'payment_option_id' => 60, 'amount' => $payment->balance_transaction, 'transaction_id' => $request->transId]);
            $subscriptionController = new UserSubscriptionController();
            $subscriptionController->purchaseSubscriptionPlan($request, '', $subscription[0]);
            if(isset($payment->payment_from) && $payment->payment_from=='app')
            {
                $returnUrl = route('payment.gateway.return.response').'/?gateway=mpesasafari'.'&status=200&transaction_id='.$request->transId.'&action=subscription';
                return Redirect::to($returnUrl);
            }else{
                return Redirect::to(route('user.subscription.plans'))->with('error',$request->message);
            }
//         }else{
//             $payment->delete();
//             if(isset($payment->payment_from) && $payment->payment_from=='app')
//             {
//                 $returnUrl = route('payment.gateway.return.response').'/?gateway=mpesasafari'.'&status=00&transaction_id='.$request->transId.'&action=subscription';
//                 return Redirect::to($returnUrl);
//             }else{
//                 return Redirect::to(route('user.subscription.plans'))->with('error',$request->message);
//             }
//         }        
    }
    
    public function completePickupDelivery($request, $payment)
    {
        $order = Order::where('order_number', $payment->transaction_id)->first();
       // if (isset($request) && ($request->get('statusId') == '2')){
            $request->request->add(['order_number'=> $order->order_number, 'payment_option_id' => 60, 'amount' => $order->payable_amount, 'transaction_id' => $request->TransID]);
            $plaseOrderForPickup = new PickupDeliveryController();
            $res = $plaseOrderForPickup->orderUpdateAfterPaymentPickupDelivery($request);
            
            if($payment->payment_from=='app')
            {
                $returnUrl = route('payment.gateway.return.response').'/?gateway=mpesasafari'.'&status=200&order='.$payment->transaction_id;
                return Redirect::to($returnUrl);
            }else{
                return Redirect::to(route('front.booking.details',$order->order_number));
            }
//         }else{
//             $data = Payment::where('transaction_id',$payment->transaction_id)->first();
//             $data->delete();
//             return Redirect::to(route('front.booking.details'))->with('error',$request->message);
//         }
    }
  }