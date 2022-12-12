<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliverySlotProduct extends Model
{
    use HasFactory;

    protected $table = 'delivery_slots_product';

    protected $fillable = ['product_id', 'delivery_slot_id'];
}
