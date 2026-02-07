# 🚀 Nginx Configuration Guide for E-Manager

This guide will help you configure Nginx on your Hostinger VPS to handle wildcard subdomains for the multi-tenant E-Manager application.

---

## 📋 Prerequisites

- Nginx installed on your VPS
- PHP-FPM installed and running
- SSL certificate (Let's Encrypt recommended)
- Domain DNS configured with wildcard A record

---

## 🔧 Step 1: Create Nginx Configuration

1. **Copy the example configuration:**

```bash
sudo cp /path/to/your/e-manager/nginx.conf.example /etc/nginx/sites-available/your-domain
```

2. **Edit the configuration file:**

```bash
sudo nano /etc/nginx/sites-available/your-domain
```

3. **Update the following values:**

   - Replace `yourdomain.com` with your actual domain name
   - Replace `/path/to/your/e-manager` with your actual project path (e.g., `/var/www/e-manager`)
   - Replace `/var/run/php/php8.1-fpm.sock` with your PHP-FPM socket path
     - Find your PHP-FPM socket: `ls -la /var/run/php/`
     - Common paths:
       - `/var/run/php/php8.1-fpm.sock`
       - `/var/run/php/php8.2-fpm.sock`
       - `/run/php/php8.1-fpm.sock`

4. **Update SSL certificate paths** (if using Let's Encrypt):

   - Replace `/etc/letsencrypt/live/yourdomain.com/fullchain.pem` with your actual certificate path
   - Replace `/etc/letsencrypt/live/yourdomain.com/privkey.pem` with your actual private key path

---

## 🔗 Step 2: Enable the Site

1. **Create a symbolic link to enable the site:**

```bash
sudo ln -s /etc/nginx/sites-available/your-domain /etc/nginx/sites-enabled/
```

2. **Remove default site (if exists):**

```bash
sudo rm /etc/nginx/sites-enabled/default
```

---

## ✅ Step 3: Test and Reload Nginx

1. **Test the configuration for syntax errors:**

```bash
sudo nginx -t
```

You should see:
```
nginx: the configuration file /etc/nginx/nginx.conf syntax is ok
nginx: configuration file /etc/nginx/nginx.conf test is successful
```

2. **If the test is successful, reload Nginx:**

```bash
sudo systemctl reload nginx
```

---

## 🔒 Step 4: SSL Certificate Setup (Let's Encrypt)

If you don't have an SSL certificate yet:

1. **Install Certbot:**

```bash
sudo apt-get update
sudo apt-get install certbot python3-certbot-nginx -y
```

2. **Get a wildcard certificate:**

```bash
sudo certbot certonly --manual \
  --preferred-challenges=dns \
  --email your-email@yourdomain.com \
  --server https://acme-v02.api.letsencrypt.org/directory \
  --agree-tos \
  -d yourdomain.com \
  -d *.yourdomain.com
```

3. **Follow the DNS verification instructions** (add TXT record to your DNS)

4. **Auto-renewal setup:**

```bash
sudo certbot renew --dry-run
```

---

## 🌐 Step 5: DNS Configuration

In your Hostinger DNS panel, add:

**Wildcard A Record:**
- Type: `A`
- Name: `*` (wildcard)
- Value: Your VPS IP address
- TTL: `3600`

**Main Domain A Record:**
- Type: `A`
- Name: `@` (or leave blank)
- Value: Your VPS IP address
- TTL: `3600`

---

## 🔍 Step 6: Verify Configuration

1. **Test DNS resolution:**

```bash
nslookup subdomain.yourdomain.com
nslookup yourdomain.com
```

2. **Test HTTP to HTTPS redirect:**

```bash
curl -I http://yourdomain.com
```

Should return: `301 Moved Permanently` with `Location: https://yourdomain.com`

3. **Test HTTPS:**

```bash
curl -I https://yourdomain.com
```

Should return: `200 OK`

4. **Test subdomain:**

```bash
curl -I https://test.yourdomain.com
```

Should return: `200 OK` (or `404` if tenant doesn't exist, which is expected)

---

## 🛠️ Step 7: PHP-FPM Configuration

1. **Check PHP-FPM status:**

```bash
sudo systemctl status php8.1-fpm
# or
sudo systemctl status php8.2-fpm
```

2. **Find PHP-FPM socket:**

```bash
ls -la /var/run/php/
# or
ls -la /run/php/
```

3. **Update Nginx config with correct socket path** (if different from default)

4. **Restart PHP-FPM if needed:**

```bash
sudo systemctl restart php8.1-fpm
```

---

## 📝 Step 8: Laravel Configuration

1. **Set correct file permissions:**

```bash
cd /path/to/your/e-manager
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache
```

2. **Clear Laravel caches:**

```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear
```

3. **Optimize for production:**

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🐛 Troubleshooting

### Issue: 502 Bad Gateway

**Solution:**
- Check PHP-FPM is running: `sudo systemctl status php8.1-fpm`
- Verify socket path in Nginx config matches actual socket
- Check PHP-FPM error log: `sudo tail -f /var/log/php8.1-fpm.log`

### Issue: 404 Not Found

**Solution:**
- Verify `root` path in Nginx config is correct
- Check file permissions: `ls -la /path/to/your/e-manager/public`
- Ensure `.htaccess` rules are working (Nginx doesn't use .htaccess, but Laravel's routing should handle it)

### Issue: Subdomain not working

**Solution:**
- Verify DNS wildcard record is set: `nslookup test.yourdomain.com`
- Check Nginx config has `server_name *.yourdomain.com`
- Verify Laravel middleware is registered (already done in code)
- Check Laravel logs: `tail -f storage/logs/laravel.log`

### Issue: SSL Certificate Error

**Solution:**
- Verify certificate paths in Nginx config
- Check certificate is valid: `sudo certbot certificates`
- Ensure certificate includes wildcard: `*.yourdomain.com`

### Issue: Permission Denied

**Solution:**
```bash
sudo chown -R www-data:www-data /path/to/your/e-manager
sudo chmod -R 755 /path/to/your/e-manager
sudo chmod -R 775 /path/to/your/e-manager/storage
sudo chmod -R 775 /path/to/your/e-manager/bootstrap/cache
```

---

## 📊 Useful Commands

```bash
# Check Nginx status
sudo systemctl status nginx

# Restart Nginx
sudo systemctl restart nginx

# Reload Nginx (without downtime)
sudo systemctl reload nginx

# View Nginx error log
sudo tail -f /var/log/nginx/error.log

# View site-specific error log
sudo tail -f /var/log/nginx/emanager-error.log

# Test Nginx configuration
sudo nginx -t

# List enabled sites
ls -la /etc/nginx/sites-enabled/

# Check PHP-FPM status
sudo systemctl status php8.1-fpm

# View PHP-FPM error log
sudo tail -f /var/log/php8.1-fpm.log
```

---

## ✅ Final Checklist

- [ ] Nginx configuration file created and edited
- [ ] Site enabled in Nginx
- [ ] Nginx configuration tested successfully
- [ ] Nginx reloaded
- [ ] SSL certificate installed (if using HTTPS)
- [ ] DNS wildcard record configured
- [ ] PHP-FPM running and socket path correct
- [ ] File permissions set correctly
- [ ] Laravel caches cleared
- [ ] Main domain accessible
- [ ] Subdomain accessible (or shows proper error if tenant doesn't exist)

---

## 🎉 Success!

Once all steps are completed, your subdomains should work correctly. The `IdentifyTenant` middleware (already registered in the code) will automatically detect and route subdomain requests to the appropriate tenant.

**Test your setup:**
1. Visit `https://yourdomain.com` - Should show main landing page
2. Visit `https://subdomain.yourdomain.com` - Should show tenant storefront or error page if tenant doesn't exist

---

## 📞 Need Help?

If you encounter issues:
1. Check Nginx error logs: `/var/log/nginx/error.log`
2. Check Laravel logs: `storage/logs/laravel.log`
3. Verify DNS: `nslookup subdomain.yourdomain.com`
4. Test Nginx config: `sudo nginx -t`
