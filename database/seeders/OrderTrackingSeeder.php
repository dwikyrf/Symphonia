<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderTracking;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderTrackingSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();

        foreach ($orders as $order) {
            $orderDate = Carbon::parse($order->created_at);
            $currentDate = $orderDate;

            // Daftar status timeline standar
            $timeline = [
                'Order diproses',
                'Sedang dikemas',
                'Dalam perjalanan',
                'Sampai di kota tujuan',
                'Order diterima'
            ];

            // Randomkan kemungkinan order selesai penuh, stuck, atau batal
            $randomScenario = rand(1, 100);

            if ($randomScenario <= 70) {
                // 🔵 70% order normal sampai selesai
                $statusesToCreate = $timeline;
            } elseif ($randomScenario <= 90) {
                // 🟡 20% order stuck di tengah (hanya sampai 'Dalam perjalanan')
                $statusesToCreate = array_slice($timeline, 0, rand(2, 3));
            } else {
                // 🔴 10% order batal setelah dikemas
                $statusesToCreate = [
                    'Order diproses',
                    'Sedang dikemas',
                    'Order dibatalkan'
                ];
            }

            foreach ($statusesToCreate as $status) {
                $currentDate = $currentDate->addDays(rand(1, 3));

                OrderTracking::create([
                    'order_id' => $order->id,
                    'status' => $status,
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                ]);
            }

            // Update status utama order berdasarkan tracking terakhir
            $finalStatus = end($statusesToCreate);
            if (strtolower($finalStatus) === 'order diterima') {
                $order->update(['status' => 'completed']);
            } elseif (strtolower($finalStatus) === 'order dibatalkan') {
                $order->update(['status' => 'cancelled']);
            } else {
                $order->update(['status' => 'processing']);
            }
        }

        $this->command->info('✅ Seeder Tracking dinamis (normal, stuck, batal) selesai!');
    }
}
