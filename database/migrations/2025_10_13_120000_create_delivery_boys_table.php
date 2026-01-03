<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_boys', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_boy_id')->unique(); // DB001, DB002, etc.
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->nullable()->unique();
            $table->string('password');
            $table->string('cnic')->nullable();
            $table->string('license_number')->nullable();
            $table->text('address')->nullable();
            $table->enum('zone', ['north', 'south', 'east', 'west', 'central'])->nullable();
            $table->enum('vehicle_type', ['motorcycle', 'bicycle', 'car', 'van'])->nullable();
            $table->string('vehicle_number')->nullable();
            $table->enum('status', ['active', 'inactive', 'on_duty', 'off_duty'])->default('active');
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('total_deliveries')->default(0);
            $table->integer('successful_deliveries')->default(0);
            $table->integer('cancelled_deliveries')->default(0);
            $table->decimal('total_cod_collected', 15, 2)->default(0.00);
            $table->decimal('pending_settlement', 15, 2)->default(0.00);
            $table->string('profile_photo')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('remember_token')->nullable();
            $table->timestamps();
        });

        // Manual delivery assignments
        Schema::create('manual_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('delivery_boy_id')->constrained('delivery_boys')->onDelete('cascade');
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['assigned', 'picked_up', 'in_transit', 'delivered', 'cancelled', 'failed'])->default('assigned');
            $table->timestamp('assigned_at');
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('delivery_notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->decimal('cod_amount', 15, 2)->default(0.00);
            $table->boolean('cod_collected')->default(false);
            $table->boolean('cod_settled')->default(false);
            $table->timestamp('cod_settled_at')->nullable();
            $table->string('delivery_proof')->nullable(); // Image proof
            $table->string('customer_signature')->nullable();
            $table->decimal('customer_rating', 3, 2)->nullable();
            $table->text('customer_feedback')->nullable();
            $table->timestamps();
        });

        // COD settlements
        Schema::create('cod_settlements', function (Blueprint $table) {
            $table->id();
            $table->string('settlement_id')->unique(); // SET-001, SET-002
            $table->foreignId('delivery_boy_id')->constrained('delivery_boys')->onDelete('cascade');
            $table->foreignId('settled_by')->constrained('users')->onDelete('cascade');
            $table->decimal('total_amount', 15, 2);
            $table->integer('total_orders');
            $table->text('order_ids'); // JSON array of order IDs
            $table->enum('payment_method', ['cash', 'bank_transfer', 'cheque', 'mobile_wallet'])->default('cash');
            $table->string('transaction_reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('settled_at');
            $table->timestamps();
        });

        // Delivery boy activity log
        Schema::create('delivery_boy_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_boy_id')->constrained('delivery_boys')->onDelete('cascade');
            $table->foreignId('manual_delivery_id')->nullable()->constrained('manual_deliveries')->onDelete('cascade');
            $table->string('activity_type'); // login, logout, order_picked, order_delivered, etc.
            $table->text('description');
            $table->text('meta_data')->nullable(); // JSON data
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_boy_activities');
        Schema::dropIfExists('cod_settlements');
        Schema::dropIfExists('manual_deliveries');
        Schema::dropIfExists('delivery_boys');
    }
};







