<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    // Показать страницу избранного
    public function index()
    {
        // Получаем все товары из избранного текущего пользователя
        $products = auth()->user()->wishlists()->with('category')->latest()->get();
        
        return view('wishlist.index', compact('products'));
    }

    // Добавить или удалить товар из избранного (переключатель)
    public function toggle(Product $product)
    {
        // Метод toggle сам проверяет: если товар есть — удаляет, если нет — добавляет
        auth()->user()->wishlists()->toggle($product->id);

        // Возвращаем пользователя на ту же страницу, где он был
        return back();
    }
}