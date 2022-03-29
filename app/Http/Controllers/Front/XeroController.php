<?php

namespace App\Http\Controllers\Front; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{ThirdPartyAccounting, Order};
use Session, Auth;

class XeroController extends Controller
{
	use \App\Http\Traits\XeroManager;
    private $client_id;
    private $secret_id;
    private $orderObj;
    public function __construct(Order $order)
    {
        $xero_creds = ThirdPartyAccounting::where('code','xero')->first();
        $creds_arr = json_decode($xero_creds->credentials);
        $this->client_id = $creds_arr->client_id??'';
        $this->secret_id = $creds_arr->secret_id??'';

        $this->orderObj = $order;
    }


    public function index(Request $request)
    {
        Session::put('xero_order_number',$request->order_number);
    	$response = $this->authorization();
    }
    public function xero_callback(Request $request)
    {
    	$data = $request->all();
    	// $this->callback($data);
        $order_number = Session::get('xero_order_number','00045400');
        $order = $this->orderObj->getByNumber($order_number);
        // dd($order);
    	$invoice = $this->createInvoice($data,$order);
    	dd($invoice);
    }
}
