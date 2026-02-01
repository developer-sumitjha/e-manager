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
        // List of tables that need tenant_id
        $tables = [
            'products',
            'categories',
            'orders',
            'pending_orders',
            'inventory',
            'delivery_boys',
            'manual_deliveries',
            'accounts',
            'transactions',
            'invoices',
            'gaaubesi_shipments',
            'pathao_shipments',
            'shipments',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'tenant_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->onDelete('cascade');
                    $table->index('tenant_id');
                });
                
                echo "✅ Added tenant_id to {$table}\n";
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'products',
            'categories',
            'orders',
            'pending_orders',
            'inventory',
            'delivery_boys',
            'manual_deliveries',
            'accounts',
            'transactions',
            'invoices',
            'gaaubesi_shipments',
            'pathao_shipments',
            'shipments',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'tenant_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropForeign(['tenant_id']);
                    $table->dropColumn('tenant_id');
                });
            }
        }
    }
};
