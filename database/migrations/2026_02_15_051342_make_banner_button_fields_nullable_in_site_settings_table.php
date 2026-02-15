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
        Schema::table('site_settings', function (Blueprint $table) {
            // Make banner button fields nullable and remove defaults
            $table->string('banner_button_text')->nullable()->default(null)->change();
            $table->string('banner_button_link')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // Restore defaults (for rollback)
            $table->string('banner_button_text')->default('Shop Now')->change();
            $table->string('banner_button_link')->default('/products')->change();
        });
    }
};
