<section class="mt-16 border-t border-white/5 pt-12">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
        <div class="border-l-2 border-white pl-4">
            <h2 class="text-xs font-bold uppercase tracking-[0.4em]">{{ __('ui.profile.saved_items') }}</h2>
            <span class="mono text-[10px] text-white/40 uppercase">{{ __('ui.store.total_units') }}: {{ $products->count() }}</span>
        </div>

        <a href="{{ route('wishlist.index') }}" wire:navigate
            class="ui-btn ui-btn-compact mono text-[9px] tracking-[0.25em]">
            {{ __('ui.profile.open_wishlist') }}
        </a>
    </div>

    @if($products->isEmpty())
        <div class="py-16 border border-dashed border-white/5 bg-white/[0.02] text-center">
            <p class="mono text-[10px] text-white/20 uppercase tracking-[0.4em]">{{ __('ui.profile.no_saved') }}</p>
            <a href="{{ route('home') }}" wire:navigate
                class="ui-btn ui-btn-primary mt-6 px-8 py-3 text-[9px] font-bold tracking-[0.25em]">
                {{ __('ui.profile.browse_store') }}
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
                                class="ui-btn ui-btn-icon ui-btn-danger shrink-0 text-white/35"
                                aria-label="{{ __('ui.profile.remove') }}">
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
                                class="ui-btn ui-btn-primary px-3 py-2 text-[8px] font-bold tracking-[0.2em]">
                                <span wire:loading.remove wire:target="addToBag({{ $product->id }})">{{ __('ui.profile.add') }}</span>
                                <span wire:loading wire:target="addToBag({{ $product->id }})">...</span>
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>
