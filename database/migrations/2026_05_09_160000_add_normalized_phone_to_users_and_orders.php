<?php

use App\Support\Phone;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_normalized')->nullable()->after('phone')->index();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_phone_normalized')->nullable()->after('customer_phone')->index();
        });

        DB::table('users')
            ->select('id', 'phone')
            ->orderBy('id')
            ->each(function (object $user): void {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['phone_normalized' => Phone::normalize($user->phone)]);
            });

        DB::table('orders')
            ->select('id', 'customer_phone')
            ->orderBy('id')
            ->each(function (object $order): void {
                DB::table('orders')
                    ->where('id', $order->id)
                    ->update(['customer_phone_normalized' => Phone::normalize($order->customer_phone)]);
            });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['customer_phone_normalized']);
            $table->dropColumn('customer_phone_normalized');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['phone_normalized']);
            $table->dropColumn('phone_normalized');
        });
    }
};
