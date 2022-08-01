<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BannerServiceArea extends Model
{
    use HasFactory;

    public function serviceArea(){
      return $this->hasOne('App\Models\ServiceArea', 'id', 'service_area_id')->select('id', 'vendor_id', 'geo_array', 'name');
    }
}