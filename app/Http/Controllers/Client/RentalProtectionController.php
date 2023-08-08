<?php

namespace App\Http\Controllers\Client;

use App\Models\RentalProtection;
use Illuminate\Http\Request;
use App\Http\Controllers\Client\BaseController;

class RentalProtectionController extends BaseController
{
    public function index(Request $request){
        $rentalProtection = RentalProtection::get();
        return view('backend.rentalProtection.index')->with(['rentalProtection' => $rentalProtection]);
    }
}
