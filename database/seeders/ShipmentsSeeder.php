<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shipment;
use App\Models\Order;
use Illuminate\Support\Str;

class ShipmentsSeeder extends Seeder
{
    public function run(): void
    {
        // Get some confirmed orders to create shipments for
        $confirmedOrders = Order::where('status', 'confirmed')->take(5)->get();
        
        if ($confirmedOrders->count() > 0) {
            foreach ($confirmedOrders as $order) {
                Shipment::create([
                    'order_id' => $order->id,
                    'delivery_method' => rand(0, 1) ? 'manual' : 'logistics',
                    'tracking_number' => 'SHIP-' . strtoupper(Str::random(8)),
                    'status' => ['pending', 'shipped', 'in_transit', 'delivered'][rand(0, 3)],
                    'estimated_delivery_date' => now()->addDays(rand(1, 7)),
                    'actual_delivery_date' => rand(0, 1) ? now()->subDays(rand(1, 3)) : null,
                    'notes' => 'Sample shipment created by seeder',
                    'delivery_agent_name' => rand(0, 1) ? 'John Doe' : null,
                    'delivery_agent_phone' => rand(0, 1) ? '9851234567' : null,
                    'logistics_company' => rand(0, 1) ? 'FastTrack Logistics' : null,
                    'created_by' => 1, // Assuming admin user ID is 1
                    'created_at' => now()->subDays(rand(1, 10)),
                    'updated_at' => now()->subDays(rand(1, 10)),
                ]);
                
                // Update order status based on shipment status
                $shipmentStatus = ['pending', 'shipped', 'in_transit', 'delivered'][rand(0, 3)];
                if ($shipmentStatus === 'delivered') {
                    $order->update(['status' => 'completed']);
                } elseif ($shipmentStatus === 'shipped') {
                    $order->update(['status' => 'shipped']);
                } else {
                    $order->update(['status' => 'processing']);
                }
            }
        }

        $this->command->info('Sample shipments created successfully!');
    }
}