<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed admin user
        $this->call(AdminUserSeeder::class);
        
        // Seed categories
        $this->call(CategoriesSeeder::class);
        
        // Seed products
        $this->call(ProductsSeeder::class);
        
        // Seed orders
        $this->call(OrdersSeeder::class);
        
        // Seed pending orders
        $this->call(PendingOrdersSeeder::class);
        
        // Seed processed orders
        $this->call(ProcessedOrdersSeeder::class);
        
        // Seed shipments
        $this->call(ShipmentsSeeder::class);
        
        // Seed delivery boys
        $this->call(DeliveryBoysSeeder::class);
        
        // Seed manual deliveries
        $this->call(ManualDeliveriesSeeder::class);
        
        // Seed accounts
        $this->call(AccountsSeeder::class);
        
        // Seed transactions
        $this->call(TransactionsSeeder::class);
    }
}
