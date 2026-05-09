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

    public function render()
    {
        $products = Product::query()
            ->with('category')
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
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }
}
