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
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('adjustment_number')->unique(); // ADJ-000001
            $table->enum('type', ['increase', 'decrease']); // increase or decrease
            $table->integer('quantity'); // positive number
            $table->integer('old_stock'); // stock before adjustment
            $table->integer('new_stock'); // stock after adjustment
            $table->enum('reason', [
                'damaged',
                'lost',
                'found',
                'expired',
                'returned',
                'theft',
                'sample',
                'manufacturing_defect',
                'stock_count_correction',
                'other'
            ]);
            $table->text('notes')->nullable();
            $table->string('reference_number')->nullable(); // For linking to other documents
            $table->foreignId('adjusted_by')->constrained('users')->onDelete('cascade'); // Who made the adjustment
            $table->timestamp('adjustment_date');
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('tenant_id');
            $table->index('product_id');
            $table->index('adjustment_number');
            $table->index('type');
            $table->index('reason');
            $table->index('adjustment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};






