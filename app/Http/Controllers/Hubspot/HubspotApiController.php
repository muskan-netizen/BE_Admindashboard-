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
use App\Models\{Country, UserWishlist, User,CarImages ,Product, UserAddress};

class HubspotApiController extends FrontController{

public function create(Request $r)
    {
        //pr(env('HUBSPOT_API_KEY'));
        $hubspot = Factory::createWithOAuth2Token('pat-na1-58be0ab0-c539-4c0d-a3b7-1208f112e510');
        // $hubspot = new Factory([
        //     'key'      => 'demo',
        //     'oauth2'   => 'false', // default
        //   ]);
          
          // Then you can call a resource 
          // When referencing endpoints, use camelCase
        //   $response = $hubspot->contacts()->all([
        //     'count'     => 10,
        //     'property'  => ['firstname', 'lastname'],
        //     'vidOffset' => 123456,
        // ]);
          
        //$contact = $hubspot->contacts()->getByEmail("bh@hubspot.com");

//         pr($hubspot->getClient());
// die;

        $arr = [
            
                [
                    'property' => 'firstname',
                    'value' => 'hubspot222'
                ],
                [
                    'property' => 'lastname',
                    'value' => 'momo'
                ],
                [
                    'property' => 'phone',
                    'value' => 'user'
                ],
                [
                    'property' => 'email',
                    'value' => 'apitesst@hubspot.com'
                ],
        
        ];
        $post_json = json_encode($arr);
        // $endpoint = 'https://api.hubapi.com/contacts/v1/contact?hapikey=pat-na1-72d44b58-5ab9-4d9b-89b4-38f05e771ea1';
        // $client = $hubspot->getClient();
        // $res = $client->request('POST', $endpoint, [
        //     'body' => $post_json
        // ]);
        $endpoint = 'https://api.hubapi.com/contacts/v1/contact';
        $client = $hubspot->getClient();
        $client->request(
            'post',
            $endpoint,
            ['json' => ['properties' => $arr]]
        );
        echo "Contact Created!";
        die;
    }
}