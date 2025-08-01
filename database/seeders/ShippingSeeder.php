<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Shipping;
use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();

        if ($orders->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada order ditemukan. Jalankan OrderSeeder terlebih dahulu.');
            return;
        }

        foreach ($orders as $order) {
            $status = 'pending';
            $shippedAt = null;
            $deliveredAt = null;

            if ($order->payment_status === 'paid') {
                $status = fake()->randomElement(['dikirim', 'diterima']);

                if ($status === 'dikirim') {
                    $shippedAt = now()->subDays(rand(1, 3));
                } elseif ($status === 'diterima') {
                    $shippedAt = now()->subDays(rand(3, 5));
                    $deliveredAt = now()->subDays(rand(0, 2));
                }
            }

            // Buat shipping
            $shipping = Shipping::create([
                'courier_name'     => fake()->randomElement(['J&T', 'JNE', 'Sicepat', 'Anteraja']),
                'service_code'     => fake()->randomElement(['REG', 'YES', 'EZ']),
                'shipping_cost'    => $shippingCost = fake()->numberBetween(10000, 50000),
                'tracking_number'  => strtoupper(fake()->bothify('TRK-########')),
                'estimated_days'   => fake()->randomElement(['2-3 hari', '3-5 hari', '1-2 hari']),
                'status'           => $status,
                'shipped_at'       => $shippedAt,
                'delivered_at'     => $deliveredAt,
            ]);

            // Update order
            $order->shipping_id = $shipping->id;
            $order->total_price = $order->price + $shippingCost;
            $order->save();
        }

        $this->command->info('✅ ShippingSeeder berhasil dijalankan!');
    }
}
