<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerfication extends Model
{
    use HasFactory;


    public function addVerification($data)
    {
    	return self::updateOrCreate([
    		'verification_option_id' => $data['verification_option_id'],
    		'user_id' => $data['user_id'],
    	],[
    		'response_id' => $data['response_id'],
    		'status' => $data['status']
    	]);
    }
    public function updateStatus($data)
    {
    	return self::where([
    		'verification_option_id' => $data['verification_option_id'],
    		'response_id' => $data['response_id']
    	])->update([
    		'status' => $data['status']
    	]);
    }
}
