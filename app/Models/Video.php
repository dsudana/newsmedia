<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'title',
        'description',
        'youtube_url',
        'youtube_id',
        'thumbnail_url',
        'category_id',
        'order',
        'status',
        'views_count',
        'user_id',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public static function extractYoutubeId($url)
    {
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.*/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/|youtube\.com/embed/)([^"&?\s]{11})%i', $url, $match)) {
            return $match[1];
        }
        return null;
    }

    public static function getYoutubeThumbnail($youtubeId)
    {
        return "https://img.youtube.com/vi/{$youtubeId}/maxresdefault.jpg";
    }
}
