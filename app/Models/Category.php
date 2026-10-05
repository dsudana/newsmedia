<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'meta_title',
        'meta_description',
        'icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function keywords()
    {
        return $this->hasMany(Keyword::class);
    }

    public function scopeActive(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->orderBy('order');
    }

    public function getDescriptionAttribute(?string $value)
    {
        if (empty($value) || $value === 'Imported from WordPress') {
            return "Berita tentang {$this->name}";
        }
        return $value;
    }
}
