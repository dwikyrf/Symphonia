<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada user dulu
        if (User::count() == 0) {
            $this->call(UserSeeder::class);
        }

        // Buat 30 Orders
        $orders = Order::factory()->count(30)->create();

        // Untuk setiap order, buatkan transaksi
        foreach ($orders as $order) {
            Transaction::factory()->create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'order_date' => $order->created_at,
                'total_payment' => $order->total_price + 99000, // tambah ongkir contoh
            ]);
        }
    }
}
