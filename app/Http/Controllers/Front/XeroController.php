<?php

namespace App\Http\Controllers\Front; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class XeroController extends Controller
{
	use \App\Http\Traits\XeroManager;

    public function index()
    {
    	$response = $this->authorization();
    	dd($response);
    }
    public function xero_callback(Request $request)
    {
    	dd($data($request->all()));
    	// $this->callback($request->all());
    	$contact = $this->createContact();
    	$data['contact_id'] = $contact[0]['contact_id'];
    	$invoice = $this->createInvoice($data);
    }
}
