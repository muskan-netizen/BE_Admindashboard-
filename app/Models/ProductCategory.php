<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
	/**
	 * Table has no surrogate `id` column (see migration create_product_categories_table).
	 * Using product_id as the key fixes updateOrCreate / updates (otherwise SQL uses `where id is null`).
	 */
	protected $primaryKey = 'product_id';

	public $incrementing = false;

	protected $keyType = 'int';

	protected $fillable = ['category_id','product_id'];

	public function product(){
	    return $this->hasOne('App\Models\Product', 'id', 'product_id'); 
	}
    public function cat(){
	    return $this->hasOne('App\Models\CategoryTranslation', 'category_id', 'category_id')->select('id', 'name', 'category_id'); 
	}
	public function categoryDetail(){
	    return $this->belongsTo('App\Models\Category', 'category_id', 'id')->whereNull('deleted_at'); 
	}
}
