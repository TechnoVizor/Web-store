<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Support\VercelBlobUploader;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class Products extends Component
{
    use WithFileUploads, WithPagination;

    public const SIZE_OPTIONS = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL'];

    public $search = '';

    // Переменные для модального окна
    public $isModalOpen = false;

    public $product_id;

    public $name;

    public $slug;

    public $price;

    public $category_id;

    public $description;

    public $image;

    public $imageUpload;

    public array $sizes = Product::DEFAULT_SIZES;

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
        $this->image = '';
        $this->imageUpload = null;
        $this->sizes = Product::DEFAULT_SIZES;
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
        $this->image = $product->image;
        $this->sizes = $product->availableSizes();

        $this->isModalOpen = true;
    }

    public function saveProduct()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                Rule::unique('products', 'slug')->ignore($this->product_id),
            ],
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'imageUpload' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:4096',
            'sizes' => ['required', 'array', 'min:1'],
            'sizes.*' => ['string', Rule::in(self::SIZE_OPTIONS)],
        ]);

        $image = $this->image;

        if ($this->imageUpload) {
            $image = app(VercelBlobUploader::class)->upload($this->imageUpload, 'products');
        }

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'description' => $this->description,
            'image' => $image ?: null,
            'sizes' => collect($this->sizes)->intersect(self::SIZE_OPTIONS)->values()->all(),
        ];

        if ($this->product_id) {
            Product::findOrFail($this->product_id)->update($data);
        } else {
            Product::create($data);
        }

        $this->closeModal();
    }

    public function getExistingImageUrlProperty(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        return asset('storage/'.$this->image);
    }

    public function deleteProduct($id)
    {
        Product::find($id)?->delete();
    }

    public function render()
    {
        $products = Product::where('name', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        // Передаем категории, чтобы выводить их в выпадающем списке <select>
        $categories = Category::all();

        return view('livewire.admin.products', compact('products', 'categories'));
    }
}
