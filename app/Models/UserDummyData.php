<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDummyData extends Model
{
    use HasFactory;

    protected $table = 'user_dummy_data';

    protected $fillable = [
        'name',
        'image',
    ];
}
