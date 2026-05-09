<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WishlistToggle extends Component
{
    public $productId;

    public $isWished;

    public function mount($productId)
    {
        $this->productId = $productId;

        // Проверяем, есть ли товар в избранном у юзера
        if (Auth::check()) {
            $this->isWished = Auth::user()->wishlists()->where('product_id', $this->productId)->exists();
        } else {
            $this->isWished = false;
        }
    }

    public function toggle()
    {
        if (! Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        $user = Auth::user();

        // Переключаем состояние (attach/detach)
        if ($this->isWished) {
            $user->wishlists()->detach($this->productId);
            $this->isWished = false;
        } else {
            $user->wishlists()->attach($this->productId);
            $this->isWished = true;
        }

        // Опционально: можно отправить уведомление в твой системный лог
        $this->dispatch('show-system-alert', message: $this->isWished ? 'UNIT_SAVED_TO_MEMORY' : 'UNIT_PURGED_FROM_MEMORY');
    }

    public function render()
    {
        return view('livewire.wishlist-toggle');
    }
}
