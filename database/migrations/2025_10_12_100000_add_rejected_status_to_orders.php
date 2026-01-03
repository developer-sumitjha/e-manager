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
        // For MySQL, update the status column to include 'rejected'
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'confirmed', 'rejected', 'processing', 'shipped', 'completed', 'cancelled') DEFAULT 'pending'");
        }
        // For SQLite and other databases, we'll handle this in the model
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For MySQL, revert back to previous enum
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'confirmed', 'processing', 'shipped', 'completed', 'cancelled') DEFAULT 'pending'");
        }
    }
};






