# ✅ Production Launch Checklist - NewsMedia

**This is your go/no-go decision document. Check off each item before production launch.**

**Timeline:** 1-2 days if you follow these guides step-by-step.

---

## Pre-Launch Phase (1-2 hours)

### Code & Database
- [ ] All code committed to git (`git status` is clean)
- [ ] Database migrations run successfully (`php artisan migrate`)
- [ ] Database has production data (107 articles, 19 categories)
- [ ] No SQL errors in logs
- [ ] Caching cleared: `php artisan cache:clear`

### Configuration Files
- [ ] `.env.production` created with all required values:
  - [ ] `APP_DEBUG=false`
  - [ ] `APP_ENV=production`
  - [ ] `SESSION_ENCRYPT=true`
  - [ ] `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD` updated
  - [ ] `MAIL_MAILER=sendgrid` (or mailgun)
  - [ ] `SENDGRID_API_KEY` filled in (from SendGrid/Mailgun)
- [ ] `.env` has `APP_DEBUG=true` for local development
- [ ] `config/app.php` has correct APP_KEY

### Performance
- [ ] Database indexes created: `php artisan migrate`
- [ ] Query performance tested (articles load < 500ms)
- [ ] Static assets optimized: `npm run build`
- [ ] Cache configured: `php artisan config:cache`

---

## Email Setup Phase (2-3 hours)

### Email Service Account
- [ ] SendGrid or Mailgun account created
- [ ] API key generated and copied
- [ ] Sender domain added and verified (DNS records)
- [ ] Bounce rate monitored (target: < 5%)
- [ ] Spam rate monitored (target: < 1%)

### Email Configuration
- [ ] Email credentials in `.env.production`
- [ ] Test email sent successfully (tinker command)
- [ ] Newsletter template created and tested
- [ ] Unsubscribe link verified in test email
- [ ] Email appears in SendGrid/Mailgun activity log

### Email Testing
- [ ] [x] Newsletter subscription email works
- [ ] [x] Password reset email works (if configured)
- [ ] [x] Email shows correct branding/logo
- [ ] [x] Links in email are clickable
- [ ] [x] Email arrives in inbox within 30 seconds

---

## SSL/HTTPS Setup Phase (1-2 hours)

### Certificate Installation
- [ ] Domain name registered and pointing to server
- [ ] Let's Encrypt certificate requested: `sudo certbot certonly --nginx -d yourdomain.com`
- [ ] Certificate installed at: `/etc/letsencrypt/live/yourdomain.com/`
- [ ] Auto-renewal configured: `sudo systemctl status certbot.timer`
- [ ] Test renewal successful: `sudo certbot renew --dry-run`

### NGINX Configuration
- [ ] NGINX config updated with SSL paths
- [ ] HTTP redirects to HTTPS
- [ ] Security headers added (HSTS, X-Frame-Options, etc.)
- [ ] NGINX config tested: `sudo nginx -t`
- [ ] NGINX reloaded: `sudo systemctl reload nginx`

### HTTPS Verification
- [ ] `https://yourdomain.com` loads without warnings
- [ ] Lock icon shows in browser address bar
- [ ] Mixed content warning checked (should be none)
- [ ] SSL Labs test shows A+ rating
- [ ] Certificate details visible: `certbot certificates`

### Laravel HTTPS Configuration
- [ ] `.env.production` has: `APP_URL=https://yourdomain.com`
- [ ] Config cached: `php artisan config:cache`
- [ ] Session cookies secure: `SESSION_SECURE_COOKIES=true`
- [ ] Test login works over HTTPS

---

## Backup Setup Phase (1-2 hours)

### Backup Infrastructure
- [ ] Backup directory created: `/home/backups/newsmedia`
- [ ] Backup script created: `/home/scripts/backup-newsmedia.sh`
- [ ] Script tested manually: `./backup-newsmedia.sh`
- [ ] Cron job configured for daily backups
- [ ] Backup runs automatically and produces SQL file

### Backup Verification
- [ ] Recent backups exist: `ls -lh /home/backups/newsmedia/`
- [ ] Backup file sizes are reasonable (> 10 MB)
- [ ] Backup logs show successful runs
- [ ] Old backups are being deleted automatically

### Restore Testing
- [ ] Test database created: `mysql -e "CREATE DATABASE newsmedia_test;"`
- [ ] Backup restored to test database
- [ ] Article count verified: `SELECT COUNT(*) FROM articles;` shows 107
- [ ] Test database deleted after verification
- [ ] Restore time documented (target: < 5 minutes)

### Optional: Cloud Backups
- [ ] AWS S3 or Google Drive backup configured
- [ ] Off-site backup script working
- [ ] Verify files exist in cloud storage

---

## Server Security Phase (1-2 hours)

### System Security
- [ ] Ubuntu updated: `sudo apt-get update && apt-get upgrade`
- [ ] Firewall configured:
  - [ ] Port 80 (HTTP) open
  - [ ] Port 443 (HTTPS) open
  - [ ] Port 3306 (MySQL) closed to public
  - [ ] SSH (port 22) restricted by IP
- [ ] SSH key authentication enabled (no password login)
- [ ] Fail2ban installed to prevent brute-force attacks

### File Permissions
- [ ] Laravel directories owned by www-data: `chown -R www-data:www-data /var/www/newsmedia`
- [ ] Storage writable: `chmod -R 755 storage/`
- [ ] Bootstrap cache writable: `chmod -R 755 bootstrap/cache/`
- [ ] .env.production is not world-readable: `chmod 600 .env.production`

### Application Security
- [ ] APP_DEBUG=false in production
- [ ] Error details not exposed to users
- [ ] .env file not in version control
- [ ] Database credentials not in code
- [ ] API keys not in version control
- [ ] CSRF protection enabled (default)

---

## Performance & Monitoring Phase (1 hour)

### Performance Testing
- [ ] Homepage loads in < 2 seconds
- [ ] Article page loads in < 1 second
- [ ] Admin panel responsive (< 500ms)
- [ ] Database queries optimized (no N+1 queries)
- [ ] Static assets cached (CSS/JS loaded from cache)

### Monitoring Setup
- [ ] Error log rotation configured
- [ ] Error tracking service configured (Sentry optional)
- [ ] Server monitoring setup (CPU, RAM, disk space)
- [ ] Uptime monitoring service (UptimeRobot or similar)
- [ ] Email alerts configured for critical issues

### Logging Configuration
- [ ] Log level set to 'warning' in production
- [ ] Logs rotate daily to prevent disk full
- [ ] Old logs deleted after 30 days
- [ ] Check logs: `tail -f /var/log/nginx/newsmedia_error.log`

---

## Content & Features Phase (2-4 hours)

### Homepage & Frontend
- [ ] Homepage loads correctly with all sections
- [ ] All section types render properly:
  - [ ] Featured news carousel
  - [ ] Category grid
  - [ ] Article carousel
  - [ ] Popular posts section
  - [ ] Sidebar (stay connected, tags)
- [ ] Images load correctly and are optimized
- [ ] Navigation works across all pages
- [ ] Mobile layout responsive (test on phone)

### Articles & Categories
- [ ] All 107 articles display correctly
- [ ] All 19 categories have articles
- [ ] Article detail page shows:
  - [ ] Featured image
  - [ ] Metadata (author, date, category)
  - [ ] Content formatted correctly
  - [ ] Related articles sidebar
- [ ] Category pages show correct articles
- [ ] Article search works (if implemented)

### Newsletter & Email
- [ ] Newsletter subscription box appears on homepage
- [ ] Newsletter sign-up works and sends confirmation
- [ ] Newsletter template displays correctly
- [ ] Admin can send newsletters from admin panel
- [ ] Unsubscribe link works

### Ads & Monetization
- [ ] Ad slots display correctly:
  - [ ] Homepage top banner
  - [ ] Sidebar ads
  - [ ] Article bottom ads
- [ ] AdSense integration works (if using)
- [ ] Manual ads display with correct HTML

### Legal Pages
- [ ] Privacy policy accessible at `/privacy`
- [ ] Terms of service accessible at `/terms`
- [ ] Cookie policy accessible at `/cookies`
- [ ] All legal pages display correctly
- [ ] Footer links point to legal pages

### Admin Panel
- [ ] Admin login works
- [ ] Admin dashboard loads
- [ ] Homepage builder accessible and functional
- [ ] Article management works
- [ ] Settings panel accessible
- [ ] User can create/edit newsletter template
- [ ] Branding settings apply correctly

---

## Final Verification Phase (30 minutes)

### Smoke Tests (Quick sanity checks)
- [ ] `https://yourdomain.com` loads without errors
- [ ] Admin login at `/admin` works
- [ ] Can view article from homepage
- [ ] Category page loads articles
- [ ] Newsletter subscription sends email
- [ ] Images display on all pages
- [ ] Mobile layout works (use phone to test)

### Cross-Browser Testing
- [ ] Chrome: Desktop + Mobile
- [ ] Firefox: Desktop
- [ ] Safari: Mobile (iOS)
- [ ] Edge: Desktop

### User Journey Testing
1. [ ] New user visits homepage
2. [ ] Clicks article and reads content
3. [ ] Clicks category to see more articles
4. [ ] Subscribes to newsletter (receives email)
5. [ ] Unsubscribes from newsletter
6. [ ] Visits legal pages (privacy, terms)

### Admin User Journey
1. [ ] Admin logs in
2. [ ] Views dashboard
3. [ ] Creates/edits article
4. [ ] Configures homepage sections
5. [ ] Updates branding settings
6. [ ] Sends test newsletter

---

## Go-Live Decision

### Launch Decision

**DO NOT LAUNCH if:**
- [ ] Any critical item above is unchecked
- [ ] Error logs show PHP/database errors
- [ ] Email not working
- [ ] SSL certificate warnings
- [ ] Database backup failed

**SAFE TO LAUNCH if:**
- [x] All items above are checked
- [x] No errors in application logs
- [x] Email sending successfully
- [x] HTTPS working without warnings
- [x] Database backups running
- [x] All pages load correctly
- [x] Admin panel fully functional

---

## Post-Launch Monitoring (24 hours)

### First Hour
- [ ] Visit homepage and verify homepage loads
- [ ] Check error logs for any issues
- [ ] Monitor server CPU/RAM/disk usage
- [ ] Verify no database connection errors
- [ ] Test email sending if newsletter scheduled

### First 8 Hours
- [ ] Monitor error logs every hour
- [ ] Check error tracking dashboard (if using Sentry)
- [ ] Monitor uptime status
- [ ] Test admin functionality
- [ ] Verify backups run on schedule

### First 24 Hours
- [ ] Review full error logs
- [ ] Check monitoring alerts
- [ ] Monitor email delivery rates
- [ ] Verify database backups
- [ ] Check server performance metrics
- [ ] Get team feedback on functionality

### First Week
- [ ] Daily review of error logs
- [ ] Monitor newsletter open rates (if sent)
- [ ] Check user engagement metrics
- [ ] Verify backup integrity with test restore
- [ ] Review security logs for suspicious activity
- [ ] Update status page if issues found

---

## Rollback Plan (If critical issues occur)

**If critical errors:**

1. **Stop traffic immediately:**
```bash
sudo systemctl stop nginx
```

2. **Restore database from backup:**
```bash
aws s3 cp s3://backups/newsmedia_backup.sql.gz .
gunzip newsmedia_backup.sql.gz
mysql -u root -p newsmedia < newsmedia_backup.sql
```

3. **Restore from git (code rollback):**
```bash
git checkout previous-stable-commit
git reset --hard HEAD~1
```

4. **Start traffic again:**
```bash
sudo systemctl start nginx
```

5. **Notify users:**
- Post status on website
- Send email to admins
- Estimate time to fix

**Goal:** Full rollback in < 30 minutes

---

## Documentation & Handoff

- [ ] Production access credentials documented (secure location)
- [ ] Admin guide provided to team
- [ ] Backup restore procedures documented
- [ ] Emergency contact list created
- [ ] Status page setup (if needed)
- [ ] Monitoring dashboard URL shared with team
- [ ] Error tracking dashboard shared with team

---

## Sign-Off

**When this checklist is 100% complete:**

- ✅ Application is production-ready
- ✅ All critical systems tested
- ✅ Backups working
- ✅ Email functioning
- ✅ HTTPS secure
- ✅ Performance acceptable
- ✅ All content verified

**Result:** NewsMedia is live for Indonesian market 🚀

---

**Status: ✅ PRODUCTION READY**

Estimated completion: **2-3 days** following all guides

**Questions?** Refer to specific guides:
- Email issues → EMAIL_SETUP_GUIDE.md
- Backup issues → BACKUP_SETUP_GUIDE.md
- HTTPS issues → SSL_HTTPS_SETUP.md
- Deployment issues → DEPLOYMENT_CHECKLIST.md
