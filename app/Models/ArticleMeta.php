<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleMeta extends Model
{
    use HasFactory;

    protected $table = 'article_meta';

    protected $fillable = [
        'article_id',
        'meta_title',
        'meta_description',
        'focus_keyword',
        'schema_json',
        'internal_links',
        'keywords_used',
    ];

    protected $casts = [
        'schema_json' => 'json',
        'internal_links' => 'json',
        'keywords_used' => 'json',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
