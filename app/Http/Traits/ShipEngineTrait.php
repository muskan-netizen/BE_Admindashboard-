<?php
namespace App\Http\Traits;

use App\Models\Cart;
use App\Models\ShippingOption;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Http;

trait ShipEngineTrait
{
    public $ship_engine_api_key;
    public $url;
    public $header;
    public $service_code;
    public $status;
    
    public function __construct()
    {
        $creds = ShippingOption::select('credentials', 'test_mode','status')->where('code', 'shipengine')->where('status', 1)->first();

        if(isset($creds) && !empty($creds)){
            $creds_arr = json_decode($creds->credentials);

            $this->ship_engine_api_key = $creds_arr->api_key;
            $this->service_code = $creds_arr->service_code;
            $this->url = "https://api.shipengine.com/v1";
            $this->header = [
                'API-Key' => $this->ship_engine_api_key,
                'Content-Type' => 'application/json',
            ];
            $this->status = $creds->status ?? 0;
        } 
    }

    public function shipEngineAddressValidate($address)
    {
        // \Log::info(['address' => $address]);
        $addr = explode(',',$address['address']);
        $response =  Http::withHeaders($this->header)->post($this->url.'/addresses/validate',[
            [
                "address_line1"  =>  $addr[0],
                "city_locality"  =>  $address['city'],
                "state_province" =>  $address['state'],
                "postal_code"    =>  $address['pincode'],
                "country_code"   =>  $address['country_code']
            ]
        ])->json();
        $response['message'] = "Address is not Validate for Shipping";
        // \Log::info($response);
        return $response;
    }

    public function shipEngineRateEstimate()
    {
        // \Log::info(['address' => $address]);
        $response =  Http::withHeaders($this->header)->post($this->url.'/rates/estimate',[
            "carrier_ids" => [
                "se-5298717"
            ],
            "from_country_code" => "US",
            "from_postal_code" => "78756",
            "to_country_code" => "US",
            "to_postal_code" => "95128",
            "to_city_locality" => "San Jose",
            "to_state_province" => "CA",
            "weight" => [
                "value" => 1.0,
                "unit" => "ounce"
            ],
            "confirmation" => "none",
            "address_residential_indicator" => "no"
            
        ])->json();
        // \Log::info($response);
        return $response;
    }

    public function getShippingDetail($id)
    {
        return  Http::withHeaders($this->header)->get($this->url.'/shipments/'.$id);
    }

    public function getLabelFee($data)
    {
        $vendor = $data['vendor'];

        $total_weight = 0;

        foreach ($data->vendorProducts as $key => $vendor_product) {
            $total_weight += $vendor_product['product']['weight'] ?? 0;
        }
                
        $user_address = UserAddress::where('user_id', auth()->user()->id)->where('status',1)->orderBy('is_primary','Desc')->first();

        $response =  Http::withHeaders($this->header)->post($this->url.'/labels',[
            "shipment" => [
                "service_code" => $this->service_code,
                "ship_to" => [
                    "name" => auth()->user()->name,
                    "address_line1" => explode(',',$user_address['address'])[0],
                    "city_locality" => $user_address['city'],
                    "state_province" => $user_address['state_code'],
                    "postal_code" => $user_address['pincode'],
                    "country_code" => $user_address['country_code'],
                    "address_residential_indicator" => "yes",
                ],
                "ship_from" => [
                    "name" => "John Doe",
                    "company_name" =>  $vendor['name'],
                    "phone" =>  $vendor['phone_no'],
                    "address_line1" =>  explode(',',$vendor['address'])[0],
                    "city_locality" =>  $vendor['city'],
                    "state_province" => $vendor['state_code'],
                    "postal_code" =>  $vendor['pincode'],
                    "country_code" =>  $vendor['country_code'],
                    "address_residential_indicator" => "yes",
                ],
                "packages" => [
                    [
                        "weight" => [
                            "value" => $total_weight,
                            "unit" => "pound"
                        ]
                    ]
                ]
            ]
        ]);
        // \Log::info(['response' => $response]);
        if (isset($response['errors'])) {
            return ['status' => 208,'message' => $response['errors'][0]['message']];
        }

        return $response['shipment_cost']['amount'];
    }
}
