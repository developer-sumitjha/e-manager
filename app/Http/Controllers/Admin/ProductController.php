<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status (Active, Inactive, Out of Stock)
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                case 'out_of_stock':
                    $query->where('stock', 0);
                    break;
            }
        } else {
            // Default to active products
            $query->where('is_active', true);
        }

        // Legacy filters for backward compatibility
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status);
        }

        if ($request->has('featured') && $request->featured != '') {
            $query->where('is_featured', $request->featured);
        }

        if ($request->has('stock') && $request->stock != '') {
            if ($request->stock == 'low') {
                $query->where('stock', '<', 20);
            } elseif ($request->stock == 'out') {
                $query->where('stock', 0);
            }
        }

        $products = $query->latest()->paginate(10);
        
        // Get counts for filter tabs
        $activeCount = Product::where('is_active', true)->count();
        $inactiveCount = Product::where('is_active', false)->count();
        $outOfStockCount = Product::where('stock', 0)->count();
        
        return view('admin.products.index', compact('products', 'activeCount', 'inactiveCount', 'outOfStockCount'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'video' => 'nullable|mimes:mp4,webm,ogg|max:51200', // 50MB max
            'primary_image_index' => 'nullable|integer|min:0',
            'is_active' => 'sometimes',
            'is_featured' => 'sometimes',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        // Auto-generate SKU if not provided
        if (empty($validated['sku'])) {
            $base = strtoupper(Str::slug($validated['name'], '-'));
            if ($base === '') {
                $base = 'SKU';
            }
            $validated['sku'] = $this->generateUniqueSku($base);
        }
        
        // Handle multiple images
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
            }
            $validated['images'] = $imagePaths;
            
            // Set first image as fallback for old 'image' field
            $validated['image'] = $imagePaths[0] ?? null;
            
            // Set primary image index
            $validated['primary_image_index'] = $request->input('primary_image_index', 0);
        } elseif ($request->hasFile('image')) {
            // Fallback to single image upload
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
            $validated['images'] = [$imagePath];
            $validated['primary_image_index'] = 0;
        }
        
        // Handle video upload
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('products/videos', 'public');
            $validated['video'] = $videoPath;
        }

        $product = Product::create($validated);
        
        // Clear storefront cache
        Cache::forget("categories_{$product->tenant_id}");

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully with images and video!');
    }

    public function show(Product $product)
    {
        $product->load('category');
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'sometimes',
            'is_featured' => 'sometimes',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        // Normalize checkboxes
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        $product->update($validated);
        
        // Clear storefront cache
        Cache::forget("categories_{$product->tenant_id}");

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        try {
            // Soft delete only; keep media files to preserve references in UI/history
            $product->delete();

            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);
            }

            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Likely foreign key constraint (e.g., order_items referencing this product)
            \Log::error('Product delete failed (DB): '.$e->getMessage(), ['product_id' => $product->id]);
            $message = 'Unable to delete product. It may be referenced by existing orders. (' . $e->getCode() . ')';
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return redirect()->route('admin.products.index')->with('error', $message);
        } catch (\Throwable $e) {
            \Log::error('Product delete failed: '.$e->getMessage(), ['product_id' => $product->id]);
            $message = 'An unexpected error occurred while deleting the product: ' . $e->getMessage();
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return redirect()->route('admin.products.index')->with('error', $message);
        }
    }

    /**
     * JSON-only soft delete endpoint to avoid redirect/CSRF quirks in DELETE.
     */
    public function destroyJson(Request $request, Product $product)
    {
        try {
            $product->delete();
            return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Product delete (JSON) DB error: '.$e->getMessage(), ['product_id' => $product->id]);
            return response()->json([
                'success' => false,
                'message' => 'Unable to delete product. It may be referenced by existing orders.'
            ], 422);
        } catch (\Throwable $e) {
            \Log::error('Product delete (JSON) error: '.$e->getMessage(), ['product_id' => $product->id]);
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while deleting the product.'
            ], 500);
        }
    }

    public function toggleStatus(Request $request, Product $product)
    {
        // Accept common boolean representations from JS/FormData
        $request->validate([
            'is_active' => 'required|in:1,0,true,false,on,off'
        ]);

        $raw = $request->input('is_active');
        $isActive = in_array($raw, ['1', 1, true, 'true', 'on'], true);

        $product->update([
            'is_active' => $isActive
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product status updated successfully.',
            'is_active' => $product->is_active
        ]);
    }

    public function duplicate(Product $product)
    {
        $newProduct = $product->replicate();
        $newProduct->name = $product->name . ' (Copy)';
        $newProduct->sku = $product->sku . '_copy_' . time();
        $newProduct->slug = Str::slug($newProduct->name) . '_' . time();
        $newProduct->is_featured = false; // Reset featured status for copy
        $newProduct->save();

        return response()->json([
            'success' => true,
            'message' => 'Product duplicated successfully.',
            'product_id' => $newProduct->id
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id'
        ]);

        $action = $request->action;
        $productIds = $request->product_ids;
        $count = 0;

        switch ($action) {
            case 'activate':
                $count = Product::whereIn('id', $productIds)->update(['is_active' => true]);
                $message = "{$count} products activated successfully.";
                break;
                
            case 'deactivate':
                $count = Product::whereIn('id', $productIds)->update(['is_active' => false]);
                $message = "{$count} products deactivated successfully.";
                break;
                
            case 'delete':
                $products = Product::whereIn('id', $productIds)->get();
                foreach ($products as $product) {
                    // Delete images
                    if ($product->image && Storage::exists('public/' . $product->image)) {
                        Storage::delete('public/' . $product->image);
                    }
                    if ($product->images) {
                        foreach ($product->images as $image) {
                            if (Storage::exists('public/' . $image)) {
                                Storage::delete('public/' . $image);
                            }
                        }
                    }
                    $product->delete();
                    $count++;
                }
                $message = "{$count} products deleted successfully.";
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'count' => $count
        ]);
    }

    /**
     * Generate a unique SKU based on a base string.
     */
    private function generateUniqueSku(string $base): string
    {
        $candidate = $base;
        $suffix = 1;
        while (\App\Models\Product::where('sku', $candidate)->exists()) {
            $candidate = $base . '-' . str_pad((string)$suffix, 3, '0', STR_PAD_LEFT);
            $suffix++;
            if ($suffix > 9999) {
                // Fallback to random suffix if extreme collision
                $candidate = $base . '-' . strtoupper(Str::random(6));
                if (!\App\Models\Product::where('sku', $candidate)->exists()) {
                    break;
                }
            }
        }
        return $candidate;
    }
}
