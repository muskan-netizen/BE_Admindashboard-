<?php
namespace App\Http\Traits;

use DB;
use Auth;
use HttpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\Client;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Log;


trait CustomerSignupSuccessEmailTrait{

    /**
     * getMultiBanner
     *
     * @param  mixed
     * @return void
     */

     public function sendCustomerSignupSuccessEmail($user)
     {
        if(getAdditionalPreference(['is_cust_success_signup_email'])['is_cust_success_signup_email'] == 1)
        {
          $content = '';
          $client_detail = Client::first();
          $email_template = EmailTemplate::where('slug', '=', 'newcustomersignup')->first();
          if($email_template){
              $content = $email_template->content;
              $content = str_ireplace("{name}", $user->name, $content);
              $content = str_ireplace("{client_name}", $client_detail->name, $content);
          
              $email_data = [
                  'name' => $user->name,
                  'email' => $user->email,
                  'powered_by' => url('/'),
                  'phone_no' => $user->phone_number,
                  'logo' => $client_detail->logo['original'],
                  'email_template_content' => $content,
                  'subject' => $email_template->subject,
                  'customer_name' => ucwords($user->name),
      
              ];
              dispatch(new \App\Jobs\sendCustomerRegistrationEmail($email_data))->onQueue('verify_email');
          }
        }
     }
}
