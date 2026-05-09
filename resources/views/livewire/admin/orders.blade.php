<div>
    @php
        $statuses = ['new', 'pending', 'processing', 'paid', 'shipped', 'delivered', 'cancelled'];
        $statusClass = fn (?string $status): string => 'status-pill status-' . ($status ?: 'new');
        $selectClass = fn (?string $status): string => match ($status) {
            'new' => 'text-blue-300 border-blue-500/30 bg-blue-500/10',
            'pending' => 'text-yellow-300 border-yellow-500/35 bg-yellow-500/10',
            'processing' => 'text-sky-300 border-sky-500/35 bg-sky-500/10',
            'paid' => 'text-green-300 border-green-500/35 bg-green-500/10',
            'shipped' => 'text-purple-300 border-purple-500/35 bg-purple-500/10',
            'delivered' => 'text-teal-300 border-teal-500/35 bg-teal-500/10',
            'cancelled' => 'text-red-300 border-red-500/35 bg-red-500/10',
            default => 'text-zinc-300 border-zinc-700 bg-black',
        };
    @endphp

    <div class="mb-8 grid gap-5 border-b border-zinc-800/80 pb-5 md:grid-cols-[1fr_auto] md:items-end">
        <div>
            <h2 class="mb-2 text-[10px] uppercase tracking-[0.3em] text-zinc-500">{{ __('ui.admin.orders_section') }}</h2>
            <h1 class="text-3xl font-black uppercase tracking-tight text-white">{{ __('ui.admin.orders') }}</h1>
        </div>

        <input type="text" wire:model.live.debounce.350ms="search" placeholder="{{ __('ui.admin.search_customer') }}"
            class="admin-input w-full px-4 py-3 text-xs tracking-[0.18em] md:w-80">
    </div>

    @if (session()->has('error'))
        <div class="mb-5 border border-red-500/30 bg-red-500/10 px-4 py-3 text-[10px] font-bold uppercase tracking-[0.22em] text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-5 grid grid-cols-2 gap-3 md:grid-cols-7">
        @foreach($statuses as $status)
            <div class="{{ $statusClass($status) }} justify-center">
                {{ __('ui.status.' . $status) }}
            </div>
        @endforeach
    </div>

    <div class="admin-panel overflow-hidden">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>{{ __('ui.admin.order_id') }}</th>
                    <th>{{ __('ui.admin.customer_data') }}</th>
                    <th>{{ __('ui.admin.amount') }}</th>
                    <th>{{ __('ui.admin.status') }}</th>
                    <th>{{ __('ui.admin.timestamp') }}</th>
                    <th class="text-right">{{ __('ui.admin.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr wire:key="admin-order-{{ $order->id }}">
                        <td class="text-zinc-500">
                            <div class="font-bold text-white/75">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</div>
                            <div class="mt-1 text-[10px] uppercase tracking-[0.18em] text-zinc-600">{{ $order->items_count ?? $order->items()->count() }} items</div>
                        </td>
                        <td>
                            <div class="font-bold uppercase text-zinc-200">{{ $order->customer_name ?: 'Guest customer' }}</div>
                            <div class="mt-1 text-[10px] text-zinc-500">{{ $order->customer_phone ?: 'No phone' }}</div>
                            @if($order->customer_email)
                                <div class="mt-1 max-w-[220px] truncate text-[10px] text-zinc-600">{{ $order->customer_email }}</div>
                            @endif
                        </td>
                        <td class="font-black text-green-400">
                            ${{ number_format($order->total_amount, 2) }}
                        </td>
                        <td>
                            <div class="mb-2 {{ $statusClass($order->status) }}">
                                {{ __('ui.status.' . $order->status) }}
                            </div>

                            <select wire:change="updateStatus({{ $order->id }}, $event.target.value)"
                                class="admin-select w-full px-3 py-2 text-[10px] font-bold uppercase tracking-[0.16em] {{ $selectClass($order->status) }}">
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                        {{ __('ui.status.' . $status) }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-zinc-500">
                            {{ $order->created_at->timezone(config('app.timezone'))->format('d.m.Y') }}
                            <div class="mt-1 text-[10px] text-zinc-600">{{ $order->created_at->timezone(config('app.timezone'))->format('H:i') }}</div>
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" wire:click="openModal({{ $order->id }})"
                                    class="ui-btn px-3 py-2 text-[10px] font-bold tracking-widest">
                                    {{ __('ui.admin.details') }}
                                </button>

                                @if(in_array($order->status, ['delivered', 'cancelled'], true))
                                    <button type="button"
                                        wire:click="deleteOrder({{ $order->id }})"
                                        wire:confirm="{{ __('ui.admin.delete_confirm') }}"
                                        class="ui-btn ui-btn-danger px-3 py-2 text-[10px] font-bold text-red-400"
                                        title="{{ __('ui.admin.delete_record') }}">
                                        ✕
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-xs uppercase tracking-widest text-zinc-600">
                            {{ __('ui.admin.no_orders') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $orders->links() }}
    </div>

    @if($isModalOpen && $selectedOrder)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/85 px-4 backdrop-blur-sm">
            <div class="admin-panel w-full max-w-3xl p-6 shadow-[0_30px_90px_rgba(0,0,0,0.65)]">
                <div class="mb-6 flex items-start justify-between gap-4 border-b border-zinc-800 pb-5">
                    <div>
                        <div class="mb-3 flex flex-wrap items-center gap-3">
                            <h2 class="text-xl font-black uppercase tracking-tight text-white">
                                {{ __('ui.admin.order_data') }} #{{ str_pad($selectedOrder->id, 5, '0', STR_PAD_LEFT) }}
                            </h2>
                            <div class="{{ $statusClass($selectedOrder->status) }}">{{ __('ui.status.' . $selectedOrder->status) }}</div>
                        </div>

                        <div class="grid gap-4 text-xs md:grid-cols-2">
                            <div>
                                <div class="mb-1 text-[10px] uppercase tracking-[0.2em] text-zinc-500">{{ __('ui.admin.customer_name') }}</div>
                                <div class="font-bold uppercase text-white/80">{{ $selectedOrder->customer_name }}</div>
                            </div>
                            <div>
                                <div class="mb-1 text-[10px] uppercase tracking-[0.2em] text-zinc-500">{{ __('ui.admin.registration_date') }}</div>
                                <div class="font-bold text-white/80">{{ $selectedOrder->created_at->timezone(config('app.timezone'))->format('d.m.Y H:i') }}</div>
                            </div>
                            <div>
                                <div class="mb-1 text-[10px] uppercase tracking-[0.2em] text-zinc-500">{{ __('ui.admin.contact_email') }}</div>
                                <div class="font-bold text-sky-300">{{ $selectedOrder->customer_email ?: 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="mb-1 text-[10px] uppercase tracking-[0.2em] text-zinc-500">{{ __('ui.admin.contact_phone') }}</div>
                                <div class="font-bold text-white/80">{{ $selectedOrder->customer_phone ?: 'N/A' }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <div class="mb-1 text-[10px] uppercase tracking-[0.2em] text-zinc-500">{{ __('ui.admin.shipping_address') }}</div>
                                <div class="border border-zinc-800 bg-black px-3 py-3 text-zinc-300">
                                    {{ $selectedOrder->customer_address ?? $selectedOrder->address ?? __('ui.admin.location_missing') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" wire:click="closeModal" class="ui-btn px-3 py-2 text-[10px] tracking-widest">✕</button>
                </div>

                <div class="max-h-[46vh] space-y-3 overflow-y-auto pr-1">
                    @forelse($selectedOrder->items as $item)
                        <div class="grid grid-cols-[64px_1fr_auto] items-center gap-4 border border-zinc-800 bg-black p-3">
                            @if($item->product && $item->product->image)
                                <img src="{{ $item->product->image_url }}" class="h-16 w-16 object-cover opacity-85">
                            @else
                                <div class="flex h-16 w-16 items-center justify-center border border-zinc-800 bg-zinc-950 text-[8px] text-zinc-600">{{ __('ui.admin.no_image') }}</div>
                            @endif

                            <div class="min-w-0">
                                <div class="truncate text-sm font-bold uppercase tracking-wide text-white/80">
                                    {{ $item->product ? $item->product->name : __('ui.product.unknown') }}
                                </div>
                                <div class="mt-1 text-[10px] uppercase tracking-[0.18em] text-zinc-500">
                                    {{ __('ui.admin.quantity') }}: {{ $item->quantity }}
                                    @if($item->size)
                                        / {{ __('ui.product.size') }}: {{ $item->size }}
                                    @endif
                                </div>
                            </div>

                            <div class="text-right font-black text-green-400">
                                ${{ number_format($item->price, 2) }}
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs uppercase tracking-widest text-zinc-500">{{ __('ui.admin.no_items') }}</div>
                    @endforelse
                </div>

                <div class="mt-6 flex items-center justify-between border-t border-zinc-800 pt-5">
                    <span class="text-xs uppercase tracking-widest text-zinc-500">{{ __('ui.admin.total_amount') }}</span>
                    <span class="text-2xl font-black text-green-400">${{ number_format($selectedOrder->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
    @endif
</div>
