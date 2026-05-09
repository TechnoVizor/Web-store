<?php

namespace App\Http\Controllers;

use App\Models\Product;

class StoreController extends Controller
{
    // Метод для главной страницы (каталога)
    public function index()
    {
        // Берем только активные товары и подгружаем их категории
        return view('store.index');
    }

    // Метод для страницы одного товара
    public function show(string $slug)
    {
        $product = Product::query()
            ->with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('store.show', compact('product'));
    }
}
