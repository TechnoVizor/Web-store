<?php

namespace App\Livewire\Profile;

use App\Support\CustomerOrders;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrdersPreview extends Component
{
    public function render(): View
    {
        $user = Auth::user();

        CustomerOrders::attachGuestOrders($user);

        return view('livewire.profile.orders-preview', [
            'orders' => $user
                ->orders()
                ->withCount('items')
                ->latest()
                ->limit(3)
                ->get(),
            'ordersCount' => $user->orders()->count(),
        ]);
    }
}
