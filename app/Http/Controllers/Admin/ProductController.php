<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

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
        if ($request->has('filter') && $request->filter != 'all') {
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
                case 'low-stock':
                    $query->where('stock', '>', 0)
                          ->where('stock', '<', 20);
                    break;
            }
        }
        // Removed default filter - show all products by default

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
        
        // Get counts for filter tabs (using same base query to respect tenant scope)
        $baseQuery = Product::query();
        $activeCount = (clone $baseQuery)->where('is_active', true)->count();
        $inactiveCount = (clone $baseQuery)->where('is_active', false)->count();
        $outOfStockCount = (clone $baseQuery)->where('stock', 0)->count();
        $lowStockCount = (clone $baseQuery)->where('stock', '>', 0)->where('stock', '<', 20)->count();
        $totalCount = $baseQuery->count();
        
        return view('admin.products.index', compact('products', 'activeCount', 'inactiveCount', 'outOfStockCount', 'lowStockCount', 'totalCount'));
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
            'stock' => 'nullable|integer|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'track_inventory' => 'sometimes',
            'allow_backorders' => 'sometimes',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'video' => 'nullable|mimes:mp4,webm,ogg|max:51200', // 50MB max
            'primary_image_index' => 'nullable|integer|min:0',
            'is_active' => 'sometimes',
            'is_featured' => 'sometimes',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['track_inventory'] = $request->has('track_inventory');
        $validated['allow_backorders'] = $request->has('allow_backorders');
        
        // Handle stock fields - map stock_quantity to stock for backward compatibility
        if (isset($validated['stock_quantity'])) {
            $validated['stock'] = $validated['stock_quantity'];
        } elseif (!isset($validated['stock'])) {
            // Default to 0 if neither stock nor stock_quantity is provided
            $validated['stock'] = 0;
            $validated['stock_quantity'] = 0;
        } else {
            // If stock is provided but stock_quantity is not, sync them
            $validated['stock_quantity'] = $validated['stock'];
        }
        
        // Set default values for inventory fields
        if (!isset($validated['low_stock_threshold'])) {
            $validated['low_stock_threshold'] = 5;
        }
        
        // Set stock_status based on stock quantity
        if ($validated['track_inventory']) {
            if ($validated['stock_quantity'] > 0) {
                $validated['stock_status'] = 'in_stock';
            } elseif ($validated['allow_backorders']) {
                $validated['stock_status'] = 'on_backorder';
            } else {
                $validated['stock_status'] = 'out_of_stock';
            }
        } else {
            $validated['stock_status'] = 'in_stock';
        }

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_active' => 'sometimes',
            'is_featured' => 'sometimes',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        // Handle image upload - delete old image if new one is uploaded
        if ($request->hasFile('image')) {
            try {
                $uploadedFile = $request->file('image');
                
                // Validate file upload
                if (!$uploadedFile->isValid()) {
                    $errorCode = $uploadedFile->getError();
                    $errorMessages = [
                        UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
                        UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive',
                        UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
                        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
                        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                        UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload',
                    ];
                    $errorMsg = $errorMessages[$errorCode] ?? 'Unknown upload error (code: ' . $errorCode . ')';
                    throw new \Exception('File upload failed: ' . $errorMsg);
                }
                
                // Delete old image if it exists
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                
                // Also delete from images array if it exists there
                if ($product->images && is_array($product->images) && count($product->images) > 0) {
                    $firstImage = $product->images[0] ?? null;
                    if ($firstImage && Storage::disk('public')->exists($firstImage) && $firstImage !== $product->image) {
                        Storage::disk('public')->delete($firstImage);
                    }
                }
                
                // Ensure storage directory is writable
                $storagePath = storage_path('app/public');
                if (!is_writable($storagePath)) {
                    throw new \Exception('Storage directory is not writable: ' . $storagePath . '. Please check permissions.');
                }
                
                // Ensure products directory exists
                $productsDir = storage_path('app/public/products');
                if (!File::exists($productsDir)) {
                    if (!File::makeDirectory($productsDir, 0755, true)) {
                        throw new \Exception('Failed to create products directory: ' . $productsDir);
                    }
                }
                
                // Check if directory is writable
                if (!is_writable($productsDir)) {
                    throw new \Exception('Products directory is not writable: ' . $productsDir . '. Please check permissions.');
                }
                
                // Store new image
                try {
                    $imagePath = $uploadedFile->store('products', 'public');
                } catch (\Exception $e) {
                    \Log::error('Image store exception', [
                        'error' => $e->getMessage(),
                        'file' => $uploadedFile->getClientOriginalName(),
                        'size' => $uploadedFile->getSize(),
                        'mime' => $uploadedFile->getMimeType(),
                    ]);
                    throw new \Exception('Failed to store image file: ' . $e->getMessage());
                }
                
                if (!$imagePath || empty($imagePath)) {
                    $error = error_get_last();
                    $errorMsg = $error ? $error['message'] : 'Unknown error';
                    \Log::error('Image store returned false/empty', [
                        'error' => $errorMsg,
                        'file' => $uploadedFile->getClientOriginalName(),
                        'size' => $uploadedFile->getSize(),
                        'mime' => $uploadedFile->getMimeType(),
                        'tmp_name' => $uploadedFile->getRealPath(),
                    ]);
                    throw new \Exception('Failed to store image file. Error: ' . $errorMsg);
                }
                
                // Verify the file was actually created
                if (!Storage::disk('public')->exists($imagePath)) {
                    \Log::error('Image file not found after store', ['path' => $imagePath]);
                    throw new \Exception('Image file was not created at path: ' . $imagePath);
                }
                
                $validated['image'] = $imagePath;
                
                // Update images array - if product has images array, update it, otherwise create new
                if ($product->images && is_array($product->images) && count($product->images) > 0) {
                    // Replace first image in array
                    $images = $product->images;
                    $images[0] = $imagePath;
                    $validated['images'] = $images;
                } else {
                    // Create new images array
                    $validated['images'] = [$imagePath];
                    $validated['primary_image_index'] = 0;
                }
            } catch (\Exception $e) {
                \Log::error('Product image upload failed: ' . $e->getMessage(), [
                    'product_id' => $product->id,
                    'error' => $e->getMessage()
                ]);
                return back()->withInput()->withErrors(['image' => 'Failed to upload image: ' . $e->getMessage()]);
            }
        } else {
            // Preserve existing image if no new image is uploaded
            if ($product->image) {
                $validated['image'] = $product->image;
            }
            if ($product->images && is_array($product->images)) {
                $validated['images'] = $product->images;
            }
        }
        
        // Handle multiple images upload (if provided)
        if ($request->hasFile('images')) {
            try {
                // Delete old images if they exist
                if ($product->images && is_array($product->images)) {
                    foreach ($product->images as $oldImage) {
                        if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                            Storage::disk('public')->delete($oldImage);
                        }
                    }
                }
                // Also delete old single image if it exists
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                
                // Ensure products directory exists
                $productsDir = storage_path('app/public/products');
                if (!File::exists($productsDir)) {
                    File::makeDirectory($productsDir, 0755, true);
                }
                
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    if ($path) {
                        // Verify the file was actually created
                        if (Storage::disk('public')->exists($path)) {
                            $imagePaths[] = $path;
                        } else {
                            \Log::warning('Image file was not created', ['path' => $path]);
                        }
                    } else {
                        $error = error_get_last();
                        \Log::error('Failed to store image', ['error' => $error ? $error['message'] : 'Unknown error']);
                    }
                }
                
                if (count($imagePaths) > 0) {
                    $validated['images'] = $imagePaths;
                    
                    // Set first image as fallback for old 'image' field
                    $validated['image'] = $imagePaths[0];
                    
                    // Set primary image index
                    $validated['primary_image_index'] = $request->input('primary_image_index', 0);
                }
            } catch (\Exception $e) {
                \Log::error('Product multiple images upload failed: ' . $e->getMessage(), [
                    'product_id' => $product->id,
                    'error' => $e->getMessage()
                ]);
                return back()->withInput()->withErrors(['images' => 'Failed to upload images: ' . $e->getMessage()]);
            }
        }

        // Normalize checkboxes
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        try {
            $product->update($validated);
            
            // Clear storefront cache
            Cache::forget("categories_{$product->tenant_id}");

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Product update failed: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);
            return back()->withInput()->withErrors(['error' => 'Failed to update product: ' . $e->getMessage()]);
        }
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
            // Check if product belongs to current tenant
            if ($product->tenant_id !== auth()->user()->tenant_id) {
                \Log::warning('Product delete attempt - wrong tenant', [
                    'product_id' => $product->id,
                    'product_tenant_id' => $product->tenant_id,
                    'user_tenant_id' => auth()->user()->tenant_id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to delete this product.'
                ], 403);
            }
            
            // Check if product is referenced in orders
            $orderItemCount = \App\Models\OrderItem::where('product_id', $product->id)->count();
            if ($orderItemCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete product. It is referenced in {$orderItemCount} order(s). Products with orders cannot be deleted."
                ], 422);
            }
            
            // Perform soft delete
            $productName = $product->name;
            $product->delete();
            
            \Log::info('Product deleted successfully', [
                'product_id' => $product->id,
                'product_name' => $productName,
                'deleted_by' => auth()->user()->id
            ]);
            
            return response()->json([
                'success' => true, 
                'message' => 'Product deleted successfully.'
            ]);
            
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Product delete (JSON) DB error: '.$e->getMessage(), [
                'product_id' => $product->id,
                'error_code' => $e->getCode(),
                'error_sql' => $e->getSql() ?? 'N/A'
            ]);
            
            $message = 'Unable to delete product. ';
            if (str_contains($e->getMessage(), 'foreign key constraint')) {
                $message .= 'It may be referenced by existing orders or other records.';
            } else {
                $message .= 'Database error: ' . $e->getMessage();
            }
            
            return response()->json([
                'success' => false,
                'message' => $message,
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 422);
            
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            \Log::error('Product delete (JSON) auth error', ['product_id' => $product->id]);
            return response()->json([
                'success' => false,
                'message' => 'Authentication required. Please refresh the page and try again.'
            ], 401);
            
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            \Log::error('Product delete (JSON) permission error', ['product_id' => $product->id]);
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete products.'
            ], 403);
            
        } catch (\Throwable $e) {
            \Log::error('Product delete (JSON) unexpected error: '.$e->getMessage(), [
                'product_id' => $product->id,
                'error_type' => get_class($e),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $message = 'An unexpected error occurred while deleting the product.';
            if (config('app.debug')) {
                $message .= ' Error: ' . $e->getMessage();
            }
            
            return response()->json([
                'success' => false,
                'message' => $message,
                'error' => config('app.debug') ? $e->getMessage() : null
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
