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
        Schema::create('pathao_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            
            // OAuth Credentials
            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            
            // API Configuration
            $table->string('api_url')->default('https://courier-api-sandbox.pathao.com');
            $table->text('access_token')->nullable(); // JWT tokens can be very long
            $table->text('refresh_token')->nullable(); // Refresh tokens can be very long
            $table->timestamp('token_expires_at')->nullable();
            
            // Store Information
            $table->integer('store_id')->nullable();
            $table->string('store_name')->nullable();
            
            // Default Settings
            $table->integer('default_item_type')->default(2); // 1 = Document, 2 = Parcel
            $table->integer('default_delivery_type')->default(48); // 48 = Normal, 12 = On Demand
            $table->decimal('default_item_weight', 5, 2)->default(0.5); // in kg
            
            // Pickup Information
            $table->integer('pickup_city_id')->nullable();
            $table->integer('pickup_zone_id')->nullable();
            $table->integer('pickup_area_id')->nullable();
            $table->text('pickup_address')->nullable();
            $table->string('pickup_contact_name')->nullable();
            $table->string('pickup_contact_number')->nullable();
            
            // Settings
            $table->boolean('auto_create_shipment')->default(false);
            $table->boolean('send_notifications')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pathao_settings');
    }
};
