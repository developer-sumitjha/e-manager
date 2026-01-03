# ⚡ Quick Setup Instructions

## 🎯 What You Need to Do

I've analyzed your project and prepared everything for setup. Here's what you need to do:

### **Option 1: Automated Setup (Recommended)**

Run the setup script I created:

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/e-manager
./setup.sh
```

This script will:
- ✅ Check all prerequisites
- ✅ Generate application key
- ✅ Install npm dependencies
- ✅ Test database connection
- ✅ Run migrations
- ✅ Seed database
- ✅ Create storage symlink
- ✅ Build frontend assets
- ✅ Optimize caches

**Before running:** Make sure your `.env` file has the correct database name you created in phpMyAdmin.

---

### **Option 2: Manual Setup**

If the script doesn't work, follow these steps manually:

#### 1. Update .env File

Open `.env` and update these lines:

```env
DB_DATABASE=your_database_name  # Replace with your actual database name
DB_USERNAME=root                # Usually 'root' for XAMPP
DB_PASSWORD=                     # Usually empty for XAMPP
```

#### 2. Generate Application Key

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/e-manager
/Applications/XAMPP/xamppfiles/bin/php artisan key:generate
```

#### 3. Install NPM Dependencies

```bash
npm install
# If permission error, try: sudo npm install
```

#### 4. Run Migrations

```bash
/Applications/XAMPP/xamppfiles/bin/php artisan migrate
```

#### 5. Seed Database

```bash
/Applications/XAMPP/xamppfiles/bin/php artisan db:seed --class=SubscriptionPlansSeeder
/Applications/XAMPP/xamppfiles/bin/php artisan db:seed --class=SuperAdminSeeder
```

#### 6. Create Storage Link

```bash
/Applications/XAMPP/xamppfiles/bin/php artisan storage:link
```

#### 7. Build Frontend Assets

```bash
npm run build
```

#### 8. Clear Caches

```bash
/Applications/XAMPP/xamppfiles/bin/php artisan optimize:clear
/Applications/XAMPP/xamppfiles/bin/php artisan config:cache
/Applications/XAMPP/xamppfiles/bin/php artisan route:cache
```

---

## 🧪 Test Your Setup

After setup, test these URLs:

1. **Public Landing Page:**
   ```
   http://localhost/e-manager/public/
   ```

2. **Signup Page:**
   ```
   http://localhost/e-manager/public/signup
   ```

3. **Super Admin Login:**
   ```
   http://localhost/e-manager/public/super/login
   Email: admin@emanager.com
   Password: SuperAdmin@123
   ```

---

## 📋 Current Status

✅ **Already Done:**
- PHP 8.2.4 installed
- Composer installed
- Node.js v24.11.1 installed
- npm 11.6.2 installed
- Laravel Framework 12.33.0 detected
- Composer dependencies installed (vendor folder exists)
- .env file exists

⏳ **Needs to be Done:**
- Update .env with your database credentials
- Install npm dependencies (node_modules missing)
- Generate application key
- Run migrations
- Seed database
- Build frontend assets

---

## 🔧 Troubleshooting

### Database Connection Error

**Error:** `SQLSTATE[HY000] [1045] Access denied`

**Solution:**
1. Check if MySQL is running in XAMPP
2. Verify database name in `.env` matches what you created in phpMyAdmin
3. Check `DB_USERNAME` and `DB_PASSWORD` in `.env`

### Permission Errors with npm

**Error:** `EPERM: operation not permitted`

**Solution:**
```bash
sudo npm install
```

Or fix npm permissions:
```bash
sudo chown -R $(whoami) ~/.npm
```

### Application Key Error

**Error:** `No application encryption key has been specified`

**Solution:**
```bash
/Applications/XAMPP/xamppfiles/bin/php artisan key:generate
```

---

## 📚 Documentation

For more detailed information, see:
- `SETUP_AFTER_MIGRATION.md` - Complete setup guide
- `README.md` - Project overview
- `START_YOUR_SAAS_NOW.md` - Quick start guide
- `QUICK_REFERENCE.md` - Command reference

---

## ✅ Next Steps After Setup

1. **Test tenant creation** at `/public/signup`
2. **Explore super admin panel** at `/public/super/login`
3. **Review documentation** in the project root
4. **Start development** with `npm run dev` for hot reload

---

**Need Help?** Check the error logs: `storage/logs/laravel.log`

