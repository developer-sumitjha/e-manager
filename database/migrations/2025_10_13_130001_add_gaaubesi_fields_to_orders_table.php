<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Check and add columns only if they don't exist
            if (!Schema::hasColumn('orders', 'receiver_name')) {
                $table->string('receiver_name')->nullable()->after('shipping_address');
            }
            if (!Schema::hasColumn('orders', 'receiver_phone')) {
                $table->string('receiver_phone')->nullable()->after('receiver_name');
            }
            if (!Schema::hasColumn('orders', 'receiver_city')) {
                $table->string('receiver_city')->nullable()->after('receiver_phone');
            }
            if (!Schema::hasColumn('orders', 'receiver_area')) {
                $table->string('receiver_area')->nullable()->after('receiver_city');
            }
            if (!Schema::hasColumn('orders', 'receiver_full_address')) {
                $table->text('receiver_full_address')->nullable()->after('receiver_area');
            }
            if (!Schema::hasColumn('orders', 'delivery_branch')) {
                $table->string('delivery_branch')->nullable()->after('receiver_full_address');
            }
            if (!Schema::hasColumn('orders', 'package_access')) {
                $table->string('package_access')->nullable()->after('delivery_branch');
            }
            if (!Schema::hasColumn('orders', 'delivery_type')) {
                $table->string('delivery_type')->nullable()->after('package_access');
            }
            if (!Schema::hasColumn('orders', 'package_type')) {
                $table->string('package_type')->nullable()->after('delivery_type');
            }
            if (!Schema::hasColumn('orders', 'sender_name')) {
                $table->string('sender_name')->nullable()->after('package_type');
            }
            if (!Schema::hasColumn('orders', 'sender_phone')) {
                $table->string('sender_phone')->nullable()->after('sender_name');
            }
            if (!Schema::hasColumn('orders', 'delivery_instructions')) {
                $table->text('delivery_instructions')->nullable()->after('notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = [
                'receiver_name',
                'receiver_phone',
                'receiver_city',
                'receiver_area',
                'receiver_full_address',
                'delivery_branch',
                'package_access',
                'delivery_type',
                'package_type',
                'sender_name',
                'sender_phone',
                'delivery_instructions',
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};







