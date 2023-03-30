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

class RoadieController extends Controller
{
    use \App\Http\Traits\RoadieTrait;
    use \App\Http\Traits\ApiResponser;
    
    private $api_access_token;
    private $api_base_url;
    public $roadie_status;
    
    public function __construct(){
        $simp_creds = ShippingOption::select('credentials', 'test_mode','status')->where('code', 'roadie')->where('status', 1)->first();
        if($simp_creds && $simp_creds->credentials){
            $creds_arr = json_decode($simp_creds->credentials);
            $this->api_access_token = $creds_arr->api_access_token??'';
            $this->api_base_url = $creds_arr->api_base_url??'';
            $this->roadie_status = $simp_creds->status??'';
        }
    }

    public function getEstimate($vendorData,$address){  
        $postData = [
            "items" => [
                [
                    "length" => 1.0,
                    "width" => 1.0,
                    "height" => 1.0,
                    "weight" => 1.0,
                    "value" => 20.00,
                    "quantity" => 1
                ],
                [
                    "length" => 1.0,
                    "width" => 1.0,
                    "height" => 1.0,
                    "weight" => 1.0,
                    "value" => 20.00,
                    "quantity" => 1
                ]
            ],
            "pickup_location" => [
                "address" => [
                    "name" => $vendorData->vendor->address,
                    "street1" => $vendorData->vendor->address,
                    "street2" => null,
                    "city" => $vendorData->vendor->address,
                    "state" => $vendorData->vendor->address,
                    "zip" => $vendorData->vendor->pincode,
                    "latitude" => $vendorData->vendor->latitude,
                    "longitude" => $vendorData->vendor->longitude
                ]
            ],
            "delivery_location" => [
                "address" => [
                    "name" => $address->address,
                    "street1" => $address->street,
                    "street2" => null,
                    "city" => $address->city,
                    "state" => $address->state,
                    "zip" => $address->pincode,
                    "latitude" => $address->latitude,
                    "longitude" => $address->longitude
                ]
            ],
            "pickup_after" => date('Y-m-d H:i:s'),
            "deliver_between" => [
                "start" => date('Y-m-d H:i:s', strtotime('+1 days')),
                "end" => date('Y-m-d H:i:s', strtotime('+5 days'))
            ]
        ];
        // pr($postData);
        $quotation = $this->getQuotations($postData);
        return $quotation;
    }


    public function quotation(Request $request)
    {
    	$data = (object) array(
            "pick_lat"=> "3.115825684565",
            "pick_lng"=> "101.666775521484",
            "pick_address"=> "Malaysia",
            "vendor_name"=> "General Electric",
            "vendor_contact"=> "8965745236",
            "drop_lat"=> "3.229537972256",
            "drop_lng"=> "101.730552380616",
            "drop_address"=> "6PHJ+R6 Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia",
            "user_name"=> "Xavier Ross",
            "user_phone"=> "+41767250736",
            "remarks"=> "Delivery vendor message remarks"
        );
        $quotation = $this->getQuotations($data);
        return $quotation;
    }


    public function getDeliveryFeeLalamove($vendor_id)
    {
        try{    

                $customer = User::find(Auth::id());
                $cus_address = UserAddress::where('user_id', Auth::id())->orderBy('is_primary', 'desc')->first();
                if ($cus_address && $this->lalamove_status==1){

                    $vendor_details = Vendor::find($vendor_id);
                    $data = (object) array(
                        'pick_lat' => $vendor_details->latitude,
                        'pick_lng' => $vendor_details->longitude,
                        'pick_address' => $vendor_details->address,
                        'vendor_name' => $vendor_details->name,
                        // 'vendor_contact' => $vendor_details->phone_no,
                        'vendor_contact' => '3768865552',
                        'drop_lat' => $cus_address->latitude,
                        'drop_lng' => $cus_address->longitude,
                        'drop_address' => $cus_address->address,
                        'user_name' => $customer->name,
                        'user_phone' => $customer->phone_number,
                        'remarks' => 'Delivery vendor message remarks'
                    );
            
                    $quotation = $this->getQuotations($data);
                    $actualAmount=0;
                    if($quotation['code']!='409')
                    { 
                        $json = json_decode($quotation['response']);
                        $distance =  round($json->distance->value/1000);
                        if($this->base_price > 0)
                        {
                            $actualAmount = getBaseprice($distance);
                         }else{
                            $actualAmount = $json->totalFee;
                        }
                    }
                    //dd($actualAmount);
                    return $actualAmount;
                }
            
        }catch(\Exception $e)
        {
            return 0;
        }

    }    
}
