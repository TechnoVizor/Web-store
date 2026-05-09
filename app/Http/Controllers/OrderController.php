<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Support\CustomerOrders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Список заказов в личном кабинете
    public function index()
    {
        CustomerOrders::attachGuestOrders(Auth::user());

        $orders = Auth::user()->orders()
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    // Детали конкретного заказа
    public function show(Order $order)
    {
        if ((int) $order->user_id !== (int) Auth::id()) {
            abort(403, 'ACCESS_DENIED');
        }

        $order->load('items.product');
        return view('orders.show', compact('order'));
    }

    // Страница успеха (просто вьюха)
    public function success()
    {
        return view('orders.success');
    }
}
