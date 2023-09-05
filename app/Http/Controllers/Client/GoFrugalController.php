<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Http\Traits\GoFrugal;
use App\Models\Vendor;
use Exception;
use Illuminate\Http\Request;

class GoFrugalController extends BaseController
{
    use GoFrugal;
    
    public function index(Request $request){
       
        // $response =  $this->getVendors();
        // if(!$response['status']){
        //     return redirect()->back()->withErrors(['error' => $response['message']]);
        // }
        // if(!empty($response['data'])){
        //     foreach($response['data'] as $key => $vendor){
        //         $newVendor = new Vendor();
        //         $newVendor->name = $vendor['name'];
        //         $newVendor->slug = strtolower(str_replace(' ','-' , $newVendor->name));
        //         $newVendor->address = $vendor['address1'];
        //         $newVendor->name = $vendor['name'];
        //         $newVendor->name = $vendor['name'];
        //         $newVendor->name = $vendor['name'];
        //     }
        // }
        
       
        foreach($request->supplierMaster as $key => $vendor){
            $newVendor = new Vendor();
            $newVendor->name = $vendor['name'];
            $newVendor->slug = strtolower(str_replace(' ','-' , $newVendor->name));
            $newVendor->address = $vendor['address1'];
            $newVendor->city = $vendor['address2'];
            $newVendor->state = $vendor['address3'];
            $newVendor->email = $vendor['emailId'];
            $newVendor->phone_no = $vendor['mobileNumber'];
            $newVendor->pincode = $vendor['pincode'];
            $newVendor->save();
        }
        return response()->json(['message' => 'Vendor Added Successfully'], 200);
    }

    public function syncData(){

    }
}
