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
        Schema::create('site_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            
            // Page Basic Info
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            
            // Page Type
            $table->enum('page_type', ['about', 'contact', 'products', 'categories', 'custom'])->default('custom');
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            // Page Settings
            $table->boolean('is_active')->default(true);
            $table->boolean('show_in_menu')->default(true);
            $table->integer('menu_order')->default(0);
            
            // Page Features
            $table->string('banner_image')->nullable();
            $table->string('template')->default('default'); // default, full-width, sidebar
            
            // Contact Page Specific
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('contact_address')->nullable();
            $table->string('map_iframe')->nullable();
            
            // Custom CSS/JS
            $table->text('custom_css')->nullable();
            $table->text('custom_js')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('tenant_id');
            $table->index('slug');
            $table->index('page_type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_pages');
    }
};
