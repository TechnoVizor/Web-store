@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-6xl px-6 py-20" aria-labelledby="orders-title">
    <div class="mb-10 grid gap-6 border-b border-white/8 pb-8 md:grid-cols-[1fr_auto] md:items-end">
        <div class="border-l border-white/50 pl-6">
            <p class="mono mb-2 text-[10px] uppercase tracking-[0.34em] text-white/35">{{ __('ui.orders.registry') }}</p>
            <h1 id="orders-title" class="text-3xl font-black uppercase tracking-tight text-white/80 md:text-4xl">{{ __('ui.orders.title') }}</h1>
            <p class="mono mt-2 text-[10px] uppercase tracking-[0.24em] text-white/45">{{ __('ui.orders.subtitle', ['name' => auth()->user()->name]) }}</p>
        </div>

        <div class="border border-white/10 bg-[#070707] px-5 py-4">
            <p class="mono text-[10px] uppercase tracking-[0.24em] text-white/35">{{ __('ui.orders.total_orders') }}</p>
            <p class="mt-1 text-2xl font-black text-white/80">{{ $orders->total() }}</p>
        </div>
    </div>

    <div class="space-y-4" role="list" aria-label="{{ __('ui.orders.title') }}">
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

            <article class="border border-white/10 bg-[#060606] transition-colors hover:border-white/20" role="listitem" aria-labelledby="order-{{ $order->id }}-title">
                <div class="grid gap-4 border-b border-white/8 p-4 md:grid-cols-[1fr_auto_auto] md:items-center md:p-5">
                    <div class="min-w-0">
                        <div class="mb-2 flex flex-wrap items-center gap-x-4 gap-y-2">
                            <span id="order-{{ $order->id }}-title" class="mono text-sm font-black uppercase tracking-[0.18em] text-white/80">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                            <time datetime="{{ $order->created_at->toIso8601String() }}" class="mono text-[10px] uppercase tracking-[0.18em] text-white/45">
                                {{ $order->created_at->timezone(config('app.timezone'))->format('d.m.Y H:i') }}
                            </time>
                        </div>
                        <p class="mono text-[10px] uppercase tracking-[0.2em] text-white/35">
                            {{ $order->items->sum('quantity') }} {{ __('ui.orders.units_total') }}
                        </p>
                    </div>

                    <div class="inline-flex w-fit border px-3 py-2 mono text-[10px] uppercase tracking-[0.2em] {{ $statusClass }}">
                        {{ __('ui.status.' . $order->status) }}
                    </div>

                    <div class="md:text-right">
                        <p class="mono text-[10px] uppercase tracking-[0.22em] text-white/35">{{ __('ui.orders.amount') }}</p>
                        <p class="text-xl font-black text-white/80">${{ number_format($order->total_amount, 2) }}</p>
                    </div>
                </div>

                <div class="divide-y divide-white/8">
                    @foreach($order->items as $item)
                        <div class="grid grid-cols-[52px_1fr_auto] items-center gap-4 p-4 md:p-5">
                            @if($item->product && $item->product->image)
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="h-[52px] w-[52px] object-cover opacity-80">
                            @else
                                <div class="flex h-[52px] w-[52px] items-center justify-center border border-white/10 bg-black text-[8px] text-white/30" aria-hidden="true">NO_IMG</div>
                            @endif

                            <div class="min-w-0">
                                <a href="{{ $item->product ? route('product.show', $item->product->slug) : '#' }}" class="block truncate text-sm font-bold uppercase tracking-[0.12em] text-white/78 transition-colors hover:text-white focus:outline-none focus-visible:underline">
                                    {{ $item->product ? $item->product->name : __('ui.product.unknown') }}
                                </a>
                                <p class="mono mt-1 text-[10px] uppercase tracking-[0.16em] text-white/38">{{ $item->quantity }} {{ __('ui.cart.units') }}</p>
                            </div>

                            <div class="text-right">
                                <p class="mono text-sm font-bold text-white/72">${{ number_format($item->price, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>
        @empty
            <div class="border border-dashed border-white/10 bg-[#060606] py-24 text-center">
                <p class="mono text-[10px] uppercase tracking-[0.45em] text-white/45">{{ __('ui.orders.empty') }}</p>
                <a href="{{ route('home') }}" wire:navigate class="ui-btn ui-btn-primary mt-8 px-8 py-3 text-[10px] font-bold tracking-[0.25em]">
                    {{ __('ui.orders.return_home') }}
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $orders->links() }}
    </div>
</div>
@endsection
