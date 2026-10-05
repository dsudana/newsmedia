# 💾 Automated Backup Setup Guide - NewsMedia

**Critical:** Database backups protect against data loss. Setup automated backups immediately.

---

## Option A: Linux/Ubuntu Cron-Based Backups (Recommended)

### Step 1: Create Backup Directory

```bash
# SSH into production server
ssh user@your-server.com

# Create backup directory
mkdir -p /home/backups/newsmedia
cd /home/backups/newsmedia

# Set correct permissions
chmod 700 /home/backups/newsmedia
```

### Step 2: Create Backup Script

Create `/home/scripts/backup-newsmedia.sh`:

```bash
#!/bin/bash

# Configuration
DB_USER="newsmedia_user"
DB_PASS="your_db_password"
DB_NAME="newsmedia"
BACKUP_DIR="/home/backups/newsmedia"
KEEP_DAYS=30
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_FILE="$BACKUP_DIR/newsmedia_$TIMESTAMP.sql.gz"

# Create backup
echo "[$(date)] Starting database backup..."
mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" | gzip > "$BACKUP_FILE"

# Check if backup successful
if [ $? -eq 0 ]; then
    echo "[$(date)] ✅ Backup successful: $BACKUP_FILE"
    
    # Also backup storage directory
    tar -czf "$BACKUP_DIR/storage_$TIMESTAMP.tar.gz" /var/www/newsmedia/storage/app/public > /dev/null 2>&1
    
    # Delete backups older than KEEP_DAYS
    find "$BACKUP_DIR" -name "newsmedia_*.sql.gz" -mtime +$KEEP_DAYS -delete
    find "$BACKUP_DIR" -name "storage_*.tar.gz" -mtime +$KEEP_DAYS -delete
    
    echo "[$(date)] Old backups cleaned up (older than $KEEP_DAYS days)"
else
    echo "[$(date)] ❌ Backup failed!"
    # Send alert email
    echo "Database backup failed on $(hostname)" | mail -s "ALERT: Backup Failed" admin@yourdomain.com
fi
```

### Step 3: Make Script Executable

```bash
chmod +x /home/scripts/backup-newsmedia.sh

# Test it works
/home/scripts/backup-newsmedia.sh
```

### Step 4: Setup Cron Job

Edit crontab:

```bash
crontab -e
```

Add this line for **daily backup at 2:00 AM**:

```
0 2 * * * /home/scripts/backup-newsmedia.sh >> /var/log/newsmedia-backup.log 2>&1
```

For **hourly backups** (during high-traffic times):

```
0 * * * * /home/scripts/backup-newsmedia.sh >> /var/log/newsmedia-backup.log 2>&1
```

### Step 5: Verify Cron is Running

```bash
# Check cron log
grep backup /var/log/syslog | tail -20

# List cron jobs
crontab -l
```

---

## Option B: Cloud Backup (AWS S3)

### Why S3?
- ✅ Offsite storage (safer than local)
- ✅ Automatic replication
- ✅ Long-term retention
- ✅ $0.023 per GB/month

### Step 1: Create AWS S3 Bucket

1. Go to [AWS Console](https://console.aws.amazon.com)
2. Create S3 bucket: `newsmedia-backups-yourdomain`
3. Enable **Versioning** (keep old versions)
4. Set lifecycle policy: Delete backups after 90 days

### Step 2: Create IAM User

1. Go to **IAM** → **Users** → **Create User**
2. Name: `newsmedia-backup`
3. Attach policy: `AmazonS3FullAccess`
4. Create access key
5. Copy **Access Key ID** and **Secret Access Key**

### Step 3: Install AWS CLI

```bash
sudo apt-get update
sudo apt-get install awscli

# Configure credentials
aws configure
# Enter Access Key ID
# Enter Secret Access Key
# Region: ap-southeast-1 (Singapore, closest to Indonesia)
```

### Step 4: Create S3 Backup Script

Create `/home/scripts/backup-to-s3.sh`:

```bash
#!/bin/bash

# Configuration
DB_USER="newsmedia_user"
DB_PASS="your_db_password"
DB_NAME="newsmedia"
S3_BUCKET="s3://newsmedia-backups-yourdomain"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_FILE="/tmp/newsmedia_$TIMESTAMP.sql.gz"

# Create backup
mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" | gzip > "$BACKUP_FILE"

# Upload to S3
if [ $? -eq 0 ]; then
    aws s3 cp "$BACKUP_FILE" "$S3_BUCKET/databases/"
    
    # Also backup storage
    tar -czf "/tmp/storage_$TIMESTAMP.tar.gz" /var/www/newsmedia/storage/app/public > /dev/null 2>&1
    aws s3 cp "/tmp/storage_$TIMESTAMP.tar.gz" "$S3_BUCKET/storage/"
    
    # Cleanup local temp
    rm -f "$BACKUP_FILE" "/tmp/storage_$TIMESTAMP.tar.gz"
    
    echo "[$(date)] ✅ S3 backup successful"
else
    echo "[$(date)] ❌ Backup to S3 failed"
fi
```

### Step 5: Add to Cron

```bash
crontab -e
# Add:
0 2 * * * /home/scripts/backup-to-s3.sh >> /var/log/newsmedia-backup.log 2>&1
```

---

## Option C: Google Drive Backup

### Why Google Drive?
- ✅ 15GB free storage included
- ✅ Easy to access
- ✅ No credit card needed
- ⚠️ Slower than S3

### Setup

1. Install `gdrive` tool:
```bash
sudo apt-get install gdrive
```

2. Authenticate:
```bash
gdrive auth
# Follow link, copy auth code
```

3. Create backup script:
```bash
#!/bin/bash
DB_USER="newsmedia_user"
DB_PASS="your_db_password"
DB_NAME="newsmedia"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")

mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" | gzip | \
    gdrive upload --parent FOLDER_ID - --name "newsmedia_$TIMESTAMP.sql.gz"

echo "[$(date)] ✅ Google Drive backup complete"
```

4. Replace `FOLDER_ID` with your Google Drive folder ID

---

## Backup Recovery Procedures

### Restore from Local Backup

```bash
# List backups
ls -lh /home/backups/newsmedia/

# Decompress backup
gunzip newsmedia_20260720_020000.sql.gz

# Restore to database
mysql -u root -p newsmedia < newsmedia_20260720_020000.sql

# If database exists, drop first
mysql -u root -p -e "DROP DATABASE newsmedia; CREATE DATABASE newsmedia;"
mysql -u root -p newsmedia < newsmedia_20260720_020000.sql
```

### Restore from S3

```bash
# Download backup from S3
aws s3 cp s3://newsmedia-backups-yourdomain/databases/newsmedia_20260720_020000.sql.gz .

# Decompress and restore
gunzip newsmedia_20260720_020000.sql.gz
mysql -u root -p newsmedia < newsmedia_20260720_020000.sql
```

### Restore from Google Drive

```bash
# Find file
gdrive list --query "name contains 'newsmedia_20260720'"

# Download
gdrive download FILE_ID

# Restore
gunzip newsmedia_20260720_020000.sql.gz
mysql -u root -p newsmedia < newsmedia_20260720_020000.sql
```

---

## Backup Verification

### Verify Backups Are Running

```bash
# Check recent backups
ls -lt /home/backups/newsmedia | head -5

# Check file sizes (should be > 10MB for 107 articles)
du -h /home/backups/newsmedia/*.sql.gz

# Check backup log
tail -50 /var/log/newsmedia-backup.log
```

### Test Restore Process (Monthly)

1. Create test database:
```bash
mysql -u root -p -e "CREATE DATABASE newsmedia_test;"
```

2. Restore latest backup:
```bash
gunzip -c /home/backups/newsmedia/newsmedia_latest.sql.gz | mysql -u root -p newsmedia_test
```

3. Verify data:
```bash
mysql -u root -p newsmedia_test -e "SELECT COUNT(*) as articles FROM articles;"
# Should show: articles | 107
```

4. Drop test database:
```bash
mysql -u root -p -e "DROP DATABASE newsmedia_test;"
```

---

## Storage Requirements

| Component | Size | Backup Frequency |
|-----------|------|------------------|
| Database | ~50 MB | Daily |
| User uploads | ~1 GB | Daily |
| Logs | ~100 MB | Weekly |
| Total | ~1.5 GB | - |

**If keeping 30 days:** 1.5 GB × 30 = 45 GB storage needed

---

## Backup Checklist

- [ ] Backup script created and tested
- [ ] Cron job configured for daily backups
- [ ] Backups are actually running (check logs)
- [ ] Backup size is reasonable (> 10 MB for database)
- [ ] Test restore process successful
- [ ] Restore tested on different server
- [ ] Off-site backups configured (S3 or Google Drive)
- [ ] Backup logs monitored weekly
- [ ] Older backups are being deleted
- [ ] Alert email configured if backup fails

---

## Disaster Recovery Plan

**In case of data loss:**

1. **Immediate Actions (within 30 minutes):**
   - Stop web server: `systemctl stop nginx`
   - Download latest backup: `aws s3 cp s3://bucket/database.sql.gz .`
   - Restore database: `gunzip database.sql.gz && mysql -u root -p database < database.sql`

2. **Verification (30-60 minutes):**
   - Check article count matches
   - Test admin login works
   - Test homepage loads correctly
   - Check recent newsletter sends are present

3. **Data Loss Communication (if needed):**
   - If lost data, announce to users immediately
   - Estimate recovery time
   - Provide status updates every 30 minutes

**Goal:** Full recovery in < 2 hours

---

**Status: ✅ Backups are critical for production. Setup one method immediately.**
