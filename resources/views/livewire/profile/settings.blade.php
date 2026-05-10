<?php

use Livewire\Volt\Component;
use App\Models\User;
use App\Support\CustomerOrders;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public $name;
    public $nickname;
    public $email;
    public $phone;
    public $address;
    public $avatar;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->nickname = $user->nickname;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->avatar = $user->avatar;
    }

    public function updateSettings()
    {
        $user = Auth::user();

        $this->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'required|string|max:50|unique:users,nickname,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'required|in:' . implode(',', User::AVATARS),
        ]);

        $data = [
            'name' => $this->name,
            'nickname' => $this->nickname,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'avatar' => $this->avatar,
        ];

        $user->update($data);
        CustomerOrders::attachGuestOrders($user);

        $this->dispatch('cart-updated');
        session()->flash('message', __('ui.profile.updated'));
    }
}; ?>

<div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
    <div class="space-y-5 lg:col-span-4">
        <div class="profile-frame overflow-hidden bg-[#050505]">
            <div class="aspect-square bg-black">
                <img src="{{ $avatar }}" class="h-full w-full object-cover opacity-85">
            </div>

            <div class="grid grid-cols-2 gap-3 border-t border-white/10 p-4">
                @foreach(\App\Models\User::AVATARS as $option)
                    <label class="group/avatar cursor-pointer">
                        <input type="radio" wire:model.live="avatar" value="{{ $option }}" class="peer sr-only">
                        <span class="block border border-white/10 bg-black p-2 transition-all group-hover/avatar:border-white/30 peer-checked:border-white/70 peer-checked:bg-white/8">
                            <img src="{{ $option }}" class="aspect-square w-full object-cover opacity-70 transition-opacity peer-checked:opacity-100">
                            <span class="mt-2 block text-center mono text-[9px] uppercase tracking-[0.18em] text-white/35 peer-checked:text-white/75">
                                {{ $loop->first ? __('ui.profile.avatar_male') : __('ui.profile.avatar_female') }}
                            </span>
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="profile-frame p-5">
            <p class="mono mb-4 text-[10px] uppercase tracking-[0.28em] text-white/35">{{ __('ui.profile.profile_status') }}</p>
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
        <div class="profile-frame p-5 sm:p-7">
            <form wire:submit="updateSettings" class="space-y-8">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="profile-field">
                        <label class="mono block text-[10px] uppercase tracking-[0.28em] text-white/40">{{ __('ui.profile.full_name') }}</label>
                        <input type="text" wire:model="name"
                            class="mono pt-3 text-base text-white/80">
                    </div>

                    <div class="profile-field">
                        <label class="mono block text-[10px] uppercase tracking-[0.28em] text-white/40">{{ __('ui.profile.nickname') }}</label>
                        <input type="text" wire:model="nickname"
                            class="mono pt-3 text-base text-white/80">
                    </div>

                    <div class="profile-field">
                        <label class="mono block text-[10px] uppercase tracking-[0.28em] text-white/40">{{ __('ui.profile.email') }}</label>
                        <input type="email" wire:model="email"
                            class="mono pt-3 text-base text-white/80">
                    </div>

                    <div class="profile-field">
                        <label class="mono block text-[10px] uppercase tracking-[0.28em] text-white/40">{{ __('ui.profile.phone') }}</label>
                        <input type="text" wire:model="phone" placeholder="+371..."
                            class="mono pt-3 text-base text-white/80 placeholder:text-white/25">
                    </div>

                    <div class="profile-field md:col-span-2">
                        <label class="mono block text-[10px] uppercase tracking-[0.28em] text-white/40">{{ __('ui.profile.address') }}</label>
                        <input type="text" wire:model="address" placeholder="{{ __('ui.profile.address_placeholder') }}"
                            class="mono pt-3 text-base text-white/80 placeholder:text-white/25">
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
