<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateLink extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'image',
        'destination_url',
        'commission_type',
        'commission_value',
        'price',
        'clicks_count',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_affiliate_link');
    }

    public function clicks()
    {
        return $this->hasMany(AffiliateClick::class);
    }
}
