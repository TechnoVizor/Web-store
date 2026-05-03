<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Берем активные товары, сразу подгружаем их категории (чтобы избежать проблемы N+1 запросов),
        // и разбиваем на страницы по 12 штук на каждой.
        $products = Product::with('category')
            ->where('is_active', true)
            ->latest() // Сортируем: сначала новые
            ->paginate(12);

        // Передаем переменную $products в шаблон 'store.index'
        return view('store.show', compact('products'));
    }
}