<?php

namespace App\Http\Controllers\Front;

use Auth;
use Session;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\WalletController;
use App\Http\Controllers\Front\UserSubscriptionController;
use App\Models\{User, UserVendor, Cart, CartAddon, CartCoupon, CartProduct, CartProductPrescription, Payment, PaymentOption, Client, ClientPreference, ClientCurrency, Order, OrderProduct, OrderProductAddon, OrderProductPrescription, VendorOrderStatus, OrderVendor, OrderTax, SubscriptionPlansUser, UserAddress};

class StripeGatewayController extends FrontController
{

    use ApiResponser;
    public $gateway;
    public $currency;

    public function __construct()
    {
        $stripe_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'stripe')->where('status', 1)->first();
        $creds_arr = json_decode($stripe_creds->credentials);
        $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
        $testmode = (isset($stripe_creds->test_mode) && ($stripe_creds->test_mode == '1')) ? true : false;
        $this->gateway = Omnipay::create('Stripe');
        $this->gateway->setApiKey($api_key);
        $this->gateway->setTestMode($testmode); //set it to 'false' when go live

        $primaryCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
        $this->currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'USD';
    }

    public function postPaymentViaStripe(request $request)
    {
        try {
            $user = Auth::user();
            $address = UserAddress::where('user_id', $user->id);
            $amount = $this->getDollarCompareAmount($request->amount);
            $token = $request->input('stripe_token');
            $response = $this->gateway->purchase([
                'currency' => $this->currency,
                'token' => $token,
                'amount' => $amount,
                'metadata' => ['cart_id' => ($request->cart_id) ? $request->cart_id : 0],
                'description' => 'This is a test purchase transaction.',
            //     'name'=>Auth::user()->name,
            //     'address' => [
            //        'line1'       => '510 Townsend St',
            //        'postal_code' => '98140',
            //        'city'        => 'San Francisco',
            //        'state'       => 'CA',
            //        'country'     => 'US',
            //    ],
                // 'name' => Auth::user()->name,
                // 'address' => $address->address . ', ' . $address->state . ', ' . $address->country . ', ' . $address->pincode,
            ])->send();
            if ($response->isSuccessful()) {
               // $this->successMail();
                return $this->successResponse($response->getData());
            }
            // elseif ($response->isRedirect()) {
            //     return $this->errorResponse($response->getRedirectUrl(), 400);
            // } 
            else {
                $this->failMail();
                return $this->errorResponse($response->getMessage(), 400);
            }
        } catch (\Exception $ex) {
            $this->failMail();
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    public function subscriptionPaymentViaStripe(request $request)
    {
        try {
            $user = Auth::user();
            $address = UserAddress::where('user_id', $user->id);
            $token = $request->stripe_token;
            $plan = SubscriptionPlansUser::where('slug', $request->subscription_id)->firstOrFail();
            $saved_payment_method = $this->getSavedUserPaymentMethod($request);
            if (!$saved_payment_method) {
                $customerResponse = $this->gateway->createCustomer(array(
                    'description' => 'Creating Customer for subscription',
                    'email' => $request->email,
                    'source' => $token
                ))->send();
                // Find the card ID
                $customer_id = $customerResponse->getCustomerReference();
                if ($customer_id) {
                    $request->request->set('customerReference', $customer_id);
                    $save_payment_method_response = $this->saveUserPaymentMethod($request);
                }
            } else {
                $customer_id = $saved_payment_method->customerReference;
            }

            // $subscriptionResponse = $this->gateway->createSubscription(array(
            //     "customerReference" => $customer_id,
            //     'plan' => 'Basic Plan',
            // ))->send();

            $amount = $this->getDollarCompareAmount($request->amount);
            $authorizeResponse = $this->gateway->authorize([
                'amount' => $amount,
                'currency' => $this->currency,
                'description' => 'This is a subscription purchase transaction.',
                'customerReference' => $customer_id
            ])->send();
            if ($authorizeResponse->isSuccessful()) {
                $purchaseResponse = $this->gateway->purchase([
                    'currency' => $this->currency,
                    'amount' => $amount,
                    'metadata' => ['user_id' => $user->id, 'plan_id' => $plan->id],
                    'description' => 'This is a subscription purchase transaction.',
                    'customerReference' => $customer_id
                ])->send();
                if ($purchaseResponse->isSuccessful()) {
                  //  $this->successMail();
                    return $this->successResponse($purchaseResponse->getData());
                } else {
                    $this->failMail();
                    return $this->errorResponse($purchaseResponse->getMessage(), 400);
                }
            } else {
                $this->failMail();
                return $this->errorResponse($authorizeResponse->getMessage(), 400);
            }
        } catch (\Exception $ex) {
            $this->failMail();
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }


    ///// Stripe FPX Payment /////

    public function createStripeFPXPaymentIntent(Request $request)
    {
        try{
            ////// Create webhook Endpoint ///////
            $secret_key = stripeFPXPaymentCredentials()->secret_key;
            $stripe = new \Stripe\StripeClient($secret_key);

            $res = $stripe->webhookEndpoints->create([
                'url' => url('payment/webhook/stripe_fpx'),
                'enabled_events' => [
                    'payment_intent.succeeded',
                    'payment_intent.payment_failed'
                ]
            ]);

            $user = Auth::user();
            $order_number = $request->order_number;
            $address_id = $request->address_id;
            $user_address = UserAddress::where('id', $address_id)->first();
            $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();

            $postdata = [
                'payment_method_types' => ['fpx'],
                'amount' => $request->amount * 100,
                'currency' => 'myr', //$this->currency
                'description' => 'Payment',
                // 'customer' => $user->id,
                'receipt_email' => $user->email ?? '',
                'metadata' => [
                    'cart_id' => $cart->id,
                    'order_number' => $order_number,
                    'payment_form' => $request->payment_form
                ],
                // 'billing_details' => [
                //     'name' => $user->name,
                //     'email' => $user->email,
                //     'phone' => $user->dial_code . $user->phone_number,
                //     'address' => [
                //         'line1' => $user_address->street,
                //         'city' => $user_address->city,
                //         'state' => $user_address->state,
                //         'country' => $user_address->country,
                //         'postal_code' => $user_address->pincode
                //     ]
                // ],
                'shipping' => [
                    'name' => $user->name,
                    'phone' => $user->dial_code . $user->phone_number,
                    'address' => [
                        'line1' => $user_address->street,
                        'city' => $user_address->city,
                        'state' => $user_address->state,
                        'country' => $user_address->country,
                        'postal_code' => $user_address->pincode
                    ]
                ]
            ];
            $payment_intent = $stripe = $stripe->paymentIntents->create($postdata);
            
            return $this->successResponse($payment_intent->client_secret);
        }
        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), $ex->getCode());
        }
    }

    public function retrieveStripeFPXPaymentIntent(Request $request){
        try {
            if($request->has('payment_intent')){
                if($request->has('redirect_status') && ($request->redirect_status == 'succeeded')){
                    $secret_key = stripeFPXPaymentCredentials()->secret_key;
                    \Stripe\Stripe::setApiKey($secret_key);
    
                    $payment_intent_id = $request->get('payment_intent');
                    $intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
                    $charges = $intent->charges->data;
                    $transactionId = $cart_id = $payment_form = $order_number = '';
                    $amount = 0;
                    if(count($charges)){
                        $transactionId = $charges[0]->balance_transaction;
                        $payment_form = $charges[0]->metadata->payment_form;
                        $order_number = $charges[0]->metadata->order_number;
                        $cart_id = $charges[0]->metadata->cart_id ?? '';
                        $amount = $charges[0]->amount / 100;
                    }
                    
                    // dd($charges[0]);

                    if($payment_form == 'cart'){
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
                            $returnUrlParams = ''; //'?gateway=paylink&order=' . $order->id;
                            $returnUrl = route('order.success', $order->id); // route('order.return.success');
                            return Redirect::to(url($returnUrl . $returnUrlParams));
        
                            // Send Email
                            //   $this->successMail();
                        }
                    } elseif($request->payment_form == 'wallet'){
                        $request->request->add(['wallet_amount' => $request->amount, 'transaction_id' => $transactionId]);
                        $walletController = new WalletController();
                        $walletController->creditWallet($request);
                        $returnUrl = route('user.wallet');
                        return Redirect::to(url($returnUrl));
                    }
                    elseif($request->payment_form == 'tip'){
                        $request->request->add(['order_number' => $request->order, 'tip_amount' => $request->amount, 'transaction_id' => $transactionId]);
                        $orderController = new OrderController();
                        $orderController->tipAfterOrder($request);
                        $returnUrl = route('user.orders');
                        return Redirect::to(url($returnUrl));
                    }
                    elseif($request->payment_form == 'subscription'){
                        $request->request->add(['payment_option_id' => 9, 'transaction_id' => $transactionId]);
                        $subscriptionController = new UserSubscriptionController();
                        $subscriptionController->purchaseSubscriptionPlan($request, '', $request->subscription);
                        $returnUrl = route('user.subscription.plans');
                        return Redirect::to(url($returnUrl));
                    }
                }
                elseif($request->has('redirect_status') && ($request->redirect_status == 'failed')){
                    if($request->has('order')){
                        $order = Order::where('order_number', $request->order)->first();
                        if($order){
                            $order_products = OrderProduct::select('id')->where('order_id', $order->id)->get();
                            foreach($order_products as $order_prod){
                                OrderProductAddon::where('order_product_id', $order_prod->id)->delete();
                            }
                            OrderProduct::where('order_id', $order->id)->delete();
                            OrderProductPrescription::where('order_id', $order->id)->delete();
                            VendorOrderStatus::where('order_id', $order->id)->delete();
                            OrderVendor::where('order_id', $order->id)->delete();
                            OrderTax::where('order_id', $order->id)->delete();
                            $order->delete();
                            return Redirect::to(route('showCart'))->with('error', 'Your order has been cancelled');
                        }
                    }
                }
            }
        }
        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), $ex->getCode);
        }
    }


    public function webhook(Request $request)
    {
        $secret_key = stripeFPXPaymentCredentials()->secret_key;
        \Stripe\Stripe::setApiKey($secret_key);

        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                \Log::info($paymentIntent);
                break;
            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }
        
        http_response_code(200);
    }
}
