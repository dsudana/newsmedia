<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SponsoredPost extends Model
{
    protected $fillable = [
        'user_id',
        'article_id',
        'title',
        'price',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
