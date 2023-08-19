<?php

namespace App\Console;

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
        Commands\MargApiOrderUpdate::class
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
        $schedule->command('send_campaign:notification')->everyMinute();
        $schedule->command('service_area:active_for_vendor_slot')->everyMinute();
        $schedule->command('copy:catalog')->everyTenMinutes();
        $schedule->command('pickup:notify')->everyMinute();
        $schedule->command('auto:sycn_product_from_marg_api')->hourly();
        $schedule->command('marg:marg_order_update')->everyTenMinutes();
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
