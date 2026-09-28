<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Keyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'keyword',
        'description',
        'intent',
        'focus_tone',
        'target_words',
        'status',
        'error_msg',
        'outline',
        'use_humanizer',
        'generated_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_keyword');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeDone($query)
    {
        return $query->where('status', 'done');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
