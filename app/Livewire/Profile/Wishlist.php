<?php

namespace App\Livewire\Profile;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Wishlist extends Component
{
    public function remove(int $productId): void
    {
        Auth::user()->wishlists()->detach($productId);
    }

    public function render(): View
    {
        return view('livewire.profile.wishlist', [
            'products' => Auth::user()
                ->wishlists()
                ->with('category:id,name')
                ->select(['products.id', 'products.category_id', 'products.name', 'products.slug', 'products.price', 'products.image'])
                ->latest('wishlists.created_at')
                ->limit(4)
                ->get(),
            'productsCount' => Auth::user()->wishlists()->count(),
        ]);
    }
}
