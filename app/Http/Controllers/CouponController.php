<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{
    public function apply(Request $request, $subdomain)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        $request->validate(['code' => 'required|string']);

        $code = strtoupper(trim($request->code));
        $coupon = Coupon::where('tenant_id', $tenant->id)
            ->where('code', $code)
            ->where('is_active', true)
            ->where(function($q){ $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()->toDateString()); })
            ->where(function($q){ $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()->toDateString()); })
            ->first();

        if (!$coupon) {
            return back()->withErrors(['code' => 'Invalid or expired coupon']);
        }

        // Calculate cart subtotal
        $cart = Session::get('cart', []);
        $subtotal = array_sum(array_map(function($item) { return $item['quantity'] * $item['price']; }, $cart));
        if ($subtotal < (float)$coupon->min_order_amount) {
            return back()->withErrors(['code' => 'Minimum order amount not met']);
        }

        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return back()->withErrors(['code' => 'Coupon usage limit reached']);
        }

        Session::put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => (float)$coupon->value,
        ]);

        return back()->with('success', 'Coupon applied');
    }

    public function remove(Request $request, $subdomain)
    {
        Session::forget('coupon');
        return back()->with('success', 'Coupon removed');
    }
}






