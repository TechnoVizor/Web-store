<div>
    {{-- Вставляем твои уникальные стили --}}
<style>
        .mono { font-family: 'JetBrains Mono', monospace; }
        .glass { background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(20px) saturate(180%); }
        .product-card {
            position: relative;
            background: linear-gradient(180deg, rgba(255,255,255,0.028), rgba(255,255,255,0.008));
            border: 1px solid rgba(255,255,255,0.07);
            transition: transform 260ms ease, border-color 260ms ease, background-color 260ms ease;
        }
        .product-card::before,
        .product-card::after {
            content: "";
            position: absolute;
            z-index: 2;
            width: 0.8rem;
            height: 0.8rem;
            opacity: 0;
            transition: opacity 260ms ease, transform 260ms ease;
            pointer-events: none;
        }
        .product-card::before {
            left: -1px;
            top: -1px;
            border-left: 1px solid rgba(255,255,255,0.58);
            border-top: 1px solid rgba(255,255,255,0.58);
            transform: translate(-4px, -4px);
        }
        .product-card::after {
            right: -1px;
            bottom: -1px;
            border-right: 1px solid rgba(255,255,255,0.58);
            border-bottom: 1px solid rgba(255,255,255,0.58);
            transform: translate(4px, 4px);
        }
        .product-card:hover {
            transform: translateY(-4px);
            border-color: rgba(255, 255, 255, 0.22);
            background: rgba(255,255,255,0.035);
        }
        .product-card:hover::before,
        .product-card:hover::after {
            opacity: 1;
            transform: translate(0, 0);
        }
        .product-media::after {
            content: "";
            position: absolute;
            inset: auto 0 0 0;
            height: 34%;
            background: linear-gradient(180deg, transparent, rgba(0,0,0,0.72));
            opacity: 0.78;
            pointer-events: none;
        }
        .wishlist-heart {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.15rem;
            height: 2.15rem;
            border: 1px solid rgba(255,255,255,0.16);
            background: rgba(0,0,0,0.36);
            color: rgba(255,255,255,0.46);
            backdrop-filter: blur(16px);
            transition: color 180ms ease, border-color 180ms ease, background-color 180ms ease, transform 180ms ease;
        }
        .wishlist-heart:hover,
        .wishlist-heart:focus-visible {
            color: rgba(255,255,255,0.88);
            border-color: rgba(255,255,255,0.34);
            background: rgba(0,0,0,0.58);
            transform: translateY(-1px);
            outline: none;
        }
        .wishlist-heart.is-active {
            color: rgba(255,255,255,0.86);
            border-color: rgba(255,255,255,0.30);
            background: rgba(255,255,255,0.08);
        }
        .wishlist-heart.is-active svg {
            fill: rgba(255,255,255,0.72);
        }
        
        .skeleton-shimmer {
            background: linear-gradient(90deg, #050505 25%, #111111 50%, #050505 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite linear;
        }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }

        .filter-input { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.8); outline: none; transition: all 0.3s; }
        .filter-input:focus { border-color: white; background: rgba(255,255,255,0.07); }
        .cat-btn { font-size: 9px; min-height: 2rem; padding: 0.45rem 0.7rem; letter-spacing: 0.1em; }
        .cat-btn.active { background: rgba(255,255,255,0.8); color: #050505 !important; border-color: rgba(255,255,255,0.8); }
        .loading-fade { transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>

<header class="relative min-h-[560px] md:min-h-[720px] border-b border-white/5 overflow-hidden flex items-center">
    <video
        data-hero-video
        class="absolute inset-0 h-full w-full object-cover opacity-35 pointer-events-none"
        autoplay
        muted
        loop
        playsinline
        preload="auto"
        aria-hidden="true">
        <source src="{{ asset('videos/hero-fashion-loop.mp4') }}" type="video/mp4">
    </video>
    <div class="absolute inset-0 bg-black/55 pointer-events-none"></div>
    <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-black to-transparent pointer-events-none"></div>

    <div class="relative z-10 container mx-auto px-6 text-center"
         x-data="{
            status: '',
            titlePart1: '',
            titlePart2: '',
            desc: '',
            fullStatus: @js(__('ui.store.status')),
            fullPart1: @js(__('ui.store.title_1')),
            fullPart2: @js(__('ui.store.title_2')),
            fullDesc: @js(__('ui.store.description')),
            type(target, source, speed) {
                let index = 0;
                const interval = setInterval(() => {
                    this[target] += source[index] ?? '';
                    index++;
                    if (index >= source.length) clearInterval(interval);
                }, speed);
            }
         }"
         x-init="
            type('status', fullStatus, 24);
            setTimeout(() => type('titlePart1', fullPart1, 44), 250);
            setTimeout(() => type('titlePart2', fullPart2, 44), 820);
            setTimeout(() => type('desc', fullDesc, 12), 1250);
         ">
        
        {{-- Бадж статуса --}}
        <div class="inline-block px-3 py-1 border border-white/10 mb-6">
            <span class="text-[8px] font-mono text-white/40 uppercase tracking-widest" x-text="status"></span>
        </div>

        {{-- ЗАГОЛОВОК: СТРУКТУРА КОТОРУЮ НЕ СЛОМАТЬ --}}
        <h1 class="text-4xl md:text-7xl font-bold tracking-tighter mb-6 uppercase text-white min-h-[1.5em] text-center leading-none">
            <span x-text="titlePart1"></span>
            <span class="inline">
        <span class="text-white/20" x-text="titlePart2"></span>
        <span class="blink-cursor">|</span>
    </span>
</h1>

        {{-- Описание --}}
        <p class="text-white/40 text-sm max-w-lg mx-auto font-light leading-relaxed min-h-[3em]" x-text="desc"></p>
    </div>
</header>

<style>
.blink-cursor {
    color: rgba(255, 255, 255, 0.8);
    margin-left: 1px;
    animation: blink-animation 1.1s step-end infinite;
    font-weight: 200;
    /* Важно для мобилок: */
    display: inline-block;
    white-space: nowrap; 
    vertical-align: middle;
    line-height: 1;
}

@keyframes blink-animation {
    from, to { opacity: 1; }
    50% { opacity: 0; }
}

/* На всякий случай: адаптивный отступ для хедера */
@media (max-width: 768px) {
    header {
        padding-top: 3rem;
        padding-bottom: 3rem;
    }
}
</style>
    <main class="container mx-auto px-4 pt-28 pb-20 relative min-h-[800px]">
        
        {{-- ПАНЕЛЬ УПРАВЛЕНИЯ (Всегда видна) --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
            <div class="border-l-2 border-white pl-4">
                <h2 class="text-xs font-bold uppercase tracking-[0.4em]">{{ __('ui.store.inventory') }}</h2>
                <span class="mono text-[10px] text-white/40 uppercase">{{ __('ui.store.total_units') }}: {{ $products->total() }}</span>
            </div>

            <div class="w-full md:hidden">
                <label for="mobile-category-filter" class="mono text-[8px] text-white/30 uppercase tracking-[0.25em] block mb-2">
                    {{ __('ui.store.category_filter') }}
                </label>
                <div class="relative">
                    <select id="mobile-category-filter"
                        wire:model.live="selectedCategory"
                        class="w-full appearance-none bg-black border border-white/10 px-4 py-3 pr-10 text-[10px] text-white uppercase tracking-[0.2em] focus:outline-none focus:border-white mono">
                        <option value="">{{ __('ui.store.all_categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-white/40"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <div class="hidden md:flex flex-wrap items-center gap-3">
                <button wire:click="resetFilters" class="ui-btn ui-btn-compact mono text-[9px] tracking-widest mr-4">
                    [ {{ __('ui.store.reset') }} ]
                </button>
                @foreach($categories as $category)
                    <button wire:click="selectCategory({{ $category->id }})" 
                            class="ui-btn cat-btn mono {{ $selectedCategory == $category->id ? 'active' : '' }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- ГЛАВНЫЙ КОНТЕЙНЕР СЕТКИ --}}
        <div class="relative">
            
            {{-- СКЕЛЕТОНЫ (Абсолютное наложение поверх контента при загрузке) --}}
            {{-- Мы используем opacity вместо удаления, чтобы высота страницы не менялась --}}
            <div wire:loading.delay.shorter wire:target="selectedCategory, search" 
                 class="absolute inset-0 z-30 bg-black/60 backdrop-blur-sm transition-all duration-500">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-6">
                    @for($i = 0; $i < 8; $i++)
                        <div class="aspect-[4/5] skeleton-shimmer border border-white/5"></div>
                    @endfor
                </div>
            </div>

            {{-- ОСНОВНОЙ СПИСОК ТОВАРОВ --}}
            <div class="loading-fade" 
                 wire:loading.class="opacity-20 pointer-events-none" 
                 wire:target="selectedCategory, search">
                
                @if($products->isEmpty())
                    <div class="py-40 text-center border border-dashed border-white/5">
                        <p class="mono text-[10px] text-white/20 uppercase tracking-[0.5em]">{{ __('ui.store.empty') }}</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-6">
                        @foreach($products as $product)
                            <div class="product-card flex flex-col group h-full" wire:key="p-{{ $product->id }}">
                                
                                {{-- Картинка --}}
                                <div class="product-media relative aspect-[4/5] overflow-hidden bg-black" 
                                     x-data="{ loaded: false }" 
                                     x-init="$nextTick(() => { if ($refs.img && $refs.img.complete) loaded = true; }); setTimeout(() => { loaded = true; }, 3000);">
                                    
                                    <div x-show="!loaded" class="absolute inset-0 skeleton-shimmer z-10 transition-opacity duration-500" x-transition:leave="opacity-0"></div>
                                    
                                    <a href="{{ route('product.show', $product->slug) }}" wire:navigate class="block w-full h-full">
                                        <img x-ref="img"
                                             src="{{ $product->image_url }}"
                                             alt="{{ $product->name }}"
                                             width="640"
                                             height="800"
                                             loading="lazy"
                                             fetchpriority="auto"
                                             decoding="async"
                                             @load="loaded = true"
                                             x-on:error="loaded = true"
                                             class="w-full h-full object-cover transition-all duration-700"
                                             :class="loaded ? 'opacity-70 group-hover:opacity-100 group-hover:scale-105' : 'opacity-0'">
                                    </a>
                                    
                                    {{-- Wishlist --}}
                                    <div class="absolute top-3 right-3 z-20">
                                        @if(auth()->check())
                                            <livewire:wishlist-toggle :product-id="$product->id" :key="'wish-'.$product->id" />
                                        @else
                                            <a href="/login" wire:navigate aria-label="{{ __('ui.nav.sign_in') }}" class="wishlist-heart">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>

                                    <div class="absolute bottom-3 left-3 z-20 mono text-[7px] text-white/42 bg-black/45 px-2 py-1 border border-white/10 uppercase tracking-[0.22em]">
                                        Ref_{{ $product->id }}
                                    </div>
                                </div>

                                {{-- Данные --}}
                                <div class="p-4 sm:p-5 flex flex-col flex-grow">
                                    <div class="mb-5 min-h-[4.25rem]">
                                        <div class="mb-2 flex items-center justify-between gap-3">
                                            <p class="mono text-[8px] text-white/34 uppercase tracking-[0.22em] truncate">{{ $product->category->name }}</p>
                                            <span class="h-px flex-1 bg-white/8"></span>
                                        </div>
                                        <h3 class="text-[12px] sm:text-sm font-bold uppercase tracking-[0.12em] leading-snug text-white/86 line-clamp-2">{{ $product->name }}</h3>
                                    </div>
                                    <div class="mt-auto">
                                        <div class="mb-4 flex items-end justify-between border-t border-white/6 pt-4">
                                            <span class="mono text-[8px] uppercase tracking-[0.22em] text-white/30">{{ __('ui.store.price_label') }}</span>
                                            <span class="text-base font-black mono text-white/86">${{ number_format($product->price, 0) }}</span>
                                        </div>
                                        <div class="mb-4">
                                            <div class="mb-2 flex items-center justify-between">
                                                <span class="mono text-[8px] uppercase tracking-[0.22em] text-white/30">{{ __('ui.product.size') }}</span>
                                                <span class="mono text-[8px] uppercase tracking-[0.18em] text-white/20">{{ __('ui.product.size_hint') }}</span>
                                            </div>
                                            <div class="grid grid-cols-5 gap-1.5">
                                                @foreach($product->availableSizes() as $size)
                                                    <label class="group/size">
                                                        <input type="radio"
                                                            wire:model.live="selectedSizes.{{ $product->id }}"
                                                            value="{{ $size }}"
                                                            @checked(($selectedSizes[$product->id] ?? $product->availableSizes()[0]) === $size)
                                                            class="peer sr-only">
                                                        <span class="flex min-h-8 items-center justify-center border border-white/10 bg-black/30 mono text-[9px] font-bold uppercase tracking-[0.14em] text-white/42 transition-all group-hover/size:border-white/28 group-hover/size:text-white/78 peer-checked:border-white/70 peer-checked:bg-white/80 peer-checked:text-black">
                                                            {{ $size }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        <button type="button"
                                            wire:click.prevent="addToBag({{ $product->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="addToBag({{ $product->id }})"
                                            class="ui-btn w-full py-3 sm:py-3.5 px-1 sm:px-4 text-[7px] sm:text-[10px] font-bold tracking-[0.2em] active:scale-[0.98] mono">
                                            <span wire:loading.remove wire:target="addToBag({{ $product->id }})">{{ __('ui.store.add_to_bag') }}</span>
                                            <span wire:loading wire:target="addToBag({{ $product->id }})">{{ __('ui.store.adding') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-20">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>
