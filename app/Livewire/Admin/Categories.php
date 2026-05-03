<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Category;
use Illuminate\Support\Str;

#[Layout('components.layouts.admin')]
class Categories extends Component
{
    use WithPagination;

    public $search = '';
    
    // Переменные модального окна
    public $isModalOpen = false;
    public $category_id, $name, $slug;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    // МАГИЯ: Автозаполнение Slug при вводе имени
    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function openModal()
    {
        $this->resetFields();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetFields();
    }

    public function resetFields()
    {
        $this->category_id = null;
        $this->name = '';
        $this->slug = '';
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        $this->category_id = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        
        $this->isModalOpen = true;
    }

    public function saveCategory()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $this->category_id,
        ]);

        Category::updateOrCreate(
            ['id' => $this->category_id],
            [
                'name' => $this->name,
                'slug' => $this->slug,
            ]
        );

        $this->closeModal();
    }

    public function deleteCategory($id)
    {
        Category::find($id)?->delete();
    }

    public function render()
    {
        $categories = Category::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.categories', compact('categories'));
    }
}