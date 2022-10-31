<?php
namespace App\Http\Traits;

use DB;
use Auth;
use HttpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\Client as CP;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

use App\Models\{Order,ProductVariant,OrderVendor,VendorOrderCancelReturnPayment,ClientPreference,ProductBooking,User,UserAddress,Vendor,OrderProduct,OrderProductDispatchRoute,VendorOrderProductDispatcherStatus, Product};
use App\Http\Traits\{ValidatorTrait};

trait OrderTrait{
    use ValidatorTrait;

    public function ProductVariantStock($order_id)
    {

        $order = Order::with(['vendors.products.pvariant'])->find($order_id);
        if( isset($order->vendors )){
            foreach ($order->vendors as $vendor) {
                foreach ($vendor->products as $product) {
                    $ProductVariant = ProductVariant::find($product->variant_id);
                   
                    if ($ProductVariant) {
                            
                            $update_quantity  = $ProductVariant->quantity - $product->quantity;
                            if($update_quantity < 0)
                            $update_quantity  = 0;
                            
                            $ProductVariant->quantity  = $update_quantity;
                            $ProductVariant->save();
                        
                    }
                    
                }
            }
        }
        return 1;
    }

    public function GetVendorReturnAmount($request, $order){
       
        $order_vendor_paybel_amount = OrderVendor::where('order_id',$order->id)->where('order_status_option_id',"!=",'3')->select(DB::raw('sum(payable_amount) AS sum_of_order_payable_amount'))->first();
        $order_total_amount=  $order_vendor_paybel_amount->sum_of_order_payable_amount;
        
        $canceld_order_payments =VendorOrderCancelReturnPayment::where('order_id',$order->id)->select(DB::raw('sum(wallet_amount) AS sum_of_wallet_amount'),DB::raw('sum(online_payment_amount) AS sum_of_online_payment_amount'))->first();
        //pr($canceld_order_payments->toArray());
        $vendor_payble_amount = $order->vendors->first()->payable_amount;
        // vendor contribution in order
        $vendor_contribution_percentage = 0;
        if($order_total_amount > 0){
            $vendor_contribution_percentage = ($vendor_payble_amount / $order_total_amount) * 100;
        }

        $vendor_loyalty_amount =  $vendor_loyalty_points = $vendor_wallet_amount = $vendor_loyalty_points_earned = $vendor_online_payment_amount = 0;   

        if($order->loyalty_points_used > 0){
            // get loyalty for vendor
            $total_loyalty_amount = $order->loyalty_amount_saved ;
          
            // get loyalty points as pr 1 rup (primery Currency)
            $redeem_points_per_primary_currency = 0;
            if($order->loyalty_amount_saved > 0){
                $redeem_points_per_primary_currency =  $order->loyalty_points_used /  $order->loyalty_amount_saved;
            }
            
            // vendot loyalty amount in order
            $vendor_loyalty_amount =  ($total_loyalty_amount * $vendor_contribution_percentage ) / 100;

            // vendor loyalty points in order
            $vendor_loyalty_points  =  ($vendor_loyalty_amount * $redeem_points_per_primary_currency);
        }
        if($order->loyalty_points_earned > 0){
            $total_loyalty_points_earned = $order->loyalty_points_earned ;
            // get perticuler vendor loyalty point earnd 
            $vendor_loyalty_points_earned   =($total_loyalty_points_earned * $vendor_contribution_percentage ) / 100;
        }

        if($order->wallet_amount_used > 0){
            $order_total_wallet_amount =  $order->wallet_amount_used;
            // deduction  canceld order waller amount
            $order_total_wallet_amount = $order_total_wallet_amount -  $canceld_order_payments->sum_of_wallet_amount;

            $vendor_wallet_amount = ($order_total_wallet_amount * $vendor_contribution_percentage ) / 100;
        }
        if($order->payment_status == 1  ){
            $order_total_payable_amount =  $order->payable_amount;
             // deduction  canceld order online payment  amount
             $order_total_payable_amount = $order_total_payable_amount - $canceld_order_payments->sum_of_online_payment_amount;
            //vendo online payment contributuin in order
            $vendor_online_payment_amount = ($order_total_payable_amount * $vendor_contribution_percentage ) / 100;
        }
        
        $vendor_total_sum = $vendor_loyalty_amount +  $vendor_wallet_amount +  $vendor_online_payment_amount ;  

        $vendor_return_amount = $vendor_wallet_amount + $vendor_online_payment_amount;

        // get what time order placed according to current time
        $orderPlacedTime = (strtotime(now()) - strtotime($order->created_at)) / 60; // in minutes

        // check admin cancellation charges
        $client_preference_detail = ClientPreference::first();
        if(($client_preference_detail->order_cancellation_time > 0) && ($orderPlacedTime > $client_preference_detail->order_cancellation_time)){

            //online payment
            if($vendor_return_amount > 0){
                $vendor_return_amount = $vendor_return_amount - ($client_preference_detail->cancellation_percentage * $vendor_return_amount / 100);
            }
            //COD cancellation charges deduct from user wallet
            else{
                $order_total_payable_amount =  $order->payable_amount;
 
                // deduction canceld order online payment  amount
                $order_total_payable_amount = $order_total_payable_amount - $canceld_order_payments->sum_of_online_payment_amount;

                //vendo online payment contributuin in order
                $vendor_payment_amount = ($order_total_payable_amount * $vendor_contribution_percentage ) / 100;

                $cancellation_charges = $client_preference_detail->cancellation_percentage * $vendor_payment_amount / 100;

                // Debite order cancellation charges
                if($cancellation_charges > 0){
                    $user   = auth()->user();
                    $wallet = $user->wallet;
                    $wallet->forceWithdrawFloat($cancellation_charges, ['Wallet has been <b>debited</b> cancellation charges for order number '.$order->order_number]);
                }
            }
        }

        $data['vendor_return_amount']           = $vendor_return_amount;
        $data['vendor_loyalty_amount']          = $vendor_loyalty_amount;
        $data['vendor_wallet_amount']           = $vendor_wallet_amount;
        $data['vendor_online_payment_amount']   = $vendor_online_payment_amount;
        $data['vendor_total_sum']               = $vendor_total_sum;
        $data['vendor_contribution_percentage'] = $vendor_contribution_percentage;
        $data['vendor_loyalty_points']          = $vendor_loyalty_points;
        $data['vendor_loyalty_points_earned']   = $vendor_loyalty_points_earned;
       // pr($data);
        return  $data;

    }


     // place Request To Dispatch for Appointment , OnDemand
    public function placeRequestToDispatchSingleProduct($order, $vendor, $dispatch_domain,$request)
    {
       
        try {

            $order = Order::find($order);
            $customer = User::find($order->user_id);
            $cus_address = UserAddress::find($order->address_id);
            $tasks = array();
            $task_type = 'now';
            $schedule_time = '';
            $return_response = 2;
            $paymentSentAlready = 0;
            $vendor_details = Vendor::where('id', $vendor)->select('id', 'name', 'phone_no', 'email', 'latitude', 'longitude', 'address')->first();
         
            $order_vendor = OrderVendor::with('products.product')->where(['order_id' => $request->order_id, 'vendor_id' => $request->vendor_id])->first();
       
            foreach( $order_vendor->products as $product){
                $allocation_type = 'a';
                $agent = '';
                if ($order->payment_option_id == 1 ) {
                    $cash_to_be_collected = 'Yes';
                    $payable_amount = $order_vendor->payable_amount + $order_vendor->taxable_amount;
                } else {
                    $cash_to_be_collected = 'No';
                    $payable_amount = 0.00;
                }
               
                $tasks = array();
                $meta_data = '';
    
                $unique = Auth::user()->code;
                $team_tag = $unique . "_" . $vendor;
                if(!empty($product->scheduled_date_time) && $product->scheduled_date_time > 0){
                    $task_type = 'schedule';
                    $user = Auth::user();
                    $selectedDate = dateTimeInUserTimeZone($product->scheduled_date_time, $user->timezone);
                    $slot = trim(explode("-",$product->schedule_slot)[0]);
    
                    $slotTime = date('H:i:s', strtotime("$slot"));
                    $selectedDate = date('Y-m-d',strtotime($selectedDate));
                    $scheduleDateTime = $selectedDate.' '.$slotTime;
                    $schedule_time =  $scheduleDateTime?? null;
                }

                $task_type_id = $dispatch_domain['service_type'] == 'appointment' ?  3 : 1;
                $service_time = $product->product->first() ? $product->product->minimum_duration_min : 0;
                Log::info('service_time');
                Log::info($service_time);
                $tasks[] = array(
                    'task_type_id' => $task_type_id,
                    'latitude'     => $vendor_details->latitude ?? '',
                    'longitude'    => $vendor_details->longitude ?? '',
                    'short_name'   => '',
                    'address'      => $vendor_details->address ?? '',
                    'post_code'    => '',
                    'barcode'      => '',
                    'flat_no'     => null,
                    'email'       => $vendor_details->email ?? null,
                    'phone_number' => $vendor_details->phone_no ?? null,
                    'appointment_duration' =>  $dispatch_domain['service_type'] == 'appointment' ?  $service_time  : null ,
                );
                if($product->dispatch_agent_id){
                    $allocation_type = 'm';
                    $agent = $product->dispatch_agent_id;
                }
                if($dispatch_domain['service_type'] == 'on_demand' ){
                    $tasks[] = array(
                        'task_type_id' => 2,
                        'latitude' => $cus_address->latitude ?? '',
                        'longitude' => $cus_address->longitude ?? '',
                        'short_name' => '',
                        'address' => $cus_address->address ?? '',
                        'post_code' => $cus_address->pincode ?? '',
                        'barcode' => '',
                        'flat_no'     => $cus_address->house_number ?? null,
                        'email'       => $customer->email ?? null,
                        'phone_number' => ($customer->dial_code . $customer->phone_number)  ?? null,
                    );
                }
                Log::info('send agent id to driver');
                Log::info($agent);
                if ($customer->dial_code == "971") {
                    // $customerno = '+' . $customer->dial_code . "0" . $customer->phone_number;
                    $customerno = "0" . $customer->phone_number;
                } else {                
                    // $customerno = ($customer->phone_number) ? '+' . $customer->dial_code . $customer->phone_number : rand(111111, 11111) ;
                    $customerno = ($customer->phone_number) ? $customer->phone_number : rand(111111, 11111);
                }
               
                $client = CP::orderBy('id', 'asc')->first();
                for ($x = 1; $x <= $product->quantity; $x++) {
                    //  send all payment to fist order 
                    if( $paymentSentAlready == 0){
                        $paymentSentAlready =1;
                    }else{
                        $cash_to_be_collected = 'No';
                        $payable_amount = 0.00;
                    }
                    $dynamic = uniqid($order->id . $vendor . $product->product_id.$x);
               
                    $call_back_url = route('dispatch-order-product-status-update', $dynamic);
                    $postdata =  [
                        'order_number'  =>  $order->order_number,
                        'customer_name' => $customer->name ?? 'Dummy Customer',
                        'customer_phone_number' => $customerno ?? rand(111111, 11111),
                        'customer_dial_code' => $customer->dial_code ?? null,
                        'customer_email' => $customer->email ?? null,
                        'recipient_phone' => $customerno ?? rand(111111, 11111),
                        'recipient_email' => $customer->email ?? null,
                        'task_description' => "Order From :" . $vendor_details->name,
                        'allocation_type' => $allocation_type,
                        'task_type' => $task_type,
                        'schedule_time' => $schedule_time ?? null,
                        'cash_to_be_collected' => $payable_amount ?? 0.00,
                        'barcode' => '',
                        'order_team_tag' => $team_tag,
                        'call_back_url' => $call_back_url ?? null,
                        'task' => $tasks,
                        'is_restricted' => $order_vendor->is_restricted,
                        'vendor_id' => $vendor_details->id,
                        'order_vendor_id' => @$order_vendor->id,
                        'dbname' => @$client->database_name,
                        'order_id' => @$order->id,
                        'customer_id' => @$order->user_id,
                        'user_icon' => $customer->image,
                        'agent'     => $agent,
                        'task_type_id' =>$task_type_id, //  for add agent booking in case of appointment
                        'service_time' =>  $service_time
                    ];
                  
                    
                    if($order_vendor->is_restricted == 1)
                    {
                        $postdata['user_verification_type'] = isset($customer->passbase_verification) && !is_null($customer->passbase_verification) ? $customer->passbase_verification->resources->type : null;
                        $postdata['user_datapoints'] = isset($customer->passbase_verification) && !is_null($customer->passbase_verification) ? json_decode($customer->passbase_verification->resources->datapoints) : null;
                    }
                  
        
                    $client = new Client([
                        'headers' => [
                            'personaltoken' => $dispatch_domain['service_key'],
                            'shortcode'     => $dispatch_domain['service_key_code'],
                            'content-type'  => 'application/json'
                        ]
                    ]);
                    
                    $url = $dispatch_domain['service_key_url'];
                    $res = $client->post(
                        $url . '/api/task/create',
                        ['form_params' => ($postdata)]
                    );
                    $response = json_decode($res->getBody(), true);
                    if ($response && $response['task_id'] > 0) {
                        $dispatch_traking_url = $response['dispatch_traking_url'] ?? '';
    
                        $dispatch_route                                 = new OrderProductDispatchRoute();
                        $dispatch_route->order_id                       = $request->order_id ;
                        $dispatch_route->order_vendor_id                = $product->order_vendor_id ;
                        $dispatch_route->order_vendor_product_id        = $product->id ;
                        $dispatch_route->web_hook_code                  = $dynamic;
                        $dispatch_route->dispatch_traking_url           = $dispatch_traking_url ;
                        $dispatch_route->dispatcher_status_option_id    = 1 ;
                        $dispatch_route->order_status_option_id         = 1 ;
                        $dispatch_route->save();
                        
                        $update = VendorOrderProductDispatcherStatus::updateOrCreate([
                            'dispatcher_id' => null,
                            'order_id' =>  $request->order_id,
                            'dispatcher_status_option_id' => 1,
                            'vendor_id' =>  $request->vendor_id,
                            'order_product_route_id' => $dispatch_route->id 
                        ]);
                       
                        $return_response = 1;
                    }
              
                }
            }
            return $return_response;
        } catch (\Exception $e) {
            //return 2;
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
   

    public function bookingSlot($request)
    {

        $request = (object) $request;
       
        try {
            DB::beginTransaction(); //Initiate transaction
             
              $start_time = date("Y-m-d H:i:s",strtotime($request->start_date));
              $end_time = date("Y-m-d H:i:s",strtotime($request->end_date));
             
              
              $start_end_block_time = $start_time;
    
              $status = ProductBooking::Create([
                                'memo'=>$request->memo,
                                'variant_id'=>$request->variant_id,
                                'product_id'=>$request->product_id,
                                'order_vendor_id'=>$request->order_vendor_id,
                                'start_date_time'=>$start_time,
                                'booking_type'=>'new_booking',
                                'end_date_time'=>$end_time,
                                'order_user_id' => $request->order_user_id,
                                'booking_start_end'=>$start_end_block_time
                                ]);
            DB::commit(); //Commit transaction after all the operations
            return 1 ;
          } catch (Exception $e) {
              DB::rollBack();
              return 0;
          }
    }

    /** update vendor rating
     * @author sudhanshu sharma
     */
    public function updateVendorRating($vendor_id){
        $vendor_rating = 0;
        
        if($vendor_id != null & $vendor_id > 0){
            $vendor_rating = Product::where('vendor_id', $vendor_id)
                            ->avg('averageRating');
        } 

        if($this->checkColumnExists('vendors', 'rating')){
            Vendor::where('id', $vendor_id)->update(['rating' => $vendor_rating]);
            return $vendor_rating;
        }else{
            return $vendor_rating;
        }
    }

    /**
     * get or update vendor rating if rating is null
     * @author sudhanshu sharma
     */
    public function getVendorRating($vendor_id){
        $vendor_rating = 0;

        $vendor = Vendor::find($vendor_id);

        if($vendor && $vendor->rating == null){
            
            $vendor_rating = $this->updateVendorRating($vendor_id);
            return number_format($vendor_rating, 1);

        }else if($vendor){

            return number_format($vendor->rating, 1);

        }else{

            return number_format($vendor_rating, 1);

        }


    }

}
