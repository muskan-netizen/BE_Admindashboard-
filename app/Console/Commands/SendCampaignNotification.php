<?php

namespace App\Console\Commands;

use App\Models\Client;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Models\ClientPreference;
use App\Models\Campaign;
use App\Models\CampaignRoster;
// use App\Models\OrderVendor;
// use App\Models\Vendor;
use App\Models\UserDevice;
use App\Models\User;
// use App\Models\NotificationTemplate;
// use App\Models\AutoRejectOrderCron;
use Log;
use Carbon\Carbon;
use Twilio\Rest\Client as TwilioClient;
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
        $intervalTime = Carbon::now();
        foreach ($clients as $client) {
            $database_name = 'royo_' . $client->database_name;
            // Log::info("checking cart start: {$database_name}!");
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
                $client_preferences = ClientPreference::first();   
                $from = $client_preferences->fcm_server_key ?? "";
                $headers = [
                    'Authorization: key=' . $from,
                    'Content-Type: application/json',
                ];             
                $notifications = CampaignRoster::where('notification_time', '<=', $intervalTime)->where('status',0)->with('campaign','user')->get();
                if($notifications)
                {
                    // $prefer = ClientPreference::select('sms_provider', 'sms_key', 'sms_secret', 'sms_from')->first();
                    //     $to = '+919996687653';
                    //     $provider = $client_preferences->sms_provider;
                    //     $body = "Hi ".$client_preferences->sms_key;
                    //     if (!empty($client_preferences->sms_provider)) {
                    //         $send = $this->sendSms($provider, $client_preferences->sms_key, $client_preferences->sms_secret, $client_preferences->sms_from, $to, $body);
                    //     }
                    foreach($notifications as $singlenotification)
                    {
                        

                        $type = $singlenotification->notofication_type;
                        //	type => 1 sms, 2 email, 3 push notification
                        switch ($type) {
                            case '1':
                                //send sms
                                try {
                                   // $prefer = ClientPreference::select('sms_provider', 'sms_key', 'sms_secret', 'sms_from')->first();
                                    if ($singlenotification->user->dial_code == "971") {
                                        $to = '+' . $singlenotification->user->dial_code . "0" . $singlenotification->user->phone_number;
                                    } else {
                                        $to = '+' . $singlenotification->user->dial_code . $singlenotification->user->phone_number;
                                    }
                                    $provider = $client_preferences->sms_provider;
                                    $body = "Hi " . $singlenotification->user->name . ", " . $singlenotification->campaign->sms_text;
                                    if (!empty($client_preferences->sms_provider)) {
                                        $send = $this->sendSms($provider, $client_preferences->sms_key, $client_preferences->sms_secret, $client_preferences->sms_from, $to, $body);
                                    }
                                    
                                } catch (\Exception $ex) {
                                }
                                break;
                            case '2':
                                //send email
                                try {
                                    // $prefer = ClientPreference::select('sms_provider', 'sms_key', 'sms_secret', 'sms_from')->first();
                                    // if ($singlenotification->user->dial_code == "971") {
                                    //     $to = '+' . $singlenotification->user->dial_code . "0" . $singlenotification->user->phone_number;
                                    // } else {
                                    //     $to = '+' . $singlenotification->user->dial_code . $singlenotification->user->phone_number;
                                    // }
                                    // $provider = $client_preferences->sms_provider;
                                    // $body = "Hi " . $singlenotification->user->name . ", " . $singlenotification->campaign->sms_text;
                                    // if (!empty($client_preferences->sms_provider)) {
                                    //     $send = $this->sendEmail($provider, $client_preferences->sms_key, $client_preferences->sms_secret, $client_preferences->sms_from, $to, $body);
                                    // }
                                    
                                } catch (\Exception $ex) {
                                }
                                break;
                            case '3':
                                //send push                                
                                //$redirect_URL = "https://" . $client->sub_domain . env('SUBMAINDOMAIN') . "/viewcart";
                                $redirect_URL = $singlenotification->campaign->push_url_option_value;
                                $data = [
                                    "registration_ids" => $singlenotification->device_token,
                                    "notification" => [
                                        'title' => $singlenotification->campaign->push_title,
                                        'body'  => $singlenotification->campaign->push_message_body,
                                        'sound' => "default",
                                        "icon" => (!empty($client_preferences->favicon)) ? $client_preferences->favicon['proxy_url'] . '200/200' . $client_preferences->favicon['image_path'] : '',
                                        'click_action' => $redirect_URL,
                                        "android_channel_id" => "default-channel-id"
                                    ],
                                    "data" => [
                                        'title' => $singlenotification->campaign->push_title,
                                        'body'  => $singlenotification->campaign->push_message_body,
                                        'type' => "reminder_notification"
                                    ],
                                    "priority" => "high"
                                ];
                                $dataString = $data;
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                                curl_setopt($ch, CURLOPT_POST, true);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataString));
                                $result = curl_exec($ch);
                                // Log::info($result);
                                curl_close($ch);

                                //change status if failed
                                CampaignRoster::where('id',$singlenotification->id)->update(array('status'=>2));

                                //remove notification if success
                                CampaignRoster::where('id',$singlenotification->id)->delete();
                            
                            break;
                        }

                    }
                }               
                
                DB::disconnect($database_name);
                // Log::info("checking cart end: {$database_name}!");
            } else {
                DB::disconnect($database_name);
                // Log::info("checking cart  end: {$database_name}!");
            }
        }
    }


    protected function sendSms($provider, $sms_key, $sms_secret, $sms_from, $to, $body){
        try{
            $client_preference =  getClientPreferenceDetail();
            if($client_preference->sms_provider == 1)
            {
                $client = new TwilioClient($sms_key, $sms_secret);
                $client->messages->create($to, ['from' => $sms_from, 'body' => $body]);
            }elseif($client_preference->sms_provider == 2) //for mtalkz gateway
            {
                $crendentials = json_decode($client_preference->sms_credentials);
                $send = $this->mTalkz_sms($to,$body,$crendentials);
            }elseif($client_preference->sms_provider == 3) //for mazinhost gateway
            {
                $crendentials = json_decode($client_preference->sms_credentials);
                $send = $this->mazinhost_sms($to,$body,$crendentials);
            }elseif($client_preference->sms_provider == 4) //for unifonic gateway
            {
                $crendentials = json_decode($client_preference->sms_credentials);
                $send = $this->unifonic($to,$body,$crendentials);
            }else{
                $client = new TwilioClient($sms_key, $sms_secret);
                $client->messages->create($to, ['from' => $sms_from, 'body' => $body]);
            }
        }
        catch(\Exception $e){
            return '2';
        }
        return '1';
	}

    protected function sendEmail($sendto,$mailfrom,$subject,$body){
        
        // $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
        // $data = ClientPreference::select('sms_key', 'sms_secret', 'sms_from', 'mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'sms_provider', 'mail_password', 'mail_encryption', 'mail_from')->where('id', '>', 0)->first();
        // $message = __('An otp has been sent to your email. Please check.');
        // if (!empty($data->mail_driver) && !empty($data->mail_host) && !empty($data->mail_port) && !empty($data->mail_port) && !empty($data->mail_password) && !empty($data->mail_encryption)) {
        //     $confirured = $this->setMailDetail($data->mail_driver, $data->mail_host, $data->mail_port, $data->mail_username, $data->mail_password, $data->mail_encryption);
        //     //$sendto =  $user->email;
        //     $client_name = 'Sales';
        //     //$mail_from = $data->mail_from;
        //     try {                
        //         $data = [
        //             'link' => "link",
        //             'email' => $sendto,
        //             'mail_from' => $mailfrom,
        //             'client_name' => $client_name,
        //             'logo' => $client->logo['original'],
        //             'subject' => $subject,
        //             //'customer_name' => $name,
        //             'email_template_content' => $body,
        //         ];
        //         dispatch(new \App\Jobs\SendOrderSuccessEmailJob($data))->onQueue('verify_email');
        //         $notified = 1;
        //     } catch (\Exception $e) {
        //     }
        // }
    }
}
