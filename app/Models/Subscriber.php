<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
        'email',
        'name',
        'is_active',
        'subscribed_at',
        'verification_token',
        'unsubscribe_token',
        'verified_at',
        'token_expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscribed_at' => 'datetime',
        'verified_at' => 'datetime',
        'token_expires_at' => 'datetime',
    ];
}
