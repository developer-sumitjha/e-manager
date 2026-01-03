<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->unique(); // TEN001, TEN002, etc.
            $table->string('business_name');
            $table->string('subdomain')->unique()->nullable(); // vendor-name.emanager.com
            $table->string('domain')->unique()->nullable(); // custom domain if needed
            
            // Business Details
            $table->string('business_type')->nullable(); // retail, wholesale, restaurant, etc.
            $table->string('business_email')->unique();
            $table->string('business_phone')->nullable();
            $table->text('business_address')->nullable();
            $table->string('pan_number')->nullable(); // VAT/PAN
            $table->string('registration_number')->nullable();
            
            // Primary Contact
            $table->string('owner_name');
            $table->string('owner_email');
            $table->string('owner_phone');
            $table->string('password'); // For tenant admin login
            
            // Subscription
            $table->unsignedBigInteger('current_plan_id')->nullable();
            $table->enum('status', ['pending', 'active', 'suspended', 'cancelled', 'trial'])->default('trial');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('subscription_starts_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            
            // Settings & Configuration
            $table->json('settings')->nullable(); // Custom settings per tenant
            $table->json('features')->nullable(); // Enabled features
            $table->integer('max_orders')->default(1000); // Monthly order limit
            $table->integer('max_products')->default(500);
            $table->integer('max_users')->default(10);
            
            // Database Configuration (if using separate DBs)
            $table->string('database_name')->nullable();
            $table->string('database_host')->nullable();
            $table->string('database_username')->nullable();
            $table->string('database_password')->nullable();
            
            // Status & Metadata
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->ipAddress('last_login_ip')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('subdomain');
            $table->index('status');
            $table->index('business_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
