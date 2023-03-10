<?php

namespace App\Jobs;


use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Config;
class SyncToDispatcher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $order_panel_id;
    protected $client_preferences;
    protected $categories;
    protected $databaseName;
    public function __construct($order_panel_id, $client_preferences, $categories,$databaseName)
    {
        $this->categories = $categories;
        $this->client_preferences = $client_preferences;
        $this->order_panel_id = $order_panel_id;
        $this->databaseName = $databaseName;
        \Log::info('SyncToDispatcher job run');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // sleep(5);
      
        $url = $this->client_preferences['delivery_service_key_url'].'/api/sync-category-product';
        $postData = ['databaseName'=> $this->databaseName,'data' => $this->categories, 'order_panel_id' => $this->order_panel_id]; 
    
        $headers = [
            'shortcode' => $this->client_preferences['delivery_service_key_code']
        ];

        $response = Http::withHeaders($headers)->post($url, $postData);
        $statusCode = $response->getStatusCode();
        \Log::info(json_encode($response));
        if($statusCode == 200) {
            return true;
        }
        return true;
        
    }
   
}
