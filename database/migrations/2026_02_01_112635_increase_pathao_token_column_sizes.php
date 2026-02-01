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
        if (Schema::hasTable('pathao_settings')) {
            Schema::table('pathao_settings', function (Blueprint $table) {
                // Change access_token and refresh_token from VARCHAR(255) to TEXT
                $table->text('access_token')->nullable()->change();
                $table->text('refresh_token')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pathao_settings')) {
            Schema::table('pathao_settings', function (Blueprint $table) {
                // Revert back to VARCHAR(255) - though this might truncate existing data
                $table->string('access_token', 255)->nullable()->change();
                $table->string('refresh_token', 255)->nullable()->change();
            });
        }
    }
};
