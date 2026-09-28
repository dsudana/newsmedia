<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleFaq extends Model
{
    use HasFactory;

    protected $table = 'article_faqs';

    protected $fillable = [
        'article_id',
        'question',
        'answer',
        'order',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
