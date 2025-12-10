<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'payment_id' => fake()->uuid(),
            'order_id' => Order::factory(),
            'status' => fake()->randomElement(['pending', 'successful', 'failed']),
            'payment_method' => fake()->randomElement(['credit_card', 'paypal']),
            'gateway' => fake()->randomElement(['credit_card', 'paypal']),
            'metadata' => [],
        ];
    }
}
