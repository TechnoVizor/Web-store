<div class="py-20">
    <div class="container mx-auto px-6 max-w-6xl">
        {{-- Заголовок страницы --}}
        <div class="mb-16 border-l-2 border-white pl-6">
            <h1 class="text-4xl font-bold uppercase tracking-tighter text-white">{{ __('ui.checkout.title') }}</h1>
            <p class="mono text-[10px] text-white/30 uppercase tracking-[0.3em] mt-2">{{ __('ui.checkout.section') }} // Node: {{ request()->ip() }}</p>
        </div>

        {{-- СИСТЕМНЫЙ АЛЕРТ ОБ ОШИБКАХ --}}
        @if ($errors->any())
            <div class="mb-12 border border-red-500/50 bg-red-500/5 p-6 relative overflow-hidden animate-pulse">
                <div class="absolute top-0 left-0 w-1 h-full bg-red-500"></div>
                <div class="flex items-center mb-4">
                    <span class="text-red-500 font-bold mr-3 text-xs">[!] {{ __('ui.checkout.critical') }}</span>
                    <div class="h-[1px] flex-1 bg-red-500/20"></div>
                </div>
                <ul class="space-y-2">
                    @foreach ($errors->all() as $error)
                        <li class="text-red-400 text-[10px] uppercase tracking-widest mono flex items-center">
                            <span class="mr-2">>></span> {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
            {{-- ЛЕВАЯ КОЛОНКА: ФОРМА --}}
            <div class="lg:col-span-7">
                <form wire:submit.prevent="placeOrder" class="space-y-12">
                    <section>
                        <h2 class="text-xs font-bold uppercase tracking-widest text-white/50 mb-8 flex items-center">
                            <span class="w-8 h-[1px] bg-white/20 mr-4"></span> 01. {{ __('ui.checkout.shipping') }}
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Имя --}}
                            <div class="space-y-2">
                                <label class="mono text-[9px] uppercase text-white/40 ml-1">{{ __('ui.checkout.full_name') }}</label>
                                <input type="text" wire:model="name" placeholder="{{ __('ui.checkout.name_placeholder') }}"
                                    class="w-full bg-white/5 border {{ $errors->has('name') ? 'border-red-500/50' : 'border-white/10' }} px-4 py-4 text-white mono text-xs focus:border-white transition-all outline-none placeholder:text-white/10">
                            </div>

                            {{-- Email --}}
                            <div class="space-y-2">
                                <label class="mono text-[9px] uppercase text-white/40 ml-1">{{ __('ui.checkout.email_optional') }}</label>
                                <input type="email" wire:model="email" placeholder="{{ __('ui.checkout.email_placeholder') }}"
                                    class="w-full bg-white/5 border {{ $errors->has('email') ? 'border-red-500/50' : 'border-white/10' }} px-4 py-4 text-white mono text-xs focus:border-white transition-all outline-none placeholder:text-white/10">
                            </div>

                            {{-- Телефон --}}
                            <div class="space-y-2 md:col-span-2">
                                <label class="mono text-[9px] uppercase text-white/40 ml-1">{{ __('ui.checkout.phone') }}</label>
                                <input type="text" wire:model="phone" placeholder="{{ __('ui.checkout.phone_placeholder') }}"
                                    class="w-full bg-white/5 border {{ $errors->has('phone') ? 'border-red-500/50' : 'border-white/10' }} px-4 py-4 text-white mono text-xs focus:border-white transition-all outline-none placeholder:text-white/10">
                            </div>

                            {{-- Адрес --}}
                            <div class="space-y-2 md:col-span-2">
                                <label class="mono text-[9px] uppercase text-white/40 ml-1">{{ __('ui.checkout.address') }}</label>
                                <textarea wire:model="address" rows="3" placeholder="{{ __('ui.checkout.address_placeholder') }}"
                                    class="w-full bg-white/5 border {{ $errors->has('address') ? 'border-red-500/50' : 'border-white/10' }} px-4 py-4 text-white mono text-xs focus:border-white transition-all outline-none resize-none placeholder:text-white/10"></textarea>
                            </div>
                        </div>
                    </section>

                    <button type="submit" wire:loading.attr="disabled"
                        class="ui-btn ui-btn-primary w-full py-6 text-[10px] font-bold tracking-[0.4em] active:scale-[0.98]">
                        
                        <span wire:loading.remove wire:target="placeOrder">{{ __('ui.checkout.execute') }}</span>

                        <span wire:loading wire:target="placeOrder" class="flex items-center justify-center">
                            <svg class="animate-spin h-4 w-4 mr-3" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('ui.checkout.processing') }}
                        </span>
                    </button>
                </form>
            </div>

            {{-- ПРАВАЯ КОЛОНКА: ИТОГИ --}}
            <div class="lg:col-span-5">
                <div class="bg-white/[0.02] border border-white/5 p-8 sticky top-8">
                    <h2 class="mono text-[10px] uppercase text-white/40 tracking-widest mb-8 flex items-center">
                        <span class="w-2 h-2 bg-white/20 mr-3"></span> {{ __('ui.checkout.summary') }}
                    </h2>

                    <div class="space-y-6 mb-10">
                        @foreach($cart as $item)
                            <div class="flex justify-between items-start group">
                                <div>
                                    <p class="text-xs text-white font-bold uppercase group-hover:text-green-500 transition-colors">{{ $item['name'] }}</p>
                                    <p class="mono text-[9px] text-white/30 uppercase mt-1">{{ __('ui.cart.units') }}: {{ $item['quantity'] }} // PPU: ${{ number_format($item['price'], 0) }}</p>
                                </div>
                                <span class="mono text-xs text-white font-bold">${{ number_format($item['price'] * $item['quantity'], 0) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-white/10 pt-6 space-y-4">
                        <div class="flex justify-between mono text-[10px] uppercase">
                            <span class="text-white/40">{{ __('ui.checkout.subtotal') }}</span>
                            <span class="text-white">${{ number_format($total, 0) }}</span>
                        </div>
                        <div class="flex justify-between mono text-[10px] uppercase">
                            <span class="text-white/40">{{ __('ui.checkout.shipping_fee') }}</span>
                            <span class="text-green-500">{{ __('ui.checkout.free') }}</span>
                        </div>
                        <div class="flex justify-between items-end pt-6 border-t border-white/5">
                            <span class="mono text-[10px] uppercase text-white/40">{{ __('ui.checkout.total') }}</span>
                            <span class="text-3xl font-bold text-white tracking-tighter shadow-white/10">${{ number_format($total, 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
