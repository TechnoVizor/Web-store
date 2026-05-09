<div>
    {{-- Шапка --}}
    <div class="flex justify-between items-end mb-8 border-b border-zinc-800 pb-4">
        <div>
            <h2 class="text-[10px] text-zinc-500 tracking-[0.3em] uppercase mb-1">Logistics_System</h2>
            <h1 class="text-3xl font-bold tracking-widest uppercase text-white">Orders</h1>
        </div>
        <div class="flex space-x-4">
        <input type="text" wire:model.live.debounce.350ms="search" placeholder="> SEARCH CUSTOMER..."
                class="bg-black border border-zinc-700 text-white px-4 py-2 focus:outline-none focus:border-white text-xs w-64 tracking-widest">
        </div>
    </div>

    {{-- Таблица заказов --}}
    <div class="border border-zinc-800 bg-black overflow-hidden relative">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-zinc-800 text-zinc-500 uppercase tracking-widest text-[10px] bg-zinc-900/30">
                <tr>
                    <th class="p-4 font-normal">ORDER_ID</th>
                    <th class="p-4 font-normal">Customer_Data</th>
                    <th class="p-4 font-normal">Amount</th>
                    <th class="p-4 font-normal">Status</th>
                    <th class="p-4 font-normal">Timestamp</th>
                    <th class="p-4 font-normal text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/50 text-xs tracking-wider">
                @forelse($orders as $order)
                    <tr class="hover:bg-zinc-900/50 transition-colors">
                        <td class="p-4 text-zinc-600">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="p-4">
                            <div class="font-bold text-zinc-300 uppercase">{{ $order->customer_name }}</div>
                            <div class="text-[10px] text-zinc-500">{{ $order->customer_phone }}</div>
                        </td>
                        <td class="p-4 font-bold text-green-500">
                            ${{ number_format($order->total_amount, 2) }}
                        </td>
                        <td class="p-4">
                            {{-- Выпадающий список для смены статуса --}}
                            <select wire:change="updateStatus({{ $order->id }}, $event.target.value)"
                                class="bg-black border border-zinc-800 text-[10px] px-2 py-1 uppercase tracking-widest focus:border-white outline-none 
                                @if($order->status == 'pending') text-yellow-500 @elseif($order->status == 'paid') text-green-500 @elseif($order->status == 'cancelled') text-red-500 @else text-blue-500 @endif">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>PENDING</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>PROCESSING
                                </option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>SHIPPED</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>DELIVERED
                                </option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>CANCELLED
                                </option>
                            </select>
                        </td>
                        <td class="p-4 text-zinc-500">
                            {{ $order->created_at->format('Y-m-d H:i') }}
                        </td>
                        <td class="p-4 text-right">
    <div class="flex items-center justify-end space-x-2">
        {{-- Кнопка Details --}}
        <button wire:click="openModal({{ $order->id }})"
            class="border border-zinc-700 px-3 py-1 text-[10px] hover:bg-white hover:text-black transition-colors uppercase font-bold">
            Details
        </button>

        {{-- Кнопка удаления (показывается только для DELIVERED или CANCELLED) --}}
        @if(in_array($order->status, ['delivered', 'cancelled']))
            <button 
                wire:click="deleteOrder({{ $order->id }})"
                wire:confirm="CRITICAL_ACTION: Are you sure you want to PERMANENTLY DELETE this record from the archives?"
                class="border border-red-900/50 text-red-900 px-2 py-1 text-[10px] hover:bg-red-600 hover:text-white hover:border-red-600 transition-all font-bold"
                title="Delete Record">
                ✕
            </button>
        @else
            {{-- Заглушка, чтобы сохранить ровную сетку (опционально) --}}
            <div class="w-[25px]"></div>
        @endif
    </div>
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-zinc-600 tracking-widest uppercase text-xs">
                            No_Transactions_Found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
{{-- КИБЕР-МОДАЛКА: ДЕТАЛИ ЗАКАЗА --}}
    @if($isModalOpen && $selectedOrder)
    <div class="fixed inset-0 bg-black/90 flex items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-black border border-zinc-600 w-full max-w-2xl p-8 relative shadow-[0_0_30px_rgba(255,255,255,0.05)]">
            
            {{-- Шапка модалки --}}
            <div class="flex justify-between items-start mb-8 border-b border-zinc-800 pb-6">
                <div>
                    <h2 class="text-xl font-bold tracking-widest uppercase text-white flex items-center">
                        <span class="w-2 h-2 bg-white mr-3 text-green-500 shadow-[0_0_8px_#22c55e]"></span>
                        ORDER_DATA // #{{ str_pad($selectedOrder->id, 5, '0', STR_PAD_LEFT) }}
                    </h2>
                    
                    {{-- Инфо-панель клиента --}}
                    <div class="grid grid-cols-2 gap-x-8 gap-y-4 mt-4">
                        <div>
                            <div class="text-[9px] text-zinc-500 tracking-[0.2em] uppercase">Customer_Name</div>
                            <div class="text-xs text-white uppercase font-bold">{{ $selectedOrder->customer_name }}</div>
                        </div>
                        <div>
                            <div class="text-[9px] text-zinc-500 tracking-[0.2em] uppercase">Registration_Date</div>
                            <div class="text-xs text-white font-bold">{{ $selectedOrder->created_at->format('d.m.Y // H:i') }}</div>
                        </div>
                        <div>
                            <div class="text-[9px] text-zinc-500 tracking-[0.2em] uppercase">Contact_Email</div>
                            <div class="text-xs text-blue-400 font-bold underline decoration-blue-900">{{ $selectedOrder->customer_email ?? $selectedOrder->email ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="text-[9px] text-zinc-500 tracking-[0.2em] uppercase">Contact_Phone</div>
                            <div class="text-xs text-white font-bold">{{ $selectedOrder->customer_phone }}</div>
                        </div>
                        
                        {{-- АДРЕС ДОСТАВКИ (на всю ширину) --}}
                        <div class="col-span-2 pt-2 border-t border-zinc-900">
                            <div class="text-[9px] text-zinc-500 tracking-[0.2em] uppercase mb-1">Drop_Coordinates (Shipping_Address)</div>
                            <div class="text-xs text-zinc-300 font-mono leading-relaxed uppercase">
                                > {{ $selectedOrder->customer_address ?? $selectedOrder->address ?? 'Location_Not_Specified' }}
                            </div>
                        </div>
                    </div>
                </div>
                <button wire:click="closeModal" class="text-zinc-500 hover:text-white uppercase text-[10px] tracking-widest border border-zinc-800 px-2 py-1 transition-colors">✕ CLOSE</button>
            </div>
            
            {{-- Ниже идет список товаров и т.д. --}}

{{-- Список товаров через OrderItem --}}
            <div class="space-y-4 max-h-[50vh] overflow-y-auto pr-2">
                @forelse($selectedOrder->items as $item)
                <div class="flex items-center justify-between border border-zinc-800 bg-zinc-900/20 p-4">
                    <div class="flex items-center space-x-4">
                        {{-- Фото товара --}}
                        @if($item->product && $item->product->image)
                            <img src="{{ $item->product->image_url }}" class="w-16 h-16 object-cover border border-zinc-700">
                        @else
                            <div class="w-16 h-16 bg-black border border-zinc-700 flex items-center justify-center text-[8px] text-zinc-600 tracking-widest">NO_IMG</div>
                        @endif
                        
                        {{-- Инфо --}}
                        <div>
                            <div class="font-bold text-white text-sm uppercase tracking-wider">
                                {{ $item->product ? $item->product->name : 'UNKNOWN_MODULE (Deleted)' }}
                            </div>
                            <div class="text-[10px] text-zinc-500 uppercase mt-1">
                                QTY: {{ $item->quantity }}
                            </div>
                        </div>
                    </div>
                    
                    {{-- Цена (берем из item) --}}
                    <div class="font-bold text-green-500 tracking-widest">
                        ${{ number_format($item->price, 2) }}
                    </div>
                </div>
                @empty
                <div class="text-zinc-500 text-xs tracking-widest uppercase text-center py-4">No specific modules linked.</div>
                @endforelse
            </div>

            {{-- Итог --}}
            <div class="mt-6 pt-4 border-t border-zinc-800 flex justify-between items-center">
                <span class="text-xs text-zinc-500 tracking-widest uppercase">Total_Amount</span>
                <span class="text-xl font-bold text-green-500">${{ number_format($selectedOrder->total_amount, 2) }}</span>
            </div>
        </div>
    </div>
    @endif
</div>
