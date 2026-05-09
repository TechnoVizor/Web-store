@extends('layouts.app')

@section('content')
    <style>
        .scan-line {
            width: 100%;
            height: 2px;
            background: rgba(255, 255, 255, 0.2);
            position: absolute;
            top: 0;
            left: 0;
            animation: scan 4s linear infinite;
            z-index: 20;
        }

        @keyframes scan {
            0% {
                top: 0;
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                top: 100%;
                opacity: 0;
            }
        }
    </style>

    <main class="container mx-auto px-6 py-12 lg:py-24 max-w-7xl">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">

            <div class="lg:col-span-7 space-y-6">
                <div class="relative aspect-[4/5] bg-[#0a0a0a] border border-white/10 overflow-hidden group">
                    <div class="scan-line"></div>
                    <img src="{{ $product->image_url }}"
                        class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-all duration-1000">

                    <div class="absolute top-6 left-6 mono text-[8px] text-white/40 space-y-1">
                        <p>COORD_X: 40.7128</p>
                        <p>COORD_Y: 74.0060</p>
                    </div>
                    <div class="absolute bottom-6 right-6 border border-white/20 p-2 backdrop-blur-md">
                        <div class="w-12 h-12 border-2 border-white/10 flex items-center justify-center">
                            <span class="mono text-[8px]">100%</span>
                        </div>
                    </div>
                </div>
            </div>


            <div class="lg:col-span-5 lg:sticky lg:top-32">
                <div class="border-l-2 border-white pl-8 mb-10">
                    <h1 class="text-5xl font-bold uppercase tracking-tighter mb-4 leading-none">
                        {{ $product->name }} @auth
    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="inline-block">
        @csrf
        <button type="submit" class="transition-transform hover:scale-110 flex items-center justify-center focus:outline-none">
            <svg class="w-6 h-6 transition-colors {{ auth()->user()->wishlists->contains($product->id) ? 'fill-white text-white' : 'text-white/40 hover:text-white' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        </button>
    </form>
@else
    <a href="{{ route('login') }}" class="inline-block transition-transform hover:scale-110 focus:outline-none">
        <svg class="w-6 h-6 text-white/40 hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
    </a>
@endauth
                    </h1>
                    
                    <div class="flex items-center space-x-4">
                        <span class="mono text-xs text-white/40 uppercase tracking-widest">
                            Category: {{ $product->category->name ?? 'None' }}
                        </span>
                        <span class="w-1 h-1 bg-white/20 rounded-full"></span>
                        <span class="mono text-xs text-white/40 uppercase tracking-widest">
                            Status: Available
                        </span>
                    </div>
                </div>

                <div class="mb-12 space-y-6">
                    <div class="flex items-baseline space-x-4">
                        <span
                            class="text-4xl font-bold tracking-tighter">${{ number_format($product->price, 0) }}</span>
                        <span class="mono text-[10px] text-white/20 uppercase tracking-widest">Tax_Included</span>
                    </div>

                    <p class="text-white/50 text-sm leading-relaxed font-light max-w-md italic">
                        {{ $product->description ?: 'Engineering excellence integrated into every fiber. Minimalist aesthetic paired with maximum performance for high-stakes environments.' }}
                    </p>
                </div>

                <div class="space-y-8 p-8 bg-[#0a0a0a] border border-white/10 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-2 mono text-[7px] text-white/10 uppercase">Security_Protocol_V4
                    </div>

                    <livewire:add-to-cart :product-id="$product->id" />

                    <div class="grid grid-cols-2 gap-y-6 gap-x-4 pt-8 border-t border-white/5">
                        <div class="space-y-1">
                            <p class="mono text-[8px] text-white/30 uppercase tracking-widest leading-none">Materials
                            </p>
                            <p class="text-[10px] font-bold uppercase leading-none italic">Technical_Composite</p>
                        </div>
                        <div class="space-y-1">
                            <p class="mono text-[8px] text-white/30 uppercase tracking-widest leading-none">Shipping</p>
                            <p class="text-[10px] font-bold uppercase leading-none italic">Global_Priority</p>
                        </div>
                        <div class="space-y-1">
                            <p class="mono text-[8px] text-white/30 uppercase tracking-widest leading-none">Warranty</p>
                            <p class="text-[10px] font-bold uppercase leading-none italic">2_Year_Limited</p>
                        </div>
                        <div class="space-y-1 text-right flex flex-col justify-end">
                            <div class="flex justify-end space-x-1">
                                <div class="w-2 h-2 bg-white"></div>
                                <div class="w-2 h-2 bg-white/40"></div>
                                <div class="w-2 h-2 bg-white/10"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 p-4 border border-dashed border-white/10 opacity-20">
                    <div class="mono text-[7px] space-y-1 uppercase tracking-widest leading-none">
                        <p>> Initializing product_view_mode...</p>
                        <p>> Assets loaded successfully.</p>
                        <p>> System ready for transaction.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-20 text-center border-t border-white/5 mt-20">
        <p class="mono text-[8px] uppercase tracking-[0.8em] text-white/20">Digi // INDUSTRIAL // 2026</p>
    </footer>
@endsection
