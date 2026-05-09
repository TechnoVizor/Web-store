@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-black pt-20 pb-10">
    <div class="container mx-auto px-6">
        
        <div class="flex flex-col mb-12">
            <h1 class="text-3xl font-black tracking-tighter uppercase mb-2">
                SAVED<span class="text-white/20">_</span>ITEMS
            </h1>
            <div class="flex items-center space-x-4 text-[10px] tracking-[0.3em] text-white/30 uppercase">
                <span class="mono">User: {{ auth()->user()->name }}</span>
                <span class="w-1 h-1 bg-white/10"></span>
                <span class="mono">Total: {{ $products->count() }} Units</span>
            </div>
        </div>

        @if($products->isEmpty())
            <div class="py-40 border border-dashed border-white/5 flex flex-col items-center justify-center space-y-6">
                <div class="text-[10px] tracking-[0.5em] text-white/20 uppercase font-bold italic">
                    Database_Empty // No_Selection_Detected
                </div>
                <a href="/" class="ui-btn ui-btn-primary px-8 py-3 text-[10px] font-black tracking-[0.3em]">
                    Return to Store
                </a>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-8">
                @foreach($products as $product)
                    <div class="product-card flex flex-col group border border-white/5 bg-[#0a0a0a]">
                        
                        <div class="relative aspect-[4/5] overflow-hidden bg-black">
                            <a href="{{ route('product.show', $product->slug) }}" class="block w-full h-full">
                                <img src="{{ $product->image_url }}" 
                                     class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition-all duration-700">
                            </a>

                            <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-2 right-2">
                                @csrf
                                <button type="submit" class="ui-btn ui-btn-icon ui-btn-danger bg-black/60 backdrop-blur-md text-white/40">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </form>

                            <div class="absolute bottom-0 left-0 p-2">
                                <span class="mono text-[8px] text-white/20 uppercase bg-black/40 px-1">Ref_{{ $product->id }}</span>
                            </div>
                        </div>

                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="text-[10px] font-bold uppercase tracking-widest mb-1">{{ $product->name }}</h3>
                            <p class="mono text-[9px] text-white/20 uppercase mb-4">{{ $product->category->name }}</p>
                            
                            <div class="mt-auto flex items-center justify-between">
                                <span class="text-xs font-bold mono">${{ number_format($product->price, 0) }}</span>
                                <a href="{{ route('product.show', $product->slug) }}" class="text-[9px] font-black tracking-tighter uppercase border-b border-white/20 hover:border-white transition-all">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
    
</div>
@endsection
