@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-24 max-w-5xl">
    <div class="mb-16 border-l-2 border-white pl-6">
        <h1 class="text-3xl font-bold uppercase tracking-tighter text-white">{{ __('ui.orders.title') }}</h1>
        <p class="mono text-[10px] text-white/30 uppercase tracking-[0.3em] mt-2">{{ __('ui.orders.subtitle', ['name' => auth()->user()->name]) }}</p>
    </div>

    <div class="space-y-6">
        @forelse($orders as $order)
            <div class="bg-white/[0.02] border border-white/5 p-6 hover:border-white/20 transition-all group relative overflow-hidden">
                
                {{-- Цветной индикатор статуса слева --}}
                <div class="absolute top-0 left-0 w-1 h-full 
                    @if($order->status == 'pending') bg-yellow-500 
                    @elseif($order->status == 'processing') bg-blue-500 
                    @elseif($order->status == 'shipped') bg-purple-500 
                    @elseif($order->status == 'delivered') bg-green-500 
                    @else bg-red-500 @endif">
                </div>

                {{-- Шапка заказа --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 border-b border-white/5 pb-6 pl-4">
                    <div class="flex items-center space-x-6">
                        <span class="mono text-[10px] text-white/20 uppercase">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                        <div>
                            <p class="text-xs font-bold text-white uppercase">{{ $order->created_at->timezone(config('app.timezone'))->format('d.m.Y // H:i') }}</p>
                            <p class="mono text-[9px] text-white/30 uppercase mt-1">{{ $order->items->sum('quantity') }} {{ __('ui.orders.units_total') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-8">
                        <div class="text-right">
                            <p class="mono text-[9px] text-white/30 uppercase mb-1">{{ __('ui.orders.amount') }}</p>
                            <p class="mono text-sm font-bold text-white">${{ number_format($order->total_amount, 2) }}</p>
                        </div>
                        
                        {{-- Статус с цветовой подсветкой --}}
                        <div class="px-3 py-1 border text-[8px] mono uppercase tracking-widest
                            @if($order->status == 'pending') border-yellow-500/30 text-yellow-500 bg-yellow-500/10
                            @elseif($order->status == 'processing') border-blue-500/30 text-blue-500 bg-blue-500/10
                            @elseif($order->status == 'shipped') border-purple-500/30 text-purple-500 bg-purple-500/10
                            @elseif($order->status == 'delivered') border-green-500/30 text-green-500 bg-green-500/10
                            @else border-red-500/30 text-red-500 bg-red-500/10 @endif">
                            {{ __('ui.status.' . $order->status) }}
                        </div>
                    </div>
                </div>

                {{-- Список товаров в заказе --}}
                <div class="space-y-4 pl-4">
                    @foreach($order->items as $item)
                    <div class="flex items-center space-x-4">
                        {{-- Фото --}}
                        @if($item->product && $item->product->image)
                            <img src="{{ $item->product->image_url }}" class="w-12 h-12 object-cover border border-white/10">
                        @else
                            <div class="w-12 h-12 bg-black/50 border border-white/10 flex items-center justify-center text-[8px] text-white/20">NO_IMG</div>
                        @endif
                        
                        {{-- Название и кол-во --}}
                        <div class="flex-1">
                            <div class="text-xs font-bold text-white uppercase tracking-widest">
                                <a href="{{ $item->product ? route('product.show', $item->product->slug) : '#' }}" class="hover:text-gray-400 transition-colors">
                                    {{ $item->product ? $item->product->name : __('ui.product.unknown') }}
                                </a>
                            </div>
                            <div class="mono text-[9px] text-white/40 uppercase mt-1">{{ $item->quantity }} {{ __('ui.cart.units') }}</div>
                        </div>
                        
                        {{-- Цена за единицу --}}
                        <div class="mono text-xs text-white/60">
                            ${{ number_format($item->price, 2) }}
                        </div>
                    </div>
                    @endforeach
                </div>
                
                {{-- Если нужна кнопка для перехода на отдельную страницу заказа --}}
                {{-- <div class="mt-6 pt-4 text-right pl-4">
                    <a href="{{ route('orders.show', $order->id) }}" wire:navigate class="mono text-[10px] text-white/50 hover:text-white uppercase tracking-widest">> View_Full_Details</a>
                </div> --}}

            </div>
        @empty
            <div class="py-20 text-center border border-dashed border-white/5">
                <p class="mono text-[10px] text-white/20 uppercase tracking-[0.5em]">{{ __('ui.orders.empty') }}</p>
            </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $orders->links() }}
    </div>
</div>
@endsection
