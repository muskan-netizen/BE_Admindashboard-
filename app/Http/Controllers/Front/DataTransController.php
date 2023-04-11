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
use App\Http\Traits\ApiResponser;
use App\Models\CaregoryKycDoc;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class DataTransController extends Controller
{
    use ApiResponser;
    public function payByDataTrans(Request $request)
    {
        $data = $request->all();
        $user = auth()->user();
        $data['come_from'] = 'app';

        $amt = $request->amt ?? $request->total_amount;

        if ($request->isMethod('post')) {
                $data['come_from'] = 'web';
                $time = '';
            if (isset($request->auth_token) && ! empty($request->auth_token)) {
                $user = User::where('auth_token', $request->auth_token)->first();
                Auth::login($user);
            } else {
                $user = auth()->user();
            }

            if ($request->payment_from == 'cart') {
                $order = Order::where(['order_number' => $request->order_number])->first();
                $data['return_url'] = route('order.success',[$order->id]);
                $refNo = "Oder-".$request->order_number;
              
            } elseif ($request->payment_from == 'pickup_delivery') {
                // $request->amt = $amt;
                $time = $request->order_number;
                $data['return_url'] = route('front.booking.details', [$time]);
                $refNo = "Pickup Delivery";
              
            } elseif ($request->payment_from == 'wallet') {

                $data['return_url'] = route('user.wallet');
                $refNo = "Wallet-Credit";

            } elseif ($request->payment_from == 'tip') {

                $data['return_url'] =route('user.orders');
                $refNo = "Tip Amount";
      
            } elseif ($request->payment_from == 'subscription') {
           
                $data['return_url'] = route('user.subscription.plans');
                $refNo = "subscription";                
            }
            // $data['order_number'] = $time;
        }

        $payment = PaymentOption::find($request->payment_option_id);
        $credentials = json_decode($payment->credentials);

        $order = Order::where('order_number', $request->order_number)->first();

        $redirect = $data['return_url'];
        $redirect = route('order.dataTransuccessPage');

        $response = Http::withHeaders([
            'Authorization' => 'Basic '. base64_encode("$credentials->merchant_id:$credentials->password"),
            'Content-Type' =>'application/json' 
        ])->post('https://api.sandbox.datatrans.com/v1/transactions',[
            "currency" => "CHF",
            "refno" => $refNo,
            "amount" => $request->total_amount * 100,
            "paymentMethods" => ["ECA","VIS","PAP","AMX","AZP","APL","PAY","DIS"],
            "redirect" => [
                "successUrl" => $redirect,
                "cancelUrl" => route('userHome'),
                "errorUrl" => $redirect
            ]
        ]);
        
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
                'viva_order_id' => $order->id ?? '',
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
                $this->completeOrderCart($request, $payment);
            } elseif ($payment->type == 'wallet') {
                \Log::info("Wallet type");
               $this->completeOrderWallet($request, $payment);
            } elseif ($payment->type == 'tip') {
                $order = Order::find($payment->order_id);
                $this->completeOrderTip($request, $payment,$order->order_number??0);
            } elseif ($payment->type == 'subscription') {
                $this->completeOrderSubs($request, $payment);
            } elseif ($payment->type == 'pickup_delivery') {
                 $this->completeOrderPickup($request, $payment);
            }   
            
        if(auth()->user()){

            \Log::info("user ".json_encode(auth()->user()->id));

            $payment = Payment::select('*')->where('user_id',auth()->user()->id)->where(['transaction_id' => $request->get('datatransTrxId')])->orderBy('id','DESC')->first();
            //  dd($payment);
            if($payment){
                if ($payment->type == 'cart') {   
                    $message = 'Order has been placed successfully';
                    Session::put('success', $message);
                    \Log::info("payment order id : ".$payment->order_id);
                    $this->completeOrderCart($request, $payment);
                    return redirect()->route('order.success',['order_id' => $payment->order_id]);                  
                } elseif (in_array($payment->type,[ 'wallet','wallet_topup'])) {
                    $message = 'Wallet has been credited successfully';
                    Session::put('success', $message);
                    return redirect()->route('user.wallet');
                } elseif ($payment->type == 'tip') {
                    $message = 'Tip has been submitted successfully';
                    Session::put('success', $message);
                    return redirect()->route('user.orders');
                } elseif ($payment->type == 'subscription') {
                    $message = 'Subscription has been done successfully';
                    Session::put('success', $message);
                    return redirect()->route('user.subscription.plans');
                } 
            }
        }
       // return redirect('user/orders');
    }

    public function completeOrderCart(Request $request, $pay)
    {
        // dd($pay);
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
            Cart::where('id', $cartid)->delete();
            // send sms
            // $this->sendSuccessSMS($request, $order);
            // Payment::create([
            //     'amount' => 0,
            //     'transaction_id' => $pay->transaction_id,
            //     'balance_transaction' => $order->payable_amount,
            //     'type' => 'cart',
            //     'user_id' => $pay->user_id,
            //     'payment_option_id' => $pay->payment_option_id,
            //     'date' => date('Y-m-d'),
            //     'order_id' => $order->id
            // ]);

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
            return $order->id;
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
        // $walletController = new WalletController();
        $this->creditMyWallet($request);
        // if($come_from == 'app')
        // {
            $response['status']         = 'Success';
            $response['msg']            = 'Success Added wallet.';
            $response['payment_from']   = 'wallet';

        // }
        return response()->json($response,200);

    }
    
    public function creditMyWallet(Request $request)
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
                $payment->type = 'wallet_topup';
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
        return response()->json($response, 200);
        
    }
}
