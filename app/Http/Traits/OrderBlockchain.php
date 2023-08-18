<?php
namespace App\Http\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\{Client, ClientPreferenceAdditional, PaymentOption, UserAddress, Vendor, VerificationOption};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

trait OrderBlockchain{ 


    public function saveBlockchainOrderDetail($orderData)
    {
        
        $api_domain = ClientPreferenceAdditional::where('key_name','blockchain_api_domain')->first();
        $from_id = ClientPreferenceAdditional::where('key_name','blockchain_address_id')->first();
        $client = Client::first();
        $data = [
            "status" => $orderData->ordervendor->status ?? '0',
            "orderID" => $orderData->id,
            "order_detail" => (array) $orderData,
            "address_short_code" => $client->code,
            "from_address" => $from_id->key_value ?? '',
            'user_id' => $orderData->user_id
        ];
        
        $headers = [
            'Content-Type' => 'application/json',
        ];
        if(isset($api_domain))
        {
            $response = Http::withHeaders($headers)->post($api_domain->key_value.'/createOrder', $data);
        
            $responseData = $response->json();
        }

        
        return response()->json([
            'message' => 'Order created successfully',
            'data' => $data ?? '',
            'api_response' => $responseData ?? '',
        ], 200);
    }
    public function updateBlockchainOrderDetail($orderData)
    {
        
        $api_domain = ClientPreferenceAdditional::where('key_name','blockchain_api_domain')->first();
        $from_id = ClientPreferenceAdditional::where('key_name','blockchain_address_id')->first();
        $client = Client::first();
        $data = [
            "newStatus" => $orderData->ordervendor->status ?? '0',
            "orderID" => $orderData->id,
            "address_short_code" => $client->code,
            "from_address" => $from_id->key_value ?? '',
            'user_id' => $orderData->user_id
        ];
        
        $headers = [
            'Content-Type' => 'application/json',
        ];
        if(isset($api_domain))
        {
            $response = Http::withHeaders($headers)->post($api_domain->key_value.'/updateOrderStatus', $data);
        
            $responseData = $response->json();
        }

        
        return response()->json([
            'message' => 'Order Updated successfully',
            'data' => $data ?? '',
            'api_response' => $responseData ?? '',
        ], 200);
    }
    public function moveOrderToWarehouse($orderData)
    {
        
        $api_domain = ClientPreferenceAdditional::where('key_name','blockchain_api_domain')->first();
        $from_id = ClientPreferenceAdditional::where('key_name','blockchain_address_id')->first();
        $client = Client::first();
        $data = [
            "lat" => $orderData->ordervendor->status ?? '0',
            "orderID" => $orderData->id,
            "address_short_code" => $client->code,
            "movement" => $from_id->key_value ?? '',
            'user_id' => $orderData->user_id
        ];
        
        $headers = [
            'Content-Type' => 'application/json',
        ];
        if(isset($api_domain))
        {
            $response = Http::withHeaders($headers)->post($api_domain->key_value.'/moveOrderToWarehouse', $data);
        
            $responseData = $response->json();
        }

        
        return response()->json([
            'message' => 'Order Updated successfully',
            'data' => $data ?? '',
            'api_response' => $responseData ?? '',
        ], 200);
    }
 

}