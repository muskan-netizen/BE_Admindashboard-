<?php

namespace App\Http\Controllers\Front;

use Log;
use Auth;
use Rede;
use Session;

use Illuminate\Http\Request;

use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\WalletController;
use App\Http\Controllers\Front\UserSubscriptionController;
use App\Http\Controllers\Front\PickupDeliveryController;
use App\Models\{User, UserVendor, CaregoryKycDoc,Cart, CartAddon, CartCoupon, CartProduct, CartProductPrescription, CartDeliveryFee, Payment, PaymentOption, Client, ClientPreference, ClientCurrency, Order, OrderProduct, OrderProductAddon, OrderProductPrescription, VendorOrderStatus, OrderVendor, OrderTax, SubscriptionPlansUser, Transaction, UserAddress, UserSavedPaymentMethods, Webhook};
use Illuminate\Support\Facades\Crypt;
use function App\Notifications\via;
use Openpay\Data\Openpay as Openpay;
class OpenpayPaymentController extends FrontController
{
    //https://github.com/open-pay/openpay-php   ###documentations
    use ApiResponser;

    public $openpay_merchant_id;
    public $openpay_private_key;
    public $openpay_public_key;
    public $environment;
    public $openpay;

    public function __construct()
    {
        $openpay = PaymentOption::select('credentials', 'test_mode')->where('code', 'openpay')->where('status', 1)->first();
        $creds_arr = json_decode($openpay->credentials);
        $this->openpay_merchant_id = (isset($creds_arr->openpay_merchant_id)) ? $creds_arr->openpay_merchant_id : '';
        $this->openpay_private_key = (isset($creds_arr->openpay_private_key)) ? $creds_arr->openpay_private_key : '';
        $this->openpay_public_key = (isset($creds_arr->openpay_public_key)) ? $creds_arr->openpay_public_key : '';
        $environment = $this->environment = (isset($openpay->test_mode) && ($openpay->test_mode == '1')) ?  'false' : 'true';
        //pr($environment);
        Openpay::setId($this->openpay_merchant_id);
        Openpay::setApiKey($this->openpay_private_key);
        Openpay::setProductionMode(false);
    }
    public function beforePayment(Request $request) 
    {
      //  $request->merge(['amount_2'=>Crypt::encrypt($request->amount)]);
        $openpay_merchant_id = $this->openpay_merchant_id;
        $openpay_private_key =$this->openpay_private_key;
        $data = Session::get('opnepay_data');
        unset($request['_token']);
        Session::put('opnepay_data',$request->all());
        return view('frontend.payment_gatway.openpay_view')->with([
                                        'data' => $request->all(),
                                        'openpay_merchant_id'=>$this->openpay_merchant_id,
                                        'openpay_private_key'=>$this->openpay_private_key,
                                    ]);
    }

    public function paymentInit(Request $request, $domain='')
    {
        $validatedData = $request->validate([
            'number'        => 'required|min:16|max:20',
            'cvc'           => 'required',
            'holder_name'   => 'required',
            
        ], [
            'number.required'       => __('The Card Number is required.'),
            'cvc.required'          => __('Address Type is required'),
            'holder_name.required'  => __('The Card Holder Name is required.'),
            'number.number'      => __('Incorrect Card Number.'),
            
        ]);
        //pr($request->all());
        $amount      = $request->amount;
        $amount      = $this->getDollarCompareAmount($amount);
        $cart_number =  str_replace(' ', '', $request->number);
        $client  = Client::with('country')->where('id', '>', 0)->first();
        $country_code = $client->country ? $client->country->code : 'MX';
        $user = Auth::user();
        $address = UserAddress::where('user_id', $user->id)->first() ;
        $order_number  = $request->order_number ?? generateOrderNo();
        $payment_form  = $request->payment_form ?? 'cart';
        $cart_id  = '';
      
        if($payment_form == 'cart'){
            $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
            $cart_id =  $cart->id;
        }
        
        //pr(Openpay::getProductionMode());
        //try {
            Openpay::setId($this->openpay_merchant_id);
            Openpay::setApiKey($this->openpay_private_key);
            $openpay = Openpay::getInstance($this->openpay_merchant_id, $this->openpay_private_key, $country_code);
            
            $saved_payment_method = $this->getSavedUserPaymentMethod($request);
         
            if (!$saved_payment_method) {
                $customerData = array(
                        'name' => $user->name,
                        'last_name' => '',
                        'email' => $user->email,
                        'phone_number' => $user->phone_number,
                        'address' => array(
                                    'line1' =>  $user->address->first() ? $user->address->first()->address : "Teofilo" ,
                                    'line2' =>'',//  $user->address->first()->address,
                                    'line3' => '',
                                    'postal_code' => $user->address->first() ? $user->address->first()->pincode : "76920",
                                    'state' => $user->address->first() ? $user->address->first()->state : "Querétaro",
                                    'city' => $user->address->first() ? $user->address->first()->city : "Querétaro",
                                    'country_code' => $country_code ?? 'MX'
                                    )
                        );
                $customerResponse = $openpay->customers->add($customerData);
                
                $customer_id = $customerResponse->id;
                //if(isset($customer_id)){
                    $payment_method = new UserSavedPaymentMethods;
                    $payment_method->user_id = Auth::user()->id;
                    $payment_method->payment_option_id = 41;
                    $payment_method->customerReference = $customer_id;
                    $payment_method->save();
                //}
            }else{
                $customer_id = $saved_payment_method->customerReference;
            }
            Log::info('cystomer');
            Log::info($customer_id);
            $openPayCustomer  = $openpay->customers->get($customer_id);
            $cart_number =  str_replace(' ', '', $request->number);
            $card_last_four_digit = substr(  $cart_number, -4); 
           
            $saved_payment_cart = UserSavedPaymentMethods::where(['user_id'=>Auth::user()->id,'card_last_four_digit'=> $card_last_four_digit,'card_expiry_month'=>   $request->expMonth,'card_expiry_year'=> $request->expYear])->first();
           
            if(!$saved_payment_cart)
            {
                $cardData = array(
                    'holder_name' =>  $request->holder_name,
                    'card_number' => $cart_number,
                    'cvv2' =>  $request->cvc,
                    'expiration_month' =>  $request->expMonth,
                    'expiration_year' =>  $request->expYear,
                    'address' => array(
                            'line1' =>  $user->address->first() ? $user->address->first()->address : "Teofilo" ,
                            'line2' =>'',//  $user->address->first()->address,
                            'line3' => '',
                            'postal_code' => $user->address->first() ? $user->address->first()->pincode : "76920",
                            'state' => $user->address->first() ? $user->address->first()->state : "Querétaro",
                            'city' => $user->address->first() ? $user->address->first()->city : "Querétaro",
                            'country_code' => $country_code ?? 'MX'
                        )
                    );
                //  saved cart;
                $savCart    =   $openPayCustomer->cards->add($cardData);

                $cart_id    =   $savCart->id;
                
                $payment_method                         = new UserSavedPaymentMethods;
                $payment_method->user_id                = Auth::user()->id;
                $payment_method->payment_option_id      = $request->payment_option_id;
                $payment_method->card_last_four_digit   = $card_last_four_digit ?? NULL;
                $payment_method->card_expiry_month      = $request->expMonth ?? NULL;
                $payment_method->card_expiry_year       = $request->expYear ?? NULL;
                $payment_method->customerReference      = $customer_id;
                $payment_method->cardReference          = $cart_id;
                $payment_method->save();

            } else {
                $cart_id = $saved_payment_cart->cardReference;
            }
            Log::info('cart_id');
            Log::info($cart_id );
            $openPayCustomerCart = $openPayCustomer->cards->get($cart_id);
            //pr($openPayCustomerCart);
            // create charges tragi
            $order_info = [
                'payment_form'=>  $payment_form ,
                'user_id'=> auth()->user()->id,
                'subscription_id' =>$request->subscription_id ?? '',
                'cart_id' =>$cart_id,
            ];
            $description = json_encode($order_info);
            $chargeData = array(
                    'method' => 'card',
                    'source_id' => $cart_id,
                    'amount' => $amount,
                    'description' =>  $description,
                    'order_id' => $order_number ,
                    "device_session_id"=> $request->deviceIdHiddenFieldName,
            );
            
            $openPayCustomerCharges = $openPayCustomer->charges->create($chargeData);
           // pr($openPayCustomerCharges);
        // } catch (\Exception $e) {
        //     $data = Session::get('opnepay_data');
        //     unset($data['_token']);
        //     Log::info($e->getMessage());
           
        //     return Redirect::to(route('payment.opnepay.beforePayment',$data))->with('error',$e->getMessage());
        // }
    }
    
    public function opnepayWebhook(Request $request, $domain = '')
    {
        Log::info($request->all());
        $request = (object)$request->all();
        // Handle the event
        switch ($request->type) {
            case 'charge.succeeded':
                $meta_data      = json_decode($request->transaction->description);
                Log::info($meta_data);
                $cart_id        = $meta_data->cart_id ? $meta_data->cart_id : '';
                $payment_form   = $meta_data->payment_form;
                $user_id        = $meta_data->user_id;
                $transactionId  = $request->transaction->id;
                $order_number   = $request->transaction->order_id;
                $amount         = $request->transaction->amount;
                Log::info('amount');
                Log::info($amount);
                if($payment_form == 'cart'){
                    
            
                    
                   
                    $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
                    if ($order) {
                        $order->payment_status = 1;
                        $order->save();
                        $payment_exists = Payment::where('transaction_id', $transactionId)->first();
                        if (!$payment_exists) {
                            $payment = new Payment();
                            $payment->date = date('Y-m-d');
                            $payment->user_id = $user_id;
                            $payment->order_id = $order->id;
                            $payment->transaction_id = $transactionId;
                            $payment->balance_transaction = $amount;
                            $payment->payment_option_id = 4;
                            $payment->type = 'cart';
                            $payment->save();

                            // Deduct wallet amount if payable amount is successfully done on gateway
                            if ( $order->wallet_amount_used > 0 ) {
                                $user = User::find($user_id);
                                $wallet = $user->wallet;
                                $transaction_exists = Transaction::where('type', 'withdraw')->where('meta', 'LIKE', '%order_number%')->where('meta', 'LIKE', '%'.$order->order_number.'%')->first();
                                if(!$transaction_exists){
                                    $wallet->withdrawFloat($order->wallet_amount_used, [
                                        'description' => 'Wallet has been <b>debited</b> for order number <b>' . $order->order_number . '</b>',
                                        'order_number' => $order->order_number,
                                        'transaction_id' => $transactionId,
                                        'payment_option' => 'Stripe'
                                    ]);
                                }
                            }
    
                            // Auto accept order
                            $orderController = new OrderController();
                            $orderController->autoAcceptOrderIfOn($order->id);
    
                            // Remove cart
                            CaregoryKycDoc::where('cart_id',$cart_id)->update(['ordre_id'=> $order->id,'cart_id'=>'' ]);
                            Cart::where('id', $cart_id)->update(['schedule_type' => null, 'scheduled_date_time' => null]);
                            CartAddon::where('cart_id', $cart_id)->delete();
                            CartCoupon::where('cart_id', $cart_id)->delete();
                            CartProduct::where('cart_id', $cart_id)->delete();
                            CartProductPrescription::where('cart_id', $cart_id)->delete();
                            CartDeliveryFee::where('cart_id', $cart_id)->delete();
                  
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

                            $request = new Request(['user_id'=>$order->user_id,'address_id'=>$order->address_id]);

                            //Send Email to customer
                            $orderController->sendSuccessEmail($request, $order);
                            //Send Email to Vendor
                            foreach ($order->vendors->groupBy('vendor_id') as $vendor_id => $vendor_cart_products) {
                                $orderController->sendSuccessEmail($request, $order, $vendor_id);
                            }
                            // send sms 
                            $this->sendSuccessSMS($request, $order);
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
                    $request->request->add(['user_id' => $user_id, 'payment_option_id' => 19, 'amount' => $amount, 'transaction_id' => $transactionId]);
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
                        // $wallet_amount_used = $order->wallet_amount_used;
                        // if($wallet_amount_used > 0){
                        //     $wallet = $user->wallet;
                        //     $wallet->depositFloat($wallet_amount_used, ['Wallet has been <b>refunded</b> for cancellation of order #'. $order->order_number]);
                        // }

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
        http_response_code(200);
    }

}
