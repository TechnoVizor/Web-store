<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class StoreIndex extends Component
{
    use WithPagination;

    // Параметры фильтрации
    public $search = '';

    public $selectedCategory = null;

    // Сбрасываем страницу пагинации при поиске
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = ($this->selectedCategory == $categoryId) ? null : $categoryId;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'selectedCategory']);
    }

    public function addToBag(int $productId): void
    {
        $product = Product::query()
            ->select(['id', 'name', 'price', 'image'])
            ->whereKey($productId)
            ->where('is_active', true)
            ->firstOrFail();

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->price,
                'image' => $product->image_url,
            ];
        }

        session()->put('cart', $cart);

        $this->dispatch('cart-updated');
    }

    public function render()
    {
        $products = Product::query()
            ->with('category')
            ->select(['id', 'category_id', 'name', 'slug', 'price', 'image', 'is_active', 'created_at'])
            ->where('is_active', true)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.trim($this->search).'%');
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->latest()
            ->paginate(8);

        return view('livewire.store-index', [
            'products' => $products,
            'categories' => Category::query()
                ->select(['id', 'name'])
                ->orderBy('name')
                ->get(),
        ]);
    }
}
