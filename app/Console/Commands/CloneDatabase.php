<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Config;
use Illuminate\Support\Facades\Schema;

class CloneDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clone:db {old_db}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        try{

            $old_db = $this->argument('old_db'); 
            $database_name = 'royo_'.$this->argument('old_db'); 
            $new_db = 'new_ace'; 

            

            $result = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$database_name]);
            if (!empty($result)) {
                DB::statement('DROP DATABASE IF EXISTS royo_'.$old_db);
                // Create a new database
                DB::statement('CREATE DATABASE royo_'.$old_db);
                $this->info("Db '$old_db' has been dropped.");
            }else{
                $this->info("Db '$old_db' in--- else --.");
                // Create a new database
                DB::statement('CREATE DATABASE royo_'.$old_db);
                $this->info("Db '$old_db' has been created.");
            }
            
     

        $database_name = 'royo_' . $old_db;
            $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
            $db = DB::select($query, [$database_name]);
            if ($db) {
                $schemaName = $database_name;
                $database_host = '127.0.0.1';
                $database_port = '3306';
                $database_username = 'root';
                $database_password = '';

                $default = [
                'driver' => 'mysql',
                'host' => $database_host,
                'port' => $database_port,
                'database' => $schemaName,
                'username' => $database_username,
                'password' => $database_password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => false,
                'engine' => null
                ];
           
                Config::set("database.connections.$schemaName", $default);
                config(["database.connections.mysql.database" => $schemaName]);
            
                DB::connection($schemaName)->beginTransaction();
                DB::connection($schemaName)->statement("SET foreign_key_checks=0");

                // Variant::on($schemaName)->drop();
                // Schema::dropIfExists('variants');
                // DB::connection($schemaName)->statement("DROP TABLE variants");

                // Product::on($schemaName)->truncate();
                // AddonSet::on($schemaName)->truncate();
                // Category::on($schemaName)->truncate();

                $sqlFile = public_path('sql_files/'.$old_db.'.sql');
                $sql = file_get_contents($sqlFile);
                $sqll = DB::connection($schemaName)->unprepared($sql);
                DB::connection($schemaName)->commit();
                DB::connection($schemaName)->statement("SET foreign_key_checks=1");

                $this->info($sqll);
                DB::connection($schemaName)->disconnect($database_name);
                $this->info('done db cloned');
            }
        }catch(\Exception $e)
        {
            $this->info('Error--'.$e->getMessage().' -- line--'.$e->getLine());
        }

    }
}
