<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DeliveryBoy;
use Illuminate\Support\Facades\Hash;

class DeliveryBoysSeeder extends Seeder
{
    public function run(): void
    {
        $deliveryBoys = [
            [
                'delivery_boy_id' => 'DB001',
                'name' => 'Ahmed Hassan',
                'phone' => '+923001234567',
                'email' => 'ahmed.hassan@example.com',
                'password' => Hash::make('password123'),
                'cnic' => '12345-1234567-1',
                'license_number' => 'LIC-001-2024',
                'address' => 'House 123, Street 5, F-7, Islamabad',
                'zone' => 'north',
                'vehicle_type' => 'motorcycle',
                'vehicle_number' => 'ABC-123',
                'status' => 'active',
                'rating' => 4.5,
                'total_deliveries' => 156,
                'successful_deliveries' => 148,
                'cancelled_deliveries' => 8,
            ],
            [
                'delivery_boy_id' => 'DB002',
                'name' => 'Muhammad Ali',
                'phone' => '+923012345678',
                'email' => 'muhammad.ali@example.com',
                'password' => Hash::make('password123'),
                'cnic' => '12345-1234567-2',
                'license_number' => 'LIC-002-2024',
                'address' => 'House 45, Street 10, G-9, Islamabad',
                'zone' => 'south',
                'vehicle_type' => 'motorcycle',
                'vehicle_number' => 'XYZ-456',
                'status' => 'active',
                'rating' => 4.8,
                'total_deliveries' => 203,
                'successful_deliveries' => 198,
                'cancelled_deliveries' => 5,
            ],
            [
                'delivery_boy_id' => 'DB003',
                'name' => 'Usman Khan',
                'phone' => '+923023456789',
                'email' => 'usman.khan@example.com',
                'password' => Hash::make('password123'),
                'cnic' => '12345-1234567-3',
                'license_number' => 'LIC-003-2024',
                'address' => 'House 78, Street 3, E-11, Islamabad',
                'zone' => 'east',
                'vehicle_type' => 'bicycle',
                'vehicle_number' => 'N/A',
                'status' => 'active',
                'rating' => 4.2,
                'total_deliveries' => 89,
                'successful_deliveries' => 84,
                'cancelled_deliveries' => 5,
            ],
            [
                'delivery_boy_id' => 'DB004',
                'name' => 'Bilal Ahmed',
                'phone' => '+923034567890',
                'email' => 'bilal.ahmed@example.com',
                'password' => Hash::make('password123'),
                'cnic' => '12345-1234567-4',
                'license_number' => 'LIC-004-2024',
                'address' => 'House 90, Street 12, I-8, Islamabad',
                'zone' => 'west',
                'vehicle_type' => 'motorcycle',
                'vehicle_number' => 'DEF-789',
                'status' => 'on_duty',
                'rating' => 4.6,
                'total_deliveries' => 175,
                'successful_deliveries' => 168,
                'cancelled_deliveries' => 7,
            ],
            [
                'delivery_boy_id' => 'DB005',
                'name' => 'Hamza Malik',
                'phone' => '+923045678901',
                'email' => 'hamza.malik@example.com',
                'password' => Hash::make('password123'),
                'cnic' => '12345-1234567-5',
                'license_number' => 'LIC-005-2024',
                'address' => 'House 12, Street 7, Blue Area, Islamabad',
                'zone' => 'central',
                'vehicle_type' => 'car',
                'vehicle_number' => 'GHI-101',
                'status' => 'active',
                'rating' => 4.9,
                'total_deliveries' => 234,
                'successful_deliveries' => 230,
                'cancelled_deliveries' => 4,
            ],
        ];

        foreach ($deliveryBoys as $deliveryBoy) {
            DeliveryBoy::create($deliveryBoy);
        }

        $this->command->info('Delivery boys seeded successfully!');
        $this->command->info('Login credentials: Phone number from above, Password: password123');
    }
}
