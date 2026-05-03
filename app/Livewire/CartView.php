<?php

namespace App\Livewire;

use Livewire\Component;

class CartView extends Component
{
    public function placeholder()
    {
        return view('livewire.placeholders.cart-skeleton');
    }

    // Удаление товара (логика из твоего контроллера)
    public function removeItem($id)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        $this->dispatch('cart-updated');
        $this->dispatch('show-system-alert', message: 'PROTOCOL: ITEM_PURGED');
    }

    // Обновление количества (логика из твоего контроллера)
    public function updateQty($id, $delta)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $delta;

            // Если количество стало 0 или меньше — удаляем
            if ($cart[$id]['quantity'] <= 0) {
                unset($cart[$id]);
            }

            session()->put('cart', $cart);
        }

        $this->dispatch('cart-updated');
    }

    public function render()
    {
       $cart = session()->get('cart', []);
    $total = 0;
    foreach($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    return view('livewire.cart-view', [
        'cartItems' => $cart,
        'total' => $total
    ]); // УБРАЛИ ->layout()
}}