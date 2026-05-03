<?php
use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component {
    public $message = '';
    public $visible = false;

    #[On('cart-updated')]
    public function showAlert()
    {
        // Твое системное сообщение
        $this->message = "PROTOCOL_EXECUTED: SUCCESS";
        $this->visible = true;
    }
}; ?>

<div x-data="{ 
        show: @entangle('visible'),
        startTimer() {
            setTimeout(() => { this.show = false }, 3000); 
        }
    }" 
    x-init="$watch('show', value => { if (value) startTimer() })" 
    x-show="show"
    x-cloak
    x-transition:enter="transition ease-out duration-300" 
    x-transition:enter-start="opacity-0 translate-y-10 sm:translate-y-4 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 scale-100" 
    x-transition:leave-end="opacity-0 scale-95"
    {{-- ГЛАВНЫЙ ФИКС ПОЗИЦИИ: --}}
    class="fixed bottom-10 z-[110] w-[calc(100%-2rem)] sm:w-auto 
           left-1/2 -translate-x-1/2 
           sm:left-auto sm:right-10 sm:translate-x-0 pointer-events-none">
    
    <div class="pointer-events-auto border border-white/20 bg-black p-5 min-w-[300px] shadow-[0_0_50px_rgba(0,0,0,0.8)] relative overflow-hidden glass">

        <div class="flex items-center space-x-4">
            {{-- Индикатор статуса --}}
            <div class="w-2 h-2 bg-white animate-pulse shrink-0"></div>
            
            <div class="flex-grow">
                <p class="mono text-[9px] uppercase tracking-[0.3em] text-white/30 mb-1">System_Response</p>
                <p class="mono text-[11px] uppercase tracking-widest text-white font-bold leading-none">
                    {{ $message }}
                </p>
            </div>
        </div>

        {{-- Прогресс-бар (полоска времени) --}}
        <div class="absolute bottom-0 left-0 h-[2px] bg-white/10 w-full">
            <div x-show="show" 
                 class="h-full bg-white transition-all ease-linear"
                 :style="show ? 'width: 0%; transition-duration: 3000ms;' : 'width: 100%; transition-duration: 0ms;'"
                 x-init="$watch('show', v => { if(v) { $el.style.width = '100%'; setTimeout(() => $el.style.width = '0%', 50) } })">
            </div>
        </div>

        {{-- Декоративный элемент --}}
        <div class="absolute top-0 right-0 p-2">
            <div class="mono text-[7px] text-white/10 uppercase tracking-tighter italic">Module_01 // OK</div>
        </div>
    </div>
</div>