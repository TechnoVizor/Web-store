<?php

use App\Models\User;
use App\Support\CustomerOrders;
use App\Support\Phone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    // Поля формы
    public $name = '';

    public $nickname = '';

    public $email = '';

    public $phone = '';

    public $loginIdentifier = '';

    public $password = '';

    public $password_confirmation = '';

    // ЛОГИКА ВХОДА
    public function login()
    {
        $this->validate([
            'loginIdentifier' => 'required|string',
            'password' => 'required',
        ]);

        $identifier = trim($this->loginIdentifier);
        $phone = Phone::normalize($identifier);

        $user = str_contains($identifier, '@')
            ? User::where('email', Str::lower($identifier))->first()
            : $this->findUserByPhone($phone);

        if (! $user || ! Hash::check($this->password, $user->password)) {
            throw ValidationException::withMessages([
                'loginIdentifier' => 'AUTHENTICATION_ERROR: ACCESS_DENIED',
            ]);
        }

        Auth::login($user);
        session()->regenerate();
        CustomerOrders::attachGuestOrders($user);

        return redirect()->intended('/');
    }

    // ЛОГИКА РЕГИСТРАЦИИ
    public function register()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'required|string|max:50|unique:users',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'required|string|min:10',
            'password' => 'required|min:8|confirmed',
        ]);

        $normalizedPhone = Phone::normalize($this->phone);

        if (! $normalizedPhone) {
            throw ValidationException::withMessages([
                'phone' => 'PHONE_ERROR: INVALID_NUMBER',
            ]);
        }

        if ($this->findUserByPhone($normalizedPhone)) {
            throw ValidationException::withMessages([
                'phone' => 'PHONE_ERROR: ALREADY_REGISTERED',
            ]);
        }

        $user = User::create([
            'name' => $this->name,
            'nickname' => $this->nickname,
            'email' => filled($this->email) ? Str::lower($this->email) : $this->phoneEmail($normalizedPhone),
            'phone' => $this->phone,
            'password' => Hash::make($this->password),
        ]);

        Auth::login($user);
        CustomerOrders::attachGuestOrders($user);

        return redirect()->intended('/');
    }

    private function phoneEmail(string $normalizedPhone): string
    {
        return 'phone_'.$normalizedPhone.'_'.Str::lower(Str::random(8)).'@phone.local';
    }

    private function findUserByPhone(?string $phone): ?User
    {
        if (! $phone) {
            return null;
        }

        if (Schema::hasColumn('users', 'phone_normalized')) {
            return User::where('phone_normalized', $phone)->first();
        }

        return User::whereNotNull('phone')
            ->get()
            ->first(fn (User $user): bool => Phone::normalize($user->phone) === $phone);
    }
}; ?>

<div x-data="{ mode: 'login' }" class="w-full antialiased">
    @if (session('auth_error'))
        <div class="mb-6 border border-red-900/40 bg-red-950/20 px-4 py-3 mono text-[10px] font-bold uppercase tracking-[0.22em] text-red-300">
            {{ session('auth_error') }}
        </div>
    @endif

    <div class="mb-8">
        <p class="mono mb-3 text-[10px] uppercase tracking-[0.32em] text-white/35">Account access</p>
        <h2 class="text-3xl font-black uppercase tracking-tight text-white/80" x-text="mode === 'login' ? 'Welcome back' : 'Create account'"></h2>
        <p class="mt-3 text-sm leading-6 text-white/45" x-text="mode === 'login' ? 'Use your phone number or email to continue.' : 'Register by phone so your guest orders stay connected.'"></p>
    </div>

    <div class="mb-8 grid grid-cols-2 border border-white/10 bg-white/[0.02] p-1">
        <button type="button" @click="mode = 'login'"
            :class="mode === 'login' ? 'bg-white/80 text-black' : 'text-white/45 hover:text-white/75'"
            class="px-4 py-3 mono text-[10px] font-bold uppercase tracking-[0.24em] transition-colors">
            Sign in
        </button>
        <button type="button" @click="mode = 'register'"
            :class="mode === 'register' ? 'bg-white/80 text-black' : 'text-white/45 hover:text-white/75'"
            class="px-4 py-3 mono text-[10px] font-bold uppercase tracking-[0.24em] transition-colors">
            Register
        </button>
    </div>

    <div class="space-y-5">
        <template x-if="mode === 'register'">
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="auth-field">
                    <label class="mono mb-2 block text-[10px] uppercase tracking-[0.22em] text-white/38">First name</label>
                    <input type="text" wire:model="name"
                        class="w-full bg-transparent py-1 text-base text-white/80 outline-none placeholder:text-white/18"
                        placeholder="Illia">
                    @error('name') <span class="text-red-900 text-[8px] uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                </div>

                <div class="auth-field">
                    <label class="mono mb-2 block text-[10px] uppercase tracking-[0.22em] text-white/38">Last name</label>
                    <input type="text" wire:model="nickname"
                        class="w-full bg-transparent py-1 text-base text-white/80 outline-none placeholder:text-white/18"
                        placeholder="Ponomarov">
                    @error('nickname') <span class="text-red-900 text-[8px] uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </template>

        <template x-if="mode === 'register'">
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="auth-field">
                    <label class="mono mb-2 block text-[10px] uppercase tracking-[0.22em] text-white/38">Phone</label>
                    <input type="tel" wire:model="phone"
                        class="w-full bg-transparent py-1 text-base text-white/80 outline-none placeholder:text-white/18"
                        placeholder="+371 20000000">
                    @error('phone') <span class="text-red-900 text-[8px] uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                </div>

                <div class="auth-field">
                    <label class="mono mb-2 block text-[10px] uppercase tracking-[0.22em] text-white/38">Email optional</label>
                    <input type="email" wire:model="email"
                        class="w-full bg-transparent py-1 text-base text-white/80 outline-none placeholder:text-white/18"
                        placeholder="you@example.com">
                    @error('email') <span class="text-red-900 text-[8px] uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </template>

        <template x-if="mode === 'login'">
            <div class="auth-field">
                <label class="mono mb-2 block text-[10px] uppercase tracking-[0.22em] text-white/38">Email or phone</label>
                <input type="text" wire:model="loginIdentifier"
                    class="w-full bg-transparent py-1 text-base text-white/80 outline-none placeholder:text-white/18"
                    placeholder="you@example.com / +371 20000000">
                @error('loginIdentifier') <span class="text-red-900 text-[8px] uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
            </div>
        </template>

        <div class="auth-field">
            <label class="mono mb-2 block text-[10px] uppercase tracking-[0.22em] text-white/38">Password</label>
            <input type="password" wire:model="password"
                class="w-full bg-transparent py-1 text-base text-white/80 outline-none placeholder:text-white/18"
                placeholder="••••••••">
            @error('password') <span class="text-red-900 text-[8px] uppercase tracking-widest mt-2 block">{{ $message }}</span> @enderror
        </div>

        <template x-if="mode === 'register'">
            <div class="auth-field">
                <label class="mono mb-2 block text-[10px] uppercase tracking-[0.22em] text-white/38">Confirm password</label>
                <input type="password" wire:model="password_confirmation"
                    class="w-full bg-transparent py-1 text-base text-white/80 outline-none placeholder:text-white/18"
                    placeholder="••••••••">
            </div>
        </template>

        <div class="pt-3">
            <button type="button" @click="mode === 'login' ? $wire.login() : $wire.register()"
                class="ui-btn ui-btn-primary w-full py-4 font-bold text-[10px] tracking-[0.28em] active:scale-[0.98]">
                <span x-text="mode === 'login' ? 'Sign in' : 'Create account'"></span>
            </button>
        </div>

        <div class="relative py-3">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-white/8"></div>
            </div>
            <div class="relative flex justify-center mono text-[10px] uppercase tracking-[0.22em]">
                <span class="bg-[#060606] px-4 text-white/30">Or continue with</span>
            </div>
        </div>

        <a href="{{ route('google.login') }}"
            class="ui-btn flex w-full items-center justify-center space-x-4 py-4 font-bold text-[10px] tracking-[0.2em] active:scale-95">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24">
                <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" />
                <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 1.2-4.53z" />
            </svg>
            <span>Google</span>
        </a>
    </div>

    <style>
        .auth-field {
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.025);
            padding: 0.95rem 1rem;
            transition: border-color 180ms ease, background-color 180ms ease;
        }

        .auth-field:focus-within {
            border-color: rgba(255, 255, 255, 0.32);
            background: rgba(255, 255, 255, 0.045);
        }
    </style>
</div>
