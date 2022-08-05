<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProductBooking extends Model
{

    use HasFactory;
    use SoftDeletes;
    protected $table = "product_bookings";
    
    protected $fillables = ['product_id','order_user_id', 'order_vendor_id', 'variant_id', 'memo', 'booking_type','start_date_time', 'end_date_time', 'booking_start_end'];

    public function products()
    {
        return $this->hasMany('App\Models\OrderProduct', 'product_id', 'id');
    }
 
}
