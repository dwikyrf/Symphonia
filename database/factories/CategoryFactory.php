<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->unique()->word()),
            'slug' => $this->faker->unique()->slug(),
            'color' => $this->faker->randomElement([
                'bg-red-200 text-red-800',
                'bg-blue-200 text-blue-800',
                'bg-yellow-200 text-yellow-800',
                'bg-green-200 text-green-800',
                'bg-purple-200 text-purple-800',
            ]),
        ];
    }
}
