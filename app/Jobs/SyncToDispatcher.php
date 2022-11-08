<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\ClientPreference;
use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SyncToDispatcher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $request;
    public function __construct()
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sleep(5);
        $client_preferences = ClientPreference::first();
        \Log::info("dataaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa");
        if(@$client_preferences->delivery_service_key_url && !empty($client_preferences->delivery_service_key_url)){
            $categories = Category::with(['translation','products','products.variant','products.translation'])
            ->chunk(10, function($inspectors) use($client_preferences) {
                $this->sendDataToDispatcher($client_preferences , $inspectors);
            });
            
        }
        return true;
    }
    public function sendDataToDispatcher($client_preferences , $categories)
    {
        $url = $client_preferences->delivery_service_key_url.'/api/sync-category-product';
            $postData = ['data' => $categories, 'order_panel_id' => $this->request->order_panel_id ];
            $headers = [
                'shortcode' => $client_preferences->delivery_service_key_code
            ];

            $response = Http::withHeaders($headers)->post($url, $postData);
            $statusCode = $response->getStatusCode();
            \Log::info(json_encode($response));
            if($statusCode == 200) {
                return true;
            }
    }
}
