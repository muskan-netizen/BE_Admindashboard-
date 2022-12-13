<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGiftCard extends Model
{
    use HasFactory;

    public function giftCard(){
        return $this->belongsTo('App\Models\GiftCard', 'gift_card_id', 'id');
    }
}
