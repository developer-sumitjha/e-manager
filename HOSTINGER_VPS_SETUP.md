# 🚀 Hostinger VPS Setup Guide - Vendor Login Subdomain Fix

This guide provides step-by-step terminal commands to fix the vendor login subdomain validation issue on your Hostinger VPS.

---

## 📋 Prerequisites

- SSH access to your Hostinger VPS
- Root or sudo access
- Your domain name
- Path to your Laravel project

---

## 🔧 Step 1: Connect to Your VPS

```bash
ssh root@your-vps-ip
# or
ssh your-username@your-vps-ip
```

---

## 🔍 Step 2: Navigate to Your Project Directory

```bash
# Find your project directory (common locations)
cd /var/www/e-manager
# or
cd /home/username/e-manager
# or
cd /var/www/html/e-manager

# Verify you're in the right directory
pwd
ls -la
```

---

## 📥 Step 3: Pull Latest Changes from GitHub

```bash
# Make sure you're in the project root
cd /path/to/your/e-manager

# Pull latest changes
git pull origin main
# or
git pull origin master

# Verify the AuthController was updated
cat app/Http/Controllers/Vendor/AuthController.php | grep -A 5 "Extract subdomain"
```

---

## 🧪 Step 4: Test Hostname Extraction

First, let's test what hostname Laravel is receiving:

```bash
# Visit this URL in your browser (replace with your domain):
# https://vendor1.yourdomain.com/test-hostname
# https://vendor2.yourdomain.com/test-hostname

# Or test via curl from the server
curl -H "Host: vendor1.yourdomain.com" https://yourdomain.com/test-hostname
```

**Expected output should show:**
- `extracted_subdomain`: Should show the subdomain (e.g., "vendor1")
- `is_production_subdomain`: Should be `true` for production domains

---

## 📝 Step 5: Check Laravel Logs

```bash
# Navigate to project directory
cd /path/to/your/e-manager

# View real-time logs
tail -f storage/logs/laravel.log

# Or view last 100 lines
tail -n 100 storage/logs/laravel.log

# Search for vendor login debug logs
grep "Vendor Login" storage/logs/laravel.log | tail -20
```

**What to look for:**
- `Vendor Login - Hostname Debug`: Shows what hostname Laravel receives
- `Vendor Login - Subdomain Extraction`: Shows extracted subdomain
- `Vendor Login - Subdomain Validation`: Shows validation results

---

## ⚙️ Step 6: Verify Nginx Configuration

```bash
# Find your Nginx config file
sudo find /etc/nginx -name "*yourdomain*" -type f
# or
ls -la /etc/nginx/sites-available/
ls -la /etc/nginx/sites-enabled/

# Edit your Nginx config
sudo nano /etc/nginx/sites-available/yourdomain
# or
sudo nano /etc/nginx/sites-enabled/yourdomain
```

**Verify your PHP-FPM location block includes:**

```nginx
location ~ \.php$ {
    try_files $uri =404;
    fastcgi_split_path_info ^(.+\.php)(/.+)$;
    fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    
    # IMPORTANT: Ensure host header is passed correctly
    fastcgi_param HTTP_HOST $host;
    fastcgi_param SERVER_NAME $host;
    
    include fastcgi_params;
    
    fastcgi_read_timeout 300;
    fastcgi_send_timeout 300;
}
```

**If missing, add these lines:**
```bash
# Edit the file
sudo nano /etc/nginx/sites-available/yourdomain

# Add these lines in the PHP-FPM location block:
# fastcgi_param HTTP_HOST $host;
# fastcgi_param SERVER_NAME $host;
```

---

## ✅ Step 7: Test Nginx Configuration

```bash
# Test Nginx configuration syntax
sudo nginx -t

# Expected output:
# nginx: the configuration file /etc/nginx/nginx.conf syntax is ok
# nginx: configuration file /etc/nginx/nginx.conf test is successful
```

**If there are errors, fix them before proceeding.**

---

## 🔄 Step 8: Reload Nginx and PHP-FPM

```bash
# Reload Nginx (no downtime)
sudo systemctl reload nginx

# Or restart Nginx (if reload doesn't work)
sudo systemctl restart nginx

# Check Nginx status
sudo systemctl status nginx

# Restart PHP-FPM
sudo systemctl restart php8.1-fpm
# or
sudo systemctl restart php8.2-fpm

# Check PHP-FPM status
sudo systemctl status php8.1-fpm
```

---

## 🧹 Step 9: Clear Laravel Caches

```bash
# Navigate to project directory
cd /path/to/your/e-manager

# Clear all caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize:clear

# Rebuild caches for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verify routes are cached
php artisan route:list | grep vendor
```

---

## 🔐 Step 10: Set Correct File Permissions

```bash
# Navigate to project directory
cd /path/to/your/e-manager

# Set ownership (replace www-data with your web server user if different)
sudo chown -R www-data:www-data .

# Set directory permissions
sudo find . -type d -exec chmod 755 {} \;

# Set file permissions
sudo find . -type f -exec chmod 644 {} \;

# Set special permissions for storage and cache
sudo chmod -R 775 storage bootstrap/cache

# Verify permissions
ls -la storage/
ls -la bootstrap/cache/
```

---

## 🧪 Step 11: Test the Fix

```bash
# Test from command line (optional)
curl -I https://vendor1.yourdomain.com/vendor/login
curl -I https://vendor2.yourdomain.com/vendor/login
```

**Manual Testing Steps:**

1. **Test correct subdomain login:**
   - Visit: `https://vendor1.yourdomain.com/vendor/login`
   - Login with a user from `vendor1` tenant
   - ✅ Should work successfully

2. **Test wrong subdomain login:**
   - Visit: `https://vendor1.yourdomain.com/vendor/login`
   - Login with a user from `vendor2` tenant
   - ❌ Should show error: "You can only log in from your own vendor subdomain"

3. **Check logs after testing:**
   ```bash
   tail -n 50 storage/logs/laravel.log | grep "Vendor Login"
   ```

---

## 🐛 Step 12: Troubleshooting

### Issue: Still can't extract subdomain

**Check logs:**
```bash
tail -f storage/logs/laravel.log
```

**Test hostname extraction:**
```bash
# Visit in browser:
# https://vendor1.yourdomain.com/test-hostname
```

**If subdomain is not extracted, check:**
1. DNS wildcard record is set correctly
2. Nginx `server_name` includes wildcard: `*.yourdomain.com`
3. Host header is being passed correctly

### Issue: Nginx not passing host header

**Add to Nginx config:**
```bash
sudo nano /etc/nginx/sites-available/yourdomain
```

**In the PHP-FPM location block, ensure:**
```nginx
fastcgi_param HTTP_HOST $host;
fastcgi_param SERVER_NAME $host;
```

**Then reload:**
```bash
sudo nginx -t
sudo systemctl reload nginx
```

### Issue: 502 Bad Gateway

**Check PHP-FPM:**
```bash
sudo systemctl status php8.1-fpm
sudo tail -f /var/log/php8.1-fpm.log
```

**Find correct PHP-FPM socket:**
```bash
ls -la /var/run/php/
# or
ls -la /run/php/
```

**Update Nginx config with correct socket path, then reload.**

### Issue: Permission Denied

**Fix permissions:**
```bash
cd /path/to/your/e-manager
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache
```

---

## 📊 Step 13: Monitor Logs (Optional)

**Watch logs in real-time:**
```bash
# Terminal 1: Watch Laravel logs
tail -f /path/to/your/e-manager/storage/logs/laravel.log

# Terminal 2: Watch Nginx error logs
sudo tail -f /var/log/nginx/error.log

# Terminal 3: Watch Nginx access logs
sudo tail -f /var/log/nginx/access.log
```

---

## 🧹 Step 14: Remove Debug Code (After Fixing)

Once everything works, remove debug logging:

```bash
# Edit AuthController
nano app/Http/Controllers/Vendor/AuthController.php

# Remove or comment out these lines:
# - \Log::info('Vendor Login - Hostname Debug', ...);
# - \Log::info('Vendor Login - Subdomain Extraction', ...);
# - \Log::info('Vendor Login - Subdomain Validation', ...);
# - \Log::warning('Vendor Login - Subdomain Mismatch', ...);

# Remove test route (optional)
nano routes/web.php
# Remove or comment out: Route::get('/test-hostname', ...);
```

**Then clear caches again:**
```bash
php artisan route:clear
php artisan config:clear
php artisan route:cache
php artisan config:cache
```

---

## ✅ Final Checklist

- [ ] Connected to VPS via SSH
- [ ] Navigated to project directory
- [ ] Pulled latest changes from GitHub
- [ ] Tested hostname extraction with `/test-hostname` route
- [ ] Checked Laravel logs for debug information
- [ ] Verified Nginx config passes host header
- [ ] Tested Nginx configuration syntax
- [ ] Reloaded Nginx and PHP-FPM
- [ ] Cleared all Laravel caches
- [ ] Set correct file permissions
- [ ] Tested login from correct subdomain (should work)
- [ ] Tested login from wrong subdomain (should fail)
- [ ] Verified logs show correct subdomain extraction
- [ ] Removed debug code (after confirming fix works)

---

## 🎉 Success!

If all steps are completed successfully:
- ✅ Users can only log in from their own vendor subdomain
- ✅ Login from wrong subdomain is blocked
- ✅ Logs show correct subdomain extraction
- ✅ No security vulnerabilities

---

## 📞 Need Help?

If you encounter issues:

1. **Check Laravel logs:**
   ```bash
   tail -n 100 storage/logs/laravel.log
   ```

2. **Check Nginx logs:**
   ```bash
   sudo tail -n 100 /var/log/nginx/error.log
   ```

3. **Test hostname extraction:**
   - Visit: `https://vendor1.yourdomain.com/test-hostname`
   - Check the JSON response

4. **Verify DNS:**
   ```bash
   nslookup vendor1.yourdomain.com
   nslookup vendor2.yourdomain.com
   ```

---

## 📝 Quick Reference Commands

```bash
# Navigate to project
cd /path/to/your/e-manager

# Pull changes
git pull origin main

# Clear caches
php artisan optimize:clear

# Test Nginx
sudo nginx -t

# Reload services
sudo systemctl reload nginx
sudo systemctl restart php8.1-fpm

# View logs
tail -f storage/logs/laravel.log

# Test hostname
curl https://vendor1.yourdomain.com/test-hostname
```

---

**Last Updated:** $(date)
**Version:** 1.0
