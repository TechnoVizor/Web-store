@extends('layouts.app')

@section('content')
<style>
    .profile-shell {
        --profile-line: rgba(255, 255, 255, 0.08);
        --profile-line-strong: rgba(255, 255, 255, 0.16);
        --profile-panel: rgba(255, 255, 255, 0.025);
    }

    .profile-frame {
        position: relative;
        border: 1px solid var(--profile-line);
        background:
            linear-gradient(135deg, rgba(255, 255, 255, 0.045), rgba(255, 255, 255, 0.012) 42%, rgba(255, 255, 255, 0.022)),
            #050505;
    }

    .profile-frame::before,
    .profile-frame::after {
        content: "";
        position: absolute;
        width: 1rem;
        height: 1rem;
        pointer-events: none;
        opacity: 0.8;
    }

    .profile-frame::before {
        top: -1px;
        left: -1px;
        border-top: 1px solid rgba(255, 255, 255, 0.36);
        border-left: 1px solid rgba(255, 255, 255, 0.36);
    }

    .profile-frame::after {
        right: -1px;
        bottom: -1px;
        border-right: 1px solid rgba(255, 255, 255, 0.24);
        border-bottom: 1px solid rgba(255, 255, 255, 0.24);
    }

    .profile-field {
        border: 1px solid var(--profile-line);
        background: rgba(255, 255, 255, 0.022);
        padding: 1rem;
        transition: border-color 180ms ease, background-color 180ms ease;
    }

    .profile-field:focus-within {
        border-color: rgba(255, 255, 255, 0.32);
        background: rgba(255, 255, 255, 0.045);
    }

    .profile-field input {
        width: 100%;
        border: 0;
        background: transparent;
        outline: none;
    }

    .profile-field input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0 1000px #101010 inset;
        -webkit-text-fill-color: rgba(255, 255, 255, 0.8);
    }

    .profile-stat-line {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        border-top: 1px solid var(--profile-line);
        padding-top: 0.9rem;
    }

    @media (max-width: 640px) {
        .profile-field {
            padding: 0.9rem;
        }
    }
</style>

<main class="profile-shell container mx-auto px-6 py-20 max-w-6xl">
    <div class="profile-frame mb-12 overflow-hidden">
        <div class="grid gap-8 px-6 py-8 md:grid-cols-[1fr_auto] md:items-end md:px-8">
            <div class="flex items-start gap-4">
                <div class="mt-1 h-12 w-px bg-white/60"></div>
                <div>
                    <p class="mono mb-3 text-[10px] uppercase tracking-[0.45em] text-white/30">
                        {{ __('ui.profile.access') }}
                    </p>
                    <h1 class="text-4xl font-black uppercase tracking-tighter sm:text-5xl">
                        {{ __('ui.profile.title') }}
                    </h1>
                </div>
            </div>

            <div class="mono grid gap-2 text-[10px] uppercase tracking-[0.24em] text-white/40 sm:text-right">
                <span>{{ __('ui.profile.profile_status') }}</span>
                <span class="text-green-400">{{ __('ui.profile.active') }}</span>
            </div>
        </div>
    </div>

    <livewire:profile.settings />
    <livewire:profile.wishlist />
</main>
@endsection
