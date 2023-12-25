<?php

namespace App\Http\Traits;
use App\Http\Controllers\Api\v1\PickupDeliveryController as V1PickupDeliveryController;
use App\Http\Controllers\Api\v1\UserSubscriptionController;
use App\Models\PaymentOption;
use Auth, Log, Config, Session;
use GuzzleHttp\Client;
use App\Models\ClientCurrency;
use App\Models\CartAddon;
use App\Models\UserVendor;
use App\Models\CartCoupon;
use App\Models\UserAddress;
use App\Models\CartProduct;
use App\Models\CartProductPrescription;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\CaregoryKycDoc;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Routing\UrlGenerator;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\PickupDeliveryController;
use App\Http\Controllers\Front\WalletController;
use App\Models\Cart;

trait ThawanipaymentManager
{
    public function getDetails(){
        
        $payOption           = PaymentOption::select('credentials', 'test_mode', 'status')->where('code', 'thawani')->where('status', 1)->first();
        $credentials         = json_decode($payOption->credentials);
        $thawani_Apikey      = $credentials->thawani_Apikey;
        $thawani_publishKey  = $credentials->thawani_publishKey;
        $primaryCurrency     = ClientCurrency::where('is_primary', '=', 1)->first();
        $currency            = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'OMR';
       
        if(json_decode($payOption->test_mode) == 1){
          $checkoutUrl = "https://uatcheckout.thawani.om/api/v1/checkout/session";
          $payurl ="https://uatcheckout.thawani.om";
        }else{
            $checkoutUrl = "https://checkout.thawani.om/api/v1/checkout/session";
            $payurl ="https://checkout.thawani.om";
        }
        return [
            'checkout_url' =>$checkoutUrl,
            'payurl' =>$payurl,
            'thawani_Apikey' => $thawani_Apikey,
            'thawani_publishKey' => $thawani_publishKey,
            'currency' => $currency,
        ];
    }
    public function orderNumber($request)
    {

        $time   = '';
        $amt    = $request->amt??$request->amount;

        switch ($request->payment_from) {
            case 'cart':
                $request->amt = $amt;
                $time = $request->order_number;
                Payment::create(['amount'=>0,'transaction_id'=>$time,'balance_transaction'=>$amt,'type'=>'cart','date'=>date('Y-m-d')]);
            break;

            case 'pickup_delivery':
                //Payment::create(['user_id' => auth()->id(),'payment_from' => $request->device ?? 'web',]);
            break;

            case 'wallet':
                $time = $request->transaction_id ?? 'W_' . time();
                Payment::create([ 'amount' => 0,'transaction_id' => $time,'balance_transaction' => $amt,'type' => 'wallet','date' => date('Y-m-d')]);
            break;

            case 'tip':
                $time = 'T_' . time() . '_' . $request->order_number;
                Payment::create(['amount' => 0,'transaction_id' => $time,'balance_transaction' => $amt,'type' => 'tip','date' => date('Y-m-d')]);
            break;

            case 'subscription':
                $time = 'S_' . time() . '_' . (!empty($request->subsid) ? $request->subsid : $request->subscription_id);
                Payment::create(['amount' => 0,'transaction_id' => $time,'balance_transaction' => $amt,'type' => 'subscription','date' => date('Y-m-d')]);
            break;
        }

        $request->request->add(['amt' => number_format($amt, 2)]);

        return $time;
    }
    private static function response($code, $message, $response = null)
    {
        return [
            'status' => $code,
            'message' => $message,
            'response' => $response
        ];
    }
    public function orderSuccessCartDetail($order)
    {
        try {
            $orderController = new OrderController();
            $orderController->autoAcceptOrderIfOn($order->id);
            $cart = Cart::select('id')->where('status', '0')
                ->where('user_id', $user->id)
                ->first();

            // Remove cart
            CaregoryKycDoc::where('cart_id', $cart->id)->update([
                'ordre_id' => $order->id,
                'cart_id' => ''
            ]);
            Cart::where('id', $cart->id)->update([
                'schedule_type' => null,
                'scheduled_date_time' => null
            ]);
            CartAddon::where('cart_id', $cart->id)->delete();
            CartCoupon::where('cart_id', $cart->id)->delete();
            CartProduct::where('cart_id', $cart->id)->delete();
            CartProductPrescription::where('cart_id', $cart->id)->delete();
            $orderController->sendSuccessSMS($$order);
            if (!empty($order->vendors)) {
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
            if ($request['environment'] == 'app') {
                $returnUrl = url('payment/gateway/returnResponse') . '/?gateway=mtn_momo' . '&status=200&transaction_id=' . $transactionId . '&order=' . $order_number;
            } else {
                $returnUrl = route('order.return.success');
            }

            // send sms
            $this->sendOrderSuccessSMS($order);
        } catch (\Exception $e) {
            return true;
        }
        return true;
    }
}