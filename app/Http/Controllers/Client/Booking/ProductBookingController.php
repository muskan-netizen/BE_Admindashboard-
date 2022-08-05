<?php

namespace App\Http\Controllers\Client\Booking;

use App\Http\Controllers\Client\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\{ProductBooking};
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\ToasterResponser;
class ProductBookingController extends BaseController
{
    use ApiResponser;
    use ToasterResponser;
    


    public function addBlockSlot(Request $request)
    {
      $data = $request->all();
      pr($request->all());  
      ProductBooking::Create(['memo'=>$data['order_id'],'varient_id'=>$data['varient_id'],'product_id'=>$data['product_id'],'start_date_time'=>$data['order_id'],'end_date_time'=>$data['order_id'],'booking_start_end'=>$data['booking_slot']]);
     
    }
    

}
