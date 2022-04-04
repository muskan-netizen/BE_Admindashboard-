<?php

namespace App\Http\Controllers\Api\v1;

use Session;
use Exception;
use Stripe\Stripe;
use Omnipay\Omnipay;
use App\Models\Payment;
use Slim\Http\Response;
use App\Models\Transaction;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Models\PaymentOption;
use App\Models\ClientCurrency;
use Omnipay\Common\CreditCard;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\v1\OrderController;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSavedPaymentMethods;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Front\WalletController;
use App\Http\Controllers\Front\UserSubscriptionController;
use App\Models\{User, UserVendor, Cart, CartAddon, CartCoupon, CartProduct, CartProductPrescription, Client, ClientPreference, Order, OrderProduct, OrderProductAddon, OrderProductPrescription, VendorOrderStatus, OrderVendor, OrderTax, SubscriptionPlansUser};

class PaymentResourceController extends BaseController
{
    use ApiResponser;
    public $currency;
    // For Stripe - To get Payment Intent
    public function createPaymentIntent(Request $request)
    {
        $stripe_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'stripe')->where('status', 1)->first();
        $creds_arr = json_decode($stripe_creds->credentials);
        $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
        $testmode = (isset($stripe_creds->test_mode) && ($stripe_creds->test_mode == '1')) ? true : false;

        $primaryCurrency = ClientCurrency::where('currency_id', '=', $request->header('currency'))->first();
        $this->currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'USD';

        $user = Auth::user();
        \Stripe\Stripe::setApiKey($api_key);

        $saved_payment_method = UserSavedPaymentMethods::where('user_id', $user->id)->where('payment_option_id', $request->payment_option_id)->first();
        if (!$saved_payment_method) {
        
            $address = UserAddress::where('user_id', $user->id);
            $customerResponse = \Stripe\Customer::create(array(  
                'description' => 'Creating Customer',
                'name' => $user->name,
                'email' => $user->email,
                'metadata' => [
                    'user_id' => $user->id,
                    'phone_number' => $user->phone_number
                ]
            ));  
            $customer_id = $customerResponse['id'];
            if ($customer_id) {
                $payment_method = new UserSavedPaymentMethods;
                $payment_method->user_id = Auth::user()->id;
                $payment_method->payment_option_id = $request->payment_option_id;
                $payment_method->customerReference = $customer_id;
                $payment_method->save();
            }
        }else {
            $customer_id = $saved_payment_method->customerReference;
        }

        $intent = \Stripe\PaymentIntent::create([
            'payment_method'       => $request->payment_method_id,
            'amount'               => $request->amount * 100,
            'currency'             => $this->currency,
            'confirmation_method'  => 'automatic',
            'confirm'              => true,
            'customer'             => $customer_id
        ]);

        return $intent;
    }

    // Confirm Payment Intent For Stripe
    public function confirmPaymentIntent(Request $request)
    {
       
        $stripe_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'stripe')->where('status', 1)->first();
        $creds_arr = json_decode($stripe_creds->credentials);
        $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
         \Stripe\Stripe::setApiKey($api_key);
         
         $intent = \Stripe\PaymentIntent::retrieve(
           $request->payment_intent_id
        );

        if($intent->status == 'succeeded'){
            // PAyment intent is already confirmed by SDK in FROntEnd
            $amount            = $request->amount;
            $address_id        = ($request->has('address_id')) ? $request->address_id : "";
            $order_number      = ($request->has('order_number')) ? $request->order_number : "";
            $payment_form      = ($request->has('action')) ? $request->action : "";
            $payment_option_id = ($request->has('payment_option_id')) ? $request->payment_option_id : "4";
            $subscription_slug = ($request->has('subscription_slug')) ? $request->subscription_slug : "";
            $tip_amount        = ($request->has('tip_amount')) ? $request->tip_amount : "";
            

            $parameters = [
                'transaction_id'    => $intent->id,
                'total_amount'      => $request->amount,
                'payment_option_id' => $payment_option_id,
                'address_id'        => $address_id,
                'order_number'      => $order_number,
                'payment_form'      => $payment_form,
                'subscription_slug' => $subscription_slug,
                'tip_amount'        => $tip_amount
            ];

            $result = $this->checkStripeReturnDataFrom3DAuth($request, $parameters);
            return $result;

        }else{
            return response()->json('error', 'Sorry, We cannot procees your payment.');
        }
      
    }

    public function checkStripeReturnDataFrom3DAuth($request, $parameters)
    {
        try {
            $user    = Auth::user();
            $address = UserAddress::where('user_id', $user->id);
            $amount = $parameters['total_amount'];
            $payment_form = $parameters['payment_form'];
            $transactionId = $parameters['transaction_id'];
            $subscription_slug = $parameters['subscription_slug'];
            $tip_amount = $parameters['tip_amount'];

            if($parameters['payment_form'] == 'cart'){
                $address_id = $parameters['address_id'];
                $user_address = UserAddress::where('id', $address_id)->first();
                $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
                $order_number = $parameters['order_number'];

                $postdata['description'] = 'Order Checkout';
                $postdata['metadata']['cart_id'] = $cart->id;
                $postdata['metadata']['order_number'] = $order_number;
            }
            elseif($payment_form == 'wallet'){
                $postdata['description'] = 'Wallet Checkout';
            }
            if($payment_form == 'tip'){
                $postdata['description'] = 'Tip Checkout';
                $order_number = $parameters['order_number'];
                $postdata['metadata']['order_number'] = $order_number;
            }
            elseif($payment_form == 'subscription'){
                $postdata['description'] = 'Subscription Checkout';
                $postdata['metadata']['subscription_id'] = $subscription_slug;
            }
                    $returnUrl = '';

                    if($payment_form == 'cart'){ 
                        $request->request->add(['transaction_id' => $transactionId]);
                        $orderController = new OrderController();
                        $result = $orderController->postPlaceOrder($request);
                        $returnUrl = $result;
                    } elseif($payment_form == 'wallet'){
                        $walletController = new WalletController();
                        $result  =  $this->creditMyWallet($parameters);
                        $returnUrl = $result;
                    }
                    elseif($payment_form == 'tip'){
                        $request->request->add(['order_number' => $order_number, 'tip_amount' => $tip_amount, 'transaction_id' => $transactionId]);
                        $orderController = new OrderController();
                        $result = $orderController->tipAfterOrder($request);
                        return $result;
                    }
                    elseif($payment_form == 'subscription'){
                        $request->request->add(['payment_option_id' => $parameters['payment_option_id'], 'amount' => $amount, 'transaction_id' => $transactionId]);
                        $subscriptionController = new UserSubscriptionController();
                        $result = $subscriptionController->purchaseSubscriptionPlan($request, '' ,$subscription_slug);
                        return $result;
                    }
                    return $returnUrl;
         
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    // Credit My Wallet
    public function creditMyWallet($parameters)
    {   
        $transactionId = $parameters['transaction_id'];

        $user = Auth::user();
        if($user){
            $credit_amount = $parameters['total_amount'];
            $wallet = $user->wallet;
            if ($credit_amount > 0) {
                $wallet->depositFloat($credit_amount, ['Wallet has been <b>Credited</b> by transaction reference <b>'.$transactionId.'</b>']);

                $payment = new Payment();
                $payment->date = date('Y-m-d');
                $payment->user_id = $user->id;
                $payment->transaction_id = $parameters['transaction_id'];
                $payment->payment_option_id =  $parameters['payment_option_id'];
                $payment->balance_transaction = $credit_amount;
                $payment->type = 'wallet_topup';
                $payment->save();

                $transactions = Transaction::where('payable_id', $user->id)->get();
                $response['wallet_balance'] = $wallet->balanceFloat;
                $response['transactions'] = $transactions;
                $message = 'Wallet has been credited successfully';
                return $this->successResponse($response, $message, 201);
            }
            else{
                return $this->errorResponse('Amount is not sufficient', 402);
            }
        }
        else{
            return $this->errorResponse('Invalid User', 402);
        }
    }


}
