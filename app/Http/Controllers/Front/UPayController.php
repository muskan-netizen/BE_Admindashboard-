<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Front\{UserSubscriptionController, OrderController, WalletController, FrontController};
use Auth, Log, Redirect;
use App\Models\{PaymentOption, Client, ClientPreference, Order, OrderProduct, EmailTemplate, Cart, CartAddon, OrderProductPrescription, CartProduct, CartDeliveryFee, User, Product, OrderProductAddon, Payment, ClientCurrency, OrderVendor, UserAddress, Vendor, CartCoupon, CartProductPrescription, LoyaltyCard, NotificationTemplate, VendorOrderStatus,OrderTax, SubscriptionInvoicesUser, UserDevice, UserVendor, Transaction};

class UPayController extends Controller
{
    use \App\Http\Traits\UPayPaymentManager;
	use \App\Http\Traits\ApiResponser;
	public function __construct()
  	{
		$this->upay_creds = PaymentOption::select('credentials')->where('code', 'upay')->where('status', 1)->first();
	    $this->creds_arr = json_decode($this->upay_creds->credentials);
	    $this->uidd = $this->creds_arr->uidd_key ?? '';
	    $this->aes_key = $this->creds_arr->aes_key ?? '';
	    $this->endpoint = $this->upay_creds->test_mode ? "https://ubotpsentry-tst1.outsystemsenterprise.com/UPAY" : "https://sith.unionbankph.com/UPAY";
	}
	public function beforePayment(Request $request)
    {
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
        $data['token'] = $this->createToken($user);
    	$response = $this->createPaymentRequest($data);
    	return Redirect::to($response->data->checkouturl);
    }
}
