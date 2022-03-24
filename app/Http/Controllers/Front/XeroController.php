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
    	$response = $this->callback($request->all());
    }
}
