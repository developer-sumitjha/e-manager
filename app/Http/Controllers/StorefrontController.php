<?php

namespace App\Http\Controllers;

use App\Models\SiteSettings;
use App\Models\Tenant;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\ShippingMethod;
use App\Models\TaxRule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class StorefrontController extends Controller
{
    /**
     * Show storefront preview
     */
    public function show($subdomain)
    {
        // Find tenant by subdomain
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        
        // Get site settings
        $settings = SiteSettings::where('tenant_id', $tenant->id)->first();
        
        // If no settings exist, create defaults
        if (!$settings) {
            $defaults = SiteSettings::getDefaultSettings();
            $defaults['tenant_id'] = $tenant->id;
            $settings = SiteSettings::create($defaults);
        }
        
        // Get products for preview with search/sort/pagination
        $q = trim(request('q', ''));
        $sort = request('sort', 'new');

        $productQuery = Product::where('tenant_id', $tenant->id)
            ->where('is_active', true);

        if ($q !== '') {
            $productQuery->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%")
                    ->orWhere('sku', 'like', "%$q%");
            });
        }

        if ($sort === 'price_asc') {
            $productQuery->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $productQuery->orderBy('price', 'desc');
        } else {
            $productQuery->latest('id');
        }

        $products = $productQuery->paginate(12)->withQueryString();

        // Get categories for preview with caching
        $categories = Cache::remember("categories_{$tenant->id}", 300, function() use ($tenant) {
            return Category::where('tenant_id', $tenant->id)
                ->take(6)
                ->get();
        });
        // Cart from session
        $cart = Session::get('cart', []);
        
        // Ensure cart items have consistent structure
        $cart = array_map(function($item) {
            if (!isset($item['product_id'])) {
                // This shouldn't happen, but let's be safe
                return null;
            }
            return $item;
        }, $cart);
        $cart = array_filter($cart); // Remove any null items
        
        return view('storefront.preview', compact('tenant', 'settings', 'products', 'categories', 'cart', 'q', 'sort'));
    }

    /**
     * Sitemap for storefront
     */
    public function sitemap($subdomain)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        $products = Product::where('tenant_id', $tenant->id)->where('is_active', true)->latest('updated_at')->take(500)->get(['slug','updated_at']);
        $categories = Category::where('tenant_id', $tenant->id)->latest('updated_at')->take(200)->get(['slug','updated_at']);

        $xml = view('storefront.sitemap', compact('tenant','products','categories'))->render();
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Product detail page
     */
    public function product($subdomain, $slug)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        $settings = SiteSettings::where('tenant_id', $tenant->id)->first();
        $product = Product::where('tenant_id', $tenant->id)->where('slug', $slug)->firstOrFail();

        // Load approved reviews and average rating
        $reviews = Review::where('tenant_id', $tenant->id)
            ->where('product_id', $product->id)
            ->where('approved', true)
            ->latest()
            ->paginate(10);
        $avgRating = Review::where('tenant_id', $tenant->id)
            ->where('product_id', $product->id)
            ->where('approved', true)
            ->avg('rating');
        $avgRating = $avgRating ? round($avgRating, 1) : null;

        return view('storefront.product', compact('tenant', 'settings', 'product', 'reviews', 'avgRating'));
    }

    /**
     * Category listing with pagination, search, and sort
     */
    public function category(Request $request, $subdomain, $slug)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        $settings = SiteSettings::where('tenant_id', $tenant->id)->first();
        $category = Category::where('tenant_id', $tenant->id)->where('slug', $slug)->firstOrFail();

        $query = Product::where('tenant_id', $tenant->id)
            ->where('category_id', $category->id)
            ->where('is_active', true);

        if ($search = trim($request->query('q', ''))) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('sku', 'like', "%$search%");
            });
        }

        $sort = $request->query('sort', 'new');
        if ($sort === 'price_asc') $query->orderBy('price', 'asc');
        elseif ($sort === 'price_desc') $query->orderBy('price', 'desc');
        else $query->latest('id');

        $products = $query->paginate(12)->withQueryString();

        return view('storefront.category', compact('tenant', 'settings', 'category', 'products', 'sort', 'search'));
    }

    /**
     * Show cart
     */
    public function cart($subdomain)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        $settings = SiteSettings::where('tenant_id', $tenant->id)->first();
        $cart = Session::get('cart', []);
        $coupon = Session::get('coupon');
        
        // Ensure cart items have consistent structure
        $cart = array_map(function($item) {
            if (!isset($item['product_id'])) {
                return null;
            }
            return $item;
        }, $cart);
        $cart = array_filter($cart);
        
        return view('storefront.cart', compact('tenant', 'settings', 'cart', 'coupon'));
    }

    /**
     * Add to cart
     */
    public function addToCart(Request $request, $subdomain)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        $request->validate([
            'product_id' => 'required|integer',
            'qty' => 'nullable|integer|min:1'
        ]);
        
        $qty = max(1, (int) $request->input('qty', 1));
        $product = Product::where('tenant_id', $tenant->id)->findOrFail($request->product_id);
        
        // Check stock availability
        if (!$product->isInStock()) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Product is out of stock'], 400);
            }
            return redirect()->back()->with('error', 'Product is out of stock');
        }
        
        // Check if requested quantity exceeds available stock
        if ($product->track_inventory && $qty > $product->stock_quantity) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Only ' . $product->stock_quantity . ' items available in stock'], 400);
            }
            return redirect()->back()->with('error', 'Only ' . $product->stock_quantity . ' items available in stock');
        }
        $cart = Session::get('cart', []);
        
        if (!isset($cart[$product->id])) {
            $cart[$product->id] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => (float) ($product->sale_price ?? $product->price),
                'image' => $product->primary_image_url,
                'slug' => $product->slug,
                'quantity' => 0,
            ];
        }
        $cart[$product->id]['quantity'] += $qty;
        Session::put('cart', $cart);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'cart' => array_values($cart),
                'cart_count' => array_sum(array_column($cart, 'quantity'))
            ]);
        }
        
        return redirect()->back()->with('success', 'Added to cart');
    }

    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request, $subdomain)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'qty' => 'required|integer|min:0'
        ]);
        
        $cart = Session::get('cart', []);
        if (isset($cart[$request->product_id])) {
            if ($request->qty === 0) {
                unset($cart[$request->product_id]);
            } else {
                $cart[$request->product_id]['quantity'] = $request->qty;
            }
            Session::put('cart', $cart);
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'cart' => array_values($cart),
                'cart_count' => array_sum(array_column($cart, 'quantity'))
            ]);
        }
        
        return redirect()->back()->with('success', 'Cart updated');
    }

    /**
     * Remove from cart
     */
    public function removeFromCart(Request $request, $subdomain)
    {
        $request->validate(['product_id' => 'required|integer']);
        
        $cart = Session::get('cart', []);
        unset($cart[$request->product_id]);
        Session::put('cart', $cart);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'cart' => array_values($cart),
                'cart_count' => array_sum(array_column($cart, 'quantity'))
            ]);
        }
        
        return redirect()->back()->with('success', 'Item removed');
    }

    /**
     * Clear entire cart
     */
    public function clearCart(Request $request, $subdomain)
    {
        Session::forget('cart');
        Session::forget('coupon');
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'cart' => [],
                'cart_count' => 0
            ]);
        }
        
        return redirect()->back()->with('success', 'Cart cleared');
    }

    /**
     * Checkout page
     */
    public function checkout($subdomain)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        $settings = SiteSettings::where('tenant_id', $tenant->id)->first();
        $cart = Session::get('cart', []);
        $coupon = Session::get('coupon');
        
        // Ensure cart items have consistent structure
        $cart = array_map(function($item) {
            if (!isset($item['product_id'])) {
                return null;
            }
            return $item;
        }, $cart);
        $cart = array_filter($cart);
        
        // Load shipping methods; create sensible defaults if none exist
        $shippingMethods = ShippingMethod::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('base_rate')->get();
        if ($shippingMethods->count() === 0) {
            ShippingMethod::create(['tenant_id' => $tenant->id, 'name' => 'Standard Shipping', 'base_rate' => 100, 'min_days' => 3, 'max_days' => 5, 'is_active' => true]);
            ShippingMethod::create(['tenant_id' => $tenant->id, 'name' => 'Express Shipping', 'base_rate' => 250, 'min_days' => 1, 'max_days' => 2, 'is_active' => true]);
            ShippingMethod::create(['tenant_id' => $tenant->id, 'name' => 'Store Pickup', 'base_rate' => 0, 'min_days' => null, 'max_days' => null, 'is_active' => true]);
            $shippingMethods = ShippingMethod::where('tenant_id', $tenant->id)->where('is_active', true)->orderBy('base_rate')->get();
        }
        // Load tax rule (simplified: first active rule). Defaults to 13% if none set
        $taxRule = TaxRule::where('tenant_id', $tenant->id)->where('is_active', true)->orderByDesc('id')->first();
        $taxRate = $taxRule ? (float) $taxRule->rate : 13.0;

        return view('storefront.checkout', compact('tenant', 'settings', 'cart', 'coupon', 'shippingMethods', 'taxRate'));
    }

    /**
     * Process checkout
     */
    public function processCheckout(Request $request, $subdomain)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        $cart = Session::get('cart', []);
        
        // Ensure cart items have consistent structure
        $cart = array_map(function($item) {
            if (!isset($item['product_id'])) {
                return null;
            }
            return $item;
        }, $cart);
        $cart = array_filter($cart);
        
        if (empty($cart)) {
            return redirect()->route('storefront.cart', $tenant->subdomain)
                ->with('error', 'Your cart is empty.');
        }

        // Validate checkout data
        $request->validate([
            'billing_first_name' => 'required|string|max:255',
            'billing_last_name' => 'required|string|max:255',
            'billing_email' => 'required|email|max:255',
            'billing_phone' => 'required|string|max:20',
            'billing_address' => 'required|string|max:500',
            'billing_city' => 'required|string|max:100',
            'billing_state' => 'required|string|max:100',
            'billing_postal_code' => 'required|string|max:20',
            'billing_country' => 'required|string|max:100',
            'shipping_first_name' => 'required|string|max:255',
            'shipping_last_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:100',
            'shipping_method_id' => 'required|integer|exists:shipping_methods,id',
            'payment_method' => 'required|in:cod,esewa,khalti',
        ]);

        // Calculate totals
        $subtotal = array_sum(array_map(function($item) { return $item['quantity'] * $item['price']; }, $cart));
        // Shipping method
        $shippingMethod = ShippingMethod::where('tenant_id', $tenant->id)->where('id', $request->input('shipping_method_id'))->first();
        $shippingCost = $shippingMethod ? (float) $shippingMethod->base_rate : 0;
        // Apply coupon if present (compute discount before tax)
        $coupon = Session::get('coupon');
        $discount = 0;
        if ($coupon) {
            if ($coupon['type'] === 'percentage') {
                $discount = round($subtotal * ($coupon['value'] / 100), 2);
            } else {
                $discount = min($coupon['value'], $subtotal);
            }
        }
        // Determine applicable tax rate based on tenant rules and provided address (simple: first active rule)
        $taxRule = TaxRule::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->when($request->billing_country, function($q) use ($request){ $q->where(function($qq) use ($request){ $qq->whereNull('country')->orWhere('country', $request->billing_country); }); })
            ->when($request->billing_state, function($q) use ($request){ $q->where(function($qq) use ($request){ $qq->whereNull('state')->orWhere('state', $request->billing_state); }); })
            ->orderByDesc('id')
            ->first();
        $taxRate = $taxRule ? (float) $taxRule->rate : 13.0;
        $tax = max(0, ($subtotal - $discount)) * ($taxRate / 100.0);
        $total = max(0, $subtotal - $discount) + $shippingCost + $tax;

        // Create order
        $order = Order::create([
            'tenant_id' => $tenant->id,
            'user_id' => Auth::check() ? Auth::user()->id : null, // Associate with logged-in user if available
            'order_number' => 'ORD-' . time() . '-' . rand(1000, 9999),
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $request->payment_method,
            'shipping_method' => $shippingMethod ? $shippingMethod->name : 'N/A',
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'tax_amount' => $tax,
            'total' => $total,
            'discount_total' => $discount,
            'coupon_code' => $coupon['code'] ?? null,
            'billing_first_name' => $request->billing_first_name,
            'billing_last_name' => $request->billing_last_name,
            'billing_email' => $request->billing_email,
            'billing_phone' => $request->billing_phone,
            'billing_address' => $request->billing_address,
            'billing_city' => $request->billing_city,
            'billing_state' => $request->billing_state,
            'billing_postal_code' => $request->billing_postal_code,
            'billing_country' => $request->billing_country,
            'shipping_first_name' => $request->shipping_first_name,
            'shipping_last_name' => $request->shipping_last_name,
            'shipping_email' => $request->shipping_email,
            'shipping_phone' => $request->shipping_phone,
            'shipping_address' => $request->shipping_address,
            'shipping_city' => $request->shipping_city,
            'shipping_state' => $request->shipping_state,
            'shipping_postal_code' => $request->shipping_postal_code,
            'shipping_country' => $request->shipping_country,
        ]);

        // Create order items and reduce stock
        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            if ($product && !$product->reduceStock($item['quantity'])) {
                return redirect()->route('storefront.cart', $tenant->subdomain)
                    ->with('error', 'Insufficient stock for ' . $product->name);
            }
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['quantity'] * $item['price'],
            ]);
        }

        // Clear cart
        Session::forget('cart');

        // Handle payment based on method
        if ($request->payment_method === 'cod') {
            return redirect()->route('storefront.checkout.success', $tenant->subdomain)
                ->with('order_id', $order->id);
        } else {
            // For eSewa and Khalti, redirect to payment gateway
            return redirect()->route('payment.initiate', [
                'order_id' => $order->id,
                'method' => $request->payment_method
            ]);
        }
    }

    /**
     * Checkout success page
     */
    public function checkoutSuccess($subdomain)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        $settings = SiteSettings::where('tenant_id', $tenant->id)->first();
        $orderId = session('order_id');
        $order = Order::where('tenant_id', $tenant->id)->find($orderId);
        
        if (!$order) {
            return redirect()->route('storefront.preview', $tenant->subdomain);
        }

        return view('storefront.checkout-success', compact('tenant', 'settings', 'order'));
    }
}
