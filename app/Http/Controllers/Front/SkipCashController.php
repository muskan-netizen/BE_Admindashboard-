<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\PaymentOption;
use Str;
use Auth;
use App\Models\{Order, Payment,User,Cart,UserVendor,CartProductPrescription,CartProduct,CartCoupon,OrderProduct,OrderProductAddon,OrderProductPrescription,VendorOrderStatus,OrderVendor,OrderTax,CartAddon,CaregoryKycDoc,ClientPreference,ClientCurrency};
use Session;
use Log;
class SkipCashController extends Controller
{

    public function showSkipCashPage(Request $request,$app=''){
        $data = $request->all();
        $creds = PaymentOption::where('code', 'skip_cash')
        ->where('status', 1)
        ->first();
        $creds_arr = json_decode($creds->credentials);
        $skipCashClientId=$creds_arr->skip_cash_client_id;
        if($creds->test_mode==1){
            $url = $creds_arr->skip_cash_testing_url;
        }else{
            $url = $creds_arr->skip_cash_live_url;  
        }
    
        $keyId = $creds_arr->skip_cash_key_id;
        $secretKey = $creds_arr->skip_cash_api_secret;

        $addres = Order::with('address')->where('order_number',$request->order_number)->first();
        $fields = [
            "Uid" => Str::uuid()->toString(),
            'KeyId' => $keyId,
            'Amount' => $request->amount, 
            'FirstName' => Auth::user()->name,
            'LastName' =>  Auth::user()->name,
            'Phone' => Auth::user()->phone_number,

            'Email' =>  Auth::user()->email,
            'Street' => '123',
            'City' => 'Anytown',
            'State' => $addres->address->country_code ?? 'IN',
            'Country' => $addres->address->country_code ?? 'IN',
            'PostalCode' => '12345',
            'TransactionId' => $request->order_number,
        
        ];
        $signatureString = '';
        // foreach ($fields as $key => $value) {
        //     if (!empty($value)) {
        //         $signatureString .= "$key=$value,";
        //     }
        // }
        // $signatureString = rtrim($signatureString, ',');
        $signatureString = http_build_query($fields,'',', ');
        // Encrypt the signature string using HMACSHA256 with the secret key
        $signature = hash_hmac('sha256', $signatureString, $secretKey, true);
        
        $signatureBase64 = base64_encode($signature);
       
        $headers = [
            'Content-Type: application/json',
            "Authorization: $signatureBase64",
            'x-client-id: ' . $skipCashClientId

        ];

        // Set the request body
        $body = json_encode($fields);

        // Create the cURL handle
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Execute the request
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        if ($error && $info['http_code'] !== 200) {
            $message = 'Payment error';
            
            if($app)
                $response['message'] = $message??'';

                return redirect()->back()->with('success', $message);   

        } else {
            $responseObj = json_decode($response);  
            dd($responseObj);
            $payUrl = $responseObj->resultObj->payUrl;
            $user = auth()->user();
            if ($request->isMethod('post')) {
                $data['come_from'] = 'web';
            
    
                $time = '';
                $amt = $request->amt ?? $request->amount;
                if (isset($request->auth_token) && ! empty($request->auth_token)) {
                    $user = User::where('auth_token', $request->auth_token)->first();
                    Auth::login($user);
                } else {
                    $user = auth()->user();
                }
                $order = Order::where('order_number',$request->order_number)->first();

                if ($request->payment_from == 'cart') {
                    $request->amt = $amt;
                    $time = $request->order_number;
                    Payment::create([
                        'amount' => 0,
                        'payment_option_id' => 52,
                        'transaction_id' => $time,
                        'balance_transaction' => $amt,
                        'order_id' => $order->id??0,
                        'type' => 'cart',
                        'user_id' => $user->id,
                        'date' => date('Y-m-d'),
                        'payment_from'=>$app??'web'
                    ]);
                } elseif ($request->payment_from == 'pickup_delivery') {
                    $request->amt = $amt;
                    $time = $request->order_number;
                    Payment::create([
                        'amount' => 0,
                        'payment_option_id' => 52,
                        'transaction_id' => $time,
                        'balance_transaction' => $amt,
                        'type' => 'pickup_delivery',
                        'date' => date('Y-m-d'),
                        'user_id' => auth()->id(),
                        'payment_from'=>$app??'web'

                    ]);
                } elseif ($request->payment_from == 'wallet') {
                    $time = ($request->transaction_id) ?? time();
                    Payment::create([ 
                        'amount' => 0,
                        'payment_option_id' => 52,
                        'transaction_id' => $time,
                        'balance_transaction' => $amt,
                        'type' => 'wallet',
                        'date' => date('Y-m-d'),
                        'user_id' => $user->id,
                        'payment_from'=>$app??'web'

                    ]);
                } elseif ($request->payment_from == 'tip') {
                    $time =  ($request->transaction_id) ?? time();
                    $order = Order::where('order_number',$request->order_number)->first();
                    Payment::create([
                        'amount' => 0,
                        'payment_option_id' => 52,
                        'transaction_id' => $time,
                        'balance_transaction' => $amt,
                        'type' => 'tip',
                        'order_id' => $order->id??0,
                        'date' => date('Y-m-d'),
                        'user_id' => $user->id,
                        'payment_from'=>$app??'web'

                    ]);
                } elseif ($request->payment_from == 'subscription') {
                    $time =  time();
                    Payment::create([
                        'amount' => 0,
                        'payment_option_id' => 52,
                        'transaction_id' => $time,
                        'balance_transaction' => $amt,
                        'type' => 'subscription',
                        'date' => date('Y-m-d'),
                        'user_id' => $user->id,
                        'payment_from'=>$app??'web'

                    ]);
                }
                $data['order_number'] = $time;
            }

            if($app){
                $response['message'] = $message??'';
                $response['url'] = $payUrl??'';
            }else{
                return redirect($payUrl);
            }

            return $response;
        }
    }

    public function mobilePay(Request $request)
    {
       $data =  $this->showSkipCashPage($request,'app');
       if(isset($data) && !empty($data))
        {
            return $data;
        }
    }
    




    public function successPage(Request $request)
    {
        if (isset($_POST['response'])) {
            $payment = Payment::where('transaction_id', $request->get('transId'))->first();
            if ($payment->type == 'cart') {
                $this->completeOrderCart($request, $payment);
            } elseif ($payment->type == 'wallet') {
               $this->completeOrderWallet($request, $payment);
            } elseif ($payment->type == 'tip') {
                $order = Order::find($payment->order_id);
                $this->completeOrderTip($request, $payment,$order->order_number??0);
            } elseif ($payment->type == 'subscription') {
                $this->completeOrderSubs($request, $payment);
            } elseif ($payment->type == 'pickup_delivery') {
                 $this->completeOrderPickup($request, $payment);
            }            
        }
        
        if(auth()->user()){
            $payment = Payment::select('*')->where('user_id',auth()->user()->id)->where(['transaction_id' => $request->get('transId')])->orderBy('id','DESC')->first();
            if($payment){
                if ($payment->type == 'cart') {   
                    $message = 'Order has been placed successfully';
                    Session::put('success', $message);
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
                } elseif ($payment->type == 'pickup_delivery') {
                    return redirect()->route('front.booking.details',[$payment->transaction_id]);
                }
            }
        }
       // return redirect('user/orders');
    }

    public function completeOrderCart(Request $request, $pay)
    {
        // dd($pay);
        $order = Order::where('order_number', $pay->transaction_id)->first();
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
                'schedule_pickup' => null,
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
            $this->sendSuccessSMS($request, $order);
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

    public function sendSuccessSMS($request, $order, $vendor_id = '')
    {
        try {
            $prefer = ClientPreference::select('sms_provider', 'sms_key', 'sms_secret', 'sms_from')->first();

            $user = Auth::user();
            if ($user) {
                $customerCurrency = ClientCurrency::join('currencies as cu', 'cu.id', 'client_currencies.currency_id')->where('client_currencies.currency_id', $user->currency)->first();
                $currSymbol = $customerCurrency->symbol;
                if ($user->dial_code == "971") {
                    $to = '+' . $user->dial_code . "0" . $user->phone_number;
                } else {
                    $to = '+' . $user->dial_code . $user->phone_number;
                }
                $provider = $prefer->sms_provider;
                $keyData = ['{user_name}'=>$user->name??'','{amount}'=>$currSymbol . $order->payable_amount,'{order_number}'=>$order->order_number??''];
                $body = sendSmsTemplate('order-place-Successfully',$keyData);

                if (!empty($prefer->sms_provider)) {
                    $send = $this->sendSmsNew($provider, $prefer->sms_key, $prefer->sms_secret, $prefer->sms_from, $to, $body);
                }
            }
        } catch (\Exception $ex) {
        }
    }

    public function handleWebhook(Request $request)
    {
            // Get the webhook payload
            $payload = json_decode($request->getContent(), true);
            // print_r($payload, true);

            // Log the payload to your application's logs
            Log::info('SkipCash webhook received: '. print_r($payload, true));

            // Do any additional processing based on the webhook payload
    }


    // public function failedPayment($request)
    // {
    // 	if($request->payment_from == 'cart'){
    //         $order_number = $request->order_number;
    //         $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
    //         $order_products = OrderProduct::select('id')->where('order_id', $order->id)->get();
    //         foreach ($order_products as $order_prod) {
    //             OrderProductAddon::where('order_product_id', $order_prod->id)->delete();
    //         }
    //         OrderProduct::where('order_id', $order->id)->delete();
    //         OrderProductPrescription::where('order_id', $order->id)->delete();
    //         VendorOrderStatus::where('order_id', $order->id)->delete();
    //         OrderVendor::where('order_id', $order->id)->delete();
    //         OrderTax::where('order_id', $order->id)->delete();
    //         Order::where('id', $order->id)->delete();
            
    //     }
      
    // }



    
}
