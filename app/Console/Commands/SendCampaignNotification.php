<?php

namespace App\Console\Commands;

use App\Jobs\CampaignSendNotificationJob;
use App\Models\Client;
use App\Mail\OrderSuccessEmail;
use App\Models\CampaignRoster;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Models\ClientPreference;
use Illuminate\Support\Facades\Log;

// use App\Models\Order;

class SendCampaignNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_campaign:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Campaign notifications at schedules time';

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
        $clients = Client::select('database_name', 'sub_domain')->get();
        $intervalTime = date('Y-m-d h:i:00');
        foreach ($clients as $client) {
            $database_name = 'royo_' . $client->database_name;
            //// Log::info("checking cart start: {$database_name}!");
            $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
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
                Config::set("database.connections.$database_name", $default);
                DB::setDefaultConnection($database_name);
                $client_preferences = ClientPreference::first(['fcm_server_key','sms_provider','sms_key','sms_secret','sms_from','mail_host','mail_port','mail_driver','mail_from','favicon']);
                $from = $client_preferences->fcm_server_key ?? "";
                $headers = [
                    'Authorization: key=' . $from,
                    'Content-Type: application/json',
                ];
                $chunk = config('app.campaign_chunk') ?? 100;
                $chunk_notifications = CampaignRoster::where('notification_time', '<=', $intervalTime)->where('status', 0)->with('campaign', 'user')->get()->chunk($chunk);

                if ($chunk_notifications) {
                    foreach ($chunk_notifications as $notifications)
                        CampaignSendNotificationJob::dispatch($notifications, $client_preferences, $headers);
                }
            } else {

                DB::disconnect($database_name);
            }
            // Log::info("CampaignSendNotification : ".now());
        }
    }



    // protected function sendEmail($client_preferences,$sendto,$subject,$body){

    //     $mailfrom = $client_preferences->mail_from;
    //     $confirured = $this->setMailDetail($client_preferences->mail_driver, $client_preferences->mail_host, $client_preferences->mail_port, $client_preferences->mail_username, $client_preferences->mail_password, $client_preferences->mail_encryption);
    //     // Mail::to($this->details['email'])->send($data);


    //         //$sendto =  $user->email;
    //         //$client_name = 'Sales';
    //         //$mail_from = $data->mail_from;
    //         try {
    //             $data = [
    //                 'link' => "link",
    //                 'email' => $sendto,
    //                 'mail_from' => $client_preferences->mail_from,
    //                 // 'client_name' => $client_name,
    //                 // 'logo' => $client->logo['original'],
    //                 'subject' => $subject,
    //                 //'customer_name' => $name,
    //                 'email_template_content' => $body,
    //             ];
    //             Mail::to($sendto)->send($data);
    //         } catch (\Exception $e) {
    //         }

    // }

    // public function setMailDetail($mail_driver, $mail_host, $mail_port, $mail_username, $mail_password, $mail_encryption){
    //     $config = array(
    //         'pretend' => false,
    //         'host' => $mail_host,
    //         'port' => $mail_port,
    //         'driver' => $mail_driver,
    //         'username' => $mail_username,
    //         'password' => $mail_password,
    //         'encryption' => $mail_encryption,
    //         'sendmail' => '/usr/sbin/sendmail -bs',
    //     );
    //     Config::set('mail', $config);
    //     $app = App::getInstance();
    //     $app->register('Illuminate\Mail\MailServiceProvider');
    //     return true;
    // }
}
