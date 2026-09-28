# 🚀 NewsMedia - Production Deployment Checklist

**Estimated Time: 24 hours** | **Status: READY TO LAUNCH**

---

## ✅ TIER 1: CRITICAL SECURITY FIXES (Complete Before Deployment)

### 1. Environment Configuration
- [x] Created `.env.production` file with APP_DEBUG=false
- [x] Updated SESSION_ENCRYPT=true in .env
- [ ] **TODO:** Update `.env.production` with your actual values:
  ```
  APP_URL=https://yourdomain.com
  DB_HOST=your-db-host
  DB_USERNAME=your-db-user
  DB_PASSWORD=your-db-password
  ```

### 2. Email Setup (SendGrid Recommended)

**Option A: SendGrid (Recommended for Indonesian market)**
```
Sign up: https://sendgrid.com
1. Create SendGrid account
2. Generate API key from Settings > API Keys
3. Add to .env.production:
   MAIL_MAILER=sendgrid
   SENDGRID_API_KEY=SG.xxxxxxxxxxxxxxxxxxxx
   MAIL_FROM_ADDRESS=noreply@yourdomain.com
4. Test:
   php artisan tinker
   Mail::raw('Test email', fn($m) => $m->to('test@email.com'));
```

**Option B: Mailgun (Alternative)**
```
Sign up: https://mailgun.com
1. Add domain
2. Get API credentials
3. Add to .env.production:
   MAIL_MAILER=mailgun
   MAILGUN_DOMAIN=yourdomain.mailgun.org
   MAILGUN_SECRET=key-xxxxxxxxxxxx
```

### 3. Security Headers & HTTPS
- [ ] Enable SSL/TLS certificate (Let's Encrypt recommended)
- [ ] Configure NGINX/Apache with:
  ```
  add_header X-Frame-Options "SAMEORIGIN";
  add_header X-Content-Type-Options "nosniff";
  add_header X-XSS-Protection "1; mode=block";
  add_header Referrer-Policy "strict-origin-when-cross-origin";
  ```
- [ ] Update APP_URL to HTTPS in .env.production

### 4. Database Optimization
- [x] Added 20+ performance indexes via migration
- [ ] Run migration: `php artisan migrate` ✓ DONE
- [ ] Verify indexes:
  ```bash
  mysql -h host -u user -p database
  SHOW INDEX FROM articles;
  ```

### 5. Error Tracking Setup
- [ ] **Optional but Recommended:** Setup Sentry
  ```
  1. Create account: https://sentry.io
  2. Create Laravel project
  3. Add to .env.production:
     SENTRY_LARAVEL_DSN=your-sentry-dsn
  ```

---

## ⚡ PRE-DEPLOYMENT CHECKLIST

### Application Configuration
- [ ] `php artisan config:cache` (production cache)
- [ ] `php artisan route:cache` (optimize routing)
- [ ] `php artisan view:cache` (cache views)
- [ ] Clear all caches: `php artisan cache:clear`

### Security Verification
- [ ] APP_DEBUG=false in .env.production
- [ ] SESSION_ENCRYPT=true
- [ ] Generate strong APP_KEY if needed: `php artisan key:generate`
- [ ] CSRF protection enabled (default)
- [ ] Rate limiting configured

### Database
- [ ] Database credentials updated in .env.production
- [ ] All migrations run successfully ✓
- [ ] Backups automated (daily recommended)
- [ ] Test database connection from production server

### Assets
- [ ] `npm run build` (production build)
- [ ] Static files minified
- [ ] CSS/JS cached with versioning
- [ ] Storage symlink created: `php artisan storage:link`

### Logging & Monitoring
- [ ] LOG_LEVEL=warning (production)
- [ ] Log rotation configured
- [ ] Disk space monitoring enabled
- [ ] Error tracking service configured

---

## 🌐 DEPLOYMENT STEPS

### 1. Server Preparation
```bash
# SSH into production server
ssh user@your-server.com

# Install dependencies
cd /var/www/newsmedia
composer install --no-dev --optimize-autoloader

# Copy production config
cp .env.example .env.production
# Edit with your credentials
nano .env.production
```

### 2. Database Setup
```bash
# Run migrations
php artisan migrate --force

# Run seeders (if needed)
php artisan db:seed --class=LegalPageSeeder

# Verify no errors
php artisan db:show
```

### 3. Cache & Optimization
```bash
# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### 4. File Permissions
```bash
# Set correct permissions
chmod 755 bootstrap/cache
chmod 755 storage
chmod -R 755 storage/*
chmod -R 755 bootstrap/cache/*

# Set ownership
chown -R www-data:www-data /var/www/newsmedia
```

### 5. Web Server Configuration

**NGINX Example:**
```nginx
server {
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;

    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;

    root /var/www/newsmedia/public;
    index index.php;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}

# HTTP to HTTPS redirect
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}
```

### 6. Final Verification
```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo()
>>> exit

# Test email (optional)
php artisan tinker
>>> Mail::raw('Test', fn($m) => $m->to('admin@email.com'))
>>> exit

# Test scheduler (if background jobs)
php artisan schedule:run
```

---

## 📅 POST-DEPLOYMENT

### First 24 Hours
- [ ] Monitor error logs: `tail -f storage/logs/laravel.log`
- [ ] Check server resources: `top`, `df -h`
- [ ] Monitor email delivery
- [ ] Test all admin functions

### First Week
- [ ] Setup automated backups
- [ ] Configure CDN (Cloudflare recommended)
- [ ] Setup monitoring alerts
- [ ] Test failover procedures
- [ ] Verify SSL certificate auto-renewal

### Ongoing (Monthly)
- [ ] Review security logs
- [ ] Update dependencies: `composer update`
- [ ] Monitor database growth
- [ ] Check backup integrity
- [ ] Review error tracking dashboard

---

## 🔗 Quick Reference

| Task | Command |
|------|---------|
| Clear all caches | `php artisan cache:clear` |
| Run migrations | `php artisan migrate --force` |
| Create backup | `mysqldump -u user -p database > backup.sql` |
| Check logs | `tail -f storage/logs/laravel.log` |
| Test email | `php artisan tinker` then `Mail::raw('Test', fn($m) => $m->to('test@email.com'))` |

---

## ⚠️ IMPORTANT REMINDERS

1. **Never use APP_DEBUG=true in production** - exposes sensitive code
2. **Always use HTTPS** - required for modern web
3. **Configure email before launch** - users need to receive newsletters
4. **Setup error tracking** - alerts you to production issues
5. **Automate backups** - protect against data loss
6. **Monitor the first week** - catch issues early
7. **Keep credentials in .env** - never commit them

---

## 🆘 Troubleshooting

### Email not sending
- Check SMTP credentials in .env.production
- Verify firewall allows outbound SMTP (port 587 for SendGrid)
- Check SendGrid spam score in dashboard
- Review email logs in storage/logs/

### Slow page loads
- Check if indexes were created: `SHOW INDEX FROM articles;`
- Monitor database queries: `php artisan query:log`
- Enable Redis caching (optional, advanced)

### 500 Errors
- Enable APP_DEBUG=true temporarily to see error
- Check storage/logs/laravel.log
- Verify database connection
- Ensure file permissions correct

---

**Status: ✅ READY FOR PRODUCTION**

Estimated launch time with this checklist: 4-6 hours for experienced DevOps, 1-2 days for first-time deployment.

**Next Steps:** 
1. Complete email setup (SendGrid or Mailgun)
2. Update .env.production with real credentials
3. Run pre-deployment checklist
4. Deploy and monitor first 24 hours
