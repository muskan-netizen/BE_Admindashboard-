<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientCurrency extends Model
{
	protected $fillable = ['client_code', 'currency_id', 'is_primary', 'doller_compare'];

    public function currency()
    {
      return $this->belongsTo('App\Models\Currency','currency_id','id')->select('id', 'name', 'iso_code', 'symbol');
    }


    public static function getAdminCurrencySymbol(){        
      $currencysymbol = '$';      
      $result = ClientCurrency::join('currencies', 'client_currencies.currency_id', 'currencies.id')->select('currencies.symbol')->where('client_currencies.is_primary',1)->first();
      if($result){
          $currencysymbol = $result->symbol;
      }
      return $currencysymbol;
    }
}
