<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Str;

class PendingOrdersSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first user (or create one if none exists)
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Demo User',
                'email' => 'demo@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]);
        }

        // Create sample pending manual orders
        $orders = [
            [
                'customer_name' => 'Madan Thapa',
                'customer_phone' => '9851161543',
                'shipping_address' => 'TINKUNE',
                'payment_method' => 'cash_on_delivery',
                'total_amount' => 800,
                'order_number' => 'PND-0029',
            ],
            [
                'customer_name' => 'Gobind Prasad Awasthi',
                'customer_phone' => '9848793761',
                'shipping_address' => 'BUTWAL',
                'payment_method' => 'cash_on_delivery',
                'total_amount' => 800,
                'order_number' => 'PND-0019',
            ],
            [
                'customer_name' => 'Man Bahadur Basnet',
                'customer_phone' => '9851021228',
                'shipping_address' => 'NAYA BUSPARK',
                'payment_method' => 'cash_on_delivery',
                'total_amount' => 799,
                'order_number' => 'PND-0020',
            ],
            [
                'customer_name' => 'Sita Devi Sharma',
                'customer_phone' => '9841234567',
                'shipping_address' => 'KATHMANDU',
                'payment_method' => 'cash_on_delivery',
                'total_amount' => 1200,
                'order_number' => 'PND-0021',
            ],
            [
                'customer_name' => 'Ram Prasad Koirala',
                'customer_phone' => '9852345678',
                'shipping_address' => 'POKHARA',
                'payment_method' => 'paid',
                'total_amount' => 650,
                'order_number' => 'PND-0022',
            ],
            [
                'customer_name' => 'Gita Kumari Rai',
                'customer_phone' => '9843456789',
                'shipping_address' => 'CHITWAN',
                'payment_method' => 'cash_on_delivery',
                'total_amount' => 950,
                'order_number' => 'PND-0023',
            ],
            [
                'customer_name' => 'Hari Bahadur Gurung',
                'customer_phone' => '9854567890',
                'shipping_address' => 'LALITPUR',
                'payment_method' => 'cash_on_delivery',
                'total_amount' => 750,
                'order_number' => 'PND-0024',
            ],
        ];

        foreach ($orders as $orderData) {
            Order::create([
                'user_id' => $user->id,
                'order_number' => $orderData['order_number'],
                'subtotal' => $orderData['total_amount'],
                'tax' => 0,
                'shipping' => 0,
                'total' => $orderData['total_amount'],
                'status' => 'pending',
                'payment_status' => $orderData['payment_method'] === 'paid' ? 'paid' : 'unpaid',
                'payment_method' => $orderData['payment_method'],
                'shipping_address' => $orderData['shipping_address'],
                'notes' => 'Manual order created by admin',
                'is_manual' => true,
                'created_by' => 1, // Assuming admin user ID is 1
                'created_at' => now()->subDays(rand(1, 7)),
                'updated_at' => now()->subDays(rand(1, 7)),
            ]);
        }

        $this->command->info('Sample pending orders created successfully!');
    }
}