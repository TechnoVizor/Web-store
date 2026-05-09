<section class="profile-frame mt-10 p-5 sm:p-7">
    <div class="mb-6 flex flex-col gap-4 border-b border-white/10 pb-6 sm:flex-row sm:items-center sm:justify-between">
        <div class="border-l border-white/60 pl-4">
            <p class="mono mb-2 text-[10px] uppercase tracking-[0.36em] text-white/30">{{ __('ui.orders.total_orders') }}: {{ $ordersCount }}</p>
            <h2 class="text-xl font-black uppercase tracking-tight">{{ __('ui.profile.recent_orders') }}</h2>
        </div>

        <a href="{{ route('orders.index') }}" wire:navigate
            class="ui-btn ui-btn-compact mono text-[10px] tracking-[0.25em]">
            {{ __('ui.orders.view_history') }}
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="border border-dashed border-white/10 bg-white/[0.018] px-6 py-10 text-center">
            <p class="mono text-[10px] uppercase tracking-[0.4em] text-white/30">{{ __('ui.orders.empty') }}</p>
            <a href="{{ route('home') }}" wire:navigate
                class="ui-btn ui-btn-primary mt-6 px-8 py-3 text-[10px] font-bold tracking-[0.25em]">
                {{ __('ui.orders.return_home') }}
            </a>
        </div>
    @else
        <div class="grid gap-3">
            @foreach($orders as $order)
                <a href="{{ route('orders.show', $order) }}" wire:navigate
                    class="group grid gap-4 border border-white/10 bg-[#090909] p-4 transition duration-200 hover:border-white/24 sm:grid-cols-[1fr_auto] sm:items-center">
                    <div class="min-w-0">
                        <div class="mb-2 flex flex-wrap items-center gap-3">
                            <span class="mono text-[10px] font-bold uppercase tracking-[0.24em] text-white/70">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                            <span class="mono border border-white/10 px-2 py-1 text-[9px] uppercase tracking-[0.18em] text-white/45">
                                {{ __('ui.status.' . $order->status) }}
                            </span>
                        </div>
                        <p class="mono truncate text-[10px] uppercase tracking-[0.22em] text-white/32">
                            {{ $order->items_count }} {{ __('ui.orders.units_total') }} / {{ $order->created_at?->timezone(config('app.timezone'))->format('Y-m-d H:i') }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between gap-4 sm:justify-end">
                        <span class="mono text-sm font-black text-white/80">${{ number_format($order->total_amount, 2) }}</span>
                        <span class="ui-btn ui-btn-compact text-[10px] tracking-[0.22em]">{{ __('ui.admin.details') }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</section>
