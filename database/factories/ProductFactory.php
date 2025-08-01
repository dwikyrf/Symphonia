<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'slug' => $this->faker->unique()->slug(),
            'price' => $this->faker->numberBetween(50000, 200000),
            'description' => $this->faker->paragraph(),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'image' => 'img/default-product.jpg',
        ];
    }
}

