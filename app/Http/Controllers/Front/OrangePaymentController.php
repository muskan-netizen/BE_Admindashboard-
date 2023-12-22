<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\{ApiResponser, OrderTrait};
use App\Models\{Order, Payment, PaymentOption, User};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Transaction;
use Illuminate\Support\Facades\Redirect;

class OrangePaymentController extends Controller
{
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
            'Cookie' => 'BIGipServer~naomi-ginefa~PoolOCProuter-OCP-EFA-HTTP-NORMANDIE=!bMhHGM1WQJ1AYQU3UzhIVdohv0ViBgAcRlF4ofhzU+EHb/PyP6lvieVDAfKrSraeI165DBPfBSERU1soW8q/2i86sytoghA2NSjLcpI=; BIGipServer~naomi-ginefa~PoolOCProuter-OCP-EFA-HTTP-RUEIL=!vkJSjroHRb2WlZEkioRitpNtJV/P2h3jGIk+uQTUWrMoxexXvPdZT4xTqPSJLM2dcETW0u8QH7B0mu5DnY0HBylZRac/EOgBJVy3h+4=; aa18925415715d69ee149f9d943dc7f8=3488b5c2d8e3b589a38818d14ae4ae74',
        ];
        $formattedHeaders = [];
        foreach ($headers as $key => $value) {
            $formattedHeaders[] = $key . ': ' . $value;
        }
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.orange.com/oauth/v3/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
        CURLOPT_HTTPHEADER => $formattedHeaders,
        ));

        $response = curl_exec($curl);
        curl_close($curl);
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
            'Cookie' => 'BIGipServer~naomi-ginefa~PoolOCProuter-OCP-EFA-HTTP-NORMANDIE=!bMhHGM1WQJ1AYQU3UzhIVdohv0ViBgAcRlF4ofhzU+EHb/PyP6lvieVDAfKrSraeI165DBPfBSERU1soW8q/2i86sytoghA2NSjLcpI=; BIGipServer~naomi-ginefa~PoolOCProuter-OCP-EFA-HTTP-RUEIL=!vkJSjroHRb2WlZEkioRitpNtJV/P2h3jGIk+uQTUWrMoxexXvPdZT4xTqPSJLM2dcETW0u8QH7B0mu5DnY0HBylZRac/EOgBJVy3h+4=; aa18925415715d69ee149f9d943dc7f8=3488b5c2d8e3b589a38818d14ae4ae74'
        ];
        $order_id = (string)($order->id??time());
        $data = [
            "merchant_key" => $this->orangepay_MerchantKey,
            "currency" => "OUV",
            "order_id" => $order_id,
            "amount" => $request->total_amount,
            "return_url" => "http://192.168.102.19:8000/success-orangepay",
            "cancel_url" => "http://myvirtualshop.webnode.es/txncncld/",
            "notif_url" => "http://www.merchant-example2.org/notif",
            "lang" => "fr",
            "reference" => $order_id,
        ];
        
        $formattedHeaders = [];
        foreach ($headers as $key => $value) {
            $formattedHeaders[] = $key . ': ' . $value;
        }
        $jsonData = json_encode($data);
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.orange.com/orange-money-webpay/dev/v1/webpayment',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$jsonData,
        CURLOPT_HTTPHEADER => $formattedHeaders,
        ));
        $response1 = curl_exec($curl);
        $response = json_decode($response1, true);
        $response['order_id'] = $order_id;
        $response['total_amount'] = $request->total_amount;
        $response['payment_from'] = $request->payment_from;
        $jsonResponse = json_encode($response);
        curl_close($curl);
        return $jsonResponse;
    }catch(\Exception $e){
        \Log::error($e->getMessage());
        \Log::error($e->getLine());
    }
    }

    public function TransactionStatus(Request $request){
        $curl = curl_init();
        $Tokenresponse=$this->create_token($request);
        $WebPaymentresponse=$this->web_payment($request);
        $data = json_decode($Tokenresponse, true);
        $pay_token_response = json_decode($WebPaymentresponse, true);
        $accessToken = $data['access_token'];
        if($pay_token_response){
        $headers = [
            'Authorization' => 'Bearer '.$accessToken,
            'Content-Type:  application/json',
            'Cookie: BIGipServer~naomi-ginefa~PoolOCProuter-OCP-EFA-HTTP-NORMANDIE=!t2Qh85zNWnEJ+IQ3UzhIVdohv0ViBnhaD1hFEHEWeNoalfU5hMGNUOuhlEZYk91eW+9tOFiw4dTiyl5vq9m65dAz0cmbpSbpAZ3g3tA=; BIGipServer~naomi-ginefa~PoolOCProuter-OCP-EFA-HTTP-RUEIL=!ilbgZLGq3TzKnqckioRitpNtJV/P2gkGz1rLYsSG0yBEMI6CARW0FYOCKB9imN4RgdVkWoBNq0IePmlv/ZhZTtFOX24VazyroMYjW6Q=; aa18925415715d69ee149f9d943dc7f8=63fa33d2a534a6f7f3dceaa7feba523d'
        ];
        $data = [
            "order_id" => $pay_token_response['order_id'],
            "amount" => $pay_token_response['total_amount'],
            "pay_token" => $pay_token_response['pay_token'],
        ];
        $formattedHeaders = [];
        foreach ($headers as $key => $value) {
            $formattedHeaders[] = $key . ': ' . $value;
        }
        $jsonData = json_encode($data);
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.orange.com/orange-money-webpay/dev/v1/transactionstatus',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$jsonData ,
        CURLOPT_HTTPHEADER => $formattedHeaders,
        ));

        $response1 = curl_exec($curl);
        $response = json_decode($response1, true);
        $response['order_id'] = $pay_token_response['order_id'];
        $response['total_amount'] = $pay_token_response['total_amount'];
        $response['payment_from'] = $pay_token_response['payment_from'];
        $jsonResponse = json_encode($response);
        curl_close($curl);
        return $jsonResponse;
    }else{
        \Log::error('inside else');

    }

    }

    public function SuccessPage(Request $request){
        $TransactionStatusresponse=$this->TransactionStatus($request);
        $response = json_decode($TransactionStatusresponse, true);
        $user = auth()->user();
        $order=Order::where('id',$response['order_id'])->first();
        $data['come_from'] = 'app';
        if ($request->isMethod('post')) {
                $data['come_from'] = 'web';
            if (isset($request->auth_token) && ! empty($request->auth_token)) {
                $user = User::where('auth_token', $request->auth_token)->first();
                Auth::login($user);
            } else {
                $user = auth()->user();
            }         
        }
        if($response['status'] == 'SUCCESS'){
            if($response['payment_from'] == 'subscription')
            {
                $data = [
                    'amount' => $response['total_amount'],
                    'payment_option_id' => 66,
                    'transaction_id' => $response['txnid'],
                    'balance_transaction' => $response['total_amount'],
                    'viva_order_id' => $order->order_number ?? '',
                    'type' => $response['payment_from'],
                    'date' => date('Y-m-d'),
                    'user_id' => $user->id,
                ];
            }
            else{
                $data = [
                    'amount' => $response['total_amount'],
                    'payment_option_id' => 66,
                    'transaction_id' => $response['txnid'],
                    'balance_transaction' => $response['total_amount'],
                    'viva_order_id' => $order->order_number ?? '',
                    'type' => $response['payment_from'],
                    'date' => date('Y-m-d'),
                    'user_id' => $user->id
                ];
            }
            Payment::create($data);
        }

    $payment = Payment::where('transaction_id', $response['txnid'])->first();

        if ($payment->type == 'wallet') {
            return $this->completeOrderWallet($request, $payment,$user);
        }elseif ($payment->type == 'subscription') {
            return $this->completeOrderSubs($request, $payment,$user);
        } elseif ($payment->type == 'pickup_delivery') {
            return $this->completeOrderPickup($request, $payment,$user,$response);
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

    public function completeOrderPickup(Request $request,$payment,$user,$response)
    {
        $order = Order::where('order_number',$payment->viva_order_id)->first();
        if ($order) {
                $order->payment_status = 1;
                $order->save();
                $payment_exists = Payment::where('transaction_id', $response['txnid'])->first();
                if (!$payment_exists) {
                    $payment = new Payment();
                    $payment->date = date('Y-m-d');
                    $payment->type = 'pickup_delivery';
                    $payment->order_id = $order->id ?? '';
                    $payment->payment_option_id = 66;
                    $payment->user_id = $order->user_id ?? '';
                    $payment->transaction_id =$response['txnid'];
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

}
