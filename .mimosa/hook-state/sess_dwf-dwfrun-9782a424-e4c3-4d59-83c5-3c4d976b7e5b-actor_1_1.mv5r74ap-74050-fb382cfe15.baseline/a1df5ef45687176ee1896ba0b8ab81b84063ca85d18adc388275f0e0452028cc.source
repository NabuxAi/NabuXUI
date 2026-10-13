<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => 'ORD-'.fake()->unique()->numberBetween(1001, 99999),
            'customer_name' => fake()->name(),
            'product_id' => Product::factory(),
            'status' => fake()->randomElement(Order::STATUSES),
            'total' => fake()->randomFloat(2, 19, 1200),
            'ordered_at' => fake()->dateTimeBetween('-45 days'),
        ];
    }
}
