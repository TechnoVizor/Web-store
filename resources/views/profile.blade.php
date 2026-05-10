@extends('layouts.app')

@section('content')
<style>
    .profile-shell {
        --profile-line: rgba(255, 255, 255, 0.08);
    }

    .profile-frame {
        border: 1px solid var(--profile-line);
        background: #060606;
    }

    .profile-field {
        border: 1px solid var(--profile-line);
        background: #0b0b0b;
        padding: 1rem;
        transition: border-color 180ms ease;
    }

    .profile-field:focus-within {
        border-color: rgba(255, 255, 255, 0.32);
    }

    .profile-field input {
        width: 100%;
        border: 0;
        background: transparent;
        font-size: 16px;
        line-height: 1.5rem;
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
    <div class="mb-12 border-b border-white/8 pb-8">
        <div class="grid gap-6 md:grid-cols-[1fr_auto] md:items-end">
            <div class="flex items-start gap-4">
                <div class="mt-1 h-12 w-px bg-white/60"></div>
                <div>
                    <p class="mono mb-3 text-[10px] uppercase tracking-[0.45em] text-white/30">
                        {{ __('ui.profile.access') }}
                    </p>
                    <h1 class="text-4xl font-black uppercase tracking-tight sm:text-5xl">
                        {{ __('ui.profile.title') }}
                    </h1>
                </div>
            </div>

            <div class="border border-white/10 bg-white/[0.015] px-4 py-3">
                <div class="flex items-center gap-3">
                    <span class="h-2 w-2 bg-green-500"></span>
                    <span class="mono text-[10px] uppercase tracking-[0.22em] text-white/60">{{ __('ui.profile.active') }}</span>
                </div>
            </div>
        </div>
    </div>

    <livewire:profile.settings />
    <livewire:profile.orders-preview />
    <livewire:profile.wishlist />
</main>
@endsection
