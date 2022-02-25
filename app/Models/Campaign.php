<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = ['title','type','sms_text','email_title','email_subject','email_body','push_title', 'push_image', 'push_message_body', 'push_url_option','push_url_option_value','send_to','schedule_datetime','request_user_count','request_time_difference','total_request_count','status'];
}
