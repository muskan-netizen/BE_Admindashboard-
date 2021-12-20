<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Facades\Agent;

class HomeController extends Controller
{
    public function share(Request $request)
    {

    	$device = Agent::device();
    	if(Agent::isTablet() || Agent::isPhone())
    	{
    		$platform = Agent::platform();
    		dd($platform);
    	}else{
    		dd('Desktop');
    	}
    }
}
