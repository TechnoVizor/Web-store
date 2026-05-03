<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    protected $fillable = [
    'category_id',
    'name',
    'slug',
    'description',
    'price',
    'image', // <-- Добавь это
    'quantity',
    'is_active'
];
    // Товар принадлежит категории
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}