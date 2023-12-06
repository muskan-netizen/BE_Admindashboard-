<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderVendor;
use App\Models\OrderVendorProduct;
use App\Models\ShippingOption;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Vendor;
use App\Models\VendorOrderDispatcherStatus;
use App\Models\VendorOrderStatus;
use App\Models\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
class D4BDunzoController extends Controller
{
    use \App\Http\Traits\Dunzo;
    private $client_id;
    private $client_secret;
    private $app_url;
    private $base_price;
    private $distance;
    private $amount_per_km;
    public $status;
    private $token;
    public function __construct()
    {
       
        $simp_creds = ShippingOption::select('credentials', 'test_mode','status')->where('code', 'd4b_dunzo')->where('status', 1)->first();
      
        if($simp_creds){
            $this->status = $simp_creds->status??'0';
            $creds_arr = json_decode($simp_creds->credentials);
          
            $this->client_id = $creds_arr->client_id??'';
            $this->client_secret = $creds_arr->client_secret??'';
            $this->client_id = $creds_arr->client_id??'';
            // $this->app_url = (($simp_creds->test_mode=='1')?'https://apis-staging.dunzo.in':'https://apis-staging.dunzo.in'); //Live url - https://app.adloggs.com/aa
            $this->app_url = (($simp_creds->test_mode=='1')?'https://apis-staging.dunzo.in/api/v1/token':'https://api.dunzo.in/api/v1/token'); //Live url - https://app.adloggs.com/aa
            $this->base_price = $creds_arr->base_price ?? ''; 
            $this->distance = $creds_arr->distance ?? ''; 
            $this->amount_per_km = $creds_arr->amount_per_km ?? '';
            $response = Http::withHeaders([
                'client-id' => $this->client_id,
                'client-secret' => $this->client_secret,
                'Accept-Language' => 'en_US',
                'Content-Type' => 'application/json',
            ])->get($this->app_url);        
            // Work with the response as needed
            $status = $response->status();            
            $content = $response->json(); // Assuming the response is in JSON format
            $this->token = $content['token'];
        }else{
            return 0;
        }
    }
    public function quote($vendor_id)
    {
        try{    
                $customer = User::find(Auth::id());
                $cus_address = UserAddress::where('user_id', Auth::id())->orderBy('is_primary', 'desc')->first();
                
                if ($cus_address && $this->status==1){
                   
                    $vendor_details = Vendor::find($vendor_id);
                    // 'pick_lat' => $vendor_details->latitude,
                    //     'pick_lng' => $vendor_details->longitude,
                    //     'pick_address' => $vendor_details->address,
                    //     'vendor_name' => $vendor_details->name,
                    //     'vendor_contact' => $vendor_details->phone_no,
                    //     'drop_lat' => $cus_address->latitude,
                    //     'drop_lng' => $cus_address->longitude,
                    //     'drop_address' => $cus_address->address,
                    //     'user_name' => $customer->name,
                    //     'user_phone' => $customer->phone_number,
                    //     'remarks' => orderProductDetails($order_id),
                    //     'schedule_time' => $scheduledAt,
               
                    $response = Http::withHeaders([
                        'client-id' => $this->client_id,
                        'Authorization' => $this->token,
                        'Accept-Language' => 'en_US',
                        'Content-Type' => 'application/json',
                    ])->post('https://apis-staging.dunzo.in/api/v2/quote', [
                        'pickup_details' => [
                            [
                                'lat' => floatval($vendor_details->latitude),
                                'lng' => floatval($vendor_details->longitude),
                                'reference_id' => 'pickup-ref-abcd123'.strtotime(now()),
                            ],
                        ],
                        'optimised_route' => true,
                        'drop_details' => [
                            [
                                'lat' =>  floatval($cus_address->latitude),
                                'lng' => floatval($cus_address->longitude),
                                'reference_id' => 'drop-ref1-abcd887'.strtotime(now()),
                                // 'payment_data' => [
                                //     'payment_method' => 'COD',
                                //     'amount' => 101,
                                // ],
                            ]
                           
                                ],
                        'delivery_type' => 'SCHEDULED',
                       
                        'schedule_time' => Carbon::now()->addMinutes(31)->timestamp,
                    ]);
                  if($response->successful()){
                    // dd($response->json());
                    return $response->json();
                            // dd($response->json());
                  }else{
                    // dd($response->json());  
                    $response = 2;
                  }
                //     if($quotation['code']=='200'){
                //         $response = $this->placeOrders($data,$response);
                //     \Log::info(json_encode($response));
                //         if($response['code']=='200'){
                //             $response = json_decode($response['response']);
                //         }else{
                //             $response = 2;
                //         }
                // }else{
                //     $response = 2;
                // }
                  
                    // \Log::info($response);
                  
                  
                //     $data = (object) array(
                //         'pick_lat' => $vendor_details->latitude,
                //         'pick_lng' => $vendor_details->longitude,
                //         'pick_address' => $vendor_details->address,
                //         'vendor_name' => $vendor_details->name,
                //         // 'vendor_contact' => $vendor_details->phone_no,
                //         'vendor_contact' => '3768865552',
                //         'drop_lat' => $cus_address->latitude,
                //         'drop_lng' => $cus_address->longitude,
                //         'drop_address' => $cus_address->address,
                //         'user_name' => $customer->name,
                //         'user_phone' => $customer->phone_number,
                //         'remarks' => 'Delivery vendor message remarks'
                //     );
            
                //     $quotation = $this->getQuotations($data);
                //     $actualAmount=0;
                //     if($quotation['code']!='409')
                //     { 
                //         $json = json_decode($quotation['response']);
                //         $distance =  round($json->distance->value/1000);
                //         if($this->base_price > 0)
                //         {
                //             $actualAmount = getBaseprice($distance);
                //          }else{
                //             $actualAmount = $json->totalFee;
                //         }
                //     }
                //     //dd($actualAmount);
                //     return $actualAmount;
                }
            
        }catch(\Exception $e)
        {
            return 0;
        }
    }
    public function createOrderRequestD4BDunzo($user_id,$orderVendor)
    { 
		// $this->configuration();
	
		
        
        $order = Order::find($orderVendor->order_id);
        $customer = User::find($user_id);
        $vendor_details = Vendor::find($orderVendor->vendor_id);
      
        $cus_address = UserAddress::find($order->address_id);
        $orderProducts = OrderVendorProduct::where(['order_id'=>$orderVendor->order_id,'order_vendor_id'=>$orderVendor->id])->get();
        // create order
      
        $response_d4b_dunzo = Http::withHeaders([
            'client-id' => $this->client_id,
            'Authorization' => $this->token,
            'Accept-Language' => 'en_US',
            'Content-Type' => 'application/json',
        ])
        ->post('https://apis-staging.dunzo.in/api/v2/tasks', [
            'request_id' =>  $orderVendor->id.'-'.$orderVendor->order_id.'-'.$orderVendor->vendor_id.strtotime(now()),
            // 'reference_id' => '9357d296-c366-4409-872d-2e0898f27f80'.strtotime(now()),
            'pickup_details' => [
                [
                    'reference_id' => 'pick_ref_1'.$orderVendor->id.'-'.$orderVendor->order_id.'-'.$orderVendor->vendor_id.strtotime(now()),
                    'special_instructions' => 'fragile items, handle with great care',
                    'address' => [
                        // 'apartment_address' => '004',
                        'street_address_1' => $vendor_details->address,
                        // 'street_address_2' => 'LB Shastri nagar',
                        // 'landmark' => 'Iblur lake',
                        'city' =>  $vendor_details->city,
                        'state' => $vendor_details->state,
                        'pincode' => $vendor_details->pincode,
                        'country' =>  $vendor_details->country,
                        'lat' => (float) $vendor_details->latitude,
                        'lng' => (float) $vendor_details->longitude,
                        'contact_details' => [
                            'name' => $vendor_details->name,
                            'phone_number' => $vendor_details->phone_no,
                        ],
                    ],
                    'otp_required' => false,
                ],
            ],
            // 'optimised_route' => true,
            'drop_details' => [
                [
                    'reference_id' => 'drop_ref_1'.$orderVendor->id.'-'.$orderVendor->order_id.'-'.$orderVendor->vendor_id.strtotime(now()),
                    'special_instructions' => 'leave at door step and ring the bell',
                    'address' => [
                        // 'apartment_address' => '204 Block 4',
                        'street_address_1' => 'Suncity Apartments',
                        // 'street_address_2' => 'Bellandur',
                        // 'landmark' => 'Iblur lake',
                        'city' =>  $cus_address->city ?? '',
                        'state' => $cus_address->state ?? '',
                        'pincode' => $cus_address->pincode  ?? '',
                        'lat' => (float) $cus_address->latitude,
                        'lng' => (float) $cus_address->longitude,
                        'country' =>$cus_address->country ?? '',
                        'contact_details' => [
                            'name' => $customer->name,
                            'phone_number' => $customer->phone_number,
                        ],
                        
                    ],
                    
                    'otp_required' => false,
                    'payment_data' => [
                        'payment_method' => (($order->payment_option_id==1)?'COD':'Prepaid'),
                        'amount' =>  $order->total_amount,
                    ],
                ]
            ],
            'payment_method' => 'DUNZO_CREDIT',
            'delivery_type' => 'SCHEDULED',
            'schedule_time' => Carbon::now()->addMinutes(31)->timestamp,
        ]);   
        
        
        return $response_d4b_dunzo->json();
    }
    public function dunzoWebhook(Request $request)
    {
        // "order_status_id": 4,
        // "order_uuid":"4ed83e5d-ec49-44ef-a7ea-eba3cfd91416",
        // "partner_order_id": "78954uigg",
        // "deliveryStaffDetails": {
        //     "name": "karthick",
        //     "phone": "99999999999",
        //     "currentLocation": {
        //         "lat": 10.452855555,
        //         "long": 11.55854455
        //     }
        // }
        $trackingId = '';
        $json = json_decode($request->getContent());
        if($request && isset($json->order_uuid)){
            //is for Dunzo webhook 
            Webhook::create(['tracking_order_id'=>(($json->order_uuid)?$json->order_uuid:''),'response'=>$request->getContent()]);
            $awb = $json->order_uuid;
            $dispatcher_status_option_id = '';
            $order_status_option_id = '';
            $details = OrderVendor::where('web_hook_code',$awb)->first();
            if(isset($json->order_status_id) && $json->order_status_id == '3'){
                $dispatcher_status_option_id = '1';
            }elseif(isset($json->order_status_id) && $json->order_status_id == '4'){
                $dispatcher_status_option_id = '3';
                $order_status_option_id = '4';
            }elseif(isset($json->order_status_id) && $json->order_status_id == '8'){
                $dispatcher_status_option_id = '4';
                $order_status_option_id = '5';              
            }elseif(isset($json->order_status_id) && $json->order_status_id == '5'){
                $dispatcher_status_option_id = '5';
                $order_status_option_id = '6';
            }elseif(isset($json->order_status_id) && $json->order_status_id == '6'){
                $dispatcher_status_option_id = '6';
                $order_status_option_id = '3';
            }
            if(!empty($dispatcher_status_option_id) && !empty($details)){
                $data = array('order_id'=>$details->order_id,'vendor_id'=>$details->vendor_id,'dispatcher_status_option_id'=>$dispatcher_status_option_id);
                if($dispatcher_status_option_id == 5){
                    $data = array_merge($data,array('type' => '2'));
                }
                $update = VendorOrderDispatcherStatus::Create($data);
                if($dispatcher_status_option_id == '1'){
                    $update = VendorOrderDispatcherStatus::Create(['order_id'=>$details->order_id,'vendor_id'=>$details->vendor_id,'dispatcher_status_option_id'=>'2']);                
                }
                $this->sendOrderNotification($update->id);
                if(isset($order_status_option_id) && !empty($order_status_option_id)){            
                    $checkif= VendorOrderStatus::where(['order_id' => $details->order_id,
                        'order_status_option_id' =>  $order_status_option_id,
                        'vendor_id' =>  $details->vendor_id,
                        'order_vendor_id' =>  $details->id])->count();
                    
                    if($checkif == 0){
                        $update_vendor = VendorOrderStatus::updateOrCreate([
                            'order_id' =>  $details->order_id,
                            'order_status_option_id' =>  $order_status_option_id,
                            'vendor_id' =>  $details->vendor_id,
                            'order_vendor_id' =>  $details->id ]);
                        
                        OrderVendor::where('vendor_id', $details->vendor_id)->where('order_id', $details->order_id)->update(['order_status_option_id' => $order_status_option_id]);
                        // if driver is reject order
                        if($order_status_option_id == 3 ){
                            $this->cancelOrderByDriver($details);
                        }
                    }
                }
                OrderVendor::where('vendor_id', $details->vendor_id)->where('order_id', $details->order_id)->update(['dispatcher_status_option_id' => $dispatcher_status_option_id]);          
            }
        }elseif(@$json->awb){
        //shiprocket webhook
        Webhook::create(['tracking_order_id'=>(($json->awb)?$json->awb:''),'response'=>$request->getContent()]);
        if(isset($json->shipment_status_id) && $json->shipment_status_id == '1')
        {
            $awb = $json->awb;
            $details = OrderVendor::where('ship_awb_id',$awb)->first();
            VendorOrderDispatcherStatus::Create(['order_id'=>$details->order_id,'vendor_id'=>$details->vendor_id,'dispatcher_status_option_id'=>'1']);
        }elseif(isset($json->shipment_status_id) && $json->shipment_status_id == '3')
        {
			$awb = $json->awb;
            $details = OrderVendor::where('ship_awb_id',$awb)->first();
            VendorOrderDispatcherStatus::Create(['order_id'=>$details->order_id,'vendor_id'=>$details->vendor_id,'dispatcher_status_option_id'=>'2']);
        }elseif(isset($json->shipment_status_id) && $json->shipment_status_id == '19')
        {
			$awb = $json->awb;
            $details = OrderVendor::where('ship_awb_id',$awb)->first();
            VendorOrderDispatcherStatus::Create(['order_id'=>$details->order_id,'vendor_id'=>$details->vendor_id,'dispatcher_status_option_id'=>'3']);
        }elseif(isset($json->shipment_status_id) && $json->shipment_status_id == '42')
        {
			$awb = $json->awb;
            $details = OrderVendor::where('ship_awb_id',$awb)->first();
            VendorOrderDispatcherStatus::Create(['order_id'=>$details->order_id,'vendor_id'=>$details->vendor_id,'dispatcher_status_option_id'=>'4']);
        }elseif(isset($json->shipment_status_id) && $json->shipment_status_id == '7')
        {
            $awb = $json->awb;
            $details = OrderVendor::where('ship_awb_id',$awb)->first();
            VendorOrderDispatcherStatus::Create(['order_id'=>$details->order_id,'vendor_id'=>$details->vendor_id,'dispatcher_status_option_id'=>'5','type'=>'2']);
        }
    }
        return response([],200);
    }
    public function d4bdunzoWebhook(Request $request)
    {
            try {
                $jsonData = json_decode($request->getContent());
                $taskId = $jsonData->task_id;      
                $details = OrderVendor::where('d4b_task_id', $taskId)->first();
                $trackingStatus = $this->getTrackInfo($taskId);
                $dispatcherStatusOptionId = null;
                switch ($jsonData->state) {
                    case 'queued':
                        $dispatcherStatusOptionId = '1';
                        break;
                    case 'runner_accepted/reached_for_pickup':
                        $dispatcherStatusOptionId = '2';
                        break;
                    case 'pickup_complete/started_for_delivery/reached_for_delivery':
                        $dispatcherStatusOptionId = '4';
                        break;
                    case 'delivered':
                        $dispatcherStatusOptionId = '6';
                        break;
                    case 'cancelled':
                    case 'location_cancelled':
                        $dispatcherStatusOptionId = '3';
                        break;
                    // Add more cases as needed
                    // Default case if the state doesn't match any known values
                    default:
                        // Handle unknown state if necessary
                }
               
                if ($dispatcherStatusOptionId !== null) {
                  
                //   VendorOrderStatus::where([
                //         'order_id' => $details->order_id,
                //         'vendor_id' => $details->vendor_id,
                     
                       
                //     ])->update(['order_status_option_id'=>$dispatcherStatusOptionId]);
                    OrderVendor::where([
                        'order_id' => $details->order_id,
                        'vendor_id' => $details->vendor_id,
                     
                       
                    ])->update(['order_status_option_id'=>$dispatcherStatusOptionId]);
                    
                    // dd(VendorOrderStatus::where([
                    //     'order_id' => $details->order_id,
                    //     'vendor_id' => $details->vendor_id,
                     
                       
                    // ])->first()); 
                    // VendorOrderStatus::Create(['order_id'=>$details->order_id,'vendor_id'=>$details->vendor_id,'order_status_option_id'=>$dispatcherStatusOptionId]);
                    VendorOrderDispatcherStatus::create([
                        'order_id' => $details->order_id,
                        'vendor_id' => $details->vendor_id,
                        'dispatcher_status_option_id' => $dispatcherStatusOptionId
                    ]);
                }
                return response([], 200);
            } catch (Exception $e) {
                // Handle exceptions here
                return response(['error' => $e->getMessage()], 500);
            }
    }
    public function getTrackInfo($task_id)
    {
        $traking_res = Http::withHeaders([
            'client-id' => $this->client_id,
            'Authorization' => $this->token,
            'Accept-Language' => 'en_US',
            'Content-Type' => 'application/json',
        ])
        ->get('https://apis-staging.dunzo.in/api/v1/tasks/'.$task_id.'/status');
        
        // You can then handle the traking_res as needed
       
        return $traking_res->json();   
    }
	public function getDistance($vendorId)
    {
		$this->configuration();
		if($this->status == 1){
			$customer = User::find(Auth::id());
			$cus_address = UserAddress::where('user_id', Auth::id())->orderBy('is_primary', 'desc')->first();
			$vendor_details = Vendor::find($vendorId);
			$latitude[] =  $vendor_details->latitude ?? 30.71728880;
			$latitude[] =  $cus_address->latitude ?? 30.717288800000;
			$longitude[] =  $vendor_details->longitude ?? 76.803508700000;
			$longitude[] =  $cus_address->longitude ?? 76.803508700000;
			$distance =  GoogleDistanceMatrix($latitude,$longitude);
			return $distance['distance'];
		}
		return false;
    }
		 # get delivery fee getDunzoBaseFee
		 public function getDunzoBaseFee($vendorId,$distance = null)
		 {	
			$fees = 0;
			$this->configuration();
			if($this->status == 1 && $this->base_price>0){
                    if(!$distance){
				      $distance = $this->getDistance($vendorId);
                    }
				if($distance){
					//Helper Function
					$fees =   getBaseprice($distance,'dunzo');
				}
			}
			return $fees;
		}
        # get delivery fee Dunzo Courier Service
		public function getQuotations($vendor_id,$address)
		{
			$this->configuration();
			$vendor_details = Vendor::find($vendor_id);
            $data =array(
                'pickup_lat'=>$vendor_details->latitude ?? '',
                'pickup_long'=>$vendor_details->longitude ?? '',
                'delivery_lat' => $address->latitude, //Required
				'delivery_long' => $address->longitude, //Required
            );
			$status =  $this->getfees($data);
			if($this->status){
				if(($this->base_price>0) && $status->status == true){
				return $this->getDunzoBaseFee($vendor_id,$status->data->distance);
			}else{
                    if($status->status){
                        return $status->data->estimated_price??0;
                    }
                    return 0; 
				}
			}
            return 0; 
		}
		# get delivery fee Courier Service
		public function getCourierService($vendorId)
		{
			$this->configuration();
            $vendor_details = Vendor::find($vendorId);
            $data =array(
                'pickup_lat'=>$vendor_details->latitude ?? '',
                'pickup_long'=>$vendor_details->longitude ?? ''
            );
			$status =  $this->checkAvilabilty($data);
			if($this->status == 1){
				if(($this->base_price>0) && $status->status == true){
				return $this->getDunzoBaseFee($vendorId);
			}else{
                    if($status->status){
                        return $this->getDunzoBaseFee($vendorId);
                    }
                    return 0; 
				}
			}
		}
    public function cancelOrderRequestDunzo($order_id)
    {
		// $this->configuration();
		// if($this->status){
        //     $data =array('order_uuid',$order_id,'update_type'=>'Cancel');
		// 	return $cancel_order= $this->cancelOrder($data);
		// }
    }
	
}