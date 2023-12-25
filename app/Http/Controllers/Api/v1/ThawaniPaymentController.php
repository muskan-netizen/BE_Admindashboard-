<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Traits\MtnMomoPaymentManager;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use App\Http\Traits\ThawanipaymentManager;

class ThawaniPaymentController extends Controller
{
    use ThawanipaymentManager;

    public function __construct() {

        $creds =self::getDetails();
        $this->checkoutUrl =$creds['checkout_url'];
        $this->payurl =  $creds['payurl'];
        $this->thawani_Apikey = $creds['thawani_Apikey'];
        $this->thawani_publishKey = $creds['thawani_publishKey'];
        $this->currency = $creds['currency'];
    }

    public function paybythawanipg(Request $request)
    {
        try{
            $transaction_id = self::orderNumber($request);

            $payment    = Payment::where('transaction_id', $transaction_id)->first();
            $user       = auth()->user();
            $amount     = intval($payment->balance_transaction);
            $parameters = ['transaction_id' => $transaction_id];
            $sessionUrl = $this->checkoutUrl;
            $successUrl = route('after.payment', $parameters);
            $cancelurl      = url($request->returnUrl);

             $response = Http::withHeaders(['Accept' => 'application/json','Content-Type' => 'application/json','thawani-api-key' => $this->thawani_Apikey,
                                             ])->post($sessionUrl, ['client_reference_id' => $user->id,'mode' => 'payment','products' => [[
                                                'name' => 1,'quantity' => 1,'unit_amount' =>$amount*1000,],],
                                                'success_url' => $successUrl,'cancel_url' =>  $cancelurl,'metadata' => ['Customer name' => $user->name,'order id' => $transaction_id,],]);
                if ($response->failed()) {
                  echo "HTTP Error: " . $response->status();
                } 
                else 
                {

                        $responseData = $response->json();
                        $sessionId = $responseData['data']['session_id'];

                        $payurl= "{$this->payurl}/pay/{$sessionId}?key={$this->thawani_publishKey}";
                        return response()->json([
                            'status'      => 'Success',
                            'payment_url' => $payurl
                            ]);
               }
         }catch (\Exception $e)
         {
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred. Please try again.'], 500);
            
         }
    }
    public function afterpayment(Request $request){
        try{
            $transactionId = $request->get('transaction_id');
            $payment       = Payment::where('transaction_id', $transactionId)->first();
            if ($payment) 
            {
                $payment->viva_order_id     = $transactionId;
                $payment->payment_option_id = 67;
                $payment->save();
          
                switch ($payment->type) 
                {
                    case 'cart':
                        $order = Order::where('order_number', $transactionId)->first();
                        if ($order) {
                            $order->payment_status = '1';
                            $order->save();
                            $this->orderSuccessCartDetail($order);
                            $returnUrl = route('payment.gateway.return.response') . '/?gateway=thawani' . '&status=200&order=' . $order->id;
                            return redirect($returnUrl);
                        }
                    break;
                
                    case 'wallet':
                        $user = User::findOrFail($payment->user_id);
                        Auth::login($user);
                        $returnUrl = route('payment.gateway.return.response') . '/?gateway=thawani' . '&status=200&transaction_id=' . $payment->transaction_id . '&action=wallet';
                        $wallet  = $user->wallet;
                        $wallet->depositFloat($payment->balance_transaction, ['Wallet has been <b>credited</b> for order number <b>' . $payment->transaction_id . '</b>']);
                        return redirect($returnUrl);
                    break;
                
                    case 'subscription':
                        $subcription_idtime = $payment->transaction_id;
                        $subs_id = strstr($subcription_idtime, '_', true);
                        $data['transaction_id'] = $payment->transaction_id;
                        $data['payment_option_id'] = 67;
                        $data['subsid'] = $payment->transaction_id;
                        $data['subscription_id'] = $subs_id;
                        $data['amount'] = $payment->amount;
                        $request = new Request($data);
                        $subscriptionController = new UserSubscriptionController();
                        $subscriptionController->purchaseSubscriptionPlan($request, $subs_id);
                        $returnUrl = route('payment.gateway.return.response') . '/?gateway=thawani' . '&status=200&transaction_id=' . $payment->transaction_id . '&action=subscription';
                        return redirect($returnUrl);
                    break;
                
                    case 'pickup_delivery':
                        $data['payment_option_id'] = 67;
                        $data['transaction_id'] = $transactionId;
                        $data['amount'] = $payment->amount;
                        $data['order_number'] = $transactionId;
                        $data['reload_route'] = $payment->reload_route;
                        $request = new Request($data);
                        $plaseOrderForPickup = new PickupDeliveryController();
                        $res = $plaseOrderForPickup->orderUpdateAfterPaymentPickupDelivery($request);
                        return redirect()->route('front.booking.details', $transactionId);
                    break;
                
                    case 'tip':
                        $data['tip_amount'] = $payment->amount;
                        $data['order_number'] = $payment->order_number;
                        $data['transaction_id'] = $transactionId;
                        $request = new Request($data);
                        $orderController = new OrderController();
                        $orderController->tipAfterOrder($request);
                        $returnUrl = route('payment.gateway.return.response') . '/?gateway=thawani' . '&status=200&order=' . $transactionId . '&action=tip';
                        return redirect($returnUrl);
                    break;
                }
            }
            else {
                return redirect()->back();
            }
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred. Please try again.'], 500);
        }
    }

}
