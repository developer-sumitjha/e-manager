#!/bin/bash

# Vendor Login Subdomain Fix Script for Hostinger VPS
# Run this script on your VPS to fix the vendor login subdomain validation issue

echo "🚀 Starting Vendor Login Subdomain Fix..."
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Get current directory
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$SCRIPT_DIR"

echo -e "${GREEN}✓${NC} Current directory: $SCRIPT_DIR"
echo ""

# Step 1: Pull latest changes
echo -e "${YELLOW}Step 1: Pulling latest changes from GitHub...${NC}"
git pull origin main || git pull origin master
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓${NC} Git pull successful"
else
    echo -e "${RED}✗${NC} Git pull failed. Please check your git repository."
    exit 1
fi
echo ""

# Step 2: Clear Laravel caches
echo -e "${YELLOW}Step 2: Clearing Laravel caches...${NC}"
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize:clear
echo -e "${GREEN}✓${NC} Caches cleared"
echo ""

# Step 3: Rebuild caches
echo -e "${YELLOW}Step 3: Rebuilding production caches...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✓${NC} Caches rebuilt"
echo ""

# Step 4: Set permissions
echo -e "${YELLOW}Step 4: Setting file permissions...${NC}"
if [ -w . ]; then
    find . -type d -exec chmod 755 {} \;
    find . -type f -exec chmod 644 {} \;
    chmod -R 775 storage bootstrap/cache
    echo -e "${GREEN}✓${NC} Permissions set"
else
    echo -e "${YELLOW}⚠${NC} Cannot set permissions (may need sudo)"
    echo "   Run manually: sudo chown -R www-data:www-data ."
    echo "   Then: sudo chmod -R 755 . && sudo chmod -R 775 storage bootstrap/cache"
fi
echo ""

# Step 5: Test Nginx configuration
echo -e "${YELLOW}Step 5: Testing Nginx configuration...${NC}"
if command -v nginx &> /dev/null; then
    sudo nginx -t
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓${NC} Nginx configuration is valid"
        echo -e "${YELLOW}⚠${NC} Reload Nginx manually: sudo systemctl reload nginx"
    else
        echo -e "${RED}✗${NC} Nginx configuration has errors. Please fix them."
    fi
else
    echo -e "${YELLOW}⚠${NC} Nginx not found in PATH"
fi
echo ""

# Step 6: Check PHP-FPM
echo -e "${YELLOW}Step 6: Checking PHP-FPM status...${NC}"
if systemctl is-active --quiet php8.1-fpm; then
    echo -e "${GREEN}✓${NC} PHP 8.1-FPM is running"
    echo -e "${YELLOW}⚠${NC} Restart manually if needed: sudo systemctl restart php8.1-fpm"
elif systemctl is-active --quiet php8.2-fpm; then
    echo -e "${GREEN}✓${NC} PHP 8.2-FPM is running"
    echo -e "${YELLOW}⚠${NC} Restart manually if needed: sudo systemctl restart php8.2-fpm"
else
    echo -e "${YELLOW}⚠${NC} PHP-FPM status unknown"
fi
echo ""

# Step 7: Display test URLs
echo -e "${GREEN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${GREEN}✓${NC} Setup complete!"
echo ""
echo -e "${YELLOW}Next Steps:${NC}"
echo ""
echo "1. Test hostname extraction:"
echo "   Visit: https://vendor1.yourdomain.com/test-hostname"
echo "   (Replace vendor1 and yourdomain.com with your actual values)"
echo ""
echo "2. Test vendor login:"
echo "   - Correct subdomain: https://vendor1.yourdomain.com/vendor/login"
echo "   - Wrong subdomain: Try logging in with vendor2 user from vendor1 subdomain"
echo ""
echo "3. Check logs:"
echo "   tail -f storage/logs/laravel.log"
echo ""
echo "4. Verify Nginx config includes:"
echo "   fastcgi_param HTTP_HOST \$host;"
echo "   fastcgi_param SERVER_NAME \$host;"
echo ""
echo -e "${GREEN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
