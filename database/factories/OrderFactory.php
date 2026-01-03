<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-' . $this->faker->unique()->numberBetween(1000, 9999),
            'subtotal' => $this->faker->randomFloat(2, 50, 500),
            'tax' => $this->faker->randomFloat(2, 5, 50),
            'shipping' => $this->faker->randomFloat(2, 0, 30),
            'total' => $this->faker->randomFloat(2, 100, 1000),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'processing', 'shipped', 'completed', 'cancelled']),
            'payment_status' => $this->faker->randomElement(['unpaid', 'paid', 'refunded']),
            'payment_method' => $this->faker->randomElement(['cod', 'online', 'bank_transfer', 'khalti', 'esewa']),
            'shipping_address' => $this->faker->address,
            'notes' => $this->faker->optional()->sentence,
            'delivery_instructions' => $this->faker->optional()->sentence,
            'is_manual' => $this->faker->boolean(20), // 20% chance of being manual
            'created_by' => User::factory(),
            'receiver_name' => $this->faker->name,
            'receiver_phone' => $this->faker->phoneNumber,
            'receiver_city' => $this->faker->city,
            'receiver_area' => $this->faker->streetName,
            'receiver_full_address' => $this->faker->address,
            'delivery_branch' => $this->faker->randomElement(['HEAD OFFICE', 'POKHARA', 'CHITWAN']),
            'package_access' => $this->faker->randomElement(["Can't Open", 'Can Open']),
            'delivery_type' => $this->faker->randomElement(['Pickup', 'Drop Off']),
            'package_type' => $this->faker->optional()->word,
            'sender_name' => $this->faker->company,
            'sender_phone' => $this->faker->phoneNumber,
        ];
    }

    /**
     * Indicate that the order is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);
    }

    /**
     * Indicate that the order is confirmed.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
            'payment_status' => 'paid',
        ]);
    }

    /**
     * Indicate that the order is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'payment_status' => 'paid',
        ]);
    }

    /**
     * Indicate that the order is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'payment_status' => 'unpaid',
        ]);
    }

    /**
     * Indicate that the order is manual.
     */
    public function manual(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_manual' => true,
            'status' => 'pending',
        ]);
    }
}


