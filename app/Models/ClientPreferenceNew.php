<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientPreferenceNew extends Model
{
  protected $table = 'client_preferences_new';
    protected $fillable = ['client_code','gtag_id','fpixel_id'];

    public function domain()
    {
      return $this->belongsTo('App\Models\Client','client_code','code')->select('id', 'code', 'custom_domain');
    }
   

    public function client_detail()
    {
      return $this->belongsTo('App\Models\Client','client_code','code');
    }

    public function client_preference()
    {
      return $this->belongsTo('App\Models\Client','client_code','client_code');
    }

}