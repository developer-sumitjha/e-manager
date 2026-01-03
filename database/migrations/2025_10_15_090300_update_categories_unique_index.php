<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Drop global unique on slug if it exists
            try {
                $table->dropUnique('categories_slug_unique');
            } catch (\Throwable $e) {
                // index may not exist; ignore
            }

            // Ensure tenant_id index exists (optional)
            if (!Schema::hasColumn('categories', 'tenant_id')) {
                // If tenant_id somehow missing, add it (safeguard)
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                $table->index('tenant_id');
            }

            // Add composite unique per-tenant slug
            $table->unique(['tenant_id', 'slug'], 'categories_tenant_slug_unique');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Drop composite unique
            try {
                $table->dropUnique('categories_tenant_slug_unique');
            } catch (\Throwable $e) {
                // ignore
            }

            // Restore global unique on slug
            try {
                $table->unique('slug', 'categories_slug_unique');
            } catch (\Throwable $e) {
                // ignore
            }
        });
    }
};







