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
    <a href="{{ route('cart.index') }}"
        wire:navigate
        class="group relative flex min-h-10 items-center border border-white/10 bg-white/[0.035] px-3 py-2 text-white/70 transition-all duration-300 hover:-translate-y-px hover:border-white/35 hover:bg-white/[0.075] focus:outline-none focus-visible:border-white/50"
        aria-label="{{ __('ui.cart.system_bag') }}: {{ $count }} {{ __('ui.cart.units') }}">
        <span class="pointer-events-none absolute -left-px -top-px h-2 w-2 border-l border-t border-white/30 opacity-0 transition-all duration-300 group-hover:opacity-100"></span>
        <span class="pointer-events-none absolute -bottom-px -right-px h-2 w-2 border-b border-r border-white/30 opacity-0 transition-all duration-300 group-hover:opacity-100"></span>

        <span class="relative mr-3 flex h-3 w-3 items-center justify-center border border-white/15 bg-black">
            @if($count > 0)
                <span class="absolute h-2 w-2 animate-ping bg-white/50"></span>
                <span class="relative h-1.5 w-1.5 bg-white"></span>
            @else
                <span class="relative h-1.5 w-1.5 bg-white/20"></span>
            @endif
        </span>

        <span class="mono min-w-6 text-center text-xs font-black tracking-tighter transition-colors group-hover:text-white">
            {{ str_pad($count, 2, '0', STR_PAD_LEFT) }}
        </span>

        <span class="mx-2 h-4 w-px bg-white/10 transition-colors group-hover:bg-white/25"></span>

        <span class="mono text-[9px] uppercase tracking-[0.22em] text-white/30 transition-colors group-hover:text-white/60">{{ __('ui.cart.units') }}</span>

        <span class="ml-3 hidden h-5 items-center border-l border-white/10 pl-3 mono text-[8px] uppercase tracking-[0.22em] text-white/20 transition-colors group-hover:text-white/45 sm:flex">
            {{ __('ui.cart.system_bag') }}
        </span>
    </a>
</div>
