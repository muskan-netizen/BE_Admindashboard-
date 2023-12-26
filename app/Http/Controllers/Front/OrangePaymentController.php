<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\{ApiResponser, OrderTrait};
use App\Http\Traits\HomePage\HomePageTrait;
use App\Models\{Order, Payment, PaymentOption, User};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Transaction;
use Illuminate\Support\Facades\Redirect;

class OrangePaymentController extends Controller
{
    use HomePageTrait;
    use ApiResponser;
    public function __construct()
    {
        $payOption = PaymentOption::select('credentials', 'test_mode', 'status')->where('code', 'orangepay')->where('status', 1)->first();
        if (@$payOption && !empty($payOption->credentials)) {
            $credentials = json_decode($payOption->credentials);
            $this->orangepay_MerchantKey = $credentials->orangepay_MerchantKey;
            $this->orangepay_MerchantToken = $credentials->orangepay_MerchantToken;
        }
    }
    public function create_token(Request $request){

        $curl = curl_init();
        $bearer=$this->orangepay_MerchantToken;
        $headers = [
            'Authorization' => 'Basic '.$bearer,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        $formattedHeaders = [];
        foreach ($headers as $key => $value) {
            $formattedHeaders[] = $key . ': ' . $value;
        }
        $url='https://api.orange.com/oauth/v3/token';
        $response = $this->makeCurlRequest($url, 'POST',[] , $formattedHeaders);
        return $response;
    }

    

    public function web_payment(Request $request){
        try{
        $user = auth()->user();
        $data['come_from'] = 'app';
        $order = Order::where(['order_number' => $request->order_number])->first();
        if ($request->isMethod('post')) {
                $data['come_from'] = 'web';
            if (isset($request->auth_token) && ! empty($request->auth_token)) {
                $user = User::where('auth_token', $request->auth_token)->first();
                Auth::login($user);
            } else {
                $user = auth()->user();
            }         
        }
        $response=$this->create_token($request);
        $data = json_decode($response, true);
        $accessToken = $data['access_token'];
        $curl = curl_init();
        $headers = [
            'Authorization' => 'Bearer '.$accessToken,
            'Content-Type' => 'application/json',
        ];
        $order_id = (string)($order->id??time());
        $data = [
            "merchant_key" => $this->orangepay_MerchantKey,
            "currency" => "OUV",
            "order_id" => $order_id,
            "amount" => $request->total_amount,
            "return_url" => route('success.orangepayment'),
            "cancel_url" => route('cancel.orangepayment'),
            "notif_url" => route('success.orangepayment'),
            "lang" => "fr",
            "reference" => $order_id,
        ];
        
        $formattedHeaders = [];
        foreach ($headers as $key => $value) {
            $formattedHeaders[] = $key . ': ' . $value;
        }
        $jsonData = json_encode($data);
        $url='https://api.orange.com/orange-money-webpay/dev/v1/webpayment';
        $response1 =$this->makeCurlRequest($url,'POST', $data, $formattedHeaders);
        $response = json_decode($response1, true);
        $jsonResponse = json_encode($response);
        curl_close($curl);
        if($response['notif_token']){
        $data = [
            'amount' => $request->total_amount,
            'payment_option_id' => 66,
            'transaction_id' => $response['notif_token'],
            'balance_transaction' => $request->total_amount,
            'viva_order_id' => $order->order_number ?? $order_id,
            'type' => $request->payment_from,
            'date' => date('Y-m-d'),
            'user_id' => $user->id
        ];
        }
        Payment::create($data);
        return $jsonResponse;
    }catch(\Exception $e){
        \Log::error($e->getMessage());
        \Log::error($e->getLine());
    }
    }

    public function SuccessPage(Request $request){
        $payment=Payment::where('transaction_id',$request['notif_token'])->first();
        $order=Order::where('order_number',$payment->viva_order_id)->first();
        $user=User::where('id',$payment->user_id)->first();
        $data['come_from'] = 'app';
        if($request['status'] == 'SUCCESS'){
                $payment->update([
                    'transaction_id' => $request['txnid'],
                ]);
                $payment->save();
        }
    $payment = Payment::where('transaction_id', $request['txnid'])->first();
        if ($payment->type == 'wallet') {
            return $this->completeOrderWallet($request, $payment,$user);
        }elseif ($payment->type == 'subscription') {
            return $this->completeOrderSubs($request, $payment,$user);
        } elseif ($payment->type == 'pickup_delivery') {
            return $this->completeOrderPickup($request, $payment,$user);
        }  


    }

    public function completeOrderWallet(Request $request,$payment,$user)
    {
        $data['amount'] =  $payment->amount;
        $data['transaction_id'] =  $payment->transaction_id;
        $data['payment_option_id'] = 66;
        $data['come_from'] = $request['come_from'];
        $data['user_id'] = $user->id;

        $request = new \Illuminate\Http\Request($data);
        $this->creditMyWallet($request);

        if(isset($request->come_from) && $request->come_from == 'app')
        {
            $response['status']         = 200;
            $response['msg']            = 'Success Added wallet.';
            $response['payment_from']   = 'wallet';
            $response['data']   = $data;
            // $returnUrl = route('payment.gateway.return.response').'/?gateway=orangepay'.'&status=200&transaction_id='.$payment->transaction_id;
            // return Redirect::to($returnUrl);
            return response()->json($response);
        }
        return redirect()->route('user.wallet');

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

    public function completeOrderSubs(Request $request, $payment,$user)
    {
        $data['transaction_id'] = $payment->transaction_id;
        $data['payment_option_id'] = 66;
        $data['subsid'] = $request['subscription_id'];
        $data['subscription_id'] = $request['subscription_id'];
        $data['amount'] = $payment->amount;
        $data['come_from'] = $request['come_from'];
        $data['user_id'] = $user->id;

        $request = new \Illuminate\Http\Request($data);

        $subscriptionController = new UserSubscriptionController();        
        $subscriptionController->purchaseSubscriptionPlan($request,'', $payment->viva_order_id);

        if (isset($request->come_from) && $request->come_from == 'app') {
            $response['status'] = 200;
            $response['msg'] = 'Success Added Subscription.';
            $response['payment_from'] = 'subscription';
            $response['transaction_id'] = $payment->transaction_id;
            // $returnUrl = route('payment.gateway.return.response').'/?gateway=orangepay'.'&status=200&transaction_id='.$payment->transaction_id;
            // return Redirect::to($returnUrl);
            return response()->json($response);
        }
        return redirect()->route('user.subscription.plans');
        
    }
    
    public function completeOrderPickup(Request $request,$payment,$user)
    {
        $order = Order::where('order_number',$payment->viva_order_id)->first();
        if ($order) {
                $order->payment_status = 1;
                $order->save();
                $payment_exists = Payment::where('transaction_id', $request['txnid'])->first();
                if (!$payment_exists) {
                    $payment = new Payment();
                    $payment->date = date('Y-m-d');
                    $payment->type = 'pickup_delivery';
                    $payment->order_id = $order->id ?? '';
                    $payment->payment_option_id = 66;
                    $payment->user_id = $order->user_id ?? '';
                    $payment->transaction_id =$request['txnid'];
                    $payment->balance_transaction = $order->payable_amount ?? '';
                    $payment->save();
                }
                
                $request->request->add(['order_number'=> $order->order_number, 'amount' => $order->payable_amount, 'transaction_id' => $payment->transaction_id]);
                $plaseOrderForPickup = new PickupDeliveryController();
                $res = $plaseOrderForPickup->orderUpdateAfterPaymentPickupDelivery($request);
                
                if (isset($request->come_from) && $request->come_from == 'app') {
                    $response['status'] = 200;
                    $response['msg'] = 'Success Added Pickup.';
                    $response['payment_from'] = 'pickup_delivery';
                    $response['order'] = $order;
                    return response()->json($response);
                }

                return Redirect::to(route('front.booking.details',$order->order_number));
        }else{
            //Failed transaction case
            $data = Payment::where('transaction_id',$request->OrderMerchantReference)->first();
            $data->delete();

            if (isset($request->come_from) && $request->come_from == 'app') {
                $response['status'] = 200;
                $response['msg'] = 'Success Added Pickup.';
                $response['payment_from'] = 'pickup_delivery';
                return response()->json($response);
            }

            return Redirect::to(route('user.wallet'))->with('error',$request->message);
        }
    }

    public function CancelPage(Request $request)
    {
        if(isset($request->come_from) && $request->come_from == 'app')
        {
            $response['status']         = 'Error';
            $response['msg']            = 'Payment Cancel';
            return response()->json($response);
        }
        return redirect()->back();
    }

}
