<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        // Basic Information
        'site_name',
        'site_tagline',
        'site_description',
        'logo',
        'favicon',
        'contact_email',
        'contact_phone',
        'address',
        
        // Theme & Appearance
        'theme',
        'layout',
        'primary_color',
        'secondary_color',
        'accent_color',
        'text_color',
        'background_color',
        'header_bg_color',
        'footer_bg_color',
        
        // Typography
        'font_family',
        'font_size',
        'heading_font',
        
        // Hero/Banner Section
        'banner_image',
        'banner_title',
        'banner_subtitle',
        'banner_button_text',
        'banner_button_link',
        'show_banner',
        
        // Navigation
        'navigation_links',
        'show_categories_menu',
        'show_search_bar',
        'show_cart_icon',
        
        // Homepage Sections
        'show_featured_products',
        'show_new_arrivals',
        'show_categories',
        'show_testimonials',
        'show_about_section',
        'homepage_sections_order',
        
        // Product Display
        'product_card_style',
        'products_per_page',
        'show_product_ratings',
        'show_quick_view',
        'show_add_to_cart_button',
        
        // Footer
        'footer_about',
        'footer_links',
        'show_social_links',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'youtube_url',
        'linkedin_url',
        
        // SEO Settings
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'google_analytics_code',
        'facebook_pixel_code',
        
        // E-commerce Settings
        'currency',
        'currency_symbol',
        'currency_position',
        'enable_guest_checkout',
        'enable_reviews',
        'enable_wishlist',
        'min_order_amount',
        'shipping_cost',
        'free_shipping_over',
        'free_shipping_amount',
        
        // Notifications & Popups
        'show_cookie_notice',
        'cookie_notice_text',
        'show_promo_popup',
        'promo_popup_content',
        'promo_popup_image',
        
        // Custom Code
        'custom_css',
        'custom_js',
        'header_code',
        'footer_code',
        
        // Maintenance & Status
        'is_active',
        'maintenance_mode',
        'maintenance_message',
        
        // Additional Settings
        'additional_settings',
    ];

    protected $casts = [
        'navigation_links' => 'array',
        'footer_links' => 'array',
        'homepage_sections_order' => 'array',
        'additional_settings' => 'array',
        'show_banner' => 'boolean',
        'show_categories_menu' => 'boolean',
        'show_search_bar' => 'boolean',
        'show_cart_icon' => 'boolean',
        'show_featured_products' => 'boolean',
        'show_new_arrivals' => 'boolean',
        'show_categories' => 'boolean',
        'show_testimonials' => 'boolean',
        'show_about_section' => 'boolean',
        'show_product_ratings' => 'boolean',
        'show_quick_view' => 'boolean',
        'show_add_to_cart_button' => 'boolean',
        'show_social_links' => 'boolean',
        'enable_guest_checkout' => 'boolean',
        'enable_reviews' => 'boolean',
        'enable_wishlist' => 'boolean',
        'free_shipping_over' => 'boolean',
        'show_cookie_notice' => 'boolean',
        'show_promo_popup' => 'boolean',
        'is_active' => 'boolean',
        'maintenance_mode' => 'boolean',
        'min_order_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'free_shipping_amount' => 'decimal:2',
        'font_size' => 'integer',
        'products_per_page' => 'integer',
    ];

    /**
     * Append URL attributes to JSON
     */
    protected $appends = [
        'logo_url',
        'favicon_url',
        'banner_image_url',
        'og_image_url',
        'promo_popup_image_url'
    ];

    /**
     * Get the tenant that owns the site settings.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the logo URL attribute
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    /**
     * Get the favicon URL attribute
     */
    public function getFaviconUrlAttribute()
    {
        return $this->favicon ? asset('storage/' . $this->favicon) : null;
    }

    /**
     * Get the banner image URL attribute
     */
    public function getBannerImageUrlAttribute()
    {
        return $this->banner_image ? asset('storage/' . $this->banner_image) : null;
    }

    /**
     * Get the OG image URL attribute
     */
    public function getOgImageUrlAttribute()
    {
        return $this->og_image ? asset('storage/' . $this->og_image) : null;
    }

    /**
     * Get the promo popup image URL attribute
     */
    public function getPromoPopupImageUrlAttribute()
    {
        return $this->promo_popup_image ? asset('storage/' . $this->promo_popup_image) : null;
    }

    /**
     * Get default settings for a new tenant
     */
    public static function getDefaultSettings()
    {
        return [
            'site_name' => 'My Store',
            'site_tagline' => 'Your tagline here',
            'theme' => 'modern',
            'layout' => 'default',
            'primary_color' => '#667eea',
            'secondary_color' => '#764ba2',
            'accent_color' => '#10b981',
            'text_color' => '#1e293b',
            'background_color' => '#ffffff',
            'header_bg_color' => '#ffffff',
            'footer_bg_color' => '#1e293b',
            'font_family' => 'Inter',
            'font_size' => 16,
            'heading_font' => 'Inter',
            'banner_button_text' => 'Shop Now',
            'banner_button_link' => '/products',
            'show_banner' => true,
            'show_categories_menu' => true,
            'show_search_bar' => true,
            'show_cart_icon' => true,
            'show_featured_products' => true,
            'show_new_arrivals' => true,
            'show_categories' => true,
            'show_about_section' => true,
            'product_card_style' => 'card',
            'products_per_page' => 12,
            'show_product_ratings' => true,
            'show_quick_view' => true,
            'show_add_to_cart_button' => true,
            'show_social_links' => true,
            'currency' => 'NPR',
            'currency_symbol' => 'Rs.',
            'currency_position' => 'before',
            'enable_guest_checkout' => true,
            'enable_reviews' => true,
            'enable_wishlist' => true,
            'shipping_cost' => 0,
            'free_shipping_over' => false,
            'show_cookie_notice' => true,
            'show_promo_popup' => false,
            'is_active' => true,
            'maintenance_mode' => false,
        ];
    }
}
