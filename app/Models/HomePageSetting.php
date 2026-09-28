<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePageSetting extends Model
{
    use HasFactory;

    protected $table = 'home_page_settings';

    protected $fillable = [
        'section_name',
        'is_enabled',
        'order',
        'items_count',
        'config',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'order' => 'integer',
        'items_count' => 'integer',
        'config' => 'array',
    ];
}
