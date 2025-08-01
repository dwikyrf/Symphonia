<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'transaction_id' => Str::uuid(),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'order_id' => Order::inRandomOrder()->first()?->id ?? Order::factory(),
            'order_date' => now(),
            'total_payment' => $this->faker->numberBetween(50000, 500000),
            'payment_method' => 'bank_transfer',
            'payment_stage' => $this->faker->randomElement(['dp', 'full']),
            'transfer_proof_dp' => null,
            'is_verified_dp' => false,
            'transfer_proof_full' => null,
            'is_verified_full' => false,
            'status' => 'pending',
        ];
    }
}

