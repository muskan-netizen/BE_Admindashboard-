<?php

namespace App\Http\Controllers\Front; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Front\{UserSubscriptionController, OrderController, WalletController, FrontController};
use Auth, Log, Redirect,Config;
use App\Models\{PaymentOption, Client, ClientPreference, Order, OrderProduct, EmailTemplate, Cart, CartAddon, OrderProductPrescription, CartProduct, CartDeliveryFee, User, Product, OrderProductAddon, Payment, ClientCurrency, OrderVendor, UserAddress, Vendor, CartCoupon, CartProductPrescription, LoyaltyCard, NotificationTemplate, VendorOrderStatus,OrderTax, SubscriptionInvoicesUser, UserDevice, UserVendor, Transaction};

class TelrController extends Controller 
{
    use \App\Http\Traits\TelrPaymentManager; 
	use \App\Http\Traits\ApiResponser;
	public function __construct()
  	{
		$this->telr_creds = PaymentOption::select('credentials')->where('code', 'telr')->where('status', 1)->first();
	    $this->creds_arr = json_decode($this->telr_creds->credentials);
	    $this->merchant_id = $this->creds_arr->merchant_id ?? '';
	    $this->api_key = $this->creds_arr->api_key ?? '';
	    // $this->url = url('payment/telr');
	    $this->url = "https://6ec8-180-188-237-239.ngrok.io/payment/telr";
	    Config::set('telr.test_mode', true);
	    Config::set('telr.create.ivp_store', (int)$this->merchant_id);
	    Config::set('telr.create.ivp_authkey', $this->api_key);
	    Config::set('telr.create.return_auth', $this->url.'/success');
	    Config::set('telr.create.return_can', $this->url.'/cancel');
	    Config::set('telr.create.return_decl', $this->url.'/declined');
	}
	public function beforePayment(Request $request)
    {
    	// dd(config('telr'));
    	$data = $request->all();
        $data['come_from'] = 'app';
        if($request->isMethod('post'))
        {
            $data['come_from'] = 'web';
        }else{
            $user = User::where('auth_token', $request->auth_token)->first();
            Auth::login($user);
        }
        $user = Auth::user();
        $data['customer_name'] = $user->name;
        $data['customer_email'] = $user->email??'dummy@yopmail.com';
        $data['customer_phone'] = '+'.($user->dial_code??'91').($user->phone_number??'9876543210');
    	$response = $this->createPaymentpage($data);
    	dd($response);
    	return Redirect::to($response->url);
    }
    public function afterPayment(Request $request)
    {
    	dd($request);
    } 
}
