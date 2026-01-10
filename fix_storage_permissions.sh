#!/bin/bash

# Fix storage permissions for Laravel/XAMPP
# Run this script with: bash fix_storage_permissions.sh
# If you get permission errors, run with: sudo bash fix_storage_permissions.sh

PROJECT_DIR="/Applications/XAMPP/xamppfiles/htdocs/e-manager"
WEB_USER="_www"  # XAMPP default web server user on macOS
CURRENT_USER=$(whoami)

echo "=========================================="
echo "Fixing Laravel Storage Permissions"
echo "=========================================="
echo "Project: $PROJECT_DIR"
echo "Current User: $CURRENT_USER"
echo "Web User: $WEB_USER"
echo ""

# Check if running as root
if [ "$EUID" -eq 0 ]; then
    USE_SUDO=""
    echo "Running as root..."
else
    USE_SUDO="sudo"
    echo "Will use sudo for system directories..."
fi

echo ""
echo "Step 1: Setting directory permissions..."

# Set proper permissions for directories (775 = rwxrwxr-x)
$USE_SUDO chmod -R 775 "$PROJECT_DIR/storage" 2>/dev/null || chmod -R 775 "$PROJECT_DIR/storage"
$USE_SUDO chmod -R 775 "$PROJECT_DIR/bootstrap/cache" 2>/dev/null || chmod -R 775 "$PROJECT_DIR/bootstrap/cache"

# Make sure all directories are executable
find "$PROJECT_DIR/storage" -type d -exec chmod 775 {} \; 2>/dev/null
find "$PROJECT_DIR/bootstrap/cache" -type d -exec chmod 775 {} \; 2>/dev/null

echo "Step 2: Setting file permissions..."

# Make sure files are readable/writable (664 = rw-rw-r--)
find "$PROJECT_DIR/storage" -type f -exec chmod 664 {} \; 2>/dev/null
find "$PROJECT_DIR/bootstrap/cache" -type f -exec chmod 664 {} \; 2>/dev/null

echo "Step 3: Ensuring critical directories exist and are writable..."

# Ensure all critical directories exist
mkdir -p "$PROJECT_DIR/storage/framework/views"
mkdir -p "$PROJECT_DIR/storage/framework/cache"
mkdir -p "$PROJECT_DIR/storage/framework/sessions"
mkdir -p "$PROJECT_DIR/storage/framework/testing"
mkdir -p "$PROJECT_DIR/storage/logs"
mkdir -p "$PROJECT_DIR/storage/app/public"
mkdir -p "$PROJECT_DIR/storage/app/public/products"
mkdir -p "$PROJECT_DIR/bootstrap/cache"

# Set permissions on critical directories
chmod 775 "$PROJECT_DIR/storage/framework/views"
chmod 775 "$PROJECT_DIR/storage/framework/cache"
chmod 775 "$PROJECT_DIR/storage/framework/sessions"
chmod 775 "$PROJECT_DIR/storage/framework/testing"
chmod 775 "$PROJECT_DIR/storage/logs"
chmod 775 "$PROJECT_DIR/storage/app/public"
chmod 775 "$PROJECT_DIR/storage/app/public/products"
chmod 775 "$PROJECT_DIR/bootstrap/cache"

echo "Step 4: Ensuring log file exists..."

# Ensure log file exists and is writable
if [ ! -f "$PROJECT_DIR/storage/logs/laravel.log" ]; then
    touch "$PROJECT_DIR/storage/logs/laravel.log"
fi
chmod 666 "$PROJECT_DIR/storage/logs/laravel.log"

echo "Step 5: Changing ownership (if running with sudo)..."

# Change ownership to web server user (only if using sudo)
if [ "$EUID" -eq 0 ] || [ -n "$USE_SUDO" ]; then
    echo "Changing ownership to web server user..."
    $USE_SUDO chown -R $WEB_USER:admin "$PROJECT_DIR/storage" 2>/dev/null
    $USE_SUDO chown -R $WEB_USER:admin "$PROJECT_DIR/bootstrap/cache" 2>/dev/null
else
    echo "Skipping ownership change (not running as root/sudo)"
    echo "If you still have permission issues, run: sudo bash fix_storage_permissions.sh"
fi

echo ""
echo "Step 6: Clearing Laravel caches..."

cd "$PROJECT_DIR"
php artisan view:clear 2>/dev/null || echo "Warning: Could not clear view cache"
php artisan cache:clear 2>/dev/null || echo "Warning: Could not clear application cache"
php artisan config:clear 2>/dev/null || echo "Warning: Could not clear config cache"

echo ""
echo "=========================================="
echo "✅ Permissions fixed!"
echo "=========================================="
echo ""
echo "If you still encounter permission errors:"
echo "1. Run with sudo: sudo bash fix_storage_permissions.sh"
echo "2. Check that XAMPP web server user can access the directories"
echo "3. Verify PHP can write to storage/framework/views"
echo ""

