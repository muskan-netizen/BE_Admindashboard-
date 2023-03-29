<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use App\Models\PaymentOption;
use Illuminate\Http\Request;
use Auth;
use App\Http\Traits\{ApiResponser,NmiPaymentTrait,OrderTrait};
use App\Models\Cart;
use App\Models\CartAddon;
use App\Models\CartCoupon;
use App\Models\CartProduct;
use App\Models\CartProductPrescription;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserVendor;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Redirect;
use JWT\Token;
use Log;

class NmiPaymentController extends Controller
{
   use ApiResponser,NmiPaymentTrait,OrderTrait;

   private $merchant_key;
   private $merchant_id;
   private $url;
   public $user;
   public $domain;
   public $ip;

   public function __construct()
   {
      $payOpt = PaymentOption::select('credentials', 'test_mode','status')->where('code', 'nmi')->where('status', 1)->first();
      $json = json_decode($payOpt->credentials);
      $this->merchant_id = $json->nmi_client_id;
      $this->merchant_key = $json->nmi_key_id;
      $this->url = "https://secure.nmi.com/api/transact.php"; 
      $this->setLogin($this->merchant_key);
      $this->domain = request()->getHttpHost();
      $this->domain = request()->ip();
   }

   
   public function orderNumber($request)
   {
       $time = time();
       $user_id = auth()->id();

       if ($request->from == 'cart') {
           $time = $request->order_number;
           Payment::create([
               'amount' => 0,
               'transaction_id' => $time,
               'balance_transaction' => $request->amt,
               'type' => 'cart',
               'date' => date('Y-m-d'),
               'user_id' => $user_id
           ]);
       } elseif ($request->from == 'wallet') {
           $time = $request->transaction_id ?? time();
           Payment::create([
               'amount' => 0,
               'transaction_id' => $time,
               'balance_transaction' => $request->amt,
               'type' => 'wallet',
               'date' => date('Y-m-d'),
               'user_id' => $user_id
           ]);
       } elseif ($request->from == 'tip') {
           $time = time();
           Payment::create([
               'amount' => 0,
               'transaction_id' => $request->order_number . '_' . $time,
               'balance_transaction' => $request->amt,
               'type' => 'tip',
               'date' => date('Y-m-d'),
               'user_id' => $user_id
           ]);
       } elseif ($request->from == 'subscription') {
           $time = time();
           Payment::create([
               'amount' => 0,
               'transaction_id' => $request->subsid . '_' . $time,
               'balance_transaction' => $request->amt,
               'type' => 'subscription',
               'date' => date('Y-m-d'),
               'user_id' => $user_id
           ]);
       } else if ($request->from == 'pickup_delivery') {
           $time = $request->order_number;
           Payment::create([
               'amount' => 0,
               'transaction_id' => $time,
               'balance_transaction' => $request->amt,
               'type' => 'pickup_delivery',
               'date' => date('Y-m-d'),
               'user_id' => $user_id
           ]);
       }
       return $time;
   }

   public function beforePayment(Request $request)
   {

       $response = [];
       $user = auth()->user();
       $number = $this->orderNumber($request);

       if ($request->from == 'wallet') {
           $number = $this->orderNumber($request);
           $request->request->add([
               'order_number' => $number,
               'amount' => $request->amount
           ]);
       }
       if ($request->from == 'subscription') {
           $number = $this->orderNumber($request);
           $request->request->add([
               'order_number' => $number,
               'amount' => $request->amount
           ]);
       }

       $expDate = substr($request->dt,0,2).'/'.substr($request->dt,-2);

       $address = UserAddress::where('is_primary','1')->first();

       $this->setBilling($user->name,$user->name,$user->name,$address->address,$address->address,$address->city, $address->state,$address->pincode,$address->country,$user->phone_number,$user->phone_number,$user->email,$this->domain);

       $this->setShipping($user->name,$user->name,$user->name,$address->address,$address->address,$address->city, $address->state,$address->pincode,$address->country,$user->email);
       $this->setOrder($number,"Royo Order",0, 0,$user->phone_number,$this->ip);
       $dataResponse = $this->doSale($request->amount??$request->amt,$request->cno,$expDate);
    //    $dataResponse = $this->responses;
    \Log::info($dataResponse);
       if ($dataResponse['response'] == 3) {
           $response['status'] = 'Fail';
           $response['msg'] = $dataResponse['responsetext'];
           $response['payment_from'] = $request->from;
           $response['route'] = '';
           return $response;
       }
       if (isset($dataResponse['response']) && $dataResponse['response'] == 1) {

           if ($request->from == 'tip') {
               $payment = Payment::where('transaction_id', $request->order_number . '_' . $number)->first();
           } else if ($request->from == 'subscription') {
               $payment = Payment::where('transaction_id', $request->subsid . '_' . $number)->first();
           } else {
               $payment = Payment::where('transaction_id', $request->order_number)->first();
           }

           // //\Log::info(json_encode($request->all()));
           if ($payment) {
               $payment->viva_order_id = $dataResponse['transactionid'];
               $payment->save();
           }

           if ($payment->type == 'cart') {
               return $this->completeOrderCart($dataResponse, $payment);
           } elseif ($payment->type == 'wallet') {
               return $this->completeOrderWallet($dataResponse, $payment, $request->amount);
           } elseif ($payment->type == 'tip') {
               return $this->completeOrderTip($dataResponse, $payment, $request->amount, $request);
           } elseif ($payment->type == 'subscription') {
               return $this->completeOrderSubs($dataResponse, $payment, $request);
           } elseif ($payment->type == 'pickup_delivery') {
               return $this->completePickupDelivery($dataResponse, $payment, $request);
           }
       } else {

           $returnUrl = route('order.return.success');
           $response['status'] = 'Fail';
           $response['msg'] = $dataResponse['responsetext'];
           $response['payment_from'] = 'cart';
           $response['route'] = $returnUrl;
           return $response;
       }
   }

   


       
//         $gw = new NmiPaymentController();
// $gw->setLogin("zC4Wr4g4A3UGdd7MWpe88buJdHt5SDPN");
// $gw->setBilling("John","Smith","Acme, Inc.","123 Main St","Suite 200", "Beverly Hills",
//         "CA","90210","US","555-555-5555","555-555-5556","support@example.com",
//         "www.example.com");
// $gw->setShipping("Mary","Smith","na","124 Shipping Main St","Suite Ship", "Beverly Hills",
//         "CA","90210","US","support@example.com");
// $gw->setOrder("1234","Big Order",1, 2, "PO1234","65.192.14.10");

// $r = $gw->doSale("50.00","4111111111111111","1010");
// dd($gw->responses);

// "response" => "3"
//   "responsetext" => "Duplicate transaction REFID:1206192251"
//   "authcode" => ""
//   "transactionid" => ""
//   "avsresponse" => ""
//   "cvvresponse" => ""
//   "orderid" => "1234"
//   "type" => "sale"
//   "response_code" => "300"
//   "amount_authorized" => ""
//   "first_name" => "John"
//   "last_name" => "Smith"
//   "address_1" => "123 Main St"
//   "city" => "Beverly Hills"
//   "postal_code" => "90210"
//   "state" => "CA"
//   "phone" => "555-555-5555"
//   "shipping_address_1" => "124 Shipping Main St"
//   "shipping_city" => "Beverly Hills"
//   "shipping_postal_code" => "90210"
//   "shipping_state" => "CA"
//   "shipping_country" => "US"
//   "shipping_amount" => "2.00"
//   "shipping_company" => "na"
//   "shipping_email" => "support@example.com"
//   "date" => ""
//   "currency" => "USD"

}
