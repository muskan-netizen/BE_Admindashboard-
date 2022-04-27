<?php

namespace App\Http\Controllers\Api\v1;

use Auth, Log, Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Front\{BaseController, UserSubscriptionController, OrderController, WalletController};
use App\Models\{PaymentOption, Cart, SubscriptionPlansUser, Order, Payment, CartAddon, CartCoupon, CartProduct, CartProductPrescription, CartDeliveryFee, UserVendor, User,OrderProduct, OrderProductAddon, Transaction};

class PaytabController extends BaseController
{
    use \App\Http\Traits\PaytabPaymentManager;
	use \App\Http\Traits\ApiResponser;

	private $application_id;
	private $access_token;
    
	
    public function completePayment(Request $request)
    {
        $user = Auth::user();
        $transaction_id = $request->transaction_id;
    	if($request->action == 'cart'){
            $order_number = $request->order_number;
            $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
            if ($order) {
                $order->payment_status = 1;
                $order->save();
                $payment_exists = Payment::where('transaction_id', $transaction_id)->first();
                if (!$payment_exists) {
                    $payment = new Payment();
                    $payment->date = date('Y-m-d');
                    $payment->order_id = $order->id;
                    $payment->transaction_id = $transactionId;
                    $payment->balance_transaction = $request->amount;
                    $payment->type = 'cart';
                    $payment->save();

                    // Auto accept order
                    $orderController = new OrderController();
                    $orderController->autoAcceptOrderIfOn($order->id);

                    // Remove cart
                    $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
                    Cart::where('id', $cart->id)->update(['schedule_type' => null, 'scheduled_date_time' => null]);
                    CartAddon::where('cart_id', $cart->id)->delete();
                    CartCoupon::where('cart_id', $cart->id)->delete();
                    CartProduct::where('cart_id', $cart->id)->delete();
                    CartProductPrescription::where('cart_id', $cart->id)->delete();
                    CartDeliveryFee::where('cart_id', $cart->id)->delete();

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
            }
        } elseif($request->action == 'wallet'){
            $request->request->add(['wallet_amount' => $request->amount, 'transaction_id' => $transaction_id]);
            $walletController = new WalletController();
            $walletController->creditWallet($request);
        }
        elseif($request->action == 'tip'){
            $request->request->add(['order_number' => $request->order_number, 'tip_amount' => $request->amount, 'transaction_id' => $transaction_id]);
            $orderController = new OrderController();
            $orderController->tipAfterOrder($request);
        }
        elseif($request->action == 'subscription'){
            $request->request->add(['payment_option_id' => 27, 'transaction_id' => $transaction_id]);
            $subscriptionController = new UserSubscriptionController();
            $subscriptionController->purchaseSubscriptionPlan($request, '', $request->subscription_id);
        }
        return $this->successResponse('', __('Payment completed successfully'), 200);
    }
    public function failedPayment($request)
    {
    	if($request->action == 'cart'){
            $order_number = $request->order_number;
            $order = Order::where('order_number', $order_number)->first();
            if($order){
                $wallet_amount_used = $order->wallet_amount_used;
                if($wallet_amount_used > 0){
                    $transaction = Transaction::where('type', 'deposit')->where('meta', 'LIKE', '%'.$order->order_number.'%')->first();
                    if(!$transaction){
                        $wallet = $user->wallet;
                        $wallet->depositFloat($wallet_amount_used, ['Wallet has been <b>refunded</b> for cancellation of order <b>'. $order->order_number. '</b>']);
                    }
                }
            }
        }
        return $this->successResponse(__('Payment failed'), 400);
    }
}
