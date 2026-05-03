<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

#[Layout('components.layouts.admin')]
class Products extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    
    
    // Переменные для модального окна
    public $isModalOpen = false;
    public $product_id, $name, $slug, $price, $category_id, $description;
    public $image;
    public $existingImage;

    // Сбрасываем страницу при поиске
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    
    // --- ЛОГИКА УПРАВЛЕНИЯ ОКНОМ ---
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
        $this->product_id = null;
        $this->name = '';
        $this->slug = '';
        $this->price = '';
        $this->category_id = '';
        $this->description = '';
        $this->image = null;
        $this->existingImage = null;
    }

    // --- CRUD ОПЕРАЦИИ ---
    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $this->product_id = $product->id;
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->price = $product->price;
        $this->category_id = $product->category_id;
        $this->description = $product->description;
        $this->existingImage = $product->image;
        
        $this->isModalOpen = true;
    }

    public function saveProduct()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug,' . $this->product_id,
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:15000', // Макс 2МБ
        ]);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'description' => $this->description,
        ];

        // Сохранение картинки
        if ($this->image) {
            // Сохраняет в storage/app/public/products и возвращает путь
            $data['image'] = $this->image->store('products', 'public');
        }

        Product::updateOrCreate(['id' => $this->product_id], $data);

        $this->closeModal();
    }

    public function deleteProduct($id)
    {
        Product::find($id)?->delete();
    }

    public function render()
    {
        $products = Product::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);
            
        // Передаем категории, чтобы выводить их в выпадающем списке <select>
        $categories = Category::all();

        return view('livewire.admin.products', compact('products', 'categories'));
    }
}