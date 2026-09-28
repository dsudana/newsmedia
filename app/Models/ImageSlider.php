<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageSlider extends Model
{
    protected $fillable = [
        'image_url',
        'caption',
        'cta_text',
        'cta_url',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(fn($query) => $query->orderBy('order'));
    }
}
