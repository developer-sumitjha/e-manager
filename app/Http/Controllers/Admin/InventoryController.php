<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
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
                  ->orWhereHas('category', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter functionality
        $filter = $request->input('filter', '');
        switch ($filter) {
            case 'low_stock':
                $query->where('stock', '>', 0)->where('stock', '<=', 20);
                break;
            case 'out_of_stock':
                $query->where('stock', 0);
                break;
            default:
                // All products
                break;
        }

        $products = $query->latest()->paginate(10);

        // Get counts for filter tabs
        $allCount = Product::count();
        $lowStockCount = Product::where('stock', '>', 0)->where('stock', '<=', 20)->count();
        $outOfStockCount = Product::where('stock', 0)->count();

        return view('admin.inventory.index', compact(
            'products', 
            'allCount', 
            'lowStockCount', 
            'outOfStockCount'
        ));
    }

    public function bulkUpdateStock(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
            'stock_quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:500'
        ]);

        $productIds = $validated['product_ids'];
        $stockQuantity = $validated['stock_quantity'];
        $notes = $validated['notes'] ?? '';

        $updatedCount = 0;
        foreach ($productIds as $productId) {
            $product = Product::find($productId);
            if ($product) {
                $product->update([
                    'stock' => $stockQuantity
                ]);
                $updatedCount++;
            }
        }

        $message = "Stock updated for {$updatedCount} products.";
        if ($notes) {
            $message .= " Notes: {$notes}";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'count' => $updatedCount
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id'
        ]);

        $productIds = $validated['product_ids'];
        $deletedCount = 0;

        foreach ($productIds as $productId) {
            $product = Product::find($productId);
            if ($product) {
                // Delete associated images if any
                if ($product->image && file_exists(public_path('storage/' . $product->image))) {
                    unlink(public_path('storage/' . $product->image));
                }
                
                if ($product->images) {
                    foreach ($product->images as $image) {
                        if (file_exists(public_path('storage/' . $image))) {
                            unlink(public_path('storage/' . $image));
                        }
                    }
                }

                $product->delete();
                $deletedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$deletedCount} products deleted successfully.",
            'count' => $deletedCount
        ]);
    }

    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'stock_quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:500'
        ]);

        $product->update([
            'stock' => $validated['stock_quantity']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully.',
            'product' => $product->fresh()
        ]);
    }

    public function getStockAlerts()
    {
        $lowStockProducts = Product::where('stock', '>', 0)
            ->where('stock', '<=', 20)
            ->with('category')
            ->get();

        $outOfStockProducts = Product::where('stock', 0)
            ->with('category')
            ->get();

        return response()->json([
            'low_stock' => $lowStockProducts,
            'out_of_stock' => $outOfStockProducts,
            'low_stock_count' => $lowStockProducts->count(),
            'out_of_stock_count' => $outOfStockProducts->count()
        ]);
    }

    public function export(Request $request)
    {
        $query = Product::with('category');

        // Apply same filters as index
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhereHas('category', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $filter = $request->input('filter', '');
        switch ($filter) {
            case 'low_stock':
                $query->where('stock', '>', 0)->where('stock', '<=', 20);
                break;
            case 'out_of_stock':
                $query->where('stock', 0);
                break;
        }

        $products = $query->get();

        $filename = 'inventory_report_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID', 'Product Name', 'SKU', 'Category', 'Stock', 'Price', 'Sale Price', 'Status', 'Created At'
            ]);

            foreach ($products as $product) {
                $status = $product->stock == 0 ? 'Out of Stock' : 
                         ($product->stock <= 20 ? 'Low Stock' : 'In Stock');
                
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->sku,
                    $product->category->name,
                    $product->stock,
                    $product->price,
                    $product->sale_price,
                    $status,
                    $product->created_at->format('Y-m-d H:i:s')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}







