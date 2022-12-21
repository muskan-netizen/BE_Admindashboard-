<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'prescription'];

    public function user(){
        return $this->belongsTo(User::class, 'id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class, 'prescription_id');
    }
}
