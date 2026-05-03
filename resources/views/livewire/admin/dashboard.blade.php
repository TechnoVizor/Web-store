<div>
    {{-- Шапка --}}
    <div class="mb-10 border-b border-zinc-800 pb-4">
        <h2 class="text-[10px] text-zinc-500 tracking-[0.3em] uppercase mb-1">Neural_Network // Brain</h2>
        <h1 class="text-3xl font-bold tracking-widest uppercase text-white">System_Dashboard</h1>
    </div>

    {{-- Сетка статистики --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        @php
            $cards = [
                ['label' => 'Total_Revenue', 'value' => '$' . number_format($stats['total_revenue'], 0), 'color' => 'text-green-500'],
                ['label' => 'Order_Volume', 'value' => str_pad($stats['orders_count'], 4, '0', STR_PAD_LEFT), 'color' => 'text-white'],
                ['label' => 'Average_Check', 'value' => '$' . number_format($stats['avg_check'], 2), 'color' => 'text-blue-400'],
                ['label' => 'User_Base', 'value' => str_pad($stats['users_count'], 4, '0', STR_PAD_LEFT), 'color' => 'text-zinc-500'],
            ];
        @endphp

        @foreach($cards as $card)
            <div class="border border-zinc-800 bg-zinc-900/20 p-6 flex flex-col justify-between h-28">
                <span class="text-[9px] text-zinc-500 tracking-widest uppercase">{{ $card['label'] }}</span>
                <span class="text-2xl font-bold {{ $card['color'] }}">{{ $card['value'] }}</span>
            </div>
        @endforeach
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- ГРАФИК ПРОДАЖ (Слева) --}}
        <div class="lg:col-span-2 border border-zinc-800 bg-black p-6">
            <h3 class="text-xs font-bold text-white uppercase tracking-widest mb-10 flex items-center">
                <span class="w-1.5 h-1.5 bg-green-500 mr-2 shadow-[0_0_8px_#22c55e]"></span>
                Sales_Activity_7D
            </h3>
            
            <div class="flex items-end justify-between h-48 gap-2">
                @php 
                    $maxSale = $salesData->max('total') ?: 1; 
                @endphp
            @foreach($salesData as $data)
        {{-- Добавили h-full, чтобы этот контейнер растянулся на все 48 единиц высоты --}}
        <div class="flex-1 h-full flex flex-col justify-end items-center group relative">
            
            {{-- Сумма: теперь она позиционируется абсолютно над баром, чтобы не ломать высоту --}}
            <span class="absolute z-10 opacity-0 group-hover:opacity-100 transition-opacity bg-zinc-900 text-green-500 text-[8px] px-1 border border-green-500/30 -top-4 font-mono">
                ${{ number_format($data->total, 0) }}
            </span>

            {{-- Барометр --}}
            <div class="w-full bg-green-500/10 border-t border-x border-green-500/50 group-hover:bg-green-500/30 group-hover:border-green-500 shadow-[0_0_15px_rgba(34,197,94,0.1)] transition-all relative" 
                 style="height: {{ ($data->total / $maxSale) * 100 }}%; min-height: 4px;">
                
                {{-- Верхняя яркая грань --}}
                <div class="absolute top-0 left-0 w-full h-[2px] bg-green-500 shadow-[0_0_10px_#22c55e]"></div>
            </div>

            {{-- Подпись даты под графиком --}}
            <div class="absolute -bottom-6 flex flex-col items-center">
                <span class="text-[8px] text-zinc-600 uppercase tracking-tighter">
                    {{ date('d/m', strtotime($data->date)) }}
                </span>
            </div>
        </div>
    @endforeach
            </div>
        <div class="h-8"></div>

        {{-- ТОП ТОВАРОВ (Справа) --}}
        <div class="border border-zinc-800 bg-black p-6">
            <h3 class="text-xs font-bold text-white uppercase tracking-widest mb-6">Top_Modules_Sold</h3>
            <div class="space-y-4">
                @foreach($topProducts as $item)
                <div class="flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-zinc-300 font-bold uppercase truncate w-32">
                            {{ $item->product->name ?? 'Deleted_Item' }}
                        </span>
                        <span class="text-[8px] text-zinc-600 uppercase">Sales: {{ $item->total_qty }}</span>
                    </div>
                    <div class="h-1 flex-1 mx-4 bg-zinc-900 overflow-hidden">
                        @php $barWidth = ($item->total_qty / ($topProducts->first()->total_qty ?: 1)) * 100; @endphp
                        <div class="h-full bg-white" style="width: {{ $barWidth }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ПОСЛЕДНИЕ ЗАКАЗЫ (ЛОГ) --}}
    <div class="mt-8 border border-zinc-800 bg-black p-6">
        <h3 class="text-xs font-bold text-white uppercase tracking-widest mb-4">Live_System_Logs // Recent_Orders</h3>
        <div class="space-y-2">
            @foreach($recentOrders as $order)
                <div class="text-[10px] flex items-center space-x-4 py-2 border-b border-zinc-900 last:border-0">
                    <span class="text-zinc-600">[{{ $order->created_at->format('H:i:s') }}]</span>
                    <span class="text-green-500 tracking-tighter">SUCCESS</span>
                    <span class="text-zinc-400 font-mono">ORDER_#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                    <span class="text-zinc-500 uppercase">{{ $order->customer_name }}</span>
                    <span class="ml-auto font-bold text-white">${{ number_format($order->total_amount, 2) }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>