<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class Article extends Model
{
    use SoftDeletes, \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'meta_title',
        'meta_description',
        'schema_type',
        'status',
        'published_at',
        'scheduled_at',
        'views_count',
        'is_featured',
        'read_time',
        'word_count',
        'ai_provider',
        'seo_score',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'is_featured' => 'boolean',
        'read_time' => 'integer',
        'word_count' => 'integer',
        'seo_score' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = self::uniqueSlug($article->title);
            }
            if (!empty($article->content)) {
                $article->word_count = str_word_count(strip_tags($article->content));
                $article->read_time = max(1, ceil($article->word_count / 200));
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('title') && !$article->isDirty('slug')) {
                $article->slug = self::uniqueSlug($article->title, $article->id);
            }
            if ($article->isDirty('content')) {
                $article->word_count = str_word_count(strip_tags($article->content));
                $article->read_time = max(1, ceil($article->word_count / 200));
            }
        });

        static::saved(function ($article) {
            self::clearHomepageCache();
        });

        static::deleted(function ($article) {
            self::clearHomepageCache();
        });
    }

    private static function clearHomepageCache(): void
    {
        Cache::forget('homepage_latest_articles');
        Cache::forget('homepage_categories');
        Cache::forget('homepage_sidebar_categories');
        Cache::forget('homepage_sidebar_articles');
    }

    private static function uniqueSlug($title, $exceptId = null)
    {
        $slug = Str::slug($title);
        $count = self::where('slug', 'like', $slug . '%')
            ->when($exceptId, fn($q) => $q->where('id', '!=', $exceptId))
            ->count();

        return $count ? "{$slug}-" . Str::random(6) : $slug;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors for retnews template compatibility
    protected $appends = ['url', 'image', 'date', 'author'];

    public function getUrlAttribute()
    {
        return route('blog.show', $this->slug);
    }



    public function getImageAttribute(): string
    {
        if (
            empty($this->featured_image) ||
            str_contains($this->featured_image, 'placeholder')
        ) {
            return asset('images/placeholder.jpg');
        }

        return asset('storage/' . ltrim($this->featured_image, '/'));
    }

    public function getDateAttribute()
    {
        return $this->published_at?->format('M d, Y');
    }

    public function getAuthorAttribute()
    {
        return $this->user;
    }

    /**
     * Get sanitized content safe for display
     * Uses HTMLPurifier for XSS prevention - industry standard
     * Whitelist-based approach is more secure than regex-based
     */
    public function getSafeContent(): string
    {
        $config = [
            'HTML.Allowed' => 'p,br,strong,em,b,i,u,h1,h2,h3,h4,h5,h6,ul,ol,li,blockquote,pre,code,img[src|alt],a[href|title],table,thead,tbody,tr,th,td,div,span,hr,figure,figcaption',
            'URI.AllowedSchemes' => ['http' => true, 'https' => true, 'mailto' => true],
            'URI.SafeIframeRegexp' => '%^(?:https?:)?//(?:www\.)?(?:youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/|dailymotion\.com/embed/video/)%',
        ];

        return clean($this->content, $config);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function affiliateLinks()
    {
        return $this->belongsToMany(AffiliateLink::class, 'article_affiliate_link');
    }

    public function articleViews()
    {
        return $this->hasMany(ArticleView::class);
    }

    public function meta()
    {
        return $this->hasOne(ArticleMeta::class);
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'article_keyword');
    }

    public function faqs()
    {
        return $this->hasMany(ArticleFaq::class);
    }

    public function analytics()
    {
        return $this->hasMany(ArticleAnalytic::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeByCategory($query, $slug)
    {
        return $query->whereHas('category', fn($q) => $q->where('slug', $slug));
    }

    public function scopeByTag($query, $slug)
    {
        return $query->whereHas('tags', fn($q) => $q->where('slug', $slug));
    }

    public function scopeRecent($query)
    {
        return $query->orderByDesc('published_at');
    }
}
