<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalPage extends Model
{
    protected $fillable = ['slug', 'title', 'content'];

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
