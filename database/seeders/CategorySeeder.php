<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Baju', 'color' => 'blue'],
            ['name' => 'Celana', 'color' => 'red'],
            ['name' => 'Aksesoris', 'color' => 'green'],
            ['name' => 'Rompi', 'color' => 'yellow'],
            ['name' => 'Jaket', 'color' => 'brown'],
            // Tambahkan kategori lain sesuai kebutuhan
        ];

        foreach ($categories as $category) {
            Category::create([
                'name'  => $category['name'],
                'slug'  => Str::slug($category['name']),
                'color' => $category['color'],
            ]);
        }
    }
}
