<?php 

namespace App\Http\Controllers\Hubspot;
use SevenShores\Hubspot\Http\Client;
use SevenShores\Hubspot\Endpoints\Contacts;
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
        $hubspot = Factory::createWithOAuth2Token('pat-na1-72d44b58-5ab9-4d9b-89b4-38f05e771ea1');
        // $hubspot = new Factory([
        //     'key'      => 'demo',
        //     'oauth2'   => 'false', // default
        //   ]);
          
          // Then you can call a resource 
          // When referencing endpoints, use camelCase
          
        $contact = $hubspot->contacts()->getByEmail("bh@hubspot.com");

        pr($contact->toArray());
die;

        $arr = [
            'properties' => [
                [
                    'property' => 'firstname',
                    'value' => $r['firstname']
                ],
                [
                    'property' => 'lastname',
                    'value' => $r['lastname']
                ],
                [
                    'property' => 'phone',
                    'value' => $r['phone']
                ],
                [
                    'property' => 'email',
                    'value' => $r['email']
                ],
            ]
        ];
        $post_json = json_encode($arr);
        $endpoint = 'https://api.hubapi.com/contacts/v1/contact?hapikey=pat-na1-72d44b58-5ab9-4d9b-89b4-38f05e771ea1';
        $client = new Client();
        $res = $client->request('POST', $endpoint, [
            'body' => $post_json
        ]);
        echo "Contact Created!";
        die;
    }
}