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
        Schema::table('orders', function (Blueprint $table) {
            // Add shipping_cost column if it doesn't exist
            if (!Schema::hasColumn('orders', 'shipping_cost')) {
                $table->decimal('shipping_cost', 10, 2)->default(0)->nullable();
            }
            // Add tax_amount column if it doesn't exist
            if (!Schema::hasColumn('orders', 'tax_amount')) {
                $table->decimal('tax_amount', 10, 2)->default(0)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
        if (Schema::hasColumn('orders', 'shipping_cost')) {
            $table->dropColumn('shipping_cost');
        }
        if (Schema::hasColumn('orders', 'tax_amount')) {
            $table->dropColumn('tax_amount');
        }
        });
    }
};
