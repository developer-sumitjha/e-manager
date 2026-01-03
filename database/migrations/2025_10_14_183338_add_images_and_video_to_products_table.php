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
        Schema::table('products', function (Blueprint $table) {
            // Add video field (images already exists)
            if (!Schema::hasColumn('products', 'video')) {
                $table->string('video')->nullable()->after('images');
            }
            
            // Add primary image index (0 = first image in array)
            if (!Schema::hasColumn('products', 'primary_image_index')) {
                $table->integer('primary_image_index')->default(0)->after('video');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'video')) {
                $table->dropColumn('video');
            }
            if (Schema::hasColumn('products', 'primary_image_index')) {
                $table->dropColumn('primary_image_index');
            }
        });
    }
};
