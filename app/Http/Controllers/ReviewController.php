<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Tenant;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $subdomain, $productId)
    {
        $tenant = Tenant::where('subdomain', $subdomain)->firstOrFail();
        $user = Auth::user();

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string|max:5000',
        ]);

        $product = Product::where('tenant_id', $tenant->id)->findOrFail($productId);

        Review::create([
            'tenant_id' => $tenant->id,
            'product_id' => $product->id,
            'user_id' => $user ? $user->id : null,
            'rating' => (int) $request->rating,
            'title' => $request->title,
            'body' => $request->body,
            'approved' => false, // require admin approval
        ]);

        return back()->with('success', 'Thank you! Your review has been submitted for approval.');
    }
}






