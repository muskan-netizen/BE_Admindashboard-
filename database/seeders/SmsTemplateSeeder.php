<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SmsTemplate;

class SmsTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SmsTemplate::updateOrCreate(['id' => 1],['label' => "Order Placed Successfully", 'slug' => 'order-place-Successfully', 'subject' => "Order Placed Successfully", 'content' => "Hi {user_name} Your order of amount {amount} for order number {order_number}", 'tags' => "{user_name},{amount},{order_number}"]);
        SmsTemplate::updateOrCreate(['id' => 2],['label' => "Otp Sms For Vendor Login", 'slug' => 'otp-sms-vendor-login', 'subject' => "Otp Sms For Vendor Login", 'content' => "Please enter otp-{otp_code}. Keep it safe and don't show to other.", 'tags' => "{otp_code}"]);
        SmsTemplate::updateOrCreate(['id' => 3],['label' => "Otp Sms User For Signup", 'slug' => 'otp-sms-user-signup', 'subject' => "Otp Sms User For Signup", 'content' => "Please enter otp-{otp_code}. Keep it safe and don't show to other.", 'tags' => "{otp_code}"]);
        SmsTemplate::updateOrCreate(['id' => 4],['label' => "Otp Sms For User Login", 'slug' => 'otp-sms-user-login', 'subject' => "Otp Sms For User Login", 'content' => "Please enter otp-{otp_code}. Keep it safe and don't show to other.", 'tags' => "{otp_code}"]);
        SmsTemplate::updateOrCreate(['id' => 5],['label' => "User Signup Sms", 'slug' => 'user-signup-sms', 'subject' => "User Signup Sms", 'content' => "Dear {user_name}, Thanks for creating an account with us!", 'tags' => "{user_name}"]);
        // SmsTemplate::updateOrCreate(['id' => 6],['label' => "Otp to verify account", 'slug' => 'user-signup-sms', 'subject' => "User Signup Sms", 'content' => "Dear {user_name}, Thanks for creating an account with us!", 'tags' => "{user_name}"]);


        // Dear ".ucwords($user->name).", Please enter OTP ".$phoneCode." to verify your account.

        
    }
}
