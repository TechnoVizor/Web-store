<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    // Поля формы
    public $name = '';

    public $nickname = '';

    public $email = '';

    public $password = '';

    // ЛОГИКА ВХОДА
    public function login()
    {
        $credentials = $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            session()->regenerate();

            return redirect()->intended('/');
        }

        throw ValidationException::withMessages([
            'email' => 'AUTHENTICATION_ERROR: ACCESS_DENIED',
        ]);
    }

    // ЛОГИКА РЕГИСТРАЦИИ
    public function register()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'required|string|max:50|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $this->name,
            'nickname' => $this->nickname,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        Auth::login($user);

        return redirect()->intended('/');
    }
}; ?>

<div x-data="{ mode: 'login' }" class="w-full max-w-sm mx-auto py-8 lg:py-8 antialiased mono">
    @if (session('auth_error'))
        <div class="mb-8 border border-red-900/40 bg-red-950/20 px-4 py-3 text-[8px] font-bold uppercase tracking-[0.3em] text-red-300">
            {{ session('auth_error') }}
        </div>
    @endif

    {{-- Переключатель режимов (Мгновенный через Alpine) --}}
    <div class="flex border-b border-white/5 mb-10">
        <button @click="mode = 'login'"
            :class="mode === 'login' ? 'text-white border-b border-white' : 'text-white/20 hover:text-white/40'"
            class="flex-1 py-4 text-[10px] uppercase tracking-[0.4em] transition-all duration-300">
            /Sign_In
        </button>
        <button @click="mode = 'register'"
            :class="mode === 'register' ? 'text-white border-b border-white' : 'text-white/20 hover:text-white/40'"
            class="flex-1 py-4 text-[10px] uppercase tracking-[0.4em] transition-all duration-300">
            /Register
        </button>
    </div>

    <div class="space-y-8">
        
        {{-- Поля регистрации (Показываются мгновенно) --}}
        <template x-if="mode === 'register'">
            <div class="space-y-8 animate-in fade-in slide-in-from-top-2 duration-500">
                <div class="space-y-3">
                    <label class="text-[9px] text-white/40 uppercase tracking-[0.3em] block">01 // Identity_Name</label>
                    <input type="text" wire:model="name"
                        class="w-full bg-transparent border-b border-white/10 py-3 text-base md:text-[11px] outline-none focus:border-white transition-all placeholder:text-white/5 tracking-[0.2em]"
                        placeholder="Full Name">
                    @error('name') <span class="text-red-900 text-[8px] uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-3">
                    <label class="text-[9px] text-white/40 uppercase tracking-[0.3em] block">02 // Nickname</label>
                    <input type="text" wire:model="nickname"
                        class="w-full bg-transparent border-b border-white/10 py-3 text-base md:text-[11px] outline-none focus:border-white transition-all placeholder:text-white/5  tracking-[0.2em]"
                        placeholder="Unique_ID">
                    @error('nickname') <span class="text-red-900 text-[8px] uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </template>

        {{-- Общие поля (Email) --}}
        <div class="space-y-3">
            <label class="text-[9px] text-white/40 uppercase tracking-[0.3em] block">
                <span x-text="mode === 'register' ? '03' : '01'"></span> // Communication_Email
            </label>
            <input type="email" wire:model="email"
                class="w-full bg-transparent border-b border-white/10 py-3 text-base md:text-[11px] outline-none focus:border-white transition-all placeholder:text-white/5  tracking-[0.2em]"
                placeholder="Email_Address">
            @error('email') <span class="text-red-900 text-[8px] uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
        </div>

        {{-- Общие поля (Password) --}}
        <div class="space-y-3">
            <label class="text-[9px] text-white/40 uppercase tracking-[0.3em] block">
                <span x-text="mode === 'register' ? '04' : '02'"></span> // Password
            </label>
            <input type="password" wire:model="password"
                class="w-full bg-transparent border-b Up border-white/10 py-3 text-base md:text-[11px] outline-none focus:border-white transition-all placeholder:text-white/5  tracking-[0.2em]"
                placeholder="••••••••">
            @error('password') <span class="text-red-900 text-[8px] uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
        </div>

        {{-- Кнопка действия --}}
        <div class="pt-6">
            <button @click="mode === 'login' ? $wire.login() : $wire.register()"
                class="w-full py-5 bg-white text-black font-bold uppercase text-[10px] tracking-[0.4em] hover:bg-zinc-200 transition-all active:scale-[0.98]">
                <span x-text="mode === 'login' ? 'login' : 'Register'"></span>
            </button>
        </div>

        {{-- Разделитель --}}
        <div class="relative py-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-white/5"></div>
            </div>
            <div class="relative flex justify-center text-[7px] uppercase tracking-[0.4em]">
                <span class="bg-black px-4 text-white/20">Social_Gateway_Active</span>
            </div>
        </div>

        {{-- Google Auth --}}
        <a href="{{ route('google.login') }}"
            class="flex items-center justify-center space-x-4 w-full py-5 border border-white/10 text-white font-bold uppercase text-[10px] tracking-[0.2em] transition-all hover:bg-white/5 active:scale-95">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24">
                <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" />
                <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 1.2-4.53z" />
            </svg>
            <span>Auth_via_Google</span>
        </a>
    </div>
</div>
