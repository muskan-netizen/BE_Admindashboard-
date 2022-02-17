<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Facades\Agent;
use App\Models\ClientPreference;
use App\Models\Category;
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
    public function createSitmap()
    {
        $categories = Category::select(["id","slug", "updated_at"]) 
        // you may want to add where clauses here according to your needs
        ->orderBy("id", "desc")
        ->take(50000) // each Sitemap file must have no more than 50,000 URLs and must be no larger than 10MB
        ->get();

        $vendors = Category::select(["id", "updated_at"]) 
        // you may want to add where clauses here according to your needs
        ->orderBy("id", "desc")
        ->take(50000) // each Sitemap file must have no more than 50,000 URLs and must be no larger than 10MB
        ->get();

        $products = Category::select(["id", "updated_at"]) 
        // you may want to add where clauses here according to your needs
        ->orderBy("id", "desc")
        ->take(50000) // each Sitemap file must have no more than 50,000 URLs and must be no larger than 10MB
        ->get();

        return response()->view('sitemap',['categories'=>$categories, 'vendors'=>$vendors, 'products'=>$products])->header('Content-Type', 'text/xml');
    }
} 
