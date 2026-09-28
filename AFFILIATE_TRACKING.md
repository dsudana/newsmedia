# Affiliate Links & Click Tracking

## Overview

The Affiliate Links feature allows you to:

- Create trackable affiliate links for monetization
- Track clicks and conversions automatically
- Manage commission rates (fixed or percentage)
- Monitor performance metrics
- Integrate with articles for sponsored content

---

## How Click Tracking Works

### Architecture

```
User clicks article link with affiliate URL
         ↓
/go/{affiliate-slug} route triggered
         ↓
RedirectController::__invoke()
         ↓
✅ Create AffiliateClick record (IP, user agent, referrer)
✅ Increment AffiliateLink::clicks_count
         ↓
Redirect to destination URL
         ↓
Affiliate site receives traffic (tracks conversion)
```

### Automatic Click Increment

When a click is tracked:

1. **AffiliateClick record created** with:
   - `affiliate_link_id` — Which affiliate link was clicked
   - `ip_address` — Visitor's IP address (for analytics)
   - `user_agent` — Browser/device info
   - `referrer` — HTTP referer (where click came from)
   - `created_at` — Timestamp

2. **AffiliateLink::clicks_count incremented** automatically
   - Done via Model Observer (see: `app/Observers/AffiliateClickObserver.php`)
   - Ensures accurate click count on the main affiliate link record

---

## Creating Affiliate Links

### In Admin Panel

Go to **Admin → Affiliates → Create**

**Required Fields:**
- **Name** — Link name (e.g., "Amazon Associate - Laptop")
- **Destination URL** — Full URL to affiliate product/page (must be valid URL)
- **Commission Type** — `fixed` (flat amount) or `percentage` (% of sale)
- **Commission Value** — Amount or percentage (e.g., 50 or 5.5)

**Optional Fields:**
- **Custom Slug** — URL-friendly identifier (auto-generated from name if empty)
- **Active** — Toggle to enable/disable the link

### Auto-Generated Slug

If you don't provide a custom slug, it's auto-generated from the name:
- `Amazon Associate - Laptop` → `amazon-associate-laptop`
- URL becomes: `/go/amazon-associate-laptop`

### Example

```
Name: Booking.com Hotels
Destination: https://www.booking.com/?aid=123456
Commission: Percentage, 5.5%
Auto Slug: booking-com-hotels
Public URL: https://yoursite.com/go/booking-com-hotels
```

---

## Using Affiliate Links

### In Articles

Add affiliate links in your article content:

```html
<!-- Simple link in article -->
<a href="/go/booking-com-hotels">Book your hotel on Booking.com</a>

<!-- With tracking info in article -->
Check out <a href="/go/amazon-laptop-deals">our recommended laptops</a> 
on Amazon (we earn a small commission).
```

### In Article Metadata

Store affiliate link ID in article `metadata`:

```blade
<!-- In article show template -->
@if($article->affiliate_link_id)
    <div class="affiliate-disclosure">
        <p>This article contains affiliate links. We may earn a commission.</p>
        <a href="/go/{{ $article->affiliateLink->slug }}" class="btn btn-primary">
            Visit: {{ $article->affiliateLink->name }}
        </a>
    </div>
@endif
```

### Direct Redirect

Users can click the affiliate link:

```
User clicks: https://yoursite.com/go/booking-com-hotels
         ↓
Tracked: Click recorded with IP, user agent, referrer
         ↓
Redirected: https://www.booking.com/?aid=123456
```

---

## Tracking & Analytics

### View Click Data

**Admin Panel:**
Go to **Admin → Affiliates** to see:
- List of all affiliate links
- **Clicks Count** — Total clicks on each link
- **Commission Type & Value** — Payout structure
- **Status** — Active/Inactive

**Click Details:**

Click on any affiliate link to view:
- Total clicks
- Click history (IP, user agent, referrer)
- When each click occurred
- Affiliate details

### Database Queries

```bash
php artisan tinker

# Total clicks on specific affiliate
>>> $link = \App\Models\AffiliateLink::find(1);
>>> echo $link->clicks_count;

# All clicks for an affiliate
>>> $clicks = $link->clicks()->get();
>>> $clicks->each(fn($c) => echo $c->ip_address . ' - ' . $c->created_at . PHP_EOL);

# Clicks in date range
>>> $link->clicks()
>>>     ->whereBetween('created_at', ['2024-01-01', '2024-01-31'])
>>>     ->count();

# Top affiliate by clicks
>>> $top = \App\Models\AffiliateLink::withCount('clicks')
>>>     ->orderByDesc('clicks_count')
>>>     ->first();
>>> echo $top->name . ': ' . $top->clicks_count . ' clicks';
```

---

## Privacy & Compliance

### Data Collection

The system collects:
- ✅ IP address — For analytics/fraud detection
- ✅ User agent — For device tracking
- ✅ Referrer — For source tracking
- ❌ NO personal data (email, names, etc.)

### Privacy Notice

**Add to your site's Privacy Policy:**

```
Affiliate Links & Tracking:
- We use affiliate links for monetization
- Clicks are tracked anonymously (IP address stored)
- Click data is used only for analytics
- Third-party sites have their own privacy policies
- You can disable cookies in your browser settings
```

### GDPR Compliance

If your site targets EU users:
- ✅ Disclose affiliate relationships
- ✅ Include in Privacy Policy
- ✅ Get user consent for click tracking (optional but recommended)

---

## Troubleshooting

### Clicks Not Being Tracked

**Check these:**

1. **Redirect route working?**
   ```bash
   curl -L http://yoursite.com/go/your-slug
   # Should redirect to destination URL
   ```

2. **AffiliateClick records created?**
   ```bash
   php artisan tinker
   >>> \App\Models\AffiliateClick::count();
   >>> \App\Models\AffiliateClick::latest()->first();
   ```

3. **clicks_count not updating?**
   ```bash
   # Check if observer is registered
   >>> \App\Models\AffiliateLink::observe(\App\Observers\AffiliateClickObserver::class);
   # Then test a click
   ```

4. **Link inactive?**
   ```bash
   # Make sure is_active = 1
   php artisan tinker
   >>> $link->update(['is_active' => true]);
   ```

### Common Issues

| Issue | Cause | Fix |
|-------|-------|-----|
| 404 on `/go/{slug}` | Link doesn't exist or inactive | Check slug is correct, set is_active=1 |
| Clicks recorded but count stays 0 | Observer not registered | Restart server, check AppServiceProvider |
| Wrong redirect URL | URL typo | Edit affiliate link, verify URL format |
| No referrer data | Referrer-Policy header | Check HTTP headers in browser dev tools |

---

## Advanced Features

### Commission Calculation

Calculate potential earnings:

```php
// Fixed commission per click
$fixed_affiliate = \App\Models\AffiliateLink::find(1);
$earnings = $fixed_affiliate->clicks_count * $fixed_affiliate->commission_value;
// Example: 50 clicks × $2.50 = $125

// Percentage commission (needs sale amount data)
$percent_affiliate = \App\Models\AffiliateLink::find(2);
// Manual calculation when sale occurs
// $sale_amount = 100; // From affiliate partner
// $earnings = ($sale_amount * $percent_affiliate->commission_value) / 100;
// = ($100 × 5.5) / 100 = $5.50
```

### Click Filtering

Get clicks from specific source/device:

```bash
php artisan tinker

# Clicks from mobile users
>>> $link->clicks()
>>>     ->where('user_agent', 'like', '%Mobile%')
>>>     ->count();

# Clicks from specific referrer
>>> $link->clicks()
>>>     ->where('referrer', 'like', '%google%')
>>>     ->count();

# Clicks today
>>> $link->clicks()
>>>     ->whereDate('created_at', today())
>>>     ->count();
```

### Reports

Generate affiliate performance report:

```bash
php artisan tinker

# All affiliates with click counts
>>> \App\Models\AffiliateLink::withCount('clicks')
>>>     ->orderByDesc('clicks_count')
>>>     ->get()
>>>     ->each(fn($a) => echo 
>>>         $a->name . ': ' . 
>>>         $a->clicks_count . ' clicks, ' .
>>>         'Commission: ' . $a->commission_value . 
>>>         ' (' . $a->commission_type . ')' . PHP_EOL
>>>     );
```

---

## Best Practices

### ✅ DO:
- Disclose affiliate relationships clearly
- Place links naturally in relevant content
- Monitor click sources and performance
- Update commission info regularly
- Test redirect URLs before going live

### ❌ DON'T:
- Hide affiliate relationships
- Add too many links per article (hurts user experience)
- Use misleading link text
- Direct users without warning (say "external link")
- Store sensitive data in referrer field

---

## Testing Click Tracking

### Manual Test

```bash
# Open terminal and test the redirect
curl -v "http://127.0.0.1:8000/go/your-affiliate-slug"

# Should show:
# Location: https://destination-url.com
# HTTP 302 Redirect
```

### Verify Click Recorded

```bash
php artisan tinker

# Check if click was saved
>>> $link = \App\Models\AffiliateLink::first();
>>> echo "Clicks before: " . $link->clicks_count . PHP_EOL;

# Simulate a click (in real use, user clicks link)
>>> \App\Models\AffiliateClick::create([
>>>     'affiliate_link_id' => $link->id,
>>>     'ip_address' => '192.168.1.1',
>>>     'user_agent' => 'Test Browser'
>>> ]);

# Check count incremented
>>> echo "Clicks after: " . $link->fresh()->clicks_count . PHP_EOL;
```

---

## FAQ

**Q: Do I need to set up the Anthropic API key for this feature?**
A: No! Affiliate tracking works independently. AI keyword generation is a separate feature.

**Q: How long are click records kept?**
A: Forever (or until manually deleted). Consider archiving old data if table gets large.

**Q: Can I export affiliate data?**
A: Yes, query the database or use Laravel's export features to generate reports.

**Q: What if I change the destination URL?**
A: Old clicks still reference the old URL. Changing the URL only affects new clicks.

**Q: How accurate is click tracking?**
A: Very accurate for raw clicks. Conversions depend on the affiliate program's tracking.

---

## Support

For issues:
1. Check if link is active and slug is correct
2. Review redirect logs: `storage/logs/laravel.log`
3. Test with curl: `curl -v /go/slug`
4. Check database: `affiliate_clicks` table for records

See `AFFILIATE_LINKS.md` for full feature documentation.
