<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Config, Exception, Log;
use Illuminate\Support\Facades\Artisan;
use Mail;
use DB;
use App\Http\Traits\OnBoardingProcessManager;

class CreateDummyDbProcessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $failOnTimeout = true;

    public $timeout = 120000;


    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /*
          status meaning
          0 -> Pending
          1 -> Onboarding Complete
          2 -> Database and Migration start/Complete
          3 -> After Database and Migration data Insert with or without AI start/Complete
        */
        set_time_limit(800);//300 seconds = 5 minutes
        \Log::info('create new database for OnBoardingProcessJob');    
        $sqlEmport = $this->createNewDatabaseMigration();
        return false;
       
    }    

    public function createNewDatabaseMigration()
    {
        $dbname = 'royo_'.OnBoardingProcessManager::randomStringName(8);
        $database = OnBoardingProcessManager::createDbName($dbname);
    
        $schemaName = 'royo_' . $database ?: config("database.connections.mysql.database");
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
            'engine' => null,
            'options'   => [
                \PDO::ATTR_EMULATE_PREPARES => true
            ]
        ];

        $query = "CREATE DATABASE $schemaName;";     
        \Log::info("database created: {$database}!");
        DB::statement($query);      

        Config::set("database.connections.$schemaName", $default);
        config(["database.connections.mysql.database" => $schemaName]);
        \Log::info('migrate start');
        Artisan::call('migrate', ['--database' => $schemaName, '--force' => true]);

        \Log::info("migrate done");

        \Log::info("DatabaseSeeder start");
        try{
            Artisan::call('db:seed', ['--class' => 'DatabaseSeeder', '--database' => $schemaName, '--force' => true]);
        } catch (\PDOException $e) {
            \Log::info('getting eerror in DatabaseSeeder');
            \Log::info($e->getMessage());
            //DB::connection($schemaName)->rollBack();
            return 0;
        }
        \Log::info("DatabaseSeeder done");

        DB::disconnect($schemaName);
    }    

}
