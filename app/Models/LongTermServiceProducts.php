<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LongTermServiceProducts extends Model
{
    use HasFactory;
    public function product(){
        $langData = $this->hasOne('App\Models\Product','id','product_id');
        return $langData;
    }
}
