<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
public function add($id)
{
    $product = Product::findOrFail($id);
    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        $cart[$id]['quantity']++;
    } else {
        $cart[$id] = [
            "name" => $product->name,
            "quantity" => 1,
            "price" => $product->price,
            "image" => $product->image, // <--- Добавляем это поле
        ];
    }

    session()->put('cart', $cart);
    return redirect()->back()->with('success', 'Товар успешно добавлен в корзину!');
}
    public function index()
{
    $cart = session()->get('cart', []);
    $total = 0;

    foreach($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    return view('cart.index', compact('cart', 'total'));
}

public function update(Request $request)
{
    $cart = session()->get('cart', []);
    if(isset($cart[$request->id])) {
        $cart[$request->id]['quantity'] = $request->quantity;
        session()->put('cart', $cart);
    }

    // Считаем новые итоги, чтобы вернуть их в JS
    $subtotal = number_format($cart[$request->id]['price'] * $cart[$request->id]['quantity'], 0);
    $total = 0;
    foreach($cart as $item) $total += $item['price'] * $item['quantity'];

    return response()->json([
        'subtotal' => $subtotal,
        'total' => number_format($total, 0),
        'cartCount' => count($cart)
    ]);
}





// Добавим сразу метод для удаления товара
public function remove($id)
{
    $cart = session()->get('cart', []);
    if(isset($cart[$id])) {
        unset($cart[$id]);
        session()->put('cart', $cart);
    }
    return redirect()->back()->with('success', 'Товар удален');
}
}