<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;

class OrdersSeeder extends Seeder
{
    public function run(): void
    {
        // Get users (create a regular user if doesn't exist)
        $regularUser = User::where('role', 'user')->first();
        
        if (!$regularUser) {
            $regularUser = User::create([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]);
        }

        // Create additional test user
        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // Get some products
        $products = Product::take(5)->get();

        // Order 1 - Completed
        $order1 = Order::create([
            'user_id' => $regularUser->id,
            'order_number' => 'ORD-' . date('Ymd') . '-001',
            'subtotal' => 139.98,
            'tax' => 14.00,
            'shipping' => 10.00,
            'total' => 163.98,
            'status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'Credit Card',
            'shipping_address' => '123 Main St, New York, NY 10001',
            'notes' => 'Please deliver between 9 AM - 5 PM',
            'created_at' => now()->subDays(5),
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $products[0]->id,
            'quantity' => 1,
            'price' => $products[0]->sale_price ?? $products[0]->price,
            'total' => $products[0]->sale_price ?? $products[0]->price,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $products[1]->id,
            'quantity' => 2,
            'price' => $products[1]->price,
            'total' => $products[1]->price * 2,
        ]);

        // Order 2 - Processing
        $order2 = Order::create([
            'user_id' => $user2->id,
            'order_number' => 'ORD-' . date('Ymd') . '-002',
            'subtotal' => 89.99,
            'tax' => 9.00,
            'shipping' => 5.00,
            'total' => 103.99,
            'status' => 'processing',
            'payment_status' => 'paid',
            'payment_method' => 'PayPal',
            'shipping_address' => '456 Oak Avenue, Los Angeles, CA 90001',
            'created_at' => now()->subDays(2),
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $products[2]->id,
            'quantity' => 1,
            'price' => $products[2]->sale_price ?? $products[2]->price,
            'total' => $products[2]->sale_price ?? $products[2]->price,
        ]);

        // Order 3 - Pending
        $order3 = Order::create([
            'user_id' => $regularUser->id,
            'order_number' => 'ORD-' . date('Ymd') . '-003',
            'subtotal' => 299.99,
            'tax' => 30.00,
            'shipping' => 15.00,
            'total' => 344.99,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'Bank Transfer',
            'shipping_address' => '123 Main St, New York, NY 10001',
            'created_at' => now()->subHours(5),
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $products[3]->id,
            'quantity' => 1,
            'price' => $products[3]->price,
            'total' => $products[3]->price,
        ]);

        // Order 4 - Completed (older)
        $order4 = Order::create([
            'user_id' => $user2->id,
            'order_number' => 'ORD-' . date('Ymd', strtotime('-10 days')) . '-004',
            'subtotal' => 49.99,
            'tax' => 5.00,
            'shipping' => 5.00,
            'total' => 59.99,
            'status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'Credit Card',
            'shipping_address' => '456 Oak Avenue, Los Angeles, CA 90001',
            'created_at' => now()->subDays(10),
        ]);

        OrderItem::create([
            'order_id' => $order4->id,
            'product_id' => $products[4]->id,
            'quantity' => 3,
            'price' => $products[4]->sale_price ?? $products[4]->price,
            'total' => ($products[4]->sale_price ?? $products[4]->price) * 3,
        ]);

        // Order 5 - Cancelled
        $order5 = Order::create([
            'user_id' => $regularUser->id,
            'order_number' => 'ORD-' . date('Ymd', strtotime('-3 days')) . '-005',
            'subtotal' => 124.98,
            'tax' => 12.50,
            'shipping' => 10.00,
            'total' => 147.48,
            'status' => 'cancelled',
            'payment_status' => 'refunded',
            'payment_method' => 'Credit Card',
            'shipping_address' => '123 Main St, New York, NY 10001',
            'notes' => 'Customer requested cancellation',
            'created_at' => now()->subDays(3),
        ]);

        OrderItem::create([
            'order_id' => $order5->id,
            'product_id' => $products[0]->id,
            'quantity' => 2,
            'price' => $products[0]->sale_price ?? $products[0]->price,
            'total' => ($products[0]->sale_price ?? $products[0]->price) * 2,
        ]);
    }
}
