<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class Categories extends Component
{
    use WithPagination;

    public $search = '';

    // Переменные модального окна
    public $isModalOpen = false;

    public $category_id;

    public $name;

    public $slug;

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
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($this->category_id),
            ],
        ]);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
        ];

        if ($this->category_id) {
            Category::findOrFail($this->category_id)->update($data);
        } else {
            Category::create($data);
        }

        $this->closeModal();
    }

    public function deleteCategory($id)
    {
        Category::find($id)?->delete();
    }

    public function render()
    {
        $categories = Category::where('name', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.categories', compact('categories'));
    }
}
