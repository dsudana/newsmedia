<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleAnalytic extends Model
{
    use HasFactory;

    protected $table = 'article_analytics';

    protected $fillable = [
        'article_id',
        'date',
        'views',
        'unique_visitors',
        'scroll_depth',
        'avg_time_on_page',
    ];

    protected $casts = [
        'date' => 'date',
        'views' => 'integer',
        'unique_visitors' => 'integer',
        'scroll_depth' => 'integer',
        'avg_time_on_page' => 'integer',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
