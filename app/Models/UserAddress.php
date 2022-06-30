<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }
    public function CarDetails()
    {
        return $this->hasOne('App\Models\CarDetails');
    }
    public function images()
    {
        return $this->hasOne('App\Models\CarImages')->select('id' ,'image'); ;
    }
}
