<?php

namespace Database\Factories;

use App\Models\Shipping;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ShippingFactory extends Factory
{
    // Hubungkan ke model Shipping
    protected $model = Shipping::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['pending', 'dikirim', 'diterima']);
        $shippedAt = null;
        $deliveredAt = null;

        if ($status === 'dikirim') {
            $shippedAt = Carbon::now()->subDays(rand(1, 3));
        } elseif ($status === 'diterima') {
            $shippedAt = Carbon::now()->subDays(rand(3, 5));
            $deliveredAt = Carbon::now()->subDays(rand(0, 2));
        }

        return [
            'courier_name'     => $this->faker->randomElement(['J&T', 'JNE', 'NINJA', 'Sicepat']),
            'service_code'     => $this->faker->randomElement(['REG', 'YES', 'Standard']),
            'shipping_cost'    => $this->faker->numberBetween(10000, 50000),
            'tracking_number'  => $this->faker->optional()->bothify('TRK-#######'),
            'estimated_days'   => $this->faker->randomElement(['2-3 hari', '3-5 hari', '-']),
            'status'           => $status,
            'shipped_at'       => $shippedAt,
            'delivered_at'     => $deliveredAt,
        ];
    }
}
