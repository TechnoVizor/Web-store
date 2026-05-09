<section class="profile-frame mt-12 p-5 sm:p-8">
    <div class="mb-8 flex flex-col gap-4 border-b border-white/10 pb-8 sm:flex-row sm:items-end sm:justify-between">
        <div class="border-l border-white/60 pl-4">
            <p class="mono mb-2 text-[10px] uppercase tracking-[0.36em] text-white/30">{{ __('ui.store.total_units') }}: {{ $products->count() }}</p>
            <h2 class="text-xl font-black uppercase tracking-tight">{{ __('ui.profile.saved_items') }}</h2>
        </div>

        <a href="{{ route('wishlist.index') }}" wire:navigate
            class="ui-btn ui-btn-compact mono text-[10px] tracking-[0.25em]">
            {{ __('ui.profile.open_wishlist') }}
        </a>
    </div>

    @if($products->isEmpty())
        <div class="border border-dashed border-white/10 bg-white/[0.018] px-6 py-16 text-center">
            <p class="mono text-[10px] uppercase tracking-[0.4em] text-white/30">{{ __('ui.profile.no_saved') }}</p>
            <a href="{{ route('home') }}" wire:navigate
                class="ui-btn ui-btn-primary mt-6 px-8 py-3 text-[10px] font-bold tracking-[0.25em]">
                {{ __('ui.profile.browse_store') }}
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach($products as $product)
                <article wire:key="profile-wish-{{ $product->id }}"
                    class="group grid min-h-32 grid-cols-[96px_1fr] gap-4 border border-white/10 bg-white/[0.018] p-3 transition duration-200 hover:border-white/20 hover:bg-white/[0.04]">
                    <a href="{{ route('product.show', $product->slug) }}" wire:navigate
                        class="block aspect-[4/5] overflow-hidden bg-black">
                        <img src="{{ $product->image_url }}"
                            alt="{{ $product->name }}"
                            width="320"
                            height="400"
                            loading="lazy"
                            decoding="async"
                            class="h-full w-full object-cover opacity-75 transition duration-300 group-hover:scale-[1.03] group-hover:opacity-100">
                    </a>

                    <div class="flex min-w-0 flex-col">
                        <div class="flex items-start justify-between gap-3 border-b border-white/10 pb-3">
                            <div class="min-w-0">
                                <h3 class="truncate text-sm font-bold uppercase tracking-wide text-white/80">
                                    {{ $product->name }}
                                </h3>
                                <p class="mono mt-1 truncate text-[10px] uppercase tracking-[0.18em] text-white/30">
                                    {{ $product->category->name ?? 'Unclassified' }}
                                </p>
                            </div>

                            <button type="button"
                                wire:click.prevent="remove({{ $product->id }})"
                                wire:loading.attr="disabled"
                                wire:target="remove({{ $product->id }})"
                                class="ui-btn ui-btn-icon ui-btn-danger shrink-0 text-white/40"
                                aria-label="{{ __('ui.profile.remove') }}">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="mt-auto flex items-center justify-between gap-3 pt-4">
                            <span class="mono text-sm font-bold text-white/80">${{ number_format($product->price, 0) }}</span>
                            <button type="button"
                                wire:click.prevent="addToBag({{ $product->id }})"
                                wire:loading.attr="disabled"
                                wire:target="addToBag({{ $product->id }})"
                                class="ui-btn px-3 py-2 text-[10px] font-bold tracking-[0.22em]">
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
