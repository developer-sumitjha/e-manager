<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PaymentController extends Controller
{
    public function initiatePayment(Request $request)
    {
        $orderId = $request->query('order_id');
        $method = $request->query('method');

        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        // Simulate payment gateway redirect
        // In a real scenario, you would integrate with eSewa/Khalti APIs here
        // and redirect the user to their payment portal.
        Log::info("Initiating payment for Order #{$order->order_number} via {$method}");

        // For demo purposes, immediately redirect to a simulated return URL
        return redirect()->route('payment.return', ['method' => $method, 'order_id' => $order->id, 'status' => 'success']);
    }

    public function paymentReturn(Request $request, $method)
    {
        $orderId = $request->query('order_id');
        $status = $request->query('status'); // 'success' or 'failed'

        $order = Order::find($orderId);
        $tenant = Tenant::find($order->tenant_id);

        if (!$order || !$tenant) {
            return redirect()->route('storefront.preview', $tenant->subdomain ?? '')->with('error', 'Payment return failed: Order or Tenant not found.');
        }

        if ($status === 'success') {
            $order->update(['payment_status' => 'paid', 'status' => 'processing']);
            Log::info("Payment successful for Order #{$order->order_number} via {$method}");
            
            // Clear any relevant caches
            Cache::forget("categories_{$tenant->id}");
            
            return redirect()->route('storefront.checkout.success', $tenant->subdomain)
                            ->with('order_id', $order->id)
                            ->with('success', 'Payment successful!');
        } else {
            $order->update(['payment_status' => 'failed']);
            Log::warning("Payment failed for Order #{$order->order_number} via {$method}");
            return redirect()->route('storefront.checkout', $tenant->subdomain)
                            ->with('error', 'Payment failed. Please try again.');
        }
    }

    public function paymentCallback(Request $request, $method)
    {
        // This method would typically be called by the payment gateway
        // (eSewa/Khalti) to notify your system of a payment status change.
        // It usually involves verifying a signature/hash and then updating the order.

        Log::info("Payment callback received for method: {$method}", $request->all());

        // For demo, assume a successful callback for any received data
        $orderId = $request->input('order_id'); // Assuming order_id is sent in callback
        $order = Order::find($orderId);

        if ($order) {
            $order->update(['payment_status' => 'paid', 'status' => 'processing']);
            Log::info("Order #{$order->order_number} updated to paid via callback.");
            
            // Clear any relevant caches
            Cache::forget("categories_{$order->tenant_id}");
            
            return response()->json(['status' => 'success', 'message' => 'Order updated.']);
        }

        return response()->json(['status' => 'failed', 'message' => 'Order not found.'], 404);
    }
}