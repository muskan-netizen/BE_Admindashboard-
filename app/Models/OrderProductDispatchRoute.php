<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProductDispatchRoute extends Model
{
    use HasFactory;
    public function status(){
        return $this->hasMany('App\Models\VendorOrderProductDispatcherStatus', 'id', 'order_product_route_id');
      }
}
