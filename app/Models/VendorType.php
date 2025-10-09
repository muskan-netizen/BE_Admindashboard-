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
