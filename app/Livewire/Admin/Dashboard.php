<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        // 1. Базовая статистика
        $stats = [
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total_amount'),
            'orders_count' => Order::count(),
            'avg_check' => Order::where('status', '!=', 'cancelled')->avg('total_amount') ?? 0,
            'users_count' => User::count(),
        ];

        // 2. Данные для графика (Продажи за последние 7 дней)
        $salesData = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total')
        )
        ->where('status', '!=', 'cancelled')
        ->where('created_at', '>=', now()->subDays(6))
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();

        // 3. Топ-5 товаров (по количеству проданных единиц)
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_qty', 'DESC')
            ->take(5)
            ->get();

        // 4. Последние заказы для лога
        $recentOrders = Order::latest()->take(5)->get();

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'salesData' => $salesData,
            'topProducts' => $topProducts,
            'recentOrders' => $recentOrders
        ]);
    }
}