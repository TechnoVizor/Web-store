<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Создаем одного тестового админа, чтобы потом можно было зайти на сайт
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // пароль будет: password
            'is_admin' => true,
            'is_super_admin' => true,
        ]);

        // 2. Создаем 5 категорий
        Category::factory(5)->create();

        // 3. Создаем 50 товаров (они сами привяжутся к случайным категориям благодаря нашему ProductFactory)
        Product::factory(50)->create();
    }
}
