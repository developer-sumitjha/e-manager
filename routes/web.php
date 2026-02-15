<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;

// IMPORTANT: Domain routes must be defined FIRST to avoid conflicts with root route
// Storefront Routes - Subdomain-based (clean URLs when accessed via subdomain)

// Helper function to define subdomain routes
$defineSubdomainRoutes = function() {
    Route::get('/', [App\Http\Controllers\StorefrontController::class, 'show'])->name('storefront.subdomain.preview');
    // Test route to verify domain routing works
    Route::get('/test-domain', function ($subdomain) {
        return response()->json([
            'subdomain' => $subdomain,
            'hostname' => request()->getHost(),
            'path' => request()->path(),
            'message' => 'Domain route is working'
        ]);
    });
    
    Route::get('/cart', [App\Http\Controllers\StorefrontController::class, 'cart'])->name('storefront.subdomain.cart');
    Route::get('/checkout', [App\Http\Controllers\StorefrontController::class, 'checkout'])->name('storefront.subdomain.checkout');
    Route::get('/checkout/success', [App\Http\Controllers\StorefrontController::class, 'checkoutSuccess'])->name('storefront.subdomain.checkout.success');
    Route::get('/product/{slug}', [App\Http\Controllers\StorefrontController::class, 'product'])->name('storefront.subdomain.product');
    Route::get('/category/{slug}', [App\Http\Controllers\StorefrontController::class, 'category'])->name('storefront.subdomain.category');
    
    // Products archive page - dynamic route (must come after specific routes)
    Route::get('/{slug}', function($subdomain, $slug) {
        // Exclude routes that should be handled by other routes
        $excludedRoutes = ['cart', 'checkout', 'test-domain'];
        if (in_array($slug, $excludedRoutes)) {
            abort(404);
        }
        
        $tenant = \App\Models\Tenant::where('subdomain', $subdomain)->firstOrFail();
        $settings = \App\Models\SiteSettings::where('tenant_id', $tenant->id)->first();
        
        if (!$settings) {
            abort(404, 'Store settings not found');
        }
        
        $archiveSlug = $settings->additional_settings['products_archive_slug'] ?? 'products';
        
        // Debug logging
        \Log::info('Dynamic route check', [
            'subdomain' => $subdomain,
            'slug' => $slug,
            'archiveSlug' => $archiveSlug,
            'match' => strtolower($slug) === strtolower($archiveSlug)
        ]);
        
        // Check if this slug matches the archive slug (case-insensitive comparison)
        if (strtolower($slug) === strtolower($archiveSlug)) {
            return app(\App\Http\Controllers\StorefrontController::class)->productsArchive(request(), $subdomain);
        }
        
        // If not archive, check if it's a category
        $category = \App\Models\Category::where('tenant_id', $tenant->id)->where('slug', $slug)->first();
        if ($category) {
            return app(\App\Http\Controllers\StorefrontController::class)->category(request(), $subdomain, $slug);
        }
        
        // If not category, check if it's a product
        $product = \App\Models\Product::where('tenant_id', $tenant->id)->where('slug', $slug)->first();
        if ($product) {
            return app(\App\Http\Controllers\StorefrontController::class)->product($subdomain, $slug);
        }
        
        // If not product, check if it's a SitePage (custom page)
        $page = \App\Models\SitePage::where('tenant_id', $tenant->id)
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
        if ($page) {
            return app(\App\Http\Controllers\StorefrontController::class)->page($subdomain, $slug);
        }
        
        // Not found
        abort(404);
    })->name('storefront.subdomain.dynamic');
    
    Route::post('/cart/add', [App\Http\Controllers\StorefrontController::class, 'addToCart'])->name('storefront.subdomain.cart.add')->middleware('throttle:30,1');
    Route::post('/cart/update', [App\Http\Controllers\StorefrontController::class, 'updateCart'])->name('storefront.subdomain.cart.update')->middleware('throttle:20,1');
    Route::post('/cart/remove', [App\Http\Controllers\StorefrontController::class, 'removeFromCart'])->name('storefront.subdomain.cart.remove')->middleware('throttle:20,1');
    Route::post('/cart/clear', [App\Http\Controllers\StorefrontController::class, 'clearCart'])->name('storefront.subdomain.cart.clear')->middleware('throttle:10,1');
    Route::post('/checkout/process', [App\Http\Controllers\StorefrontController::class, 'processCheckout'])->name('storefront.subdomain.checkout.process');
    Route::post('/cart/coupon/apply', [App\Http\Controllers\CouponController::class, 'apply'])->name('storefront.subdomain.coupon.apply')->middleware('throttle:10,1');
    Route::post('/cart/coupon/remove', [App\Http\Controllers\CouponController::class, 'remove'])->name('storefront.subdomain.coupon.remove')->middleware('throttle:10,1');
    
    // Contact form submission
    Route::post('/page/{pageId}/contact', [App\Http\Controllers\StorefrontController::class, 'submitContact'])->name('storefront.subdomain.contact.submit')->middleware('throttle:10,1');
};

// Localhost routes (for development)
Route::domain('{subdomain}.localhost')->group($defineSubdomainRoutes);

// Production domain routes (for live website)
// Get the main domain from APP_URL or use a wildcard pattern
$appUrl = config('app.url', 'http://localhost');
$parsedUrl = parse_url($appUrl);
$mainDomain = $parsedUrl['host'] ?? null;

// If we have a main domain and it's not localhost, register production routes
if ($mainDomain && $mainDomain !== 'localhost' && !str_contains($mainDomain, 'localhost')) {
    // Extract the domain without subdomain (e.g., example.com from subdomain.example.com)
    $domainParts = explode('.', $mainDomain);
    if (count($domainParts) >= 2) {
        // Get the main domain (last 2 parts: example.com)
        $baseDomain = $domainParts[count($domainParts) - 2] . '.' . $domainParts[count($domainParts) - 1];
        // Register routes for production domain with wildcard subdomain
        Route::domain('{subdomain}.' . $baseDomain)->group($defineSubdomainRoutes);
    }
}

// Public Routes - Super Admin Public Frontend (Landing Page)
// This route should only handle non-subdomain requests (main domain)
// IMPORTANT: This must come AFTER subdomain routes to avoid conflicts
Route::get('/', function (\Illuminate\Http\Request $request) {
    // Get hostname - try multiple methods for reliability
    $host = $request->getHost();
    $hostHeader = $request->header('Host');
    $httpHost = $request->server('HTTP_HOST');
    $serverName = $request->server('SERVER_NAME');
    
    // Use the most reliable source, prioritizing HTTP_HOST
    $hostname = $httpHost ?: ($hostHeader ?: ($host ?: $serverName));
    
    // Remove port if present (e.g., primax.claudnova.com:443 -> primax.claudnova.com)
    $hostname = preg_replace('/:\d+$/', '', $hostname);
    
    // Extract subdomain
    $parts = explode('.', $hostname);
    
    // Remove 'www' prefix if present
    if (isset($parts[0]) && strtolower($parts[0]) === 'www') {
        $parts = array_slice($parts, 1);
    }
    
    // Check if it's a pure IP address (no subdomain possible)
    $isIpAddress = filter_var($hostname, FILTER_VALIDATE_IP) !== false;
    
    $subdomain = null;
    if (!$isIpAddress && count($parts) > 1) {
        // Handle localhost subdomains (e.g., vendor1.localhost)
        $isLocalhostSubdomain = (count($parts) === 2 && strtolower(end($parts)) === 'localhost');
        
        // Handle production subdomains (e.g., vendor1.example.com or primax.claudnova.com)
        // For production: if we have 3+ parts, the first is the subdomain
        $isProductionSubdomain = count($parts) >= 3;
        
        if ($isLocalhostSubdomain || $isProductionSubdomain) {
            $subdomain = $parts[0];
            
            // Skip special subdomains
            if (in_array(strtolower($subdomain), ['www', 'super', 'admin'])) {
                $subdomain = null;
            }
        }
    }
    
    // Log for debugging (remove in production if not needed)
    \Log::info('Root Route - Subdomain Detection', [
        'hostname' => $hostname,
        'host' => $host,
        'hostHeader' => $hostHeader,
        'httpHost' => $httpHost,
        'serverName' => $serverName,
        'parts' => $parts,
        'extracted_subdomain' => $subdomain,
        'is_ip' => $isIpAddress,
        'is_localhost_subdomain' => isset($isLocalhostSubdomain) ? $isLocalhostSubdomain : false,
        'is_production_subdomain' => isset($isProductionSubdomain) ? $isProductionSubdomain : false,
    ]);
    
    // If we have a subdomain, check if it's a valid tenant and show storefront directly
    if ($subdomain !== null) {
        $tenant = \App\Models\Tenant::whereRaw('LOWER(subdomain) = ?', [strtolower($subdomain)])
            ->whereIn('status', ['trial', 'active'])
            ->first();
        
        if ($tenant) {
            // Log successful tenant match
            \Log::info('Root Route - Tenant Found', [
                'subdomain' => $subdomain,
                'tenant_id' => $tenant->id,
                'tenant_name' => $tenant->name,
            ]);
            
            // Show storefront directly without redirect
            $storefrontController = app(\App\Http\Controllers\StorefrontController::class);
            return $storefrontController->show($tenant->subdomain);
        } else {
            // Log when subdomain found but no tenant
            \Log::warning('Root Route - Subdomain Found But No Tenant', [
                'subdomain' => $subdomain,
                'hostname' => $hostname,
            ]);
        }
    }
    
    // No valid subdomain or tenant found, show main landing page
    return view('welcome');
})->name('public.landing');

// Test root route logic - mirrors the exact root route logic
Route::get('/test-root-logic', function (\Illuminate\Http\Request $request) {
    // This is the EXACT same logic as the root route
    $host = $request->getHost();
    $hostHeader = $request->header('Host');
    $httpHost = $request->server('HTTP_HOST');
    $serverName = $request->server('SERVER_NAME');
    
    $hostname = $httpHost ?: ($hostHeader ?: ($host ?: $serverName));
    $hostname = preg_replace('/:\d+$/', '', $hostname);
    
    $parts = explode('.', $hostname);
    
    if (isset($parts[0]) && strtolower($parts[0]) === 'www') {
        $parts = array_slice($parts, 1);
    }
    
    $isIpAddress = filter_var($hostname, FILTER_VALIDATE_IP) !== false;
    
    $subdomain = null;
    if (!$isIpAddress && count($parts) > 1) {
        $isLocalhostSubdomain = (count($parts) === 2 && strtolower(end($parts)) === 'localhost');
        $isProductionSubdomain = count($parts) >= 3;
        
        if ($isLocalhostSubdomain || $isProductionSubdomain) {
            $subdomain = $parts[0];
            
            if (in_array(strtolower($subdomain), ['www', 'super', 'admin'])) {
                $subdomain = null;
            }
        }
    }
    
    $tenant = null;
    $willShowStorefront = false;
    if ($subdomain !== null) {
        $tenant = \App\Models\Tenant::whereRaw('LOWER(subdomain) = ?', [strtolower($subdomain)])
            ->whereIn('status', ['trial', 'active'])
            ->first();
        
        if ($tenant) {
            $willShowStorefront = true;
        }
    }
    
    return response()->json([
        'root_route_logic' => [
            'hostname' => $hostname,
            'parts' => $parts,
            'parts_count' => count($parts),
            'extracted_subdomain' => $subdomain,
            'is_ip' => $isIpAddress,
            'is_localhost_subdomain' => isset($isLocalhostSubdomain) ? $isLocalhostSubdomain : false,
            'is_production_subdomain' => isset($isProductionSubdomain) ? $isProductionSubdomain : false,
        ],
        'tenant_check' => [
            'tenant_found' => $tenant !== null,
            'tenant_id' => $tenant ? $tenant->id : null,
            'tenant_subdomain' => $tenant ? $tenant->subdomain : null,
            'tenant_status' => $tenant ? $tenant->status : null,
            'will_show_storefront' => $willShowStorefront,
        ],
        'message' => $willShowStorefront 
            ? 'Root route SHOULD show storefront for this subdomain' 
            : 'Root route will show welcome page',
    ], JSON_PRETTY_PRINT);
});

// Debug route - Enhanced for subdomain detection
Route::get('/debug', function (\Illuminate\Http\Request $request) {
    // Get hostname - try multiple methods
    $host = $request->getHost();
    $hostHeader = $request->header('Host');
    $httpHost = $request->server('HTTP_HOST');
    $serverName = $request->server('SERVER_NAME');
    
    $hostname = $httpHost ?: ($hostHeader ?: ($host ?: $serverName));
    $hostname = preg_replace('/:\d+$/', '', $hostname);
    
    $parts = explode('.', $hostname);
    $partsWithoutWww = $parts;
    if (isset($parts[0]) && strtolower($parts[0]) === 'www') {
        $partsWithoutWww = array_slice($parts, 1);
    }
    
    $isIpAddress = filter_var($hostname, FILTER_VALIDATE_IP) !== false;
    $isLocalhostSubdomain = (count($partsWithoutWww) === 2 && strtolower(end($partsWithoutWww)) === 'localhost');
    $isProductionSubdomain = count($partsWithoutWww) >= 3;
    
    $extractedSubdomain = null;
    if (!$isIpAddress && ($isLocalhostSubdomain || $isProductionSubdomain)) {
        $extractedSubdomain = $partsWithoutWww[0];
        if (in_array(strtolower($extractedSubdomain), ['www', 'super', 'admin'])) {
            $extractedSubdomain = null;
        }
    }
    
    // Check for tenant
    $tenant = null;
    if ($extractedSubdomain) {
        $tenant = \App\Models\Tenant::whereRaw('LOWER(subdomain) = ?', [strtolower($extractedSubdomain)])
            ->whereIn('status', ['trial', 'active'])
            ->first();
    }
    
    // Get all tenants for reference
    $allTenants = \App\Models\Tenant::select('id', 'name', 'subdomain', 'status')->get();
    
    return response()->json([
        'hostname_detection' => [
            'getHost()' => $host,
            'header(Host)' => $hostHeader,
            'HTTP_HOST' => $httpHost,
            'SERVER_NAME' => $serverName,
            'final_hostname' => $hostname,
            'full_url' => $request->fullUrl(),
        ],
        'subdomain_extraction' => [
            'host_parts' => $parts,
            'host_parts_without_www' => $partsWithoutWww,
            'extracted_subdomain' => $extractedSubdomain,
            'is_ip' => $isIpAddress,
            'is_localhost_subdomain' => $isLocalhostSubdomain,
            'is_production_subdomain' => $isProductionSubdomain,
        ],
        'tenant_match' => [
            'subdomain' => $extractedSubdomain,
            'tenant_found' => $tenant !== null,
            'tenant_id' => $tenant ? $tenant->id : null,
            'tenant_name' => $tenant ? $tenant->name : null,
            'tenant_subdomain' => $tenant ? $tenant->subdomain : null,
            'tenant_status' => $tenant ? $tenant->status : null,
        ],
        'all_tenants' => $allTenants,
        'app_url' => config('app.url'),
    ], JSON_PRETTY_PRINT);
});

// Test hostname extraction route (for debugging vendor login subdomain issue)
Route::get('/test-hostname', function (\Illuminate\Http\Request $request) {
    $host = $request->getHost();
    $hostHeader = $request->header('Host');
    $httpHost = $request->server('HTTP_HOST');
    $serverName = $request->server('SERVER_NAME');
    $hostname = $host ?: ($hostHeader ?: $httpHost);
    $parts = explode('.', $hostname);
    
    // Remove www if present
    $partsWithoutWww = $parts;
    if (isset($parts[0]) && $parts[0] === 'www') {
        $partsWithoutWww = array_slice($parts, 1);
    }
    
    return response()->json([
        'getHost()' => $host,
        'header(Host)' => $hostHeader,
        'HTTP_HOST' => $httpHost,
        'SERVER_NAME' => $serverName,
        'final_hostname' => $hostname,
        'full_url' => $request->fullUrl(),
        'host_parts' => $parts,
        'host_parts_without_www' => $partsWithoutWww,
        'extracted_subdomain' => count($partsWithoutWww) >= 3 ? $partsWithoutWww[0] : (count($partsWithoutWww) === 2 && end($partsWithoutWww) === 'localhost' ? $partsWithoutWww[0] : null),
        'is_localhost_subdomain' => count($partsWithoutWww) === 2 && end($partsWithoutWww) === 'localhost',
        'is_production_subdomain' => count($partsWithoutWww) >= 3,
    ]);
});

// Test storefront route
Route::get('/test-storefront/{subdomain}', function ($subdomain) {
    return response()->json([
        'subdomain' => $subdomain,
        'message' => 'Storefront route is working'
    ]);
});
Route::get('/signup', [App\Http\Controllers\Public\LandingController::class, 'signup'])->name('public.signup');
Route::post('/signup', [App\Http\Controllers\Public\LandingController::class, 'submitSignup'])->name('public.signup.submit');
Route::get('/pricing', [App\Http\Controllers\Public\LandingController::class, 'pricing'])->name('public.pricing');
Route::get('/test-api', function() { return view('public.test-api'); })->name('public.test-api');
Route::view('/services', 'public.services')->name('public.services');
Route::view('/about', 'public.about')->name('public.about');
Route::view('/contact', 'public.contact')->name('public.contact');


// Storefront Routes - Path-based (for when accessed without subdomain, e.g., localhost/storefront/primax/...)
Route::prefix('storefront/{subdomain}')->group(function () {
    Route::get('/', [App\Http\Controllers\StorefrontController::class, 'show'])->name('storefront.preview');
    
    // Products archive page - dynamic route based on slug
    Route::get('/{slug}', function($subdomain, $slug) {
        $tenant = \App\Models\Tenant::where('subdomain', $subdomain)->firstOrFail();
        $settings = \App\Models\SiteSettings::where('tenant_id', $tenant->id)->first();
        
        if (!$settings) {
            abort(404, 'Store settings not found');
        }
        
        $archiveSlug = $settings->additional_settings['products_archive_slug'] ?? 'products';
        
        // Check if this slug matches the archive slug (case-insensitive comparison)
        if (strtolower($slug) === strtolower($archiveSlug)) {
            return app(\App\Http\Controllers\StorefrontController::class)->productsArchive(request(), $subdomain);
        }
        
        // If not archive, check if it's a category
        $category = \App\Models\Category::where('tenant_id', $tenant->id)->where('slug', $slug)->first();
        if ($category) {
            return app(\App\Http\Controllers\StorefrontController::class)->category(request(), $subdomain, $slug);
        }
        
        // If not category, check if it's a product
        $product = \App\Models\Product::where('tenant_id', $tenant->id)->where('slug', $slug)->first();
        if ($product) {
            return app(\App\Http\Controllers\StorefrontController::class)->product($subdomain, $slug);
        }
        
        // If not product, check if it's a SitePage (custom page)
        $page = \App\Models\SitePage::where('tenant_id', $tenant->id)
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
        if ($page) {
            return app(\App\Http\Controllers\StorefrontController::class)->page($subdomain, $slug);
        }
        
        // Not found
        abort(404);
    })->name('storefront.dynamic');
    
    Route::get('/product/{slug}', [App\Http\Controllers\StorefrontController::class, 'product'])->name('storefront.product');
    Route::post('/product/{product}/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->middleware(['auth:web'])->name('storefront.product.reviews.store');
    Route::get('/cart', [App\Http\Controllers\StorefrontController::class, 'cart'])->name('storefront.cart');
    Route::post('/cart/add', [App\Http\Controllers\StorefrontController::class, 'addToCart'])->name('storefront.cart.add')->middleware('throttle:30,1');
    Route::post('/cart/update', [App\Http\Controllers\StorefrontController::class, 'updateCart'])->name('storefront.cart.update')->middleware('throttle:20,1');
            Route::post('/cart/remove', [App\Http\Controllers\StorefrontController::class, 'removeFromCart'])->name('storefront.cart.remove')->middleware('throttle:20,1');
            Route::post('/cart/clear', [App\Http\Controllers\StorefrontController::class, 'clearCart'])->name('storefront.cart.clear')->middleware('throttle:10,1');
            Route::post('/cart/coupon/apply', [App\Http\Controllers\CouponController::class, 'apply'])->name('storefront.coupon.apply')->middleware('throttle:10,1');
            Route::post('/cart/coupon/remove', [App\Http\Controllers\CouponController::class, 'remove'])->name('storefront.coupon.remove')->middleware('throttle:10,1');
    
    // Contact form submission
    Route::post('/page/{pageId}/contact', [App\Http\Controllers\StorefrontController::class, 'submitContact'])->name('storefront.contact.submit')->middleware('throttle:10,1');
    
    Route::get('/checkout', [App\Http\Controllers\StorefrontController::class, 'checkout'])->name('storefront.checkout');
    Route::post('/checkout/process', [App\Http\Controllers\StorefrontController::class, 'processCheckout'])->name('storefront.checkout.process');
    Route::get('/checkout/success', [App\Http\Controllers\StorefrontController::class, 'checkoutSuccess'])->name('storefront.checkout.success');
    Route::get('/category/{slug}', [App\Http\Controllers\StorefrontController::class, 'category'])->name('storefront.category');
    Route::get('/sitemap.xml', [App\Http\Controllers\StorefrontController::class, 'sitemap'])->name('storefront.sitemap');
    
    // Customer Authentication Routes
    Route::get('/login', [App\Http\Controllers\Customer\AuthController::class, 'showLogin'])->name('customer.login');
            Route::post('/login', [App\Http\Controllers\Customer\AuthController::class, 'login'])->middleware('throttle:5,1');
            Route::get('/register', [App\Http\Controllers\Customer\AuthController::class, 'showRegister'])->name('customer.register');
            Route::post('/register', [App\Http\Controllers\Customer\AuthController::class, 'register'])->middleware('throttle:3,1');
            Route::post('/logout', [App\Http\Controllers\Customer\AuthController::class, 'logout'])->name('customer.logout');
    
    // Customer Account Routes (protected)
    Route::middleware(['auth:web', 'customer'])->group(function () {
        Route::get('/account', [App\Http\Controllers\Customer\AccountController::class, 'dashboard'])->name('customer.dashboard');
        Route::get('/account/profile', [App\Http\Controllers\Customer\AccountController::class, 'profile'])->name('customer.profile');
        Route::post('/account/profile', [App\Http\Controllers\Customer\AccountController::class, 'updateProfile']);
        Route::get('/account/addresses', [App\Http\Controllers\Customer\AccountController::class, 'addresses'])->name('customer.addresses');
        Route::post('/account/addresses', [App\Http\Controllers\Customer\AccountController::class, 'storeAddress']);
        Route::put('/account/addresses/{address}', [App\Http\Controllers\Customer\AccountController::class, 'updateAddress']);
        Route::delete('/account/addresses/{address}', [App\Http\Controllers\Customer\AccountController::class, 'deleteAddress']);
        Route::get('/account/orders', [App\Http\Controllers\Customer\AccountController::class, 'orders'])->name('customer.orders');
        Route::get('/account/orders/{order}', [App\Http\Controllers\Customer\AccountController::class, 'orderDetail'])->name('customer.order.detail');
    });
});

// Payment Routes
Route::post('/payment/initiate', [App\Http\Controllers\PaymentController::class, 'initiatePayment'])->name('payment.initiate');
Route::match(['GET','POST'],'/payment/return/{method}', [App\Http\Controllers\PaymentController::class, 'paymentReturn'])->name('payment.return');
Route::post('/payment/callback/{method}', [App\Http\Controllers\PaymentController::class, 'paymentCallback'])->name('payment.callback');
Route::get('/payment/esewa/success', [App\Http\Controllers\PaymentController::class, 'esewaSuccess'])->name('payment.esewa.success');
Route::get('/payment/esewa/failure', function() { return redirect()->back()->with('error', 'Payment cancelled'); })->name('payment.esewa.failure');
Route::get('/payment/khalti/verify', [App\Http\Controllers\PaymentController::class, 'khaltiVerify'])->name('payment.khalti.verify');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Admin Routes
Route::middleware(['auth', 'admin_employee', 'subscription.active'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Products
    Route::resource('products', ProductController::class)
        ->middleware(['plan.limit:products', 'employee.can:products']);
    Route::post('products/{product}/delete', [ProductController::class, 'destroyJson'])
        ->name('products.destroy.json')
        ->middleware(['employee.can:products']);
    Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::post('products/{product}/duplicate', [ProductController::class, 'duplicate'])->name('products.duplicate')->middleware('plan.limit:products');
    Route::post('products/bulk-action', [ProductController::class, 'bulkAction'])->name('products.bulk-action');
    
    // Stock Adjustments
    Route::resource('stock-adjustments', App\Http\Controllers\Admin\StockAdjustmentController::class)
        ->middleware('employee.can:inventory');
    Route::get('stock-adjustments/product/{product}/stock', [App\Http\Controllers\Admin\StockAdjustmentController::class, 'getProductStock'])->name('stock-adjustments.product-stock');
    
    // Orders
    Route::resource('orders', OrderController::class)
        ->middleware(['plan.limit:orders', 'employee.can:orders']);
    Route::post('orders/bulk-action', [OrderController::class, 'bulkAction'])->name('orders.bulk-action');
    Route::match(['put', 'post'], 'orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('orders/{order}/export', [OrderController::class, 'export'])->name('orders.export');
    
    // Pending Orders - Custom routes first (before resource routes)
    Route::post('pending-orders/{pending_order}/confirm', [App\Http\Controllers\Admin\PendingOrderController::class, 'confirm'])->name('pending-orders.confirm');
    Route::post('pending-orders/{pending_order}/reject', [App\Http\Controllers\Admin\PendingOrderController::class, 'reject'])->name('pending-orders.reject');
    Route::post('pending-orders/bulk-action', [App\Http\Controllers\Admin\PendingOrderController::class, 'bulkAction'])->name('pending-orders.bulk-action');
    Route::get('pending-orders/bulk/create', [App\Http\Controllers\Admin\PendingOrderController::class, 'createBulk'])->name('pending-orders.create-bulk');
    Route::post('pending-orders/bulk', [App\Http\Controllers\Admin\PendingOrderController::class, 'storeBulk'])->name('pending-orders.store-bulk');
    Route::get('rejected-orders', [App\Http\Controllers\Admin\PendingOrderController::class, 'rejectedOrders'])->name('rejected-orders.index');
    
    // Trash Management for Pending Orders
    Route::get('pending-orders/trash', [App\Http\Controllers\Admin\PendingOrderController::class, 'trash'])->name('pending-orders.trash');
    Route::post('pending-orders/{id}/restore', [App\Http\Controllers\Admin\PendingOrderController::class, 'restore'])->name('pending-orders.restore');
    Route::delete('pending-orders/{id}/force-delete', [App\Http\Controllers\Admin\PendingOrderController::class, 'forceDelete'])->name('pending-orders.force-delete');
    
    // Pending Orders - Resource routes last
    Route::resource('pending-orders', App\Http\Controllers\Admin\PendingOrderController::class);
    
    // Shipments
    Route::resource('shipments', App\Http\Controllers\Admin\ShipmentController::class)
        ->middleware('employee.can:shipments');
    Route::post('shipments/allot', [App\Http\Controllers\Admin\ShipmentController::class, 'allotShipments'])->name('shipments.allot');
    Route::post('shipments/{shipment}/update-status', [App\Http\Controllers\Admin\ShipmentController::class, 'updateStatus'])->name('shipments.update-status');
    
    // Gaaubesi Logistics
    Route::get('gaaubesi', [App\Http\Controllers\Admin\GaaubesiController::class, 'index'])->name('gaaubesi.index')->middleware('employee.can:manual_delivery');
    
    // Gaaubesi - Specific routes BEFORE parameterized routes
    Route::get('gaaubesi/create', [App\Http\Controllers\Admin\GaaubesiController::class, 'create'])->name('gaaubesi.create');
    Route::get('gaaubesi/bulk-create', [App\Http\Controllers\Admin\GaaubesiController::class, 'bulkCreateForm'])->name('gaaubesi.bulk-create');
    Route::post('gaaubesi/bulk-create', [App\Http\Controllers\Admin\GaaubesiController::class, 'bulkCreate'])->name('gaaubesi.bulk-create.store');
    Route::get('gaaubesi/service-stations', [App\Http\Controllers\Admin\GaaubesiController::class, 'serviceStations'])->name('gaaubesi.service-stations');
    Route::get('gaaubesi/comments', [App\Http\Controllers\Admin\GaaubesiController::class, 'comments'])->name('gaaubesi.comments');
    Route::get('gaaubesi/cod-settlement', [App\Http\Controllers\Admin\GaaubesiController::class, 'codSettlement'])->name('gaaubesi.cod-settlement');
    Route::get('gaaubesi/analytics', [App\Http\Controllers\Admin\GaaubesiController::class, 'analytics'])->name('gaaubesi.analytics');
    Route::get('gaaubesi/notifications', [App\Http\Controllers\Admin\GaaubesiController::class, 'notifications'])->name('gaaubesi.notifications');
    Route::get('gaaubesi/settings', [App\Http\Controllers\Admin\GaaubesiController::class, 'settings'])->name('gaaubesi.settings');
    Route::post('gaaubesi/settings', [App\Http\Controllers\Admin\GaaubesiController::class, 'updateSettings'])->name('gaaubesi.update-settings');
    Route::get('gaaubesi-locations', [App\Http\Controllers\Admin\GaaubesiController::class, 'getLocations'])->name('gaaubesi.locations');
    Route::get('gaaubesi/test-connection', [App\Http\Controllers\Admin\GaaubesiController::class, 'testConnection'])->name('gaaubesi.test-connection');
    
    // Gaaubesi - Non-parameterized POST routes
    Route::post('gaaubesi', [App\Http\Controllers\Admin\GaaubesiController::class, 'store'])->name('gaaubesi.store');
    
    // Gaaubesi - Parameterized routes LAST
    Route::get('gaaubesi/{gaaubesiShipment}', [App\Http\Controllers\Admin\GaaubesiController::class, 'show'])->name('gaaubesi.show');

    // Pathao Logistics
    Route::get('pathao', [App\Http\Controllers\Admin\PathaoController::class, 'index'])->name('pathao.index')->middleware('employee.can:manual_delivery');
    
    // Pathao - Specific routes BEFORE parameterized routes
    Route::get('pathao/create', [App\Http\Controllers\Admin\PathaoController::class, 'create'])->name('pathao.create')->middleware('employee.can:manual_delivery');
    Route::get('pathao/bulk-create', [App\Http\Controllers\Admin\PathaoController::class, 'bulkCreateForm'])->name('pathao.bulk-create')->middleware('employee.can:manual_delivery');
    Route::post('pathao/bulk-create', [App\Http\Controllers\Admin\PathaoController::class, 'bulkCreate'])->name('pathao.bulk-create.store')->middleware('employee.can:manual_delivery');
    Route::post('pathao', [App\Http\Controllers\Admin\PathaoController::class, 'store'])->name('pathao.store')->middleware('employee.can:manual_delivery');
    Route::get('pathao/settings', [App\Http\Controllers\Admin\PathaoController::class, 'settings'])->name('pathao.settings');
    Route::post('pathao/settings', [App\Http\Controllers\Admin\PathaoController::class, 'updateSettings'])->name('pathao.update-settings');
    Route::get('pathao/cities', [App\Http\Controllers\Admin\PathaoController::class, 'getCities'])->name('pathao.cities');
    Route::post('pathao/zones', [App\Http\Controllers\Admin\PathaoController::class, 'getZones'])->name('pathao.zones');
    Route::post('pathao/areas', [App\Http\Controllers\Admin\PathaoController::class, 'getAreas'])->name('pathao.areas');
    Route::post('pathao/test-connection', [App\Http\Controllers\Admin\PathaoController::class, 'testConnection'])->name('pathao.test-connection');
    Route::post('pathao/{pathaoShipment}/refresh-status', [App\Http\Controllers\Admin\PathaoController::class, 'refreshStatus'])->name('pathao.refresh-status')->middleware('employee.can:manual_delivery');

    // Pathao - Parameterized routes LAST
    Route::get('pathao/{pathaoShipment}', [App\Http\Controllers\Admin\PathaoController::class, 'show'])->name('pathao.show')->middleware('employee.can:manual_delivery');
    Route::post('gaaubesi/{gaaubesiShipment}/refresh-status', [App\Http\Controllers\Admin\GaaubesiController::class, 'refreshStatus'])->name('gaaubesi.refresh-status');
    Route::post('gaaubesi/{gaaubesiShipment}/post-comment', [App\Http\Controllers\Admin\GaaubesiController::class, 'postComment'])->name('gaaubesi.post-comment');
    Route::post('gaaubesi/{gaaubesiShipment}/mark-cod-settled', [App\Http\Controllers\Admin\GaaubesiController::class, 'markCodSettled'])->name('gaaubesi.mark-cod-settled');
    Route::post('gaaubesi/{gaaubesiShipment}/add-comment', [App\Http\Controllers\Admin\GaaubesiController::class, 'addComment'])->name('gaaubesi.add-comment');
    Route::get('gaaubesi/{gaaubesiShipment}/download-label', [App\Http\Controllers\Admin\GaaubesiController::class, 'downloadLabel'])->name('gaaubesi.download-label');
    
    // Manual Delivery
    Route::get('manual-delivery', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'index'])->name('manual-delivery.index')->middleware('employee.can:manual_delivery');
    Route::get('manual-delivery/deliveries', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'deliveries'])->name('manual-delivery.deliveries');
    Route::get('manual-delivery/activities', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'activities'])->name('manual-delivery.activities');
    Route::get('manual-delivery/performance', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'performance'])->name('manual-delivery.performance');
    
    // Order Allocation (must come before parameterized routes)
    Route::get('manual-delivery/allocation', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'allocation'])->name('manual-delivery.allocation');
    Route::post('manual-delivery/allocate', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'allocateOrder'])->name('manual-delivery.allocate');
    Route::post('manual-delivery/bulk-allocate', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'bulkAllocate'])->name('manual-delivery.bulk-allocate');
    
    // Delivery Boy Wise Views (must come before parameterized routes)
    Route::get('manual-delivery/delivery-boy-wise', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'deliveryBoyWise'])->name('manual-delivery.delivery-boy-wise');
    Route::get('manual-delivery/delivery-boy/{deliveryBoy}/deliveries', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'deliveryBoyDeliveries'])->name('manual-delivery.boy-deliveries');
    Route::post('manual-delivery/deliveries/{manualDelivery}/update-status', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'updateDeliveryStatus'])->name('manual-delivery.update-delivery-status');
    
    // COD Settlements (must come before parameterized routes)
    Route::get('manual-delivery/cod-settlements', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'codSettlements'])->name('manual-delivery.cod-settlements');
    Route::get('manual-delivery/cod-settlements/{deliveryBoy}/create', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'createCodSettlement'])->name('manual-delivery.create-settlement');
    Route::post('manual-delivery/cod-settlements/{deliveryBoy}', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'storeCodSettlement'])->name('manual-delivery.store-settlement');
    
    // Delivery Boy Analytics (must come before parameterized routes)
    Route::get('manual-delivery/delivery-boy/{deliveryBoy}/analytics', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'deliveryBoyAnalytics'])->name('manual-delivery.boy-analytics');
    
    // Delivery Boys Management (must come before parameterized routes)
    Route::get('manual-delivery/delivery-boys', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'deliveryBoys'])->name('manual-delivery.delivery-boys');
    Route::post('manual-delivery/delivery-boys', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'storeDeliveryBoy'])->name('manual-delivery.store-delivery-boy');
    Route::post('manual-delivery/delivery-boy/{deliveryBoy}/update', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'updateDeliveryBoy'])->name('manual-delivery.update-delivery-boy');
    Route::post('manual-delivery/delivery-boy/{deliveryBoy}/status', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'updateDeliveryBoyStatus'])->name('manual-delivery.update-boy-status');
    
    // Parameterized routes (must come after all specific routes)
    Route::get('manual-delivery/{manualDelivery}', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'show'])->name('manual-delivery.show');
    Route::get('manual-delivery/{manualDelivery}/edit', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'edit'])->name('manual-delivery.edit');
    Route::put('manual-delivery/{manualDelivery}', [App\Http\Controllers\Admin\ManualDeliveryController::class, 'update'])->name('manual-delivery.update');
    
    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('index');
        Route::get('/general', [App\Http\Controllers\Admin\SettingsController::class, 'general'])->name('general');
        Route::get('/email', [App\Http\Controllers\Admin\SettingsController::class, 'email'])->name('email');
        Route::get('/payment', [App\Http\Controllers\Admin\SettingsController::class, 'payment'])->name('payment');
        Route::get('/notification', [App\Http\Controllers\Admin\SettingsController::class, 'notification'])->name('notification');
        Route::get('/shipping', [App\Http\Controllers\Admin\SettingsController::class, 'shipping'])->name('shipping');
        Route::get('/tax', [App\Http\Controllers\Admin\SettingsController::class, 'tax'])->name('tax');
        Route::get('/order', [App\Http\Controllers\Admin\SettingsController::class, 'order'])->name('order');
        Route::get('/security', [App\Http\Controllers\Admin\SettingsController::class, 'security'])->name('security');
        Route::get('/api', [App\Http\Controllers\Admin\SettingsController::class, 'api'])->name('api');
        Route::get('/system', [App\Http\Controllers\Admin\SettingsController::class, 'system'])->name('system');
        
        Route::post('/update', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('update');
        Route::post('/clear-cache', [App\Http\Controllers\Admin\SettingsController::class, 'clearCache'])->name('clear-cache');
        Route::post('/optimize', [App\Http\Controllers\Admin\SettingsController::class, 'optimize'])->name('optimize');
        Route::post('/test-email', [App\Http\Controllers\Admin\SettingsController::class, 'testEmail'])->name('test-email');
    });
    
    // Site Builder
    Route::prefix('site-builder')->name('site-builder.')->middleware('employee.can:site_builder')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\SiteBuilderController::class, 'index'])->name('index');
        Route::post('/basic-info', [App\Http\Controllers\Admin\SiteBuilderController::class, 'updateBasicInfo'])->name('update-basic-info');
        Route::post('/theme', [App\Http\Controllers\Admin\SiteBuilderController::class, 'updateTheme'])->name('update-theme');
        Route::post('/logo', [App\Http\Controllers\Admin\SiteBuilderController::class, 'uploadLogo'])->name('upload-logo');
        Route::post('/favicon', [App\Http\Controllers\Admin\SiteBuilderController::class, 'uploadFavicon'])->name('upload-favicon');
        Route::post('/banner', [App\Http\Controllers\Admin\SiteBuilderController::class, 'updateBanner'])->name('update-banner');
        Route::post('/banner-image', [App\Http\Controllers\Admin\SiteBuilderController::class, 'uploadBannerImage'])->name('upload-banner-image');
        Route::post('/navigation', [App\Http\Controllers\Admin\SiteBuilderController::class, 'updateNavigation'])->name('update-navigation');
        Route::post('/homepage', [App\Http\Controllers\Admin\SiteBuilderController::class, 'updateHomepage'])->name('update-homepage');
        Route::post('/slide-image', [App\Http\Controllers\Admin\SiteBuilderController::class, 'uploadSlideImage'])->name('upload-slide-image');
        Route::post('/products', [App\Http\Controllers\Admin\SiteBuilderController::class, 'updateProducts'])->name('update-products');
        Route::post('/footer', [App\Http\Controllers\Admin\SiteBuilderController::class, 'updateFooter'])->name('update-footer');
        Route::post('/seo', [App\Http\Controllers\Admin\SiteBuilderController::class, 'updateSeo'])->name('update-seo');
        Route::post('/og-image', [App\Http\Controllers\Admin\SiteBuilderController::class, 'uploadOgImage'])->name('upload-og-image');
        Route::post('/ecommerce', [App\Http\Controllers\Admin\SiteBuilderController::class, 'updateEcommerce'])->name('update-ecommerce');
        Route::post('/custom-code', [App\Http\Controllers\Admin\SiteBuilderController::class, 'updateCustomCode'])->name('update-custom-code');
        Route::post('/notifications', [App\Http\Controllers\Admin\SiteBuilderController::class, 'updateNotifications'])->name('update-notifications');
        Route::post('/maintenance', [App\Http\Controllers\Admin\SiteBuilderController::class, 'updateMaintenance'])->name('update-maintenance');
        Route::get('/preview-url', [App\Http\Controllers\Admin\SiteBuilderController::class, 'getPreviewUrl'])->name('preview-url');
    });
    
    // Site Pages Management
    Route::resource('site-pages', App\Http\Controllers\Admin\SitePageController::class);
    
    // Accounting
    Route::get('accounting', [App\Http\Controllers\Admin\AccountingController::class, 'index'])->name('accounting.index')->middleware('employee.can:accounting');
    Route::get('accounting/accounts', [App\Http\Controllers\Admin\AccountingController::class, 'accounts'])->name('accounting.accounts');
    Route::get('accounting/accounts/create', [App\Http\Controllers\Admin\AccountingController::class, 'createAccount'])->name('accounting.accounts.create');
    Route::post('accounting/accounts', [App\Http\Controllers\Admin\AccountingController::class, 'storeAccount'])->name('accounting.accounts.store');
    Route::get('accounting/accounts/{account}/edit', [App\Http\Controllers\Admin\AccountingController::class, 'editAccount'])->name('accounting.accounts.edit');
    Route::put('accounting/accounts/{account}', [App\Http\Controllers\Admin\AccountingController::class, 'updateAccount'])->name('accounting.accounts.update');
    Route::delete('accounting/accounts/{account}', [App\Http\Controllers\Admin\AccountingController::class, 'destroyAccount'])->name('accounting.accounts.destroy');
    Route::get('accounting/sales', [App\Http\Controllers\Admin\AccountingController::class, 'sales'])->name('accounting.sales');
    Route::get('accounting/sales/create-invoice', [App\Http\Controllers\Admin\AccountingController::class, 'createInvoice'])->name('accounting.create-invoice');
    Route::post('accounting/sales/store-invoice', [App\Http\Controllers\Admin\AccountingController::class, 'storeInvoice'])->name('accounting.store-invoice');
    Route::get('accounting/invoices/{invoice}', [App\Http\Controllers\Admin\AccountingController::class, 'showInvoice'])->name('accounting.show-invoice');
    Route::get('accounting/invoices/{invoice}/edit', [App\Http\Controllers\Admin\AccountingController::class, 'editInvoice'])->name('accounting.edit-invoice');
    Route::put('accounting/invoices/{invoice}', [App\Http\Controllers\Admin\AccountingController::class, 'updateInvoice'])->name('accounting.update-invoice');
    Route::delete('accounting/invoices/{invoice}', [App\Http\Controllers\Admin\AccountingController::class, 'destroyInvoice'])->name('accounting.destroy-invoice');
    Route::get('accounting/purchases', [App\Http\Controllers\Admin\AccountingController::class, 'purchases'])->name('accounting.purchases');
    Route::get('accounting/purchases/create', [App\Http\Controllers\Admin\AccountingController::class, 'createPurchase'])->name('accounting.purchases.create');
    Route::post('accounting/purchases', [App\Http\Controllers\Admin\AccountingController::class, 'storePurchase'])->name('accounting.purchases.store');
    Route::get('accounting/purchases/{purchase}/edit', [App\Http\Controllers\Admin\AccountingController::class, 'editPurchase'])->name('accounting.purchases.edit');
    Route::put('accounting/purchases/{purchase}', [App\Http\Controllers\Admin\AccountingController::class, 'updatePurchase'])->name('accounting.purchases.update');
    Route::delete('accounting/purchases/{purchase}', [App\Http\Controllers\Admin\AccountingController::class, 'destroyPurchase'])->name('accounting.purchases.destroy');
    Route::get('accounting/expenses', [App\Http\Controllers\Admin\AccountingController::class, 'expenses'])->name('accounting.expenses');
    Route::get('accounting/expenses/create', [App\Http\Controllers\Admin\AccountingController::class, 'createExpense'])->name('accounting.expenses.create');
    Route::post('accounting/expenses', [App\Http\Controllers\Admin\AccountingController::class, 'storeExpense'])->name('accounting.store-expense');
    Route::get('accounting/expenses/{expense}/edit', [App\Http\Controllers\Admin\AccountingController::class, 'editExpense'])->name('accounting.expenses.edit');
    Route::put('accounting/expenses/{expense}', [App\Http\Controllers\Admin\AccountingController::class, 'updateExpense'])->name('accounting.expenses.update');
    Route::delete('accounting/expenses/{expense}', [App\Http\Controllers\Admin\AccountingController::class, 'destroyExpense'])->name('accounting.expenses.destroy');
    Route::get('accounting/ledger', [App\Http\Controllers\Admin\AccountingController::class, 'ledger'])->name('accounting.ledger');
    Route::get('accounting/payments', [App\Http\Controllers\Admin\AccountingController::class, 'payments'])->name('accounting.payments');
    Route::post('accounting/payments', [App\Http\Controllers\Admin\AccountingController::class, 'storePayment'])->name('accounting.store-payment');
    Route::get('accounting/reports', [App\Http\Controllers\Admin\AccountingController::class, 'reports'])->name('accounting.reports');
    Route::get('accounting/export-reports', [App\Http\Controllers\Admin\AccountingController::class, 'exportReports'])->name('accounting.export-reports');
    Route::post('accounting/quick-entry', [App\Http\Controllers\Admin\AccountingController::class, 'quickEntry'])->name('accounting.quick-entry');
    
    // Users
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->middleware(['plan.limit:users', 'employee.can:users']);
    Route::post('users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::get('users/export', [App\Http\Controllers\Admin\UserController::class, 'export'])->name('users.export');
    
    // Inventory
    Route::get('inventory', [App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('inventory.index');
    Route::post('inventory/bulk-update-stock', [App\Http\Controllers\Admin\InventoryController::class, 'bulkUpdateStock'])->name('inventory.bulk-update-stock');
    Route::post('inventory/bulk-delete', [App\Http\Controllers\Admin\InventoryController::class, 'bulkDelete'])->name('inventory.bulk-delete');
    Route::put('inventory/{product}/update-stock', [App\Http\Controllers\Admin\InventoryController::class, 'updateStock'])->name('inventory.update-stock');
    Route::get('inventory/stock-alerts', [App\Http\Controllers\Admin\InventoryController::class, 'getStockAlerts'])->name('inventory.stock-alerts');
    Route::get('inventory/export', [App\Http\Controllers\Admin\InventoryController::class, 'export'])->name('inventory.export');
    
    // Reports
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index')->middleware('employee.can:reports');
    Route::get('/reports/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export')->middleware('employee.can:reports');

    // Subscription Management (Admin self-service)
    Route::prefix('subscription')->name('subscription.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\SubscriptionController::class, 'index'])->name('index');
        Route::get('plans', [App\Http\Controllers\Admin\SubscriptionController::class, 'plans'])->name('plans');
        Route::post('change-plan', [App\Http\Controllers\Admin\SubscriptionController::class, 'changePlan'])->name('change-plan');
        Route::post('cancel', [App\Http\Controllers\Admin\SubscriptionController::class, 'cancel'])->name('cancel');
        Route::post('resume', [App\Http\Controllers\Admin\SubscriptionController::class, 'resume'])->name('resume');
        Route::get('invoices', [App\Http\Controllers\Admin\SubscriptionController::class, 'invoices'])->name('invoices');
        Route::get('payment-method', [App\Http\Controllers\Admin\SubscriptionController::class, 'paymentMethod'])->name('payment-method');
        Route::post('payment-method', [App\Http\Controllers\Admin\SubscriptionController::class, 'updatePaymentMethod'])->name('payment-method.update');
    });
});

// Super Admin Routes
Route::prefix('super')->name('super.')->group(function () {
    // Authentication
    Route::get('login', [App\Http\Controllers\SuperAdmin\AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [App\Http\Controllers\SuperAdmin\AuthController::class, 'login']);
    Route::post('logout', [App\Http\Controllers\SuperAdmin\AuthController::class, 'logout'])->name('logout');
    
    // Protected Routes
    Route::middleware('auth:super_admin')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])->name('dashboard');
        
        // Tenant Management
        Route::resource('tenants', App\Http\Controllers\SuperAdmin\TenantController::class);
        Route::post('tenants/{tenant}/approve', [App\Http\Controllers\SuperAdmin\TenantController::class, 'approve'])->name('tenants.approve');
        Route::post('tenants/{tenant}/suspend', [App\Http\Controllers\SuperAdmin\TenantController::class, 'suspend'])->name('tenants.suspend');
        Route::post('tenants/{tenant}/activate', [App\Http\Controllers\SuperAdmin\TenantController::class, 'activate'])->name('tenants.activate');
        
        // Enhanced Tenant Management
        Route::post('tenants/bulk-action', [App\Http\Controllers\SuperAdmin\TenantController::class, 'bulkAction'])->name('tenants.bulk-action');
        Route::get('tenants-export', [App\Http\Controllers\SuperAdmin\TenantController::class, 'export'])->name('tenants.export');
        Route::get('tenants/{tenant}/analytics', [App\Http\Controllers\SuperAdmin\TenantController::class, 'analytics'])->name('tenants.analytics');
        Route::get('tenants/{tenant}/health', [App\Http\Controllers\SuperAdmin\TenantController::class, 'health'])->name('tenants.health');
        
        // Subscription Plans
        Route::resource('plans', App\Http\Controllers\SuperAdmin\PlanController::class);
        Route::post('plans/{plan}/toggle-status', [App\Http\Controllers\SuperAdmin\PlanController::class, 'toggleStatus'])->name('plans.toggle-status');
        
        // Subscriptions Management
        Route::get('subscriptions', [App\Http\Controllers\SuperAdmin\SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::get('subscriptions/create', [App\Http\Controllers\SuperAdmin\SubscriptionController::class, 'create'])->name('subscriptions.create');
        Route::post('subscriptions', [App\Http\Controllers\SuperAdmin\SubscriptionController::class, 'store'])->name('subscriptions.store');
        Route::get('subscriptions/{subscription}', [App\Http\Controllers\SuperAdmin\SubscriptionController::class, 'show'])->name('subscriptions.show');
        Route::get('subscriptions/{subscription}/edit', [App\Http\Controllers\SuperAdmin\SubscriptionController::class, 'edit'])->name('subscriptions.edit');
        Route::put('subscriptions/{subscription}', [App\Http\Controllers\SuperAdmin\SubscriptionController::class, 'update'])->name('subscriptions.update');
        Route::post('subscriptions/{subscription}/activate', [App\Http\Controllers\SuperAdmin\SubscriptionController::class, 'activate'])->name('subscriptions.activate');
        Route::post('subscriptions/{subscription}/cancel', [App\Http\Controllers\SuperAdmin\SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
        Route::post('subscriptions/{subscription}/renew', [App\Http\Controllers\SuperAdmin\SubscriptionController::class, 'renew'])->name('subscriptions.renew');
        Route::post('subscriptions/{subscription}/extend', [App\Http\Controllers\SuperAdmin\SubscriptionController::class, 'extend'])->name('subscriptions.extend');
        Route::post('subscriptions/{subscription}/update-status', [App\Http\Controllers\SuperAdmin\SubscriptionController::class, 'updateStatus'])->name('subscriptions.update-status');
        Route::post('subscriptions/bulk-action', [App\Http\Controllers\SuperAdmin\SubscriptionController::class, 'bulkAction'])->name('subscriptions.bulk-action');
        
        // Payments
        Route::get('payments', [App\Http\Controllers\SuperAdmin\PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{payment}', [App\Http\Controllers\SuperAdmin\PaymentController::class, 'show'])->name('payments.show');
        
        // Analytics
        Route::get('analytics', [App\Http\Controllers\SuperAdmin\AnalyticsController::class, 'index'])->name('analytics');
        Route::get('analytics/chart-data', [App\Http\Controllers\SuperAdmin\AnalyticsController::class, 'getChartData'])->name('analytics.chart-data');
        
        // Activity Monitor
        Route::prefix('activity-monitor')->name('activity-monitor.')->group(function () {
            Route::get('/', [App\Http\Controllers\SuperAdmin\ActivityMonitorController::class, 'index'])->name('index');
            Route::get('/activities', [App\Http\Controllers\SuperAdmin\ActivityMonitorController::class, 'getActivities'])->name('activities');
            Route::get('/health', [App\Http\Controllers\SuperAdmin\ActivityMonitorController::class, 'getSystemHealth'])->name('health');
            Route::post('/sync', [App\Http\Controllers\SuperAdmin\ActivityMonitorController::class, 'triggerSync'])->name('sync');
        });
        
        // Notifications
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [App\Http\Controllers\SuperAdmin\NotificationController::class, 'index'])->name('index');
            Route::get('/get', [App\Http\Controllers\SuperAdmin\NotificationController::class, 'getNotifications'])->name('get');
            Route::post('/mark-read', [App\Http\Controllers\SuperAdmin\NotificationController::class, 'markAsRead'])->name('mark-read');
            Route::post('/create', [App\Http\Controllers\SuperAdmin\NotificationController::class, 'create'])->name('create');
            Route::post('/delete', [App\Http\Controllers\SuperAdmin\NotificationController::class, 'delete'])->name('delete');
        });
        
        // Data Validation
        Route::prefix('data-validation')->name('data-validation.')->group(function () {
            Route::get('/', [App\Http\Controllers\SuperAdmin\DataValidationController::class, 'index'])->name('index');
            Route::post('/run', [App\Http\Controllers\SuperAdmin\DataValidationController::class, 'runValidation'])->name('run');
            Route::post('/fix', [App\Http\Controllers\SuperAdmin\DataValidationController::class, 'fixIssues'])->name('fix');
        });
        
        // ===== NEW COMPREHENSIVE FEATURES =====
        // Public Site Builder (Public landing)
        Route::prefix('site-builder')->name('site-builder.')->group(function(){
            Route::get('public', [App\Http\Controllers\SuperAdmin\PublicSiteBuilderController::class, 'index'])->name('public.index');
            Route::post('public', [App\Http\Controllers\SuperAdmin\PublicSiteBuilderController::class, 'update'])->name('public.update');
            Route::post('public/upload', [App\Http\Controllers\SuperAdmin\PublicSiteBuilderController::class, 'upload'])->name('public.upload');
        });
        
        // System Monitoring
        Route::prefix('system')->name('system.')->group(function () {
            Route::get('monitor', [App\Http\Controllers\SuperAdmin\SystemMonitorController::class, 'index'])->name('monitor');
            Route::get('status', [App\Http\Controllers\SuperAdmin\SystemMonitorController::class, 'getSystemStatus'])->name('status');
            Route::get('logs', [App\Http\Controllers\SuperAdmin\SystemMonitorController::class, 'logs'])->name('logs');
            Route::get('database', [App\Http\Controllers\SuperAdmin\SystemMonitorController::class, 'database'])->name('database');
            Route::get('queue', [App\Http\Controllers\SuperAdmin\SystemMonitorController::class, 'queue'])->name('queue');
            Route::get('cache', [App\Http\Controllers\SuperAdmin\SystemMonitorController::class, 'cache'])->name('cache');
            Route::post('clear-cache', [App\Http\Controllers\SuperAdmin\SystemMonitorController::class, 'clearCache'])->name('clear-cache');
            Route::get('info', [App\Http\Controllers\SuperAdmin\SystemMonitorController::class, 'info'])->name('info');
        });
        
        // Communication
        Route::prefix('communication')->name('communication.')->group(function () {
            Route::get('/', [App\Http\Controllers\SuperAdmin\CommunicationController::class, 'index'])->name('index');
            Route::get('announcements/create', [App\Http\Controllers\SuperAdmin\CommunicationController::class, 'createAnnouncement'])->name('announcements.create');
            Route::post('announcements', [App\Http\Controllers\SuperAdmin\CommunicationController::class, 'storeAnnouncement'])->name('announcements.store');
            Route::get('email-tenants', [App\Http\Controllers\SuperAdmin\CommunicationController::class, 'emailTenants'])->name('email-tenants');
            Route::post('send-bulk-email', [App\Http\Controllers\SuperAdmin\CommunicationController::class, 'sendBulkEmail'])->name('send-bulk-email');
            Route::get('notifications', [App\Http\Controllers\SuperAdmin\CommunicationController::class, 'notifications'])->name('notifications');
            Route::post('send-notification', [App\Http\Controllers\SuperAdmin\CommunicationController::class, 'sendNotification'])->name('send-notification');
            Route::get('tickets', [App\Http\Controllers\SuperAdmin\CommunicationController::class, 'tickets'])->name('tickets');
            Route::get('support', [App\Http\Controllers\SuperAdmin\CommunicationController::class, 'support'])->name('support');
            Route::post('support/{id}/reply', [App\Http\Controllers\SuperAdmin\CommunicationController::class, 'replySupport'])->name('support.reply');
            Route::get('templates', [App\Http\Controllers\SuperAdmin\CommunicationController::class, 'templates'])->name('templates');
            Route::put('templates/{id}', [App\Http\Controllers\SuperAdmin\CommunicationController::class, 'updateTemplate'])->name('templates.update');
            Route::get('broadcasts', [App\Http\Controllers\SuperAdmin\CommunicationController::class, 'broadcasts'])->name('broadcasts');
            Route::get('sms', [App\Http\Controllers\SuperAdmin\CommunicationController::class, 'sms'])->name('sms');
        });
        
        // SMS System
        Route::prefix('sms')->name('sms.')->group(function () {
            Route::get('/', [App\Http\Controllers\SuperAdmin\SmsController::class, 'index'])->name('index');
            
            // Templates
            Route::get('templates', [App\Http\Controllers\SuperAdmin\SmsController::class, 'templates'])->name('templates');
            Route::post('templates', [App\Http\Controllers\SuperAdmin\SmsController::class, 'storeTemplate'])->name('store-template');
            Route::put('templates/{template}', [App\Http\Controllers\SuperAdmin\SmsController::class, 'updateTemplate'])->name('update-template');
            Route::delete('templates/{template}', [App\Http\Controllers\SuperAdmin\SmsController::class, 'deleteTemplate'])->name('delete-template');
            
            // Campaigns
            Route::get('campaigns', [App\Http\Controllers\SuperAdmin\SmsController::class, 'campaigns'])->name('campaigns');
            Route::get('campaigns/create', [App\Http\Controllers\SuperAdmin\SmsController::class, 'createCampaign'])->name('create-campaign');
            Route::post('campaigns', [App\Http\Controllers\SuperAdmin\SmsController::class, 'storeCampaign'])->name('store-campaign');
            Route::get('campaigns/{campaign}', [App\Http\Controllers\SuperAdmin\SmsController::class, 'showCampaign'])->name('show-campaign');
            
            // Send SMS
            Route::get('send-single', [App\Http\Controllers\SuperAdmin\SmsController::class, 'sendSingle'])->name('send-single');
            Route::post('send-single', [App\Http\Controllers\SuperAdmin\SmsController::class, 'sendSingleSms'])->name('send-single-sms');
            
            // Logs
            Route::get('logs', [App\Http\Controllers\SuperAdmin\SmsController::class, 'logs'])->name('logs');
            
            // Credits
            Route::get('credits', [App\Http\Controllers\SuperAdmin\SmsController::class, 'credits'])->name('credits');
            Route::post('credits/add', [App\Http\Controllers\SuperAdmin\SmsController::class, 'addCredits'])->name('add-credits');
        });
        
        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [App\Http\Controllers\SuperAdmin\ReportsController::class, 'index'])->name('index');
            Route::get('revenue', [App\Http\Controllers\SuperAdmin\ReportsController::class, 'revenue'])->name('revenue');
            Route::get('tenants', [App\Http\Controllers\SuperAdmin\ReportsController::class, 'tenants'])->name('tenants');
            Route::get('subscriptions', [App\Http\Controllers\SuperAdmin\ReportsController::class, 'subscriptions'])->name('subscriptions');
            Route::get('activity', [App\Http\Controllers\SuperAdmin\ReportsController::class, 'activity'])->name('activity');
            Route::get('custom', [App\Http\Controllers\SuperAdmin\ReportsController::class, 'custom'])->name('custom');
            Route::post('custom/generate', [App\Http\Controllers\SuperAdmin\ReportsController::class, 'generateCustom'])->name('custom.generate');
            Route::get('export', [App\Http\Controllers\SuperAdmin\ReportsController::class, 'export'])->name('export');
        });
        
        // Security
        Route::prefix('security')->name('security.')->group(function () {
            Route::get('/', [App\Http\Controllers\SuperAdmin\SecurityController::class, 'index'])->name('index');
            Route::get('audit-logs', [App\Http\Controllers\SuperAdmin\SecurityController::class, 'auditLogs'])->name('audit-logs');
            Route::get('login-attempts', [App\Http\Controllers\SuperAdmin\SecurityController::class, 'loginAttempts'])->name('login-attempts');
            Route::get('ip-blocking', [App\Http\Controllers\SuperAdmin\SecurityController::class, 'ipBlocking'])->name('ip-blocking');
            Route::post('block-ip', [App\Http\Controllers\SuperAdmin\SecurityController::class, 'blockIp'])->name('block-ip');
            Route::delete('unblock-ip/{id}', [App\Http\Controllers\SuperAdmin\SecurityController::class, 'unblockIp'])->name('unblock-ip');
            Route::get('sessions', [App\Http\Controllers\SuperAdmin\SecurityController::class, 'sessions'])->name('sessions');
            Route::delete('kill-session/{id}', [App\Http\Controllers\SuperAdmin\SecurityController::class, 'killSession'])->name('kill-session');
            Route::get('two-factor', [App\Http\Controllers\SuperAdmin\SecurityController::class, 'twoFactor'])->name('two-factor');
            Route::get('settings', [App\Http\Controllers\SuperAdmin\SecurityController::class, 'settings'])->name('settings');
            Route::put('settings', [App\Http\Controllers\SuperAdmin\SecurityController::class, 'updateSettings'])->name('settings.update');
            Route::get('suspicious-activity', [App\Http\Controllers\SuperAdmin\SecurityController::class, 'suspiciousActivity'])->name('suspicious-activity');
            Route::get('breach-check', [App\Http\Controllers\SuperAdmin\SecurityController::class, 'breachCheck'])->name('breach-check');
            Route::post('force-password-reset', [App\Http\Controllers\SuperAdmin\SecurityController::class, 'forcePasswordReset'])->name('force-password-reset');
            Route::get('export-audit-logs', [App\Http\Controllers\SuperAdmin\SecurityController::class, 'exportAuditLogs'])->name('export-audit-logs');
        });
        
        // Financial
        Route::prefix('financial')->name('financial.')->group(function () {
            Route::get('/', [App\Http\Controllers\SuperAdmin\FinancialController::class, 'index'])->name('index');
            Route::get('invoices', [App\Http\Controllers\SuperAdmin\FinancialController::class, 'invoices'])->name('invoices');
            Route::get('invoices/{id}', [App\Http\Controllers\SuperAdmin\FinancialController::class, 'viewInvoice'])->name('invoices.view');
            Route::post('invoices/generate', [App\Http\Controllers\SuperAdmin\FinancialController::class, 'generateInvoice'])->name('invoices.generate');
            Route::post('invoices/{id}/mark-paid', [App\Http\Controllers\SuperAdmin\FinancialController::class, 'markPaid'])->name('invoices.mark-paid');
            Route::delete('invoices/{id}', [App\Http\Controllers\SuperAdmin\FinancialController::class, 'cancelInvoice'])->name('invoices.cancel');
            Route::get('payments', [App\Http\Controllers\SuperAdmin\FinancialController::class, 'payments'])->name('payments');
            Route::get('refunds', [App\Http\Controllers\SuperAdmin\FinancialController::class, 'refunds'])->name('refunds');
            Route::post('refunds/{id}', [App\Http\Controllers\SuperAdmin\FinancialController::class, 'processRefund'])->name('refunds.process');
            Route::get('revenue-analysis', [App\Http\Controllers\SuperAdmin\FinancialController::class, 'revenueAnalysis'])->name('revenue-analysis');
            Route::get('payment-gateways', [App\Http\Controllers\SuperAdmin\FinancialController::class, 'paymentGateways'])->name('payment-gateways');
            Route::get('tax-reports', [App\Http\Controllers\SuperAdmin\FinancialController::class, 'taxReports'])->name('tax-reports');
            Route::get('export', [App\Http\Controllers\SuperAdmin\FinancialController::class, 'export'])->name('export');
        });
        
        // Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'index'])->name('index');
            Route::get('general', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'general'])->name('general');
            Route::put('general', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'updateGeneral'])->name('general.update');
            Route::post('save', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'save'])->name('save');
            Route::get('subscriptions', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'subscriptions'])->name('subscriptions');
            Route::get('payments', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'payments'])->name('payments');
            Route::get('email', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'email'])->name('email');
            Route::post('email/test', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'sendTestEmail'])->name('email.test');
            Route::get('features', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'features'])->name('features');
            Route::get('maintenance', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'maintenance'])->name('maintenance');
            Route::post('maintenance/toggle', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'toggleMaintenance'])->name('maintenance.toggle');
            Route::get('database', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'database'])->name('database');
            Route::post('database/backup', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'backupDatabase'])->name('database.backup');
            Route::get('cache', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'cache'])->name('cache');
            Route::post('cache/clear', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'clearAllCaches'])->name('cache.clear');
            Route::get('api', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'api'])->name('api');
            Route::get('notifications', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'notifications'])->name('notifications');
            Route::get('legal', [App\Http\Controllers\SuperAdmin\SettingsController::class, 'legal'])->name('legal');
        });
        
        // Tenant Health
        Route::prefix('tenant-health')->name('tenant-health.')->group(function () {
            Route::get('/', [App\Http\Controllers\SuperAdmin\TenantHealthController::class, 'index'])->name('index');
            Route::get('at-risk', [App\Http\Controllers\SuperAdmin\TenantHealthController::class, 'atRisk'])->name('at-risk');
            Route::get('engagement', [App\Http\Controllers\SuperAdmin\TenantHealthController::class, 'engagement'])->name('engagement');
            Route::get('usage', [App\Http\Controllers\SuperAdmin\TenantHealthController::class, 'usage'])->name('usage');
            Route::get('churn-prediction', [App\Http\Controllers\SuperAdmin\TenantHealthController::class, 'churnPrediction'])->name('churn-prediction');
            Route::get('{tenant}', [App\Http\Controllers\SuperAdmin\TenantHealthController::class, 'show'])->name('show');
            Route::post('{tenant}/send-alert', [App\Http\Controllers\SuperAdmin\TenantHealthController::class, 'sendHealthAlert'])->name('send-alert');
        });
    });
});

// Vendor Authentication Routes (Public)
Route::prefix('vendor')->name('vendor.')->group(function () {
    Route::get('login', [App\Http\Controllers\Vendor\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\Vendor\AuthController::class, 'login']);
    Route::get('register', [App\Http\Controllers\Vendor\AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [App\Http\Controllers\Vendor\AuthController::class, 'register']);
    Route::post('logout', [App\Http\Controllers\Vendor\AuthController::class, 'logout'])->name('logout');
    
    // Protected Vendor Routes (redirect vendor dashboard to admin dashboard since they're same)
    Route::middleware(['auth:web', 'vendor'])->group(function () {
        Route::get('dashboard', function () {
            return redirect()->route('admin.dashboard');
        })->name('dashboard');
        
        // Site Builder
        Route::prefix('site-builder')->name('site-builder.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\SiteBuilderController::class, 'vendorIndex'])->name('index');
            Route::post('/homepage', [App\Http\Controllers\Admin\SiteBuilderController::class, 'updateHomepage'])->name('update-homepage');
            Route::post('/slide-image', [App\Http\Controllers\Admin\SiteBuilderController::class, 'uploadSlideImage'])->name('upload-slide-image');
        });
    });
});

// Customer Dashboard (Storefront customers) - Removed duplicate route

// Delivery Boy Routes
Route::prefix('delivery-boy')->name('delivery-boy.')->group(function () {
    // Auth Routes
    Route::get('login', [App\Http\Controllers\DeliveryBoy\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\DeliveryBoy\AuthController::class, 'login']);
    Route::post('logout', [App\Http\Controllers\DeliveryBoy\AuthController::class, 'logout'])->name('logout');
    
    // Protected Routes
    Route::middleware(['delivery_boy', 'subscription.active'])->group(function () {
        Route::get('dashboard', [App\Http\Controllers\DeliveryBoy\DashboardController::class, 'index'])->name('dashboard');
        Route::get('deliveries', [App\Http\Controllers\DeliveryBoy\DashboardController::class, 'deliveries'])->name('deliveries');
        Route::get('deliveries/{manualDelivery}', [App\Http\Controllers\DeliveryBoy\DashboardController::class, 'showDelivery'])->name('delivery-details');
        Route::post('deliveries/{manualDelivery}/update-status', [App\Http\Controllers\DeliveryBoy\DashboardController::class, 'updateStatus'])->name('delivery.update-status');
        Route::get('profile', [App\Http\Controllers\DeliveryBoy\DashboardController::class, 'profile'])->name('profile');
        Route::put('profile', [App\Http\Controllers\DeliveryBoy\DashboardController::class, 'updateProfile'])->name('profile.update');
        Route::put('password', [App\Http\Controllers\DeliveryBoy\DashboardController::class, 'changePassword'])->name('password.update');
        Route::get('activities', [App\Http\Controllers\DeliveryBoy\DashboardController::class, 'activities'])->name('activities');
    });
});
