<?php

namespace App\Http\Controllers\Api\v1;

use Config, DB;
use App\Http\Controllers\Controller;
use App\Jobs\SyncToDispatcher;
use App\Models\Category;
use App\Models\ClientPreference;
use Illuminate\Http\Request;

class DispatcherController extends Controller
{
    
    public function categoryProductSyncDispatcher(Request $request)
    {
        if(@$request->order_panel_id){    
            
            $client_preferences = ClientPreference::first();
            $DatabaseName = DB::connection()->getDatabaseName();
            $preferences = [
                'delivery_service_key_url' => $client_preferences->delivery_service_key_url,
                'delivery_service_key_code' => $client_preferences->delivery_service_key_code
            ];
            $categories = [];
            if(@$client_preferences->delivery_service_key_url && !empty($client_preferences->delivery_service_key_url)){
                $categories = Category::with(['translation','products','products.variant','products.translation'])
                // ->chunk(10, function($inspectors) use($client_preferences) {
                //     $this->sendDataToDispatcher($client_preferences , $inspectors);
                // });
                ->get();
                $categories = collect($categories->toArray());
            }

        $this->connectDb();
        SyncToDispatcher::dispatch($request->order_panel_id, $preferences, $categories,$DatabaseName)->onQueue('sync_dispatcher');
        return response()->json([
            'status' => 200,
            'message' => 'Syncing is processing',
            // 'data' => $categories
        ]);
    }else{
        return response()->json([
            'status' => 400,
            'message' => 'order panel id is missing',
            // 'data' => $categories
        ]);
    }
       
    }

    public function sendDataToDispatcher($client_preferences , $categories)
    {
        $url = $client_preferences->delivery_service_key_url.'/api/sync-category-product';
            $postData = ['data' => $categories, 'order_panel_id' => $this->order_panel_id ];
            $headers = [
                'shortcode' => $client_preferences->delivery_service_key_code
            ];

            $response = Http::withHeaders($headers)->post($url, $postData);
            $statusCode = $response->getStatusCode();
            \Log::info(json_encode($response));
            if($statusCode == 200) {
                return true;
            }
            return true;
    }

    private function connectDb()
    {
        
       
        $schemaName =  'royoorders';
        $default = [
            'driver' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'database' => $schemaName,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
            'engine' => null
        ];
        config(["database.connections.mysql.database" => null]);
        Config::set("database.connections.$schemaName", $default);
        config(["database.connections.mysql.database" => $schemaName]);
        DB::setDefaultConnection($schemaName);
        return true;
    }
}
