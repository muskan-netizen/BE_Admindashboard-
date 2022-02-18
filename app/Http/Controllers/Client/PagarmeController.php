<?php

namespace App\Http\Controllers\Client;

use Auth, Log, Redirect, Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Client\{BaseController};
use App\Models\{PaymentOption, Cart, SubscriptionPlansUser, Order, Payment, CartAddon, CartCoupon, CartProduct, CartProductPrescription, UserVendor, User,OrderProductAddon,OrderProduct,OrderProductPrescription,VendorOrderStatus,OrderVendor,OrderTax};

class PagarmeController extends BaseController
{
    use \App\Http\Traits\PagarmePaymentManager;
	use \App\Http\Traits\ApiResponser;

	private $api_key;
	private $secret_key;
    private $pagarme;

	public function __construct()
  	{
		$pagarme_creds = PaymentOption::getCredentials('pagarme');
	    $creds_arr = json_decode($pagarme_creds->credentials);
	    $this->api_key = $creds_arr->api_key??'';
	    $this->secret_key = $creds_arr->secret_key??'';

        $this->pagarme = new \PagarMe\Client($this->api_key);
	}

    public function getBankAccounts()
    {
        $bankAccounts = $this->pagarme->bankAccounts()->getList();
        return $bankAccounts;
    }

    
}
