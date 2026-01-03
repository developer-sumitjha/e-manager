<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockAdjustment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockAdjustmentController extends Controller
{
    /**
     * Display a listing of stock adjustments
     */
    public function index(Request $request)
    {
        $query = StockAdjustment::with(['product', 'adjustedBy'])
            ->latest('adjustment_date');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by reason
        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('adjustment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('adjustment_date', '<=', $request->date_to);
        }

        $adjustments = $query->paginate(15);

        // Get statistics
        $stats = [
            'total_adjustments' => StockAdjustment::count(),
            'total_increases' => StockAdjustment::where('type', 'increase')->sum('quantity'),
            'total_decreases' => StockAdjustment::where('type', 'decrease')->sum('quantity'),
            'this_month' => StockAdjustment::whereMonth('adjustment_date', now()->month)->count(),
        ];

        // Get products for filter
        $products = Product::orderBy('name')->get(['id', 'name']);

        return view('admin.stock-adjustments.index', compact('adjustments', 'stats', 'products'));
    }

    /**
     * Show the form for creating a new stock adjustment
     */
    public function create()
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'stock']);

        $reasons = [
            'damaged' => 'Damaged Goods',
            'lost' => 'Lost/Missing',
            'found' => 'Found/Stock Count Correction',
            'expired' => 'Expired Products',
            'returned' => 'Customer Returns',
            'theft' => 'Theft/Shrinkage',
            'sample' => 'Sample/Promotional Use',
            'manufacturing_defect' => 'Manufacturing Defect',
            'stock_count_correction' => 'Stock Count Correction',
            'other' => 'Other',
        ];

        return view('admin.stock-adjustments.create', compact('products', 'reasons'));
    }

    /**
     * Store a newly created stock adjustment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:increase,decrease',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|in:damaged,lost,found,expired,returned,theft,sample,manufacturing_defect,stock_count_correction,other',
            'notes' => 'nullable|string|max:1000',
            'reference_number' => 'nullable|string|max:255',
            'adjustment_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Get the product
            $product = Product::findOrFail($validated['product_id']);

            // Store old stock
            $oldStock = $product->stock;

            // Calculate new stock
            if ($validated['type'] === 'increase') {
                $newStock = $oldStock + $validated['quantity'];
            } else {
                // Decrease
                if ($oldStock < $validated['quantity']) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('error', 'Cannot decrease stock by ' . $validated['quantity'] . '. Current stock is only ' . $oldStock . '.');
                }
                $newStock = $oldStock - $validated['quantity'];
            }

            // Generate adjustment number
            $adjustmentNumber = StockAdjustment::generateAdjustmentNumber();

            // Create stock adjustment record
            $adjustment = StockAdjustment::create([
                'tenant_id' => Auth::user()->tenant_id,
                'product_id' => $validated['product_id'],
                'adjustment_number' => $adjustmentNumber,
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'old_stock' => $oldStock,
                'new_stock' => $newStock,
                'reason' => $validated['reason'],
                'notes' => $validated['notes'] ?? null,
                'reference_number' => $validated['reference_number'] ?? null,
                'adjusted_by' => Auth::id(),
                'adjustment_date' => $validated['adjustment_date'],
            ]);

            // Update product stock
            $product->update(['stock' => $newStock]);

            DB::commit();

            return redirect()
                ->route('admin.stock-adjustments.show', $adjustment)
                ->with('success', 'Stock adjustment created successfully! Product stock updated from ' . $oldStock . ' to ' . $newStock . '.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create stock adjustment: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified stock adjustment
     */
    public function show(StockAdjustment $stockAdjustment)
    {
        $stockAdjustment->load(['product', 'adjustedBy']);

        return view('admin.stock-adjustments.show', compact('stockAdjustment'));
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit(StockAdjustment $stockAdjustment)
    {
        // Generally, stock adjustments should not be edited after creation
        // But we can allow editing notes and reference number
        $reasons = [
            'damaged' => 'Damaged Goods',
            'lost' => 'Lost/Missing',
            'found' => 'Found/Stock Count Correction',
            'expired' => 'Expired Products',
            'returned' => 'Customer Returns',
            'theft' => 'Theft/Shrinkage',
            'sample' => 'Sample/Promotional Use',
            'manufacturing_defect' => 'Manufacturing Defect',
            'stock_count_correction' => 'Stock Count Correction',
            'other' => 'Other',
        ];

        return view('admin.stock-adjustments.edit', compact('stockAdjustment', 'reasons'));
    }

    /**
     * Update the specified resource in storage
     */
    public function update(Request $request, StockAdjustment $stockAdjustment)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
            'reference_number' => 'nullable|string|max:255',
        ]);

        $stockAdjustment->update($validated);

        return redirect()
            ->route('admin.stock-adjustments.show', $stockAdjustment)
            ->with('success', 'Stock adjustment updated successfully!');
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy(StockAdjustment $stockAdjustment)
    {
        // Generally, stock adjustments should not be deleted
        // But if needed, we should reverse the stock change
        try {
            DB::beginTransaction();

            $product = $stockAdjustment->product;

            // Reverse the stock change
            if ($stockAdjustment->type === 'increase') {
                $product->stock -= $stockAdjustment->quantity;
            } else {
                $product->stock += $stockAdjustment->quantity;
            }

            $product->save();
            $stockAdjustment->delete();

            DB::commit();

            return redirect()
                ->route('admin.stock-adjustments.index')
                ->with('success', 'Stock adjustment deleted and stock reversed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Failed to delete stock adjustment: ' . $e->getMessage());
        }
    }

    /**
     * Get product stock for AJAX
     */
    public function getProductStock($productId)
    {
        $product = Product::findOrFail($productId);

        return response()->json([
            'success' => true,
            'stock' => $product->stock,
            'name' => $product->name,
            'sku' => $product->sku,
        ]);
    }
}






