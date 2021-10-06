<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabBookingLayout extends Model
{
    use HasFactory;

    protected $fillable = ['title','slug','order_by','is_active'];


    public function translations(){
        $langData = $this->hasMany('App\Models\CabBookingLayoutTranslation');
        return $langData;
    }


    public function pickupCategories(){
        return $this->hasMany('App\Models\CabBookingLayoutCategory')->whereHas('categoryDetail',function($q){$q->where('deleted_at',null);});
       
    }
}
