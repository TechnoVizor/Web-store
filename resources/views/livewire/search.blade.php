<?php

use Livewire\Volt\Component;
use App\Models\Product;

new class extends Component {
    public $query = '';
    public $isOpen = false;

    public function updatedQuery()
    {
        // Поиск срабатывает при каждом вводе символа
    }

    public function getResultsProperty()
    {
        if (strlen($this->query) < 2) return [];

        return Product::query()
            ->with('category:id,name')
            ->select(['id', 'category_id', 'name', 'slug', 'description', 'price', 'image'])
            ->where('name', 'like', '%' . $this->query . '%')
            ->orWhere('description', 'like', '%' . $this->query . '%')
            ->take(5)
            ->get();
    }
}; ?>

<div x-data="{ open: @entangle('isOpen') }" 
     @keydown.window.slash.prevent="open = true" 
     @keydown.window.escape="open = false">
    
    <button @click="open = true" class="text-white/50 hover:text-white transition uppercase text-[10px] tracking-widest">
        Search
    </button>

    <div x-show="open" 
         x-transition.opacity
         class="fixed inset-0 z-[100] bg-black/90 backdrop-blur-xl flex items-start justify-center pt-20 px-6"
         style="display: none;">
        
        <div @click.away="open = false" class="w-full max-w-2xl bg-[#0a0a0a] border border-white/10 shadow-2xl">
            <div class="p-6 border-b border-white/10 flex items-center">
                <span class="mr-4 text-white/30 text-sm">/</span>
                <input type="text" 
                       wire:model.live.debounce.300ms="query"
                       x-init="$watch('open', value => { if(value) $el.focus() })"
                       class="w-full bg-transparent text-xl outline-none placeholder:text-white/10" 
                       placeholder="Начните вводить название товара...">
                <button @click="open = false" class="text-white/20 hover:text-white text-xs">ЗАКРЫТЬ</button>
            </div>

            <div class="max-h-[60vh] overflow-y-auto">
                @if(count($this->results) > 0)
                    @foreach($this->results as $product)
                        <a href="{{ route('product.show', $product->slug) }}" 
                           class="flex items-center p-4 hover:bg-white/5 transition group border-b border-white/5 last:border-0">
                            <img src="{{ $product->image_url }}" class="w-12 h-12 object-cover grayscale group-hover:grayscale-0 transition">
                            <div class="ml-4">
                                <div class="text-sm font-bold uppercase tracking-tight">{{ $product->name }}</div>
                                <div class="text-[10px] text-white/40">{{ $product->category->name }} — ${{ number_format($product->price, 0) }}</div>
                            </div>
                        </a>
                    @endforeach
                @elseif(strlen($query) > 1)
                    <div class="p-10 text-center text-white/20 text-xs uppercase tracking-widest">
                        Ничего не найдено
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
