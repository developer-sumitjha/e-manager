<?php

namespace App\Http\Controllers\DeliveryBoy;

use App\Http\Controllers\Controller;
use App\Models\ManualDelivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $deliveryBoy = Auth::guard('delivery_boy')->user();

        // Get statistics
        $stats = [
            'total_deliveries' => $deliveryBoy->total_deliveries,
            'pending_deliveries' => $deliveryBoy->activeDeliveries()->count(),
            'completed_today' => $deliveryBoy->completedDeliveries()
                ->whereDate('delivered_at', today())
                ->count(),
            'pending_cod' => $deliveryBoy->pending_settlement,
            'rating' => $deliveryBoy->rating,
            'success_rate' => $deliveryBoy->total_deliveries > 0 
                ? round(($deliveryBoy->successful_deliveries / $deliveryBoy->total_deliveries) * 100, 1)
                : 0,
        ];

        // Get assigned deliveries
        $assignedDeliveries = $deliveryBoy->manualDeliveries()
            ->with(['order.user', 'order.orderItems.product'])
            ->where('status', 'assigned')
            ->orderBy('assigned_at', 'desc')
            ->get();

        // Get picked up deliveries
        $pickedUpDeliveries = $deliveryBoy->manualDeliveries()
            ->with(['order.user', 'order.orderItems.product'])
            ->whereIn('status', ['picked_up', 'in_transit'])
            ->orderBy('picked_up_at', 'desc')
            ->get();

        // Get recent completed deliveries
        $recentCompletedDeliveries = $deliveryBoy->completedDeliveries()
            ->with(['order.user', 'order.orderItems.product'])
            ->orderBy('delivered_at', 'desc')
            ->limit(10)
            ->get();

        return view('delivery-boy.dashboard', compact(
            'deliveryBoy',
            'stats',
            'assignedDeliveries',
            'pickedUpDeliveries',
            'recentCompletedDeliveries'
        ));
    }

    public function deliveries(Request $request)
    {
        $deliveryBoy = Auth::guard('delivery_boy')->user();
        
        $query = $deliveryBoy->manualDeliveries()
            ->with(['order.user', 'order.orderItems.product']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('assigned_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('assigned_at', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('order', function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $deliveries = $query->orderBy('assigned_at', 'desc')->paginate(20);

        return view('delivery-boy.deliveries', compact('deliveries', 'deliveryBoy'));
    }

    public function showDelivery(ManualDelivery $manualDelivery)
    {
        $deliveryBoy = Auth::guard('delivery_boy')->user();

        // Check if this delivery belongs to the logged-in delivery boy
        if ($manualDelivery->delivery_boy_id !== $deliveryBoy->id) {
            abort(403, 'Unauthorized access');
        }

        $manualDelivery->load(['order.user', 'order.orderItems.product', 'activities']);

        return view('delivery-boy.delivery-details', compact('manualDelivery', 'deliveryBoy'));
    }

    public function updateStatus(Request $request, ManualDelivery $manualDelivery)
    {
        $deliveryBoy = Auth::guard('delivery_boy')->user();

        // Check authorization
        if ($manualDelivery->delivery_boy_id !== $deliveryBoy->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Check if status can be updated
        if (!$manualDelivery->canUpdateStatus()) {
            return response()->json([
                'success' => false,
                'message' => 'This delivery status cannot be updated'
            ], 400);
        }

        $validated = $request->validate([
            'status' => 'required|in:picked_up,in_transit,delivered,cancelled',
            'notes' => 'nullable|string',
            'cancellation_reason' => 'required_if:status,cancelled',
            'cod_collected' => 'boolean',
            'delivery_proof' => 'nullable|image|max:2048',
        ]);

        $oldStatus = $manualDelivery->status;
        $manualDelivery->status = $validated['status'];
        
        if ($validated['status'] === 'picked_up' && !$manualDelivery->picked_up_at) {
            $manualDelivery->picked_up_at = now();
        }

        if ($validated['status'] === 'delivered') {
            $manualDelivery->delivered_at = now();
            $manualDelivery->cod_collected = $request->boolean('cod_collected', false);
            
            // Handle delivery proof upload
            if ($request->hasFile('delivery_proof')) {
                $path = $request->file('delivery_proof')->store('delivery-proofs', 'public');
                $manualDelivery->delivery_proof = $path;
            }

            // Update order status
            $manualDelivery->order->update(['status' => 'completed']);
        }

        if ($validated['status'] === 'cancelled') {
            $manualDelivery->cancelled_at = now();
            $manualDelivery->cancellation_reason = $validated['cancellation_reason'];
            
            // Update order status
            $manualDelivery->order->update(['status' => 'cancelled']);
        }

        if (isset($validated['notes'])) {
            $manualDelivery->delivery_notes = $validated['notes'];
        }

        $manualDelivery->save();

        // Log activity
        $deliveryBoy->logActivity(
            'status_update',
            "Updated delivery status from {$oldStatus} to {$validated['status']}",
            $manualDelivery->id,
            ['order_id' => $manualDelivery->order_id, 'old_status' => $oldStatus, 'new_status' => $validated['status']]
        );

        // Update delivery boy stats
        $deliveryBoy->updateStats();

        return response()->json([
            'success' => true,
            'message' => 'Delivery status updated successfully',
            'delivery' => $manualDelivery->fresh()->load('order')
        ]);
    }

    public function profile()
    {
        $deliveryBoy = Auth::guard('delivery_boy')->user();
        
        // Get performance stats
        $performanceStats = [
            'this_month_deliveries' => $deliveryBoy->completedDeliveries()
                ->whereMonth('delivered_at', now()->month)
                ->count(),
            'last_month_deliveries' => $deliveryBoy->completedDeliveries()
                ->whereMonth('delivered_at', now()->subMonth()->month)
                ->count(),
            'this_week_deliveries' => $deliveryBoy->completedDeliveries()
                ->whereBetween('delivered_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'avg_delivery_time' => 'N/A', // Can be calculated if we track this
        ];

        return view('delivery-boy.profile', compact('deliveryBoy', 'performanceStats'));
    }

    public function updateProfile(Request $request)
    {
        $deliveryBoy = Auth::guard('delivery_boy')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:delivery_boys,phone,' . $deliveryBoy->id,
            'email' => 'nullable|email|unique:delivery_boys,email,' . $deliveryBoy->id,
            'address' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            // Delete old photo
            if ($deliveryBoy->profile_photo) {
                Storage::disk('public')->delete($deliveryBoy->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')->store('delivery-boy-profiles', 'public');
        }

        $deliveryBoy->update($validated);

        return back()->with('success', 'Profile updated successfully');
    }

    public function changePassword(Request $request)
    {
        $deliveryBoy = Auth::guard('delivery_boy')->user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if (!\Hash::check($validated['current_password'], $deliveryBoy->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $deliveryBoy->update([
            'password' => \Hash::make($validated['password'])
        ]);

        $deliveryBoy->logActivity('password_change', 'Changed password');

        return back()->with('success', 'Password changed successfully');
    }

    public function activities()
    {
        $deliveryBoy = Auth::guard('delivery_boy')->user();
        
        $activities = $deliveryBoy->activities()
            ->with('manualDelivery.order')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('delivery-boy.activities', compact('activities', 'deliveryBoy'));
    }
}







