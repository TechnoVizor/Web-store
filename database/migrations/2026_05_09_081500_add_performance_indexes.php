<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_active', 'created_at'], 'products_active_created_at_idx');
            $table->index(['category_id', 'is_active', 'created_at'], 'products_category_active_created_at_idx');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('name', 'categories_name_idx');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'orders_user_created_at_idx');
            $table->index(['status', 'created_at'], 'orders_status_created_at_idx');
            $table->index('customer_name', 'orders_customer_name_idx');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->index(['product_id', 'quantity'], 'order_items_product_quantity_idx');
        });

        Schema::table('wishlists', function (Blueprint $table) {
            $table->index(['user_id', 'product_id'], 'wishlists_user_product_idx');
        });
    }

    public function down(): void
    {
        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropIndex('wishlists_user_product_idx');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('order_items_product_quantity_idx');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_customer_name_idx');
            $table->dropIndex('orders_status_created_at_idx');
            $table->dropIndex('orders_user_created_at_idx');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('categories_name_idx');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_category_active_created_at_idx');
            $table->dropIndex('products_active_created_at_idx');
        });
    }
};
