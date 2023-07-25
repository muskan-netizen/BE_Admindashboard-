<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Config;
use Illuminate\Support\Facades\DB;
use App\Models\UserVendor;
use App\Models\UserDevice;
use App\Models\User;
use App\Models\NotificationTemplate;
use GuzzleHttp\Client;
use App\Models\Client as CP;
use App\Models\{ClientPreference, ClientPreferenceAdditional, OrderLongTermServiceSchedule, UserAddress, Product, Vendor, OrderVendor};
use Log;
use Carbon\Carbon;
use App\Models\Order;
use App\Http\Traits\MargTrait;
use App\Libraries\DecryptLogic;

class MargApiOrderUpdate extends Command
{
    use MargTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marg:marg_order_update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to update inventory status on marg';

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
        \Log::info('in marg cron job every minute');
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



                $orders  = Order::where([
                    'marg_status' => null,
                    'payment_status' => 1
                ])->where('marg_max_attempt', '<', 3)
                    ->get();

                if (count($orders) > 0) {

                    foreach ($orders as $order) {
                        $this->makeInsertOrderMargApi($order);
                    }
                }
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
        return 0;
    }
}
