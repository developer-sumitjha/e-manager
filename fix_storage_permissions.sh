#!/bin/bash

# Fix storage permissions for Laravel/XAMPP
# Run this script with: sudo bash fix_storage_permissions.sh

PROJECT_DIR="/Applications/XAMPP/xamppfiles/htdocs/e-manager"
WEB_USER="_www"  # XAMPP default web server user on macOS

echo "Fixing storage permissions for Laravel..."

# Change ownership to web server user
sudo chown -R $WEB_USER:admin "$PROJECT_DIR/storage"
sudo chown -R $WEB_USER:admin "$PROJECT_DIR/bootstrap/cache"

# Set proper permissions
sudo chmod -R 775 "$PROJECT_DIR/storage"
sudo chmod -R 775 "$PROJECT_DIR/bootstrap/cache"

# Make sure directories are executable
sudo find "$PROJECT_DIR/storage" -type d -exec chmod 775 {} \;
sudo find "$PROJECT_DIR/bootstrap/cache" -type d -exec chmod 775 {} \;

# Make sure files are readable/writable
sudo find "$PROJECT_DIR/storage" -type f -exec chmod 664 {} \;
sudo find "$PROJECT_DIR/bootstrap/cache" -type f -exec chmod 664 {} \;

# Ensure log file exists and is writable
if [ ! -f "$PROJECT_DIR/storage/logs/laravel.log" ]; then
    sudo touch "$PROJECT_DIR/storage/logs/laravel.log"
fi
sudo chmod 666 "$PROJECT_DIR/storage/logs/laravel.log"
sudo chmod 777 "$PROJECT_DIR/storage/logs"

echo "Permissions fixed!"
echo "Clearing Laravel caches..."

cd "$PROJECT_DIR"
php artisan view:clear
php artisan cache:clear
php artisan config:clear

echo "Done! Try accessing the create page again."

