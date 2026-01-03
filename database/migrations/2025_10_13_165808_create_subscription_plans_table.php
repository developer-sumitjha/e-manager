<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_id')->unique(); // PLAN-FREE, PLAN-BASIC, PLAN-PRO
            $table->string('name'); // Free, Basic, Professional, Enterprise
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            // Pricing
            $table->decimal('price_monthly', 10, 2)->default(0);
            $table->decimal('price_yearly', 10, 2)->default(0);
            $table->string('currency')->default('NPR');
            
            // Limits
            $table->integer('max_orders_per_month')->default(100);
            $table->integer('max_products')->default(50);
            $table->integer('max_users')->default(5);
            $table->integer('max_storage_gb')->default(1); // Storage limit in GB
            
            // Features
            $table->boolean('has_inventory')->default(true);
            $table->boolean('has_manual_delivery')->default(false);
            $table->boolean('has_logistics_integration')->default(false);
            $table->boolean('has_accounting')->default(false);
            $table->boolean('has_analytics')->default(false);
            $table->boolean('has_api_access')->default(false);
            $table->boolean('has_multi_user')->default(false);
            $table->boolean('has_custom_domain')->default(false);
            $table->boolean('has_priority_support')->default(false);
            
            // Trial
            $table->integer('trial_days')->default(14);
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
            
            $table->index('slug');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
