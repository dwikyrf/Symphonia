<?php

// namespace Database\Seeders;

// use App\Models\Order;
// use App\Models\OrderDetail;
// use App\Models\ProductReview;
// use Illuminate\Database\Seeder;
// use Faker\Factory as Faker;

// class ProductReviewSeeder extends Seeder
// {
//     public function run(): void
//     {
//         $faker = Faker::create();

//         $completedOrders = Order::where('status', 'completed')->get();

//         foreach ($completedOrders as $order) {
//             foreach ($order->details as $detail) {
//                 if (rand(0, 100) < 80) { // 80% completed orders kasih review
//                     ProductReview::create([
//                         'user_id' => $order->user_id,
//                         'product_id' => $detail->product_id,
//                         'rating' => rand(4, 5),
//                         'comment' => $faker->sentence(),
//                         'created_at' => now()->subDays(rand(1, 30)),
//                         'updated_at' => now(),
//                     ]);
//                 }
//             }
//         }

//         $this->command->info('✅ Dummy review produk selesai!');
//     }
// }
