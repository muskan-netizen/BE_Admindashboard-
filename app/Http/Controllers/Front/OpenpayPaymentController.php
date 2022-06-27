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
        $order_number  = $request->order_number;
        //pr(Openpay::getProductionMode());
        try {
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
            $openPayCustomerCart = $openPayCustomer->cards->get($cart_id);
            //pr($openPayCustomerCart);
            // create charges tragi
            $chargeData = array(
                    'method' => 'card',
                    'source_id' => $cart_id,
                    'amount' => $amount,
                    'description' => 'Cargo inicial a mi merchant',
                    'order_id' => $order_number ?? generateOrderNo(),
                    "device_session_id"=> $request->deviceIdHiddenFieldName,
            );
            
            $openPayCustomerCharges = $openPayCustomer->charges->create($chargeData);
            pr($openPayCustomerCharges);
        } catch (\Exception $e) {
            $data = Session::get('opnepay_data');
            unset($data['_token']);
            Log::info($e->getMessage());
           
            return Redirect::to(route('payment.opnepay.beforePayment',$data))->with('error',$e->getMessage());
        }
    }
    
    public function opnepayWebhook(Request $request, $domain = '')
    {
        Log::info($request->all());
        http_response_code(200);
    }

}
