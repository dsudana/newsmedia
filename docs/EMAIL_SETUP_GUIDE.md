# 📧 Email Configuration Guide - NewsMedia

**Status:** This guide walks you through setting up email for production (newsletters, notifications, password resets).

---

## Option A: SendGrid (RECOMMENDED for Indonesian market)

### Why SendGrid?
- ✅ Reliable for Asia-Pacific region
- ✅ Good for high-volume newsletters
- ✅ $20/month starter plan
- ✅ Excellent documentation
- ✅ Built-in bounce/spam handling

### Step 1: Create SendGrid Account

1. Go to [sendgrid.com](https://sendgrid.com)
2. Sign up with your email
3. Verify email and complete account setup
4. **Do NOT send test emails yet** — need API key first

### Step 2: Generate API Key

1. Log into SendGrid dashboard
2. Go to **Settings** → **API Keys**
3. Click **Create API Key**
4. Name it: `NewsMedia Production`
5. Select **Full Access** permissions
6. Copy the key (starts with `SG.`)
7. **Save it securely** — you won't see it again

### Step 3: Verify Sending Domain

1. Go to **Settings** → **Sender Authentication**
2. Click **Verify a Domain**
3. Add your domain (e.g., `yourdomain.com`)
4. Add these DNS records to your domain registrar:

```
Type: CNAME
Name: sendgrid.net
Value: sendgrid.net

Type: CNAME
Name: em._domainkey.yourdomain.com
Value: em._domainkey.sendgrid.net

Type: CNAME
Name: s1._domainkey.yourdomain.com
Value: s1._domainkey.sendgrid.net
```

5. Return to SendGrid and click **Verify**
6. Wait 5-30 minutes for DNS propagation

### Step 4: Update Environment Configuration

Edit `.env.production`:

```env
MAIL_MAILER=sendgrid
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="NewsMedia"
SENDGRID_API_KEY=SG.your_actual_api_key_here
```

For local development (`.env`):

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

### Step 5: Test Email Sending

#### Test 1: Using Tinker (CLI)

```bash
php artisan tinker
```

Then run:

```php
Mail::raw('This is a test email from NewsMedia!', function($message) {
    $message->to('your-email@gmail.com')
            ->subject('NewsMedia Email Test');
});
```

Expected output: `true` or `Message sent` notification

#### Test 2: Send Newsletter

1. Log into admin panel
2. Go to **Newsletter** → **Templates**
3. Create a test template:
   - Name: "Welcome Email"
   - Type: "manual"
   - Subject: "Welcome to NewsMedia"
   - HTML: Simple HTML template with basic content
4. Go to **Newsletter** → **Send Email**
5. Select template and test subscriber list
6. Click Send

Expected: Email arrives in inbox within 30 seconds

#### Test 3: Monitor SendGrid Dashboard

1. Go to SendGrid **Activity** → **Email Activity**
2. See live email sends
3. Monitor **Bounce** and **Spam** rates
4. If spam rate > 5%, reduce email frequency

### Step 6: Production Verification

Before going live, test these scenarios:

1. **Newsletter subscription email** — User subscribes, gets confirmation
2. **Password reset email** — Admin creates user, reset works
3. **Error notification** — If configured, get error alerts
4. **Unsubscribe link** — Newsletter email has working unsubscribe

**Command to test all email paths:**

```bash
php artisan make:command SendTestEmails
```

Then create a notification test in tinker:

```php
// Test newsletter
use App\Models\NewsletterSubscriber;
$subscriber = NewsletterSubscriber::first();
Mail::raw('Test newsletter', fn($m) => $m->to($subscriber->email)->subject('Newsletter Test'));

// Test transactional
Mail::raw('Password reset link here', fn($m) => $m->to('admin@yourdomain.com')->subject('Password Reset'));
```

---

## Option B: Mailgun (Alternative)

### Why Mailgun?
- Good for variable email volume
- Pay-as-you-go pricing
- Excellent API
- Good deliverability in Asia

### Setup Steps

1. Go to [mailgun.com](https://mailgun.com)
2. Sign up and verify email
3. Add your domain to Mailgun
4. Copy your **Private API Key**
5. Update `.env.production`:

```env
MAIL_MAILER=mailgun
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAILGUN_DOMAIN=yourdomain.mailgun.org
MAILGUN_SECRET=key_xxxxxxxxxxxxxxxxxxxx
```

6. Add DNS records to your registrar (Mailgun provides exact records)
7. Test same way as SendGrid (tinker command above)

---

## Email Best Practices

### 1. Sender Reputation

- **Start slow:** Send 100 emails/day first week, scale up gradually
- **Monitor bounce rate:** Keep below 5%
- **Monitor spam rate:** Keep below 1%
- **Watch unsubscribe rate:** If >10% of emails, content needs work

### 2. Email Content

```html
<!-- Always include unsubscribe link -->
<a href="{{ $unsubscribeLink }}">Unsubscribe from this list</a>

<!-- Include sender info -->
<p>NewsMedia | youraddress@yourdomain.com | yourwebsite.com</p>

<!-- Avoid spam triggers -->
❌ ALL CAPS SUBJECT LINES
❌ "ACT NOW!!!" and exclamation marks
❌ Suspicious links or URL shorteners
✅ Clear subject lines
✅ Professional formatting
✅ Sender authentication (SPF/DKIM)
```

### 3. Email Templates

NewsMedia includes template system at `/admin/newsletter/templates`:

1. **Daily Newsletter:** Curated articles sent every morning
2. **Weekly Digest:** Top stories from the week
3. **Breaking News:** Urgent alerts when major story breaks

Test each template with small subscriber list first.

### 4. Database for Email Tracking

Newsletter sends are tracked in database:

```php
// Check email send status
NewsletterSend::where('status', 'pending')->get();

// Check failed sends
NewsletterSend::where('status', 'failed')->get();

// Statistics
NewsletterSend::selectRaw('COUNT(*) as total, COUNT(CASE WHEN status = "completed" THEN 1 END) as sent')
    ->first();
```

---

## Troubleshooting Email Issues

### Issue: Emails not sending (no errors)

**Check 1: Verify credentials**
```bash
php artisan tinker
> env('SENDGRID_API_KEY')
# Should show your API key
```

**Check 2: Check mail driver**
```bash
php artisan tinker
> config('mail.mailer')
# Should show 'sendgrid' not 'log'
```

**Check 3: Check logs**
```bash
tail -f storage/logs/laravel.log | grep -i mail
```

### Issue: Emails going to spam

**Solutions:**
1. Check SendGrid **Spam Reports** tab
2. Verify sender domain DKIM/SPF records
3. Add company logo to email template
4. Reduce email frequency
5. Monitor unsubscribe rate

### Issue: "Invalid API key" error

1. Verify API key copied correctly (no spaces/extra chars)
2. Regenerate API key in SendGrid dashboard
3. Update `.env.production` with new key
4. Restart web server: `systemctl restart php-fpm`

### Issue: Domain verification stuck

1. Double-check DNS records in registrar
2. Wait 30 minutes for DNS propagation
3. Use `nslookup` to verify:
```bash
nslookup sendgrid.net yourdomain.com
# Should show SendGrid MX records
```

---

## Email Quota Limits

| Plan | Monthly | Daily | Cost |
|------|---------|-------|------|
| SendGrid Free | 100 | 100 | Free |
| SendGrid Starter | 24,000 | 800 | $20 |
| SendGrid Pro | Unlimited | Unlimited | $80+ |
| Mailgun Base | 1,250 | Pay-as-go | $0.80/1K |

**Recommendation:** Start with SendGrid Starter ($20/month) for ~1,000 newsletter subscribers.

---

## Testing Checklist

Before marking email as "ready for production":

- [ ] API key working (tinker test successful)
- [ ] Sender domain verified (DNS records active)
- [ ] Newsletter template created and tested
- [ ] Test email arrives in inbox within 1 minute
- [ ] Unsubscribe link in email works
- [ ] Bounce rate < 5%
- [ ] Spam rate < 1%
- [ ] Email shows in SendGrid/Mailgun Activity
- [ ] Logo/branding shows correctly in email
- [ ] Links in email work and track correctly

---

## Quick Reference Commands

```bash
# Test SendGrid connection
php artisan tinker
Mail::raw('Test', fn($m) => $m->to('test@email.com')->subject('Test'));

# View email activity
tail -f storage/logs/laravel.log

# Clear cached config (after updating .env.production)
php artisan config:clear

# Restart PHP (needed after env change)
systemctl restart php-fpm

# Check current mail driver
php artisan config:show mail.mailer
```

---

## Support

If emails still don't work:

1. Check SendGrid/Mailgun dashboard for delivery reports
2. Review Laravel logs: `storage/logs/laravel.log`
3. Verify `.env.production` has correct syntax (no quotes in key value)
4. Test from another server to isolate firewall issues
5. Contact SendGrid/Mailgun support with delivery ID from activity log

---

**Status: ✅ Email configuration ready. Update credentials and test before production launch.**
