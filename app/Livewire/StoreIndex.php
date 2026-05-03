<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class StoreIndex extends Component
{
    
    use WithPagination;

    // Параметры фильтрации
    public $search = '';
    public $selectedCategory = null;

    // Сбрасываем страницу пагинации при поиске
    public function updatingSearch() { $this->resetPage(); }
    public function updatingSelectedCategory() { $this->resetPage(); }

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
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedCategory, function($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->latest()
            ->paginate(8);

        return view('livewire.store-index', [
            'products' => $products,
            'categories' => Category::all(),
        ]);
    }
}