<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdSlot extends Model
{
    protected $fillable = ['name', 'slug', 'location', 'ad_code', 'ad_type', 'manual_html', 'is_active'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByLocation($query, $location)
    {
        return $query->where('location', $location);
    }
}
