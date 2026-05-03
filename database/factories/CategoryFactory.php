<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        // Генерируем случайное название из 1-2 слов
        $name = fake()->words(rand(1, 2), true); 
        
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name), // Автоматически делает URL (например, "smart-phones")
        ];
    }
}