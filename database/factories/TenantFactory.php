<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => 'TEN' . $this->faker->unique()->numberBetween(1000, 9999),
            'business_name' => $this->faker->company(),
            'subdomain' => $this->faker->unique()->slug(2),
            'domain' => $this->faker->optional()->domainName(),
            'business_type' => $this->faker->randomElement(['retail', 'wholesale', 'restaurant', 'ecommerce']),
            'business_email' => $this->faker->unique()->companyEmail(),
            'business_phone' => $this->faker->phoneNumber(),
            'business_address' => $this->faker->address(),
            'pan_number' => $this->faker->optional()->bothify('PAN#######'),
            'registration_number' => $this->faker->optional()->bothify('REG#######'),
            'owner_name' => $this->faker->name(),
            'owner_email' => $this->faker->unique()->safeEmail(),
            'owner_phone' => $this->faker->phoneNumber(),
            'password' => bcrypt('password'),
            'status' => $this->faker->randomElement(['pending', 'active', 'suspended', 'cancelled', 'trial']),
            'is_verified' => $this->faker->boolean(70),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the tenant is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'is_verified' => true,
        ]);
    }

    /**
     * Indicate that the tenant is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'suspended',
            'is_verified' => false,
        ]);
    }
}
