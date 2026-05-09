<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component {
    public $count = 0;

    public function mount()
    {
        $this->updateCount();
    }

    #[On('cart-updated')]
    public function updateCount()
    {
        $cart = session()->get('cart', []);
        $this->count = array_sum(array_column($cart, 'quantity'));
    }
}; ?>

<div class="relative">
    <a href="{{ route('cart.index') }}" wire:navigate class="group flex items-center space-x-3">
        <div
            class="flex items-center border border-white/10 px-3 py-2 bg-white/5 group-hover:border-white/40 transition-all duration-500">
            <div class="relative flex h-1.5 w-1.5 mr-3">
                @if($count > 0)
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-white"></span>
                @else
                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-white/20"></span>
                @endif
            </div>

            <span class="mono text-xs font-bold tracking-tighter">
                {{ str_pad($count, 2, '0', STR_PAD_LEFT) }}
            </span>

            <span class="mono text-[9px] text-white/20 ml-2 uppercase">{{ __('ui.cart.units') }}</span>
        </div>
    </a>
</div>
