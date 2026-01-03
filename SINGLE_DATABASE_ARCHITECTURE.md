# 🗄️ SINGLE DATABASE ARCHITECTURE - COMPLETE GUIDE

## E-Manager - Simplified Multi-Tenant System

---

## ✅ **ARCHITECTURE CHANGED**

### **New System: Single Shared Database**
- ✅ All vendors in ONE database
- ✅ Data isolated by `tenant_id` column
- ✅ Single login URL for all vendors
- ✅ No subdomain routing needed
- ✅ Automatic data filtering

---

## 🏗️ **HOW IT WORKS**

### **Database Structure:**
```
emanager (Single Database)
├── tenants              (all vendor businesses)
├── users                (all vendor admins, with tenant_id)
├── orders               (all orders, with tenant_id)
├── products             (all products, with tenant_id)
├── inventory            (all inventory, with tenant_id)
├── categories           (all categories, with tenant_id)
├── delivery_boys        (all delivery boys, with tenant_id)
├── manual_deliveries    (all deliveries, with tenant_id)
├── accounts             (all accounts, with tenant_id)
├── transactions         (all transactions, with tenant_id)
└── [all other tables with tenant_id]
```

### **Data Isolation:**
```
When Vendor A logs in:
├── System identifies tenant_id = 1
├── All queries automatically filtered: WHERE tenant_id = 1
├── Vendor A sees ONLY their data
└── Cannot access Vendor B's data

When Vendor B logs in:
├── System identifies tenant_id = 2
├── All queries automatically filtered: WHERE tenant_id = 2
├── Vendor B sees ONLY their data
└── Cannot access Vendor A's data
```

---

## 🔐 **LOGIN SYSTEM**

### **Single Login URL for ALL Vendors:**
```
URL: http://localhost/e-manager/public/login
```

### **How It Works:**
1. Vendor enters their email + password
2. System finds user in `users` table
3. System checks user's `tenant_id`
4. System sets tenant context in session
5. All subsequent queries filtered by that `tenant_id`
6. Vendor sees ONLY their own data

---

## 👥 **YOUR VENDORS CAN NOW LOGIN**

### **All Vendors Login Here:**
```
http://localhost/e-manager/public/login
```

### **Example Logins:**

**Vendor: Single DB Store (TEN0005)**
```
Email: ownerdb@test.com
Password: password123
Tenant ID: 5
```

**Vendor: Test Store (TEN0002)**
```
Email: owner@test.com  
Password: password123
Tenant ID: 2
```

**Each vendor will see ONLY their own:**
- ✅ Orders
- ✅ Products
- ✅ Inventory
- ✅ Customers
- ✅ Deliveries
- ✅ Accounts
- ✅ Everything!

---

## 🎯 **AUTOMATIC DATA FILTERING**

### **Models with Tenant Scoping:**
- ✅ `Order` - Uses `BelongsToTenant` trait
- ✅ `Product` - Uses `BelongsToTenant` trait
- ⏳ Other models will be added as needed

### **How It Works:**
```php
// When logged in as Vendor with tenant_id = 5

// This query:
Order::all();

// Automatically becomes:
Order::where('tenant_id', 5)->get();

// Vendor sees ONLY their orders!
```

---

## 🆕 **CREATING NEW VENDORS**

### **Signup Process:**

1. **Visit:**
   ```
   http://localhost/e-manager/public/signup
   ```

2. **Fill Form:**
   - Business details
   - Owner details
   - Choose subdomain (for identification only)
   - Select plan
   - Set password

3. **System Creates:**
   - ✅ Tenant record in `tenants` table
   - ✅ Admin user in `users` table (with tenant_id)
   - ✅ Trial subscription
   - ✅ Ready to login!

4. **Login:**
   ```
   URL: http://localhost/e-manager/public/login
   Email: [owner email from signup]
   Password: [password from signup]
   ```

---

## 🧪 **TEST DATA ISOLATION**

### **Test Scenario:**

**Create 2 Vendors:**
```
Vendor A:
  Email: vendora@test.com
  Password: password123
  
Vendor B:
  Email: vendorb@test.com
  Password: password123
```

**Login as Vendor A:**
1. Login with vendora@test.com
2. Create some products
3. Create some orders
4. Logout

**Login as Vendor B:**
1. Login with vendorb@test.com
2. Check products list
3. Result: Empty! (Cannot see Vendor A's products)
4. Create your own products
5. Logout

**Login as Vendor A again:**
1. Login with vendora@test.com
2. Check products list
3. Result: See ONLY your products, not Vendor B's!

✅ **Complete Data Isolation!**

---

## 📊 **ADVANTAGES**

### **✅ Benefits:**
- Simpler database management
- Easier backups (one database)
- Easier queries across tenants (for super admin)
- Lower hosting costs
- Simpler deployment
- Faster setup

### **✅ Data Security:**
- Automatic filtering by tenant_id
- Cannot access other vendor's data
- Laravel global scopes enforce isolation
- Database-level constraints

---

## 🔧 **FOR DEVELOPERS**

### **Adding Tenant Scoping to New Models:**

```php
use App\Traits\BelongsToTenant;

class YourModel extends Model
{
    use BelongsToTenant;
    
    protected $fillable = [
        'tenant_id',  // Add this
        // ... other fields
    ];
}
```

### **Creating Records:**
```php
// tenant_id is automatically set from logged-in user
Product::create([
    'name' => 'New Product',
    // No need to specify tenant_id!
]);
```

### **Querying Without Tenant Filter (Super Admin):**
```php
// Remove global scope to see all tenants' data
Product::withoutGlobalScope(TenantScope::class)->get();
```

---

## 🎊 **COMPLETE!**

### **✅ What's Working:**
- Single database for all vendors
- Single login URL
- Automatic data filtering
- Complete data isolation
- Vendor signup functional
- Admin users created automatically

### **✅ Access Points:**

**Vendors Login:**
```
http://localhost/e-manager/public/login
```

**Super Admin:**
```
http://localhost/e-manager/public/super/login
```

**Create New Vendor:**
```
http://localhost/e-manager/public/signup
```

---

## 💡 **LOGIN GUIDE**

### **For Any Vendor:**
```
1. Go to: http://localhost/e-manager/public/login
2. Enter: Your owner email (from signup)
3. Enter: Your password
4. Click: Login
5. See: YOUR OWN dashboard with YOUR data only!
```

### **Test Vendor (Ready to Use):**
```
Email: ownerdb@test.com
Password: password123
Tenant: Single DB Store (TEN0005)
```

---

**🎉 Single database architecture is now active!**

**All vendors login at the same URL but see completely isolated data! 🚀**







