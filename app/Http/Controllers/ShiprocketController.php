<?php

namespace App\Http\Controllers;

use App\Models\ShippingOption;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiprocketController extends Controller
{
	
	use \App\Http\Traits\ShiprocketManager;

	private $base_price;
    private $status;
    private $distance;
    private $amount_per_km;

    public function __construct()
    {
  
      $simp_creds = ShippingOption::select('credentials', 'test_mode','status')->where('code', 'shiprocket')->where('status', 1)->first();
      if(isset($simp_creds) && !empty($simp_creds)){
            $creds_arr = json_decode($simp_creds->credentials);
            $this->base_price = $creds_arr->base_price??'0';
            if($this->base_price>0)
            {
                $this->base_price = $creds_arr->base_price??'0';
                $this->distance = $creds_arr->distance??'0';
                $this->amount_per_km = $creds_arr->amount_per_km??'0';

            }
            $this->status = $simp_creds->status??'0';
        }else{
			$this->status =0;
		}
    }


	public function configuration()
    {
  
      $simp_creds = ShippingOption::select('credentials', 'test_mode','status')->where('code', 'shiprocket')->where('status', 1)->first();
	  if(isset($simp_creds) && !empty($simp_creds)){
            $creds_arr = json_decode($simp_creds->credentials);
            $this->base_price = $creds_arr->base_price??'0';
            if($this->base_price>0)
            {
                $this->base_price = $creds_arr->base_price??'0';
                $this->distance = $creds_arr->distance??'0';
                $this->amount_per_km = $creds_arr->amount_per_km??'0';

            }
            $this->status = $simp_creds->status??'0';
        }else{
			$this->status = 0;
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


	    # get delivery fee Shiprocket
		public function getShiprocketFee($vendorId)
		 {	
			$fee = 0;
			$this->configuration();
			if($this->status == 1){
				$token = $this->getAuthToken();
				$distance = $this->getDistance($vendorId);
				if($distance){
					//Helper Function
					$fee =   getBaseprice($distance,'shiprocket');
				}
			}
			return $fee;
		}


		# get delivery fee Shiprocket Courier Service
		public function getCourierService($vendorId)
		{
			$service = array();
			$this->configuration();
			if($this->status == 1){
				$token = $this->getAuthToken();
				$service = $this->checkCourierService($token->token);
			}
			return $service;
		}




    public function checkShiprocket()
    {
		$this->configuration();
    	$token = $this->getAuthToken();
    	$order = $this->createOrder($token->token,[]);
    	$response = $this->generateAWBForShipment($token->token,["shipment_id"=>$order->shipment_id,"courier_id"=> "","status"=> ""]); // need kyc
    	$cancel_order= $this->cancelOrder($token->token,[$order->order_id]); // not working
    	$add_address = $this->addAddress($token->token,[]);
    	$tracking_shipping = $this->trackingThroughShipmentId($token->token,$order->shipment_id);
    	//dd($token->token,$order,$response,$order->order_id,$cancel_order,$add_address,$tracking_shipping);
    }


	public function createOrderRequestShiprocket(Request $request)
    {
		$this->configuration();
		$token = $this->getAuthToken();
    	$order = $this->createOrder($token->token,[]);
    }


    public function cancelOrderRequestShiprocket($order_id)
    {
		$this->configuration();
		$token = $this->getAuthToken();
		$cancel_order= $this->cancelOrder($token->token,[$order_id]);
    }


}
