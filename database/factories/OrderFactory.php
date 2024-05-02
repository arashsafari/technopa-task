<?php

namespace Database\Factories;

use App\Enums\Order\OrderStatusEnums;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
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
            'status' => fake()->randomElement(OrderStatusEnums::values()),
            'amount' => fake()->numberBetween(1, 999999),
            'user_id' => User::factory()->create(),
        ];
    }
}
