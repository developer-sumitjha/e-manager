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
        Schema::create('gaaubesi_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('shipment_id')->nullable()->constrained()->onDelete('set null');
            
            // Gaaubesi Order Details
            $table->string('gaaubesi_order_id')->nullable()->unique();
            $table->string('track_id')->nullable();
            
            // Branch Information
            $table->string('source_branch')->default('HEAD OFFICE');
            $table->string('destination_branch')->default('HEAD OFFICE');
            
            // Receiver Information
            $table->string('receiver_name');
            $table->string('receiver_address');
            $table->string('receiver_number');
            
            // Order Details
            $table->decimal('cod_charge', 10, 2);
            $table->decimal('delivery_charge', 10, 2)->nullable();
            $table->string('package_access')->default("Can't Open"); // Can't Open / Can Open
            $table->string('delivery_type')->default('Drop Off'); // Pickup / Drop Off
            $table->text('remarks')->nullable();
            $table->string('package_type')->nullable();
            
            // Sub Vendor Information (optional)
            $table->string('order_contact_name')->nullable();
            $table->string('order_contact_number')->nullable();
            
            // Status Tracking
            $table->string('last_delivery_status')->nullable();
            $table->boolean('cod_paid')->default(false);
            
            // API Response Data
            $table->json('api_response')->nullable();
            $table->json('status_history')->nullable();
            
            // Timestamps
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes
            $table->index('gaaubesi_order_id');
            $table->index('track_id');
            $table->index('last_delivery_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gaaubesi_shipments');
    }
};








