<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(rand(2, 4), true);

        return [
            'category_id' => Category::query()->inRandomOrder()->value('id') ?? Category::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => fake()->realText(200), // Фейковый текст на 200 символов
            'price' => fake()->randomFloat(2, 10, 2000), // Цена от 10 до 2000 с копейками
            'quantity' => fake()->numberBetween(0, 100), // Случайный остаток на складе
            'is_active' => fake()->boolean(80), // 80% шанс, что товар активен
        ];
    }
}
