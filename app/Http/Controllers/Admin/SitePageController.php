<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SitePage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SitePageController extends Controller
{
    /**
     * Get authenticated user's tenant ID
     */
    private function getTenantId()
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        if (!$tenantId) {
            abort(403, 'No tenant associated with this user. Please contact support.');
        }
        
        return $tenantId;
    }

    /**
     * Display a listing of the pages
     */
    public function index()
    {
        $tenantId = $this->getTenantId();
        
        $pages = SitePage::where('tenant_id', $tenantId)
            ->orderBy('menu_order')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.site-pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new page
     */
    public function create()
    {
        return view('admin.site-pages.create');
    }

    /**
     * Store a newly created page in storage
     */
    public function store(Request $request)
    {
        $tenantId = $this->getTenantId();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'page_type' => 'required|in:about,contact,products,categories,custom',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'is_active' => 'nullable',
            'menu_order' => 'nullable|integer|min:0',
            'template' => 'nullable|in:default,full-width,sidebar',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string',
            'map_iframe' => 'nullable|string',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $validated['tenant_id'] = $tenantId;
        // Convert checkbox to boolean (checked = true, unchecked = false)
        $validated['is_active'] = $request->has('is_active') && $request->input('is_active') !== '0';
        
        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }
        
        // Handle banner image upload
        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('site-assets/page-banners', 'public');
            $validated['banner_image'] = $path;
        }
        
        $page = SitePage::create($validated);
        
        return redirect()->route('admin.site-pages.index')
            ->with('success', 'Page created successfully!');
    }

    /**
     * Display the specified page
     */
    public function show(SitePage $sitePage)
    {
        $tenantId = $this->getTenantId();
        
        if ($sitePage->tenant_id !== $tenantId) {
            abort(403, 'Unauthorized access to this page.');
        }
        
        return view('admin.site-pages.show', compact('sitePage'));
    }

    /**
     * Show the form for editing the specified page
     */
    public function edit(SitePage $sitePage)
    {
        $tenantId = $this->getTenantId();
        
        if ($sitePage->tenant_id !== $tenantId) {
            abort(403, 'Unauthorized access to this page.');
        }
        
        return view('admin.site-pages.edit', compact('sitePage'));
    }

    /**
     * Update the specified page in storage
     */
    public function update(Request $request, SitePage $sitePage)
    {
        $tenantId = $this->getTenantId();
        
        if ($sitePage->tenant_id !== $tenantId) {
            abort(403, 'Unauthorized access to this page.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'page_type' => 'required|in:about,contact,products,categories,custom',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'is_active' => 'nullable',
            'menu_order' => 'nullable|integer|min:0',
            'template' => 'nullable|in:default,full-width,sidebar',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string',
            'map_iframe' => 'nullable|string',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        // Convert checkbox to boolean (checked = true, unchecked = false)
        $validated['is_active'] = $request->has('is_active') && $request->input('is_active') !== '0';
        
        // Update slug if changed
        if (!empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['slug']);
        }
        
        // Handle banner image upload
        if ($request->hasFile('banner_image')) {
            // Delete old image
            if ($sitePage->banner_image && Storage::disk('public')->exists($sitePage->banner_image)) {
                Storage::disk('public')->delete($sitePage->banner_image);
            }
            
            $path = $request->file('banner_image')->store('site-assets/page-banners', 'public');
            $validated['banner_image'] = $path;
        }
        
        $sitePage->update($validated);
        
        return redirect()->route('admin.site-pages.index')
            ->with('success', 'Page updated successfully!');
    }

    /**
     * Remove the specified page from storage
     */
    public function destroy(SitePage $sitePage)
    {
        $tenantId = $this->getTenantId();
        
        if ($sitePage->tenant_id !== $tenantId) {
            abort(403, 'Unauthorized access to this page.');
        }
        
        // Delete banner image if exists
        if ($sitePage->banner_image && Storage::disk('public')->exists($sitePage->banner_image)) {
            Storage::disk('public')->delete($sitePage->banner_image);
        }
        
        $sitePage->delete();
        
        return redirect()->route('admin.site-pages.index')
            ->with('success', 'Page deleted successfully!');
    }
}
