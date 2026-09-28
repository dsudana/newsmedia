<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SeoSetting extends Model
{
    protected $table = 'seo_settings';

    protected $fillable = [
        'page_name',
        'page_title',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'twitter_card',
        'structured_data',
        'index',
        'follow',
        'sitemap_priority',
        'sitemap_changefreq',
        'is_active',
    ];

    protected $casts = [
        'structured_data' => 'array',
        'index' => 'boolean',
        'follow' => 'boolean',
        'is_active' => 'boolean',
        'sitemap_priority' => 'float',
    ];

    /**
     * Get SEO settings for a page with caching
     */
    public static function getByPageName(string $pageName)
    {
        $cacheKey = "seo_settings:{$pageName}";
        $ttl = 3600; // 1 hour

        return Cache::remember($cacheKey, $ttl, function () use ($pageName) {
            return self::where('page_name', $pageName)
                ->where('is_active', true)
                ->first();
        });
    }

    /**
     * Clear cache when model is saved or deleted
     */
    protected static function booted()
    {
        static::saved(function ($model) {
            Cache::forget("seo_settings:{$model->page_name}");
        });

        static::deleted(function ($model) {
            Cache::forget("seo_settings:{$model->page_name}");
        });
    }

    /**
     * Get Open Graph meta tags as array
     */
    public function getOgTagsAttribute()
    {
        return [
            'og:title' => $this->og_title,
            'og:description' => $this->og_description,
            'og:image' => $this->og_image,
            'og:type' => $this->og_type,
        ];
    }

    /**
     * Get Twitter Card meta tags as array
     */
    public function getTwitterTagsAttribute()
    {
        return [
            'twitter:card' => $this->twitter_card,
            'twitter:title' => $this->twitter_title,
            'twitter:description' => $this->twitter_description,
            'twitter:image' => $this->twitter_image,
        ];
    }

    /**
     * Get robots meta tag
     */
    public function getRobotsTagAttribute()
    {
        $parts = [];
        if ($this->index) {
            $parts[] = 'index';
        } else {
            $parts[] = 'noindex';
        }

        if ($this->follow) {
            $parts[] = 'follow';
        } else {
            $parts[] = 'nofollow';
        }

        return implode(', ', $parts);
    }
}
