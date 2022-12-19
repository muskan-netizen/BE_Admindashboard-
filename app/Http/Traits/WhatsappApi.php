<?php
namespace App\Http\Traits;
use Log;
use App\Models\{Order};
use Carbon\Carbon;

trait WhatsappApi{
    /**
     * Create Order on https://logisy.tech/
     */
    public function createOrder($order_id) {
        try{
            $order = Order::where('id', $order_id)->first();
            if( !empty($order->payable_amount) ) {
    
                // if order payable amount is not empty
                $order_address = optional($order)->address ?? '';
                $address = [
                    "id"            => $order_address->id ?? '',
                    "zip"           => $order_address->pincode ?? '100000',
                    "city"          => $order_address->city ?? 'City',
                    "phone"         => optional($order->user)->phone_number ?? '1234567890',
                    "country"       => $order_address->country ?? 'country',
                    "default"       => true,
                    "address1"      => $order_address->address ?? 'address',
                    "address2"      => $order_address->address ?? 'address',
                    "province"      => $order_address->city ?? 'City',
                    "country_code"  => $order_address->country_code ?? 'Country Code',
                    "country_name"  => $order_address->country ?? 'Country',
                    "province_code" => 'PRV',
                    "latitude"      => $order_address->latitude ?? '0.00',
                    "longitude"     => $order_address->longitude ?? '0.00'
                ];
    
                // Order Product
                $order_products     = [];
                if( !empty($order->orderVendorProduct) ) {
                    foreach ($order->orderVendorProduct as $key => $value) {
                        
                        $order_products[] = [
                            "id"                => $value->product_id ?? '',
                            "sku"               => optional($value->product)->sku ?? 'sku',
                            "name"              => optional($value->product)->title ?? 'Product Name/Title',
                            "title"             => optional($value->product)->title ?? 'Product Name/Title',
                            "grams"             => 0,
                            "total_tax"         => $value->taxable_amount ?? '0.00',
                            "total_discounts"   => "0.00",
                            "price_without_tax" => "0.00",
                            "total_price"       => "0.00",
                            "quantity"          => $value->quantity ?? '0',
                            "product_id"        => $value->product_id ?? '',
                            "variant_id"        => $value->product_id ?? ''
                        ];
                        
                    }
                }
                
                $api_data = [
                    "id"                => $order->order_number,
                    "note"              => $order->comment_for_vendor ?? '',
                    "tags"              => "some_tag_1,some_tag_2",
                    "first_name"        => optional($order->user)->name ?? 'First Name',
                    "last_name"         => optional($order->user)->name ?? 'Last Name',
                    "email"             => optional($order->user)->email ?? '',
                    "phone"             => optional($order->user)->phone_number ?? '',
                    "currency"          => "INR",
                    "shipping_cost"     => "0.00",
                    "total_tax"         => $order->taxable_amount ?? "0.00",
                    "total_discounts"   => $order->total_discount ?? "0.00",
                    "price_without_tax" => $order->total_amount ?? "0.00",
                    "total_price"       => $order->payable_amount ?? "0.00",
                    "created_at"        => Carbon::parse($order->created_at)->toDateTimeString(),
                    "line_items"        => $order_products,
                    "source_name"       => "web",
                    "order_number"      => $order->order_number,
                    "billing_address"   => $address,
                    "financial_status"  => "pending",
                    "order_status_url"  => "https://yourstore.com/orders/2711891284034/",
                    "shipping_address"  => $address,
                    "payment_gateway_names" => [
                        "cash_on_delivery",
                        "cashfree"
                    ],
                    
                ];
                $url        = 'https://logisy.tech/api/stores/order/create/';
                $api_data   = json_encode($api_data);
                $this->hitCurl($url, $api_data);

            }
        }
        catch(\Exception $e) {
            \Log::info('catch');
            \Log::info($e->getMessage());
        }
    }

    /**
     * Hit custom event 
     */
     public function customEvents() {
        $api_data = [
            "to_number" => "917355555968",
            "customer_name" => "Nitish",
            "template_name" => "test_template",

            "headers" => [
                "type" => "image/url/document/text",
                "link" => "https://cdn.shopify.com/s/files/1/0042/4384/9314/files/IMG-20210411-WA0006_2.jpg",
                "text" => "1234" 
            ],

            "body" => [
                "parameters" => [
                    "nitish",
                    "abcd",
                    "efgh"
                ]
            ],
            "buttons" => [
                [
                    "index" => "0",
                    "type" => "https://logisy.tech",
                    "value" => "https://logisy.tech"
                ]
            ],
        ];

        $this->hitCurl('https://logisy.tech/api/custom_events/whatsapp/', json_encode($api_data));
        
     }

     /**
      * Common Curl function
      */
     public function hitCurl($url, $data) {

        set_time_limit(0);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT,500); // 500 seconds
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $headers = array();
        $headers[] = 'X-Api-Key: fCnPbahHymjjKsqJgZU6qGoXCY9nTj5q';
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            Log::info(curl_error($ch));
        }
        curl_close($ch);
     }
}