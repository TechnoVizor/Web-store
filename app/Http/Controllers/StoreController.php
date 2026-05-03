<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    // Метод для главной страницы (каталога)
    public function index()
    {
        // Берем только активные товары и подгружаем их категории
       return view('store.index');
    }

    // Метод для страницы одного товара
    public function show($slug)
{
    $product = \App\Models\Product::where('slug', $slug)->firstOrFail();
    return view('store.show', compact('product'));
}
}
