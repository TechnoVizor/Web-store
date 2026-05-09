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
        'image',
        'quantity',
        'sizes',
        'is_active',
    ];

    protected $casts = [
        'sizes' => 'array',
        'is_active' => 'boolean',
    ];

    public const DEFAULT_SIZES = ['XS', 'S', 'M', 'L', 'XL'];

    public function availableSizes(): array
    {
        $sizes = collect($this->sizes)
            ->filter(fn ($size): bool => is_string($size) && trim($size) !== '')
            ->map(fn ($size): string => strtoupper(trim($size)))
            ->unique()
            ->values()
            ->all();

        return $sizes ?: self::DEFAULT_SIZES;
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        return asset('storage/'.$this->image);
    }

    // Товар принадлежит категории
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
