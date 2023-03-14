<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxCategory extends Model
{
    public function taxRate(){
        if(checkColumnExists('tax_rates','square_tax_id')){
            return $this->hasMany('App\Models\TaxRateCategory', 'tax_cate_id', 'id')
       			->join('tax_rates as tr', 'tr.id', 'tax_rate_categories.tax_rate_id')
		       ->select('tr.id', 'tr.identifier', 'tr.is_zip', 'tr.zip_code', 'tr.zip_from', 'tr.zip_to', 'tr.state', 'tr.country', 'tr.tax_rate', 'tr.tax_rate', 'tr.tax_rate', 'tax_rate_categories.tax_cate_id', 'square_tax_id', 'square_tax_version');
        }else{
            return $this->hasMany('App\Models\TaxRateCategory', 'tax_cate_id', 'id')
       			->join('tax_rates as tr', 'tr.id', 'tax_rate_categories.tax_rate_id')
		       ->select('tr.id', 'tr.identifier', 'tr.is_zip', 'tr.zip_code', 'tr.zip_from', 'tr.zip_to', 'tr.state', 'tr.country', 'tr.tax_rate', 'tr.tax_rate', 'tr.tax_rate', 'tax_rate_categories.tax_cate_id');
        }
    }
}