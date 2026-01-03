<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'sale_price' => $this->faker->optional(0.3)->randomFloat(2, 5, 500),
            'sku' => $this->faker->unique()->bothify('SKU-####'),
            'stock' => $this->faker->numberBetween(0, 100),
            'image' => $this->faker->optional()->imageUrl(400, 300, 'products'),
            'images' => json_encode([
                $this->faker->imageUrl(400, 300, 'products'),
                $this->faker->imageUrl(400, 300, 'products'),
            ]),
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
            'is_featured' => $this->faker->boolean(20), // 20% chance of being featured
            'category_id' => Category::factory(),
            'tenant_id' => Tenant::factory(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the product is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    /**
     * Indicate that the product is low stock.
     */
    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => $this->faker->numberBetween(1, 5),
        ]);
    }

    /**
     * Indicate that the product is high value.
     */
    public function highValue(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => $this->faker->randomFloat(2, 500, 2000),
        ]);
    }
}
