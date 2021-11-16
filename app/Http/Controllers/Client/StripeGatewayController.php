<?php

namespace App\Http\Controllers\Client;

use Auth;
use Session;
use Redirect;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;
use App\Models\{PaymentOption, Client, ClientCurrency, SubscriptionPlansVendor, PayoutOption, UserVendor, VendorConnectedAccount};
use App\Http\Traits\ApiResponser;
use App\Http\Traits\ToasterResponser;
use App\Http\Controllers\Client\BaseController;
use Illuminate\Support\Facades\Validator;
class StripeGatewayController extends BaseController{

    use ApiResponser;
    use ToasterResponser;
    public $gateway;

    public function __construct()
    {
        $stripe_creds = PaymentOption::select('credentials')->where('code', 'stripe')->where('status', 1)->first();
        $creds_arr = json_decode($stripe_creds->credentials);
        $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
        $this->gateway = Omnipay::create('Stripe');
        $this->gateway->setApiKey($api_key);
        $this->gateway->setTestMode(true); //set it to 'false' when go live
    }

    public function subscriptionPaymentViaStripe(request $request)
    {
        try{
            $user = Auth::user();
            $token = $request->stripe_token;
            $plan = SubscriptionPlansVendor::where('slug',$request->subscription_id)->firstOrFail();
            $request->request->add(['user_id' => $user->id]); //add request
            $saved_payment_method = $this->getSavedVendorPaymentMethod($request);
            if(!$saved_payment_method){
                $customerResponse = $this->gateway->createCustomer(array(
                    'description' => 'Creating Customer for subscription',
                    'email' => $request->email,
                    'source' => $token
                ))->send();
                $customer_id = $customerResponse->getCustomerReference();
                if($customer_id){
                    $request->request->set('customerReference', $customer_id);
                    $save_payment_method_response = $this->saveVendorPaymentMethod($request);
                }
            }
            else{
                $customer_id = $saved_payment_method->customerReference;
            }
            
            // $subscriptionResponse = $this->gateway->createSubscription(array(
            //     "customerReference" => $customer_id,
            //     'plan' => 'Basic Plan',
            // ))->send();
            $authorizeResponse = $this->gateway->authorize([
                'amount' => $request->amount,
                'currency' => 'INR',
                'description' => 'This is a subscription purchase transaction.',
                'customerReference' => $customer_id
            ])->send();
            if ($authorizeResponse->isSuccessful()) {
                $purchaseResponse = $this->gateway->purchase([
                    'currency' => 'INR',
                    'amount' => $request->amount,
                    'metadata' => ['user_id'=>$user->id, 'vendor_id' => $request->vendor_id, 'plan_id' => $plan->id],
                    'description' => 'This is a subscription purchase transaction.',
                    'customerReference' => $customer_id
                ])->send();
                if ($purchaseResponse->isSuccessful()) {
                    return $this->successResponse($purchaseResponse->getData());
                }
                else {
                    return $this->errorResponse($purchaseResponse->getMessage(), 400);
                }
            }
            else {
                return $this->errorResponse($authorizeResponse->getMessage(), 400);
            }
        }catch(\Exception $ex){
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    public function verifyOAuthToken(request $request)
    {
        $user = Auth::user();
        $vendor = $request->state;
        if($request->has('code')){
            $code = $request->code;    
            $checkIfExists = VendorConnectedAccount::where('user_id', $user->id)->where('vendor_id', $vendor)->first();        
            if($vendor > 0){
                if($checkIfExists){
                    $msg = __('You are already connected to stripe');
                    $toaster = $this->errorToaster('Error', $msg);
                }else{
                    $stripe_creds = PayoutOption::select('credentials')->where('code', 'stripe')->where('status', 1)->first();
                    $creds_arr = json_decode($stripe_creds->credentials);
                    $secret_key = (isset($creds_arr->secret_key)) ? $creds_arr->secret_key : '';

                    // Complete the connection and get the account ID
                    \Stripe\Stripe::setApiKey($secret_key);
                    $response = \Stripe\OAuth::token([
                        'grant_type' => 'authorization_code',
                        'code' => $code,
                    ]);
                    // dd($response);

                    // Access the connected account id in the response
                    $connected_account_id = $response->stripe_user_id;

                    VendorConnectedAccount::insert([
                        'user_id' => $user->id,
                        'vendor_id' => $vendor,
                        'account_id' => $connected_account_id,
                        'payment_option_id' => 4,
                        'status' => 1
                    ]);
                    $msg = __('Stripe connect has been enabled successfully');
                    $toaster = $this->successToaster('Success', $msg);
                }
            }else{
                $msg = __('Invalid Data');
                $toaster = $this->errorToaster('Error', $msg);
            }
        }
        else{
            $msg = __('Stripe connect has been declined');
            $toaster = $this->errorToaster('Error', $msg);
        }
        
        return Redirect::To(route('vendor.payout', $vendor))->with('toaster', $toaster);
    }
}
