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
        Schema::create('gaaubesi_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('api_token')->nullable();
            $table->string('api_url')->default('https://api.gaaubesi.com');
            $table->string('default_package_access')->default('Accessible');
            $table->string('default_delivery_type')->default('Normal');
            $table->string('default_insurance')->default('Not Insured');
            $table->string('pickup_branch')->nullable();
            $table->text('pickup_address')->nullable();
            $table->string('pickup_contact_person')->nullable();
            $table->string('pickup_contact_phone')->nullable();
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
        Schema::dropIfExists('gaaubesi_settings');
    }
};
