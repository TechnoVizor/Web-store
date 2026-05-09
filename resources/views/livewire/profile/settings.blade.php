<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $name;
    public $nickname;
    public $email;
    public $phone;
    public $address;
    public $newAvatar;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->nickname = $user->nickname;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->address = $user->address;
    }

    public function updatedNewAvatar()
    {
        $this->validate([
            'newAvatar' => 'image|max:2048',
        ]);

        $user = Auth::user();
        $path = $this->newAvatar->store('avatars', 'public');

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
            'newAvatar' => 'nullable|image|max:5048',
        ]);

        $data = [
            'name' => $this->name,
            'nickname' => $this->nickname,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
        ];

        if ($this->newAvatar) {
            $path = $this->newAvatar->store('avatars', 'public');
            $data['avatar'] = '/storage/' . $path;
        }

        $user->update($data);

        $this->dispatch('cart-updated');
        session()->flash('message', __('ui.profile.updated'));
    }
}; ?>

<div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
    <div class="space-y-6 lg:col-span-4">
        <div x-data="{ uploading: false }" x-on:livewire-upload-start="uploading = true"
            x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-error="uploading = false"
            class="profile-frame group relative flex aspect-square items-center justify-center overflow-hidden bg-[#050505]">
            @if($newAvatar)
                <img src="{{ $newAvatar->temporaryUrl() }}" class="h-full w-full object-cover">
            @elseif(auth()->user()->avatar)
                <img src="{{ auth()->user()->avatar }}"
                    class="h-full w-full object-cover opacity-75 transition duration-300 group-hover:scale-[1.015] group-hover:opacity-60">
            @else
                <div class="mono text-[10px] uppercase tracking-[0.35em] text-white/20">{{ __('ui.profile.no_avatar') }}</div>
            @endif

            <div x-show="uploading" class="absolute inset-0 z-20 flex items-center justify-center bg-black/80">
                <div class="mono animate-pulse text-[10px] uppercase tracking-[0.35em] text-green-400">{{ __('ui.profile.uploading') }}
                </div>
            </div>

            <label
                class="absolute inset-0 flex cursor-pointer flex-col justify-end bg-gradient-to-t from-black via-black/60 to-transparent p-5 opacity-0 transition duration-300 group-hover:opacity-100">
                <input type="file" wire:model="newAvatar" accept="image/*" class="hidden">
                <span class="ui-btn w-full py-3 mono text-[10px] font-bold tracking-[0.25em]">
                    {{ __('ui.profile.update_picture') }}
                </span>
            </label>
        </div>

        <div class="profile-frame p-6">
            <div class="mb-5 flex items-center justify-between gap-4">
                <p class="mono text-[10px] uppercase tracking-[0.28em] text-white/30">{{ __('ui.profile.profile_status') }}</p>
                <div class="h-2 w-2 bg-green-500"></div>
            </div>
            <div class="space-y-3">
                <div class="profile-stat-line">
                    <span class="mono text-[10px] uppercase tracking-[0.22em] text-white/30">{{ __('ui.profile.last_login') }}</span>
                    <span class="mono text-[10px] uppercase tracking-[0.14em]">{{ now(config('app.timezone'))->format('H:i:s') }}</span>
                </div>
                <div class="profile-stat-line">
                    <span class="mono text-[10px] uppercase tracking-[0.22em] text-white/30">{{ __('ui.profile.access_level') }}</span>
                    <span class="mono text-[10px] uppercase tracking-[0.14em]">{{ __('ui.profile.authorized') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-8">
        <div class="profile-frame p-5 sm:p-8">
            <form wire:submit="updateSettings" class="space-y-8">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="profile-field">
                        <label class="mono block text-[10px] uppercase tracking-[0.28em] text-white/40">{{ __('ui.profile.full_name') }}</label>
                        <input type="text" wire:model="name"
                            class="mono pt-3 text-sm text-white/80">
                    </div>

                    <div class="profile-field">
                        <label class="mono block text-[10px] uppercase tracking-[0.28em] text-white/40">{{ __('ui.profile.nickname') }}</label>
                        <input type="text" wire:model="nickname"
                            class="mono pt-3 text-sm text-white/80">
                    </div>

                    <div class="profile-field">
                        <label class="mono block text-[10px] uppercase tracking-[0.28em] text-white/40">{{ __('ui.profile.email') }}</label>
                        <input type="email" wire:model="email"
                            class="mono pt-3 text-sm text-white/80">
                    </div>

                    <div class="profile-field">
                        <label class="mono block text-[10px] uppercase tracking-[0.28em] text-white/40">{{ __('ui.profile.phone') }}</label>
                        <input type="text" wire:model="phone" placeholder="+371..."
                            class="mono pt-3 text-sm text-white/80 placeholder:text-white/25">
                    </div>

                    <div class="profile-field md:col-span-2">
                        <label class="mono block text-[10px] uppercase tracking-[0.28em] text-white/40">{{ __('ui.profile.address') }}</label>
                        <input type="text" wire:model="address" placeholder="{{ __('ui.profile.address_placeholder') }}"
                            class="mono pt-3 text-sm text-white/80 placeholder:text-white/25">
                    </div>
                </div>

                @if (session()->has('message'))
                    <div class="border border-green-500/20 bg-green-500/10 px-4 py-3 mono text-[10px] uppercase tracking-[0.24em] text-green-400">
                        >> {{ session('message') }}
                    </div>
                @endif

                <div class="flex flex-col gap-5 border-t border-white/10 pt-8 sm:flex-row sm:items-center sm:justify-between">
                    <div class="mono text-[10px] uppercase tracking-[0.28em] text-white/30">
                        {{ __('ui.profile.save_hint') }}
                    </div>
                    <button type="submit"
                        class="ui-btn ui-btn-primary px-8 py-4 text-[10px] font-bold tracking-[0.25em]">
                        {{ __('ui.profile.update_info') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
