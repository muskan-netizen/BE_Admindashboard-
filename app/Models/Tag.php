<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    
    public function primary(){
      $langData = $this->hasOne('App\Models\TagTranslation')->whereHas('primary');
      return $langData;
    }

    
    public function translations(){
      $langData = $this->hasMany('App\Models\TagTranslation');
      return $langData;
    }


}
