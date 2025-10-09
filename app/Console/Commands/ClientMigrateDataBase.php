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
        DB::disconnect();
        try {
            // ✅ Check if default DB connection works
            DB::purge();       // clears any cached connection configuration
            DB::reconnect(); 
            $currentDatabase = DB::connection()->getDatabaseName();

            // ✅ Log success with the actual connected DB name
            \Log::info("✅ Database connection successful. Connected to: {$currentDatabase}");
            $this->info("✅ Database connection successful. Connected to: {$currentDatabase}");
        } catch (\Exception $e) {
            \Log::error("❌ Database connection failed: " . $e->getMessage());
            $this->error("❌ Database connection failed. Please check your .env settings.");
            return; // stop further execution
        }
    
        // ✅ Fetch clients
        $clients = Client::where('status', 1)->get();
        \Log::info("Clients fetched: " . count($clients));
        \Log::info("Clients: " . $clients);
    
        foreach ($clients as $key => $client) {
            $database_name = 'royo_' . $client->database_name;
            $this->info("🚀 Migrating database: {$database_name}");
    
            $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?";
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
    
                // ✅ Set and migrate
                Config::set("database.connections.$database_name", $default);
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
