#!/bin/bash

# Quick fix for Laravel storage permissions
# Run: sudo bash quick_fix_permissions.sh

PROJECT_DIR="/Applications/XAMPP/xamppfiles/htdocs/e-manager"

echo "Fixing Laravel storage permissions..."

# Fix storage/framework directories
sudo chmod -R 777 "$PROJECT_DIR/storage/framework/views"
sudo chmod -R 777 "$PROJECT_DIR/storage/framework/cache"
sudo chmod -R 777 "$PROJECT_DIR/storage/framework/sessions"
sudo chmod -R 777 "$PROJECT_DIR/bootstrap/cache"

# Fix storage/app/public for file uploads
sudo chmod -R 777 "$PROJECT_DIR/storage/app/public"
sudo chmod -R 777 "$PROJECT_DIR/storage/app/public/products"
sudo chmod -R 777 "$PROJECT_DIR/storage/app/public/categories"

# Ensure directories exist
sudo mkdir -p "$PROJECT_DIR/storage/app/public/products"
sudo mkdir -p "$PROJECT_DIR/storage/app/public/categories"
sudo mkdir -p "$PROJECT_DIR/storage/logs"

# Fix logs directory
sudo chmod -R 777 "$PROJECT_DIR/storage/logs"

# Clear the view cache
cd "$PROJECT_DIR"
php artisan view:clear
php artisan cache:clear

echo "✅ Done! All storage permissions fixed."
echo "You can now upload images and the site should work properly."

