<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Mail\OrderSuccessEmail;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Models\ClientPreference;
use App\Models\Campaign;
use App\Models\CampaignRoster;
use App\Models\UserDevice;
use App\Models\User;
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
                $client_preferences = ClientPreference::first();

                // CampaignRoster::where('id',6287)->delete();
                $notifications = CampaignRoster::where('notification_time', '<=',$intervalTime)->where('status',0)->with('campaign','user')->get();
                // $notifications = CampaignRoster::whereBetween('notification_time', [$intervalTime, $add1Minute])->where('status',0)->with('campaign','user')->get();
                //// Log::info("CampaignRoster time: {$intervalTime}!");
                //// Log::info("CampaignRoster data: {$notifications}!");
                if($notifications)
                {

                  foreach($notifications as $singlenotification)
                    {
                        //CampaignRoster::where('id',6290)->delete();
                        CampaignRoster::where(['campaign_id'=>$singlenotification->campaign_id])->delete();
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
                                        $this->sendSms($provider, $client_preferences->sms_key, $client_preferences->sms_secret, $client_preferences->sms_from, $to, $body);
                                        // if($send)
                                        // {
                                        //     if($send==2)
                                        //     {
                                        //         //change status if failed
                                        //         CampaignRoster::where('id',$singlenotification->id)->update(array('status'=>2));
                                        //     }else{
                                        //         //remove notification if success
                                        //         CampaignRoster::where('id',$singlenotification->id)->delete();
                                        //     }
                                        // }else{
                                        //     //change status if failed
                                        //     CampaignRoster::where('id',$singlenotification->id)->update(array('status'=>2));
                                        // }
                                    }

                                } catch (\Exception $ex) {
                                }
                                break;
                            case '2':
                                //send email
                                try {
                                    if (!empty($client_preferences->mail_driver) && !empty($client_preferences->mail_host) && !empty($client_preferences->mail_port) && !empty($client_preferences->mail_password) && !empty($client_preferences->mail_encryption)) {
                                        $useremail = $singlenotification->user->email;
                                        $email_subject = $singlenotification->campaign->email_subject;
                                        $email_body = $singlenotification->campaign->email_body;

                                        $email_data = [
                                            'email' => $useremail,
                                            'mail_from' => $client_preferences->mail_from,
                                            'subject' => $email_subject,
                                            'email_template_content' => $email_body,
                                            'send_to_cc' => 0
                                        ];
                                        dispatch(new \App\Jobs\SendOrderSuccessEmailJob($email_data))->onQueue('verify_email');
                                        // if($sendemail)
                                        // {
                                        //     //remove notification if success
                                        //     CampaignRoster::where('id',$singlenotification->id)->delete();
                                        // }else{
                                        //    //change status if failed
                                        //     CampaignRoster::where('id',$singlenotification->id)->update(array('status'=>2));
                                        // }
                                        //$this->sendEmail($client_preferences,$useremail,$email_subject,$email_body);
                                    }
                                } catch (\Exception $ex) {
                                }
                                break;
                            case '3':
                                //send push
                                $redirect_URL = $singlenotification->campaign->push_url_option_value;
                                $attachmentImg = (!empty($singlenotification->campaign->push_image['proxy_url'])) ? $singlenotification->campaign->push_image['proxy_url'] . '200/200' . $singlenotification->campaign->push_image['image_path'] : '';

                                $data = [
                                    "registration_ids" => [$singlenotification->device_token],
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
                                        'type' => "reminder_notification",
                                        'click_action' => $redirect_URL,
                                    ],
                                    "priority" => "high"
                                ];
                                sendFcmCurlRequest($data);
                                // if($result)
                                // {
                                //     //remove notification if success
                                //     CampaignRoster::where('id',$singlenotification->id)->delete();
                                // }else{
                                //     //change status if failed
                                //     CampaignRoster::where('id',$singlenotification->id)->update(array('status'=>2));
                                // }
                            break;
                        }
                    }
                }

                DB::disconnect($database_name);
                //// Log::info("checking cart end: {$database_name}!");
            } else {
                DB::disconnect($database_name);
                //// Log::info("checking cart  end: {$database_name}!");
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
            }
            elseif($client_preference->sms_provider == 7) //for Vonage gateway
            {
            $crendentials = json_decode($client_preference->sms_credentials);
            $send = $this->vonage_sms($to,$body,$crendentials);
            }
            elseif($client_preference->sms_provider == 8) //for SMS partner gateway France
            {
            $crendentials = json_decode($client_preference->sms_credentials);
            $send = $this->sms_partner_gateway($to,$body,$crendentials);
            }elseif($client_preference->sms_provider == 9) //for SMS Ethiopia gateway
            {
            $crendentials = json_decode($client_preference->sms_credentials);
            $send = $this->sms_ethiopia_gateway($to,$body,$crendentials);
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
