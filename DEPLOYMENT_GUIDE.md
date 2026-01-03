# 🚀 DEPLOYMENT GUIDE - E-MANAGER SAAS PLATFORM

## Complete Production Deployment Instructions

---

## 📋 **PRE-DEPLOYMENT CHECKLIST**

### ✅ Prerequisites
- [x] Domain purchased (e.g., emanager.com)
- [ ] SSL certificate ready (wildcard *.emanager.com)
- [ ] Production server provisioned
- [ ] MySQL 8+ installed
- [ ] PHP 8.1+ installed
- [ ] Composer installed
- [ ] Redis installed (optional but recommended)

---

## 🌐 **STEP 1: DOMAIN & DNS CONFIGURATION**

### Domain Setup

1. **Main Domain**
```
A Record: emanager.com → YOUR_SERVER_IP
```

2. **Wildcard Subdomain**
```
A Record: *.emanager.com → YOUR_SERVER_IP
CNAME: * → emanager.com
```

3. **Verify DNS**
```bash
# Test main domain
nslookup emanager.com

# Test subdomain
nslookup tenant1.emanager.com
```

---

## 🔒 **STEP 2: SSL CERTIFICATE**

### Option A: Let's Encrypt (Recommended - Free)

```bash
# Install Certbot
sudo apt-get update
sudo apt-get install certbot python3-certbot-apache

# Get wildcard certificate
sudo certbot certonly --manual \
  --preferred-challenges=dns \
  --email admin@emanager.com \
  --server https://acme-v02.api.letsencrypt.org/directory \
  --agree-tos \
  -d emanager.com \
  -d *.emanager.com

# Follow DNS verification instructions
# Add TXT record: _acme-challenge.emanager.com

# Verify installation
sudo certbot certificates

# Auto-renewal
sudo certbot renew --dry-run
```

### Option B: Commercial SSL

```bash
# Purchase wildcard SSL from provider
# Upload certificate files:
# - emanager.com.crt
# - emanager.com.key
# - emanager.com.ca-bundle
```

---

## 🖥️ **STEP 3: SERVER SETUP**

### Install Required Software

```bash
# Update system
sudo apt-get update && sudo apt-get upgrade -y

# Install Apache
sudo apt-get install apache2 -y

# Install PHP 8.1
sudo apt-get install php8.1 php8.1-cli php8.1-fpm php8.1-mysql \
  php8.1-curl php8.1-gd php8.1-mbstring php8.1-xml php8.1-zip \
  php8.1-bcmath php8.1-intl -y

# Install MySQL
sudo apt-get install mysql-server -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Redis (Optional)
sudo apt-get install redis-server -y

# Install Node.js (for frontend compilation)
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install nodejs -y
```

---

## 📁 **STEP 4: DEPLOY APPLICATION**

### Clone/Upload Project

```bash
# Navigate to web root
cd /var/www

# Upload project files (via FTP/SCP)
# Or clone from repository
git clone https://your-repo.com/e-manager.git

# Set ownership
sudo chown -R www-data:www-data /var/www/e-manager
sudo chmod -R 755 /var/www/e-manager
sudo chmod -R 775 /var/www/e-manager/storage
sudo chmod -R 775 /var/www/e-manager/bootstrap/cache
```

### Install Dependencies

```bash
cd /var/www/e-manager

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install NPM dependencies (if needed)
npm install
npm run build
```

---

## ⚙️ **STEP 5: ENVIRONMENT CONFIGURATION**

### Create Production .env

```bash
cd /var/www/e-manager
cp .env.example .env
nano .env
```

### Configure .env

```env
APP_NAME="E-Manager"
APP_ENV=production
APP_KEY=base64:GENERATE_WITH_php_artisan_key:generate
APP_DEBUG=false
APP_URL=https://emanager.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=emanager
DB_USERNAME=emanager_user
DB_PASSWORD=STRONG_PASSWORD_HERE

# eSewa Live Credentials
ESEWA_MERCHANT_ID=your_live_merchant_id
ESEWA_SECRET_KEY=your_live_secret_key
ESEWA_BASE_URL=https://esewa.com.np/epay

# Khalti Live Credentials
KHALTI_PUBLIC_KEY=your_live_public_key
KHALTI_SECRET_KEY=your_live_secret_key
KHALTI_BASE_URL=https://khalti.com/api/v2

# Gaaubesi Logistics
GAAUBESI_API_TOKEN=a321a34a4f891a94fb45b56f3b8b0bf95e57d11c
GAAUBESI_API_URL=https://api.gaaubesi.com

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=noreply@emanager.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@emanager.com

# Cache & Session (Use Redis in production)
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Generate Application Key

```bash
php artisan key:generate
```

---

## 🗄️ **STEP 6: DATABASE SETUP**

### Create Database & User

```bash
mysql -u root -p
```

```sql
-- Create database
CREATE DATABASE emanager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user with secure password
CREATE USER 'emanager_user'@'localhost' IDENTIFIED BY 'YOUR_STRONG_PASSWORD';

-- Grant privileges
GRANT ALL PRIVILEGES ON emanager.* TO 'emanager_user'@'localhost';
GRANT CREATE ON *.* TO 'emanager_user'@'localhost';
GRANT ALL PRIVILEGES ON `tenant_%`.* TO 'emanager_user'@'localhost';

FLUSH PRIVILEGES;
EXIT;
```

### Run Migrations

```bash
cd /var/www/e-manager

# Run main database migrations
php artisan migrate --force

# Seed subscription plans
php artisan db:seed --class=SubscriptionPlansSeeder

# Seed super admin
php artisan db:seed --class=SuperAdminSeeder

# Verify
mysql -u emanager_user -p emanager -e "SHOW TABLES;"
```

---

## 🌐 **STEP 7: APACHE CONFIGURATION**

### Create VirtualHost

```bash
sudo nano /etc/apache2/sites-available/emanager.conf
```

### VirtualHost Configuration

```apache
<VirtualHost *:80>
    ServerName emanager.com
    ServerAlias *.emanager.com
    
    Redirect permanent / https://emanager.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName emanager.com
    ServerAlias *.emanager.com
    
    DocumentRoot /var/www/e-manager/public
    
    <Directory /var/www/e-manager/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/emanager.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/emanager.com/privkey.pem
    
    # Logging
    ErrorLog ${APACHE_LOG_DIR}/emanager-error.log
    CustomLog ${APACHE_LOG_DIR}/emanager-access.log combined
    
    # PHP Configuration
    <FilesMatch \.php$>
        SetHandler "proxy:unix:/run/php/php8.1-fpm.sock|fcgi://localhost"
    </FilesMatch>
</VirtualHost>
```

### Enable Site & Modules

```bash
# Enable required modules
sudo a2enmod rewrite
sudo a2enmod ssl
sudo a2enmod proxy_fcgi
sudo a2enmod headers

# Enable site
sudo a2ensite emanager.conf

# Disable default site
sudo a2dissite 000-default.conf

# Test configuration
sudo apache2ctl configtest

# Restart Apache
sudo systemctl restart apache2
```

---

## 🔐 **STEP 8: SECURITY HARDENING**

### File Permissions

```bash
cd /var/www/e-manager

# Set correct ownership
sudo chown -R www-data:www-data .

# Set directory permissions
sudo find . -type d -exec chmod 755 {} \;

# Set file permissions
sudo find . -type f -exec chmod 644 {} \;

# Storage and cache writeable
sudo chmod -R 775 storage bootstrap/cache
```

### Disable Directory Listing

```bash
# Already in VirtualHost: Options -Indexes
```

### Hide Sensitive Files

```bash
# Create/update .htaccess in root
nano /var/www/e-manager/public/.htaccess
```

```apache
# Add these rules
<Files .env>
    Order allow,deny
    Deny from all
</Files>

<Files composer.json>
    Order allow,deny
    Deny from all
</Files>
```

---

## 🚀 **STEP 9: OPTIMIZE FOR PRODUCTION**

### Laravel Optimizations

```bash
cd /var/www/e-manager

# Clear all cache
php artisan optimize:clear

# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### PHP Optimizations

```bash
sudo nano /etc/php/8.1/fpm/php.ini
```

```ini
# Recommended production settings
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 20M
post_max_size = 25M
opcache.enable = 1
opcache.memory_consumption = 128
opcache.max_accelerated_files = 10000
opcache.revalidate_freq = 2
```

```bash
sudo systemctl restart php8.1-fpm
```

---

## 📊 **STEP 10: MONITORING & LOGGING**

### Setup Log Rotation

```bash
sudo nano /etc/logrotate.d/emanager
```

```
/var/www/e-manager/storage/logs/*.log {
    daily
    rotate 14
    compress
    delaycompress
    notifempty
    create 0644 www-data www-data
    sharedscripts
}
```

### Monitor Services

```bash
# Create monitoring script
sudo nano /usr/local/bin/emanager-monitor.sh
```

```bash
#!/bin/bash

# Check Apache
if ! systemctl is-active --quiet apache2; then
    echo "Apache is down!" | mail -s "ALERT: Apache Down" admin@emanager.com
    systemctl restart apache2
fi

# Check MySQL
if ! systemctl is-active --quiet mysql; then
    echo "MySQL is down!" | mail -s "ALERT: MySQL Down" admin@emanager.com
    systemctl restart mysql
fi

# Check disk space
DISK_USAGE=$(df -h / | tail -1 | awk '{print $5}' | sed 's/%//')
if [ $DISK_USAGE -gt 80 ]; then
    echo "Disk usage is ${DISK_USAGE}%" | mail -s "ALERT: High Disk Usage" admin@emanager.com
fi
```

```bash
chmod +x /usr/local/bin/emanager-monitor.sh

# Add to cron
crontab -e
# Add: */5 * * * * /usr/local/bin/emanager-monitor.sh
```

---

## 💾 **STEP 11: BACKUP STRATEGY**

### Automated Database Backup

```bash
sudo nano /usr/local/bin/emanager-backup.sh
```

```bash
#!/bin/bash

BACKUP_DIR="/backups/emanager"
DATE=$(date +%Y%m%d_%H%M%S)
RETENTION_DAYS=30

mkdir -p $BACKUP_DIR

# Backup main database
mysqldump -u emanager_user -p'YOUR_PASSWORD' emanager | gzip > $BACKUP_DIR/main_$DATE.sql.gz

# Backup all tenant databases
for DB in $(mysql -u emanager_user -p'YOUR_PASSWORD' -e "SHOW DATABASES LIKE 'tenant_%'" -s --skip-column-names); do
    mysqldump -u emanager_user -p'YOUR_PASSWORD' $DB | gzip > $BACKUP_DIR/${DB}_$DATE.sql.gz
done

# Delete old backups
find $BACKUP_DIR -name "*.sql.gz" -mtime +$RETENTION_DAYS -delete

# Upload to cloud (optional)
# aws s3 sync $BACKUP_DIR s3://your-bucket/emanager-backups/
```

```bash
chmod +x /usr/local/bin/emanager-backup.sh

# Schedule daily backup at 2 AM
crontab -e
# Add: 0 2 * * * /usr/local/bin/emanager-backup.sh
```

---

## ✅ **STEP 12: FINAL VERIFICATION**

### Test All Endpoints

```bash
# Test main site
curl -I https://emanager.com

# Test API
curl https://emanager.com/api/plans

# Test super admin
curl -I https://emanager.com/super/login

# Test signup
curl -I https://emanager.com/signup
```

### Test Tenant Creation

1. Visit: https://emanager.com/signup
2. Fill form and submit
3. Verify database created:
```bash
mysql -u emanager_user -p -e "SHOW DATABASES LIKE 'tenant_%';"
```

### Test Payment Gateways

1. Create test tenant
2. Wait for trial to expire
3. Test eSewa payment
4. Test Khalti payment

---

## 🎯 **POST-DEPLOYMENT CHECKLIST**

### Immediate Tasks
- [ ] Test main website loading
- [ ] Test super admin login
- [ ] Create test tenant via signup
- [ ] Verify tenant database created
- [ ] Test API endpoints
- [ ] Verify email sending
- [ ] Test SSL certificate
- [ ] Check all routes working

### Within 24 Hours
- [ ] Monitor error logs
- [ ] Check server resources
- [ ] Verify backups running
- [ ] Test payment webhooks
- [ ] Monitor tenant signups

### Within 1 Week
- [ ] Review performance metrics
- [ ] Optimize slow queries
- [ ] Setup monitoring alerts
- [ ] Configure CDN (if needed)
- [ ] Review security logs

---

## 🔧 **TROUBLESHOOTING**

### Common Issues

**Issue: Subdomain not resolving**
```bash
# Check DNS
nslookup tenant1.emanager.com

# Check Apache config
sudo apache2ctl -S

# Restart Apache
sudo systemctl restart apache2
```

**Issue: Database connection failed**
```bash
# Test connection
mysql -u emanager_user -p emanager

# Check .env credentials
cat .env | grep DB_

# Check Laravel can connect
php artisan tinker
>>> DB::connection()->getPdo();
```

**Issue: 500 Internal Server Error**
```bash
# Check error logs
tail -f /var/www/e-manager/storage/logs/laravel.log
tail -f /var/log/apache2/emanager-error.log

# Check permissions
ls -la /var/www/e-manager/storage

# Clear cache
php artisan optimize:clear
```

---

## 📞 **SUPPORT**

### Log Locations
```
Application: /var/www/e-manager/storage/logs/laravel.log
Apache: /var/log/apache2/emanager-error.log
MySQL: /var/log/mysql/error.log
```

### Useful Commands
```bash
# Check service status
sudo systemctl status apache2
sudo systemctl status mysql
sudo systemctl status redis

# View logs in real-time
tail -f /var/www/e-manager/storage/logs/laravel.log

# Check disk space
df -h

# Check memory
free -h

# Check processes
top
```

---

## 🎉 **DEPLOYMENT COMPLETE!**

### Your Platform is Live At:
- **Main Site:** https://emanager.com
- **Super Admin:** https://emanager.com/super/login
- **API:** https://emanager.com/api/plans

### Next Steps:
1. ✅ Test all functionality
2. ✅ Monitor for first 24-48 hours
3. ✅ Start marketing campaign
4. ✅ Begin onboarding vendors!

**Your multi-tenant SaaS platform is now live and ready for business! 🚀**







