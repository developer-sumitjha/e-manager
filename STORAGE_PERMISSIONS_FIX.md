# Fix Storage Permissions Issue

## Problem
Laravel cannot write compiled views to `storage/framework/views/` because the web server user doesn't have write permissions.

## Quick Fix (Run in Terminal)

### Option 1: Use the provided script
```bash
cd /Applications/XAMPP/xamppfiles/htdocs/e-manager
sudo bash fix_storage_permissions.sh
```

### Option 2: Manual fix
```bash
cd /Applications/XAMPP/xamppfiles/htdocs/e-manager

# Fix ownership (XAMPP on macOS typically uses _www user)
sudo chown -R _www:admin storage bootstrap/cache

# Fix permissions
sudo chmod -R 775 storage bootstrap/cache

# Clear Laravel caches
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

### Option 3: If _www doesn't work, try daemon
```bash
sudo chown -R daemon:admin storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
php artisan view:clear
```

## Verify Fix
After running the fix, try accessing the product create page again. The error should be resolved.

## Note
If you're running XAMPP, the web server user is typically `_www` or `daemon` on macOS. You can check which user your Apache is running as by looking at the XAMPP control panel or Apache configuration.

