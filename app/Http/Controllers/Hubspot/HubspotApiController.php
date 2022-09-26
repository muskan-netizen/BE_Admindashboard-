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
use App\Models\{Country, ClientPreferenceAdditional, User,CarImages ,Product, UserAddress};

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
       // try {
            $hubspot = Factory::createWithOAuth2Token('pat-na1-58be0ab0-c539-4c0d-a3b7-1208f112e510');
            $endpoint = 'https://api.hubapi.com/contacts/v1/contact/batch';
            $client = $hubspot->getClient();
            $lastUpdate='1998-01-14' ;
            $preference =  ClientPreferenceAdditional::first();
            if($preference){
               $lastUpdate = $preference->hubspot_last_update;
            }
            $users = User::whereDate('created_at','>',$lastUpdate)->get()->take(2);
            ///$arr = [];
            $data = [];
            if(sizeof($users)){
                foreach($users as $key => $user){
                    $data[] =  [  "email"=> $user->email, 
                                        'properties'=>  
                                            [
                                                [ 'property' => 'firstname', 'value'  => $user->name ],
                                                [ 'property' => 'lastname', 'value'  => $user->name ],
                                                [ 'property' => 'phone', 'value'  =>$user->dial_code.$user->phone_number ],
                                                // [ 'property' => 'address', 'value'  => $user->email ],
                                                // [ 'property' => 'city', 'value'  => $user->email ],
                                                // [ 'property' => 'state', 'value'  => $user->email ],
                                                // [ 'property' => 'zip', 'value'  => $user->email ],
                                            ]
                                ];
                                
                }
               // pr($data);
                if(count($data)>0){
                    $rp = $client->request(
                        'post',
                        $endpoint,
                        ['json' => $data]
                    );
                    $now = Carbon::now();
                    $preference->hubspot_last_update = $now;
                    $preference->save();
                    return response()->json(['status' => true, 'notiFY' => [] , 'message' => __('Sucessfully !!!')]);
                }
                return response()->json(['status' => false, 'notiFY' => [] , 'message' => __('No Data found !!!')]);
            }
            return response()->json(['status' => false, 'notiFY' => [] , 'message' => __('No Data found !!!')]);
        // } catch (\Throwable $th) {
        //     pr($th);
        //     return response()->json(['status' => false, 'notiFY' => [] , 'message' => __('Something went wrong !!!')]);
        // }
     
    }
}