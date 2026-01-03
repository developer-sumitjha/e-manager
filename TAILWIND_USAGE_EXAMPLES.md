# 🎨 Tailwind CSS Usage Examples for Admin Dashboard

## 📋 **Ready-to-Use Code Snippets**

Here are practical examples of how to use the DealDeck Tailwind design system in your admin dashboard.

---

## 🏗️ **Layout Structure**

### **Main Dashboard Layout**
```html
<!-- Main container -->
<div class="bg-background min-h-screen text-textPrimary font-poppins">
  <!-- Sidebar -->
  <aside class="dealdeck-sidebar">
    <div class="p-6">
      <!-- Logo -->
      <div class="flex items-center space-x-3 mb-8">
        <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center">
          <i class="fas fa-chart-line text-white text-lg"></i>
        </div>
        <h1 class="text-xl font-bold text-primary">E-Manager</h1>
      </div>
      
      <!-- Navigation -->
      <nav class="space-y-2">
        <a href="#" class="dealdeck-nav-link dealdeck-nav-link-active">
          <i class="fas fa-tachometer-alt mr-3"></i>
          Dashboard
        </a>
        <a href="#" class="dealdeck-nav-link">
          <i class="fas fa-users mr-3"></i>
          Users
        </a>
        <a href="#" class="dealdeck-nav-link">
          <i class="fas fa-shopping-cart mr-3"></i>
          Orders
        </a>
        <a href="#" class="dealdeck-nav-link">
          <i class="fas fa-chart-bar mr-3"></i>
          Reports
        </a>
      </nav>
    </div>
  </aside>
  
  <!-- Main content -->
  <div class="ml-sidebar">
    <!-- Header -->
    <header class="bg-card shadow-header px-6 py-4 border-b border-borderGray">
      <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-textPrimary">Dashboard</h2>
        <div class="flex items-center space-x-4">
          <!-- Search -->
          <div class="relative">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-textMuted"></i>
            <input type="text" placeholder="Search..." 
                   class="w-64 pl-10 pr-4 py-2 bg-gray-100 border border-borderGray rounded-lg focus:border-primary focus:ring-2 focus:ring-primaryLight transition-all duration-200">
          </div>
          
          <!-- Notifications -->
          <button class="relative p-2 text-textSecondary hover:text-primary hover:bg-primaryLight rounded-lg transition-all duration-200">
            <i class="fas fa-bell text-lg"></i>
            <span class="absolute -top-1 -right-1 w-5 h-5 bg-danger text-white text-xs rounded-full flex items-center justify-center">3</span>
          </button>
          
          <!-- Profile -->
          <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
              <span class="text-white font-semibold text-sm">JD</span>
            </div>
            <span class="text-textPrimary font-medium">John Doe</span>
          </div>
        </div>
      </div>
    </header>
    
    <!-- Content -->
    <main class="p-6">
      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Stat Card 1 -->
        <div class="dealdeck-stat-card">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center">
              <i class="fas fa-users text-white text-xl"></i>
            </div>
            <span class="dealdeck-badge-success">+2.09%</span>
          </div>
          <div class="dealdeck-stat-value">1,234</div>
          <div class="dealdeck-stat-label">Total Users</div>
        </div>
        
        <!-- Stat Card 2 -->
        <div class="dealdeck-stat-card">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-success rounded-xl flex items-center justify-center">
              <i class="fas fa-shopping-cart text-white text-xl"></i>
            </div>
            <span class="dealdeck-badge-danger">-1.04%</span>
          </div>
          <div class="dealdeck-stat-value">567</div>
          <div class="dealdeck-stat-label">Orders</div>
        </div>
        
        <!-- Stat Card 3 -->
        <div class="dealdeck-stat-card">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-warning rounded-xl flex items-center justify-center">
              <i class="fas fa-dollar-sign text-white text-xl"></i>
            </div>
            <span class="dealdeck-badge-success">+5.34%</span>
          </div>
          <div class="dealdeck-stat-value">$12,345</div>
          <div class="dealdeck-stat-label">Revenue</div>
        </div>
        
        <!-- Stat Card 4 -->
        <div class="dealdeck-stat-card">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-info rounded-xl flex items-center justify-center">
              <i class="fas fa-chart-line text-white text-xl"></i>
            </div>
            <span class="dealdeck-badge-success">+12.4%</span>
          </div>
          <div class="dealdeck-stat-value">89.2%</div>
          <div class="dealdeck-stat-label">Growth</div>
        </div>
      </div>
      
      <!-- Charts Row -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Chart Card 1 -->
        <div class="dealdeck-card">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-textPrimary">Sales Overview</h3>
            <div class="flex space-x-2">
              <button class="px-3 py-1 text-sm bg-primaryLight text-primary rounded-lg font-medium">Today</button>
              <button class="px-3 py-1 text-sm text-textSecondary hover:bg-primaryLight hover:text-primary rounded-lg font-medium">This Year</button>
            </div>
          </div>
          <!-- Chart placeholder -->
          <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
            <span class="text-textMuted">Chart will be rendered here</span>
          </div>
        </div>
        
        <!-- Chart Card 2 -->
        <div class="dealdeck-card">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-textPrimary">User Activity</h3>
            <button class="text-textSecondary hover:text-primary">
              <i class="fas fa-ellipsis-h"></i>
            </button>
          </div>
          <!-- Chart placeholder -->
          <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
            <span class="text-textMuted">Chart will be rendered here</span>
          </div>
        </div>
      </div>
      
      <!-- Data Table -->
      <div class="dealdeck-card">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-semibold text-textPrimary">Recent Orders</h3>
          <button class="dealdeck-button">
            <i class="fas fa-plus mr-2"></i>
            Add Order
          </button>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-100">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-textSecondary uppercase tracking-wider">Order ID</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-textSecondary uppercase tracking-wider">Customer</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-textSecondary uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-textSecondary uppercase tracking-wider">Amount</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-textSecondary uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-borderGray">
              <tr class="hover:bg-primaryLight transition-colors duration-200">
                <td class="px-6 py-4 text-textPrimary font-medium">#12345</td>
                <td class="px-6 py-4 text-textPrimary">John Doe</td>
                <td class="px-6 py-4">
                  <span class="dealdeck-badge-success">Completed</span>
                </td>
                <td class="px-6 py-4 text-textPrimary font-semibold">$299.00</td>
                <td class="px-6 py-4">
                  <div class="flex space-x-2">
                    <button class="text-primary hover:text-primaryDark">
                      <i class="fas fa-eye"></i>
                    </button>
                    <button class="text-textSecondary hover:text-primary">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="text-danger hover:text-red-700">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr class="hover:bg-primaryLight transition-colors duration-200">
                <td class="px-6 py-4 text-textPrimary font-medium">#12346</td>
                <td class="px-6 py-4 text-textPrimary">Jane Smith</td>
                <td class="px-6 py-4">
                  <span class="dealdeck-badge-warning">Pending</span>
                </td>
                <td class="px-6 py-4 text-textPrimary font-semibold">$149.00</td>
                <td class="px-6 py-4">
                  <div class="flex space-x-2">
                    <button class="text-primary hover:text-primaryDark">
                      <i class="fas fa-eye"></i>
                    </button>
                    <button class="text-textSecondary hover:text-primary">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="text-danger hover:text-red-700">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>
```

---

## 🎨 **Component Examples**

### **Modal Dialog**
```html
<!-- Modal backdrop -->
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-card rounded-2xl shadow-soft p-8 max-w-md w-full mx-4">
    <div class="flex items-center justify-between mb-6">
      <h3 class="text-xl font-semibold text-textPrimary">Confirm Action</h3>
      <button class="text-textMuted hover:text-textPrimary">
        <i class="fas fa-times text-xl"></i>
      </button>
    </div>
    <p class="text-textSecondary mb-6">Are you sure you want to perform this action?</p>
    <div class="flex space-x-3">
      <button class="dealdeck-button-outline">Cancel</button>
      <button class="dealdeck-button">Confirm</button>
    </div>
  </div>
</div>
```

### **Form Elements**
```html
<form class="space-y-6">
  <!-- Input Group -->
  <div>
    <label class="block text-sm font-medium text-textPrimary mb-2">Full Name</label>
    <input type="text" 
           class="w-full px-4 py-3 border border-borderGray rounded-lg focus:border-primary focus:ring-2 focus:ring-primaryLight transition-all duration-200"
           placeholder="Enter your full name">
  </div>
  
  <!-- Select Group -->
  <div>
    <label class="block text-sm font-medium text-textPrimary mb-2">Status</label>
    <select class="w-full px-4 py-3 border border-borderGray rounded-lg focus:border-primary focus:ring-2 focus:ring-primaryLight transition-all duration-200">
      <option>Active</option>
      <option>Inactive</option>
      <option>Pending</option>
    </select>
  </div>
  
  <!-- Textarea Group -->
  <div>
    <label class="block text-sm font-medium text-textPrimary mb-2">Description</label>
    <textarea rows="4" 
              class="w-full px-4 py-3 border border-borderGray rounded-lg focus:border-primary focus:ring-2 focus:ring-primaryLight transition-all duration-200"
              placeholder="Enter description"></textarea>
  </div>
  
  <!-- Checkbox Group -->
  <div class="flex items-center">
    <input type="checkbox" id="terms" class="w-4 h-4 text-primary border-borderGray rounded focus:ring-primaryLight">
    <label for="terms" class="ml-2 text-sm text-textSecondary">
      I agree to the terms and conditions
    </label>
  </div>
  
  <!-- Button Group -->
  <div class="flex space-x-3">
    <button type="submit" class="dealdeck-button">Save Changes</button>
    <button type="button" class="dealdeck-button-outline">Cancel</button>
  </div>
</form>
```

### **Alert Messages**
```html
<!-- Success Alert -->
<div class="bg-success bg-opacity-10 border border-success border-opacity-20 rounded-lg p-4 mb-4">
  <div class="flex items-center">
    <i class="fas fa-check-circle text-success mr-3"></i>
    <span class="text-success font-medium">Success! Your changes have been saved.</span>
  </div>
</div>

<!-- Error Alert -->
<div class="bg-danger bg-opacity-10 border border-danger border-opacity-20 rounded-lg p-4 mb-4">
  <div class="flex items-center">
    <i class="fas fa-exclamation-circle text-danger mr-3"></i>
    <span class="text-danger font-medium">Error! Please check your input and try again.</span>
  </div>
</div>

<!-- Warning Alert -->
<div class="bg-warning bg-opacity-10 border border-warning border-opacity-20 rounded-lg p-4 mb-4">
  <div class="flex items-center">
    <i class="fas fa-exclamation-triangle text-warning mr-3"></i>
    <span class="text-warning font-medium">Warning! This action cannot be undone.</span>
  </div>
</div>
```

### **Loading States**
```html
<!-- Loading Button -->
<button class="dealdeck-button opacity-75 cursor-not-allowed" disabled>
  <i class="fas fa-spinner fa-spin mr-2"></i>
  Loading...
</button>

<!-- Loading Card -->
<div class="dealdeck-card">
  <div class="animate-pulse">
    <div class="h-4 bg-gray-200 rounded w-3/4 mb-4"></div>
    <div class="h-4 bg-gray-200 rounded w-1/2 mb-2"></div>
    <div class="h-4 bg-gray-200 rounded w-5/6"></div>
  </div>
</div>
```

---

## 📱 **Responsive Examples**

### **Responsive Grid**
```html
<!-- Responsive stat cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
  <div class="dealdeck-stat-card">Card 1</div>
  <div class="dealdeck-stat-card">Card 2</div>
  <div class="dealdeck-stat-card">Card 3</div>
  <div class="dealdeck-stat-card">Card 4</div>
</div>

<!-- Responsive sidebar -->
<div class="dealdeck-sidebar hidden lg:block">
  <!-- Desktop sidebar -->
</div>

<!-- Mobile menu button -->
<button class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-card rounded-lg shadow-soft">
  <i class="fas fa-bars text-textPrimary"></i>
</button>
```

### **Responsive Table**
```html
<div class="dealdeck-card overflow-x-auto">
  <table class="w-full min-w-max">
    <thead class="bg-gray-100">
      <tr>
        <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-textSecondary uppercase tracking-wider">Name</th>
        <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-textSecondary uppercase tracking-wider hidden sm:table-cell">Email</th>
        <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-textSecondary uppercase tracking-wider">Status</th>
        <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-textSecondary uppercase tracking-wider hidden md:table-cell">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-borderGray">
      <!-- Table rows -->
    </tbody>
  </table>
</div>
```

---

## 🎯 **Quick Reference**

### **Common Classes**
```html
<!-- Layout -->
bg-background min-h-screen
bg-card rounded-xl shadow-card
dealdeck-sidebar

<!-- Typography -->
text-textPrimary text-xl font-semibold
text-textSecondary text-sm
text-textMuted text-xs

<!-- Colors -->
text-primary bg-primary
text-success bg-success
text-danger bg-danger
text-warning bg-warning

<!-- Spacing -->
p-6 px-4 py-2
mb-4 mt-2
space-x-3 space-y-4

<!-- Flexbox -->
flex items-center justify-between
flex-col md:flex-row

<!-- Grid -->
grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3
gap-4 lg:gap-6

<!-- Responsive -->
hidden lg:block
block lg:hidden
```

### **Custom Components**
```html
<!-- Use these pre-built components -->
dealdeck-card
dealdeck-button
dealdeck-button-outline
dealdeck-nav-link
dealdeck-nav-link-active
dealdeck-stat-card
dealdeck-stat-value
dealdeck-stat-label
dealdeck-badge-success
dealdeck-badge-danger
dealdeck-badge-warning
dealdeck-badge-info
```

---

## 🚀 **Next Steps**

1. **Replace existing HTML** with Tailwind classes
2. **Use custom components** for consistency
3. **Build assets** with `npm run build`
4. **Test responsiveness** on different devices
5. **Customize further** as needed

**Your Tailwind CSS setup is ready to use!** 🎨✨

