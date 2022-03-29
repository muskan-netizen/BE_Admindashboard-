<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use Auth;
use App\Models\User;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\CartAddon;
use App\Models\UserVendor;
use App\Models\CartCoupon;
use App\Models\UserAddress;
use App\Models\CartProduct;
use Illuminate\Http\Request;
use App\Models\PaymentOption;
use Illuminate\Support\Carbon;
use App\Http\Traits\ApiResponser;
use App\Models\CartProductPrescription;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\Easebuzz;
use App\Helpers\Payment as HelperPayment;
use Log;

class EasebuzzController  extends Controller
{
    use ApiResponser;

    private $MERCHANT_KEY;
    private $SALT;
   

    public function __construct() {
        $payOpt = PaymentOption::select('credentials', 'test_mode', 'status')->where('code', 'easebuzz')->where('status', 1)->first();
        $json = json_decode($payOpt->credentials);
        $this->MERCHANT_KEY =  $json->easebuzz_merchant_key;
        $this->SALT =  $json->easebuzz_salt;
        $this->ENV = ($payOpt->test_mode == 1) ?  "test" : 'prod' ; 
    }

    function easebuzz_gateway (){
        return view('frontend.payment_gatway.easebuzz');
    }

    function order (Request $request){
       
        $this->validate($request, [
           // 'amount' => 'required|regex:/^d+(.d{1,2})?$/',
            'customerName' => 'required',
            'customerPhone' => 'required',
            'customerEmail' => 'required',
        ]);
        // pr($request->all());
        $amount = number_format($request->amount,2);
        $customerName = $request->customerName;
        $customerPhone = $request->customerPhone;
        $customerEmail = $request->customerEmail;
        $now = new \DateTime();
        $created_at = $now->format('Y-m-d H:i:s');
        $orderId = generateOrderNo();
        $postData = array (
            "txnid" => $orderId,
            "amount" =>  $amount,
            "firstname" => $customerName,
            "email" => $customerEmail,
            "phone" => $customerPhone,
            "productinfo" => "Laptop",
            "surl" => url('easebuzz-webhook'),
            "furl" => url('easebuzz-webhook'),
            "udf1" => "aaaa",
            "udf2" => "aaaa",
            "udf3" => "aaaa",
            "udf4" => "aaaa",
            "udf5" => "aaaa",
            "udf6" => "aaaa",
            "udf7" => "aaaa",
            "address1" => 'Address 1',
            "address2" => 'Address 2',
            "city" => "aaaa",
            "state" => "aaaa",
            "country" => "India",
            "zipcode" => '123456',
        );
        // pr($request->all());
        
        $easebuzzObj = new Easebuzz($this->MERCHANT_KEY, $this->SALT, $this->ENV);
        $easebuzzObj->initiatePaymentAPI($postData);
       // pr($easebuzzObj);
    }

    function easebuzz_webhook (Request $request){
        $easebuzzObj = new Easebuzz($MERCHANT_KEY = null, $this->SALT, $ENV = null);
        $result = $easebuzzObj->easebuzzResponse($request->all());
        $res = json_decode($result);
        $status = $res->status;
        if ($status == 1){
            $data = $res->data;
            $orderId = $data->txnid;
            $status = $data->status;
            if ($status == 'success'){
              //  Order::where('id', $orderId)->update(['status_id' => 1]);
                \Session::flash('successMessage', 'Successful..!');
                return redirect('easebuzz-gateway');
            }else{
                \Session::flash('errorMessage', 'failed!');
                return redirect('agent/add-money/v1/welcome');
            }
        }
    }
}
