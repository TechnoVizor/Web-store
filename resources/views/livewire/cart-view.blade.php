<div> {{-- Корневой элемент Livewire (ОБЯЗАТЕЛЕН) --}}
    
    {{-- Твои стили --}}
    <style>
        .mono { font-family: 'JetBrains Mono', monospace; }
        .skeleton-shimmer { ... }
    </style>

    {{-- Основной контейнер, который задает ширину --}}
    <main class="container mx-auto px-6 py-16 max-w-6xl">
        
        {{-- Заголовок --}}
        <div class="flex items-center space-x-4 mb-12">
            <div class="w-1 h-10 bg-white"></div>
            <div>
                <h1 class="text-3xl font-bold uppercase tracking-tighter text-white">{{ __('ui.cart.title') }}</h1>
                <p class="mono text-[10px] text-white/30 uppercase tracking-[0.3em]">{{ __('ui.cart.subtitle') }}</p>
            </div>
        </div>

    @if(empty($cartItems))
        <div class="py-32 border border-dashed border-white/5 text-center bg-white/[0.02]">
            <p class="mono text-[10px] text-white/20 uppercase tracking-[0.5em]">{{ __('ui.cart.empty') }}</p>
            <a href="/" wire:navigate class="inline-block mt-8 px-10 py-4 border border-white text-[9px] font-bold uppercase tracking-[0.3em] hover:bg-white hover:text-black transition-all duration-500">
                {{ __('ui.cart.return') }}
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
            {{-- Список товаров --}}
            <div class="lg:col-span-2 space-y-10">
@foreach($cartItems as $id => $details)
    <div class="flex flex-col sm:flex-row items-start space-y-6 sm:space-y-0 sm:space-x-8 border-b border-white/5 pb-10 group" 
         wire:key="item-{{ $id }}">
        
        {{-- Картинка --}}
        <div class="w-full sm:w-32 aspect-[3/4] bg-white/5 shrink-0 border border-white/5 overflow-hidden">
            <img src="{{ $details['image'] ?? '' }}" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition-all">
        </div>
        
        <div class="flex-grow w-full">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-widest text-white mb-1">{{ $details['name'] }}</h3>
                    <p class="mono text-[9px] text-white/20 uppercase">{{ __('ui.cart.item_id') }}: {{ $id }}</p>
                </div>
                <button wire:click="removeItem('{{ $id }}')" class="text-white/10 hover:text-red-500 transition-colors p-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="flex justify-between items-end mt-12">
                <div class="flex items-center border border-white/10 bg-black/40">
                    <button wire:click="updateQty('{{ $id }}', -1)" class="px-4 py-2 text-white/30 hover:text-white hover:bg-white/5">-</button>
                    <span class="px-6 py-2 mono text-xs border-x border-white/10 text-white">
                        {{ str_pad($details['quantity'], 2, '0', STR_PAD_LEFT) }}
                    </span>
                    <button wire:click="updateQty('{{ $id }}', 1)" class="px-4 py-2 text-white/30 hover:text-white hover:bg-white/5">+</button>
                </div>

                <div class="text-right">
                    <p class="mono text-[9px] text-white/20 uppercase mb-1">{{ __('ui.cart.subtotal') }}</p>
                    <span class="mono text-lg font-bold text-white">${{ number_format($details['price'] * $details['quantity'], 0) }}</span>
                </div>
            </div>
        </div>
    </div>
@endforeach
            </div>

            {{-- Правая колонка (Итого) --}}
            <div class="lg:col-span-1">
                <div class="border border-white/10 p-10 sticky top-24 bg-white/[0.02] backdrop-blur-md">
                    <h2 class="mono text-[10px] uppercase tracking-[0.4em] text-white/20 mb-10 pb-4 border-b border-white/5">{{ __('ui.cart.summary') }}</h2>
                    
                    <div class="space-y-6 mb-12">
                        <div class="flex justify-between text-[10px] uppercase mono tracking-widest">
                            <span class="text-white/30">{{ __('ui.cart.subtotal_units') }}</span>
                            <span class="text-white">{{ array_sum(array_column($cartItems, 'quantity')) }}</span>
                        </div>
                        <div class="flex justify-between text-[10px] uppercase mono tracking-widest">
                            <span class="text-white/30">{{ __('ui.cart.net_amount') }}</span>
                            <span class="text-white font-bold">${{ number_format($total, 0) }}</span>
                        </div>
                        <div class="flex justify-between text-[10px] uppercase mono tracking-widest">
                            <span class="text-white/30">{{ __('ui.cart.tax_service') }}</span>
                            <span class="text-white/20">{{ __('ui.cart.calculated_checkout') }}</span>
                        </div>
                    </div>

                    <div class="border-t-2 border-white pt-8 mb-12">
                        <div class="flex justify-between items-end">
                            <span class="mono text-[10px] uppercase text-white font-bold">{{ __('ui.cart.total_cost') }}</span>
                            <span class="text-3xl font-bold tracking-tighter text-white">${{ number_format($total, 0) }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" wire:navigate
   class="block w-full bg-white text-black py-5 text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-neutral-200 transition-all active:scale-[0.98] text-center">
    {{ __('ui.cart.confirm') }}
</a>
                    
                    <div class="mt-6 flex items-center justify-center space-x-3 opacity-20">
                        <div class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="mono text-[8px] uppercase tracking-widest">{{ __('ui.cart.secure') }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
