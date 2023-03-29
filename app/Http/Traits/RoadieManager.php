<?php
namespace App\Http\Traits;

use Illuminate\Support\Facades\Http;

trait RoadieManager
{
    public function testfun1()
    {

        // $postData = [
        //     "items" => [
        //         [
        //             "length" => 1.0,
        //             "width" => 1.0,
        //             "height" => 1.0,
        //             "weight" => 1.0,
        //             "quantity" => 1,
        //             "value" => 20
        //         ]
        //     ],
        //     "pickup_location" => [
        //         "address" => [
        //             "street1" => "123 Main Street",
        //             "city" => "Atlanta",
        //             "state" => "GA",
        //             "zip" => "30305"
        //         ]
        //     ],
        //     "delivery_location" => [
        //         "address" => [
        //             "street1" => "456 Central Ave.",
        //             "city" => "Atlanta",
        //             "state" => "GA",
        //             "zip" => "30308"
        //         ]
        //     ],
        //     "pickup_after" => "2023-03-28T13:00:00Z",
        //     "deliver_between" => [
        //         "start" => "2023-03-29T21:00:00Z",
        //         "end" => "2023-03-30T23:00:00Z"
        //     ]
        // ];


        // $response = Http::withHeaders([
        //     'Content-Type' => 'application/json',
        //     'Authorization' => 'Bearer e12005b2acd50631664dc802675883c8bfbddd2e'
        // ])->post('https://connect-sandbox.roadie.com/v1/estimates', $postData);

        // $statusCode = $response->getStatusCode();
        // pr(json_decode($response)->price);
        // if ($statusCode == 200) {
           
        // } else {

        // }

        $postData = [
            "reference_id" => "sdfsdsfsdfds123",
            // "idempotency_key" => "d6f9d5bb-1ba1-48d9-9125-6a61490a5ca5",
            // "alternate_id_1" => "111",
            // "alternate_id_2" => "222",
            // "description" => "General shipment description.",
            "items" => [
                [
                    "description" => "Item description",
                    "reference_id" => null,
                    "length" => 1.0,
                    "width" => 1.0,
                    "height" => 1.0,
                    "weight" => 1.0,
                    "value" => 20.00,
                    "quantity" => 1
                ],
                [
                    "description" => "Item description 2",
                    "reference_id" => null,
                    "length" => 1.0,
                    "width" => 1.0,
                    "height" => 1.0,
                    "weight" => 1.0,
                    "value" => 20.00,
                    "quantity" => 1
                ]
            ],
            "pickup_location" => [
                "address" => [
                    "name" => "Origin Location",
                    "store_number" => "12324",
                    "street1" => "123 Main Street",
                    "street2" => null,
                    "city" => "Atlanta",
                    "state" => "GA",
                    "zip" => "30305",
                    "latitude" => 33.74903,
                    "longitude" => -85.38803
                ],
                "contact" => [
                    "name" => "Origin Contact",
                    "phone" => "4049999999"
                ],
                "notes" => null
            ],
            "delivery_location" => [
                "address" => [
                    "name" => "Destination Location",
                    "store_number" => null,
                    "street1" => "456 Central Ave.",
                    "street2" => null,
                    "city" => "Atlanta",
                    "state" => "GA",
                    "zip" => "30308",
                    "latitude" => 33.04131,
                    "longitude" => -84.18303
                ],
                "contact" => [
                    "name" => "Destination Contact",
                    "phone" => "4049999999"
                ],
                "notes" => null
            ],
            "pickup_after" => "2023-03-29T13:00:00Z",
            "deliver_between" => [
                "start" => "2023-03-29T21:00:00Z",
                "end" => "2023-03-31T23:00:00Z"
            ],
            "options" => [
                "signature_required" => true,
                "notifications_enabled" => false,
                "over_21_required" => false,
                "extra_compensation" => 5.0,
                "trailer_required" => false,
                "decline_insurance" => true
            ]
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer e12005b2acd50631664dc802675883c8bfbddd2e'
        ])->post('https://connect-sandbox.roadie.com/v1/shipments', $postData);

        $statusCode = $response->getStatusCode();
        pr(json_decode($response));
        // if ($statusCode == 200) {
           
        // } else {

        // }
    }
}