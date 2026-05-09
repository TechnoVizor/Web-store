<?php

namespace App\Support;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class CustomerOrders
{
    public static function attachGuestOrders(User $user): int
    {
        $phone = self::userPhone($user);

        if (! $phone) {
            return 0;
        }

        if (! Schema::hasColumn('orders', 'customer_phone_normalized')) {
            return self::attachByRawPhone($user, $phone);
        }

        return Order::query()
            ->whereNull('user_id')
            ->where('customer_phone_normalized', $phone)
            ->update(['user_id' => $user->id]);
    }

    private static function userPhone(User $user): ?string
    {
        if (Schema::hasColumn('users', 'phone_normalized') && filled($user->phone_normalized)) {
            return $user->phone_normalized;
        }

        return Phone::normalize($user->phone);
    }

    private static function attachByRawPhone(User $user, string $phone): int
    {
        $updated = 0;

        Order::query()
            ->whereNull('user_id')
            ->whereNotNull('customer_phone')
            ->select(['id', 'customer_phone'])
            ->orderBy('id')
            ->chunkById(100, function ($orders) use ($user, $phone, &$updated): void {
                $ids = $orders
                    ->filter(fn (Order $order): bool => Phone::normalize($order->customer_phone) === $phone)
                    ->pluck('id');

                if ($ids->isEmpty()) {
                    return;
                }

                $updated += Order::query()
                    ->whereIn('id', $ids)
                    ->update(['user_id' => $user->id]);
            });

        return $updated;
    }
}
