@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-24 max-w-6xl" aria-labelledby="orders-title">
    <div class="mb-14 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
        <div class="border-l-2 border-white pl-6">
            <p class="mono text-[9px] uppercase tracking-[0.35em] text-white/35 mb-2">{{ __('ui.orders.registry') }}</p>
            <h1 id="orders-title" class="text-3xl md:text-4xl font-black uppercase tracking-tight text-white">{{ __('ui.orders.title') }}</h1>
            <p class="mono text-[10px] text-white/50 uppercase tracking-[0.3em] mt-2">{{ __('ui.orders.subtitle', ['name' => auth()->user()->name]) }}</p>
        </div>

        <div class="border border-white/10 bg-white/[0.025] px-4 py-3 text-right">
            <p class="mono text-[8px] uppercase tracking-[0.28em] text-white/28">{{ __('ui.orders.total_orders') }}</p>
            <p class="mono text-xl font-black text-white/85">{{ str_pad($orders->total(), 2, '0', STR_PAD_LEFT) }}</p>
        </div>
    </div>

    <div class="space-y-5" role="list" aria-label="{{ __('ui.orders.title') }}">
        @forelse($orders as $order)
            @php
                $statusClass = match ($order->status) {
                    'pending' => 'border-yellow-500/35 text-yellow-300 bg-yellow-500/8',
                    'processing' => 'border-blue-500/35 text-blue-300 bg-blue-500/8',
                    'shipped' => 'border-purple-500/35 text-purple-300 bg-purple-500/8',
                    'delivered' => 'border-green-500/35 text-green-300 bg-green-500/8',
                    'paid' => 'border-cyan-500/35 text-cyan-300 bg-cyan-500/8',
                    default => 'border-red-500/35 text-red-300 bg-red-500/8',
                };
            @endphp

            <article class="product-card p-5 md:p-6" role="listitem" aria-labelledby="order-{{ $order->id }}-title">
                <div class="grid gap-6 lg:grid-cols-[1fr_auto] lg:items-start">
                    <div>
                        <div class="mb-5 flex flex-col gap-4 border-b border-white/6 pb-5 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <div class="mb-2 flex items-center gap-3">
                                    <span id="order-{{ $order->id }}-title" class="mono text-[10px] uppercase tracking-[0.25em] text-white/50">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    <span class="h-px w-8 bg-white/10"></span>
                                    <time datetime="{{ $order->created_at->toIso8601String() }}" class="mono text-[9px] uppercase tracking-[0.2em] text-white/38">
                                        {{ $order->created_at->timezone(config('app.timezone'))->format('d.m.Y // H:i') }}
                                    </time>
                                </div>
                                <p class="mono text-[9px] uppercase tracking-[0.25em] text-white/32">
                                    {{ $order->items->sum('quantity') }} {{ __('ui.orders.units_total') }}
                                </p>
                            </div>

                            <div class="flex items-center gap-3 sm:justify-end">
                                <div aria-label="{{ __('ui.orders.status') }}: {{ __('ui.status.' . $order->status) }}" class="border px-3 py-2 text-[8px] mono uppercase tracking-[0.22em] {{ $statusClass }}">
                                    {{ __('ui.status.' . $order->status) }}
                                </div>
                                <div class="border border-white/10 bg-black/30 px-4 py-2 text-right">
                                    <p class="mono text-[8px] uppercase tracking-[0.25em] text-white/32">{{ __('ui.orders.amount') }}</p>
                                    <p class="mono text-base font-black text-white/86">${{ number_format($order->total_amount, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-3">
                            @foreach($order->items as $item)
                                <div class="group/item grid grid-cols-[56px_1fr_auto] items-center gap-4 border border-white/6 bg-white/[0.015] p-3 transition-colors hover:border-white/14 hover:bg-white/[0.035]">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="h-14 w-14 object-cover opacity-75 transition-opacity group-hover/item:opacity-100">
                                    @else
                                        <div class="h-14 w-14 border border-white/10 bg-black/50 flex items-center justify-center text-[8px] text-white/35" aria-hidden="true">NO_IMG</div>
                                    @endif

                                    <div class="min-w-0">
                                        <a href="{{ $item->product ? route('product.show', $item->product->slug) : '#' }}" class="block truncate text-[11px] font-bold uppercase tracking-[0.16em] text-white/82 transition-colors hover:text-white focus:outline-none focus-visible:underline">
                                            {{ $item->product ? $item->product->name : __('ui.product.unknown') }}
                                        </a>
                                        <p class="mono mt-1 text-[9px] uppercase tracking-[0.18em] text-white/38">{{ $item->quantity }} {{ __('ui.cart.units') }}</p>
                                    </div>

                                    <div class="mono text-xs font-bold text-white/62">${{ number_format($item->price, 2) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="py-24 text-center border border-dashed border-white/8 bg-white/[0.015]">
                <p class="mono text-[10px] text-white/45 uppercase tracking-[0.5em]">{{ __('ui.orders.empty') }}</p>
                <a href="{{ route('home') }}" wire:navigate class="ui-btn ui-btn-primary mt-8 px-8 py-3 text-[9px] font-bold tracking-[0.25em]">
                    {{ __('ui.orders.return_home') }}
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $orders->links() }}
    </div>
</div>
@endsection
