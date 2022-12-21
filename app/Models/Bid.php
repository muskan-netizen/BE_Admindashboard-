<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = ['vendor_id', 'discount', 'prescription_id'];

    public function prescriptions()
    {
        return $this->belongsTo(BidRequest::class, 'id');
    }

    public function bidProducts()
    {
        return $this->belongsTo(BidProduct::class, 'prescription_id');
    }
}
