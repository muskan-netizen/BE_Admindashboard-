<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDataVault extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'expiration',
        'brand',
        'card_hint'
    ];
}
