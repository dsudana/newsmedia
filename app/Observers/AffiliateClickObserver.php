<?php

namespace App\Observers;

use App\Models\AffiliateClick;

class AffiliateClickObserver
{
    public function created(AffiliateClick $click)
    {
        $click->affiliateLink?->increment('clicks_count');
    }
}
