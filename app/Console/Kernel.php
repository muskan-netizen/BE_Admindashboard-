<?php

namespace App\Console;

use App\Models\Client;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CartReminder::class,
        Commands\AutoRejectOrders::class,
        // Commands\SetDummyDataForDemo::class,
        Commands\RejectOrderNotification::class,
        Commands\HubSpotSyncData::class,
        Commands\MargApiProductUpdateCron::class,
        Commands\MargApiOrderUpdate::class,
        Commands\RecurringBooking::class,
        Commands\MakeTrait::class
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cart:reminder')->hourly();
        $schedule->command('auto:reject_order')->everyMinute();
        $schedule->command('auto:reject_order_notifi')->everyMinute();
        // $schedule->command('set_default_dummy:data')->dailyAt('00:30');
        $schedule->command('auto:create_recurring_order_for_dispatcher')->dailyAt('00:30');
        $schedule->command('set_default_dummy:data')->dailyAt('00:30');
        $schedule->command('auto:create_recurring_order_for_dispatcher')->hourly();
        $schedule->command('send_campaign:notification')->everyMinute();
        $schedule->command('service_area:active_for_vendor_slot')->everyMinute();
        $schedule->command('copy:catalog')->everyTenMinutes();
        $schedule->command('pickup:notify')->everyMinute();

        $clients = Client::where('status', 1)->limit(1)->get();
        foreach ($clients as $key => $client) {
           $database_name  = 'royo_' . $client->database_name;

            $result = \DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$database_name]);
            if (empty($result)) {
                continue;
            }
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
            \Config::set("database.connections.$database_name", $default);
            \DB::setDefaultConnection($database_name);
            $marg_cron_schedular_time = @getAdditionalPreference(['marg_cron_schedular_time']);
            \DB::disconnect($database_name);
            $marg_cron_schedular_time = $marg_cron_schedular_time['marg_cron_schedular_time'];

            if ($marg_cron_schedular_time) {
                $schedule->command('auto:sycn_product_from_marg_api')->$marg_cron_schedular_time();
                $schedule->command('marg:marg_order_update')->$marg_cron_schedular_time();
            }else{
                $schedule->command('auto:sycn_product_from_marg_api')->everyFiveMinutes();
                $schedule->command('marg:marg_order_update')->everyFiveMinutes();
            }
        }

        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
