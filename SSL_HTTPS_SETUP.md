# 🔒 SSL/HTTPS Setup Guide - NewsMedia

**Critical:** HTTPS is mandatory for modern web. This guide uses Let's Encrypt (free, auto-renewing).

---

## Why HTTPS?

- ✅ Required for security (users, admin panel, passwords)
- ✅ Required for SEO (Google ranks HTTPS higher)
- ✅ Required for modern browsers (avoid "not secure" warning)
- ✅ Free with Let's Encrypt
- ✅ Auto-renews every 90 days

---

## Prerequisites

- Domain name (already pointing to your server)
- Ubuntu 20.04+ server
- NGINX web server
- Root or sudo access

---

## Step 1: Install Certbot

```bash
sudo apt-get update
sudo apt-get install certbot python3-certbot-nginx
```

### Verify installation:

```bash
certbot --version
# Should show: certbot 2.x.x
```

---

## Step 2: Request Certificate from Let's Encrypt

### For single domain:

```bash
sudo certbot certonly --nginx -d yourdomain.com
```

### For multiple domains (with www):

```bash
sudo certbot certonly --nginx -d yourdomain.com -d www.yourdomain.com
```

### For subdomain:

```bash
sudo certbot certonly --nginx -d news.yourdomain.com
```

**Certbot will ask:**
- Email for renewal notices (provide admin email)
- Agree to terms (type `a`)
- Accept sharing email (type `y`)

**After success, you'll see:**
```
Successfully received certificate.
Certificate is saved at: /etc/letsencrypt/live/yourdomain.com/fullchain.pem
Key is saved at: /etc/letsencrypt/live/yourdomain.com/privkey.pem
```

---

## Step 3: Configure NGINX for HTTPS

Edit your NGINX config: `/etc/nginx/sites-available/newsmedia`

Replace the entire config with:

```nginx
# Redirect HTTP to HTTPS
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    
    location /.well-known/acme-challenge/ {
        root /var/www/certbot;
    }
    
    location / {
        return 301 https://$server_name$request_uri;
    }
}

# HTTPS Server
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;

    # SSL Certificates
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;

    # SSL Configuration (A+ rating on SSL Labs)
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;

    # HSTS Header (force HTTPS for 1 year)
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Permissions-Policy "geolocation=(), microphone=(), camera=()" always;

    root /var/www/newsmedia/public;
    index index.php;

    # Logging
    access_log /var/log/nginx/newsmedia_access.log;
    error_log /var/log/nginx/newsmedia_error.log;

    # Laravel routing
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Block access to .env and other sensitive files
    location ~ /\. {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }

    # Cache static assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 365d;
        add_header Cache-Control "public, immutable";
    }
}

# Redirect www to non-www (optional)
server {
    listen 443 ssl http2;
    server_name www.yourdomain.com;
    
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    
    return 301 https://yourdomain.com$request_uri;
}
```

### Test NGINX config:

```bash
sudo nginx -t
# Should show: "syntax is ok" and "test is successful"
```

### Reload NGINX:

```bash
sudo systemctl reload nginx
```

---

## Step 4: Update Laravel Configuration

Edit `.env.production`:

```env
APP_URL=https://yourdomain.com
ASSET_URL=https://yourdomain.com

# Force HTTPS in Laravel
SESSION_SECURE_COOKIES=true
COOKIE_SECURE=true
COOKIE_HTTP_ONLY=true
COOKIE_SAME_SITE=lax
```

### For local development (`.env`):

```env
APP_URL=http://127.0.0.1:8000
SESSION_SECURE_COOKIES=false
COOKIE_SECURE=false
```

### Restart Laravel queue/cache:

```bash
php artisan config:cache
php artisan cache:clear
systemctl restart php-fpm
```

---

## Step 5: Setup Automatic Renewal

Let's Encrypt certificates expire every 90 days. Certbot handles renewal automatically.

### Verify auto-renewal is enabled:

```bash
sudo systemctl status certbot.timer
# Should show: "active (waiting)"
```

### Test renewal:

```bash
sudo certbot renew --dry-run
# Should show: "The following certs are not due for renewal"
```

### Manual renewal (if needed):

```bash
sudo certbot renew
```

---

## Step 6: Test Your HTTPS Setup

### Test 1: Browser Test

1. Go to `https://yourdomain.com`
2. Should load without warnings
3. Lock icon should show in address bar

### Test 2: SSL Labs A+ Rating

1. Go to [ssllabs.com/ssltest](https://www.ssllabs.com/ssltest/)
2. Enter your domain
3. Should get A+ rating (if config is correct)

### Test 3: Check Certificate

```bash
# Show certificate details
sudo certbot certificates

# Check expiration date
echo | openssl s_client -servername yourdomain.com -connect yourdomain.com:443 2>/dev/null | \
    openssl x509 -noout -dates
```

---

## Step 7: Update URLs in Database

Any hardcoded `http://` URLs need to be updated to `https://`:

```bash
# SSH into server
mysql -u newsmedia_user -p newsmedia

# Find any http:// URLs
SELECT COUNT(*) FROM articles WHERE featured_image LIKE 'http://%';

# Update to https://
UPDATE articles SET featured_image = REPLACE(featured_image, 'http://', 'https://');
UPDATE ad_slots SET manual_html = REPLACE(manual_html, 'http://', 'https://');
```

---

## Troubleshooting HTTPS

### Issue: Certificate not found

```bash
sudo certbot certificates
# If not listed, request again:
sudo certbot certonly --nginx -d yourdomain.com
```

### Issue: Mixed content warning

Means some resources are still `http://`. Find and fix:

```bash
# In Laravel views, use:
{{ asset('image.jpg') }}  <!-- Auto uses HTTPS -->

# Not hardcoded:
<img src="http://yourdomain.com/image.jpg">  <!-- BAD -->
```

### Issue: Renewal failed

```bash
# Check renewal logs
sudo tail -50 /var/log/letsencrypt/letsencrypt.log

# Try manual renewal
sudo certbot renew -v

# If DNS issue, verify domain points to server
dig yourdomain.com @8.8.8.8
```

### Issue: HSTS errors in browser

HSTS tells browser to always use HTTPS. If certificate expires:
1. Renew immediately
2. Wait 30 days for HSTS to expire in browsers
3. Or test in private/incognito window

---

## HTTPS Checklist

- [ ] Certificate installed from Let's Encrypt
- [ ] NGINX config updated with SSL paths
- [ ] NGINX config tested (`nginx -t`)
- [ ] HTTPS loads without warnings
- [ ] HTTP redirects to HTTPS
- [ ] Auto-renewal is active
- [ ] APP_URL updated to https://
- [ ] Database URLs updated if needed
- [ ] SSL Labs shows A or A+ rating
- [ ] Certificate expiration date > 30 days away

---

## Security Headers Explained

| Header | Purpose |
|--------|---------|
| Strict-Transport-Security | Force HTTPS for 1 year |
| X-Frame-Options | Prevent clickjacking attacks |
| X-Content-Type-Options | Prevent MIME-type sniffing |
| X-XSS-Protection | Enable browser XSS protection |
| Referrer-Policy | Control referrer info |
| Permissions-Policy | Disable dangerous APIs |

---

## Performance Impact

HTTPS is faster than HTTP on modern servers:

- HTTP/2 multiplexing reduces round-trips
- Session resumption faster
- Typical overhead: < 5ms per request

**Result:** Users notice faster loading, not slower.

---

## Certificate Monitoring

Set calendar reminder for:
- **30 days before expiration:** Manual check
- **7 days before expiration:** Warning alert
- **Certificate expiration:** Critical alert

Or use monitoring service:
```bash
# Install monitoring
echo "0 9 * * * certbot renew && systemctl reload nginx" | sudo crontab -
```

---

**Status: ✅ HTTPS setup complete. Your site is now secure for users and search engines.**

**Next Steps:**
1. Test at https://yourdomain.com
2. Verify no certificate warnings
3. Check SSL Labs rating (should be A+)
4. Monitor certificate expiration (30-day reminder)
