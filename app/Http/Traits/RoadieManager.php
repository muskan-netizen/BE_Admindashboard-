<?php
namespace App\Http\Traits;

use Illuminate\Support\Facades\Http;

trait RoadieManager
{
    public function testfun1()
    {

        $postData = [
            "items" => [
                [
                    "length" => 1.0,
                    "width" => 1.0,
                    "height" => 1.0,
                    "weight" => 1.0,
                    "quantity" => 1,
                    "value" => 20
                ]
            ],
            "pickup_location" => [
                "address" => [
                    "street1" => "123 Main Street",
                    "city" => "Atlanta",
                    "state" => "GA",
                    "zip" => "30305"
                ]
            ],
            "delivery_location" => [
                "address" => [
                    "street1" => "456 Central Ave.",
                    "city" => "Atlanta",
                    "state" => "GA",
                    "zip" => "30308"
                ]
            ],
            "pickup_after" => "2023-03-28T13:00:00Z",
            "deliver_between" => [
                "start" => "2023-03-29T21:00:00Z",
                "end" => "2023-03-30T23:00:00Z"
            ]
        ];


        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer e12005b2acd50631664dc802675883c8bfbddd2e'
        ])->post('https://connect-sandbox.roadie.com/v1/estimates', $postData);

        $statusCode = $response->getStatusCode();
        pr(json_decode($response)->price);
        if ($statusCode == 200) {
           
        } else {

        }
    }
}