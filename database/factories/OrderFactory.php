<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'total_price' => 0, // akan dihitung ulang dari OrderDetail
            'dp_paid' => 0,
            'remaining_balance' => 0,
            'total_quantity' => 0,
            'payment_status' => 'pending',
            'status' => 'pending',
        ];
    }
}
