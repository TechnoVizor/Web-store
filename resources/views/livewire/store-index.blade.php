<div>
    {{-- Вставляем твои уникальные стили --}}
<style>
        .mono { font-family: 'JetBrains Mono', monospace; }
        .glass { background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(20px) saturate(180%); }
        .product-card { background: #0a0a0a; border: 1px solid #1a1a1a; transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
        .product-card:hover { border-color: rgba(255, 255, 255, 0.4); background: #111; }
        
        .skeleton-shimmer {
            background: linear-gradient(90deg, #050505 25%, #111111 50%, #050505 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite linear;
        }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }

        .filter-input { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); color: white; outline: none; transition: all 0.3s; }
        .filter-input:focus { border-color: white; background: rgba(255,255,255,0.07); }
        .cat-btn { font-size: 9px; padding: 6px 12px; border: 1px solid rgba(255,255,255,0.1); transition: all 0.3s; text-transform: uppercase; letter-spacing: 0.1em; }
        .cat-btn.active { background: white; color: black; border-color: white;


        .loading-fade { transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1); }


    }
    </style>

<header class="py-24 border-b border-white/5">
    <div class="container mx-auto px-6 text-center" 
         x-data="{ 
            status: '', 
            titlePart1: '', 
            titlePart2: '', 
            desc: '',
            fullStatus: 'Status: Production_Ready',
            fullPart1: 'Precision ',
            fullPart2: 'Equipment',
            fullDesc: 'Advanced materials engineering meets minimalist industrial aesthetics.'
         }"
         x-init="
            {{-- 1. Статус --}}
            let sCount = 0;
            let sInterval = setInterval(() => {
                if(sCount < fullStatus.length) { status += fullStatus[sCount]; sCount++; }
                else clearInterval(sInterval);
            }, 30);

            {{-- 2. Precision --}}
            setTimeout(() => {
                let t1Count = 0;
                let t1Interval = setInterval(() => {
                    if(t1Count < fullPart1.length) { titlePart1 += fullPart1[t1Count]; t1Count++; }
                    else clearInterval(t1Interval);
                }, 60);
            }, 500);

            {{-- 3. Equipment --}}
            setTimeout(() => {
                let t2Count = 0;
                let t2Interval = setInterval(() => {
                    if(t2Count < fullPart2.length) { titlePart2 += fullPart2[t2Count]; t2Count++; }
                    else clearInterval(t2Interval);
                }, 60);
            }, 1200);

            {{-- 4. Описание --}}
            setTimeout(() => {
                let dCount = 0;
                let dInterval = setInterval(() => {
                    if(dCount < fullDesc.length) { desc += fullDesc[dCount]; dCount++; }
                    else clearInterval(dInterval);
                }, 20);
            }, 2000);
         ">
        
        {{-- Бадж статуса --}}
        <div class="inline-block px-3 py-1 border border-white/10 rounded-full mb-6">
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
    color: #fff;
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
    <main class="container mx-auto px-4 py-20 relative min-h-[800px]">
        
        {{-- ПАНЕЛЬ УПРАВЛЕНИЯ (Всегда видна) --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
            <div class="border-l-2 border-white pl-4">
                <h2 class="text-xs font-bold uppercase tracking-[0.4em]">Inventory</h2>
                <span class="mono text-[10px] text-white/40 uppercase">TOTAL_UNITS: {{ $products->total() }}</span>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button wire:click="resetFilters" class="mono text-[9px] text-white/20 hover:text-white uppercase tracking-widest transition-colors mr-4">
                    [ Reset_All ]
                </button>
                @foreach($categories as $category)
                    <button wire:click="selectCategory({{ $category->id }})" 
                            class="cat-btn mono {{ $selectedCategory == $category->id ? 'active' : '' }}">
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
                        <p class="mono text-[10px] text-white/20 uppercase tracking-[0.5em]">No_Units_Found_In_Sector</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-6">
                        @foreach($products as $product)
                            <div class="product-card flex flex-col group h-full" wire:key="p-{{ $product->id }}">
                                
                                {{-- Картинка --}}
                                <div class="relative aspect-[4/5] overflow-hidden bg-black" 
                                     x-data="{ loaded: false }" 
                                     x-init="$nextTick(() => { if ($refs.img && $refs.img.complete) loaded = true; }); setTimeout(() => { loaded = true; }, 3000);">
                                    
                                    <div x-show="!loaded" class="absolute inset-0 skeleton-shimmer z-10 transition-opacity duration-500" x-transition:leave="opacity-0"></div>
                                    
                                    <a href="{{ route('product.show', $product->slug) }}" wire:navigate.hover class="block w-full h-full">
                                        <img x-ref="img"
                                             src="{{ asset('storage/' . $product->image) }}"
                                             @load="loaded = true"
                                             x-on:error="loaded = true"
                                             class="w-full h-full object-cover transition-all duration-700"
                                             :class="loaded ? 'opacity-70 group-hover:opacity-100 group-hover:scale-105' : 'opacity-0'">
                                    </a>
                                    
                                    {{-- Wishlist --}}
                                    <div class="absolute top-2 right-2 z-20">
                                        @if(auth()->check())
                                           <div class="absolute top-2 right-2 z-20">
    <livewire:wishlist-toggle :product-id="$product->id" :key="'wish-'.$product->id" />
</div>
                                        @else
                                            <a href="/login" wire:navigate.hover class="p-1.5 bg-black/40 backdrop-blur-md border border-white/5 block text-white/20">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>

                                    <div class="absolute top-2 left-2 mono text-[7px] text-white/20 bg-black/50 px-1 py-0.5 border border-white/5 uppercase">
                                        Ref_{{ $product->id }}
                                    </div>
                                </div>

                                {{-- Данные --}}
                                <div class="p-5 flex flex-col flex-grow">
                                    <h3 class="text-xs font-bold uppercase tracking-wider mb-1 leading-tight text-white/80">{{ $product->name }}</h3>
                                    <p class="mono text-[9px] text-white/30 uppercase italic mb-4">{{ $product->category->name }}</p>
                                    <div class="mt-auto">
                                        <span class="text-sm font-bold mono block mb-4">${{ number_format($product->price, 0) }}</span>
                                        <livewire:add-to-cart :product-id="$product->id" :key="'add-' . $product->id" />
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