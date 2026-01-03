<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Normalize any legacy value 'user' to 'customer' before tightening enum
        DB::table('users')->where('role', 'user')->update(['role' => 'customer']);

        // For MySQL, change `role` column to ENUM with new allowed values
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','employee','delivery_boy','customer') NOT NULL DEFAULT 'customer'");
        }
        // For SQLite and other databases, we'll handle this in the model
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For MySQL, revert to previous enum ('admin','user') to be safe
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','user') NOT NULL DEFAULT 'user'");
        }
    }
};




