<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function add(int $id)
    {
        $product = Product::query()
            ->where('is_active', true)
            ->findOrFail($id);

        $cart = session()->get('cart', []);
        $currentQuantity = (int) ($cart[$id]['quantity'] ?? 0);

        if ($currentQuantity >= $product->quantity) {
            return back()->with('error', 'Недостаточно товара на складе.');
        }

        $cart[$id] = [
            'name' => $product->name,
            'quantity' => $currentQuantity + 1,
            'price' => $product->price,
            'image' => $product->image_url,
        ];

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Товар успешно добавлен в корзину!');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = session()->get('cart', []);

        if (! isset($cart[$validated['id']])) {
            throw ValidationException::withMessages([
                'id' => 'Товар не найден в корзине.',
            ]);
        }

        $product = Product::query()->findOrFail($validated['id']);
        $cart[$validated['id']]['quantity'] = min($validated['quantity'], max(1, $product->quantity));
        session()->put('cart', $cart);

        $subtotal = number_format($cart[$validated['id']]['price'] * $cart[$validated['id']]['quantity'], 0);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return response()->json([
            'subtotal' => $subtotal,
            'total' => number_format($total, 0),
            'cartCount' => count($cart),
        ]);
    }

    public function remove(int $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Товар удален');
    }
}
