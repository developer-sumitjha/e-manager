<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSettings;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tenant;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    /**
     * Get site settings for a tenant
     */
    public function getSiteSettings($subdomain)
    {
        try {
            // Find tenant by subdomain
            $tenant = Tenant::where('subdomain', $subdomain)->first();
            
            if (!$tenant) {
                return response()->json([
                    'error' => 'Store not found'
                ], 404);
            }

            // Get or create site settings
            $settings = SiteSettings::firstOrCreate(
                ['tenant_id' => $tenant->id],
                array_merge(
                    SiteSettings::getDefaultSettings(),
                    ['tenant_id' => $tenant->id]
                )
            );

            return response()->json([
                'success' => true,
                'settings' => $settings,
                'tenant' => [
                    'id' => $tenant->id,
                    'business_name' => $tenant->business_name,
                    'subdomain' => $tenant->subdomain,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch settings',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get products for storefront
     */
    public function getProducts(Request $request, $subdomain)
    {
        try {
            $tenant = Tenant::where('subdomain', $subdomain)->first();
            
            if (!$tenant) {
                return response()->json(['error' => 'Store not found'], 404);
            }

            $query = Product::where('tenant_id', $tenant->id)
                ->where('is_active', true)
                ->with('category');

            // Search
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Category filter
            if ($request->has('category')) {
                $query->where('category_id', $request->input('category'));
            }

            // Price range filter
            if ($request->has('min_price')) {
                $query->where('price', '>=', $request->input('min_price'));
            }
            if ($request->has('max_price')) {
                $query->where('price', '<=', $request->input('max_price'));
            }

            // Featured filter
            if ($request->has('featured') && $request->input('featured')) {
                $query->where('is_featured', true);
            }

            // Sorting
            $sort = $request->input('sort', 'latest');
            switch ($sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
                default:
                    $query->latest();
            }

            // Get site settings for products per page
            $settings = SiteSettings::where('tenant_id', $tenant->id)->first();
            $perPage = $settings ? $settings->products_per_page : 12;

            $products = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'products' => $products
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch products',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single product details
     */
    public function getProduct($subdomain, $slug)
    {
        try {
            $tenant = Tenant::where('subdomain', $subdomain)->first();
            
            if (!$tenant) {
                return response()->json(['error' => 'Store not found'], 404);
            }

            $product = Product::where('tenant_id', $tenant->id)
                ->where('slug', $slug)
                ->where('is_active', true)
                ->with('category')
                ->first();

            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            // Get related products
            $relatedProducts = Product::where('tenant_id', $tenant->id)
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('is_active', true)
                ->limit(4)
                ->get();

            return response()->json([
                'success' => true,
                'product' => $product,
                'related_products' => $relatedProducts
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch product',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get categories for storefront
     */
    public function getCategories($subdomain)
    {
        try {
            $tenant = Tenant::where('subdomain', $subdomain)->first();
            
            if (!$tenant) {
                return response()->json(['error' => 'Store not found'], 404);
            }

            $categories = Category::where('tenant_id', $tenant->id)
                ->withCount(['products' => function($query) {
                    $query->where('is_active', true);
                }])
                ->get();

            return response()->json([
                'success' => true,
                'categories' => $categories
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch categories',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get featured products
     */
    public function getFeaturedProducts($subdomain)
    {
        try {
            $tenant = Tenant::where('subdomain', $subdomain)->first();
            
            if (!$tenant) {
                return response()->json(['error' => 'Store not found'], 404);
            }

            $products = Product::where('tenant_id', $tenant->id)
                ->where('is_active', true)
                ->where('is_featured', true)
                ->with('category')
                ->limit(8)
                ->get();

            return response()->json([
                'success' => true,
                'products' => $products
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch featured products',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get new arrivals
     */
    public function getNewArrivals($subdomain)
    {
        try {
            $tenant = Tenant::where('subdomain', $subdomain)->first();
            
            if (!$tenant) {
                return response()->json(['error' => 'Store not found'], 404);
            }

            $products = Product::where('tenant_id', $tenant->id)
                ->where('is_active', true)
                ->with('category')
                ->latest()
                ->limit(8)
                ->get();

            return response()->json([
                'success' => true,
                'products' => $products
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch new arrivals',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
