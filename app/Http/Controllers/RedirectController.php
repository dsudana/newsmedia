<?php

namespace App\Http\Controllers;

use App\Models\AffiliateLink;
use App\Models\AffiliateClick;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function __invoke($slug, Request $request)
    {
        $link = AffiliateLink::where('slug', $slug)->where('is_active', true)->firstOrFail();

        // Track Click
        AffiliateClick::create([
            'affiliate_link_id' => $link->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
            'clicked_at' => now(),
        ]);

        return redirect($link->destination_url);
    }
}
