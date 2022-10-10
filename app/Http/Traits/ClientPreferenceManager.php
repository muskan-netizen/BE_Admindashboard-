<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\{ClientPreference,ClientPreferenceAdditional,Client};
use GuzzleHttp\Client as GCLIENT;
use Log;
trait ClientPreferenceManager{ 

  public $client_preference_fillable_key = ['is_phone_signup', 'token_currency', 'is_token_currency_enable', 'hubspot_access_token', 'is_hubspot_enable', 'gtag_id', 'fpixel_id','is_long_term_service'];
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
    
  /**
   * updatePreferenceAdditional
   *
   * @param  mixed $$request
   * @return void
   * harbans :)
   * 
   */
  public function updatePreferenceAdditional($request=[]){
    $validated_keys = $request->only($this->client_preference_fillable_key);
    $client = Client::first();
    foreach($validated_keys as $key => $value){ 
        ClientPreferenceAdditional::updateOrCreate(
            ['key_name' => $key, 'client_code' => $client->code],
            ['key_name' => $key, 'key_value' => $value,'client_code' => $client->code,'client_id'=> $client->id]);
    } 
    return 1;
  }
}
