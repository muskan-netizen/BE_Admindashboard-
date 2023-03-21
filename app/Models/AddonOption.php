<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AddonOption extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['title','addon_id','position','price', 'square_modifier_option_id'];

    public function translation(){
       return $this->hasMany('App\Models\AddonOptionTranslation', 'addon_opt_id', 'id')->join('languages', 'addon_option_translations.language_id', 'languages.id')->select('addon_option_translations.id', 'addon_option_translations.title', 'addon_option_translations.addon_opt_id', 'addon_option_translations.language_id', 'languages.sort_code', 'languages.name'); 
    }

    public function translation_one()
    {
        return $this->hasOne('App\Models\AddonOptionTranslation', 'addon_opt_id', 'id');
    }
    public function translation_many()
    {
        return $this->hasMany('App\Models\AddonOptionTranslation', 'addon_opt_id', 'id');
    }
}