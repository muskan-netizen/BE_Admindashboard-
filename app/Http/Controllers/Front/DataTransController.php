<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\WalletController;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartAddon;
use App\Models\CartCoupon;
use App\Models\CartDeliveryFee;
use App\Models\CartProduct;
use App\Models\CartProductPrescription;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentOption;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Http\Traits\DataTransTrait;
use App\Models\CaregoryKycDoc;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class DataTransController extends Controller
{
    use DataTransTrait;
    public function payByDataTrans(Request $request)
    {
        $data = $request->all();
        $user = auth()->user();
        $data['come_from'] = 'app';

        $amt = $request->amt ?? $request->total_amount;
        $order = Order::where(['order_number' => $request->order_number])->first();

        if ($request->isMethod('post')) {
                $data['come_from'] = 'web';
                $time = '';
            if (isset($request->auth_token) && ! empty($request->auth_token)) {
                $user = User::where('auth_token', $request->auth_token)->first();
                Auth::login($user);
            } else {
                $user = auth()->user();
            }         
        }

        $order = Order::where('order_number', $request->order_number)->first();

        $response = $this->dataTransApi($request);
        
        if($request->payment_from == 'cart')
        {
            $data = [
                'amount' => $amt,
                'payment_option_id' => 55,
                'transaction_id' => $response['transactionId'],
                'balance_transaction' => $amt,
                'order_id' => $order->id ?? '',
                'type' => $request->payment_from,
                'date' => date('Y-m-d'),
                'user_id' => $user->id
            ];
        }elseif($request->payment_from == 'subscription')
        {
            $data = [
                'amount' => $amt,
                'payment_option_id' => 55,
                'transaction_id' => $response['transactionId'],
                'balance_transaction' => $amt,
                'viva_order_id' => $request->subscription_id,
                'type' => $request->payment_from,
                'date' => date('Y-m-d'),
                'user_id' => $user->id,
            ];
        }
        else{
            $data = [
                'amount' => $amt,
                'payment_option_id' => 55,
                'transaction_id' => $response['transactionId'],
                'balance_transaction' => $amt,
                'viva_order_id' => $request->order_number ?? '',
                'type' => $request->payment_from,
                'date' => date('Y-m-d'),
                'user_id' => $user->id
            ];
        }
        Payment::create($data);

        return $response;
    }
 
    public function successPage(Request $request)
    {
        $payment = Payment::where('transaction_id', $request->get('datatransTrxId'))->first();
            if ($payment->type == 'cart') {
               return $this->completeOrderCart($request, $payment);
            } elseif ($payment->type == 'wallet') {
                \Log::info("Wallet type");
               return $this->completeOrderWallet($request, $payment);
            } elseif ($payment->type == 'tip') {
                $order = Order::find($payment->order_id);
                return $this->completeOrderTip($request, $payment);
            } elseif ($payment->type == 'subscription') {
                return $this->completeOrderSubs($request, $payment);
            } elseif ($payment->type == 'pickup_delivery') {
                return $this->completeOrderPickup($request, $payment);
            }   

    }

    public function completeOrderCart(Request $request, $pay)
    {
        $order = Order::where('id', $pay->order_id)->first();
        // dd($order);
        if (! empty($order)) {
            $order->payment_status = '1';
            $order->save();

            // Auto accept order
            $orderController = new OrderController();
            $orderController->autoAcceptOrderIfOn($order->id);

            $cart = Cart::where('user_id', auth()->id())->select('id')->first();
            $cartid = $cart->id;
            Cart::where('id', $cartid)->update([
                'schedule_type' => null,
                'scheduled_date_time' => null,
                'comment_for_pickup_driver' => null,
                'comment_for_dropoff_driver' => null,
                'comment_for_vendor' => null,
                // 'schedule_porder_numberickup' => null,
                'schedule_dropoff' => null,
                'specific_instructions' => null
            ]);
            CaregoryKycDoc::where('cart_id', $cartid)->update([
                'ordre_id' => $order->id,
                'cart_id' => ''
            ]);
            CartAddon::where('cart_id', $cartid)->delete();
            CartCoupon::where('cart_id', $cartid)->delete();
            CartProduct::where('cart_id', $cartid)->delete();
            CartProductPrescription::where('cart_id', $cartid)->delete();

            // send sms
            // $this->sendSuccessSMS($request, $order);
            Payment::create([
                'amount' => 0,
                'transaction_id' => $pay->transaction_id,
                'balance_transaction' => $order->payable_amount,
                'type' => 'cart',
                'user_id' => $pay->user_id,
                'payment_option_id' => $pay->payment_option_id,
                'date' => date('Y-m-d'),
                'order_id' => $order->id
            ]);

            // Send Notification
            // return $order->id;
            if (! empty($order->vendors)) {
                foreach ($order->vendors as $vendor_value) {
                    $vendor_order_detail = $orderController->minimize_orderDetails_for_notification($order->id, $vendor_value->vendor_id);
                    $user_vendors = UserVendor::where([
                        'vendor_id' => $vendor_value->vendor_id
                    ])->pluck('user_id');
                    $orderController->sendOrderPushNotificationVendors($user_vendors, $vendor_order_detail);
                }
            }
            $vendor_order_detail = $orderController->minimize_orderDetails_for_notification($order->id);
            $super_admin = User::where('is_superadmin', 1)->pluck('id');
            $orderController->sendOrderPushNotificationVendors($super_admin, $vendor_order_detail);
            return redirect()->route('order.success',['order_id' => $order->id]);
        } else {
            $user = auth()->user();
            $wallet = $user->wallet;
            if (isset($order->wallet_amount_used)) {
                $wallet->depositFloat($order->wallet_amount_used, [
                    'Wallet has been <b>refunded</b> for cancellation of order #' . $order->order_number
                ]);
            }
            return 0;
        }
    }

    public function completeOrderWallet($request,$payment)
    {
        $data['amount'] =  $payment->amount;
        $data['transaction_id'] =  $payment->transaction_id;
        $data['payment_option_id'] =  55;
        $request = new \Illuminate\Http\Request($data);
        $this->creditMyWallet($request);
        // if($come_from == 'app')
        // {
            $response['status']         = 'Success';
            $response['msg']            = 'Success Added wallet.';
            $response['payment_from']   = 'wallet';

        // }
        return redirect()->route('user.wallet');
        return response()->json($response,200);

    }

    public function creditMyWallet(Request $request, $domain = '')
    {
        if( (isset($request->user_id)) && (!empty($request->user_id)) ){
            $user = User::find($request->user_id);
        }elseif( (isset($request->auth_token)) && (!empty($request->auth_token)) ){
            $user = User::whereHas('device',function  ($qu) use ($request){
                $qu->where('access_token', $request->auth_token);
            })->first();

        }else{
            $user = Auth::user();
        }
        if($user){
            $credit_amount = $request->amount;
            $wallet = $user->wallet;
            if ($credit_amount > 0) {
                $saved_transaction = Transaction::where('meta', 'like', '%'.$request->transaction_id.'%')->first();
                if($saved_transaction){
                    return $this->errorResponse('Transaction has already been done', 400);
                }

                $wallet->depositFloat($credit_amount, [__("Wallet has been").' <b>Credited</b> by transaction reference <b>'.$request->transaction_id.'</b>']);

                $payment = new Payment();
                $payment->date = date('Y-m-d');
                $payment->user_id = $user->id;
                $payment->transaction_id = $request->transaction_id;
                $payment->payment_option_id = $request->payment_option_id ?? null;
                $payment->balance_transaction = $credit_amount;
                $payment->type = 'wallet';
                $payment->save();

                $transactions = Transaction::where('payable_id', $user->id)->get();
                $response['wallet_balance'] = $wallet->balanceFloat;
                $response['transactions'] = $transactions;
                $message = 'Wallet has been credited successfully';
                Session::put('success', $message);
                return $this->successResponse($response, $message, 200);
            }
            else{
                return $this->errorResponse('Amount is not sufficient', 400);
            }
        }
        else{
            return $this->errorResponse('Invalid User', 400);
        }
    }

    public function completeOrderSubs($request, $payment)
    {
        $data['transaction_id'] = $payment->transaction_id;
        $data['payment_option_id'] = 55;
        $data['subsid'] = $request['subscription_id'];
        $data['subscription_id'] = $request['subscription_id'];
        $data['amount'] = $request['amount'];

        $request = new \Illuminate\Http\Request($data);

        $subscriptionController = new UserSubscriptionController();
        $subscriptionController->purchaseSubscriptionPlan($request,'', $payment->viva_order_id);
        // if ($come_from == 'app') {
            $response['status'] = 'Success';
            $response['msg'] = 'Success Added Subscription.';
            $response['payment_from'] = 'subscription';
        // }
        return redirect()->route('user.subscription.plans');
        return response()->json($response, 200);
        
    }

    public function completeOrderPickup(Request $request,$payment)
    {
        $order = Order::where('order_number',$payment->viva_order_id)->first();
        if(isset($request->datatransTrxId))
        {
            if ($order) {
                $order->payment_status = 1;
                $order->save();
                $payment_exists = Payment::where('transaction_id', $request->datatransTrxId)->first();
                if (!$payment_exists) {
                    $payment = new Payment();
                    $payment->date = date('Y-m-d');
                    $payment->type = 'pickup_delivery';
                    $payment->order_id = $order->id ?? '';
                    $payment->payment_option_id = 55;
                    $payment->user_id = $order->user_id ?? '';
                    $payment->transaction_id = $request->datatransTrxId;
                    $payment->balance_transaction = $order->payable_amount ?? '';
                    $payment->save();
                }
                
                $request->request->add(['order_number'=> $order->order_number, 'payment_option_id' => 32, 'amount' => $order->payable_amount, 'transaction_id' => $request->datatransTrxId]);
                $plaseOrderForPickup = new PickupDeliveryController();
                $res = $plaseOrderForPickup->orderUpdateAfterPaymentPickupDelivery($request);
                return Redirect::to(route('front.booking.details',$order->order_number));
            }
        }else{
            //Failed transaction case
            $data = Payment::where('transaction_id',$request->datatransTrxId)->first();
            $data->delete();

            return Redirect::to(route('user.wallet'))->with('error',$request->message);
        }
    }

    public function completeOrderTip($request, $payment)
    {
        $data['tip_amount'] = $payment->amount;
        $data['order_number'] = $payment->viva_order_id;
        $data['transaction_id'] = $payment->transaction_id;

        $request = new \Illuminate\Http\Request($data);

        $orderController = new OrderController();
        $orderController->tipAfterOrder($request);
        
        return redirect()->route('user.orders');
        
    }

}
