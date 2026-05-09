<?php

namespace App\Support;

use App\Models\Order;
use App\Models\User;

class CustomerOrders
{
    public static function attachGuestOrders(User $user): int
    {
        $phone = $user->phone_normalized ?: Phone::normalize($user->phone);

        if (! $phone) {
            return 0;
        }

        return Order::query()
            ->whereNull('user_id')
            ->where('customer_phone_normalized', $phone)
            ->update(['user_id' => $user->id]);
    }
}
