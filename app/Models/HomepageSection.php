<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'config' => 'array',
        'settings' => 'array',
        'status' => 'bool',
        'order' => 'int',
    ];

    protected $appends = ['type_label'];

    const SECTION_TYPES = [
        'article_carousel' => [
            'label' => 'Article Carousel',
            'icon' => 'carousel',
            'description' => 'Display articles in a carousel format',
            'color' => '#FF6B6B',
        ],
        'category_highlight' => [
            'label' => 'Category Highlight',
            'icon' => 'category',
            'description' => 'Highlight specific categories',
            'color' => '#4ECDC4',
        ],
        'newsletter' => [
            'label' => 'Newsletter Signup',
            'icon' => 'mail',
            'description' => 'Newsletter subscription section',
            'color' => '#45B7D1',
        ],
        'image_banner' => [
            'label' => 'Image Banner',
            'icon' => 'image',
            'description' => 'Large image banner with optional text overlay',
            'color' => '#FFA07A',
        ],
        'text_image_split' => [
            'label' => 'Text Image Split',
            'icon' => 'layout',
            'description' => 'Side-by-side text and image layout',
            'color' => '#98D8C8',
        ],
        'blog_preview' => [
            'label' => 'Blog Preview',
            'icon' => 'blog',
            'description' => 'Preview recent blog articles',
            'color' => '#F7DC6F',
        ],
        'testimonial' => [
            'label' => 'Testimonial',
            'icon' => 'quote',
            'description' => 'Customer testimonials section',
            'color' => '#BB8FCE',
        ],
        'lookbook' => [
            'label' => 'Lookbook',
            'icon' => 'gallery',
            'description' => 'Visual lookbook gallery',
            'color' => '#85C1E2',
        ],
        'breaking_news_strip' => [
            'label' => 'Breaking News Strip',
            'icon' => 'newspaper',
            'description' => 'Horizontal carousel with breaking news thumbnails',
            'color' => '#E74C3C',
        ],
        'recent_and_popular' => [
            'label' => 'Recent & Popular',
            'icon' => 'fire',
            'description' => 'Recent posts + popular posts in 2-column layout',
            'color' => '#9B59B6',
        ],
        'category_strip_carousel' => [
            'label' => 'Category Strip Carousel',
            'icon' => 'images',
            'description' => 'Category-specific article carousel',
            'color' => '#3498DB',
        ],
        'category_grid_section' => [
            'label' => 'Category Grid',
            'icon' => 'th-large',
            'description' => 'Grid layout for category articles',
            'color' => '#1ABC9C',
        ],
        'category_list_section' => [
            'label' => 'Category List',
            'icon' => 'list',
            'description' => 'Horizontal card list for category articles',
            'color' => '#F39C12',
        ],
        'sports_carousel' => [
            'label' => 'Sports Carousel',
            'icon' => 'futbol',
            'description' => 'Sports news carousel section',
            'color' => '#E67E22',
        ],
    ];

    /**
     * Scope: Get active sections only
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope: Get sections ordered by order column
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Scope: Get sections by page type
     */
    public function scopeByPage($query, $pageType)
    {
        return $query->where('page_type', $pageType);
    }

    /**
     * Accessor: Get the type label from SECTION_TYPES constant
     */
    public function getTypeLabelAttribute()
    {
        if (isset(self::SECTION_TYPES[$this->section_type])) {
            return self::SECTION_TYPES[$this->section_type]['label'];
        }

        return ucfirst(str_replace('_', ' ', $this->section_type));
    }
}
