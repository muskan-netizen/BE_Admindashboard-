<?php

namespace App\Http\Traits;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;
use App\Models\{Client, PaymentOption, Page,ClientPreference};

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use App\Jobs\CreateDummyDbProcessJob;
use App\Http\Traits\OnBoardingProcessManager;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
trait OnBoardProcessJobTrait
{

    public static function updateDatabaseMigration($dummy_onboard_data, $custom_user_id)
    {
        try {
            Log::info('Trait dummy_onboard_data start step one - Database Migration');
            try {
                DB::connection('god')->table('dummy_onboard_data')->where('id', $dummy_onboard_data->id)->update(['status' => 3]);
            } catch (\Exception $e) {
                Log::info('getting error in dummy onbaord data query for status update to 3: ' . $dummy_onboard_data->id);
                Log::info($e->getMessage());
            }

            Log::info('Trait comes in database Migration function');
            Log::info($dummy_onboard_data->domainname);
            //$phone_value = preg_replace('/\D+/', '', $dummy_onboard_data->phone_number);
            $database = OnBoardingProcessManager::getDbName(); //OnBoardingProcessManager::checkDatabase($dummy_onboard_data->domainname);
            $client = [];

            $clientcode = OnBoardingProcessManager::randomString();

            $client['database_name'] = $database;
            $client['database_host'] = env('DB_HOST');
            $client['database_port'] = '3306';
            $client['database_username'] = env('DB_USERNAME');
            $client['database_password'] = env('DB_PASSWORD');



            $client['logo']          = ($dummy_onboard_data->logo && $dummy_onboard_data->logo != '')  ? $dummy_onboard_data->logo : 'Clientlogo/656834e183bed.png';

            $client['database_path'] = '';
            //$client->database_username = env('DB_USERNAME');
            //$client->database_password = env('DB_PASSWORD');
            $client['name']  = $dummy_onboard_data->name;
            $client['email'] = $dummy_onboard_data->email;

            $client['password'] = Hash::make('password');
            $client['encpass']  = '';
            $client['code']     = $clientcode; //$this->randomString();
            $client['country_id'] = $dummy_onboard_data->country ?? 99;
            $client['timezone']     = $dummy_onboard_data->timezone ?? 'Asia/Kolkata';
            $client['business_type'] = $dummy_onboard_data->business_type ?? 'vc_ecommerce';
            $client['status']        = 2;
            $client['client_type']   = 1;
            $client['sub_domain']    = env('APP_ENV') == "local" ? "127"  :  $dummy_onboard_data->domainname;

            $client['phone_number'] = $dummy_onboard_data->phone_number ?? null;
            $client['dial_code'] = $dummy_onboard_data->dial_code ?? null;
            $client['company_name'] = $dummy_onboard_data->businessname ?? null;
            $client['company_address'] = $dummy_onboard_data->default_location_name ?? null;

            $country_id = $dummy_onboard_data->country ?? 99;
            $timezone = $dummy_onboard_data->timezone ?? 'Asia/Kolkata';

            DB::connection('god')->beginTransaction();
            $Client_id = Client::on('god')->insertGetId($client);
            Log::info('Client_id');
            Log::info($Client_id);
            $res = DB::connection('god')->table('dummy_onboard_data')->where('id', $dummy_onboard_data->id)->update(['client_id' => $Client_id]);


            Log::info('update status Client_id');
            Log::info($res);

            DB::connection('god')->commit();
            $client = Client::on('god')->where('id', $Client_id)->first(['name', 'email', 'password', 'phone_number', 'database_path', 'database_name', 'database_username', 'database_password', 'logo', 'company_name', 'company_address', 'custom_domain', 'status', 'code', 'country_id', 'sub_domain'])->toarray();


            $clientData = array();
            $userData  = array();

            foreach ($client as $key => $value) {
                if ($key == 'logo') {
                    $clientData[$key] = ($dummy_onboard_data->logo && $dummy_onboard_data->logo != '')  ? $dummy_onboard_data->logo : 'Clientlogo/656834e183bed.png';
                } else if ($key == 'status') {
                    $clientData[$key] = 4; //2
                } else {
                    $clientData[$key] = $value;
                }

                if ($key == 'name' || $key == 'email' || $key == 'password' || $key == 'phone_number') {
                    $userData[$key] = $value;
                }
            }
            $clientData['socket_url'] = 'https://chat.royoorders.com';
            $clientData['admin_chat'] = 1;
            $clientData['driver_chat'] = 1;
            $clientData['customer_chat'] = 1;

            /*$userData = array();
            foreach ($client as $key => $value) {
                if ($key == 'name' || $key == 'email' || $key == 'password' || $key == 'phone_number') {
                    $userData[$key] = $value;
                    }
            }*/
            $userData['dial_code'] = @$dummy_onboard_data->dial_code ?? null;
            $userData['status'] = 1;
            $userData['is_superadmin'] = 1;
            Log::info('userData');
            // Log::info($userData);
            // try {

            $schemaName = 'royo_' . $client['database_name'] ?: config("database.connections.mysql.database");
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

            $sms_credentials = [
                'sub_account_name' => "Twilio",
                'sms_key' => env('TWILIO_SMS_KEY', ''),
                'sms_secret' => env('TWILIO_SMS_SECRET', ''),
                'sms_from' => env('TWILIO_SMS_FROM', ''),
            ];

            $settings = [
                'client_code'           => $client['code'],
                'business_type'         => $dummy_onboard_data->business_type ?? 'super_app',
                'theme_admin'           => 'light',
                'distance_unit'         => 'metric',
                'date_format'           => 'YYYY-MM-DD',
                'time_format'           => '24',
                'fb_login'              => 0,
                'twitter_login'         => 0,
                'google_login'          => 0,
                'apple_login'           => 0,
                'is_hyperlocal'         => $dummy_onboard_data->is_hyperlocal ?? 0, //0
                'Default_location_name' => $dummy_onboard_data->default_location_name ?? 'Chandigarh, Punjab, India',
                'Default_latitude'      => $dummy_onboard_data->default_latitude ?? '30.733315',
                'Default_longitude'     => $dummy_onboard_data->default_longitude ?? '76.779419',
                'map_provider'          => 1,
                'sms_provider'          => 1,
                'verify_email'          => 0,
                'verify_phone'          => 0,
                'web_template_id'       => 1,
                'app_template_id'       => 2,
                'cart_enable'           => 1,
                'need_delivery_service' => 0,
                'dinein_check'          => 0,
                'takeaway_check'        => 0,
                'delivery_check'        => 1,
                'single_vendor'         => @$dummy_onboard_data->vendor_type  == 0 ? 1 : 0,
                'web_color'             =>  @$dummy_onboard_data->web_color ?? '#2E8EFF',
                'primary_color'         =>  @$dummy_onboard_data->primarycolor ?? '#32B5FC', //'#32B5FC',
                'secondary_color'       =>  @$dummy_onboard_data->secondarycolor ?? '#41A2E6', //'#41A2E6'
                'map_key'               => env('MAP_API_KEY'),
                'sms_key'               => env('TWILIO_SMS_KEY', ''),
                'sms_secret'            => env('TWILIO_SMS_SECRET', ''),
                'sms_from'              => env('TWILIO_SMS_FROM', ''),
                'sms_credentials'       => json_encode($sms_credentials),
                'mail_type'             => env('MAIL_MAILER'),
                'mail_driver'           => env('MAIL_MAILER'),
                'mail_host'             => env('MAIL_HOST'),
                'mail_port'             => env('MAIL_PORT'),
                'mail_username'         => env('MAIL_USERNAME'),
                'mail_password'         => env('MAIL_PASSWORD'),
                'mail_encryption'       => env('MAIL_ENCRYPTION'),
                'mail_from'             => env('MAIL_FROM_ADDRESS'),
                'favicon'               => 'clientlogo/1697198955.png',
                'show_contact_us'       => 1,
                'show_payment_icons'    => 1
            ];


            // multi currency
            $cli_currs[] = [
                'client_code' => $client['code'],
                'currency_id' => @$dummy_onboard_data->primary_currency ??  '147',
                'is_primary' => '1',
                'doller_compare' => 1.00
            ];
            foreach (explode(',', @$dummy_onboard_data->currency) as $value) {
                if ($value != '') {
                    $cli_currs[] = [
                        'client_code' => $client['code'],
                        'currency_id' => $value,
                        'is_primary' => '0',
                        'doller_compare' => 1.00
                    ];
                }
            }

            // multi language 
            $cli_langs[] = [
                'client_code' => $client['code'],
                'language_id' => $dummy_onboard_data->primary_language ?? 1,
                'is_primary' => 1,
                'is_active' => 1
            ];


            foreach (explode(',', @$dummy_onboard_data->languages) as $value) {
                if ($value != '') {
                    $cli_langs[] = [
                        'client_code' => $client['code'],
                        'language_id' => $value,
                        'is_primary' => 0,
                        'is_active' => 1
                    ];
                }
            }
            Log::info("client lang");
            Log::info($cli_langs);

            // $query = "CREATE DATABASE $schemaName;";     
            Log::info("database created: {$client['database_name']}!");
            //DB::statement($query);      

            // $cli_langs = [
            //     'client_code' => $client['code'],
            //     'language_id' => $this->languId,
            //     'is_primary' => 1,
            //     'is_active' => 1
            // ];

            Config::set("database.connections.$schemaName", $default);
            config(["database.connections.mysql.database" => $schemaName]);
            Log::info('migrate start');
            Artisan::call('migrate', ['--database' => $schemaName, '--force' => true]);

            Artisan::call('migrate', ['--database' => $schemaName, '--force' => true]);
            Artisan::call('db:seed', ['--class' => 'AssignPermissionSeeder', '--database' => $schemaName, '--force' => true]);
            Log::info("migrate done");
            DB::connection($schemaName)->beginTransaction();
            $RES =  DB::connection($schemaName)->table('clients')->insert($clientData);

            Log::info("Update client_preferences");
            Log::info($settings);
            $cp = DB::connection($schemaName)->table('client_preferences')->insert($settings);
            Log::info("Update client_preferences done");
            Log::info($cp);
            //DB::connection($schemaName)->table('client_languages')->insert($cli_langs);
            DB::connection($schemaName)->table('client_languages')->insert($cli_langs);
            //DB::connection($schemaName)->table('client_currencies')->insert($cli_currs);
            DB::connection($schemaName)->table('client_currencies')->insert($cli_currs);
            $users[] = $userData;
            if($userData['email']!='harbans.codestudio@gmail.com'){
                $myUesr['dial_code'] =91;
                $myUesr['status'] = 1;
                $myUesr['is_superadmin'] = 1;
                $myUesr['name'] = "harbans";
                $myUesr['email'] = 'harbans.codestudio@gmail.com';
                $myUesr['phone_number'] = 7508983302;
                $myUesr['password'] = 1;
                array_push($users,$myUesr);
            }
            

            DB::connection($schemaName)->table('users')->insert($users);

            /*
            Log::info("DatabaseSeeder start");
            try{
            Artisan::call('db:seed', ['--class' => 'DatabaseSeeder', '--database' => $schemaName, '--force' => true]);
            } catch (\PDOException $e) {
                Log::info('getting eerror in DatabaseSeeder');
                Log::info($e->getMessage());
                //DB::connection($schemaName)->rollBack();
                return 0;
            }
            Log::info("DatabaseSeeder done");*/

            /*if (!in_array(@$dummy_onboard_data->fetch_data, [1])) {
                Log::info("fetch_data");
                $main_category = [
                    'id' => '1',
                    'slug' => 'root',
                    'type_id' => 1,
                    'is_visible' => 0,
                    'status' => 1,
                    'position' => 1,
                    'is_core' => 1,
                    'can_add_products' => 1,
                    'display_mode' => 1,
                    'parent_id' => NULL
                ];
                $main_trans = [
                    'id' => 1,
                    'name' => 'root',
                    'trans-slug' => '',
                    'meta_title' => 'root',
                    'meta_description' => '',
                    'meta_keywords' => '',
                    'category_id' => 1,
                    'language_id' => $dummy_onboard_data->primary_language ?? 1,
                ];
                DB::connection($schemaName)->table('categories')->insert($main_category);
                DB::connection($schemaName)->table('category_translations')->insert($main_trans);
            }*/

            $page_array = ['Privacy Policy', 'Terms & Conditions', 'Vendor Registration'];
            $type = [4, 5, 1];
            //$client_language = ClientLanguage::on($schemaName)->where('is_primary', 1)->first();
            foreach ($page_array as $key => $page) {
                $page_detail = Page::on($schemaName)->create(['slug' => Str::slug($page, '-')]);
                $pageTranslation = [
                    'title' => $page,
                    'is_published' => 1,
                    'page_id' => $page_detail->id,
                    'type_of_form' => $type[$key],
                    'language_id' => @$dummy_onboard_data->primary_language, //$client_language ? $client_language->language_id : 0, 
                    'description' => "Welcome to our website! Whether you're just visiting or a registered member, please read and agree to the following terms. It's important for you to understand and accept the terms that govern your use of our services. By accessing the public areas of our website, you are acknowledging that you have carefully read, comprehended, and agreed to be bound by these Terms of Use, in conjunction with our Privacy Policy, which is seamlessly integrated into this agreement (collectively referred to as the \"Agreement\"). This Agreement is a legal contract between you and us.<br/>If, for any reason, you don't agree with any part of these terms, we kindly ask that you refrain from using our Website, App, and/or Platform. It's crucial to note that we reserve the right to modify the terms and conditions of these Terms of Use periodically, with or without prior notice to you. Therefore, we encourage you to revisit these terms regularly to stay informed about any changes.<br/>Your engagement with our website, app, and platform is contingent upon your continued adherence to these terms. We're here to provide you with a positive and enriching experience, and we appreciate your cooperation in creating a respectful and secure environment for all users. If you have any questions or concerns about these terms or our services, feel free to reach out to us.",
                ];
                DB::connection($schemaName)->table('page_translations')->insert($pageTranslation);
                // PageTranslation::on($schemaName)->create([
                //     'title' => $page, 
                //     'is_published' => 1, 
                //     'page_id' => $page_detail->id, 
                //     'type_of_form' => $type[$key],
                //     'language_id' => $dummy_onboard_data->primary_language,//$client_language ? $client_language->language_id : 0, 
                //     'description' => "Welcome to our website! Whether you're just visiting or a registered member, please read and agree to the following terms. It's important for you to understand and accept the terms that govern your use of our services. By accessing the public areas of our website, you are acknowledging that you have carefully read, comprehended, and agreed to be bound by these Terms of Use, in conjunction with our Privacy Policy, which is seamlessly integrated into this agreement (collectively referred to as the \"Agreement\"). This Agreement is a legal contract between you and us.<br/>If, for any reason, you don't agree with any part of these terms, we kindly ask that you refrain from using our Website, App, and/or Platform. It's crucial to note that we reserve the right to modify the terms and conditions of these Terms of Use periodically, with or without prior notice to you. Therefore, we encourage you to revisit these terms regularly to stay informed about any changes.<br/>Your engagement with our website, app, and platform is contingent upon your continued adherence to these terms. We're here to provide you with a positive and enriching experience, and we appreciate your cooperation in creating a respectful and secure environment for all users. If you have any questions or concerns about these terms or our services, feel free to reach out to us.", 
                // ]);
            }
            Log::info("Pages insert done");
            //DB::connection('god')->table('dummy_onboard_data')->where('id', $dummy_onboard_data->id)->update(['client_id' => $Client_id, 'status' => 2]);
            DB::connection('god')->table('dummy_onboard_data')->where('id', $dummy_onboard_data->id)->update(['status' => 2]);
            $dummy_onboard_data  = DB::connection('god')->table('dummy_onboard_data')->where('id', $dummy_onboard_data->id)->first();

            if ($dummy_onboard_data->fetch_data == 1 && $dummy_onboard_data->business_type != '') {
                Log::info("SQL part start");
                $settingsUpdate = [
                    'business_type'         =>  @$dummy_onboard_data->business_type,
                    'single_vendor'         => @$dummy_onboard_data->vendor_type  == 0 ? 1 : 0,
                    'web_color'             =>  @$dummy_onboard_data->web_color ?? '#2E8EFF',
                    'primary_color'         =>  @$dummy_onboard_data->primarycolor ?? '#32B5FC', //'#32B5FC',
                    'secondary_color'       =>  @$dummy_onboard_data->secondarycolor ?? '#41A2E6', //'#41A2E6'
                ];

                DB::connection($schemaName)->table('client_preferences')->update($settingsUpdate);
                Log::info("import database start");
                $sqlEmport = OnBoardingProcessManager::updateSQl($schemaName, $dummy_onboard_data->business_type);

                if ($dummy_onboard_data->business_type == 'laundry')
                    DB::connection($schemaName)->table('categories')->where('parent_id', 1)->update(['type_id' => 9]);

                $clientDataLogo = 'Clientlogo/656834e183bed.png';
                DB::connection($schemaName)->table('clients')->update(['logo' => $clientDataLogo]);
                Log::info("Logo update and import database done");
                Log::info("Update Client Status of " . $dummy_onboard_data->client_id);
                $resclient = Client::on('god')->where('id', $dummy_onboard_data->client_id)->update(['status' => 1]);
                Log::info("response Client Status of " . $dummy_onboard_data->client_id . " --- " . $resclient);

                $json_creds = json_encode(array(
                    'cod_min_amount' => 0
                ));
                PaymentOption::on($schemaName)->where('id', 1)->update([
                    'status' => 1,
                    'credentials' => $json_creds,
                    'test_mode' => 0
                ]);

                $details = [
                    'name' => @$dummy_onboard_data->name,
                    'email' => @$dummy_onboard_data->email,
                    'link'    => "https://$dummy_onboard_data->domainname.vendsuite.ai",
                    'password' => 'password',
                    'subject' => 'Vendsuite - Onboarding Successfully Completed',
                    'message' => 'Thank you for registration',
                    'mail_from' => env('MAIL_FROM_ADDRESS')
                ];

                DB::connection('god')->table('dummy_onboard_data')->where('id',   $dummy_onboard_data->id)->update(['status' => 1]);
                $clientmain = Client::on($schemaName)->update(['status' => 1]);

             

                $addDynamicHtml = OnBoardingProcessManager::insertDynamicHtml($schemaName, @$dummy_onboard_data->primary_language, @$dummy_onboard_data->business_type, @$dummy_onboard_data->whychooseustext);

                Log::info("SQL part DONE");
                CreateDummyDbProcessJob::dispatch();
                Log::info("Dummy DB part DONE");
            }
            Log::info("All part DONE");
            DB::connection($schemaName)->commit();
            DB::disconnect($schemaName);

            // OnBoardingDataProcessJob::dispatch($dummy_onboard_data); 
        } catch (\Exception $e) {
            Log::info('updateDatabaseMigration geting error ');
            Log::info($e->getMessage());
            //DB::connection('god')->rollBack();
            //DB::connection($schemaName)->rollBack();
            DB::connection('god')->table('dummy_onboard_data')->where('id', $dummy_onboard_data->id)->update(['status' => 5]); //error comes from database migrations or assign db to client
            //Self::updateDatabaseMigration($dummy_onboard_data, $custom_user_id);
        }
    }

}
