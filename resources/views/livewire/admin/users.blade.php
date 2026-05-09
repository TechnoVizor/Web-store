<div>
    {{-- Заголовок и поиск --}}
    <div class="mb-10 border-b border-zinc-800 pb-4 flex justify-between items-end">
        <div>
            <h2 class="text-[10px] text-zinc-500 tracking-[0.3em] uppercase mb-1">{{ __('ui.admin.users_section') }}</h2>
            <h1 class="text-3xl font-bold tracking-widest uppercase text-white">{{ __('ui.admin.users') }}</h1>
        </div>
        <input type="text" wire:model.live.debounce.350ms="search" placeholder="{{ __('ui.admin.search_identity') }}"
            class="bg-transparent border border-zinc-800 px-4 py-2 text-xs mono focus:border-white outline-none text-white w-64">
    </div>

    {{-- Таблица --}}
    <div class="border border-zinc-800 bg-black overflow-hidden relative">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-zinc-800 text-zinc-500 uppercase tracking-widest text-[10px] bg-zinc-900/30">
                <tr>
                    <th class="p-4 font-normal">ID</th>
                    <th class="p-4 font-normal">{{ __('ui.admin.pic') }}</th>
                    <th class="p-4 font-normal">{{ __('ui.admin.identity') }}</th>
                    <th class="p-4 font-normal">{{ __('ui.admin.access_level') }}</th>
                    <th class="p-4 font-normal text-right">{{ __('ui.admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/50 text-xs tracking-wider">
                @foreach($users as $user)
                    <tr class="hover:bg-zinc-900/50 transition-colors">
                        <td class="p-4 text-zinc-600 font-mono">#{{ $user->id }}</td>
                        <td class="p-4">
                            <div
                                class="w-10 h-10 border border-zinc-800 bg-zinc-900/50 flex items-center justify-center overflow-hidden">
                                @if($user->avatar)
                                    {{-- Если аватар есть — выводим картинку --}}
                                    <img src="{{ $user->avatar }}"
                                        class="w-full h-full object-cover opacity-80 hover:opacity-100 transition-opacity">
                                @else
                                    {{-- Если аватара нет — выводим стильную заглушку --}}
                                    <div class="flex flex-col items-center">
                                        <span class="text-[7px] text-zinc-700 font-mono leading-none">{{ __('ui.admin.no_pic') }}</span>
                                        <div class="w-4 h-[1px] bg-zinc-800 mt-1"></div>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-zinc-300 uppercase">{{ $user->name }}</div>
                            <div class="text-[10px] text-zinc-500 font-mono">{{ $user->email }}</div>
                        </td>
                        <td class="p-4">
                            @if($user->is_super_admin)
                                {{-- Метка для тебя --}}
                                <div
                                    class="px-2 py-1 inline-block border border-blue-500 text-blue-500 bg-blue-500/10 text-[9px] uppercase font-bold tracking-[0.2em] shadow-[0_0_10px_rgba(59,130,246,0.2)]">
                                    {{ __('ui.admin.root_system') }}
                                </div>
                            @elseif($user->is_admin)
                                {{-- Метка для обычных админов --}}
                                <div
                                    class="px-2 py-1 inline-block border border-green-500 text-green-500 bg-green-500/10 text-[9px] uppercase font-bold tracking-widest">
                                    {{ __('ui.admin.admin_access') }}
                                </div>
                            @else
                                <div
                                    class="px-2 py-1 inline-block border border-zinc-800 text-zinc-500 text-[9px] uppercase font-bold tracking-widest">
                                    {{ __('ui.admin.user_access') }}
                                </div>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <button wire:click="editUser({{ $user->id }})"
                                    class="border border-zinc-700 px-3 py-1 text-[10px] hover:bg-white hover:text-black transition-colors uppercase font-bold">
                                    {{ __('ui.admin.details') }}
                                </button>

                                {{-- Показываем крестик только если цель НЕ супер-админ --}}
                                @if(!$user->is_super_admin)
                                    <button wire:click="deleteUser({{ $user->id }})"
                                        wire:confirm="{{ __('ui.admin.delete_user_confirm') }}"
                                        class="border border-red-900/50 text-red-900 px-2.5 py-1 text-[10px] hover:bg-red-600 hover:text-white transition-all font-bold">
                                        ✕
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>

    {{-- КИБЕР-МОДАЛКА: РЕДАКТИРОВАНИЕ ПОЛЬЗОВАТЕЛЯ --}}
    @if($isModalOpen)
        <div class="fixed inset-0 bg-black/90 flex items-center justify-center z-50 backdrop-blur-sm">
            <div
                class="bg-black border border-zinc-600 w-full max-w-lg p-8 relative shadow-[0_0_30px_rgba(255,255,255,0.05)]">

                <div class="flex justify-between items-start mb-8 border-b border-zinc-800 pb-6">
                    <h2 class="text-xl font-bold tracking-widest uppercase text-white flex items-center">
                        <span class="w-2 h-2 bg-blue-500 mr-3 shadow-[0_0_8px_#3b82f6]"></span>
                        {{ __('ui.admin.user_details') }} // #{{ $userId }}
                    </h2>
                    <button wire:click="closeModal"
                        class="text-zinc-500 hover:text-white uppercase text-[10px] tracking-widest transition-colors">✕
                        {{ __('ui.admin.cancel') }}</button>
                </div>

                <form wire:submit.prevent="saveUser" class="space-y-6">
                    @php
                        // Проверяем статус: если мы зашли в профиль Супер-Админа, 
                        // а сами при этом НЕ являемся Супер-Админом — блокируем всё.
                        $isTargetSuper = (bool) $is_super_admin;
                        $iAmSuper = (bool) auth()->user()->is_super_admin;

                        $canEdit = $iAmSuper || !$isTargetSuper;
                    @endphp

                    <div class="grid grid-cols-1 gap-6 @if(!$canEdit) pointer-events-none select-none @endif">
                        {{-- Имя --}}
                        <div class="space-y-2">
                            <label class="block text-[9px] text-zinc-500 uppercase tracking-[0.2em]">{{ __('ui.profile.full_name') }}</label>
                            <input type="text" wire:model="name" {{ $canEdit ? '' : 'disabled readonly' }}
                                class="w-full bg-zinc-900/50 border border-zinc-800 p-3 text-xs text-white focus:border-white outline-none transition-colors {{ $canEdit ? '' : 'opacity-30 cursor-not-allowed' }}">
                        </div>

                        {{-- Почта --}}
                        <div class="space-y-2">
                            <label
                                class="block text-[9px] text-zinc-500 uppercase tracking-[0.2em]">{{ __('ui.profile.email') }}</label>
                            <input type="email" wire:model="email" {{ $canEdit ? '' : 'disabled readonly' }}
                                class="w-full bg-zinc-900/50 border border-zinc-800 p-3 text-xs text-white focus:border-white outline-none transition-colors {{ $canEdit ? '' : 'opacity-30 cursor-not-allowed' }}">
                        </div>

                        {{-- Телефон --}}
                        <div class="space-y-2">
                            <label
                                class="block text-[9px] text-zinc-500 uppercase tracking-[0.2em]">{{ __('ui.profile.phone') }}</label>
                            <input type="text" wire:model="phone" {{ $canEdit ? '' : 'disabled readonly' }}
                                class="w-full bg-zinc-900/50 border border-zinc-800 p-3 text-xs text-white focus:border-white outline-none transition-colors {{ $canEdit ? '' : 'opacity-30 cursor-not-allowed' }}">
                        </div>

                        {{-- Статус Админа --}}
                        <div class="pt-6 border-t border-zinc-900">
                            @if($canEditUser)
                                <label class="flex items-center group cursor-pointer relative py-2">
                                    <div class="relative w-5 h-5 flex-shrink-0">
                                        <input type="checkbox" wire:model.live="is_admin"
                                            class="absolute inset-0 z-20 opacity-0 cursor-pointer">
                                        <div
                                            class="absolute inset-0 z-10 border border-zinc-700 transition-all group-hover:border-white {{ $is_admin ? 'bg-white' : '' }}">
                                            @if($is_admin) <span
                                                class="text-black text-[12px] flex items-center justify-center h-full font-bold">✓</span>
                                            @endif
                                        </div>
                                    </div>
                                    <span
                                        class="ml-4 text-[10px] uppercase tracking-[0.2em] {{ $is_admin ? 'text-white font-bold' : 'text-zinc-500' }}">{{ __('ui.admin.grant_admin') }}</span>
                                </label>
                            @else
                                <div class="flex items-center py-2 opacity-40">
                                    <div
                                        class="w-5 h-5 border border-blue-500 flex items-center justify-center bg-blue-500/10 text-blue-500 text-[10px]">
                                        🔒</div>
                                    <span
                                        class="ml-4 text-[10px] uppercase tracking-[0.2em] text-blue-500 font-bold">{{ __('ui.admin.root_protection') }}</span>
                                </div>
                            @endif
                        </div>
                        </fieldset>

                        {{-- Кнопка вне fieldset, чтобы она не блокировалась (мы её просто скроем) --}}
                        <div class="mt-8">
                            @if($canEditUser)
                                <button type="submit"
                                    class="w-full bg-white text-black font-bold py-4 uppercase text-[10px] tracking-[0.2em] hover:bg-zinc-200 transition-all">
                                    {{ __('ui.admin.commit_changes') }}
                                </button>
                            @else
                                <div class="w-full border border-blue-900/30 bg-blue-900/5 p-4 text-center">
                                    <p class="text-[8px] text-blue-500 uppercase tracking-[0.4em] font-bold animate-pulse">
                                        {{ __('ui.admin.protected_notice') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                </form>
            </div>
        </div>
    @endif
</div>
