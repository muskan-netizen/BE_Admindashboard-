<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = ['vendor_id', 'discount', 'prescription_id','bid_total', 'final_amount'];

    public function bidRequests()
    {
        return $this->belongsTo(BidRequest::class, 'id');
    }

    public function bidProducts()
    {
        return $this->hasMany(BidProduct::class, 'bid_id')->with('product');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
