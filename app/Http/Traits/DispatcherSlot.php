<?php
namespace App\Http\Traits;

use DB;
use HttpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\{Order,ProductVariant,OrderVendor,VendorOrderCancelReturnPayment,UserDevice,ClientPreference};
use Auth;
use GuzzleHttp\Client as GClient;
trait DispatcherSlot{

    public function getSlotFeeDispatcher($data)
    {
      
        try {
                $postdata =  [ 
                                "latitude"  => $data['latitude'], 
                                "longitude" => $data['longitude'], 
                                "tags"      => $data['tags'], 
                                "schedule_date" => $data['schedule_date'] ,
                                "service_time" => "30"
                            ];
                
                //pr($postdata);
                $client = new GClient([
                    'headers' => [
                        'personaltoken' => $data['service_key'],
                        'shortcode'     => $data['service_key_code'],
                        'content-type'  => 'application/json'
                    ]
                ]);
              
                $url = $data['service_key_url'];
                $res = $client->post(
                    $url . '/api/agent/check_slot',
                    ['form_params' => ($postdata)]
                );
                //pr($res->getBody());
                $response = json_decode($res->getBody(), true);
                
                pr($response);
                if ($response && $response['message'] == 'success') {
                    $response_array[] = array('delivery_fee' => $response['total'], 'total_duration' => $response['total_duration']);
                    return $response;
                }
               
        } catch (\Exception $e) {
            pr($e);
           
        }
    }
    
}
