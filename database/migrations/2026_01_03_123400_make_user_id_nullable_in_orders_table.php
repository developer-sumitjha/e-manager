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
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            // For MySQL, find and drop the foreign key constraint, modify column, then re-add foreign key
            $constraints = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'orders' 
                AND COLUMN_NAME = 'user_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            if (!empty($constraints)) {
                $constraintName = $constraints[0]->CONSTRAINT_NAME;
                DB::statement("ALTER TABLE `orders` DROP FOREIGN KEY `{$constraintName}`");
            }
            
            // Modify column to be nullable
            DB::statement('ALTER TABLE `orders` MODIFY COLUMN `user_id` BIGINT UNSIGNED NULL');
            
            // Re-add foreign key constraint
            if (!empty($constraints)) {
                DB::statement('ALTER TABLE `orders` ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE');
            }
        } else {
            // For other databases (SQLite, PostgreSQL, etc.), use Schema builder
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: This will fail if there are any NULL user_id values
        // You may need to update NULL values to a default user before running this
        DB::statement('ALTER TABLE `orders` MODIFY COLUMN `user_id` BIGINT UNSIGNED NOT NULL');
    }
};
