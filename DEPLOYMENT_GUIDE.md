# 🚀 RET NEWS - Deployment Guide

**Version:** 1.0  
**Last Updated:** July 15, 2026  
**Status:** Production Ready

---

## Table of Contents

1. [System Requirements](#system-requirements)
2. [Pre-Deployment Checklist](#pre-deployment-checklist)
3. [Installation Steps](#installation-steps)
4. [Database Setup](#database-setup)
5. [Environment Configuration](#environment-configuration)
6. [Security Configuration](#security-configuration)
7. [Performance Optimization](#performance-optimization)
8. [Monitoring & Logging](#monitoring--logging)
9. [Backup Strategy](#backup-strategy)
10. [Troubleshooting](#troubleshooting)

---

## System Requirements

### Minimum Requirements
- **PHP:** 8.3+
- **MySQL:** 8.0+ or PostgreSQL 12+
- **Node.js:** 18+ (for asset compilation)
- **Composer:** 2.0+
- **RAM:** 2GB
- **Disk Space:** 5GB

### Recommended Requirements
- **PHP:** 8.3+ with extensions:
  - `php-bcmath`
  - `php-ctype`
  - `php-fileinfo`
  - `php-json`
  - `php-mbstring`
  - `php-pdo`
  - `php-tokenizer`
  - `php-xml`
  - `php-zip`
  - `php-redis` (optional, for caching)
- **MySQL:** 8.0.23+
- **Node.js:** 20+ LTS
- **SSL Certificate:** Let's Encrypt or commercial
- **Mail Server:** SMTP configured

---

## Pre-Deployment Checklist

- [ ] Application code pushed to repository
- [ ] All tests passing (`php artisan test`)
- [ ] Environment variables configured
- [ ] Database backup strategy defined
- [ ] SSL certificate obtained
- [ ] Domain DNS records updated
- [ ] Email service configured
- [ ] Monitoring tools setup
- [ ] Team notified of deployment

---

## Installation Steps

### 1. Clone Repository

```bash
cd /var/www
git clone https://github.com/your-org/ret-news.git
cd ret-news
git checkout main  # or specific release tag
```

### 2. Install PHP Dependencies

```bash
composer install --optimize-autoloader --no-dev
```

### 3. Install Node Dependencies

```bash
npm ci  # Use ci for production (reproducible builds)
npm run build  # Compile assets
```

### 4. Set File Permissions

```bash
chown -R www-data:www-data /var/www/ret-news
chmod -R 755 /var/www/ret-news
chmod -R 775 /var/www/ret-news/storage
chmod -R 775 /var/www/ret-news/bootstrap/cache
```

### 5. Copy Environment File

```bash
cp .env.example .env
```

### 6. Generate Application Key

```bash
php artisan key:generate
```

### 7. Run Database Migrations

```bash
php artisan migrate --force
```

### 8. Seed Database (Optional)

```bash
php artisan db:seed
```

---

## Database Setup

### MySQL Setup

```sql
-- Create database
CREATE DATABASE ret_news CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user
CREATE USER 'ret_news_user'@'localhost' IDENTIFIED BY 'secure_password_here';

-- Grant privileges
GRANT ALL PRIVILEGES ON ret_news.* TO 'ret_news_user'@'localhost';
FLUSH PRIVILEGES;
```

### PostgreSQL Setup

```sql
-- Create database
CREATE DATABASE ret_news WITH ENCODING 'UTF8' LC_COLLATE 'en_US.UTF-8' LC_CTYPE 'en_US.UTF-8';

-- Create user
CREATE USER ret_news_user WITH PASSWORD 'secure_password_here';

-- Grant privileges
GRANT ALL PRIVILEGES ON DATABASE ret_news TO ret_news_user;
```

### Backup Strategy

```bash
# Daily backup script (cron job)
#!/bin/bash
BACKUP_DIR="/backups/ret-news"
DATE=$(date +%Y-%m-%d_%H-%M-%S)

# MySQL backup
mysqldump -u ret_news_user -p'password' ret_news | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Application files backup
tar -czf $BACKUP_DIR/app_$DATE.tar.gz /var/www/ret-news

# Keep only last 30 days
find $BACKUP_DIR -name "*.gz" -mtime +30 -delete
```

Add to crontab:
```bash
0 2 * * * /path/to/backup-script.sh
```

---

## Environment Configuration

### Critical Environment Variables

```env
# Application
APP_NAME="RET NEWS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://retnews.com
APP_TIMEZONE=Asia/Jakarta

# Security
APP_KEY=base64:your-generated-key-here
FORCE_HTTPS=true

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=ret_news
DB_USERNAME=ret_news_user
DB_PASSWORD=secure_password

# Cache & Session
CACHE_STORE=redis
SESSION_DRIVER=database
QUEUE_CONNECTION=redis

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@retnews.com
MAIL_FROM_NAME="RET NEWS"

# File Storage
FILESYSTEM_DISK=public
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=notice

# Optional: Error Tracking
# SENTRY_LARAVEL_DSN=https://your-key@sentry.io/your-project
```

---

## Security Configuration

### 1. File Permissions

```bash
# Restrict .env file
chmod 600 /var/www/ret-news/.env

# Restrict storage
chmod 750 /var/www/ret-news/storage
chmod 750 /var/www/ret-news/bootstrap/cache
```

### 2. Firewall Rules

```bash
# Allow HTTP & HTTPS only
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

### 3. SSL/TLS Certificate

```bash
# Using Let's Encrypt with Certbot
sudo apt-get install certbot python3-certbot-nginx
sudo certbot certonly --nginx -d retnews.com -d www.retnews.com

# Auto-renewal
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer
```

### 4. Nginx Configuration

```nginx
server {
    listen 443 ssl http2;
    server_name retnews.com www.retnews.com;

    ssl_certificate /etc/letsencrypt/live/retnews.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/retnews.com/privkey.pem;

    # Security headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "DENY" always;
    add_header X-XSS-Protection "1; mode=block" always;

    root /var/www/ret-news/public;
    index index.php;

    # Laravel routing
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    # Deny access to .env
    location ~ /\.env {
        deny all;
    }
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    server_name retnews.com www.retnews.com;
    return 301 https://$server_name$request_uri;
}
```

### 5. Database Security

```bash
# Remote access only for backups
# In MySQL config /etc/mysql/mysql.conf.d/mysqld.cnf:
bind-address = 127.0.0.1

# Strong passwords
ALTER USER 'ret_news_user'@'localhost' IDENTIFIED BY 'P@ssw0rd!SecurePass123';
```

---

## Performance Optimization

### 1. Enable Caching

```bash
# Install Redis
sudo apt-get install redis-server

# Configure Laravel caching
# In .env:
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Clear config cache regularly
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Database Optimization

```bash
# Create indexes (already in migrations, but verify)
ALTER TABLE articles ADD INDEX (slug);
ALTER TABLE articles ADD INDEX (category_id);
ALTER TABLE articles ADD INDEX (published_at);
ALTER TABLE articles ADD INDEX (status);

# Analyze tables
ANALYZE TABLE articles;
ANALYZE TABLE categories;
ANALYZE TABLE users;
```

### 3. PHP-FPM Configuration

```bash
# /etc/php/8.3/fpm/pool.d/www.conf
pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 20
pm.max_requests = 1000
```

### 4. Image Optimization

```bash
# Install image optimization tools
sudo apt-get install imagemagick jpegoptim optipng

# Configure in Laravel for automatic optimization
# Use middleware or queue for image processing
```

---

## Monitoring & Logging

### 1. Application Logging

```bash
# Check Laravel logs
tail -f /var/www/ret-news/storage/logs/laravel.log

# Log rotation
sudo apt-get install logrotate

# /etc/logrotate.d/ret-news
/var/www/ret-news/storage/logs/*.log {
    daily
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
}
```

### 2. System Monitoring

```bash
# Install monitoring tools
sudo apt-get install htop nethogs iotop

# Monitor PHP-FPM
systemctl status php8.3-fpm
```

### 3. Uptime Monitoring

```bash
# Setup with external service (UptimeRobot, New Relic, etc)
# Or use local monitoring:
*/5 * * * * curl -s https://retnews.com/api/health > /dev/null || mail -s "RET NEWS Down" admin@example.com
```

### 4. Error Tracking (Sentry)

```bash
# Install Sentry Laravel package
composer require sentry/sentry-laravel

# Publish configuration
php artisan sentry:publish --dsn=YOUR_DSN

# In .env
SENTRY_LARAVEL_DSN=https://your-key@sentry.io/your-project
```

---

## Backup Strategy

### Full Backup Procedure

```bash
#!/bin/bash
# Backup everything

BACKUP_DATE=$(date +%Y-%m-%d)
BACKUP_DIR="/backups/ret-news-full-$BACKUP_DATE"

mkdir -p $BACKUP_DIR

# Database
mysqldump -u ret_news_user -p'password' ret_news > $BACKUP_DIR/database.sql
gzip $BACKUP_DIR/database.sql

# Application
tar -czf $BACKUP_DIR/application.tar.gz \
    --exclude='.git' \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='storage/logs' \
    /var/www/ret-news

# Storage files
tar -czf $BACKUP_DIR/storage.tar.gz /var/www/ret-news/storage/app

# Verify
echo "Backup Size: $(du -h $BACKUP_DIR | tail -1)"

# Upload to remote storage
aws s3 sync $BACKUP_DIR s3://my-backup-bucket/ret-news/$BACKUP_DATE/
```

### Restore Procedure

```bash
# 1. Stop application
systemctl stop php8.3-fpm

# 2. Restore database
gunzip < /backups/database.sql.gz | mysql -u ret_news_user -p'password' ret_news

# 3. Restore application
rm -rf /var/www/ret-news/*
tar -xzf /backups/application.tar.gz -C /var/www

# 4. Restore storage
tar -xzf /backups/storage.tar.gz -C /var/www/ret-news

# 5. Fix permissions
chown -R www-data:www-data /var/www/ret-news
chmod -R 775 /var/www/ret-news/storage

# 6. Start application
systemctl start php8.3-fpm
```

---

## Troubleshooting

### Application not loading

```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Clear cache
php artisan cache:clear
php artisan config:clear

# Check permissions
ls -la storage/logs/
ls -la bootstrap/cache/
```

### Database connection error

```bash
# Test connection
php artisan tinker
> DB::connection()->getPdo()

# Check credentials in .env
cat .env | grep DB_
```

### Slow performance

```bash
# Check PHP-FPM status
systemctl status php8.3-fpm

# Check database queries
DEBUGBAR_ENABLED=true php artisan tinker

# Check Redis connection
redis-cli ping
```

### Email not sending

```bash
# Test email configuration
php artisan tinker
> Mail::raw('Test', function($m) { $m->to('test@example.com'); })

# Check SMTP credentials
telnet smtp.mailtrap.io 465
```

---

## Support & Contact

- **Documentation:** https://ret-news-docs.example.com
- **Support Email:** support@retnews.com
- **Issue Tracker:** https://github.com/your-org/ret-news/issues
- **Slack:** #ret-news-support

---

**Last Reviewed:** July 15, 2026  
**Next Review:** October 15, 2026
