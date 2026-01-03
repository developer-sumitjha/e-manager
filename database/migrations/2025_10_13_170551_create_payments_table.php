<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->unique(); // PAY-0001
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->onDelete('set null');
            $table->unsignedBigInteger('invoice_id')->nullable();
            
            // Payment Details
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('NPR');
            $table->enum('payment_method', ['esewa', 'khalti', 'bank_transfer', 'cash', 'other']);
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            
            // Gateway Response
            $table->string('transaction_id')->nullable(); // From payment gateway
            $table->string('gateway_reference')->nullable();
            $table->json('gateway_response')->nullable();
            
            // Metadata
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            
            $table->timestamps();
            
            $table->index('tenant_id');
            $table->index('status');
            $table->index('payment_method');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_payments');
    }
};
