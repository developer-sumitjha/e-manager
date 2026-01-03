<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public API - No authentication required
Route::get('/plans', [App\Http\Controllers\Api\TenantController::class, 'getPlans']);
Route::post('/tenants/signup', [App\Http\Controllers\Api\TenantController::class, 'signup']);
Route::post('/tenants/check-subdomain', [App\Http\Controllers\Api\TenantController::class, 'checkSubdomain']);

// Storefront API - Public access
Route::prefix('storefront/{subdomain}')->group(function () {
    Route::get('/settings', [App\Http\Controllers\Api\StorefrontController::class, 'getSiteSettings']);
    Route::get('/products', [App\Http\Controllers\Api\StorefrontController::class, 'getProducts']);
    Route::get('/products/{slug}', [App\Http\Controllers\Api\StorefrontController::class, 'getProduct']);
    Route::get('/categories', [App\Http\Controllers\Api\StorefrontController::class, 'getCategories']);
    Route::get('/featured-products', [App\Http\Controllers\Api\StorefrontController::class, 'getFeaturedProducts']);
    Route::get('/new-arrivals', [App\Http\Controllers\Api\StorefrontController::class, 'getNewArrivals']);
});

// Tenant API - Requires authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});


