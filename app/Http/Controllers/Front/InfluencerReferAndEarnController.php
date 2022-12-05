<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InfluencerReferAndEarnController extends Controller
{
    function index(Request $request) {
        // dd($request->all());
        return view('frontend/account/referAndEarn');
    }
}
