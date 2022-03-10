<?php

namespace App\Http\Controllers\Front;

use DB;
use Log;
use Auth;
use Session;
use Redirect;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Front\{FrontController, OrderController, WalletController, UserSubscriptionController};
use App\Models\Client as CP;
use App\Models\{PaymentOption, Client, ClientPreference, Order, OrderProduct, EmailTemplate, Cart, CartAddon, OrderProductPrescription, CartProduct, User, Product, OrderProductAddon, Payment, ClientCurrency, OrderVendor, UserAddress, Vendor, CartCoupon, CartProductPrescription, LoyaltyCard, NotificationTemplate, VendorOrderStatus,OrderTax, SubscriptionInvoicesUser, UserDevice, UserVendor};

class CashfreeGatewayController extends FrontController
{
    use ApiResponser;
    public $APP_ID;
    public $SECRET_KEY;
    public $TEST_MODE;
    public $currency;

    public function __construct()
    {
        $cashfree_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'cashfree')->where('status', 1)->first();
        $creds_arr = isset($cashfree_creds->credentials) ? json_decode($cashfree_creds->credentials) : '';
        $app_id = (isset($creds_arr->app_id)) ? $creds_arr->app_id : '';
        $secret_key = (isset($creds_arr->secret_key)) ? $creds_arr->secret_key : '';
        $testmode = (isset($cashfree_creds->test_mode) && ($cashfree_creds->test_mode == '1')) ? true : false;
        $this->APP_ID = $app_id;
        $this->SECRET_KEY = $secret_key;
        $this->TEST_MODE = $testmode;
        
        $primaryCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
        $this->currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'USD';
    }

    public function createOrder(Request $request, $domain = ''){
        try{
            $user = Auth::user();
            $amount = $this->getDollarCompareAmount($request->amount);
            $payment_form = $request->payment_form;

            $returnUrl = route('order.return.success');
            $customer_data = array(
                'customer_id' => 'customer_'.$user->id,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone_number
            );
            $order_tags = ['user_id' => strval($user->id), 'payment_form' => $payment_form];
            $reference_number = $description = '';
            $returnUrlParams = '?order_id={order_id}&order_token={order_token}&gateway=cashfree&amount=' . $request->amount . '&payment_form=' . $payment_form;

            if($payment_form == 'cart'){
                $description = 'Order Checkout';
                $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
                $request->request->add(['cart_id' => $cart->id]);
                $reference_number = $request->order_number;
                $order_tags['cart_id'] = strval($cart->id);
                $order_tags['order_number'] = $reference_number;

                $order = Order::where('order_number', $reference_number)->first();
                $returnUrlParams = $returnUrlParams . '&cart_id=' .$cart->id; //. '&order_id={order_id}' .$reference_number. '&order_token=' .$reference_number;
            }
            elseif($payment_form == 'wallet'){
                $description = 'Wallet Checkout';
                // $reference_number = $user->id;
            }
            if($payment_form == 'tip'){
                $description = 'Tip Checkout';
                $order_tags['order_number'] = $request->order_number;
                
                $order = Order::where('order_number', $reference_number)->first();
                $reference_number = $request->order_number;
                // $returnUrlParams = $returnUrlParams . '&order_id=' .$reference_number. '&order_token=' .$reference_number;
            }
            elseif($payment_form == 'subscription'){
                $description = 'Subscription Checkout';
                if($request->has('subscription_id')){
                    $slug = $request->subscription_id;
                    $subscription_plan = SubscriptionPlansUser::with('features.feature')->where('slug', $slug)->where('status', '1')->first();
                    $customer_data['subscription_id'] = $subscription_plan->id;
                    // $reference_number = $request->subscription_id;
                    $returnUrlParams = $returnUrlParams . '&subscription=' . $request->subscription_id;
                    $order_tags['subscription_id'] = $request->subscription_id;
                }
            }

            $data = array(
                'order_id' => $reference_number,
                'order_amount' => $amount,
                'order_currency' => $this->currency,
                'customer_details' => $customer_data,
                'order_note' => $description,
                'order_tags' => $order_tags,
                'order_meta' => array(
                    'return_url' => url('payment/cashfree/return' . $returnUrlParams),
                    'notify_url' => url("payment/cashfree/notify")
                )
            );

            // return '{"cf_order_id":2229268,"order_id":"72372814","entity":"order","order_currency":"INR","order_amount":40698.89,"order_expiry_time":"2022-04-08T12:44:30+05:30","customer_details":{"customer_id":"customer_2","customer_name":null,"customer_email":"newclient@gmail.com","customer_phone":"7672507360"},"order_meta":{"return_url":"http://local.myorder.com/payment/cashfree/return?order_id={order_id}\u0026order_token={order_token}\u0026gateway=cashfree\u0026amount=40698.89\u0026payment_form=cart\u0026cart_id=819","notify_url":"http://local.myorder.com/payment/cashfree/notify","payment_methods":null},"settlements":{"url":"https://sandbox.cashfree.com/pg/orders/72372814/settlements"},"payments":{"url":"https://sandbox.cashfree.com/pg/orders/72372814/payments"},"refunds":{"url":"https://sandbox.cashfree.com/pg/orders/72372814/refunds"},"order_status":"ACTIVE","order_token":"pv7oxeu7iNTv34hiiRUt","order_note":null,"payment_link":"https://payments-test.cashfree.com/order/#pv7oxeu7iNTv34hiiRUt","order_tags":null,"order_splits":[]}';

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $this->getPaymentURL() . "/orders",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => [
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "x-api-version: 2022-01-01",
                    "x-client-id: ". $this->APP_ID,
                    "x-client-secret: ". $this->SECRET_KEY
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return $this->errorResponse($err->message, 400);
            } else {
                return $this->successResponse(json_decode($response), 'Order has been created successfully');
            }
        }
        catch(\Exception $ex){
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    public function cashfreeReturn(Request $request, $domain = '')
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->getPaymentURL() . "/orders/" .$request->order_id. "/payments",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "Content-Type: application/json",
                "x-api-version: 2022-01-01",
                "x-client-id: ". $this->APP_ID,
                "x-client-secret: ". $this->SECRET_KEY
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $response = json_decode($response);

        if(!$err){
            $response = $response[0];
            $payment_status = strtolower($response->payment_status);
            if($payment_status == 'success'){
                if($request->payment_form == 'cart'){
                    $order_number = $request->order_id;
                    $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
                    if ($order) {
                        $returnUrlParams = '';
                        $returnUrl = route('order.success', $order->id);
                        return Redirect::to(url($returnUrl . $returnUrlParams))->with('success', 'Transaction has been completed successfully');
    
                        // Send Email
                        //   $this->successMail();
                    }
                } elseif($request->payment_form == 'wallet'){
                    $returnUrl = route('user.wallet');
                    return Redirect::to(url($returnUrl))->with('success', 'Transaction has been completed successfully');
                }
                elseif($request->payment_form == 'tip'){
                    $returnUrl = route('user.orders');
                    return Redirect::to(url($returnUrl))->with('success', 'Transaction has been completed successfully');
                }
                elseif($request->payment_form == 'subscription'){
                    $returnUrl = route('user.subscription.plans');
                    return Redirect::to(url($returnUrl))->with('success', 'Transaction has been completed successfully');
                }
            }
        }

        if($request->payment_form == 'cart'){
            return Redirect::to(route('showCart'))->with('error', 'Your order has been cancelled');
        } elseif($request->payment_form == 'wallet'){
            return Redirect::to(route('user.wallet'))->with('error', 'Transaction has been cancelled');
        } elseif($request->payment_form == 'tip'){
            return Redirect::to(route('user.orders'))->with('error', 'Transaction has been cancelled');
        } elseif($request->payment_form == 'subscription'){
            return Redirect::to(route('user.subscription.plans'))->with('error', 'Transaction has been cancelled');
        }
    }

    public function cashfreeNotify(Request $request, $domain = '')
    {
        // Notify PayFast that information has been received
        //dd('sad');
        \Log::info($request->all());

        try{
            $response = json_encode($request->data);
            \Log::info($response);
            switch ($response->payment->payment_status) {
                case 'SUCCESS':
                    $transactionId = $response->payment->cf_payment_id;
                    $user_id = $cart_id = $payment_form = $order_number = '';
                    $amount = $response->order->order_amount;
                    if($response->order->order_tags){
                        $tags = $response->order->order_tags;
                        $payment_form = $tags->payment_form;
                        $user_id = $tags->user_id;
                    }

                    if($payment_form == 'cart'){
                        $order_number = $response->order->order_id;
                        $cart_id = $response->order->order_tags->cart_id ?? '';
                        $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
                        if ($order) {
                            $order->payment_status = 1;
                            $order->save();
                            $payment_exists = Payment::where('transaction_id', $transactionId)->first();
                            if (!$payment_exists) {
                                $payment = new Payment();
                                $payment->date = date('Y-m-d');
                                $payment->order_id = $order->id;
                                $payment->transaction_id = $transactionId;
                                $payment->balance_transaction = $amount;
                                $payment->type = 'cart';
                                $payment->save();
        
                                // Auto accept order
                                $orderController = new OrderController();
                                $orderController->autoAcceptOrderIfOn($order->id);
        
                                // Remove cart
                                Cart::where('id', $cart_id)->update(['schedule_type' => null, 'scheduled_date_time' => null]);
                                CartAddon::where('cart_id', $cart_id)->delete();
                                CartCoupon::where('cart_id', $cart_id)->delete();
                                CartProduct::where('cart_id', $cart_id)->delete();
                                CartProductPrescription::where('cart_id', $cart_id)->delete();
        
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
                            }
        
                            // Send Email
                            //   $this->successMail();
                        }
                    } elseif($payment_form == 'wallet'){
                        $request->request->add(['user_id' => $user_id, 'wallet_amount' => $amount, 'transaction_id' => $transactionId]);
                        $walletController = new WalletController();
                        $walletController->creditWallet($request);
                    }
                    elseif($payment_form == 'tip'){
                        $order_number = $charges[0]->metadata->order_number;
                        $request->request->add(['user_id' => $user_id, 'order_number' => $order_number, 'tip_amount' => $amount, 'transaction_id' => $transactionId]);
                        $orderController = new OrderController();
                        $orderController->tipAfterOrder($request);
                    }
                    elseif($payment_form == 'subscription'){
                        $subscription = $charges[0]->metadata->subscription_id;
                        $request->request->add(['user_id' => $user_id, 'payment_option_id' => 24, 'amount' => $amount, 'transaction_id' => $transactionId]);
                        $subscriptionController = new UserSubscriptionController();
                        $subscriptionController->purchaseSubscriptionPlan($request, '', $subscription);
                    }
                    break;
                
                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    // \Log::info($paymentIntent);

                    $meta = $paymentIntent->metadata;
                    // \Log::info($meta);
                    $user_id = $payment_form = $order_number = '';
                    // $amount = $paymentIntent->amount / 100;
                    if($meta){
                        $payment_form = $meta->payment_form;
                        $user_id = $meta->user_id;
                    }
                    $user = User::find($user_id);

                    if($payment_form == 'cart'){
                        $order_number = $meta->order_number;
                        $order = Order::where('order_number', $order_number)->first();
                        if($order){
                            $wallet_amount_used = $order->wallet_amount_used;
                            if($wallet_amount_used > 0){
                                $wallet = $user->wallet;
                                $wallet->depositFloat($wallet_amount_used, ['Wallet has been <b>refunded</b> for cancellation of order #'. $order->order_number]);
                            }

                            // $order_products = OrderProduct::select('id')->where('order_id', $order->id)->get();
                            // foreach($order_products as $order_prod){
                            //     OrderProductAddon::where('order_product_id', $order_prod->id)->delete();
                            // }
                            // OrderProduct::where('order_id', $order->id)->delete();
                            // OrderProductPrescription::where('order_id', $order->id)->delete();
                            // VendorOrderStatus::where('order_id', $order->id)->delete();
                            // OrderVendor::where('order_id', $order->id)->delete();
                            // OrderTax::where('order_id', $order->id)->delete();
                            // $order->delete();
                        }
                    }
                    break;
                
                // ... handle other event types
                default:
                    echo 'Received unknown event type ' . $event->type;
            }
        }
        catch(Exception $ex){
            \Log::info($ex->getMessage());
        }
        http_response_code(200);
    }

    public function getPaymentURL(){
        if($this->TEST_MODE == false){
            return 'https://api.cashfree.com/pg';
        }else{
            return 'https://sandbox.cashfree.com/pg';
        }
    }

}
