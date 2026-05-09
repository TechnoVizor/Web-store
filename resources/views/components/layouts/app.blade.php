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
                <a href="/" wire:navigate class="group flex items-center space-x-2">
                    <span class="text-xl font-black tracking-tighter uppercase">
                        DIGI<span
                            class="text-white/20 group-hover:text-white transition-colors duration-500">_</span>STORE
                    </span>
                </a>

                <div
                    class="hidden lg:flex items-center space-x-8 text-[10px] font-medium tracking-[0.2em] text-white/40">
                    <a href="/" wire:navigate
                        class="hover:text-white transition-colors uppercase">{{ __('ui.nav.collections') }}</a>
                    <a href="/orders" wire:navigate
                        class="hover:text-white transition-colors uppercase">{{ __('ui.nav.archive') }}</a>
                    <a href="/labs" wire:navigate class="hover:text-white transition-colors uppercase">{{ __('ui.nav.labs') }}</a>

                    @auth
                        @if(auth()->user()->is_admin || auth()->user()->is_super_admin)
                            <a href="/admin" wire:navigate target="_blank"
                                class="flex items-center text-white/40 hover:text-white transition-all group">
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
                    <div class="hidden lg:flex items-center space-x-6">
                        @auth
                            <a href="/profile" wire:navigate
                                class="text-white/40 hover:text-white transition-colors flex items-center space-x-2">
                                <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                                <span>{{ __('ui.nav.account') }}</span>
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="rounded-sm border border-transparent px-2 py-1 text-white/25 transition-all uppercase hover:border-red-500/40 hover:bg-red-500/10 hover:text-red-400 focus:outline-none focus-visible:border-red-400 focus-visible:text-red-300">{{ __('ui.nav.logout') }}</button>
                            </form>
                        @else
                            <a href="/login" wire:navigate
                                class="text-white/40 hover:text-white transition-colors">{{ __('ui.nav.sign_in') }}</a>
                        @endauth
                    </div>

                    <div class="hidden lg:flex items-center gap-2 text-[9px] tracking-[0.2em] uppercase">
                        @foreach(['en' => 'EN', 'ru' => 'RU', 'lv' => 'LV'] as $locale => $label)
                            <a href="{{ route('language.switch', $locale) }}"
                                class="{{ app()->getLocale() === $locale ? 'text-white' : 'text-white/25 hover:text-white/70' }} transition-colors">
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
                    class="text-white/60 hover:text-white">{{ __('ui.nav.collections') }}</a>
                <a href="/orders" @click="mobileMenuOpen = false" wire:navigate
                    class="text-white/60 hover:text-white">{{ __('ui.nav.archive') }}</a>
                <a href="/labs" @click="mobileMenuOpen = false" wire:navigate
                    class="text-white/60 hover:text-white">{{ __('ui.nav.labs') }}</a>
            </div>

            <div class="flex items-center gap-4 text-[11px] tracking-[0.25em] uppercase">
                <span class="text-white/25">{{ __('ui.nav.language') }}</span>
                @foreach(['en' => 'EN', 'ru' => 'RU', 'lv' => 'LV'] as $locale => $label)
                    <a href="{{ route('language.switch', $locale) }}"
                        class="{{ app()->getLocale() === $locale ? 'text-white' : 'text-white/35' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div class="pt-8 border-t border-white/5 flex flex-col space-y-6">
                @auth
                    <a href="/profile" @click="mobileMenuOpen = false" wire:navigate
                        class="text-[12px] tracking-[0.2em] text-white flex items-center space-x-3 uppercase">
                        <span>{{ __('ui.nav.account') }}</span>
                    </a>

                    @if(auth()->user()->is_admin)
                        <a href="/admin" wire:navigate
                            class="text-[12px] tracking-[0.2em] text-blue-400 uppercase">{{ __('ui.nav.admin_panel') }}</a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="rounded-sm border border-red-500/20 px-3 py-2 text-[12px] tracking-[0.2em] text-red-400 uppercase transition-all hover:bg-red-500/10 hover:text-red-300 focus:outline-none focus-visible:border-red-300">{{ __('ui.nav.logout') }}</button>
                    </form>
                @else
                    <a href="/login" @click="mobileMenuOpen = false" wire:navigate
                        class="text-[12px] tracking-[0.2em] text-white uppercase ">{{ __('ui.nav.sign_in') }}</a>
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
                    <div class="relative">
                        <input type="email" aria-label="{{ __('ui.footer.newsletter') }}" placeholder="{{ __('ui.footer.email_placeholder') }}"
                            class="w-full bg-transparent border-b border-white/10 py-2 text-base md:text-[11px] mono focus:border-white outline-none transition-all placeholder:text-white/10">
                        <button
                            class="absolute right-0 bottom-0 min-h-11 px-3 text-[10px] font-bold hover:text-white/60 transition-colors">{{ __('ui.footer.join') }}</button>
                    </div>
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


    <script>
        document.addEventListener('livewire:navigating', () => {
            // Показываем какой-то системный лог в консоли для красоты (опционально)
            console.log('DIGI_STORE: navigating...');
        });
    </script>
    @livewireScripts

</body>

</html>
