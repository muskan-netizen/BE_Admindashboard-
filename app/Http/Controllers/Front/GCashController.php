<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class GCashController extends Controller
{
	use \App\Http\Traits\GCashpaymentManager;
	use \App\Http\Traits\ApiResponser;

    public function beforePayment(Request $request)
    {
    	$response = $this->createPaymentRequest($request->all());
    	return $this->successResponse($response);
    }
    public function webView(Request $request)
    {
    	$response = Session::get('gcash_session_data',[]);
    	return view('frontend.gcash_view')->with('response',$response);
    }
}
