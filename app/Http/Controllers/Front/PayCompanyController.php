<?php

namespace App\Http\Controllers\Front;

use App\Helpers\Payment;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\OrderTrait;
use App\Models\ClientCurrency;
use App\Models\Company;
use App\Models\Order;
use App\Models\PaymentOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDF;

class PayCompanyController extends Controller
{

    use ApiResponser,OrderTrait;

 
    public function __construct()
    {
       $payOpt = PaymentOption::select('credentials', 'test_mode','status')->where('code', 'payViaCompany')->where('status', 1)->first();
    }


    public function orderNumber($request)
    {
        $time = time();
        $user_id = auth()->id();
        $amount = $request->amt??$request->amount;
        if ($request->payment_from == 'pickup_delivery') {
            $time = $request->order_id??$request->order_number;
            Payment::create([
                'amount' => 0,
                'transaction_id' => $time,
                'balance_transaction' => $amount,
                'type' => 'pickup_delivery',
                'date' => date('Y-m-d'),
                'user_id' => $user_id,
                'payment_from'=>$request->user_from??'web'
            ]);
        }
        return $time;
    }


    public function payByCompany(Request $request)
    {   
        $company = Company::first();
        $order = Order::where('order_number','85222773')->first();
        $data["email"] = $company->email??null;
        $data["title"] = "Its Testing email";
        $data["body"] = "This is Body part";

        $clientCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
    
        
         view()->share('clientCurrency',$clientCurrency); 
         view()->share('order',$order);
         $pdf = PDF::loadView('backend.order.print');
        //  return $pdf->download('pdfview.pdf'); 
  
        Mail::send('email.verify', 
                    [
                    'email' => $data["email"],
                    'mail_from' => $data["email"],
                    'client_name' => $company->name,
                    'code' => '11111',
                    'logo' => 'no_logo.png',
                    'customer_name' => "Link from link not found" ,
                    'code_text' => 'Register yourself using this referral code below to get bonus offer',
                    'link' => "http://local.myorder.com/user/register?refferal_code=11222",
                    'email_template_content' => 'data templates'
            ],function($message)use($data, $pdf) {
            $message->to($data["email"], $data["email"])
            ->subject($data["title"])
            ->attachData($pdf->output(), "invoice.pdf");
        });
        
        
    }



    protected function successMail()
	{
		$data = ClientPreference::select(
			'sms_key',
			'sms_secret',
			'sms_from',
			'mail_type',
			'mail_driver',
			'mail_host',
			'mail_port',
			'mail_username',
			'sms_provider',
			'mail_password',
			'mail_encryption',
			'mail_from'
		)->where('id', '>', 0)->first();
		$confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);


		$mail_from = $data->mail_from;

		$email_template_content = '';
		$email_template = EmailTemplate::where('id', 6)->first();
		$address = UserAddress::where('user_id', Auth::user()->id)->first();
		if (Auth::user()) {
			$cart = Cart::select('id', 'is_gift', 'item_count')->with('coupon.promo')->where('status', '0')->where('user_id', Auth::user()->id)->first();
		} else {
			$cart = Cart::select('id', 'is_gift', 'item_count')->with('coupon.promo')->where('status', '0')->where('unique_identifier', session()->get('_token'))->first();
		}
		if ($cart) {
			$cartDetails = $this->getCart($cart);
		}
		if ($email_template) {
			$email_template_content = $email_template->content;

			$returnHTML = view('email.orderProducts')->with(['cartData' => $cartDetails])->render();

			$email_template_content = $email_template->content;
			$email_template_content = str_ireplace("{name}", Auth::user()->name, $email_template_content);
			$email_template_content = str_ireplace("{products}", $returnHTML, $email_template_content);
			$email_template_content = str_ireplace("{address}", $address->address . ', ' . $address->state . ', ' . $address->country . ', ' . $address->pincode, $email_template_content);
		}
		Mail::send('frontend.successmail', compact('email_template_content'), function ($message) use ($mail_from) {
			$message->from($mail_from);
			$message->to(Auth::user()->email);
			$message->subject('Payment Succesful Notification');
		});
	}




    
}
