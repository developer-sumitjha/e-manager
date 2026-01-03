<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('subscription_id')->unique(); // SUB-0001, SUB-0002
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('subscription_plans');
            
            // Subscription Period
            $table->enum('billing_cycle', ['monthly', 'yearly'])->default('monthly');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            
            // Pricing
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('NPR');
            
            // Status
            $table->enum('status', ['active', 'cancelled', 'expired', 'past_due', 'trial'])->default('trial');
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            // Auto-renewal
            $table->boolean('auto_renew')->default(true);
            $table->timestamp('next_billing_date')->nullable();
            
            // Payment
            $table->string('payment_method')->nullable(); // esewa, khalti, bank_transfer
            $table->string('payment_reference')->nullable();
            $table->timestamp('last_payment_at')->nullable();
            
            $table->timestamps();
            
            $table->index('tenant_id');
            $table->index('status');
            $table->index('ends_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
