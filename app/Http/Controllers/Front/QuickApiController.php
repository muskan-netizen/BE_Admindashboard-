<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderVendor;
use App\Models\VendorOrderStatus;
use App\Models\ShippingOption;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Vendor;
use App\Models\VendorOrderDispatcherStatus;
use App\Models\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\TryCatch;
use Log,DB;

class QuickApiController extends Controller
{
	use \App\Http\Traits\ApiResponser;


    public function webhooks(Request $request)
    {
        try{
           $json = json_decode($request->getContent());
           if(isset($request)){
            Webhook::create(['tracking_order_id'=>'1313','response'=>$request->getContent()]);
           }
        
        }catch(\Exception $e){

            if(isset($request)){
                Webhook::create(['tracking_order_id'=>'1213','response'=>$request->getContent()]);
               }
            return response(['error'=>$e->getMessage()],200);
        }

        return response([],200);

    }

    
}
