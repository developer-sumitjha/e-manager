<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ManualDelivery;
use App\Models\Order;
use App\Models\DeliveryBoy;
use App\Models\User;
use Carbon\Carbon;

class ManualDeliveriesSeeder extends Seeder
{
    public function run(): void
    {
        // Get some confirmed orders and delivery boys
        $orders = Order::where('status', 'confirmed')->take(5)->get();
        $deliveryBoys = DeliveryBoy::where('status', 'active')->get();
        
        if ($orders->isEmpty() || $deliveryBoys->isEmpty()) {
            $this->command->info('No confirmed orders or delivery boys found. Skipping manual deliveries seeding.');
            return;
        }

        $adminUser = User::where('role', 'admin')->first();

        foreach ($orders as $index => $order) {
            $deliveryBoy = $deliveryBoys->random();
            
            ManualDelivery::create([
                'order_id' => $order->id,
                'delivery_boy_id' => $deliveryBoy->id,
                'assigned_by' => $adminUser->id,
                'status' => 'assigned',
                'assigned_at' => now()->subHours($index),
                'cod_amount' => $order->payment_method === 'cod' ? $order->total : 0,
                'delivery_notes' => 'Please handle with care',
            ]);
            
            // Update order to processing
            $order->update(['status' => 'processing']);
        }
        
        // Update delivery boy stats
        foreach ($deliveryBoys as $boy) {
            $boy->updateStats();
        }
        
        $this->command->info('Manual deliveries seeded successfully!');
    }
}
