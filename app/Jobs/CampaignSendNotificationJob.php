<?php

namespace App\Jobs;
use App;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Twilio\Rest\Client as TwilioClient;
use App\Models\Campaign;
use App\Models\CampaignRoster;
use App\Models\UserDevice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CampaignSendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $notifications;
    protected $client_preferences;
    protected $headers;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    // public function __construct($notifications,$client_preferences,$headers){
    //     $this->notifications = $notifications;
    //     $this->client_preferences = $client_preferences;
    // }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function __construct($notifications,$client_preferences,$headers)
    {
        // $notifications = $this->notifications;
        // $client_preferences = $this->client_preferences;
        // $headers = $this->headers;
        foreach($notifications as $singlenotification)
        {
            //CampaignRoster::where('id',6290)->delete();
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

                            if($send)
                            {
                                if($send==2)
                                {
                                    //change status if failed
                                    CampaignRoster::where('id',$singlenotification->id)->update(array('status'=>2));
                                }else{
                                    //remove notification if success
                                    CampaignRoster::where('id',$singlenotification->id)->delete();
                                }
                            }else{
                                //change status if failed
                                CampaignRoster::where('id',$singlenotification->id)->update(array('status'=>2));
                            }
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
                            $sendemail = dispatch(new \App\Jobs\SendOrderSuccessEmailJob($email_data))->onQueue('verify_email');
                            if($sendemail)
                            {
                                //remove notification if success
                                CampaignRoster::where('id',$singlenotification->id)->delete();
                            }else{
                               //change status if failed
                                CampaignRoster::where('id',$singlenotification->id)->update(array('status'=>2));
                            }
                            //$this->sendEmail($client_preferences,$useremail,$email_subject,$email_body);
                        }
                    } catch (\Exception $ex) {
                    }
                    break;
                case '3':
                    //send push
                    //$redirect_URL = "https://" . $client->sub_domain . env('SUBMAINDOMAIN') . "/viewcart";
                    $redirect_URL = $singlenotification->campaign->push_url_option_value;
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
                    curl_close($ch);
                    $result = json_decode($result);

                    if($result->success == true)
                    {
                        //remove notification if success
                        CampaignRoster::where('id',$singlenotification->id)->delete();
                    }else{
                        $error_message = 'Campaign Notificqation Failed due to '. $result->results[0]->error . '  User_id ' . $singlenotification->user_id;

                        Log::error($error_message);
                        //change status if failed
                        //  Log::error('user_id - ' . $singlenotification->user_id.' send_notification_error ' .$result);
                        CampaignRoster::where('id',$singlenotification->id)->update(array('status'=>2));
                    }
                break;
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

}
