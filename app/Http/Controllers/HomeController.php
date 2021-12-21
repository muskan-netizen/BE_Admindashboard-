<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Facades\Agent;
use App\Models\ClientPreference;
use Redirect;

class HomeController extends Controller
{
    public function share(Request $request)
    {
    	$device = Agent::device();
    	if(Agent::isTablet() || Agent::isPhone())
    	{
    		$link = ClientPreference::select('android_app_link','ios_link')->first();
    		$platform = Agent::platform();
    		if($platform == "AndroidOS")
    		{
    			if(!is_null($link->android_app_link))
    			{
    				return Redirect::to($link->android_app_link);
    			}
    		}else{
    			if(!is_null($link->ios_link))
    			{
    				return Redirect::to($link->ios_link);
    			}

    		}
    	}
    	if(isset($request->serverUrl))
    	{
    		return Redirect::to(url($request->serverUrl));
    	}
    	return Redirect::to(url('/'));
    }
}
