<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        $electronics = Category::where('slug', 'electronics')->first();
        $clothing = Category::where('slug', 'clothing')->first();
        $homeGarden = Category::where('slug', 'home-garden')->first();
        $sports = Category::where('slug', 'sports-outdoors')->first();

        $products = [
            // Electronics
            [
                'category_id' => $electronics->id,
                'name' => 'Wireless Bluetooth Headphones',
                'slug' => 'wireless-bluetooth-headphones',
                'description' => 'High-quality wireless headphones with noise cancellation',
                'price' => 79.99,
                'sale_price' => 59.99,
                'sku' => 'ELEC-WBH-001',
                'stock' => 50,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'category_id' => $electronics->id,
                'name' => 'Smart Watch Series 5',
                'slug' => 'smart-watch-series-5',
                'description' => 'Advanced fitness tracker with heart rate monitor',
                'price' => 299.99,
                'sale_price' => null,
                'sku' => 'ELEC-SW-002',
                'stock' => 30,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'category_id' => $electronics->id,
                'name' => 'USB-C Fast Charger',
                'slug' => 'usb-c-fast-charger',
                'description' => '65W fast charging adapter',
                'price' => 29.99,
                'sale_price' => 24.99,
                'sku' => 'ELEC-CH-003',
                'stock' => 100,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'category_id' => $electronics->id,
                'name' => 'Wireless Mouse',
                'slug' => 'wireless-mouse',
                'description' => 'Ergonomic wireless mouse with precision tracking',
                'price' => 19.99,
                'sale_price' => null,
                'sku' => 'ELEC-MS-004',
                'stock' => 75,
                'is_active' => true,
                'is_featured' => false,
            ],

            // Clothing
            [
                'category_id' => $clothing->id,
                'name' => 'Classic Cotton T-Shirt',
                'slug' => 'classic-cotton-t-shirt',
                'description' => '100% cotton comfortable t-shirt',
                'price' => 19.99,
                'sale_price' => 14.99,
                'sku' => 'CLO-TS-001',
                'stock' => 200,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'category_id' => $clothing->id,
                'name' => 'Denim Jeans - Blue',
                'slug' => 'denim-jeans-blue',
                'description' => 'Classic fit denim jeans',
                'price' => 49.99,
                'sale_price' => null,
                'sku' => 'CLO-DJ-002',
                'stock' => 80,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'category_id' => $clothing->id,
                'name' => 'Winter Jacket',
                'slug' => 'winter-jacket',
                'description' => 'Warm winter jacket with hood',
                'price' => 89.99,
                'sale_price' => 69.99,
                'sku' => 'CLO-WJ-003',
                'stock' => 45,
                'is_active' => true,
                'is_featured' => true,
            ],

            // Home & Garden
            [
                'category_id' => $homeGarden->id,
                'name' => 'Ceramic Coffee Mug Set',
                'slug' => 'ceramic-coffee-mug-set',
                'description' => 'Set of 4 elegant ceramic mugs',
                'price' => 24.99,
                'sale_price' => null,
                'sku' => 'HOM-CM-001',
                'stock' => 60,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'category_id' => $homeGarden->id,
                'name' => 'LED Desk Lamp',
                'slug' => 'led-desk-lamp',
                'description' => 'Adjustable LED desk lamp with touch control',
                'price' => 34.99,
                'sale_price' => 29.99,
                'sku' => 'HOM-DL-002',
                'stock' => 40,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'category_id' => $homeGarden->id,
                'name' => 'Indoor Plant Pot Set',
                'slug' => 'indoor-plant-pot-set',
                'description' => 'Set of 3 decorative plant pots',
                'price' => 18.99,
                'sale_price' => null,
                'sku' => 'HOM-PP-003',
                'stock' => 90,
                'is_active' => true,
                'is_featured' => false,
            ],

            // Sports & Outdoors
            [
                'category_id' => $sports->id,
                'name' => 'Yoga Mat - Premium',
                'slug' => 'yoga-mat-premium',
                'description' => 'Non-slip premium yoga mat',
                'price' => 39.99,
                'sale_price' => 29.99,
                'sku' => 'SPO-YM-001',
                'stock' => 55,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'category_id' => $sports->id,
                'name' => 'Water Bottle - Insulated',
                'slug' => 'water-bottle-insulated',
                'description' => 'Stainless steel insulated water bottle',
                'price' => 24.99,
                'sale_price' => null,
                'sku' => 'SPO-WB-002',
                'stock' => 120,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'category_id' => $sports->id,
                'name' => 'Resistance Bands Set',
                'slug' => 'resistance-bands-set',
                'description' => 'Set of 5 resistance bands with different levels',
                'price' => 29.99,
                'sale_price' => 24.99,
                'sku' => 'SPO-RB-003',
                'stock' => 70,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'category_id' => $sports->id,
                'name' => 'Running Shoes - Pro',
                'slug' => 'running-shoes-pro',
                'description' => 'Professional running shoes with cushioning',
                'price' => 89.99,
                'sale_price' => 74.99,
                'sku' => 'SPO-RS-004',
                'stock' => 35,
                'is_active' => true,
                'is_featured' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
