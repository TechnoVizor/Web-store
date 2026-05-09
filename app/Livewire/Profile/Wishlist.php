<?php

namespace App\Livewire\Profile;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Wishlist extends Component
{
    public function remove(int $productId): void
    {
        Auth::user()->wishlists()->detach($productId);
    }

    public function addToBag(int $productId): void
    {
        $product = Product::query()
            ->select(['id', 'name', 'price', 'image'])
            ->whereKey($productId)
            ->where('is_active', true)
            ->firstOrFail();

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->price,
                'image' => $product->image_url,
            ];
        }

        session()->put('cart', $cart);

        $this->dispatch('cart-updated');
    }

    public function render(): View
    {
        return view('livewire.profile.wishlist', [
            'products' => Auth::user()
                ->wishlists()
                ->with('category:id,name')
                ->select(['products.id', 'products.category_id', 'products.name', 'products.slug', 'products.price', 'products.image'])
                ->latest('wishlists.created_at')
                ->get(),
        ]);
    }
}
