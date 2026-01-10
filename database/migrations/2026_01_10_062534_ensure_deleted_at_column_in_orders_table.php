<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to ensure the column is added regardless of Schema checks
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            // Check if column exists using raw SQL
            $columnExists = DB::select("
                SELECT COUNT(*) as count 
                FROM information_schema.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'orders' 
                AND COLUMN_NAME = 'deleted_at'
            ");
            
            if ($columnExists[0]->count == 0) {
                DB::statement('ALTER TABLE `orders` ADD COLUMN `deleted_at` TIMESTAMP NULL DEFAULT NULL');
            }
        } else {
            // For other databases, use Schema builder
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            $columnExists = DB::select("
                SELECT COUNT(*) as count 
                FROM information_schema.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'orders' 
                AND COLUMN_NAME = 'deleted_at'
            ");
            
            if ($columnExists[0]->count > 0) {
                DB::statement('ALTER TABLE `orders` DROP COLUMN `deleted_at`');
            }
        } else {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'deleted_at')) {
                    $table->dropSoftDeletes();
                }
            });
        }
    }
};
