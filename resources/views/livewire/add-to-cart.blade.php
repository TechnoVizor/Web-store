<?php

use App\Models\Product;
use Livewire\Volt\Component;

new class extends Component
{
    public $productId;

    public string $selectedSize = '';

    public function mount(): void
    {
        $product = Product::query()
            ->select(['id', 'sizes'])
            ->findOrFail($this->productId);

        $this->selectedSize = $product->availableSizes()[0];
    }

    public function addToBag()
    {
        $product = Product::query()
            ->select(['id', 'name', 'price', 'image', 'sizes'])
            ->where('is_active', true)
            ->findOrFail($this->productId);

        $size = $this->selectedSizeFor($product);
        $cartKey = $product->id.':'.$size;
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'size' => $size,
                'quantity' => 1,
                'price' => $product->price,
                'image' => $product->image_url,
            ];
        }

        session()->put('cart', $cart);

        // Опционально: можно добавить всплывающее уведомление
        $this->dispatch('cart-updated');
    }

    public function sizes(): array
    {
        return Product::query()
            ->select(['id', 'sizes'])
            ->findOrFail($this->productId)
            ->availableSizes();
    }

    private function selectedSizeFor(Product $product): string
    {
        $sizes = $product->availableSizes();
        $size = strtoupper(trim($this->selectedSize));

        return in_array($size, $sizes, true) ? $size : $sizes[0];
    }
}; ?>

<div class="space-y-5">
    <div>
        <div class="mb-3 flex items-center justify-between">
            <span class="mono text-[9px] uppercase tracking-[0.24em] text-white/35">{{ __('ui.product.size') }}</span>
            <span class="mono text-[9px] uppercase tracking-[0.18em] text-white/20">{{ __('ui.product.size_hint') }}</span>
        </div>
        <div class="grid grid-cols-5 gap-2">
            @foreach($this->sizes() as $size)
                <label class="group/size">
                    <input type="radio" wire:model.live="selectedSize" value="{{ $size }}" class="peer sr-only">
                    <span class="flex min-h-10 items-center justify-center border border-white/10 bg-black/40 mono text-[10px] font-bold uppercase tracking-[0.16em] text-white/45 transition-all group-hover/size:border-white/28 group-hover/size:text-white/78 peer-checked:border-white/70 peer-checked:bg-white/80 peer-checked:text-black">
                        {{ $size }}
                    </span>
                </label>
            @endforeach
        </div>
    </div>
    {{-- Удаляем <form>, оставляем только кнопку с wire:click --}}
        <button type="button" wire:click.prevent="addToBag" wire:loading.attr="disabled"
            class="ui-btn ui-btn-primary w-full py-3 sm:py-4 px-1 sm:px-4 text-[7px] sm:text-[10px] font-bold tracking-[0.2em] active:scale-[0.98] mono">

            {{-- Текст меняется при загрузке --}}
            <span wire:loading.remove>Add to bag</span>
            <span wire:loading>Adding...</span>

        </button>
        
</div>
