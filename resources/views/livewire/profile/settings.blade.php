<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads; // Импортируем трейт для загрузки файлов

new class extends Component {
    use WithFileUploads; // Используем трейт

    public $name;
    public $nickname;
    public $email;
    public $phone;
    public $address;
    public $newAvatar; // Временная переменная для загрузки

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->nickname = $user->nickname;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->address = $user->address;
    }

    // Этот метод сработает автоматически, как только $newAvatar изменится
    public function updatedNewAvatar()
    {
        $this->validate([
            'newAvatar' => 'image|max:2048', // Картинка, макс 2МБ
        ]);

        $user = Auth::user();

        // Сохраняем файл в папку 'avatars' (диск public)
        $path = $this->newAvatar->store('avatars', 'public');

        // Обновляем путь в базе данных (добавляем /storage/ к пути)
        $user->update([
            'avatar' => '/storage/' . $path
        ]);

        session()->flash('message', __('ui.profile.avatar_updated'));
    }

    public function updateSettings()
    {
        $user = Auth::user();

        $this->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'required|string|max:50|unique:users,nickname,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'newAvatar' => 'nullable|image|max:5048', // 2MB макс
        ]);

        $data = [
            'name' => $this->name,
            'nickname' => $this->nickname,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
        ];

        // Если был выбран новый аватар — сохраняем его
        if ($this->newAvatar) {
            $path = $this->newAvatar->store('avatars', 'public');
            $data['avatar'] = '/storage/' . $path;
        }

        $user->update($data);

        $this->dispatch('cart-updated');
        session()->flash('message', __('ui.profile.updated'));
    }
}; ?>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
    <div class="lg:col-span-4 space-y-8">
        {{-- Блок Аватара --}}
        <div x-data="{ uploading: false }" x-on:livewire-upload-start="uploading = true"
            x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-error="uploading = false"
            class="aspect-square bg-[#0a0a0a] border border-white/10 flex items-center justify-center relative group overflow-hidden">
            {{-- Отображение текущего аватара или превью загрузки --}}
            @if($newAvatar)
                <img src="{{ $newAvatar->temporaryUrl() }}" class="w-full h-full object-cover">
            @elseif(auth()->user()->avatar)
                <img src="{{ auth()->user()->avatar }}"
                    class="w-full h-full object-cover opacity-60 group-hover:opacity-40 transition-opacity">
            @else
                <div class="text-white/10 mono text-[10px] uppercase">{{ __('ui.profile.no_avatar') }}</div>
            @endif

            {{-- Индикатор загрузки --}}
            <div x-show="uploading" class="absolute inset-0 bg-black/80 flex items-center justify-center z-20">
                <div class="mono text-[8px] text-green-500 animate-pulse uppercase tracking-[0.3em]">{{ __('ui.profile.uploading') }}
                </div>
            </div>

            {{-- Слой "Edit", который триггерит выбор файла --}}
            <label
                class="absolute inset-0 cursor-pointer flex flex-col items-center justify-end p-4 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity">
                <input type="file" wire:model="newAvatar" class="hidden">
                <p class="mono text-[7px] text-center uppercase tracking-widest text-white">{{ __('ui.profile.update_picture') }}</p>
            </label>
        </div>

        {{-- Блок Статуса (оставляем как есть) --}}
        <div class="p-6 border border-white/5 bg-white/[0.02]">
            <p class="mono text-[8px] text-white/20 uppercase tracking-widest mb-4">{{ __('ui.profile.profile_status') }}</p>
            <div class="flex items-center space-x-3">
                <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div>
                <span class="mono text-[10px] uppercase tracking-widest">{{ __('ui.profile.active') }}</span>
            </div>
            <div class="mt-4 pt-4 border-t border-white/5 space-y-2">
                <p class="mono text-[7px] text-white/20 uppercase">{{ __('ui.profile.last_login') }}: {{ now(config('app.timezone'))->format('H:i:s') }}</p>
                <p class="mono text-[7px] text-white/20 uppercase">{{ __('ui.profile.access_level') }}: {{ __('ui.profile.authorized') }}</p>
            </div>
        </div>
    </div>

    <div class="lg:col-span-8">
        <form wire:submit="updateSettings" class="space-y-10">
            {{-- Сетка полей ввода --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Имя --}}
                <div>
                    <label class="mono text-[8px] text-white/40 uppercase tracking-widest block mb-2">{{ __('ui.profile.full_name') }}</label>
                    <input type="text" wire:model="name"
                        class="w-full bg-transparent border-b border-white/10 py-3 text-sm mono focus:border-white outline-none transition-all">
                </div>

                {{-- Никнейм --}}
                <div>
                    <label class="mono text-[8px] text-white/40 uppercase tracking-widest block mb-2">{{ __('ui.profile.nickname') }}</label>
                    <input type="text" wire:model="nickname"
                        class="w-full bg-transparent border-b border-white/10 py-3 text-sm mono focus:border-white outline-none transition-all">
                </div>

                {{-- Почта --}}
                <div>
                    <label class="mono text-[8px] text-white/40 uppercase tracking-widest block mb-2">{{ __('ui.profile.email') }}</label>
                    <input type="email" wire:model="email"
                        class="w-full bg-transparent border-b border-white/10 py-3 text-sm mono focus:border-white outline-none transition-all">
                </div>

                {{-- ТЕЛЕФОН (Новое поле) --}}
                <div>
                    <label class="mono text-[8px] text-white/40 uppercase tracking-widest block mb-2">{{ __('ui.profile.phone') }}</label>
                    <input type="text" wire:model="phone" placeholder="+371..."
                        class="w-full bg-transparent border-b border-white/10 py-3 text-sm mono focus:border-white outline-none transition-all placeholder:text-white/20">
                </div>

                {{-- АДРЕС (Новое поле на всю ширину) --}}
                <div class="md:col-span-2">
                    <label class="mono text-[8px] text-white/40 uppercase tracking-widest block mb-2">{{ __('ui.profile.address') }}</label>
                    <input type="text" wire:model="address" placeholder="{{ __('ui.profile.address_placeholder') }}"
                        class="w-full bg-transparent border-b border-white/10 py-3 text-sm mono focus:border-white outline-none transition-all placeholder:text-white/20">
                </div>
            </div>

            {{-- Сообщение об успехе --}}
            @if (session()->has('message'))
                <div class="mono text-[10px] text-green-500 uppercase tracking-widest animate-pulse">
                    >> {{ session('message') }}
                </div>
            @endif

            <div class="flex justify-between items-center pt-10 border-t border-white/5">
                <div class="mono text-[8px] text-white/20 uppercase tracking-[0.3em]">
                    {{ __('ui.profile.save_hint') }}
                </div>
                <button type="submit"
                    class="ui-btn ui-btn-primary px-10 py-4 font-bold text-[10px] tracking-[0.2em]">
                    {{ __('ui.profile.update_info') }}
                </button>
            </div>
        </form>
    </div>
</div>
