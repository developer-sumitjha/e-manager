# E-Manager Admin Panel

A complete Laravel-based admin panel for e-commerce management, inspired by modern admin dashboard designs.

## 🚀 Features

### Authentication & Security
- ✅ User authentication with role-based access control (Admin/User)
- ✅ Admin middleware protection for admin routes
- ✅ Secure password hashing

### Admin Dashboard
- ✅ Overview statistics (Products, Categories, Orders, Revenue)
- ✅ Recent orders display
- ✅ Recent products listing
- ✅ Modern and responsive UI

### Category Management
- ✅ Create, Read, Update, Delete categories
- ✅ Category image upload
- ✅ Active/Inactive status toggle
- ✅ Product count per category

### Product Management
- ✅ Complete CRUD operations
- ✅ Product images upload
- ✅ Category assignment
- ✅ Price and sale price
- ✅ SKU and stock management
- ✅ Featured products
- ✅ Active/Inactive status

### Order Management
- ✅ View all orders
- ✅ Order details with items
- ✅ Update order status (Pending, Processing, Completed, Cancelled)
- ✅ Payment status tracking (Unpaid, Paid, Refunded)
- ✅ Customer information
- ✅ Shipping address

## 📋 Database Schema

### Tables Created:
1. **users** - User accounts with admin/user roles
2. **categories** - Product categories
3. **products** - Product catalog
4. **orders** - Customer orders
5. **order_items** - Order line items
6. **cache** - Application caching
7. **jobs** - Queue jobs

## 🔐 Admin Login Credentials

**Email:** dreamadsnepal@gmail.com  
**Password:** Nepal@123

## 🌐 Access URLs

- **Homepage:** `http://localhost/e-manager/public`
- **Login:** `http://localhost/e-manager/public/login`
- **Admin Dashboard:** `http://localhost/e-manager/public/admin/dashboard`
- **Categories:** `http://localhost/e-manager/public/admin/categories`
- **Products:** `http://localhost/e-manager/public/admin/products`
- **Orders:** `http://localhost/e-manager/public/admin/orders`

## 🛠️ Tech Stack

- **Framework:** Laravel 12.33.0
- **PHP Version:** 8.2.4
- **Database:** MySQL (emanager)
- **Frontend:** Bootstrap 5.3.0
- **Icons:** Font Awesome 6.4.0

## 📁 Project Structure

```
e-manager/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Admin/
│   │   │       ├── DashboardController.php
│   │   │       ├── CategoryController.php
│   │   │       ├── ProductController.php
│   │   │       └── OrderController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Category.php
│       ├── Product.php
│       ├── Order.php
│       └── OrderItem.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── AdminUserSeeder.php
├── resources/
│   └── views/
│       └── admin/
│           ├── layouts/
│           │   └── app.blade.php
│           ├── dashboard/
│           │   └── index.blade.php
│           ├── categories/
│           │   ├── index.blade.php
│           │   ├── create.blade.php
│           │   └── edit.blade.php
│           ├── products/
│           │   ├── index.blade.php
│           │   ├── create.blade.php
│           │   └── edit.blade.php
│           └── orders/
│               ├── index.blade.php
│               ├── show.blade.php
│               └── edit.blade.php
└── routes/
    └── web.php
```

## 🎨 UI Features

- Modern, clean design with card-based layout
- Responsive sidebar navigation
- Sticky top navbar with user profile
- Color-coded status badges
- Hover effects on cards and buttons
- Table actions for edit/delete operations
- Alert notifications for success/error messages
- Bootstrap pagination

## 🔧 Development Setup

### Starting the Development Server
```bash
cd /Applications/XAMPP/xamppfiles/htdocs/e-manager
/Applications/XAMPP/xamppfiles/bin/php artisan serve
```

Access at: `http://127.0.0.1:8000`

### Database Operations

**Run migrations:**
```bash
php artisan migrate
```

**Seed admin user:**
```bash
php artisan db:seed --class=AdminUserSeeder
```

**Fresh migration with seeding:**
```bash
php artisan migrate:fresh --seed
```

## 📝 Usage Guide

### 1. Login as Admin
- Navigate to `/login`
- Use admin credentials
- You'll be redirected to the admin dashboard

### 2. Managing Categories
- Click "Categories" in the sidebar
- Add new category with image
- Edit or delete existing categories
- Toggle active/inactive status

### 3. Managing Products
- Click "Products" in the sidebar
- Create new product with details:
  - Name, description
  - Category
  - Price and sale price
  - SKU and stock
  - Product image
  - Featured status
- Edit product details
- Manage stock levels

### 4. Managing Orders
- View all orders in the orders list
- Click to view order details
- Update order status
- Update payment status
- View customer information

## 🔒 Security Features

- CSRF protection on all forms
- Password hashing with bcrypt
- Role-based middleware protection
- Admin-only route access
- Input validation on all forms
- File upload validation

## 📱 Responsive Design

The admin panel is fully responsive and works on:
- Desktop computers
- Tablets
- Mobile devices

## 🎯 Next Steps / Future Enhancements

Potential features to add:
- User management interface
- Reports and analytics
- Email notifications
- Invoice generation
- Product variants
- Bulk operations
- Export data (CSV/PDF)
- Advanced search and filters
- Image gallery for products
- Settings page

## 📞 Support

For issues or questions, please check:
- Laravel Documentation: https://laravel.com/docs
- Bootstrap Documentation: https://getbootstrap.com/docs

---

**Project:** E-Manager Admin Panel  
**Version:** 1.0.0  
**Created:** October 2025  
**Framework:** Laravel 12








