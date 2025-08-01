<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\Status;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderDetail;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('⚡️ Memulai proses seeding database...');

        // Status dasar
        Status::insert([
            [
                'name' => 'belum bayar',
                'slug' => 'belum-bayar',
                'color' => 'bg-red-200 text-red-800'
            ],
            [
                'name' => 'sudah dp',
                'slug' => 'sudah-dp',
                'color' => 'bg-yellow-200 text-yellow-800'
            ],
            [
                'name' => 'lunas',
                'slug' => 'lunas',
                'color' => 'bg-green-200 text-green-800'
            ],
        ]);

        $this->command->info('✅ Status seeded.');

        // Buat admin dan user biasa
        User::factory()
            ->count(10)
            ->state(function () {
                return [
                    'email' => fake()->unique()->userName() . '@gmail.com',
                ];
            })
            ->create();

        $this->command->info('✅ User seeded.');
        $this->call([UserSeeder::class]);

        // Kategori dan produk
        Category::factory(5)->create();
        Product::factory(50)->create();
        $this->command->info('✅ Category & Product seeded.');

        $this->call([AddressSeeder::class]);

        // Buat 20 order (dengan detail) dan transaksi
        Order::factory(20)->make()->each(function ($order) {
            $user = User::whereIn('role', ['user', 'corporate'])->inRandomOrder()->first();

            $address = $user->addresses()->inRandomOrder()->first()
                ?? $user->addresses()->create([
                    'recipient_name' => $user->name,
                    'phone' => '08' . rand(1000000000, 9999999999),
                    'address' => fake()->streetAddress(),
                    'city' => fake()->city(),
                    'postal_code' => fake()->postcode(),
                    'province' => fake()->state(),
                    'is_default' => true,
                ]);

            $createdAt = Carbon::create(2025, rand(1, 5), rand(1, 28))->startOfDay();

            // Buat order terlebih dahulu
            $order->forceFill([
                'user_id'           => $user->id,
                'address_id'        => $address->id,
                'role'              => $user->role,
                'order_number'      => 'ORD-' . strtoupper(Str::random(10)),
                'price'             => 0,
                'total_price'       => 0,
                'dp_paid'           => 0,
                'remaining_balance' => 0,
                'total_quantity'    => 0,
                'payment_status'    => collect(['pending','pending', 'partial', 'paid'])->random(),
                'status'            => collect(['pending', 'processing', 'completed', 'cancelled'])->random(),
                'corporate_proof'   => null,
                'created_at'        => $createdAt,
                'updated_at'        => $createdAt,
            ])->save();

            // Buat shipping dan simpan shipping_id ke order
            $shipping = \App\Models\Shipping::factory()->create([
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $order->update(['shipping_id' => $shipping->id]);

            // Buat detail order
            $details = OrderDetail::factory(rand(1, 3))->create([
                'order_id' => $order->id,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $total_produk = $details->sum(fn($item) => $item->product->price * $item->quantity);
            $total_qty = $details->sum('quantity');
            $shipping_cost = $shipping->shipping_cost ?? 0;
            $total_price = $total_produk + $shipping_cost;

            $order->update([
                'price' => $total_produk,
                'total_price' => $total_price,
                'total_quantity' => $total_qty,
                'remaining_balance' => $total_price,
            ]);

            Transaction::factory()->create([
                'user_id' => $user->id,
                'order_id' => $order->id,
                'total_payment' => $total_price,
                'payment_stage' => 'full',
                'is_verified_full' => true,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        });

        $this->command->info('✅ Order, OrderDetail, and Transaction seeded.');
        $this->command->info('🎉 Seeding selesai!');
    }
}
