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

public function create(Request $r)
    {
        //pr(env('HUBSPOT_API_KEY'));
        $hubspot = Factory::createWithOAuth2Token('pat-na1-58be0ab0-c539-4c0d-a3b7-1208f112e510');

        $lastUpdate='1998-01-14' ; // harbans bday date :)
        $preference =  ClientPreferenceAdditional::first();
        if($preference){
        $lastUpdate = $preference->hubspot_last_update;

        }
        $users = User::whereDate('created_at','>',$lastUpdate)->get();
        $arr = [];
        foreach($users as $user){
            $Userdata = [
                'property' =>  $user->name,
                'value' => $user->id
            ];
            $arr[]=$Userdata;
        }

        // pr($arr);
        // $arr = [
            
        //         [
        //             'property' => 'firstname',
        //             'value' => 'hubspot222'
        //         ],
        //         [
        //             'property' => 'lastname',
        //             'value' => 'momo'
        //         ],
        //         [
        //             'property' => 'phone',
        //             'value' => 'user'
        //         ],
        //         [
        //             'property' => 'email',
        //             'value' => 'apitesst@hubspot.com'
        //         ],
        
        // ];
        $post_json = json_encode($arr);
        $endpoint = 'https://api.hubapi.com/contacts/v1/contact';
        $client = $hubspot->getClient();
        $client->request(
            'post',
            $endpoint,
            ['json' => ['properties' => $arr]]
        );
        $now = Carbon::now();
        $preference->hubspot_last_update = $now;
        $preference->save();
        // echo "Contact Created!";
        // die;
    }
}