<?php

namespace App\Http\Controllers\DeliveryBoy;

use App\Http\Controllers\Controller;
use App\Models\DeliveryBoy;
use App\Models\ManualDelivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DeliveryBoyDashboardController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::guard('delivery_boy')->check()) {
            return redirect()->route('delivery-boy.dashboard');
        }
        
        return view('delivery-boy.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('delivery_boy')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ], $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('delivery-boy.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::guard('delivery_boy')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('delivery-boy.login');
    }

    /**
     * Dashboard
     */
    public function dashboard()
    {
        $deliveryBoy = Auth::guard('delivery_boy')->user();

        // Today's stats
        $today = Carbon::today();
        $todayStats = [
            'total_deliveries' => ManualDelivery::where('delivery_boy_id', $deliveryBoy->id)
                ->whereDate('assigned_at', $today)
                ->count(),
            'completed' => ManualDelivery::where('delivery_boy_id', $deliveryBoy->id)
                ->whereDate('delivered_at', $today)
                ->where('status', 'delivered')
                ->count(),
            'pending' => ManualDelivery::where('delivery_boy_id', $deliveryBoy->id)
                ->where('status', 'pending')
                ->count(),
            'cod_collected' => ManualDelivery::where('delivery_boy_id', $deliveryBoy->id)
                ->whereDate('delivered_at', $today)
                ->where('status', 'delivered')
                ->sum('cod_amount'),
        ];

        // Overall stats
        $overallStats = [
            'total_deliveries' => ManualDelivery::where('delivery_boy_id', $deliveryBoy->id)->count(),
            'completed_deliveries' => ManualDelivery::where('delivery_boy_id', $deliveryBoy->id)
                ->where('status', 'delivered')
                ->count(),
            'cancelled_deliveries' => ManualDelivery::where('delivery_boy_id', $deliveryBoy->id)
                ->where('status', 'cancelled')
                ->count(),
            'total_earnings' => ManualDelivery::where('delivery_boy_id', $deliveryBoy->id)
                ->where('status', 'delivered')
                ->sum('cod_amount'),
        ];

        // Pending deliveries
        $pendingDeliveries = ManualDelivery::with('order.user')
            ->where('delivery_boy_id', $deliveryBoy->id)
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();

        // Recent deliveries
        $recentDeliveries = ManualDelivery::with('order.user')
            ->where('delivery_boy_id', $deliveryBoy->id)
            ->latest()
            ->take(5)
            ->get();

        return view('delivery-boy.dashboard', compact(
            'deliveryBoy',
            'todayStats',
            'overallStats',
            'pendingDeliveries',
            'recentDeliveries'
        ));
    }

    /**
     * My Deliveries
     */
    public function deliveries(Request $request)
    {
        $deliveryBoy = Auth::guard('delivery_boy')->user();

        $query = ManualDelivery::with('order.user')
            ->where('delivery_boy_id', $deliveryBoy->id);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('assigned_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('assigned_at', '<=', $request->date_to);
        }

        $deliveries = $query->latest()->paginate(20);

        return view('delivery-boy.deliveries', compact('deliveries'));
    }

    /**
     * Delivery Details
     */
    public function deliveryDetails($id)
    {
        $deliveryBoy = Auth::guard('delivery_boy')->user();
        
        $delivery = ManualDelivery::with('order.user', 'order.orderItems.product')
            ->where('delivery_boy_id', $deliveryBoy->id)
            ->findOrFail($id);

        return view('delivery-boy.delivery-details', compact('delivery'));
    }

    /**
     * Update Delivery Status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:picked_up,delivered,cancelled',
            'notes' => 'nullable|string',
            'cod_amount' => 'nullable|numeric|min:0',
        ]);

        $deliveryBoy = Auth::guard('delivery_boy')->user();
        
        $delivery = ManualDelivery::where('delivery_boy_id', $deliveryBoy->id)->findOrFail($id);

        $updateData = [
            'status' => $request->status,
            'notes' => $request->notes,
        ];

        if ($request->status == 'picked_up') {
            $updateData['picked_up_at'] = now();
        } elseif ($request->status == 'delivered') {
            $updateData['delivered_at'] = now();
            $updateData['cod_amount'] = $request->cod_amount ?? $delivery->cod_amount;
            
            // Update order status
            $delivery->order->update(['status' => 'completed']);
        } elseif ($request->status == 'cancelled') {
            $updateData['cancelled_at'] = now();
            
            // Update order status
            $delivery->order->update(['status' => 'cancelled']);
        }

        $delivery->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Delivery status updated successfully!',
        ]);
    }

    /**
     * Profile
     */
    public function profile()
    {
        $deliveryBoy = Auth::guard('delivery_boy')->user();
        
        // Get performance stats
        $performanceStats = [
            'total_deliveries' => ManualDelivery::where('delivery_boy_id', $deliveryBoy->id)->count(),
            'success_rate' => $this->calculateSuccessRate($deliveryBoy->id),
            'avg_delivery_time' => $this->calculateAvgDeliveryTime($deliveryBoy->id),
            'total_earnings' => ManualDelivery::where('delivery_boy_id', $deliveryBoy->id)
                ->where('status', 'delivered')
                ->sum('cod_amount'),
        ];

        return view('delivery-boy.profile', compact('deliveryBoy', 'performanceStats'));
    }

    /**
     * Update Profile
     */
    public function updateProfile(Request $request)
    {
        $deliveryBoy = Auth::guard('delivery_boy')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        $deliveryBoy->update($request->only(['name', 'phone', 'address']));

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Change Password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $deliveryBoy = Auth::guard('delivery_boy')->user();

        if (!Hash::check($request->current_password, $deliveryBoy->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $deliveryBoy->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password changed successfully!');
    }

    /**
     * COD Settlement
     */
    public function codSettlement()
    {
        $deliveryBoy = Auth::guard('delivery_boy')->user();

        $deliveries = ManualDelivery::with('order')
            ->where('delivery_boy_id', $deliveryBoy->id)
            ->where('status', 'delivered')
            ->where('cod_amount', '>', 0)
            ->latest('delivered_at')
            ->paginate(20);

        $settlementStats = [
            'total_collected' => ManualDelivery::where('delivery_boy_id', $deliveryBoy->id)
                ->where('status', 'delivered')
                ->sum('cod_amount'),
            'pending_settlement' => ManualDelivery::where('delivery_boy_id', $deliveryBoy->id)
                ->where('status', 'delivered')
                ->where('cod_amount', '>', 0)
                ->where('cod_settled', false)
                ->sum('cod_amount'),
            'settled_amount' => ManualDelivery::where('delivery_boy_id', $deliveryBoy->id)
                ->where('status', 'delivered')
                ->where('cod_settled', true)
                ->sum('cod_amount'),
        ];

        return view('delivery-boy.cod-settlement', compact('deliveries', 'settlementStats'));
    }

    /**
     * Calculate success rate
     */
    protected function calculateSuccessRate($deliveryBoyId)
    {
        $total = ManualDelivery::where('delivery_boy_id', $deliveryBoyId)->count();
        
        if ($total == 0) {
            return 0;
        }

        $delivered = ManualDelivery::where('delivery_boy_id', $deliveryBoyId)
            ->where('status', 'delivered')
            ->count();

        return round(($delivered / $total) * 100, 2);
    }

    /**
     * Calculate average delivery time
     */
    protected function calculateAvgDeliveryTime($deliveryBoyId)
    {
        $deliveries = ManualDelivery::where('delivery_boy_id', $deliveryBoyId)
            ->where('status', 'delivered')
            ->whereNotNull('assigned_at')
            ->whereNotNull('delivered_at')
            ->get();

        if ($deliveries->count() == 0) {
            return 'N/A';
        }

        $totalMinutes = 0;
        foreach ($deliveries as $delivery) {
            $totalMinutes += $delivery->assigned_at->diffInMinutes($delivery->delivered_at);
        }

        $avgMinutes = round($totalMinutes / $deliveries->count());
        
        if ($avgMinutes < 60) {
            return $avgMinutes . ' mins';
        }

        $hours = floor($avgMinutes / 60);
        $minutes = $avgMinutes % 60;

        return $hours . 'h ' . $minutes . 'm';
    }
}
