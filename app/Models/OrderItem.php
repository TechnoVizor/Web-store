<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $guarded = [];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Позиция принадлежит заказу
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Позиция связана с конкретным товаром
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
