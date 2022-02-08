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

class AhoyController extends Controller
{
	
	use \App\Http\Traits\Ahoy;

    private $api_key;
    private $app_url;
    private $base_price;
    private $distance;
    private $amount_per_km;
    public $status;

    public function __construct()
    {
        $simp_creds = ShippingOption::select('credentials', 'test_mode','status')->where('code', 'ahoy')->where('status', 1)->first();
        if($simp_creds){
            $this->status = $simp_creds->status??'0';
            $creds_arr = json_decode($simp_creds->credentials);
            $this->api_key = $creds_arr->api_key??'';
            $this->app_url = (($simp_creds->test_mode=='1')?'https://ahoydev.azure-api.net':'https://ahoyapis.azure-api.net'); //Live url - https://ahoydev.azure-api.net
            $this->test = $simp_creds->test_mode; 
            $this->base_price = $creds_arr->base_price ?? ''; 
            $this->distance = $creds_arr->distance ?? ''; 
            $this->amount_per_km = $creds_arr->amount_per_km ?? '';
        }else{
            return 0;
        }
    }

	public function configuration()
    {
        $simp_creds = ShippingOption::select('credentials', 'test_mode','status')->where('code', 'ahoy')->where('status', 1)->first();
            if($simp_creds){
                $this->status = $simp_creds->status??'0';
                $creds_arr = json_decode($simp_creds->credentials);
                $this->api_key = $creds_arr->api_key??'';
                $this->app_url = (($simp_creds->test_mode=='1')?'https://ahoydev.azure-api.net':'https://ahoyapis.azure-api.net'); //Live url - https://ahoydev.azure-api.net
                $this->test = $simp_creds->test_mode; 
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


	public function createOrderPreRequestAhoy($user_id,$orderVendor)
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
				'CompanyOrderTrackId' => $orderVendor->id.'-'.$orderVendor->order_id.'-'.$orderVendor->vendor_id,
				'PickupLocationId' => $vendor_details->name ?? '',   //Required 
				'CustomerAddressId' => '',  
				'OrderLargeBoxQuantity' => $vendor_details->name ?? '',  
				'OrderMidBoxQuantity' => $vendor_details->phone_no, 
				'OrderSmallBoxQuantity' => $vendor_details->email ?? '', 
				'pickup_address' => $vendor_details->address ?? '',  
			    'PickupTime' => ($scheduledAt!='')? strtotime($scheduledAt) : strtotime($order->created_at),  //If order to be scheduled, provide pickup time in UTC Unix millisecond. if the order is an immediate leave as 0
				
                'CustomerName' => $customer->name,
				'CustomerPhone' => $customer->phone_number,
				'CustomerEmail' => $customer->email,
                'CustomerAddress'=> $cus_address->address,
				'CustomerLatitude' => $cus_address->latitude, //Required
				'CustomerLongitude' => $cus_address->longitude, //Required
				'IsCashPayment' =>  false,
				'IsCardPayment' =>  false,
				'CashAmount' => ($order->payment_option_id==1)?$order->total_amount : 0,
				'CustomerAddressTypeId' => '1',
				'CustomerAddressNote' => '',
				'Area' => '1',
				'Building' => '1',
				'Floor' => '1',
				'Unit' => '1',
				'TemperatureTypeId' => 0,


            //     • CustomerAddressTypeId
            //     ◦ 1 => Tower, (either office or apartment)
            //     ◦ 2 => Building (villa, police station. etc)
            //     ◦ 3 => Commercial (warehouse)
            // • Area => Area
            // • Building => “Building Name or Number”
            // • Floor => “Floor Number”
            // • Unit => “Apartment Number”.
            // • TemperatureTypeId 
            //     ◦ 0 => Normal
            //     ◦ 1 => Cold
            //     ◦ 2 => Warm

			  );
		}
    	$orderSuc = $this->createPreOrder($data);
		return $orderSuc;
		//Response Result
        // "preOrderId": 220,
        // "expiryTime": 1600352489947,
        // "services": [{
        //     "serviceName": "Bike",
        //     "serviceDetails": "ETA 6:25 PM",
        //     "numberOfVehicles": 1,
        //     "unitPrice": 20.00,
        //     "currency": "AED",
        //     "serviceId": 5,
        //     "serviceImageUrl": "https://ahoydelivery.blob.core.windows.net/icons/expressbike.png"
        // }]

    }




    public function confirmOrderPreRequestAhoy($order_id)
    {
		$this->configuration();
		if($this->status){
            $data =array('preOrderId',$order_id,'deliveryServiceTypeId'=>1);
			return $order= $this->confirmPreOrder($data);
		}
    }


    # get delivery fee createLocation Courier Service
		public function createLocation($vendorId)
		{
			$this->configuration();
            $vendor_details = Vendor::find($vendorId);
            $data =array(
                'locationName'=>$vendor_details->name ?? '',
                'Address'=>$vendor_details->address ?? '',
                'latitude'=>$vendor_details->latitude ?? '',
                'longitude'=>$vendor_details->longitude ?? '',
                'locationType'=>1,
                'PhoneNumber'=>$vendor_details->phone_number ?? '',
                'Email'=>$vendor_details->email ?? ''
            );
		
            if($this->status){
                return $this->createNewLocation($vendorId);
            }
            return 0; 
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
