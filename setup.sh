#!/bin/bash

# E-Manager Setup Script
# Run this script after migrating the project to set up the application

echo "🚀 E-Manager Setup Script"
echo "=========================="
echo ""

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Get the project directory
PROJECT_DIR="/Applications/XAMPP/xamppfiles/htdocs/e-manager"
PHP_BIN="/Applications/XAMPP/xamppfiles/bin/php"

cd "$PROJECT_DIR" || exit

echo -e "${YELLOW}Step 1: Checking prerequisites...${NC}"
echo ""

# Check PHP
if [ -f "$PHP_BIN" ]; then
    echo -e "${GREEN}✓ PHP found${NC}"
    $PHP_BIN -v | head -1
else
    echo -e "${RED}✗ PHP not found at $PHP_BIN${NC}"
    exit 1
fi

# Check Composer
if command -v composer &> /dev/null; then
    echo -e "${GREEN}✓ Composer found${NC}"
    composer --version | head -1
else
    echo -e "${RED}✗ Composer not found${NC}"
    exit 1
fi

# Check Node
if command -v node &> /dev/null; then
    echo -e "${GREEN}✓ Node.js found${NC}"
    node --version
else
    echo -e "${RED}✗ Node.js not found${NC}"
    exit 1
fi

# Check NPM
if command -v npm &> /dev/null; then
    echo -e "${GREEN}✓ NPM found${NC}"
    npm --version
else
    echo -e "${RED}✗ NPM not found${NC}"
    exit 1
fi

echo ""
echo -e "${YELLOW}Step 2: Checking .env file...${NC}"
if [ -f ".env" ]; then
    echo -e "${GREEN}✓ .env file exists${NC}"
    echo ""
    echo "⚠️  IMPORTANT: Please verify your .env file has correct database credentials:"
    echo "   - DB_DATABASE=your_database_name"
    echo "   - DB_USERNAME=root (or your MySQL username)"
    echo "   - DB_PASSWORD= (or your MySQL password)"
    echo ""
    read -p "Press Enter to continue after verifying .env file..."
else
    echo -e "${RED}✗ .env file not found${NC}"
    echo "Creating .env from .env.example..."
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo -e "${GREEN}✓ .env file created${NC}"
        echo "⚠️  Please edit .env file with your database credentials before continuing"
        exit 1
    else
        echo -e "${RED}✗ .env.example not found${NC}"
        exit 1
    fi
fi

echo ""
echo -e "${YELLOW}Step 3: Generating application key...${NC}"
$PHP_BIN artisan key:generate
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Application key generated${NC}"
else
    echo -e "${RED}✗ Failed to generate application key${NC}"
    echo "You may need to run this manually: php artisan key:generate"
fi

echo ""
echo -e "${YELLOW}Step 4: Installing NPM dependencies...${NC}"
npm install
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ NPM dependencies installed${NC}"
else
    echo -e "${RED}✗ Failed to install NPM dependencies${NC}"
    echo "You may need to run: sudo npm install"
fi

echo ""
echo -e "${YELLOW}Step 5: Testing database connection...${NC}"
$PHP_BIN artisan migrate:status > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Database connection successful${NC}"
else
    echo -e "${RED}✗ Database connection failed${NC}"
    echo "Please check your database credentials in .env file"
    echo "Make sure:"
    echo "  1. MySQL is running in XAMPP"
    echo "  2. Database exists in phpMyAdmin"
    echo "  3. DB_DATABASE, DB_USERNAME, DB_PASSWORD are correct in .env"
    exit 1
fi

echo ""
echo -e "${YELLOW}Step 6: Running database migrations...${NC}"
$PHP_BIN artisan migrate
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Migrations completed${NC}"
else
    echo -e "${RED}✗ Migrations failed${NC}"
    exit 1
fi

echo ""
echo -e "${YELLOW}Step 7: Seeding database...${NC}"
echo "Seeding subscription plans..."
$PHP_BIN artisan db:seed --class=SubscriptionPlansSeeder
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Subscription plans seeded${NC}"
else
    echo -e "${YELLOW}⚠ Subscription plans seeder may have already run${NC}"
fi

echo "Seeding super admin..."
$PHP_BIN artisan db:seed --class=SuperAdminSeeder
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Super admin seeded${NC}"
else
    echo -e "${YELLOW}⚠ Super admin seeder may have already run${NC}"
fi

echo ""
echo -e "${YELLOW}Step 8: Creating storage symlink...${NC}"
$PHP_BIN artisan storage:link
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Storage symlink created${NC}"
else
    echo -e "${YELLOW}⚠ Storage symlink may already exist${NC}"
fi

echo ""
echo -e "${YELLOW}Step 9: Building frontend assets...${NC}"
npm run build
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Frontend assets built${NC}"
else
    echo -e "${YELLOW}⚠ Frontend build completed with warnings${NC}"
fi

echo ""
echo -e "${YELLOW}Step 10: Clearing and optimizing caches...${NC}"
$PHP_BIN artisan optimize:clear
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache
echo -e "${GREEN}✓ Caches cleared and optimized${NC}"

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}✅ Setup Complete!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo "Your application is now ready!"
echo ""
echo "Access your application at:"
echo "  • Public Landing: http://localhost/e-manager/public/"
echo "  • Signup Page: http://localhost/e-manager/public/signup"
echo "  • Super Admin: http://localhost/e-manager/public/super/login"
echo ""
echo "Super Admin Credentials:"
echo "  • Email: admin@emanager.com"
echo "  • Password: SuperAdmin@123"
echo ""
echo "For development with hot reload, run:"
echo "  npm run dev"
echo ""

