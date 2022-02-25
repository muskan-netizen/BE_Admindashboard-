<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;

class PassbaseController extends Controller
{
	use \App\Http\Traits\PassbaseManager;
	use \App\Http\Traits\ApiResponser;
	private $api_key;
  	private $secret_key;
  	public function __construct()
	{
	    $this->api_key = 'ILVcVB4OWqBus0Clk4bC2PJhpQArmUQ3LVHB2L1iD4YQxB7gxlngCebUakHoZA9o';
	    $this->secret_key = 'YokK471tFNZ3CwIedgNta3chFswpqp1HNIny3XyeskTraSMS9WD4HwSXEtDORoGXHVtNMe1T6MoVv1gSEzsIKO0KCBaYcudPiavkXM5f85tvI1i95PPbTg8z699fTcl4';
	}
	public function index()
	{
		return view('frontend.passbase');
	}
    public function storeAuthkey(Request $request)
    {
    	$response = $this->getIdentity($request->identityAccessKey);
    	return $response;
    }
    public function webhook(Request $request)
    {
    	Log::info($request->all());
    }
}
