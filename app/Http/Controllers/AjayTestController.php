<?php

namespace App\Http\Controllers;

use App\Jobs\CampaignSendNotification;
use App\Jobs\CampaignSendNotificationJob;
use App\Jobs\SendNotificationJob;
use App\Models\CampaignRoster;
use App\Models\Client;
use App\Models\ClientPreference;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Log;
class AjayTestController extends Controller
{
    public function testPost(Request $request){
        $clients = Client::select('database_name', 'sub_domain')->get();
        $intervalTime = date('Y-m-d h:i:00');
        foreach ($clients as $client) {
            $database_name = 'royo_' . $client->database_name;
            //// Log::info("checking cart start: {$database_name}!");
            $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
            $db = DB::select($query, [$database_name]);
            if ($db) {
                $default = [
                    'prefix' => '',
                    'engine' => null,
                    'strict' => false,
                    'charset' => 'utf8mb4',
                    'host' => env('DB_HOST'),
                    'port' => env('DB_PORT'),
                    'prefix_indexes' => true,
                    'database' => $database_name,
                    'username' => env('DB_USERNAME'),
                    'password' => env('DB_PASSWORD'),
                    'collation' => 'utf8mb4_unicode_ci',
                    'driver' => env('DB_CONNECTION', 'mysql'),
                ];
                Config::set("database.connections.$database_name", $default);
                DB::setDefaultConnection($database_name);
                $client_preferences = ClientPreference::first();
                $from = $client_preferences->fcm_server_key ?? "";
                $headers = [
                    'Authorization: key=' . $from,
                    'Content-Type: application/json',
                ];
                $chunk = config('app.campaign_chunk') ?? 100;
                $chunk_notifications = CampaignRoster::where('notification_time', '<=', $intervalTime)->where('status',0)->with('campaign','user')->get()->chunk($chunk);
                if($chunk_notifications)
                {
                    // CampaignRoster::where('id',6290)->delete();
                    foreach($chunk_notifications as $notifications)

                    CampaignSendNotificationJob::dispatch($notifications,$client_preferences,$headers);

                    }
                }

                DB::disconnect($database_name);
                //// Log::info("checking cart end: {$database_name}!");
            }
        }
}
