<div>
    {{-- Шапка --}}
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-sm text-gray-500 tracking-[0.3em] uppercase mb-2">Database_Access</h2>
            <h1 class="text-4xl font-bold tracking-tighter uppercase">Products</h1>
        </div>
        <div class="flex space-x-4">
            <input type="text" wire:model.live="search" placeholder="> SEARCH..." 
                   class="bg-black border border-gray-800 text-white px-4 py-2 focus:outline-none focus:border-gray-500 font-mono text-sm w-64">
            
            <button wire:click="openModal" class="bg-white text-black px-6 py-2 font-bold hover:bg-gray-200 uppercase text-sm tracking-widest transition-colors">
                > ADD_NEW
            </button>
        </div>
    </div>

    {{-- Таблица товаров --}}
    <div class="border border-gray-800 bg-black">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-gray-800 text-gray-500 uppercase tracking-widest text-xs">
                <tr>
                    <th class="p-4 font-normal">SYS_ID</th>
                    <th class="p-4 font-normal">Nomenclature</th>
                    <th class="p-4 font-normal">Price</th>
                    <th class="p-4 font-normal">Category</th>
                    <th class="p-4 font-normal text-right">Execute</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($products as $product)
                <tr class="hover:bg-gray-900 transition-colors">
                    <td class="p-4 text-gray-500">{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="p-4 font-bold text-white">{{ $product->name }}</td>
                    <td class="p-4 text-green-500">${{ number_format($product->price, 2) }}</td>
                    <td class="p-4 text-gray-400">CAT_{{ str_pad($product->category_id, 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="p-4 text-right space-x-4">
                        <button wire:click="editProduct({{ $product->id }})" class="text-gray-500 hover:text-white transition-colors">EDIT</button>
                        <button wire:click="deleteProduct({{ $product->id }})" 
                                wire:confirm="Are you sure you want to delete this module?"
                                class="text-red-900 hover:text-red-500 transition-colors">DEL</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-600 tracking-widest uppercase">
                        No_Data_Found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>

    {{-- КИБЕР-МОДАЛЬНОЕ ОКНО --}}
    @if($isModalOpen)
    <div class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50">
        <div class="bg-black border border-gray-600 w-full max-w-md p-6 relative">
            <h2 class="text-2xl font-bold tracking-widest uppercase mb-6">
                {{ $product_id ? 'EDIT_MODULE' : 'NEW_MODULE' }}
            </h2>
            
            <form wire:submit.prevent="saveProduct" class="space-y-4">

                {{-- Изображение товара --}}


<div class="mt-4">
    <label class="block text-zinc-500 text-[10px] tracking-widest uppercase mb-2">Visual_Asset (Image)</label>
    <div class="flex items-center space-x-4">
        <input type="file" wire:model="imageUpload" accept="image/jpeg,image/png,image/webp,image/avif" class="text-xs text-zinc-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-zinc-800 file:text-zinc-300 hover:file:bg-zinc-700">

        <div wire:loading wire:target="imageUpload" class="text-[10px] text-green-500 animate-pulse uppercase">Uploading...</div>
    </div>
    @error('imageUpload') <span class="text-red-500 text-[10px] uppercase tracking-widest mt-1 block">{{ $message }}</span> @enderror

    <div class="mt-4">
        @if ($imageUpload)
            <img src="{{ $imageUpload->temporaryUrl() }}" class="w-24 h-24 object-cover border border-zinc-700">
        @elseif ($this->existingImageUrl)
            <img src="{{ $this->existingImageUrl }}" class="w-24 h-24 object-cover border border-zinc-700">
        @endif
    </div>
</div>

                {{-- Имя товара --}}
                              <div>
    <label class="block text-zinc-500 text-[10px] tracking-widest uppercase mb-2">Product_Name</label>
    <input type="text" wire:model.live="name" class="w-full bg-black border border-zinc-700 text-white p-3 text-sm focus:border-white focus:outline-none">
</div>


                {{-- Slug (URL) --}}
             <div>
    <label class="block text-zinc-500 text-[10px] tracking-widest uppercase mb-2">System_Slug</label>
    <input type="text" wire:model="slug" class="w-full bg-black border border-zinc-700 text-zinc-400 p-3 text-sm focus:border-white focus:outline-none">
</div>

<div class="mt-4">
    <label class="block text-zinc-500 text-[10px] tracking-widest uppercase mb-2">Technical_Specs / Description</label>
    <textarea 
        wire:model="description" 
        rows="4"
        class="w-full bg-black border border-zinc-700 text-white p-3 text-sm focus:border-white focus:outline-none transition-colors resize-none font-mono"
        placeholder="> ENTER DATA..."></textarea>
    @error('description') <span class="text-red-500 text-[10px] uppercase tracking-widest mt-1 block">{{ $message }}</span> @enderror
</div>
                {{-- Цена --}}
                <div>
                    <label class="block text-gray-500 text-xs tracking-widest uppercase mb-1">Price (USD)</label>
                    <input type="number" wire:model="price" step="0.01" class="w-full bg-transparent border border-gray-800 text-white p-2 focus:border-white focus:outline-none">
                    @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Категория --}}
                <div>
                    <label class="block text-gray-500 text-xs tracking-widest uppercase mb-1">Category_Class</label>
                    <select wire:model="category_id" class="w-full bg-black border border-gray-800 text-white p-2 focus:border-white focus:outline-none">
                        <option value="">> SELECT_CATEGORY</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Кнопки действий --}}
                <div class="flex justify-end space-x-4 mt-8 pt-4 border-t border-gray-800">
                    <button type="button" wire:click="closeModal" class="text-gray-500 hover:text-white uppercase tracking-widest text-sm transition-colors">
                        CANCEL
                    </button>
                    <button type="submit" class="bg-white text-black px-6 py-2 font-bold hover:bg-gray-200 uppercase text-sm tracking-widest transition-colors">
                        SAVE_DATA
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
