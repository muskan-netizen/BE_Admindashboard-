<?php

namespace App\Console\Commands;
use DB;
use Config;
use Exception;
use App\Models\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class ClientMigrateDataBase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for update the clients database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
{
    // 🔹 STEP 1: Disconnect any existing DB connections
    DB::disconnect();

    try {
        // 🔹 STEP 2: Connect freshly using .env credentials (Parent DB)
        $connectionName = env('DB_CONNECTION', 'mysql');
        $databaseName   = env('DB_DATABASE', 'royoorders');

        $parentConnection = [
            'driver'         => $connectionName,
            'host'           => env('DB_HOST'),
            'port'           => env('DB_PORT'),
            'database'       => $databaseName,
            'username'       => env('DB_USERNAME'),
            'password'       => env('DB_PASSWORD'),
            'charset'        => 'utf8mb4',
            'collation'      => 'utf8mb4_unicode_ci',
            'prefix'         => '',
            'prefix_indexes' => true,
            'strict'         => false,
            'engine'         => null,
        ];

        Config::set("database.connections.$connectionName", $parentConnection);
        DB::purge($connectionName);
        DB::reconnect($connectionName);

        $currentDatabase = DB::connection()->getDatabaseName();
        $this->info("✅ Parent DB connection successful. Connected to: {$currentDatabase}");
    } catch (\Exception $e) {
        $this->error("❌ Parent DB connection failed. Please check your .env settings.");
        return;
    }

    // 🔹 STEP 3: Fetch clients from parent DB
    $clients = (new Client)
        ->setConnection($connectionName)
        ->where('status', 1)
        ->get();

    $this->info("Clients fetched: " . count($clients));

    // 🔹 STEP 4: Loop through each client and migrate their DB
    foreach ($clients as $client) {
        $database_name = 'royo_' . $client->database_name;
      

        $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?";
        $db = DB::connection($connectionName)->select($query, [$database_name]);

        if ($db) {
            $childConnection = [
                'driver'         => env('DB_CONNECTION', 'mysql'),
                'host'           => env('DB_HOST'),
                'port'           => env('DB_PORT'),
                'database'       => $database_name,
                'username'       => $client->database_username ?? env('DB_USERNAME'),
                'password'       => $client->database_password ?? env('DB_PASSWORD'),
                'charset'        => 'utf8mb4',
                'collation'      => 'utf8mb4_unicode_ci',
                'prefix'         => '',
                'prefix_indexes' => true,
                'strict'         => false,
                'engine'         => null,
            ];

            Config::set("database.connections.$database_name", $childConnection);
            DB::purge($database_name);
            DB::reconnect($database_name);

            Artisan::call('migrate', ['--database' => $database_name]);

            DB::disconnect($database_name);
            $this->info("✅ Migration completed: {$database_name}");
        } else {
            DB::disconnect($database_name);
            $this->warn("⚠️ Database not found: {$database_name}");
        }
    }
}

    
}
