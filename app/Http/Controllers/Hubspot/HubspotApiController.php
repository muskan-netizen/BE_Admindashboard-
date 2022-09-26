<?php 

namespace App\Http\Controllers\Hubspot;
use SevenShores\Hubspot\Http\Client;
use SevenShores\Hubspot\Resources\Contacts;
use SevenShores\Hubspot\Factory;

use Auth;
use Session;
use Carbon\Carbon;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Front\FrontController;
use App\Models\{Country,Client as cl, ClientPreferenceAdditional, User,CarImages ,Product, UserAddress};

class HubspotApiController extends FrontController{

/**
 * create batch records for hubspot api
 *
 * @param  mixed $r
 * @return void
 */
public function create(Request $r)
    {
        //pr(env('HUBSPOT_API_KEY'));
        try {
            $hub_key = @getAdditionalPreference(['hubspot_access_token','hubspot_last_update']);
            $hubspot = Factory::createWithOAuth2Token($hub_key['hubspot_access_token']);
            $endpoint = 'https://api.hubapi.com/contacts/v1/contact/batch';
            $client = $hubspot->getClient();
            $lastUpdate='1998-01-14' ;
            if(isset($hub_key['hubspot_last_update'])){
               $lastUpdate =  Carbon::parse($hub_key['hubspot_last_update'])->format('Y-m-d h:i:s');
            } else {
                $lastUpdate =  Carbon::parse($lastUpdate)->format('Y-m-d h:i:s');
            }
                
            $users = User::where('created_at','>',$lastUpdate)->get()->take(3);
            ///$arr = [];
            $data = [];
            if(sizeof($users)){
                foreach($users as $key => $user){
                    $data[] =  [  "email"=> $user->email, 
                                        'properties'=>  
                                            [
                                                [ 'property' => 'firstname', 'value'  => $user->name ],
                                                [ 'property' => 'lastname', 'value'  => $user->name ],
                                                [ 'property' => 'phone', 'value'  => $user->phone_number ],
                                                // [ 'property' => 'address', 'value'  => $user->email ],
                                                // [ 'property' => 'city', 'value'  => $user->email ],
                                                // [ 'property' => 'state', 'value'  => $user->email ],
                                                // [ 'property' => 'zip', 'value'  => $user->email ],
                                            ]
                                ];
                                
                }
                if(count($data)>0){
                    $rp = $client->request(
                        'post',
                        $endpoint,
                        ['json' => $data]
                    );
                    $now = Carbon::now()->format('Y-m-d h:i:s');
       
                    $client = cl::first();
                    ClientPreferenceAdditional::updateOrCreate(
                        ['key_name' => 'hubspot_last_update', 'client_code' => $client->code],
                        ['key_name' => 'hubspot_last_update', 'key_value' => $now,'client_code' => $client->code,'client_id'=> $client->id]);
                   
                    return response()->json(['status' => true, 'notiFY' => [] , 'message' => __('Sucessfully !!!')]);
                }
                return response()->json(['status' => false, 'notiFY' => [] , 'message' => __('No Data found !!!')]);
            }
            return response()->json(['status' => false, 'notiFY' => [] , 'message' => __('No Data found !!!')]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'notiFY' => [] , 'message' => __('Something went wrong !!!')]);
        }
     
    }
}