<div>
    {{-- Шапка --}}
    <div class="flex justify-between items-end mb-8 border-b border-zinc-800 pb-4">
        <div>
            <h2 class="text-[10px] text-zinc-500 tracking-[0.3em] uppercase mb-1">{{ __('ui.admin.categories_section') }}</h2>
            <h1 class="text-3xl font-bold tracking-widest uppercase text-white">{{ __('ui.admin.categories') }}</h1>
        </div>
        <div class="flex space-x-4">
            <input type="text" wire:model.live.debounce.350ms="search" placeholder="{{ __('ui.admin.search') }}"
                   class="bg-black border border-zinc-700 text-white px-4 py-2 focus:outline-none focus:border-white text-xs w-64 tracking-widest transition-colors">
            
            <button wire:click="openModal" class="bg-white text-black px-6 py-2 font-bold hover:bg-zinc-300 uppercase text-xs tracking-widest transition-colors">
                {{ __('ui.admin.add_category') }}
            </button>
        </div>
    </div>

    {{-- Таблица --}}
    <div class="border border-zinc-800 bg-black overflow-hidden relative">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-zinc-800 text-zinc-500 uppercase tracking-widest text-[10px] bg-zinc-900/30">
                <tr>
                    <th class="p-4 font-normal">{{ __('ui.admin.cat_id') }}</th>
                    <th class="p-4 font-normal">{{ __('ui.admin.name') }}</th>
                    <th class="p-4 font-normal">{{ __('ui.admin.slug') }}</th>
                    <th class="p-4 font-normal text-right">{{ __('ui.admin.execute') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/50 text-xs tracking-wider">
                @forelse($categories as $category)
                <tr class="hover:bg-zinc-900/50 transition-colors">
                    <td class="p-4 text-zinc-600">[{{ str_pad($category->id, 3, '0', STR_PAD_LEFT) }}]</td>
                    <td class="p-4 font-bold text-zinc-300">{{ $category->name }}</td>
                    <td class="p-4 text-zinc-500">{{ $category->slug }}</td>
                    <td class="p-4 text-right space-x-4">
                        <button wire:click="editCategory({{ $category->id }})" class="text-zinc-500 hover:text-white transition-colors">{{ __('ui.admin.edit') }}</button>
                        <button wire:click="deleteCategory({{ $category->id }})" 
                                wire:confirm="{{ __('ui.admin.delete_category_confirm') }}"
                                class="text-red-900 hover:text-red-500 transition-colors">{{ __('ui.admin.delete') }}</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-zinc-600 tracking-widest uppercase text-xs">
                        {{ __('ui.admin.no_data') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>

    {{-- КИБЕР-МОДАЛЬНОЕ ОКНО --}}
    @if($isModalOpen)
    <div class="fixed inset-0 bg-black/90 flex items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-black border border-zinc-600 w-full max-w-md p-8 relative shadow-[0_0_30px_rgba(255,255,255,0.05)]">
            <h2 class="text-xl font-bold tracking-widest uppercase mb-8 text-white flex items-center">
                <span class="w-2 h-2 bg-white mr-3"></span>
                {{ $category_id ? __('ui.admin.edit_category') : __('ui.admin.new_category') }}
            </h2>

            <form wire:submit.prevent="saveCategory" class="space-y-6">
                {{-- Имя --}}
                <div>
                    <label class="block text-zinc-500 text-[10px] tracking-widest uppercase mb-2">{{ __('ui.admin.category_name') }}</label>
                    <input type="text" wire:model.live="name" class="w-full bg-black border border-zinc-700 text-white p-3 text-sm focus:border-white focus:outline-none transition-colors">
                    @error('name') <span class="text-red-500 text-[10px] uppercase tracking-widest mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label class="block text-zinc-500 text-[10px] tracking-widest uppercase mb-2">{{ __('ui.admin.slug') }}</label>
                    <input type="text" wire:model="slug" class="w-full bg-black border border-zinc-700 text-zinc-400 p-3 text-sm focus:border-white focus:outline-none transition-colors">
                    @error('slug') <span class="text-red-500 text-[10px] uppercase tracking-widest mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Кнопки --}}
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-zinc-800">
                    <button type="button" wire:click="closeModal" class="text-zinc-500 hover:text-white uppercase tracking-widest text-xs transition-colors px-4 py-2">
                        {{ __('ui.admin.cancel') }}
                    </button>
                    <button type="submit" class="bg-white text-black px-6 py-2 font-bold hover:bg-zinc-300 uppercase text-xs tracking-widest transition-colors">
                        {{ __('ui.admin.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
