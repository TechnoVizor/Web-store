@extends('layouts.app')

@section('content')
@php
    $statusClass = match ($order->status) {
        'new' => 'border-blue-500/35 text-blue-300 bg-blue-500/8',
        'pending' => 'border-yellow-500/35 text-yellow-300 bg-yellow-500/8',
        'processing' => 'border-sky-500/35 text-sky-300 bg-sky-500/8',
        'paid' => 'border-green-500/35 text-green-300 bg-green-500/8',
        'shipped' => 'border-purple-500/35 text-purple-300 bg-purple-500/8',
        'delivered' => 'border-teal-500/35 text-teal-300 bg-teal-500/8',
        default => 'border-red-500/35 text-red-300 bg-red-500/8',
    };
@endphp

<main class="container mx-auto max-w-6xl px-6 py-20" aria-labelledby="order-title">
    <div class="mb-10 grid gap-6 border-b border-white/8 pb-8 md:grid-cols-[1fr_auto] md:items-end">
        <div class="border-l border-white/50 pl-6">
            <p class="mono mb-2 text-[10px] uppercase tracking-[0.34em] text-white/35">{{ __('ui.orders.registry') }}</p>
            <h1 id="order-title" class="text-3xl font-black uppercase tracking-tight text-white/80 md:text-4xl">
                #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
            </h1>
            <time datetime="{{ $order->created_at->toIso8601String() }}" class="mono mt-2 block text-[10px] uppercase tracking-[0.24em] text-white/45">
                {{ $order->created_at->timezone(config('app.timezone'))->format('d.m.Y H:i') }}
            </time>
        </div>

        <a href="{{ route('orders.index') }}" wire:navigate class="ui-btn ui-btn-compact mono text-[10px] tracking-[0.25em]">
            {{ __('ui.orders.view_history') }}
        </a>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
        <section class="border border-white/10 bg-[#060606]" aria-label="{{ __('ui.orders.title') }}">
            <div class="flex flex-col gap-4 border-b border-white/8 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="mono text-[10px] uppercase tracking-[0.24em] text-white/35">{{ __('ui.orders.status') }}</p>
                    <div class="mt-2 inline-flex border px-3 py-2 mono text-[10px] font-bold uppercase tracking-[0.2em] {{ $statusClass }}">
                        {{ __('ui.status.' . $order->status) }}
                    </div>
                </div>

                <div class="sm:text-right">
                    <p class="mono text-[10px] uppercase tracking-[0.24em] text-white/35">{{ __('ui.orders.amount') }}</p>
                    <p class="mt-1 text-2xl font-black text-white/80">${{ number_format($order->total_amount, 2) }}</p>
                </div>
            </div>

            <div class="divide-y divide-white/8">
                @foreach($order->items as $item)
                    <article class="grid grid-cols-[64px_1fr] gap-4 p-4 sm:grid-cols-[76px_1fr_auto] sm:p-5">
                        @if($item->product?->image_url)
                            <img src="{{ $item->product->image_url }}"
                                alt="{{ $item->product->name }}"
                                loading="lazy"
                                decoding="async"
                                class="h-16 w-16 object-cover opacity-80 sm:h-[76px] sm:w-[76px]">
                        @else
                            <div class="flex h-16 w-16 items-center justify-center border border-white/10 bg-black text-[8px] text-white/30 sm:h-[76px] sm:w-[76px]">
                                NO_IMG
                            </div>
                        @endif

                        <div class="min-w-0">
                            <a href="{{ $item->product ? route('product.show', $item->product->slug) : '#' }}"
                                class="block truncate text-sm font-bold uppercase tracking-[0.12em] text-white/78 transition-colors hover:text-white focus:outline-none focus-visible:underline">
                                {{ $item->product?->name ?? __('ui.product.unknown') }}
                            </a>
                            <p class="mono mt-2 text-[10px] uppercase tracking-[0.18em] text-white/38">
                                {{ $item->quantity }} {{ __('ui.cart.units') }}
                                @if($item->size)
                                    / {{ __('ui.product.size') }}: {{ $item->size }}
                                @endif
                            </p>
                        </div>

                        <div class="col-span-2 flex items-center justify-between border-t border-white/8 pt-4 sm:col-span-1 sm:block sm:border-0 sm:pt-0 sm:text-right">
                            <p class="mono text-[10px] uppercase tracking-[0.18em] text-white/35">{{ __('ui.store.price_label') }}</p>
                            <p class="mono text-sm font-black text-white/76">${{ number_format($item->price, 2) }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <aside class="space-y-4">
            <div class="border border-white/10 bg-[#060606] p-5">
                <p class="mono mb-4 text-[10px] uppercase tracking-[0.28em] text-white/35">{{ __('ui.checkout.shipping') }}</p>
                <div class="space-y-4">
                    <div>
                        <p class="mono text-[10px] uppercase tracking-[0.2em] text-white/30">{{ __('ui.checkout.full_name') }}</p>
                        <p class="mt-1 font-bold uppercase text-white/76">{{ $order->customer_name ?: auth()->user()->name }}</p>
                    </div>
                    <div>
                        <p class="mono text-[10px] uppercase tracking-[0.2em] text-white/30">{{ __('ui.checkout.phone') }}</p>
                        <p class="mt-1 text-white/68">{{ $order->customer_phone ?: 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="mono text-[10px] uppercase tracking-[0.2em] text-white/30">{{ __('ui.checkout.address') }}</p>
                        <p class="mt-1 leading-relaxed text-white/68">{{ $order->customer_address ?: 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="border border-white/10 bg-white/[0.018] p-5">
                <div class="flex items-center justify-between">
                    <span class="mono text-[10px] uppercase tracking-[0.22em] text-white/35">{{ __('ui.cart.total_cost') }}</span>
                    <span class="text-2xl font-black text-white/82">${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </aside>
    </div>
</main>
@endsection
