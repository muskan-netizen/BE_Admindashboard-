<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\ClientPreference;
use GuzzleHttp\Client as GCLIENT;
use Log;
trait ClientPreferenceManager{ 

  # get last mile teams
  public function getLastMileTeams(){
    try {
      $dispatch_domain = $this->checkIfLastMileOn();
      if ($dispatch_domain && $dispatch_domain != false) {
        $unique = Auth::user()->code;
        $client = new GCLIENT(['headers' => 
          [
            'personaltoken' => $dispatch_domain->delivery_service_key,
            'shortcode' => $dispatch_domain->delivery_service_key_code,
            'content-type' => 'application/json'
          ]
        ]);
        $url = $dispatch_domain->delivery_service_key_url;
        $res = $client->get($url.'/api/get-all-teams');
        $response = json_decode($res->getBody(), true);
        if($response && $response['message'] == 'success'){
            return $response['teams'];
        }
      }
    }
    catch(\Exception $e){
    }
  }
  # check if last mile delivery on
  public function checkIfLastMileOn(){
    $preference = ClientPreference::first();
    if($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
      return $preference;
    else
      return false;
  }
}
