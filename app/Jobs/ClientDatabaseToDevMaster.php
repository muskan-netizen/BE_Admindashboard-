<?php

namespace App\Jobs;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Config;
use Exception;
use Illuminate\Support\Facades\Artisan;

class ClientDatabaseToDevMaster implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $client; 
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = Client::where('id', $this->client)->first(['name', 'email', 'password', 'phone_number', 'database_path', 'database_name', 'database_username', 'database_password', 'logo', 'company_name', 'company_address', 'custom_domain', 'status', 'code', 'country_id', 'sub_domain'])->toarray();
        $clientData = array();

           
        try {
           
            $schemaName = 'ab_royo_' . $client['database_name'] ?: config("database.connections.mysql.database");

                $database_host = !empty($client['database_host']) ? $client['database_host'] : env('DB_HOST_DEV', 'royoorders-2-db-development-cluster.cvgfslznkneq.us-west-2.rds.amazonaws.com');
                $database_port = !empty($client['database_port']) ? $client['database_port'] : env('DB_PORT_DEV', '3306');
                $database_username = !empty($client['database_username']) ? $client['database_username'] : env('DB_USERNAME_DEV', 'cbladmin');
                $database_password = !empty($client['database_password']) ? $client['database_password'] : env('DB_PASSWORD_DEV', 'aQ2hvKYLH4LKWmrA');

                $default = [
                'driver' => env('DB_CONNECTION', 'mysql'),
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

                $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
                $db = DB::select($query, [$schemaName]);
                if ($db) {
                    dd('exist');
                }else{
                    $query = "CREATE DATABASE $schemaName;";
                    DB::connection('dev')->statement($query);
                }
                
                dd($schemaName) ;
                
           
                Config::set("database.connections.$schemaName", $default);
                config(["database.connections.mysql.database" => $schemaName]);
            
                DB::connection($schemaName)->beginTransaction();
                DB::connection($schemaName)->statement("SET foreign_key_checks=0");

           

           

            Config::set("database.connections.$schemaName", $default);
            config(["database.connections.mysql.database" => $schemaName]);
         
            DB::connection($schemaName)->table('clients')->insert($clientData);
          
            DB::disconnect($schemaName);
        } catch (Exception $ex) {
            print_r($ex->getMessage());die;
           return $ex->getMessage();

        }
    }
}
