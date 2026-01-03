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
            $table->integer('stock_quantity')->default(0)->after('price');
            $table->integer('low_stock_threshold')->default(5)->after('stock_quantity');
            $table->boolean('track_inventory')->default(true)->after('low_stock_threshold');
            $table->boolean('allow_backorders')->default(false)->after('track_inventory');
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'on_backorder'])->default('in_stock')->after('allow_backorders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['stock_quantity', 'low_stock_threshold', 'track_inventory', 'allow_backorders', 'stock_status']);
        });
    }
};
