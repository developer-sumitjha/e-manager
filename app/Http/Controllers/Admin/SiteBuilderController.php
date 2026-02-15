<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SiteBuilderController extends Controller
{
    /**
     * Get authenticated user's tenant ID and validate
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
     * Get or create site settings for the tenant
     */
    private function getSiteSettings($tenantId)
    {
        $settings = SiteSettings::where('tenant_id', $tenantId)->first();
        
        if (!$settings) {
            // Create new settings with defaults
            $defaults = SiteSettings::getDefaultSettings();
            $defaults['tenant_id'] = $tenantId;
            $settings = SiteSettings::create($defaults);
        }
        
        return $settings;
    }
    
    /**
     * Show the site builder dashboard
     */
    public function index()
    {
        $tenantId = $this->getTenantId();
        $settings = $this->getSiteSettings($tenantId);
        
        return view('admin.site-builder.index', compact('settings'));
    }
    
    /**
     * Show the site builder dashboard for vendors
     */
    public function vendorIndex()
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        
        if (!$tenant) {
            return redirect()->route('vendor.dashboard')
                ->with('error', 'No tenant associated with this account.');
        }
        
        $tenantId = $this->getTenantId();
        $settings = $this->getSiteSettings($tenantId);
        
        return view('vendor.site-builder.index', compact('settings', 'tenant'));
    }
    
    /**
     * Update basic information
     */
    public function updateBasicInfo(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        $validator = Validator::make($request->all(), [
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $settings = SiteSettings::where('tenant_id', $tenantId)->first();
        $settings->update($request->only([
            'site_name',
            'site_tagline',
            'site_description',
            'contact_email',
            'contact_phone',
            'address'
        ]));
        
        return response()->json([
            'success' => true,
            'message' => 'Basic information updated successfully',
            'settings' => $settings
        ]);
    }
    
    /**
     * Update theme settings
     */
    public function updateTheme(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        $validator = Validator::make($request->all(), [
            'theme' => 'required|in:modern,classic,minimal,elegant',
            'layout' => 'required|in:default,sidebar,fullwidth',
            'primary_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'accent_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'text_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'background_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'header_bg_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'footer_bg_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'font_family' => 'required|string',
            'font_size' => 'required|integer|min:12|max:24',
            'heading_font' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $settings = SiteSettings::where('tenant_id', $tenantId)->first();
        $settings->update($request->only([
            'theme',
            'layout',
            'primary_color',
            'secondary_color',
            'accent_color',
            'text_color',
            'background_color',
            'header_bg_color',
            'footer_bg_color',
            'font_family',
            'font_size',
            'heading_font'
        ]));
        
        return response()->json([
            'success' => true,
            'message' => 'Theme updated successfully',
            'settings' => $settings
        ]);
    }
    
    /**
     * Upload logo
     */
    public function uploadLogo(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        // Note: Laravel's 'image' rule rejects SVG. Allow SVG by validating via mimes only.
        $validator = Validator::make($request->all(), [
            'logo' => 'required|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $settings = SiteSettings::where('tenant_id', $tenantId)->first();
        
        // Delete old logo if exists
        if ($settings->logo && Storage::disk('public')->exists($settings->logo)) {
            Storage::disk('public')->delete($settings->logo);
        }
        
        // Store new logo
        $path = $request->file('logo')->store('site-assets/logos', 'public');
        $settings->update(['logo' => $path]);
        
        return response()->json([
            'success' => true,
            'message' => 'Logo uploaded successfully',
            'logo_url' => Storage::url($path),
            'settings' => $settings
        ]);
    }
    
    /**
     * Upload favicon
     */
    public function uploadFavicon(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        // Allow ICO and SVG for favicons; avoid 'image' rule so SVG is accepted
        $validator = Validator::make($request->all(), [
            'favicon' => 'required|mimes:ico,png,jpg,svg,webp|max:1024',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $settings = SiteSettings::where('tenant_id', $tenantId)->first();
        
        // Delete old favicon if exists
        if ($settings->favicon && Storage::disk('public')->exists($settings->favicon)) {
            Storage::disk('public')->delete($settings->favicon);
        }
        
        // Store new favicon
        $path = $request->file('favicon')->store('site-assets/favicons', 'public');
        $settings->update(['favicon' => $path]);
        
        return response()->json([
            'success' => true,
            'message' => 'Favicon uploaded successfully',
            'favicon_url' => Storage::url($path),
            'settings' => $settings
        ]);
    }
    
    /**
     * Update banner settings
     */
    public function updateBanner(Request $request)
    {
        try {
            $tenantId = $this->getTenantId();
            
            $validator = Validator::make($request->all(), [
                'banner_title' => 'nullable|string|max:255',
                'banner_subtitle' => 'nullable|string|max:500',
                'banner_button_text' => 'nullable|string|max:50',
                'banner_button_link' => 'nullable|string|max:255', // Accept any string (relative or full URL)
                'show_banner' => 'nullable|boolean',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $settings = $this->getSiteSettings($tenantId);
            
            // Convert checkbox value to boolean
            // Handle empty strings - save as empty string (not null) to work with current DB schema
            // After running migration, these can be changed to null
            $data = [
                'banner_title' => !empty(trim($request->input('banner_title', ''))) ? trim($request->input('banner_title')) : '',
                'banner_subtitle' => !empty(trim($request->input('banner_subtitle', ''))) ? trim($request->input('banner_subtitle')) : '',
                'banner_button_text' => !empty(trim($request->input('banner_button_text', ''))) ? trim($request->input('banner_button_text')) : '',
                'banner_button_link' => !empty(trim($request->input('banner_button_link', ''))) ? trim($request->input('banner_button_link')) : '',
            ];
            
            $data['show_banner'] = $request->has('show_banner') && ($request->show_banner == '1' || $request->show_banner === true);
            
            $settings->update($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Banner settings updated successfully',
                'settings' => $settings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save banner settings: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Upload banner image
     */
    public function uploadBannerImage(Request $request)
    {
        try {
            $tenantId = $this->getTenantId();
            
            // Accept common banner formats including webp; keep size at 5MB
            $validator = Validator::make($request->all(), [
                'banner_image' => 'required|mimes:jpeg,png,jpg,webp|max:5120',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $settings = $this->getSiteSettings($tenantId);
            
            // Delete old banner if exists
            if ($settings->banner_image && Storage::disk('public')->exists($settings->banner_image)) {
                Storage::disk('public')->delete($settings->banner_image);
            }
            
            // Store new banner
            $path = $request->file('banner_image')->store('site-assets/banners', 'public');
            $settings->update(['banner_image' => $path]);
            
            return response()->json([
                'success' => true,
                'message' => 'Banner image uploaded successfully',
                'banner_url' => Storage::url($path),
                'settings' => $settings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload banner image: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update navigation settings
     */
    public function updateNavigation(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        $validator = Validator::make($request->all(), [
            'navigation_links' => 'nullable',
            'show_categories_menu' => 'required|boolean',
            'show_search_bar' => 'required|boolean',
            'show_cart_icon' => 'required|boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $settings = SiteSettings::where('tenant_id', $tenantId)->first();
        
        $data = $request->only([
            'show_categories_menu',
            'show_search_bar',
            'show_cart_icon'
        ]);
        
        // Handle navigation_links - can be JSON string or array
        if ($request->has('navigation_links')) {
            $navigationLinks = $request->navigation_links;
            if (is_string($navigationLinks)) {
                $decoded = json_decode($navigationLinks, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data['navigation_links'] = $decoded;
                } else {
                    // If JSON decode fails, try to parse as array from form data
                    $data['navigation_links'] = [];
                }
            } else {
                $data['navigation_links'] = $navigationLinks;
            }
        } else {
            // If no navigation_links provided, set empty array
            $data['navigation_links'] = [];
        }
        
        $settings->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Navigation settings updated successfully',
            'settings' => $settings
        ]);
    }
    
    /**
     * Update homepage sections
     */
    public function updateHomepage(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        $validator = Validator::make($request->all(), [
            'show_featured_products' => 'required|boolean',
            'show_new_arrivals' => 'required|boolean',
            'show_categories' => 'required|boolean',
            'show_testimonials' => 'required|boolean',
            'show_about_section' => 'required|boolean',
            'homepage_sections_order' => 'nullable|json',
            'show_hero_slides' => 'nullable|boolean',
            'hero_slides' => 'nullable|json',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $settings = SiteSettings::where('tenant_id', $tenantId)->first();
        
        $data = $request->only([
            'show_featured_products',
            'show_new_arrivals',
            'show_categories',
            'show_testimonials',
            'show_about_section'
        ]);
        
        if ($request->has('homepage_sections_order')) {
            $data['homepage_sections_order'] = json_decode($request->homepage_sections_order, true);
        }
        
        // Handle hero slides in additional_settings
        $additionalSettings = $settings->additional_settings ?? [];
        if ($request->has('show_hero_slides')) {
            $additionalSettings['show_hero_slides'] = $request->show_hero_slides == '1' || $request->show_hero_slides === true;
        }
        if ($request->has('hero_slides')) {
            $additionalSettings['hero_slides'] = json_decode($request->hero_slides, true);
        }
        if ($request->has('slide_height')) {
            $additionalSettings['slide_height'] = (float) $request->slide_height;
        }
        if ($request->has('slide_height_unit')) {
            $additionalSettings['slide_height_unit'] = $request->slide_height_unit;
        }
        $data['additional_settings'] = $additionalSettings;
        
        $settings->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Homepage sections updated successfully',
            'settings' => $settings
        ]);
    }
    
    /**
     * Upload slide image
     */
    public function uploadSlideImage(Request $request)
    {
        try {
            $user = Auth::user();
            $tenantId = $user->tenant_id;
            
            $validator = Validator::make($request->all(), [
                'slide_image' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048', // Reduced to 2MB for better server compatibility
                'slide_index' => 'nullable|integer',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            if (!$request->hasFile('slide_image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No image file provided'
                ], 422);
            }
            
            $file = $request->file('slide_image');
            
            // Ensure the directory exists
            $directory = 'site-assets/slides';
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }
            
            // Store slide image
            $path = $file->store($directory, 'public');
            
            if (!$path) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store image file'
                ], 500);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Slide image uploaded successfully',
                'image_path' => $path,
                'image_url' => Storage::disk('public')->url($path)
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error uploading slide image: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update product display settings
     */
    public function updateProducts(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        $validator = Validator::make($request->all(), [
            'product_card_style' => 'required|in:card,grid,list',
            'products_per_page' => 'required|integer|min:6|max:48',
            'show_product_ratings' => 'required|boolean',
            'show_quick_view' => 'required|boolean',
            'show_add_to_cart_button' => 'required|boolean',
            'products_archive_slug' => 'nullable|string|regex:/^[a-z0-9-]+$/|max:255',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $settings = SiteSettings::where('tenant_id', $tenantId)->first();
        $settings->update($request->only([
            'product_card_style',
            'products_per_page',
            'show_product_ratings',
            'show_quick_view',
            'show_add_to_cart_button'
        ]));
        
        // Save archive slug in additional_settings
        $additionalSettings = $settings->additional_settings ?? [];
        if ($request->has('products_archive_slug')) {
            $archiveSlug = $request->products_archive_slug ?: 'products';
            $additionalSettings['products_archive_slug'] = \Illuminate\Support\Str::slug($archiveSlug);
        }
        $settings->additional_settings = $additionalSettings;
        $settings->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Product display settings updated successfully',
            'settings' => $settings
        ]);
    }
    
    /**
     * Update footer settings
     */
    public function updateFooter(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        $validator = Validator::make($request->all(), [
            'footer_about' => 'nullable|string',
            'footer_links' => 'nullable|json',
            'show_social_links' => 'required|boolean',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $settings = SiteSettings::where('tenant_id', $tenantId)->first();
        
        $data = $request->only([
            'footer_about',
            'show_social_links',
            'facebook_url',
            'instagram_url',
            'twitter_url',
            'youtube_url',
            'linkedin_url'
        ]);
        
        if ($request->has('footer_links')) {
            $data['footer_links'] = json_decode($request->footer_links, true);
        }
        
        $settings->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Footer settings updated successfully',
            'settings' => $settings
        ]);
    }
    
    /**
     * Update SEO settings
     */
    public function updateSeo(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        $validator = Validator::make($request->all(), [
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'google_analytics_code' => 'nullable|string',
            'facebook_pixel_code' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $settings = SiteSettings::where('tenant_id', $tenantId)->first();
        $settings->update($request->only([
            'meta_title',
            'meta_description',
            'meta_keywords',
            'google_analytics_code',
            'facebook_pixel_code'
        ]));
        
        return response()->json([
            'success' => true,
            'message' => 'SEO settings updated successfully',
            'settings' => $settings
        ]);
    }
    
    /**
     * Upload OG image
     */
    public function uploadOgImage(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        $validator = Validator::make($request->all(), [
            'og_image' => 'required|mimes:jpeg,png,jpg,webp|max:4096',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $settings = SiteSettings::where('tenant_id', $tenantId)->first();
        
        // Delete old OG image if exists
        if ($settings->og_image && Storage::disk('public')->exists($settings->og_image)) {
            Storage::disk('public')->delete($settings->og_image);
        }
        
        // Store new OG image
        $path = $request->file('og_image')->store('site-assets/og-images', 'public');
        $settings->update(['og_image' => $path]);
        
        return response()->json([
            'success' => true,
            'message' => 'OG image uploaded successfully',
            'og_image_url' => Storage::url($path),
            'settings' => $settings
        ]);
    }
    
    /**
     * Update e-commerce settings
     */
    public function updateEcommerce(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        $validator = Validator::make($request->all(), [
            'currency' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:10',
            'currency_position' => 'required|in:before,after',
            'enable_guest_checkout' => 'required|boolean',
            'enable_reviews' => 'required|boolean',
            'enable_wishlist' => 'required|boolean',
            'min_order_amount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'required|numeric|min:0',
            'free_shipping_over' => 'required|boolean',
            'free_shipping_amount' => 'nullable|numeric|min:0',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $settings = SiteSettings::where('tenant_id', $tenantId)->first();
        $settings->update($request->only([
            'currency',
            'currency_symbol',
            'currency_position',
            'enable_guest_checkout',
            'enable_reviews',
            'enable_wishlist',
            'min_order_amount',
            'shipping_cost',
            'free_shipping_over',
            'free_shipping_amount'
        ]));
        
        return response()->json([
            'success' => true,
            'message' => 'E-commerce settings updated successfully',
            'settings' => $settings
        ]);
    }
    
    /**
     * Update custom code
     */
    public function updateCustomCode(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        $validator = Validator::make($request->all(), [
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
            'header_code' => 'nullable|string',
            'footer_code' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $settings = SiteSettings::where('tenant_id', $tenantId)->first();
        $settings->update($request->only([
            'custom_css',
            'custom_js',
            'header_code',
            'footer_code'
        ]));
        
        return response()->json([
            'success' => true,
            'message' => 'Custom code updated successfully',
            'settings' => $settings
        ]);
    }
    
    /**
     * Update notifications settings
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        $validator = Validator::make($request->all(), [
            'show_cookie_notice' => 'required|boolean',
            'cookie_notice_text' => 'nullable|string',
            'show_promo_popup' => 'required|boolean',
            'promo_popup_content' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $settings = SiteSettings::where('tenant_id', $tenantId)->first();
        $settings->update($request->only([
            'show_cookie_notice',
            'cookie_notice_text',
            'show_promo_popup',
            'promo_popup_content'
        ]));
        
        return response()->json([
            'success' => true,
            'message' => 'Notification settings updated successfully',
            'settings' => $settings
        ]);
    }
    
    /**
     * Update maintenance mode
     */
    public function updateMaintenance(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        $validator = Validator::make($request->all(), [
            'maintenance_mode' => 'required|boolean',
            'maintenance_message' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $settings = SiteSettings::where('tenant_id', $tenantId)->first();
        $settings->update($request->only([
            'maintenance_mode',
            'maintenance_message'
        ]));
        
        return response()->json([
            'success' => true,
            'message' => 'Maintenance mode updated successfully',
            'settings' => $settings
        ]);
    }
    
    /**
     * Get preview URL
     */
    public function getPreviewUrl()
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        
        // Build absolute URL honoring subdirectory installs (e.g., /e-manager/public)
        // route(..., [], false) gives a relative path we can prepend with the current base
        $relative = route('storefront.preview', ['subdomain' => $tenant->subdomain], false); // e.g., /storefront/{subdomain}
        $base = rtrim(request()->getSchemeAndHttpHost() . request()->getBaseUrl(), '/');
        $previewUrl = $base . $relative;
        
        return response()->json([
            'success' => true,
            'preview_url' => $previewUrl,
            'subdomain' => $tenant->subdomain
        ]);
    }
}
