<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateClick extends Model
{
    protected $fillable = [
        'affiliate_link_id',
        'ip_address',
        'user_agent',
        'referrer',
    ];

    public function affiliateLink()
    {
        return $this->belongsTo(AffiliateLink::class);
    }
}
