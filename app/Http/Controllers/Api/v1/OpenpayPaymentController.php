<?php

namespace App\Http\Controllers\Api\v1;
use Log;
use Auth;
use App\Helpers\Easebuzz;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Redirect;
use App\Models\{PaymentOption,Country,ClientCurrency,ClientLanguage,SubscriptionPlansUser, Order, Cart, CartAddon, CartProduct, User,  Payment,  CartCoupon, CartProductPrescription, UserVendor, Transaction,Client};
use App\Http\Controllers\Api\v1\{BaseController, OrderController, WalletController, UserSubscriptionController};
use Openpay\Data\Openpay as Openpay;
class OpenpayPaymentController  extends BaseController
{
    use ApiResponser;

    public $openpay_merchant_id;
    public $openpay_private_key;
    public $openpay_public_key;
    public $environment;
    public $openpay;

    public function __construct()
    {
        // $openpay = PaymentOption::select('credentials', 'test_mode')->where('code', 'openpay')->where('status', 1)->first();
        // $creds_arr = json_decode($openpay->credentials);
        // $this->openpay_merchant_id = (isset($creds_arr->openpay_merchant_id)) ? $creds_arr->openpay_merchant_id : '';
        // $this->openpay_private_key = (isset($creds_arr->openpay_private_key)) ? $creds_arr->openpay_private_key : '';
        // $this->openpay_public_key = (isset($creds_arr->openpay_public_key)) ? $creds_arr->openpay_public_key : '';
        // $environment = $this->environment = (isset($openpay->test_mode) && ($openpay->test_mode == '1')) ?  'test' : 'production';
        // //pr($environment);
        // Openpay::setId($this->openpay_merchant_id);
        // Openpay::setApiKey($this->openpay_private_key);
        // if( $environment == 'test'){
        //     Openpay::setSandboxMode(true);
        // }else{
        //     Openpay::setProductionMode(true);
        // }
    }
    public function beforePayment(Request $request) 
    {   
        $cl = Client::first();
        $getAdminCurrentCountry = Country::where('id', '=', $cl->country_id)->get()->first();
        //pr($request->all());
        if(!empty($getAdminCurrentCountry)){
            $countryCode = $getAdminCurrentCountry->code;
        }else{
        $countryCode = '';
        }
        if($countryCode != "MX" && $countryCode !="CO" && $countryCode !="PE" ){
            return response()->json([
                'status' => 'error',
                'message' =>__('Something went wrong!Please try again.')
            ]);
        }
        $user = Auth::user();
        $amount = $this->getDollarCompareAmount($request->amount);
        $action = isset($request->action) ? $request->action : ''; 
        $params = '?amount=' . $amount.'&auth_token='.$user->auth_token.'&payment_from='.$action.'&view_from=app';
        if(($action == 'cart') || ($action == 'tip' || $action == 'pickup_delivery')){
            $params = $params . '&order_number=' . $request->order_number;
        }
        //return $this->successResponse(url('http://192.168.99.124:8000/payment/opnepay/page'.$params));
        return $this->successResponse(url($request->serverUrl.'payment/opnepay/page'.$params)); 
        
        
        // $request->merge(['user_id'=>$user->id]);
        // $openpay_merchant_id = $this->openpay_merchant_id;
        // $openpay_private_key =$this->openpay_private_key;
        // return view('frontend.payment_gatway.openpay_view')->with([
        //                                 'data' => $request->all(),
        //                                 'openpay_merchant_id'=>$this->openpay_merchant_id,
        //                                 'openpay_private_key'=>$this->openpay_private_key,
        //                                 'return_url'=>url('/payment/opnepay/payment_init_app'),
        //                             ]);
    }
}