<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterTemplate extends Model
{
    protected $fillable = ['name', 'slug', 'subject', 'html_content', 'type', 'is_active'];

    public function sends()
    {
        return $this->hasMany(NewsletterSend::class, 'template_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
