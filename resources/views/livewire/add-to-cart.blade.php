<?php

use App\Models\Product;
use Livewire\Volt\Component;

new class extends Component
{
    public $productId;

    public function addToBag()
    {
        $product = Product::findOrFail($this->productId);
        $cart = session()->get('cart', []);

        if (isset($cart[$this->productId])) {
            $cart[$this->productId]['quantity']++;
        } else {
            $cart[$this->productId] = [
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->price,
                'image' => $product->image_url,
            ];
        }

        session()->put('cart', $cart);

        // Опционально: можно добавить всплывающее уведомление
        $this->dispatch('cart-updated');
    }
}; ?>

<div>
    {{-- Удаляем <form>, оставляем только кнопку с wire:click --}}
        <button wire:click="addToBag" wire:loading.attr="disabled"
            class="w-full py-3 sm:py-4 px-1 sm:px-4 bg-transparent border border-white/20 text-white text-[7px] sm:text-[10px] font-bold uppercase tracking-[0.2em] transition-all duration-300 hover:bg-white hover:text-black hover:border-white active:scale-[0.98] disabled:opacity-50 mono">

            {{-- Текст меняется при загрузке --}}
            <span wire:loading.remove>Add to bag</span>
            <span wire:loading>Adding...</span>

        </button>
        
</div>
