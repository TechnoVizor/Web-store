<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    protected $fillable = [
    'user_id',
    'customer_name',
    'customer_email', // Если добавил в базу
    'customer_phone',
    'customer_address',
    'total_amount',
    'status',
    ];

    // Заказ принадлежит пользователю
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Заказ содержит много позиций (товаров в корзине)
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}