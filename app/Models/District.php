<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function villages()
    {
        return $this->hasMany(Village::class)->orderBy('sort_order');
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
