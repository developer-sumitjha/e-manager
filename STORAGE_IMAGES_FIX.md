# Product Images Not Displaying on Live Server - Fix Guide

## Problem
Product images display correctly on localhost but not on the live server.

## Root Cause
The issue is typically caused by:
1. Missing storage symlink (`public/storage` → `storage/app/public`)
2. Incorrect file permissions
3. Web server configuration not allowing symlinks

## Quick Fix

### Step 1: Create Storage Symlink
SSH into your live server and run:
```bash
php artisan storage:link
```

If you get a "symlink already exists" error, remove it first:
```bash
rm public/storage
php artisan storage:link
```

### Step 2: Fix Permissions
Ensure the storage directory has correct permissions:
```bash
chmod -R 775 storage/app/public
chown -R www-data:www-data storage/app/public  # Adjust user/group as needed
```

### Step 3: Verify Symlink
Check if the symlink was created correctly:
```bash
ls -la public/storage
```
You should see something like:
```
lrwxrwxrwx 1 user user 25 Jan 1 12:00 public/storage -> ../storage/app/public
```

### Step 4: Run Diagnostic Script
We've created a diagnostic script to help identify issues:
```bash
php fix_storage_images.php
```

## Web Server Configuration

### Apache (.htaccess)
Ensure your `public/.htaccess` file allows symlinks. It should include:
```apache
Options +FollowSymLinks
```

### Nginx
Ensure your nginx configuration allows symlinks:
```nginx
location /storage {
    try_files $uri $uri/ =404;
}
```

## Code Changes Made

We've updated the `Product` model to use `Storage::url()` instead of `asset()` for better compatibility:
- `getPrimaryImageUrlAttribute()` - Now uses `Storage::disk('public')->url()`
- `getAllImagesUrlsAttribute()` - Now uses `Storage::disk('public')->url()`
- `getVideoUrlAttribute()` - Now uses `Storage::disk('public')->url()`

This provides better compatibility across different server configurations.

## Verification

After applying the fix, verify images are working:
1. Check the browser console for 404 errors on image URLs
2. Visit a product page and verify images load
3. Check the network tab to see if image requests return 200 status

## Common Issues

### Issue: "Symlink already exists but is broken"
**Solution:**
```bash
rm public/storage
php artisan storage:link
```

### Issue: "Permission denied"
**Solution:**
```bash
chmod -R 775 storage
chown -R www-data:www-data storage  # Adjust as needed
```

### Issue: "Images still not showing after symlink"
**Check:**
1. Verify `APP_URL` in `.env` matches your domain
2. Clear cache: `php artisan cache:clear`
3. Check web server logs for errors
4. Verify file actually exists in `storage/app/public/`

### Issue: "Symlink works but images return 403"
**Solution:** Check web server configuration allows access to the storage directory.

## Additional Notes

- The storage symlink must be created on each server (local, staging, production)
- If you're using a deployment script, add `php artisan storage:link` to it
- Some shared hosting providers don't allow symlinks - you may need to use a different approach

## Need Help?

If images still don't display after following these steps:
1. Run the diagnostic script: `php fix_storage_images.php`
2. Check Laravel logs: `storage/logs/laravel.log`
3. Check web server error logs
4. Verify the image files actually exist in `storage/app/public/`
