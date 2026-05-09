<div>
    {{-- Шапка --}}
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-sm text-gray-500 tracking-[0.3em] uppercase mb-2">{{ __('ui.admin.products_section') }}</h2>
            <h1 class="text-4xl font-bold tracking-tighter uppercase">{{ __('ui.admin.products') }}</h1>
        </div>
        <div class="flex space-x-4">
            <input type="text" wire:model.live.debounce.350ms="search" placeholder="{{ __('ui.admin.search') }}"
                   class="bg-black border border-gray-800 text-white px-4 py-2 focus:outline-none focus:border-gray-500 font-mono text-sm w-64">
            
            <button wire:click="openModal" class="bg-white text-black px-6 py-2 font-bold hover:bg-gray-200 uppercase text-sm tracking-widest transition-colors">
                {{ __('ui.admin.add_product') }}
            </button>
        </div>
    </div>

    {{-- Таблица товаров --}}
    <div class="border border-gray-800 bg-black">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-gray-800 text-gray-500 uppercase tracking-widest text-xs">
                <tr>
                    <th class="p-4 font-normal">{{ __('ui.admin.product_id') }}</th>
                    <th class="p-4 font-normal">{{ __('ui.admin.name') }}</th>
                    <th class="p-4 font-normal">{{ __('ui.admin.price') }}</th>
                    <th class="p-4 font-normal">{{ __('ui.admin.category') }}</th>
                    <th class="p-4 font-normal text-right">{{ __('ui.admin.execute') }}</th>
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
                        <button wire:click="editProduct({{ $product->id }})" class="text-gray-500 hover:text-white transition-colors">{{ __('ui.admin.edit') }}</button>
                        <button wire:click="deleteProduct({{ $product->id }})" 
                                wire:confirm="{{ __('ui.admin.delete_product_confirm') }}"
                                class="text-red-900 hover:text-red-500 transition-colors">{{ __('ui.admin.delete') }}</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-600 tracking-widest uppercase">
                        {{ __('ui.admin.no_data') }}
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
                {{ $product_id ? __('ui.admin.edit_product') : __('ui.admin.new_product') }}
            </h2>
            
            <form wire:submit.prevent="saveProduct" class="space-y-4">

                {{-- Изображение товара --}}


<div class="mt-4">
    <label class="block text-zinc-500 text-[10px] tracking-widest uppercase mb-2">{{ __('ui.admin.image') }}</label>
    <div class="flex items-center space-x-4">
        <input type="file" wire:model="imageUpload" accept="image/jpeg,image/png,image/webp,image/avif" class="text-xs text-zinc-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-zinc-800 file:text-zinc-300 hover:file:bg-zinc-700">

        <div wire:loading wire:target="imageUpload" class="text-[10px] text-green-500 animate-pulse uppercase">{{ __('ui.admin.uploading') }}</div>
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
    <label class="block text-zinc-500 text-[10px] tracking-widest uppercase mb-2">{{ __('ui.admin.product_name') }}</label>
    <input type="text" wire:model.live="name" class="w-full bg-black border border-zinc-700 text-white p-3 text-sm focus:border-white focus:outline-none">
</div>


                {{-- Slug (URL) --}}
             <div>
    <label class="block text-zinc-500 text-[10px] tracking-widest uppercase mb-2">{{ __('ui.admin.slug') }}</label>
    <input type="text" wire:model="slug" class="w-full bg-black border border-zinc-700 text-zinc-400 p-3 text-sm focus:border-white focus:outline-none">
</div>

<div class="mt-4">
    <label class="block text-zinc-500 text-[10px] tracking-widest uppercase mb-2">{{ __('ui.admin.description') }}</label>
    <textarea 
        wire:model="description" 
        rows="4"
        class="w-full bg-black border border-zinc-700 text-white p-3 text-sm focus:border-white focus:outline-none transition-colors resize-none font-mono"
        placeholder="{{ __('ui.admin.description_placeholder') }}"></textarea>
    @error('description') <span class="text-red-500 text-[10px] uppercase tracking-widest mt-1 block">{{ $message }}</span> @enderror
</div>
                {{-- Цена --}}
                <div>
                    <label class="block text-gray-500 text-xs tracking-widest uppercase mb-1">{{ __('ui.admin.price') }} (USD)</label>
                    <input type="number" wire:model="price" step="0.01" class="w-full bg-transparent border border-gray-800 text-white p-2 focus:border-white focus:outline-none">
                    @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Категория --}}
                <div>
                    <label class="block text-gray-500 text-xs tracking-widest uppercase mb-1">{{ __('ui.admin.category') }}</label>
                    <select wire:model="category_id" class="w-full bg-black border border-gray-800 text-white p-2 focus:border-white focus:outline-none">
                        <option value="">{{ __('ui.admin.select_category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Кнопки действий --}}
                <div class="flex justify-end space-x-4 mt-8 pt-4 border-t border-gray-800">
                    <button type="button" wire:click="closeModal" class="text-gray-500 hover:text-white uppercase tracking-widest text-sm transition-colors">
                        {{ __('ui.admin.cancel') }}
                    </button>
                    <button type="submit" class="bg-white text-black px-6 py-2 font-bold hover:bg-gray-200 uppercase text-sm tracking-widest transition-colors">
                        {{ __('ui.admin.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
