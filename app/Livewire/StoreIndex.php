<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Support\Search;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StoreIndex extends Component
{
    // Параметры фильтрации
    public $search = '';

    public $selectedCategory = null;

    public array $selectedSizes = [];

    public int $visibleProducts = 20;

    private int $loadStep = 20;

    // Сбрасываем витрину при поиске
    public function updatingSearch()
    {
        $this->visibleProducts = $this->loadStep;
    }

    public function updatedSelectedCategory($value): void
    {
        if ($value === '') {
            $this->selectedCategory = null;
        }

        $this->visibleProducts = $this->loadStep;
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = ($this->selectedCategory == $categoryId) ? null : $categoryId;
        $this->visibleProducts = $this->loadStep;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'selectedCategory']);
        $this->visibleProducts = $this->loadStep;
    }

    public function loadMore(): void
    {
        $this->visibleProducts += $this->loadStep;
    }

    public function addToBag(int $productId): void
    {
        $product = Product::query()
            ->select(['id', 'name', 'price', 'image', 'sizes'])
            ->whereKey($productId)
            ->where('is_active', true)
            ->firstOrFail();

        $size = $this->selectedSizeFor($product);

        if (! $size) {
            $this->dispatch('show-system-alert', message: __('ui.alert.select_size'));

            return;
        }

        $cartKey = $this->cartKey($product->id, $size);
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'size' => $size,
                'quantity' => 1,
                'price' => $product->price,
                'image' => $product->image_url,
            ];
        }

        session()->put('cart', $cart);

        $this->dispatch('cart-updated');
    }

    private function selectedSizeFor(Product $product): ?string
    {
        $sizes = $product->availableSizes();
        $size = strtoupper(trim((string) ($this->selectedSizes[$product->id] ?? '')));

        return in_array($size, $sizes, true) ? $size : null;
    }

    private function cartKey(int $productId, string $size): string
    {
        return $productId.':'.$size;
    }

    public function render()
    {
        $productsQuery = Product::query()
            ->with('category')
            ->select(['id', 'category_id', 'name', 'slug', 'price', 'image', 'sizes', 'is_active', 'created_at'])
            ->where('is_active', true)
            ->when($this->search, function ($query) {
                Search::whereLike($query, 'name', $this->search);
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->latest();

        $totalProducts = (clone $productsQuery)->count();
        $products = $productsQuery
            ->limit($this->visibleProducts)
            ->get();

        return view('livewire.store-index', [
            'products' => $products,
            'totalProducts' => $totalProducts,
            'categories' => Category::query()
                ->select(['id', 'name'])
                ->orderBy('name')
                ->get(),
        ]);
    }
}
