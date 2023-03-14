<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\PaymentOption;
use Str;
use Auth;
use App\Models\{Order, Payment,User,Cart,UserVendor,CartProductPrescription,CartProduct,CartCoupon,CartAddon,CaregoryKycDoc};
use Session;
class SkipCashController extends Controller
{

    public function showSkipCashPage(Request $request){
        $data = $request->all();
        //   dd($data);
        // // return view('frontend.payment_gatway.skip_cash',compact('data'));
        // $this->checkPayment($request);
        $creds = PaymentOption::where('code', 'skip_cash')
        ->where('status', 1)
        ->first();
        $creds_arr = json_decode($creds->credentials);
        $skipCashClientId=$creds_arr->skip_cash_client_id;
        $url = 'https://skipcashtest.azurewebsites.net/api/v1/payments';
        $keyId = $creds_arr->skip_cash_key_id;
        // dd($keyId);
        $secretKey = $creds_arr->skip_cash_api_secret;
        //  dd($kesecretKeyyId);
        $return_url  = 'https://example.com/success';
        
        // Define the request fields
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
            'State' => 'CA',
            'Country' => 'US',
            'PostalCode' => '12345',
            'TransactionId' => $request->order_number,
            //'return_url' => $return_url,

            //'Custom1' => '',
            //  'ClientID' => $client_id,
            //  'keyId'=>$keyId,
        ];
        $signatureString = '';
        foreach ($fields as $key => $value) {
            if (!empty($value)) {
                $signatureString .= "$key=$value,";
            }
        }
        $signatureString = rtrim($signatureString, ',');
        // dd($signatureString);

        // Encrypt the signature string using HMACSHA256 with the secret key
        $signature = hash_hmac('sha256', $signatureString, $secretKey, true);
        // echo $signature;
        // die;
        // Convert the encrypted result to base64 format
        $signatureBase64 = base64_encode($signature);
        // dd($signatureBase64);
        // Set the headers
        // echo "$keyId:$signatureBase64";
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
        //  dd($response);
        $error = curl_error($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        // dd($response);
        // Check for errors
        if ($error) {
            //  dd($info);
            echo "cURL Error: $error\n";
        } elseif ($info['http_code'] !== 200) {
          //  dd("not");
        //   dd($info);
            echo "HTTP Error: {$info['http_code']}\n";
        } else {
            // dd("succ");
            // echo "Response: $response\n";
            $responseObj = json_decode($response);
            // dd($responseObj);    
            $payUrl = $responseObj->resultObj->payUrl;
            //  dd($payUrl);
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
                        'date' => date('Y-m-d')
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
                        'user_id' => auth()->id()
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
                        'user_id' => $user->id
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
                        'user_id' => $user->id
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
                        'user_id' => $user->id
                    ]);
                }
                $data['order_number'] = $time;
            }
           return redirect($payUrl);
        }
    }
    


   


    // public function checkPayment(Request $request)
    // {
    //     $creds = PaymentOption::where('code', 'skip_cash')
    //     ->where('status', 1)
    //     ->first();
    //     $creds_arr = json_decode($creds->credentials);
    //     $skipCashClientId=$creds_arr->skip_cash_client_id;
    //     $url = 'https://skipcashtest.azurewebsites.net/api/v1/payments';
    //     $keyId = $creds_arr->skip_cash_key_id;
    //     // dd($keyId);
    //     $secretKey = $creds_arr->skip_cash_api_secret;
    //     //  dd($kesecretKeyyId);
    //     $return_url  = 'https://example.com/success';
        
    //     // Define the request fields
    //     $fields = [
    //         "Uid" => Str::uuid()->toString(),
    //         'KeyId' => $keyId,
    //         'Amount' => $request->amount, 
    //         'FirstName' => Auth::user()->name,
    //         'LastName' =>  Auth::user()->name,
    //         'Phone' => Auth::user()->phone_number,
    //         'Email' =>  Auth::user()->email,
    //         'Street' => '123',
    //         'City' => 'Anytown',
    //         'State' => 'CA',
    //         'Country' => 'US',
    //         'PostalCode' => '12345',
    //         'TransactionId' => $request->order_id,
    //         //'return_url' => $return_url,

    //         //'Custom1' => '',
    //         //  'ClientID' => $client_id,
    //         //  'keyId'=>$keyId,
    //     ];
    //     $signatureString = '';
    //     foreach ($fields as $key => $value) {
    //         if (!empty($value)) {
    //             $signatureString .= "$key=$value,";
    //         }
    //     }
    //     $signatureString = rtrim($signatureString, ',');
    //     // dd($signatureString);

    //     // Encrypt the signature string using HMACSHA256 with the secret key
    //     $signature = hash_hmac('sha256', $signatureString, $secretKey, true);
    //     // echo $signature;
    //     // die;
    //     // Convert the encrypted result to base64 format
    //     $signatureBase64 = base64_encode($signature);
    //     // dd($signatureBase64);
    //     // Set the headers
    //     // echo "$keyId:$signatureBase64";
    //     $headers = [
    //         'Content-Type: application/json',
    //         "Authorization: $signatureBase64",
    //         'x-client-id: ' . $skipCashClientId

    //     ];

    //     // Set the request body
    //     $body = json_encode($fields);

    //     // Create the cURL handle
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    //     // Execute the request
    //     $response = curl_exec($ch);
    //     //  dd($response);
    //     $error = curl_error($ch);
    //     $info = curl_getinfo($ch);
    //     curl_close($ch);
    //     // dd($response);
    //     // Check for errors
    //     if ($error) {
    //         //  dd($info);
    //         echo "cURL Error: $error\n";
    //     } elseif ($info['http_code'] !== 200) {
    //       //  dd("not");
    //     //   dd($info);
    //         echo "HTTP Error: {$info['http_code']}\n";
    //     } else {
    //         // dd("succ");
    //         // echo "Response: $response\n";
    //         $responseObj = json_decode($response);
    //       //  dd($responseObj);    
    //         $payUrl = $responseObj->resultObj->payUrl;
    //         //  dd($payUrl);
    //        return redirect($payUrl);
    //     }
        
    // }

    public function successPage(Request $request)
    {
        // dd($request->get('transId'));
        if (isset($_POST['response'])) {
           
          
            $payment = Payment::where('transaction_id', $response['tran_id'])->first();
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
        }
        
        if(auth()->user()){
            // dd("ASASS");
            \Log::info("user ".json_encode(auth()->user()->id));
            //  dd(auth()->user()->id);
            $payment = Payment::select('*')->where('user_id',auth()->user()->id)->where(['payment_option_id' => 52])->orderBy('id','DESC')->first();
            // dd($payment);
            if($payment){
                if ($payment->type == 'cart') {   
                    $message = 'Order has been placed successfully';
                    Session::put('success', $message);
                    \Log::info("payment order id : ".$payment->order_id);
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
        $order = Order::where('order_number', $pay->transaction_id)->first();
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


    
}
