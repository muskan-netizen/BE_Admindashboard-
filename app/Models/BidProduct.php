<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidProduct extends Model
{
    use HasFactory;

    protected $fillable = ['bid_id', 'product_id', 'vendor_id', 'quantity'];

    public function bids()
    {
        return $this->hasMany(Bid::class, 'id');
    }
}
