# 🚀 Setup Guide After Migration

This guide will help you set up the E-Manager project after migrating it to a new device.

## ✅ Prerequisites Check

- ✅ PHP 8.2.4 (installed)
- ✅ Composer (installed)
- ✅ Node.js v24.11.1 (installed)
- ✅ npm 11.6.2 (installed)
- ✅ MySQL Database (created in phpMyAdmin)
- ✅ XAMPP running

---

## 📋 Step-by-Step Setup

### Step 1: Update .env File

Open the `.env` file in the project root and update the following database settings:

```env
APP_NAME="E-Manager"
APP_ENV=local
APP_KEY=  # Will be generated in next step
APP_DEBUG=true
APP_URL=http://localhost/e-manager/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name  # Replace with your actual database name
DB_USERNAME=root                 # Usually 'root' for XAMPP
DB_PASSWORD=                     # Usually empty for XAMPP, or your MySQL password
```

**Important:** Replace `your_database_name` with the actual database name you created in phpMyAdmin.

---

### Step 2: Generate Application Key

Run this command in your terminal:

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/e-manager
/Applications/XAMPP/xamppfiles/bin/php artisan key:generate
```

This will generate and add the `APP_KEY` to your `.env` file.

---

### Step 3: Install NPM Dependencies

Run this command (you may need to run it manually in your terminal):

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/e-manager
npm install
```

If you encounter permission errors, try:
```bash
sudo npm install
```

---

### Step 4: Run Database Migrations

This will create all the necessary tables in your database:

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/e-manager
/Applications/XAMPP/xamppfiles/bin/php artisan migrate
```

---

### Step 5: Seed Database

Run the seeders to populate initial data (subscription plans and super admin):

```bash
/Applications/XAMPP/xamppfiles/bin/php artisan db:seed --class=SubscriptionPlansSeeder
/Applications/XAMPP/xamppfiles/bin/php artisan db:seed --class=SuperAdminSeeder
```

Or seed all at once:
```bash
/Applications/XAMPP/xamppfiles/bin/php artisan db:seed
```

---

### Step 6: Create Storage Symlink

This links the storage directory for file uploads:

```bash
/Applications/XAMPP/xamppfiles/bin/php artisan storage:link
```

---

### Step 7: Build Frontend Assets

Compile the frontend assets:

```bash
npm run build
```

For development (with hot reload):
```bash
npm run dev
```

---

### Step 8: Clear and Optimize Caches

Clear all caches and optimize the application:

```bash
/Applications/XAMPP/xamppfiles/bin/php artisan optimize:clear
/Applications/XAMPP/xamppfiles/bin/php artisan config:cache
/Applications/XAMPP/xamppfiles/bin/php artisan route:cache
/Applications/XAMPP/xamppfiles/bin/php artisan view:cache
```

---

### Step 9: Set Permissions (if needed)

Ensure storage and cache directories are writable:

```bash
chmod -R 775 storage bootstrap/cache
```

---

## 🧪 Testing the Setup

### 1. Check Database Connection

```bash
/Applications/XAMPP/xamppfiles/bin/php artisan migrate:status
```

This should show all migrations as "Ran".

### 2. Access the Application

Open your browser and navigate to:

- **Public Landing Page:** `http://localhost/e-manager/public/`
- **Signup Page:** `http://localhost/e-manager/public/signup`
- **Super Admin Login:** `http://localhost/e-manager/public/super/login`
  - Email: `admin@emanager.com`
  - Password: `SuperAdmin@123`

### 3. Verify Super Admin

Check if super admin was created:

```bash
/Applications/XAMPP/xamppfiles/bin/php artisan tinker
```

Then in tinker:
```php
\App\Models\SuperAdmin::first();
```

---

## 🔧 Troubleshooting

### Issue: "SQLSTATE[HY000] [1045] Access denied"

**Solution:** Check your database credentials in `.env` file:
- Verify `DB_DATABASE` matches your database name
- Verify `DB_USERNAME` is correct (usually `root` for XAMPP)
- Verify `DB_PASSWORD` is correct (usually empty for XAMPP)

### Issue: "No application encryption key has been specified"

**Solution:** Run:
```bash
/Applications/XAMPP/xamppfiles/bin/php artisan key:generate
```

### Issue: "Class 'PDO' not found"

**Solution:** Enable PDO extension in `php.ini`:
```ini
extension=pdo_mysql
```

### Issue: "npm install" permission errors

**Solution:** Try:
```bash
sudo npm install
```
Or fix npm permissions:
```bash
sudo chown -R $(whoami) ~/.npm
```

### Issue: Routes not working

**Solution:** Clear route cache:
```bash
/Applications/XAMPP/xamppfiles/bin/php artisan route:clear
/Applications/XAMPP/xamppfiles/bin/php artisan optimize:clear
```

### Issue: Storage files not accessible

**Solution:** Create storage symlink:
```bash
/Applications/XAMPP/xamppfiles/bin/php artisan storage:link
```

---

## 📝 Quick Reference Commands

```bash
# Navigate to project
cd /Applications/XAMPP/xamppfiles/htdocs/e-manager

# Use XAMPP PHP
/Applications/XAMPP/xamppfiles/bin/php artisan [command]

# Common Artisan commands
php artisan migrate              # Run migrations
php artisan migrate:status      # Check migration status
php artisan db:seed             # Run all seeders
php artisan optimize:clear       # Clear all caches
php artisan config:clear         # Clear config cache
php artisan route:list           # List all routes
php artisan tinker              # Open Laravel REPL
```

---

## ✅ Setup Checklist

- [ ] Updated `.env` file with database credentials
- [ ] Generated application key
- [ ] Installed npm dependencies
- [ ] Ran database migrations
- [ ] Seeded database (plans and super admin)
- [ ] Created storage symlink
- [ ] Built frontend assets
- [ ] Cleared and optimized caches
- [ ] Tested database connection
- [ ] Accessed public landing page
- [ ] Logged in as super admin

---

## 🎯 Next Steps

After completing the setup:

1. **Test Tenant Creation:**
   - Go to `http://localhost/e-manager/public/signup`
   - Create a test tenant
   - Verify tenant database is created

2. **Explore Super Admin Panel:**
   - Login at `http://localhost/e-manager/public/super/login`
   - Check dashboard statistics
   - View tenants list

3. **Review Documentation:**
   - Read `START_YOUR_SAAS_NOW.md` for quick start
   - Check `README.md` for overview
   - Review `QUICK_REFERENCE.md` for commands

---

## 📞 Need Help?

If you encounter any issues:

1. Check the error logs: `storage/logs/laravel.log`
2. Verify all prerequisites are installed
3. Ensure XAMPP MySQL is running
4. Check database credentials in `.env`
5. Review the troubleshooting section above

---

**Last Updated:** After Migration Setup  
**Version:** 1.0.0

