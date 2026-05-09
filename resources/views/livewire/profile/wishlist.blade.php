<section class="mt-16 border-t border-white/5 pt-12">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
        <div class="border-l-2 border-white pl-4">
            <h2 class="text-xs font-bold uppercase tracking-[0.4em]">Saved_Items</h2>
            <span class="mono text-[10px] text-white/40 uppercase">TOTAL_UNITS: {{ $products->count() }}</span>
        </div>

        <a href="{{ route('wishlist.index') }}" wire:navigate
            class="mono text-[9px] text-white/30 hover:text-white uppercase tracking-[0.25em] transition-colors">
            Open_Full_Archive
        </a>
    </div>

    @if($products->isEmpty())
        <div class="py-16 border border-dashed border-white/5 bg-white/[0.02] text-center">
            <p class="mono text-[10px] text-white/20 uppercase tracking-[0.4em]">No_Saved_Units</p>
            <a href="{{ route('home') }}" wire:navigate
                class="inline-block mt-6 px-8 py-3 border border-white/20 text-[9px] font-bold uppercase tracking-[0.25em] hover:bg-white hover:text-black transition-all">
                Browse_Store
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($products as $product)
                <article wire:key="profile-wish-{{ $product->id }}"
                    class="grid grid-cols-[88px_1fr] gap-4 border border-white/5 bg-[#0a0a0a] p-3 min-h-28">
                    <a href="{{ route('product.show', $product->slug) }}" wire:navigate
                        class="block aspect-[4/5] bg-black overflow-hidden">
                        <img src="{{ $product->image_url }}"
                            alt="{{ $product->name }}"
                            width="320"
                            height="400"
                            loading="lazy"
                            decoding="async"
                            class="w-full h-full object-cover opacity-70 hover:opacity-100 transition-opacity">
                    </a>

                    <div class="min-w-0 flex flex-col">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h3 class="text-[11px] font-bold uppercase tracking-wider text-white/80 truncate">
                                    {{ $product->name }}
                                </h3>
                                <p class="mono text-[8px] text-white/25 uppercase mt-1 truncate">
                                    {{ $product->category->name ?? 'Unclassified' }}
                                </p>
                            </div>

                            <button wire:click="remove({{ $product->id }})"
                                wire:loading.attr="disabled"
                                wire:target="remove({{ $product->id }})"
                                class="shrink-0 text-white/25 hover:text-red-400 transition-colors"
                                aria-label="Remove from wishlist">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="mt-auto flex items-center justify-between gap-3 pt-4">
                            <span class="mono text-xs font-bold">${{ number_format($product->price, 0) }}</span>
                            <button wire:click="addToBag({{ $product->id }})"
                                wire:loading.attr="disabled"
                                wire:target="addToBag({{ $product->id }})"
                                class="px-3 py-2 border border-white/15 text-[8px] font-bold uppercase tracking-[0.2em] hover:bg-white hover:text-black disabled:opacity-50 transition-all">
                                <span wire:loading.remove wire:target="addToBag({{ $product->id }})">Add</span>
                                <span wire:loading wire:target="addToBag({{ $product->id }})">...</span>
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>
