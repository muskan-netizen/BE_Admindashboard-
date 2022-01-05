<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Front\{UserSubscriptionController, OrderController, WalletController, FrontController};
use Auth, Log, Redirect;
use App\Models\{PaymentOption, Cart, SubscriptionPlansUser, Order, Payment, CartAddon, CartCoupon, CartProduct, CartProductPrescription, UserVendor, User,OrderProductAddon,OrderProduct,OrderProductPrescription,VendorOrderStatus,OrderVendor,OrderTax};

class PagarmeController extends FrontController
{
    use \App\Http\Traits\PagarmePaymentManager;
	use \App\Http\Traits\ApiResponser;

	private $api_key;
	private $secret_key;
	public function __construct()
  	{
		$pagarme_creds = PaymentOption::getCredentials('pagarme');
	    $creds_arr = json_decode($pagarme_creds->credentials);
	    $this->api_key = $creds_arr->api_key??'';
	    $this->secret_key = $creds_arr->secret_key??'';
	}

    public function beforePayment(Request $request) 
    {
        $data = $request->all();
        $data['come_from'] = 'app';
        if($request->isMethod('post'))
        {
            $data['come_from'] = 'web';
        }
        return view('frontend.payment_gatway.pgarmne_view')->with(['data' => $data]);
    }
    public function createPaymentCard(Request $request)
    {
        try{
            $request['card_number'] = str_replace(' ', '', $request->number);
            $data = $request->all();
            $card = $this->create_card($data);
            return $this->successResponse($card->id);
        }catch(Exception $ex){
            return $this->errorResponse();
        }
    }
    public function createPayment(Request $request)
    {
        if($request->come_from == "app")
        {
            $user = User::where('auth_token', $request->auth_token)->first();
            Auth::login($user);
        }
    	$user = Auth::user();
    	$cart = Cart::where('status', '0')->where('user_id', $user->id)->first();
        $amount = $this->getDollarCompareAmount($request->amount);
    	$request['card_number'] = str_replace(' ', '', $request->number);
    	$request['customer'] = $user;
    	$request['amount'] = $amount*100;
    	$request['items'] = [];
    	$data = $request->all();
        if($request->payment_from == 'cart'){
        	$item['id'] = "1";
            $item['title'] = "Cart Checkout";
            $item['unit_price'] = $amount*100;
            $item['quantity'] = 1;
            $item['tangible'] = true;
        }
        elseif($request->payment_from == 'wallet'){
            $item['id'] = "1";
        	$item['title'] = "Wallet Checkout";
        	$item['unit_price'] = $amount*100;
        	$item['quantity'] = 1;
        	$item['tangible'] = true;
        }
        elseif($request->payment_from == 'tip'){
            $item['id'] = "1";
        	$item['title'] = "Tip Checkout";
        	$item['unit_price'] = $amount*100;
        	$item['quantity'] = 1;
        	$item['tangible'] = true;
        }
        elseif($request->payment_from == 'subscription'){
            $item['id'] = "1";
        	$item['title'] = "Subscription Checkout";
        	$item['unit_price'] = $amount*100;
        	$item['quantity'] = 1;
        	$item['tangible'] = true;
        }
        array_push($data['items'],$item);
    	$payment = $this->create_transaction($data);
    	$request['amount'] = $amount;
    	if($payment->status == 'paid')
    	{
            $returnUrl = $this->sucessPayment($request,$payment);
        } else{
            $returnUrl = $this->failedPayment($request,$payment);
        }
        // dd($returnUrl);
        return Redirect::to(url($returnUrl));
    }
    public function sucessPayment($request, $pamyent)
    {
        if($request->come_from == "app")
        {
            $user = User::where('auth_token', $request->auth_token)->first();
            Auth::login($user);
        }
        $user = Auth::user();
    	$transactionId = $pamyent->id;
    	if($request->payment_from == 'cart'){
            $order_number = $request->order_number;
            $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
            if ($order) {
                $order->payment_status = 1;
                $order->save();
                $payment_exists = Payment::where('transaction_id', $transactionId)->first();
                if (!$payment_exists) {
                    Payment::insert([
                        'date' => date('Y-m-d'),
                        'order_id' => $order->id,
                        'transaction_id' => $transactionId,
                        'balance_transaction' => $request->amount,
                        'type' => 'cart'
                    ]);

                    // Auto accept order
                    $orderController = new OrderController();
                    $orderController->autoAcceptOrderIfOn($order->id);
                    $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();


                    // Remove cart
                    Cart::where('id', $cart->id)->update(['schedule_type' => null, 'scheduled_date_time' => null]);
                    CartAddon::where('cart_id', $cart->id)->delete();
                    CartCoupon::where('cart_id', $cart->id)->delete();
                    CartProduct::where('cart_id', $cart->id)->delete();
                    CartProductPrescription::where('cart_id', $cart->id)->delete();

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
                if($request->come_from == 'app')
                {
                    $returnUrl = route('payment.gateway.return.response').'/?gateway=pagarme'.'&status=200&transaction_id='.$transactionId.'&order='.$order_number;
                }else{
                    $returnUrl = route('order.return.success');
                }
                return $returnUrl;
            }
        } elseif($request->payment_from == 'wallet'){
            $request->request->add(['wallet_amount' => $request->amount, 'transaction_id' => $transactionId]);
            $walletController = new WalletController();
            $walletController->creditWallet($request);
            if($request->come_from == 'app')
            {
                $returnUrl = route('payment.gateway.return.response').'/?gateway=pagarme'.'&status=200&transaction_id='.$transactionId;
            }else{
                $returnUrl = route('user.wallet');
            }
            return $returnUrl;
        }
        elseif($request->payment_from == 'tip'){
            $request->request->add(['order_number' => $request->order_number, 'tip_amount' => $request->amount, 'transaction_id' => $transactionId]);
            $orderController = new OrderController();
            $orderController->tipAfterOrder($request);
            $returnUrl = route('user.orders');
            return $returnUrl;
        }
        elseif($request->payment_from == 'subscription'){
            $request->request->add(['payment_option_id' => 12, 'transaction_id' => $transactionId]);
            $subscriptionController = new UserSubscriptionController();
            $subscriptionController->purchaseSubscriptionPlan($request, '', $request->subscription_id);
            if($request->come_from == 'app')
            {
                $returnUrl = route('payment.gateway.return.response').'/?gateway=pagarme'.'&status=200&transaction_id='.$transactionId; 
            }else{
                $returnUrl = route('user.subscription.plans');
            }
            return $returnUrl;
        }
        return Redirect::to(route('order.return.success'));
    }
    public function failedPayment($request, $pamyent)
    {
    	if($request->payment_from == 'cart'){
            $order_number = $request->order_number;
            $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
            $order_products = OrderProduct::select('id')->where('order_id', $order->id)->get();
            foreach ($order_products as $order_prod) {
                OrderProductAddon::where('order_product_id', $order_prod->id)->delete();
            }
            OrderProduct::where('order_id', $order->id)->delete();
            OrderProductPrescription::where('order_id', $order->id)->delete();
            VendorOrderStatus::where('order_id', $order->id)->delete();
            OrderVendor::where('order_id', $order->id)->delete();
            OrderTax::where('order_id', $order->id)->delete();
            Order::where('id', $order->id)->delete();
            if($request->come_from == 'app')
            {
                $returnUrl = route('payment.gateway.return.response').'/?gateway=pagarme&status=0';
            }else{
                $returnUrl = route('showCart');
            }
            return $returnUrl;
        }
        elseif($request->payment_form == 'wallet'){
            if($request->come_from == 'app')
            {
                $returnUrl = route('payment.gateway.return.response').'/?gateway=pagarme&status=0';
            }else{
                $returnUrl = route('user.wallet');
            }
            return $returnUrl;
        }
        elseif($request->payment_form == 'tip'){
            if($request->come_from == 'app')
            {
                $returnUrl = route('payment.gateway.return.response').'/?gateway=pagarme&status=0';
            }else{
                $returnUrl = route('user.orders');
            }
            return $returnUrl;
        }
        elseif($request->payment_form == 'subscription'){
            if($request->come_from == 'app')
            {
                $returnUrl = route('payment.gateway.return.response').'/?gateway=pagarme&status=0';
            }else{
                $returnUrl = route('user.subscription.plans');
            }
            return $returnUrl;
        }
        return route('order.return.success');
    }
}
