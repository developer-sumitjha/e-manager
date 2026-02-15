<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SitePage extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'title',
        'slug',
        'description',
        'content',
        'page_type',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active',
        'show_in_menu',
        'menu_order',
        'banner_image',
        'template',
        'contact_email',
        'contact_phone',
        'contact_address',
        'map_iframe',
        'team_members',
        'custom_css',
        'custom_js',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_in_menu' => 'boolean',
        'menu_order' => 'integer',
        'team_members' => 'array',
    ];

    protected $appends = ['banner_image_url'];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
            
            // Ensure unique slug per tenant
            $originalSlug = $page->slug;
            $counter = 1;
            while (static::where('tenant_id', $page->tenant_id)
                ->where('slug', $page->slug)
                ->exists()) {
                $page->slug = $originalSlug . '-' . $counter;
                $counter++;
            }
        });
    }

    /**
     * Get the tenant that owns the page
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the banner image URL
     */
    public function getBannerImageUrlAttribute()
    {
        return $this->banner_image ? asset('storage/' . $this->banner_image) : null;
    }

    /**
     * Get page type label
     */
    public function getPageTypeLabelAttribute()
    {
        $labels = [
            'about' => 'About Us',
            'contact' => 'Contact Us',
            'products' => 'Products',
            'categories' => 'Categories',
            'custom' => 'Custom Page',
        ];

        return $labels[$this->page_type] ?? 'Custom Page';
    }

    /**
     * Scope a query to only include active pages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include pages shown in menu
     */
    public function scopeInMenu($query)
    {
        return $query->where('show_in_menu', true)->orderBy('menu_order');
    }

    /**
     * Get pages by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('page_type', $type);
    }
}
