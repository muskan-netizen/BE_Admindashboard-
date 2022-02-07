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
use App\Models\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DunzoController extends Controller
{
	
	use \App\Http\Traits\Dunzo;

    private $api_key;
    private $app_url;
    private $base_price;
    private $distance;
    private $amount_per_km;
    public $status;

    public function __construct()
    {
        $simp_creds = ShippingOption::select('credentials', 'test_mode','status')->where('code', 'dunzo')->where('status', 1)->first();
        if($simp_creds){
            $this->status = $simp_creds->status??'0';
            $creds_arr = json_decode($simp_creds->credentials);
            $this->api_key = $creds_arr->api_key??'';
            $this->app_url = (($simp_creds->test_mode=='1')?'https://dev.adloggs.com/aa':'https://app.adloggs.com/aa'); //Live url - https://app.adloggs.com/aa
            $this->base_price = $creds_arr->base_price ?? ''; 
            $this->distance = $creds_arr->distance ?? ''; 
            $this->amount_per_km = $creds_arr->amount_per_km ?? '';
        }else{
            return 0;
        }
    }

	public function configuration()
    {
        $simp_creds = ShippingOption::select('credentials', 'test_mode','status')->where('code', 'dunzo')->where('status', 1)->first();
        if($simp_creds){
            $this->status = $simp_creds->status??'0';
            $creds_arr = json_decode($simp_creds->credentials);
            $this->api_key = $creds_arr->api_key??'';
            $this->app_url = (($simp_creds->test_mode=='1')?'https://dev.adloggs.com/aa':'https://app.adloggs.com/aa'); //Live url - https://app.adloggs.com/aa
            $this->base_price = $creds_arr->base_price ?? ''; 
            $this->distance = $creds_arr->distance ?? ''; 
            $this->amount_per_km = $creds_arr->amount_per_km ?? '';
        }else{
            return 0;
        }
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
		 public function getDunzoBaseFee($vendorId)
		 {	
			$fees = 0;
			$this->configuration();
			if($this->status == 1 && $this->base_price>0){
				$distance = $this->getDistance($vendorId);
				if($distance){
					//Helper Function
					$fees =   getBaseprice($distance,'dunzo');
				}
			}
			return $fees;
		}


		# get delivery fee Shiprocket Courier Service
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


	public function createOrderRequestDunzo($user_id,$orderVendor)
    { 
		$this->configuration();
		if($this->status)
		{
			$order = Order::find($orderVendor->order_id);
        	$customer = User::find($user_id);
			$vendor_details = Vendor::find($orderVendor->vendor_id);
			$cus_address = UserAddress::find($order->address_id);
			$orderProducts = OrderVendorProduct::where(['order_id'=>$orderVendor->order_id,'order_vendor_id'=>$orderVendor->id])->get();
            $scheduledAt = '';
            if(isset($order->scheduled_date_time) && $order->scheduled_date_time){
                $date = date('Y-m-d',strtotime($order->scheduled_date_time));
                $time = date('H:i:s',strtotime($order->scheduled_date_time));
                $scheduledAt = $date.' '.$time;
            }

			$data = array (
				'partner_order_id' => $orderVendor->id.'-'.$orderVendor->order_id.'-'.$orderVendor->vendor_id,
				'pickup_contact_name' => $vendor_details->name ?? '',  
				'pickup_contact_no' => $vendor_details->phone_no, 
				'pickup_contact_email' => $vendor_details->email ?? '', 
				'pickup_address' => $vendor_details->address ?? '',  
			    'pickup_date_time' => ($scheduledAt!='')? $scheduledAt : date('Y-m-d H:i',strtotime($order->created_at)),
				'pickup_lat' => $vendor_details->latitude ?? '', //Required 
				'pickup_long' => $vendor_details->longitude ?? '', //Required 
				
                'delivery_contact_name' => $customer->name,
				'delivery_contact_no' => $customer->phone_number,
				'delivery_contact_email' => $customer->email,
                'delivery_address'=> $cus_address->address,
				'delivery_lat' => $cus_address->latitude, //Required
				'delivery_long' => $cus_address->longitude, //Required
				'order_description' => '',

                //(( INTEGER )) - Timezone difference with UTC in minutes for e.g. India IND 330 , Cuba CDT -240
				'utc_offset' => '330'
			  );
		}
    	$orderSuc = $this->createOrder($data);
		return $orderSuc;
		//Response Result
        // "status": true,
        // "code": 200,
        // "message": "Success",
        // "data": {
        //     "order_uuid": "f45d10c5-5edf-4ef4-aaaf-da4d96f1aebb",
        //     "trackUrl": "https://erranderz.in/dev/trackorder?key=f45d10c5-5edf-4ef4-aaaf-da4d96f1aebb",
        //     "partner_order_id": "100",
        //     "meta_data": "{}"
        // }

    }



    public function cancelOrderRequestDunzo($order_id)
    {
		$this->configuration();
		if($this->status){
            $data =array('order_uuid',$order_id,'update_type'=>'Cancel');
			return $cancel_order= $this->cancelOrder($data);
		}
    }


	public function dunzoWebhook(Request $request)
    {
		//1-AWB Assigned
		//2-Label Generated
		//3-Pickup Scheduled/Generated
		//19-Out For Pickup 
		//42-Picked Up 
		//6-Shipped 
		//7-Delivered 
		//8-Cancelled 
		//11-Pending 
		//17-Out For Delivery 
		//18-In Transit 
		//38-Reached Destination Hub 

        $trackingId = '';
        $json = json_decode($request->getContent());
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

        if($request && isset($json->shipment_status_id)){
         Webhook::create(['tracking_order_id'=>(($json->awb)?$json->awb:''),'response'=>$request->getContent()]);
        }

        return response([],200);

    }


}
