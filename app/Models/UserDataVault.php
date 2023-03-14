<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDataVault extends Model
{
    use HasFactory;

    protected $table = 'user_data_vault';

    protected $fillable = [
        'user_id',
        'token',
        'is_default',
        'expiration',
        'brand',
        'card_hint'
    ];
}
