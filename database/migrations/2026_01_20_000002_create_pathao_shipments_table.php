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
        Schema::create('pathao_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('shipment_id')->nullable()->constrained()->onDelete('set null');
            
            // Pathao Order Details
            $table->string('pathao_order_id')->nullable()->unique();
            $table->string('consignment_id')->nullable();
            $table->string('tracking_id')->nullable();
            
            // Store Information
            $table->integer('store_id')->nullable();
            
            // Item Details
            $table->integer('item_type')->default(2); // 1 = Document, 2 = Parcel
            $table->integer('delivery_type')->default(48); // 48 = Normal, 12 = On Demand
            $table->decimal('item_weight', 5, 2)->default(0.5);
            
            // Recipient Information
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->text('recipient_address');
            $table->integer('recipient_city_id');
            $table->string('recipient_city_name')->nullable();
            $table->integer('recipient_zone_id');
            $table->string('recipient_zone_name')->nullable();
            $table->integer('recipient_area_id')->nullable();
            $table->string('recipient_area_name')->nullable();
            
            // Order Details
            $table->decimal('amount', 10, 2)->default(0); // COD amount
            $table->decimal('delivery_charge', 10, 2)->nullable();
            $table->string('item_quantity')->default('1');
            $table->text('item_description')->nullable();
            $table->text('special_instruction')->nullable();
            $table->string('merchant_order_id')->nullable();
            
            // Status Tracking
            $table->string('status')->nullable();
            $table->string('status_type')->nullable();
            $table->boolean('cod_collected')->default(false);
            $table->decimal('cod_amount', 10, 2)->nullable();
            
            // API Response Data
            $table->json('api_response')->nullable();
            $table->json('status_history')->nullable();
            
            // Timestamps
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes
            $table->index('tenant_id');
            $table->index('pathao_order_id');
            $table->index('consignment_id');
            $table->index('tracking_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pathao_shipments');
    }
};
