<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SMS Templates
        Schema::create('sms_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('content');
            $table->enum('type', ['marketing', 'notification', 'transactional', 'otp'])->default('notification');
            $table->json('variables')->nullable(); // Available variables like {name}, {order_id}
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // SMS Campaigns
        Schema::create('sms_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('message');
            $table->enum('recipient_type', ['all', 'active', 'trial', 'pending', 'custom'])->default('all');
            $table->json('custom_recipients')->nullable(); // For custom phone numbers
            $table->enum('status', ['draft', 'scheduled', 'sending', 'completed', 'failed'])->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->integer('total_recipients')->default(0);
            $table->integer('sent_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->integer('delivered_count')->default(0);
            $table->decimal('cost', 10, 2)->default(0);
            $table->timestamps();
        });

        // SMS Messages (Individual SMS logs)
        Schema::create('sms_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->nullable()->constrained('sms_campaigns')->onDelete('cascade');
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->onDelete('set null');
            $table->string('phone_number');
            $table->text('message');
            $table->enum('type', ['marketing', 'notification', 'transactional', 'otp'])->default('notification');
            $table->enum('status', ['pending', 'sent', 'delivered', 'failed'])->default('pending');
            $table->string('provider_message_id')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->decimal('cost', 10, 4)->default(0);
            $table->timestamps();
        });

        // SMS Statistics (Daily aggregate)
        Schema::create('sms_statistics', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('total_sent')->default(0);
            $table->integer('total_delivered')->default(0);
            $table->integer('total_failed')->default(0);
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->timestamps();
            $table->unique('date');
        });

        // SMS Credits/Balance
        Schema::create('sms_credits', function (Blueprint $table) {
            $table->id();
            $table->integer('balance')->default(0);
            $table->decimal('cost_per_sms', 10, 4)->default(0.05); // $0.05 per SMS
            $table->integer('total_purchased')->default(0);
            $table->integer('total_used')->default(0);
            $table->timestamps();
        });

        // Initialize SMS credits
        DB::table('sms_credits')->insert([
            'balance' => 1000, // Start with 1000 free credits
            'cost_per_sms' => 0.05,
            'total_purchased' => 1000,
            'total_used' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_credits');
        Schema::dropIfExists('sms_statistics');
        Schema::dropIfExists('sms_messages');
        Schema::dropIfExists('sms_campaigns');
        Schema::dropIfExists('sms_templates');
    }
};
