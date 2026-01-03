<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->unique();
            
            // Basic Information
            $table->string('site_name')->default('My Store');
            $table->string('site_tagline')->nullable();
            $table->text('site_description')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('address')->nullable();
            
            // Theme & Appearance
            $table->string('theme', 50)->default('modern'); // modern, classic, minimal, etc.
            $table->string('layout', 50)->default('default'); // default, sidebar, fullwidth
            $table->string('primary_color', 7)->default('#667eea');
            $table->string('secondary_color', 7)->default('#764ba2');
            $table->string('accent_color', 7)->default('#10b981');
            $table->string('text_color', 7)->default('#1e293b');
            $table->string('background_color', 7)->default('#ffffff');
            $table->string('header_bg_color', 7)->default('#ffffff');
            $table->string('footer_bg_color', 7)->default('#1e293b');
            
            // Typography
            $table->string('font_family')->default('Inter');
            $table->integer('font_size')->default(16);
            $table->string('heading_font')->default('Inter');
            
            // Hero/Banner Section
            $table->string('banner_image')->nullable();
            $table->string('banner_title')->nullable();
            $table->string('banner_subtitle')->nullable();
            $table->string('banner_button_text')->default('Shop Now');
            $table->string('banner_button_link')->default('/products');
            $table->boolean('show_banner')->default(true);
            
            // Navigation
            $table->json('navigation_links')->nullable(); // Custom nav links
            $table->boolean('show_categories_menu')->default(true);
            $table->boolean('show_search_bar')->default(true);
            $table->boolean('show_cart_icon')->default(true);
            
            // Homepage Sections
            $table->boolean('show_featured_products')->default(true);
            $table->boolean('show_new_arrivals')->default(true);
            $table->boolean('show_categories')->default(true);
            $table->boolean('show_testimonials')->default(false);
            $table->boolean('show_about_section')->default(true);
            $table->json('homepage_sections_order')->nullable();
            
            // Product Display
            $table->string('product_card_style', 50)->default('card'); // card, grid, list
            $table->integer('products_per_page')->default(12);
            $table->boolean('show_product_ratings')->default(true);
            $table->boolean('show_quick_view')->default(true);
            $table->boolean('show_add_to_cart_button')->default(true);
            
            // Footer
            $table->text('footer_about')->nullable();
            $table->json('footer_links')->nullable();
            $table->boolean('show_social_links')->default(true);
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('linkedin_url')->nullable();
            
            // SEO Settings
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('og_image')->nullable();
            $table->text('google_analytics_code')->nullable();
            $table->text('facebook_pixel_code')->nullable();
            
            // E-commerce Settings
            $table->string('currency', 10)->default('NPR');
            $table->string('currency_symbol', 10)->default('Rs.');
            $table->string('currency_position', 10)->default('before'); // before, after
            $table->boolean('enable_guest_checkout')->default(true);
            $table->boolean('enable_reviews')->default(true);
            $table->boolean('enable_wishlist')->default(true);
            $table->decimal('min_order_amount', 10, 2)->nullable();
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->boolean('free_shipping_over')->default(false);
            $table->decimal('free_shipping_amount', 10, 2)->nullable();
            
            // Notifications & Popups
            $table->boolean('show_cookie_notice')->default(true);
            $table->text('cookie_notice_text')->nullable();
            $table->boolean('show_promo_popup')->default(false);
            $table->text('promo_popup_content')->nullable();
            $table->string('promo_popup_image')->nullable();
            
            // Custom Code
            $table->text('custom_css')->nullable();
            $table->text('custom_js')->nullable();
            $table->text('header_code')->nullable(); // For analytics, etc.
            $table->text('footer_code')->nullable();
            
            // Maintenance & Status
            $table->boolean('is_active')->default(true);
            $table->boolean('maintenance_mode')->default(false);
            $table->text('maintenance_message')->nullable();
            
            // Additional Settings (JSON for flexibility)
            $table->json('additional_settings')->nullable();
            
            $table->timestamps();
            
            // Foreign key
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
