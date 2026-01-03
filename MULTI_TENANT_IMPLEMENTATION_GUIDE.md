# 🏢 MULTI-TENANT SAAS PLATFORM - COMPLETE IMPLEMENTATION GUIDE

## 📋 PROJECT OVERVIEW

Transform e-manager into a multi-tenant SaaS platform where:
- **You (Platform Provider)**: Manage multiple vendor businesses
- **Vendors**: Each gets isolated database and admin panel
- **Customers**: Access vendor-specific systems

---

## ✅ COMPLETED FOUNDATION

### Database Schema (DONE ✅)
1. ✅ `tenants` - Vendor businesses
2. ✅ `subscription_plans` - Pricing tiers
3. ✅ `subscriptions` - Active subscriptions
4. ✅ `super_admins` - Platform administrators
5. ✅ `tenant_activities` - Audit logs
6. ✅ `tenant_payments` - Payment transactions
7. ✅ `tenant_invoices` - Billing invoices

### Models Created (DONE ✅)
1. ✅ `Tenant` - With database creation logic
2. ✅ `SubscriptionPlan` - With feature helpers
3. ✅ `Subscription` - With renewal logic
4. ✅ `SuperAdmin` - With role permissions

---

## 🏗️ ARCHITECTURE OVERVIEW

```
┌─────────────────────────────────────────────────────────┐
│              emanager.com (Main Platform)                │
│                                                          │
│  Public Frontend (Inertia + Vue.js)                     │
│  • Landing Page                                         │
│  • Pricing & Features                                   │
│  • Vendor Signup                                        │
│  • Login Portal                                         │
└─────────────────────────────────────────────────────────┘
                         │
        ┌────────────────┼────────────────┐
        │                │                 │
        ▼                ▼                 ▼
┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│ Super Admin  │  │  Vendor 1    │  │  Vendor 2    │
│  (Central)   │  │  (Isolated)  │  │  (Isolated)  │
│              │  │              │  │              │
│ DB: emanager │  │ DB: tenant_1 │  │ DB: tenant_2 │
└──────────────┘  └──────────────┘  └──────────────┘
```

---

## 📦 IMPLEMENTATION ROADMAP

### PHASE 1: Core Infrastructure (Week 1)

#### 1.1 Tenant Manager Service
**File:** `app/Services/TenantManager.php`

```php
<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;

class TenantManager
{
    protected $currentTenant = null;

    public function setTenant(Tenant $tenant)
    {
        $this->currentTenant = $tenant;
        $tenant->configureDatabaseConnection();
        return $this;
    }

    public function getTenant()
    {
        return $this->currentTenant;
    }

    public function createTenantDatabase(Tenant $tenant)
    {
        $dbName = 'tenant_' . strtolower($tenant->tenant_id);
        
        // Create database
        DB::statement("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        // Update tenant
        $tenant->update([
            'database_name' => $dbName,
            'database_host' => env('DB_HOST', 'localhost'),
            'database_username' => env('DB_USERNAME', 'root'),
            'database_password' => env('DB_PASSWORD', ''),
        ]);
        
        // Configure and migrate
        $tenant->configureDatabaseConnection();
        
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--force' => true,
        ]);
        
        // Seed initial data
        $this->seedTenantDatabase($tenant);
        
        return true;
    }

    protected function seedTenantDatabase(Tenant $tenant)
    {
        DB::connection('tenant')->table('users')->insert([
            'name' => $tenant->owner_name,
            'email' => $tenant->owner_email,
            'password' => bcrypt('temporary_password'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function deleteTenantDatabase(Tenant $tenant)
    {
        if ($tenant->database_name) {
            DB::statement("DROP DATABASE IF EXISTS `{$tenant->database_name}`");
        }
    }
}
```

**Usage:**
```php
$tenantManager = app(TenantManager::class);
$tenantManager->createTenantDatabase($tenant);
```

---

#### 1.2 Tenant Identification Middleware
**File:** `app/Http/Middleware/IdentifyTenant.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use App\Services\TenantManager;

class IdentifyTenant
{
    protected $tenantManager;

    public function __construct(TenantManager $tenantManager)
    {
        $this->tenantManager = $tenantManager;
    }

    public function handle($request, Closure $next)
    {
        // Get subdomain
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];
        
        // Skip for main domain and super admin
        if (in_array($subdomain, ['www', 'super', 'admin'])) {
            return $next($request);
        }
        
        // Find tenant by subdomain
        $tenant = Tenant::where('subdomain', $subdomain)
            ->where('status', 'active')
            ->first();
        
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }
        
        // Set tenant context
        $this->tenantManager->setTenant($tenant);
        
        // Share tenant with views
        view()->share('tenant', $tenant);
        
        return $next($request);
    }
}
```

**Register in:** `app/Http/Kernel.php`
```php
protected $middlewareGroups = [
    'tenant' => [
        \App\Http\Middleware\IdentifyTenant::class,
    ],
];
```

---

### PHASE 2: Super Admin Panel (Week 2)

#### 2.1 Super Admin Authentication
**File:** `config/auth.php` - Add guard

```php
'guards' => [
    'web' => [...],
    'delivery_boy' => [...],
    'super_admin' => [
        'driver' => 'session',
        'provider' => 'super_admins',
    ],
],

'providers' => [
    'super_admins' => [
        'driver' => 'eloquent',
        'model' => App\Models\SuperAdmin::class,
    ],
],
```

#### 2.2 Super Admin Routes
**File:** `routes/web.php` - Add routes

```php
// Super Admin Routes
Route::prefix('super')->name('super.')->group(function () {
    // Auth
    Route::get('login', [App\Http\Controllers\SuperAdmin\AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [App\Http\Controllers\SuperAdmin\AuthController::class, 'login']);
    Route::post('logout', [App\Http\Controllers\SuperAdmin\AuthController::class, 'logout'])->name('logout');
    
    // Protected routes
    Route::middleware('auth:super_admin')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])->name('dashboard');
        
        // Tenant Management
        Route::resource('tenants', App\Http\Controllers\SuperAdmin\TenantController::class);
        Route::post('tenants/{tenant}/approve', [App\Http\Controllers\SuperAdmin\TenantController::class, 'approve'])->name('tenants.approve');
        Route::post('tenants/{tenant}/suspend', [App\Http\Controllers\SuperAdmin\TenantController::class, 'suspend'])->name('tenants.suspend');
        Route::post('tenants/{tenant}/activate', [App\Http\Controllers\SuperAdmin\TenantController::class, 'activate'])->name('tenants.activate');
        
        // Subscription Management
        Route::resource('plans', App\Http\Controllers\SuperAdmin\PlanController::class);
        Route::get('subscriptions', [App\Http\Controllers\SuperAdmin\SubscriptionController::class, 'index'])->name('subscriptions.index');
        
        // Payments & Invoices
        Route::get('payments', [App\Http\Controllers\SuperAdmin\PaymentController::class, 'index'])->name('payments.index');
        Route::get('invoices', [App\Http\Controllers\SuperAdmin\InvoiceController::class, 'index'])->name('invoices.index');
        
        // Analytics
        Route::get('analytics', [App\Http\Controllers\SuperAdmin\AnalyticsController::class, 'index'])->name('analytics');
    });
});
```

#### 2.3 Super Admin Dashboard Controller
**File:** `app/Http/Controllers/SuperAdmin/DashboardController.php`

```php
<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Subscription;
use App\Models\TenantPayment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_tenants' => Tenant::count(),
            'active_tenants' => Tenant::where('status', 'active')->count(),
            'trial_tenants' => Tenant::where('status', 'trial')->count(),
            'suspended_tenants' => Tenant::where('status', 'suspended')->count(),
            
            'total_revenue' => TenantPayment::where('status', 'completed')->sum('amount'),
            'revenue_this_month' => TenantPayment::where('status', 'completed')
                ->whereMonth('paid_at', now()->month)
                ->sum('amount'),
            
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'expiring_soon' => Subscription::where('status', 'active')
                ->whereBetween('ends_at', [now(), now()->addDays(7)])
                ->count(),
        ];
        
        $recentTenants = Tenant::latest()->take(10)->get();
        $recentPayments = TenantPayment::with('tenant')->latest()->take(10)->get();
        
        // Monthly revenue chart data
        $monthlyRevenue = TenantPayment::selectRaw('MONTH(paid_at) as month, SUM(amount) as total')
            ->where('status', 'completed')
            ->whereYear('paid_at', now()->year)
            ->groupBy('month')
            ->get();
        
        return view('super-admin.dashboard', compact('stats', 'recentTenants', 'recentPayments', 'monthlyRevenue'));
    }
}
```

---

### PHASE 3: Public Frontend (Week 3)

#### 3.1 Install Inertia.js Manually

**Step 1: Create package.json (if not exists)**
```json
{
    "private": true,
    "type": "module",
    "scripts": {
        "dev": "vite",
        "build": "vite build"
    },
    "devDependencies": {
        "@vitejs/plugin-vue": "^5.0.0",
        "axios": "^1.6.4",
        "laravel-vite-plugin": "^1.0",
        "vite": "^5.0",
        "vue": "^3.4.0"
    },
    "dependencies": {
        "@inertiajs/vue3": "^1.0.0"
    }
}
```

**Step 2: Install dependencies**
```bash
npm install
```

**Step 3: Create vite.config.js**
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
```

**Step 4: Update resources/js/app.js**
```javascript
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)
    },
})
```

**Step 5: Create app.blade.php for Inertia**
**File:** `resources/views/app.blade.php`

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @inertiaHead
</head>
<body>
    @inertia
</body>
</html>
```

#### 3.2 Public Landing Page
**File:** `resources/js/Pages/Landing.vue`

```vue
<template>
  <div class="landing-page">
    <!-- Hero Section -->
    <section class="hero">
      <div class="container">
        <div class="hero-content">
          <h1>Powerful Business Management for Nepal</h1>
          <p>Complete order management, inventory, delivery, and accounting system for your business</p>
          <div class="cta-buttons">
            <a href="/signup" class="btn btn-primary">Start Free Trial</a>
            <a href="/pricing" class="btn btn-secondary">View Pricing</a>
          </div>
        </div>
        <div class="hero-image">
          <img src="/images/dashboard-preview.png" alt="Dashboard Preview" />
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="features">
      <div class="container">
        <h2>Everything You Need to Run Your Business</h2>
        <div class="feature-grid">
          <div class="feature-card">
            <i class="fas fa-shopping-cart"></i>
            <h3>Order Management</h3>
            <p>Track and manage orders from creation to delivery</p>
          </div>
          <div class="feature-card">
            <i class="fas fa-boxes"></i>
            <h3>Inventory Control</h3>
            <p>Real-time stock tracking and management</p>
          </div>
          <div class="feature-card">
            <i class="fas fa-truck"></i>
            <h3>Delivery System</h3>
            <p>Manual delivery and logistics integration</p>
          </div>
          <div class="feature-card">
            <i class="fas fa-calculator"></i>
            <h3>Accounting</h3>
            <p>Complete financial management and reports</p>
          </div>
          <div class="feature-card">
            <i class="fas fa-chart-line"></i>
            <h3>Analytics</h3>
            <p>Business insights and performance metrics</p>
          </div>
          <div class="feature-card">
            <i class="fas fa-users"></i>
            <h3>Multi-User</h3>
            <p>Team collaboration and role management</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Pricing Teaser -->
    <section class="pricing-teaser">
      <div class="container">
        <h2>Simple, Transparent Pricing</h2>
        <p>14-day free trial. No credit card required.</p>
        <a href="/pricing" class="btn btn-primary">View All Plans</a>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
      <div class="container">
        <h2>Ready to Get Started?</h2>
        <p>Join hundreds of businesses managing their operations with e-manager</p>
        <a href="/signup" class="btn btn-primary btn-lg">Start Your Free Trial</a>
      </div>
    </section>
  </div>
</template>

<script setup>
// Component logic here
</script>

<style scoped>
.hero {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 6rem 0;
}

.hero-content h1 {
  font-size: 3rem;
  margin-bottom: 1.5rem;
}

.feature-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
  margin-top: 3rem;
}

.feature-card {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.feature-card i {
  font-size: 3rem;
  color: #8B5CF6;
  margin-bottom: 1rem;
}

/* Add more styles */
</style>
```

#### 3.3 Vendor Signup Page
**File:** `resources/js/Pages/Signup.vue`

```vue
<template>
  <div class="signup-page">
    <div class="signup-container">
      <div class="signup-header">
        <h1>Start Your Free Trial</h1>
        <p>Get started with e-manager in minutes. No credit card required.</p>
      </div>

      <form @submit.prevent="submitSignup" class="signup-form">
        <!-- Business Information -->
        <div class="form-section">
          <h3>Business Information</h3>
          
          <div class="form-group">
            <label>Business Name *</label>
            <input v-model="form.business_name" type="text" required 
                   placeholder="Your Business Name" />
          </div>

          <div class="form-group">
            <label>Business Email *</label>
            <input v-model="form.business_email" type="email" required 
                   placeholder="business@example.com" />
          </div>

          <div class="form-group">
            <label>Business Phone</label>
            <input v-model="form.business_phone" type="tel" 
                   placeholder="+977-1-1234567" />
          </div>

          <div class="form-group">
            <label>Business Type</label>
            <select v-model="form.business_type">
              <option value="">Select Type</option>
              <option value="retail">Retail</option>
              <option value="wholesale">Wholesale</option>
              <option value="restaurant">Restaurant</option>
              <option value="ecommerce">E-commerce</option>
              <option value="other">Other</option>
            </select>
          </div>
        </div>

        <!-- Owner Information -->
        <div class="form-section">
          <h3>Owner Information</h3>
          
          <div class="form-group">
            <label>Full Name *</label>
            <input v-model="form.owner_name" type="text" required 
                   placeholder="John Doe" />
          </div>

          <div class="form-group">
            <label>Email Address *</label>
            <input v-model="form.owner_email" type="email" required 
                   placeholder="john@example.com" />
          </div>

          <div class="form-group">
            <label>Phone Number *</label>
            <input v-model="form.owner_phone" type="tel" required 
                   placeholder="9800000000" />
          </div>

          <div class="form-group">
            <label>Password *</label>
            <input v-model="form.password" type="password" required 
                   minlength="8" placeholder="Minimum 8 characters" />
          </div>

          <div class="form-group">
            <label>Confirm Password *</label>
            <input v-model="form.password_confirmation" type="password" required 
                   placeholder="Confirm your password" />
          </div>
        </div>

        <!-- Subdomain Selection -->
        <div class="form-section">
          <h3>Choose Your Subdomain</h3>
          
          <div class="form-group">
            <label>Subdomain *</label>
            <div class="subdomain-input">
              <input v-model="form.subdomain" type="text" required 
                     pattern="[a-z0-9-]+" placeholder="your-business" />
              <span class="subdomain-suffix">.emanager.com</span>
            </div>
            <small>Your admin panel will be accessible at: {{ form.subdomain }}.emanager.com</small>
          </div>
        </div>

        <!-- Plan Selection -->
        <div class="form-section">
          <h3>Select Your Plan</h3>
          
          <div class="plan-selector">
            <div v-for="plan in plans" :key="plan.id" 
                 class="plan-card" 
                 :class="{ selected: form.plan_id === plan.id }"
                 @click="form.plan_id = plan.id">
              <h4>{{ plan.name }}</h4>
              <div class="plan-price">
                <span class="price">Rs. {{ plan.price_monthly }}</span>
                <span class="period">/month</span>
              </div>
              <ul class="plan-features">
                <li v-for="feature in plan.features" :key="feature">
                  <i class="fas fa-check"></i> {{ feature }}
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Terms & Conditions -->
        <div class="form-group">
          <label class="checkbox-label">
            <input v-model="form.agreed_to_terms" type="checkbox" required />
            I agree to the Terms of Service and Privacy Policy
          </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary btn-lg" :disabled="loading">
          <span v-if="loading">
            <i class="fas fa-spinner fa-spin"></i> Creating Account...
          </span>
          <span v-else>
            Start Free Trial
          </span>
        </button>
      </form>

      <div class="signup-footer">
        <p>Already have an account? <a href="/login">Sign In</a></p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  plans: Array
})

const form = ref({
  business_name: '',
  business_email: '',
  business_phone: '',
  business_type: '',
  owner_name: '',
  owner_email: '',
  owner_phone: '',
  password: '',
  password_confirmation: '',
  subdomain: '',
  plan_id: props.plans[0]?.id || null,
  agreed_to_terms: false
})

const loading = ref(false)

function submitSignup() {
  loading.value = true
  
  router.post('/api/tenants/signup', form.value, {
    onSuccess: () => {
      // Redirect to success page or login
      alert('Account created! Check your email for verification.')
    },
    onError: (errors) => {
      loading.value = false
      alert('Error: ' + Object.values(errors).join(', '))
    }
  })
}
</script>

<style scoped>
.signup-page {
  min-height: 100vh;
  background: #f3f4f6;
  padding: 3rem 0;
}

.signup-container {
  max-width: 800px;
  margin: 0 auto;
  background: white;
  border-radius: 12px;
  padding: 3rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.form-section {
  margin-bottom: 2rem;
  padding-bottom: 2rem;
  border-bottom: 1px solid #e5e7eb;
}

.form-section h3 {
  margin-bottom: 1.5rem;
  color: #1f2937;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #374151;
}

.form-group input,
.form-group select {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 1rem;
}

.subdomain-input {
  display: flex;
  align-items: center;
}

.subdomain-suffix {
  background: #f3f4f6;
  padding: 0.75rem 1rem;
  border: 1px solid #d1d5db;
  border-left: none;
  border-radius: 0 6px 6px 0;
}

.plan-selector {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.plan-card {
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  padding: 1.5rem;
  cursor: pointer;
  transition: all 0.3s;
}

.plan-card:hover {
  border-color: #8B5CF6;
}

.plan-card.selected {
  border-color: #8B5CF6;
  background: rgba(139, 92, 246, 0.05);
}
</style>
```

---

### PHASE 4: API Endpoints (Week 3)

#### 4.1 Tenant Signup API
**File:** `app/Http/Controllers/Api/TenantController.php`

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\SubscriptionPlan;
use App\Services\TenantManager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    protected $tenantManager;

    public function __construct(TenantManager $tenantManager)
    {
        $this->tenantManager = $tenantManager;
    }

    public function signup(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_email' => 'required|email|unique:tenants,business_email',
            'business_phone' => 'nullable|string',
            'business_type' => 'nullable|string',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email',
            'owner_phone' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'subdomain' => 'required|string|alpha_dash|unique:tenants,subdomain',
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        try {
            // Generate tenant ID
            $lastTenant = Tenant::latest()->first();
            $nextNumber = $lastTenant ? intval(substr($lastTenant->tenant_id, 3)) + 1 : 1;
            $tenantId = 'TEN' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Create tenant
            $tenant = Tenant::create([
                'tenant_id' => $tenantId,
                'business_name' => $validated['business_name'],
                'business_email' => $validated['business_email'],
                'business_phone' => $validated['business_phone'] ?? null,
                'business_type' => $validated['business_type'] ?? null,
                'owner_name' => $validated['owner_name'],
                'owner_email' => $validated['owner_email'],
                'owner_phone' => $validated['owner_phone'],
                'password' => bcrypt($validated['password']),
                'subdomain' => strtolower($validated['subdomain']),
                'current_plan_id' => $validated['plan_id'],
                'status' => 'trial',
                'trial_ends_at' => now()->addDays(14),
            ]);

            // Create database for tenant
            $this->tenantManager->createTenantDatabase($tenant);

            // Create trial subscription
            $plan = SubscriptionPlan::find($validated['plan_id']);
            $tenant->subscriptions()->create([
                'subscription_id' => 'SUB-' . Str::random(8),
                'plan_id' => $plan->id,
                'billing_cycle' => 'monthly',
                'starts_at' => now(),
                'ends_at' => now()->addDays(14),
                'trial_ends_at' => now()->addDays(14),
                'amount' => 0,
                'status' => 'trial',
            ]);

            // Log activity
            $tenant->logActivity('signup', 'New tenant signed up');

            // Send welcome email
            // Mail::to($tenant->owner_email)->send(new WelcomeTenant($tenant));

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully! Check your email for verification.',
                'tenant' => $tenant,
                'login_url' => "https://{$tenant->subdomain}.emanager.com/login"
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Tenant signup failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create account. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPlans()
    {
        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function($plan) {
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'slug' => $plan->slug,
                    'description' => $plan->description,
                    'price_monthly' => $plan->price_monthly,
                    'price_yearly' => $plan->price_yearly,
                    'yearly_discount' => $plan->getYearlyDiscount(),
                    'features' => $plan->getFeaturesList(),
                    'is_featured' => $plan->is_featured,
                ];
            });

        return response()->json($plans);
    }
}
```

**API Routes:**
```php
// routes/api.php
Route::post('/tenants/signup', [App\Http\Controllers\Api\TenantController::class, 'signup']);
Route::get('/plans', [App\Http\Controllers\Api\TenantController::class, 'getPlans']);
```

---

### PHASE 5: Subscription Plans Seeder

**File:** `database/seeders/SubscriptionPlansSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlansSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'plan_id' => 'PLAN-FREE',
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Perfect for getting started',
                'price_monthly' => 0,
                'price_yearly' => 0,
                'max_orders_per_month' => 50,
                'max_products' => 25,
                'max_users' => 1,
                'max_storage_gb' => 1,
                'has_inventory' => true,
                'has_manual_delivery' => false,
                'has_logistics_integration' => false,
                'has_accounting' => false,
                'has_analytics' => false,
                'has_api_access' => false,
                'has_multi_user' => false,
                'has_custom_domain' => false,
                'has_priority_support' => false,
                'trial_days' => 14,
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 1,
            ],
            [
                'plan_id' => 'PLAN-STARTER',
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'For growing businesses',
                'price_monthly' => 2500,
                'price_yearly' => 25000, // 2 months free
                'max_orders_per_month' => 500,
                'max_products' => 200,
                'max_users' => 3,
                'max_storage_gb' => 5,
                'has_inventory' => true,
                'has_manual_delivery' => true,
                'has_logistics_integration' => false,
                'has_accounting' => false,
                'has_analytics' => true,
                'has_api_access' => false,
                'has_multi_user' => true,
                'has_custom_domain' => false,
                'has_priority_support' => false,
                'trial_days' => 14,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'plan_id' => 'PLAN-PROFESSIONAL',
                'name' => 'Professional',
                'slug' => 'professional',
                'description' => 'For established businesses',
                'price_monthly' => 5000,
                'price_yearly' => 50000, // 2 months free
                'max_orders_per_month' => 2000,
                'max_products' => 1000,
                'max_users' => 10,
                'max_storage_gb' => 20,
                'has_inventory' => true,
                'has_manual_delivery' => true,
                'has_logistics_integration' => true,
                'has_accounting' => true,
                'has_analytics' => true,
                'has_api_access' => true,
                'has_multi_user' => true,
                'has_custom_domain' => false,
                'has_priority_support' => true,
                'trial_days' => 14,
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 3,
            ],
            [
                'plan_id' => 'PLAN-ENTERPRISE',
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'For large-scale operations',
                'price_monthly' => 10000,
                'price_yearly' => 100000, // 2 months free
                'max_orders_per_month' => 10000,
                'max_products' => 5000,
                'max_users' => 50,
                'max_storage_gb' => 100,
                'has_inventory' => true,
                'has_manual_delivery' => true,
                'has_logistics_integration' => true,
                'has_accounting' => true,
                'has_analytics' => true,
                'has_api_access' => true,
                'has_multi_user' => true,
                'has_custom_domain' => true,
                'has_priority_support' => true,
                'trial_days' => 30,
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
```

**Run with:**
```bash
php artisan make:seeder SubscriptionPlansSeeder
# Then paste the code above
php artisan db:seed --class=SubscriptionPlansSeeder
```

---

### PHASE 6: Super Admin Seeder

**File:** `database/seeders/SuperAdminSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuperAdmin;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        SuperAdmin::create([
            'name' => 'Platform Administrator',
            'email' => 'admin@emanager.com',
            'password' => bcrypt('SuperAdmin@123'),
            'phone' => '+977-1-1234567',
            'role' => 'super_admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
```

---

## 📚 COMPLETE IMPLEMENTATION CHECKLIST

### ✅ DONE
- [x] Database schema (7 tables)
- [x] Core models (Tenant, SubscriptionPlan, Subscription, SuperAdmin)
- [x] Migration files
- [x] Tenant database creation logic
- [x] Subscription management helpers

### 📋 TODO - CODE TEMPLATES PROVIDED ABOVE
- [ ] Install Inertia.js (Step 3.1)
- [ ] Create TenantManager service (Step 1.1)
- [ ] Create IdentifyTenant middleware (Step 1.2)
- [ ] Configure super admin authentication (Step 2.1)
- [ ] Create super admin routes (Step 2.2)
- [ ] Create super admin controllers (Step 2.3)
- [ ] Create Vue.js landing page (Step 3.2)
- [ ] Create signup form (Step 3.3)
- [ ] Create API controller (Step 4.1)
- [ ] Run seeders (Phase 5 & 6)

---

## 🚀 QUICK START GUIDE

### Step 1: Run Seeders
```bash
php artisan make:seeder SubscriptionPlansSeeder
php artisan make:seeder SuperAdminSeeder
php artisan db:seed --class=SubscriptionPlansSeeder
php artisan db:seed --class=SuperAdminSeeder
```

### Step 2: Create Services Directory
```bash
mkdir -p app/Services
```
Then create `TenantManager.php` using code from Step 1.1

### Step 3: Create Middleware
```bash
php artisan make:middleware IdentifyTenant
```
Then paste code from Step 1.2

### Step 4: Install Frontend (If you have npm/composer)
```bash
npm install
npm run dev
```

---

## 📖 DETAILED DOCUMENTATION FILES

I'll create additional documentation files for you...







