<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();

        if ($users->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada user ditemukan.');
            return;
        }

        foreach ($users as $user) {
            // Pastikan setiap user punya alamat
            if ($user->addresses()->count() == 0) {
                Address::create([
                    'user_id'        => $user->id,
                    'recipient_name' => $user->name,
                    'phone'          => '08' . rand(1000000000, 9999999999),
                    'address'        => fake()->address(),
                    'city'           => fake()->city(),
                    'postal_code'    => fake()->postcode(),
                    'province'       => fake()->state(),
                    'is_default'     => true,
                ]);
            }

            $addresses = $user->addresses()->pluck('id')->toArray();

            // Buat beberapa order
            foreach (range(1, rand(3, 6)) as $i) {
                $randomMonth = rand(1, 5);
                $randomDay = rand(1, 28);
                $createdAt = Carbon::create(2025, $randomMonth, $randomDay)->startOfDay();

                $order = Order::create([
                    'order_number'      => 'ORD-' . strtoupper(Str::random(10)),
                    'user_id'           => $user->id,
                    'address_id'        => fake()->randomElement($addresses), // pilih salah satu alamat user
                    'role'              => $user->role,
                    'total_price'       => 0,
                    'dp_paid'           => 0,
                    'remaining_balance' => 0,
                    'total_quantity'    => 0,
                    'payment_status'    => collect(['pending','pending', 'partial', 'paid'])->random(),
                    'status'            => collect(['pending', 'processing', 'completed', 'cancelled'])->random(),
                    'corporate_proof'   => null,
                    'created_at'        => $createdAt,
                    'updated_at'        => $createdAt,
                ]);

                $products = Product::inRandomOrder()->limit(rand(1, 3))->get();
                $totalPrice = 0;
                $totalQty = 0;

                foreach ($products as $product) {
                    $quantity = rand(1, 5);
                    OrderDetail::create([
                        'order_id'    => $order->id,
                        'product_id'  => $product->id,
                        'size'        => collect(['S', 'M', 'L', 'XL', 'XXL'])->random(),
                        'quantity'    => $quantity,
                        'price'       => $product->price,
                        'design'      => null,
                        'logo'        => null,
                        'description' => fake()->sentence(),
                        'created_at'  => $createdAt,
                        'updated_at'  => $createdAt,
                    ]);

                    $totalQty += $quantity;
                    $totalPrice += $product->price * $quantity;
                }

                $order->update([
                    'price'             => $totalPrice,
                    'total_price'       => $totalPrice, // nanti ini ditambah ongkir
                    'total_quantity'    => $totalQty,
                ]);


                // Simulasi pembayaran
                if (rand(0, 1)) {
                    $dp = $totalPrice * 0.40;
                    $order->update([
                        'dp_paid'           => $dp,
                        'remaining_balance' => $totalPrice - $dp,
                        'payment_status'    => 'partial',
                        'status'            => 'processing',
                    ]);
                } elseif (rand(0, 1)) {
                    $order->update([
                        'dp_paid'           => $totalPrice,
                        'remaining_balance' => 0,
                        'payment_status'    => 'paid',
                        'status'            => 'completed',
                    ]);
                }

                // Buat transaksi
                Transaction::create([
                    'user_id'           => $user->id,
                    'order_id'          => $order->id,
                    'total_payment'     => $order->total_price,
                    'payment_stage'     => 'full',
                    'is_verified_full'  => true,
                    'created_at'        => $createdAt,
                    'updated_at'        => $createdAt,
                ]);
            }
        }

        $this->command->info('✅ Order, Address, dan Transaksi berhasil di-seed dengan benar.');
    }
}
