<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil kategori yang sudah ada
        $categories = Category::all();
        if ($categories->isEmpty()) {
            $this->command->error('Tidak ada kategori. Harap jalankan CategorySeeder terlebih dahulu.');
            return;
        }
        
        // Daftar gambar yang tersedia
        $images = [
            'Item-1.jpg',
            'Item-2.jpg',
            'Item-3.jpg',
            'Item-4.jpg',
            'Item-5.jpg',
        ];

        // Override nilai category_id dan gambar pada setiap produk
        Product::factory(100)->create([
            'category_id' => function () use ($categories) {
                return $categories->random()->id;
            },
            'image' => function () use ($images) {
                return $images[array_rand($images)];
            },
        ]);
    }
}
