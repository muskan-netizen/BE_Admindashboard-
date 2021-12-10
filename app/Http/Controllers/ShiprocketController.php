<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShiprocketController extends Controller
{
	
	use \App\Http\Traits\ShiprocketManager;
    public function checkShiprocket()
    {
    	// $auth = $this->getFedexAuthToken();
    	// $val_address = $this->validateAddress($auth->access_token);
    	// $rate = $this->getRate($auth->access_token);
    	// $shipment = $this->createShipment($auth->access_token);
    	// $val_shipment = $this->validateShipment($auth->access_token);
    	// dd($auth,$val_address,$rate,$shipment);
    	// dd($auth,$shipment);

    	$token = $this->getAuthToken();
    	$order = $this->createOrder($token->token,[]);
    	$response = $this->generateAWBForShipment($token->token,["shipment_id"=>$order->shipment_id,"courier_id"=> "","status"=> ""]); // need kyc
    	$cancel_order= $this->cancelOrder($token->token,[$order->order_id]); // not working
    	$add_address = $this->addAddress($token->token,[]);
    	$tracking_shipping = $this->trackingThroughShipmentId($token->token,$order->shipment_id);
    	dd($token->token,$order,$response,$order->order_id,$cancel_order,$add_address,$tracking_shipping);
    }

    public function shiprocketWebhook(Request $request)
    {

    }
}
