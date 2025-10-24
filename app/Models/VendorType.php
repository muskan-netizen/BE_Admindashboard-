<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorType extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'status',
        'order_by'
    ];

    protected $casts = [
        'status' => 'boolean',
        'order_by' => 'integer'
    ];
    


    public function getImageAttribute($value)
    {
        \Log::info($value);
      $values = array();
      $img = 'default/default_image.png';
      
      // Handle both string and array inputs
      if(!empty($value)){
        if(is_array($value)) {
          // If it's an array, extract the path from the array
          $img = $value['image_path'] ?? $value['path'] ?? $value;
        } else {
          $img = $value;
        }
      }
      
      $ex = checkImageExtension($img);
      $values['proxy_url'] = \Config::get('app.IMG_URL1');
      $values['image_path'] = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($img).$ex;
      $values['image_fit'] = \Config::get('app.FIT_URl');

      return $values;
    }

    // Scope for active vendor types
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Scope for ordering by order_by field
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_by', 'asc');
    }
}
