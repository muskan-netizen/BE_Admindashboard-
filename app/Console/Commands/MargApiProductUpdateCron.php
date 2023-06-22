<?php

namespace App\Console\Commands;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Models\UserVendor;
use App\Models\UserDevice;
use App\Models\User;
use App\Models\NotificationTemplate;
use GuzzleHttp\Client;
use App\Models\Client as CP;
use App\Models\{ ClientPreference, OrderLongTermServiceSchedule,UserAddress,Product,Vendor,OrderVendor};
use Log;
use Carbon\Carbon;
use App\Models\Order;

class MargApiProductUpdateCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:sycn_product_from_marg_api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sycn product quantity from marg api';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /**
         * Recurring Booking Order send to dispather
         */

         try {
            $clients = CP::where('status', 1)->get();


            foreach ($clients as $key => $client) {
                //Connect client connection
                $database_name  = 'royo_' . $client->database_name;
                $header         = $client->database_name;

                $default = [
                    'driver' => env('DB_CONNECTION', 'mysql'),
                    'host' => env('DB_HOST'),
                    'port' => env('DB_PORT'),
                    'database' => $database_name,
                    'username' => $client->database_username,
                    'password' => $client->database_password,
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
                    'prefix_indexes' => true,
                    'strict' => false,
                    'engine' => null
                ];
                Config::set("database.connections.$database_name", $default);
                DB::setDefaultConnection($database_name);

                $preference = ClientPreference::first();


                $hub_key = @getAdditionalPreference(['marg_access_token','is_marg_enable','marg_decrypt_key', 'marg_company_code']);

                if(!isset($hub_key) && $hub_key['is_marg_enable'] != 1){
                    return false;
                }



             

                \DB::disconnect($database_name);
            }
        }catch (Exception $ex) {
            return $ex->getMessage();
        }
        return 0;


    }
}
