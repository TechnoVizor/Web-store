<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? __('ui.brand') }}</title>
    <meta name="description" content="{{ __('ui.footer.description') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 16px;
            background-color: #000;
            color: rgba(255, 255, 255, 0.8);
            letter-spacing: -0.02em;
            -webkit-font-smoothing: antialiased;
        }

        /* ИСПРАВЛЕНО: Теперь тут реально Mono шрифт */
        .mono {
            font-family: "JetBrains Mono", "SFMono-Regular", Consolas, "Liberation Mono", ui-monospace, monospace !important;
        }

        .text-\[7px\],
        .text-\[8px\],
        .text-\[9px\],
        .text-\[10px\],
        .text-\[11px\] { font-size: 12px !important; }

        .text-white\/10 { color: rgba(255, 255, 255, 0.46) !important; }
        .text-white\/20 { color: rgba(255, 255, 255, 0.58) !important; }
        .text-white\/30 { color: rgba(255, 255, 255, 0.66) !important; }
        .text-white\/40 { color: rgba(255, 255, 255, 0.74) !important; }
        .text-white { color: rgba(255, 255, 255, 0.8) !important; }
        .bg-white { background-color: rgba(255, 255, 255, 0.8) !important; }
        .border-white { border-color: rgba(255, 255, 255, 0.8) !important; }
        .fill-white { fill: rgba(255, 255, 255, 0.8) !important; }
        .text-black,
        .hover\:text-black:hover,
        .group:hover .group-hover\:text-black {
            color: #050505 !important;
        }
        .hover\:bg-white:hover {
            background-color: rgba(255, 255, 255, 0.8) !important;
        }
        .hover\:border-white:hover {
            border-color: rgba(255, 255, 255, 0.8) !important;
        }

        .nav-action {
            position: relative;
            display: inline-flex;
            align-items: center;
            min-height: 2.25rem;
            border: 1px solid transparent;
            padding: 0.5rem 0.65rem;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            transition: color 180ms ease, border-color 180ms ease, background-color 180ms ease, transform 180ms ease;
        }

        .nav-action::before,
        .nav-action::after {
            content: "";
            position: absolute;
            height: 1px;
            width: 0.45rem;
            background: currentColor;
            opacity: 0;
            transition: opacity 180ms ease, transform 180ms ease;
        }

        .nav-action::before {
            top: -1px;
            left: -1px;
            transform: translate(-4px, -2px);
        }

        .nav-action::after {
            right: -1px;
            bottom: -1px;
            transform: translate(4px, 2px);
        }

        .nav-action:hover,
        .nav-action:focus-visible {
            border-color: rgba(255, 255, 255, 0.24);
            background: rgba(255, 255, 255, 0.045);
            color: rgba(255, 255, 255, 0.86) !important;
            transform: translateY(-1px);
            outline: none;
        }

        .nav-action:hover::before,
        .nav-action:hover::after,
        .nav-action:focus-visible::before,
        .nav-action:focus-visible::after {
            opacity: 0.8;
            transform: translate(0, 0);
        }

        .nav-action-danger {
            color: rgba(255, 255, 255, 0.28);
        }

        .nav-action-active {
            border-color: rgba(255, 255, 255, 0.22);
            background: rgba(255, 255, 255, 0.055);
            color: rgba(255, 255, 255, 0.86) !important;
        }

        .nav-action-danger:hover,
        .nav-action-danger:focus-visible {
            border-color: rgba(239, 68, 68, 0.45);
            background: rgba(239, 68, 68, 0.10);
            color: rgba(252, 165, 165, 0.92) !important;
        }

        .brand-mark {
            position: relative;
            display: inline-flex;
            align-items: center;
            min-height: 2.5rem;
            border: 1px solid transparent;
            padding: 0.35rem 0.55rem;
            transition: border-color 220ms ease, background-color 220ms ease, transform 220ms ease, letter-spacing 220ms ease;
        }

        .brand-mark::before,
        .brand-mark::after {
            content: "";
            position: absolute;
            width: 0.55rem;
            height: 0.55rem;
            opacity: 0;
            transition: opacity 220ms ease, transform 220ms ease;
            pointer-events: none;
        }

        .brand-mark::before {
            top: -1px;
            left: -1px;
            border-top: 1px solid rgba(255,255,255,0.55);
            border-left: 1px solid rgba(255,255,255,0.55);
            transform: translate(-3px, -3px);
        }

        .brand-mark::after {
            right: -1px;
            bottom: -1px;
            border-right: 1px solid rgba(255,255,255,0.55);
            border-bottom: 1px solid rgba(255,255,255,0.55);
            transform: translate(3px, 3px);
        }

        .brand-mark:hover,
        .brand-mark:focus-visible {
            border-color: rgba(255,255,255,0.16);
            background: rgba(255,255,255,0.035);
            transform: translateY(-1px);
            letter-spacing: -0.015em;
            outline: none;
        }

        .brand-mark:hover::before,
        .brand-mark:hover::after,
        .brand-mark:focus-visible::before,
        .brand-mark:focus-visible::after {
            opacity: 1;
            transform: translate(0, 0);
        }

        .ui-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(255, 255, 255, 0.025);
            color: rgba(255, 255, 255, 0.78);
            text-transform: uppercase;
            transition: color 180ms ease, border-color 180ms ease, background-color 180ms ease, transform 180ms ease, opacity 180ms ease;
        }

        .ui-btn::before,
        .ui-btn::after {
            content: "";
            position: absolute;
            height: 0.45rem;
            width: 0.45rem;
            opacity: 0;
            transition: opacity 180ms ease, transform 180ms ease, border-color 180ms ease;
        }

        .ui-btn::before {
            top: -1px;
            left: -1px;
            border-top: 1px solid currentColor;
            border-left: 1px solid currentColor;
            transform: translate(-3px, -3px);
        }

        .ui-btn::after {
            right: -1px;
            bottom: -1px;
            border-right: 1px solid currentColor;
            border-bottom: 1px solid currentColor;
            transform: translate(3px, 3px);
        }

        .ui-btn:hover,
        .ui-btn:focus-visible {
            border-color: rgba(255, 255, 255, 0.34);
            background: rgba(255, 255, 255, 0.07);
            color: rgba(255, 255, 255, 0.94) !important;
            transform: translateY(-1px);
            outline: none;
        }

        .ui-btn:hover::before,
        .ui-btn:hover::after,
        .ui-btn:focus-visible::before,
        .ui-btn:focus-visible::after {
            opacity: 0.72;
            transform: translate(0, 0);
        }

        .ui-btn-primary {
            border-color: rgba(255, 255, 255, 0.78);
            background: rgba(255, 255, 255, 0.80);
            color: #050505 !important;
        }

        .ui-btn-primary:hover,
        .ui-btn-primary:focus-visible {
            border-color: rgba(255, 255, 255, 0.92);
            background: rgba(255, 255, 255, 0.88);
            color: #050505 !important;
        }

        .ui-btn-danger:hover,
        .ui-btn-danger:focus-visible {
            border-color: rgba(239, 68, 68, 0.55);
            background: rgba(239, 68, 68, 0.10);
            color: rgba(252, 165, 165, 0.95) !important;
        }

        .ui-btn-compact {
            min-height: 2rem;
            padding: 0.45rem 0.7rem;
        }

        .ui-btn-icon {
            min-height: 2rem;
            min-width: 2rem;
            padding: 0.45rem;
        }

        .ui-btn:disabled {
            cursor: not-allowed;
            opacity: 0.45;
            transform: none;
        }

        .glass {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .animate-shimmer {
            position: relative;
            overflow: hidden;
        }

        .animate-shimmer::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05), transparent);
            animation: shimmer 1.5s infinite;
        }

        .livewire-progress-bar {
            background: #fff !important;
            height: 1px !important;
        }

        .skeleton-shimmer {
            background: linear-gradient(90deg, #0a0a0a 25%, #1a1a1a 50%, #0a0a0a 75%);
            background-size: 200% 100%;
            animation: skeleton-load 1.5s infinite linear;
        }

        @keyframes skeleton-load {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        input::placeholder,
        textarea::placeholder {
            color: rgba(255, 255, 255, 0.2) !important;
            /* Твой цвет (белый 20%) */
            opacity: 1 !important;
            /* Важно для Firefox! */
        }

        body :is(input:not([type="checkbox"]):not([type="radio"]):not([type="file"]):not([type="submit"]):not([type="button"]), textarea, select) {
            font-size: 16px !important;
            line-height: 1.5rem !important;
        }

        /* Internet Explorer 10-11 */
        input:-ms-input-placeholder,
        textarea:-ms-input-placeholder {
            color: rgba(255, 255, 255, 0.2) !important;
        }

        /* Microsoft Edge */
        input::-ms-input-placeholder,
        textarea::-ms-input-placeholder {
            color: rgba(255, 255, 255, 0.2) !important;
        }
    </style>
    @livewireStyles
</head>

<body class="antialiased">
    {{-- Навигация --}}
    <nav x-data="{ mobileMenuOpen: false }"
        class="sticky top-0 z-50 w-full border-b border-white/5 bg-black/60 backdrop-blur-xl">
        <div class="container mx-auto px-6 h-14 flex items-center justify-between">

            <div class="flex items-center space-x-12">
                <a href="/" wire:navigate class="brand-mark group">
                    <span class="text-xl font-black tracking-tighter uppercase">
                        DIGI<span
                            class="text-white/20 group-hover:text-white transition-colors duration-500">_</span>STORE
                    </span>
                </a>

                <div
                    class="hidden lg:flex items-center gap-3 text-[10px] font-medium tracking-[0.2em] text-white/40">
                    <a href="/" wire:navigate
                        class="nav-action">{{ __('ui.nav.collections') }}</a>
                    <a href="/orders" wire:navigate
                        class="nav-action">{{ __('ui.nav.archive') }}</a>
                    <a href="/labs" wire:navigate class="nav-action">{{ __('ui.nav.labs') }}</a>

                    @auth
                        @if(auth()->user()->is_admin || auth()->user()->is_super_admin)
                            <a href="/admin" wire:navigate target="_blank"
                                class="nav-action group">
                                <span class="w-[1px] h-3 bg-white/10 mx-2"></span>
                                <svg class="w-3.5 h-3.5 transition-transform duration-700 group-hover:rotate-180" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span
                                    class="ml-2 text-[9px] font-bold tracking-[0.3em] opacity-0 group-hover:opacity-100 transition-opacity uppercase">{{ __('ui.nav.admin_panel') }}</span>
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="flex items-center space-x-6">
                <div class="hidden md:block">
                    <livewire:search lazy />
                </div>

                <div class="hidden lg:block h-4 w-[1px] bg-white/10 mx-2"></div>

                <div class="flex items-center space-x-6 text-[10px] font-medium tracking-[0.2em]">
                    <div class="hidden lg:flex items-center gap-2">
                        @auth
                            <a href="/profile" wire:navigate
                                class="nav-action flex items-center space-x-2">
                                <span class="h-1.5 w-1.5 border border-white/60 bg-white/20 transition-colors"></span>
                                <span>{{ __('ui.nav.account') }}</span>
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="nav-action nav-action-danger">{{ __('ui.nav.logout') }}</button>
                            </form>
                        @else
                            <a href="/login" wire:navigate
                                class="nav-action">{{ __('ui.nav.sign_in') }}</a>
                        @endauth
                    </div>

                    <div class="hidden lg:flex items-center gap-1 text-[9px] tracking-[0.2em] uppercase">
                        @foreach(['en' => 'EN', 'ru' => 'RU', 'lv' => 'LV'] as $locale => $label)
                            <a href="{{ route('language.switch', $locale) }}"
                                class="nav-action min-h-8 px-2 py-1 {{ app()->getLocale() === $locale ? 'nav-action-active' : '' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                    <div class="relative">
                        <livewire:cart-count />
                    </div>

                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        :aria-label="mobileMenuOpen ? 'Close navigation' : 'Open navigation'"
                        class="lg:hidden relative w-6 h-5 focus:outline-none z-50">
                        <div class="relative flex items-center justify-center">
                            <span :class="mobileMenuOpen ? 'rotate-45 translate-y-0' : '-translate-y-1.5'"
                                class="absolute block w-6 h-px bg-white transition-all duration-300"></span>
                            <span :class="mobileMenuOpen ? 'opacity-0' : 'opacity-100'"
                                class="absolute block w-6 h-px bg-white transition-all duration-300"></span>
                            <span :class="mobileMenuOpen ? '-rotate-45 translate-y-0' : 'translate-y-1.5'"
                                class="absolute block w-6 h-px bg-white transition-all duration-300"></span>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-10" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-10"
            class="lg:hidden absolute top-full left-0 w-full bg-black/95 backdrop-blur-2xl border-b border-white/5 py-8 px-6 space-y-8 shadow-2xl"
            style="display: none;">

            <div class="flex flex-col space-y-6 text-[12px] font-medium tracking-[0.3em] uppercase">
                <a href="/" @click="mobileMenuOpen = false" wire:navigate
                    class="nav-action w-full justify-between">{{ __('ui.nav.collections') }}</a>
                <a href="/orders" @click="mobileMenuOpen = false" wire:navigate
                    class="nav-action w-full justify-between">{{ __('ui.nav.archive') }}</a>
                <a href="/labs" @click="mobileMenuOpen = false" wire:navigate
                    class="nav-action w-full justify-between">{{ __('ui.nav.labs') }}</a>
            </div>

            <div class="flex items-center gap-4 text-[11px] tracking-[0.25em] uppercase">
                <span class="text-white/25">{{ __('ui.nav.language') }}</span>
                @foreach(['en' => 'EN', 'ru' => 'RU', 'lv' => 'LV'] as $locale => $label)
                    <a href="{{ route('language.switch', $locale) }}"
                        class="nav-action min-h-8 px-2 py-1 {{ app()->getLocale() === $locale ? 'nav-action-active' : '' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div class="pt-8 border-t border-white/5 flex flex-col space-y-6">
                @auth
                    <a href="/profile" @click="mobileMenuOpen = false" wire:navigate
                        class="nav-action text-[12px] tracking-[0.2em]">
                        <span>{{ __('ui.nav.account') }}</span>
                    </a>

                    @if(auth()->user()->is_admin)
                        <a href="/admin" wire:navigate
                            class="nav-action text-[12px] tracking-[0.2em]">{{ __('ui.nav.admin_panel') }}</a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-action nav-action-danger text-[12px] tracking-[0.2em]">{{ __('ui.nav.logout') }}</button>
                    </form>
                @else
                    <a href="/login" @click="mobileMenuOpen = false" wire:navigate
                        class="nav-action text-[12px] tracking-[0.2em]">{{ __('ui.nav.sign_in') }}</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ГИБРИДНЫЙ КОНТЕНТ --}}
    <main>
        @if(isset($slot))
            {{ $slot }}
        @else
            @yield('content')
        @endif
    </main>

    <livewire:system-alert />

    <footer class="mt-20 border-t border-white/5 bg-black/20 py-16">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 md:gap-8">

                <div class="md:col-span-4 space-y-6">
                    <a href="/" wire:navigate class="text-xl font-black tracking-tighter uppercase">
                        DIGI<span class="text-white/20">_</span>STORE
                    </a>
                    <p class="text-[10px] leading-relaxed text-white/30 tracking-widest uppercase max-w-xs">
                        {{ __('ui.footer.description') }}
                    </p>
                </div>

                <div class="md:col-span-2 space-y-4">
                    <h4 class="text-[10px] font-bold tracking-[0.3em] text-white uppercase">{{ __('ui.footer.sitemap') }}</h4>
                    <ul class="space-y-2 text-[10px] tracking-widest text-white/40 uppercase">
                        <li><a href="/" wire:navigate class="hover:text-white transition-colors">{{ __('ui.nav.collections') }}</a>
                        </li>
                        <li><a href="/orders" wire:navigate class="hover:text-white transition-colors">{{ __('ui.nav.archive') }}</a>
                        </li>
                        <li><a href="/about" wire:navigate
                                class="hover:text-white transition-colors">{{ __('ui.footer.about') }}</a></li>
                        <li><a href="/privacy" wire:navigate
                                class="hover:text-white transition-colors">{{ __('ui.footer.privacy') }}</a></li>
                        <li><a href="/terms" wire:navigate class="hover:text-white transition-colors">{{ __('ui.footer.terms') }}</a>
                        </li>
                    </ul>
                </div>

                <div class="md:col-span-2 space-y-4">
                    <h4 class="text-[10px] font-bold tracking-[0.3em] text-white uppercase">{{ __('ui.footer.connect') }}</h4>
                    <ul class="space-y-2 text-[10px] tracking-widest text-white/40 uppercase">
                        <li><a href="#" class="hover:text-white transition-colors">Instagram</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Twitter (X)</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Discord</a></li>
                    </ul>
                </div>

                <div class="md:col-span-4 space-y-4">
                    <h4 class="text-[10px] font-bold tracking-[0.3em] text-white uppercase">{{ __('ui.footer.newsletter') }}</h4>
                    <form x-data="{ subscribed: false }" x-on:submit.prevent="subscribed = true" class="relative">
                        <input type="email" aria-label="{{ __('ui.footer.newsletter') }}" placeholder="{{ __('ui.footer.email_placeholder') }}"
                            class="w-full bg-transparent border-b border-white/10 py-2 pr-28 text-base md:text-[11px] mono focus:border-white outline-none transition-all placeholder:text-white/10">
                        <button type="submit"
                            class="ui-btn ui-btn-compact absolute right-0 bottom-0 min-h-11 px-3 text-[10px] font-bold transition-transform hover:-translate-y-0.5">
                            <span x-show="!subscribed">{{ __('ui.footer.join') }}</span>
                            <span x-show="subscribed" x-cloak>{{ __('ui.footer.joined') }}</span>
                        </button>
                    </form>
                </div>

            </div>

            <div
                class="mt-20 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="text-[8px] text-white/10 tracking-[0.5em] uppercase">
                    © 2026 DIGI STORE // {{ __('ui.footer.owner', ['name' => 'Illia Ponomarov']) }} // {{ __('ui.footer.rights') }}
                </div>
                <div class="flex space-x-8 text-[8px] text-white/10 tracking-[0.3em] uppercase italic">
                    <span>LVA_NODE_01</span>
                    <span>{{ __('ui.footer.stable') }}</span>
                </div>
            </div>
        </div>
    </footer>
    <livewire:chat-bot />


    @livewireScripts
    <script>
        (() => {
            const playHeroVideos = () => {
                document.querySelectorAll('[data-hero-video]').forEach((video) => {
                    video.muted = true;
                    video.loop = true;
                    video.playsInline = true;

                    if (video.readyState < 2) {
                        video.load();
                    }

                    video.play().catch(() => {});
                });
            };

            document.addEventListener('DOMContentLoaded', playHeroVideos);
            document.addEventListener('livewire:navigated', () => requestAnimationFrame(playHeroVideos));
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) {
                    playHeroVideos();
                }
            });
        })();
    </script>

</body>

</html>
