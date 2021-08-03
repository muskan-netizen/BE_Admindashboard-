<?php

namespace App\Http\Controllers\Front;

use App\Models\{VendorOrderDispatcherStatus,OrderVendor};
use Illuminate\Http\Request;
use App\Http\Requests\DispatchOrderStatusUpdateRequest;
use App\Http\Controllers\Front\FrontController;
use Carbon\Carbon;
use Auth;
use Session;
use DB;
use App\Http\Traits\ApiResponser;
class DispatcherController extends FrontController
{
    use ApiResponser;
   
   
    /******************    ---- order status update from dispatch (Need to dispatcher_status_option_id ) -----   ******************/
    public function dispatchOrderStatusUpdate(DispatchOrderStatusUpdateRequest $request, $domain = '', $web_hook_code)
    {
        try {
            DB::beginTransaction();
            $checkiftokenExist = OrderVendor::where('web_hook_code',$web_hook_code)->first();
            if($checkiftokenExist){
                $update = VendorOrderDispatcherStatus::updateOrCreate(['dispatcher_id' => null,
                    'order_id' =>  $checkiftokenExist->order_id,
                    'dispatcher_status_option_id' =>  $request->dispatcher_status_option_id,
                    'vendor_id' =>  $checkiftokenExist->vendor_id ]);

            if(isset($request->dispatch_traking_url) && !empty($request->dispatch_traking_url))
            {
                $update_tr = OrderVendor::where('web_hook_code',$web_hook_code)->update(['dispatch_traking_url' =>  $request->dispatch_traking_url]);
            }
            OrderVendor::where('vendor_id', $checkiftokenExist->vendor_id)->where('order_id', $checkiftokenExist->order_id)->update(['dispatcher_status_option_id' => $request->dispatcher_status_option_id]);
              
                    DB::commit();
                    $message = "Order status updated.";
                    return $this->successResponse($update, $message);
                   
            }else{
                DB::rollback();
                $message = "Invalid Order Token";
                return $this->errorResponse($message, 400);
               }
            
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), $e->getCode());
            
        }
    }


    /******************    ---- pickup delivery status update (Need to dispatcher_status_option_id ) -----   ******************/
    public function dispatchPickupDeliveryUpdate(Request $request, $domain = '', $web_hook_code)
    {
        try {
            DB::beginTransaction();
            $checkiftokenExist = OrderVendor::where('web_hook_code',$web_hook_code)->first();
            if($checkiftokenExist){
                $update = VendorOrderDispatcherStatus::updateOrCreate(['dispatcher_id' => null,
                    'order_id' =>  $checkiftokenExist->order_id,
                    'dispatcher_status_option_id' =>  $request->dispatcher_status_option_id,
                    'vendor_id' =>  $checkiftokenExist->vendor_id ]);

            if(isset($request->dispatch_traking_url) && !empty($request->dispatch_traking_url))
            {
                $update_tr = OrderVendor::where('web_hook_code',$web_hook_code)->update(['dispatch_traking_url' =>  $request->dispatch_traking_url]);
            }

            OrderVendor::where('vendor_id', $checkiftokenExist->vendor_id)->where('order_id', $checkiftokenExist->order_id)->update(['dispatcher_status_option_id' => $request->dispatcher_status_option_id]);
          
              
            
                    DB::commit();
                    $message = "Order status updated.";
                    return $this->successResponse($update, $message);
                   
            }else{
                DB::rollback();
                $message = "Invalid Order Token";
                return $this->errorResponse($message, 400);
               }
            
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), $e->getCode());
            
        }
    }
}
