<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{VerificationOption, UserVerfication};  
use Log;

class PassbaseController extends Controller
{
	use \App\Http\Traits\PassbaseManager;
	use \App\Http\Traits\ApiResponser;
	private $publish_key;
  	private $secret_key;
  	private $userVerificationObj;
  	public function __construct(UserVerfication $userVerfication)
	{
		$this->userVerificationObj = $userVerfication;
		$passbase_creds = VerificationOption::select('credentials', 'test_mode')->where('code', 'passbase')->where('status', 1)->first();
        $creds_arr = json_decode($passbase_creds->credentials);
	    $this->publish_key = $creds_arr->publish_key ?? '';
	    $this->secret_key = $creds_arr->secret_key ?? '';
	}
	public function index(Request $request)
	{
		// $response = $this->getIdentity('7bcbb1ee-8d8e-4bee-93d4-3df9d7740335');

		$data = $request->all();
		$data['publish_key'] = $this->publish_key;
		return view('frontend.passbase')->with('data',$data);
	}
    public function storeAuthkey(Request $request)
    {
    	$response = $this->getIdentity($request->identityAccessKey);
    	$add = $this->userVerificationObj->addVerification([
    		'verification_option_id' => 1,
    		'user_id' => 1,
    		'response_id' => $response['id'],
    		'status' => $response['status']
    	]);
    	return $response;
    }
    public function webhook(Request $request)
    {
    	$events = $request->all();
    	foreach($events  as $event)
    	{
    		$update_status = $this->userVerificationObj->updateStatus([
    			'verification_option_id' => 1,
    			'response_id' => $event['key'],
    			'status' => $event['status']
    		]);
    	}
    	Log::info($request->all());
    } 
}
