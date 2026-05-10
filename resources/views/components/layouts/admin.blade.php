<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('ui.admin.title') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Подключаем отдельный шрифт только для админки --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body.admin-terminal {
            margin: 0 !important;
            padding: 0 !important;
            background-color: #070707 !important;
            color: rgba(255, 255, 255, 0.78) !important;
            font-family: 'Fira Code', monospace !important;
            font-size: 16px !important;
        }

        /* 2. Жесткая изоляция всех внутренних элементов */
        .admin-terminal,
        .admin-terminal * {
            font-family: 'Fira Code', monospace !important;
            border-color: rgba(255, 255, 255, 0.1);
        }

        /* 3. Кастомный скроллбар (только для админки) */
        .admin-terminal::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        .admin-terminal::-webkit-scrollbar-track {
            background: #000 !important;
        }

        .admin-terminal::-webkit-scrollbar-thumb {
            background: #333 !important;
            border-radius: 0px !important;
        }

        .admin-terminal::-webkit-scrollbar-thumb:hover {
            background: #666 !important;
        }

        .admin-terminal main,
        .admin-terminal aside {
            background-color: inherit;
        }

        .admin-terminal .text-\[7px\] { font-size: 8px !important; }
        .admin-terminal .text-\[8px\] { font-size: 9px !important; }
        .admin-terminal .text-\[9px\] { font-size: 10px !important; }
        .admin-terminal .text-\[10px\] { font-size: 11px !important; }
        .admin-terminal .text-\[11px\] { font-size: 12px !important; }

        .admin-terminal .ui-btn {
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

        .admin-terminal .ui-btn:hover,
        .admin-terminal .ui-btn:focus-visible {
            border-color: rgba(255, 255, 255, 0.34);
            background: rgba(255, 255, 255, 0.07);
            color: rgba(255, 255, 255, 0.94);
            transform: translateY(-1px);
            outline: none;
        }

        .admin-terminal .ui-btn-primary {
            border-color: rgba(255, 255, 255, 0.78);
            background: rgba(255, 255, 255, 0.80);
            color: #050505;
        }

        .admin-terminal .ui-btn-primary:hover,
        .admin-terminal .ui-btn-primary:focus-visible {
            border-color: rgba(255, 255, 255, 0.92);
            background: rgba(255, 255, 255, 0.88);
            color: #050505;
        }

        .admin-terminal .ui-btn-danger:hover,
        .admin-terminal .ui-btn-danger:focus-visible {
            border-color: rgba(239, 68, 68, 0.55);
            background: rgba(239, 68, 68, 0.10);
            color: rgba(252, 165, 165, 0.95);
        }

        .admin-shell {
            background:
                linear-gradient(180deg, rgba(255,255,255,0.025), transparent 18rem),
                #070707;
        }

        .admin-sidebar {
            background: #050505;
        }

        .admin-nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border: 1px solid transparent;
            border-left-color: rgba(255,255,255,0.08);
            padding: 0.85rem 1rem;
            color: rgba(255,255,255,0.46);
            transition: background-color 180ms ease, border-color 180ms ease, color 180ms ease, transform 180ms ease;
        }

        .admin-nav-link:hover,
        .admin-nav-link:focus-visible {
            background: rgba(255,255,255,0.045);
            border-color: rgba(255,255,255,0.16);
            color: rgba(255,255,255,0.84);
            transform: translateX(2px);
            outline: none;
        }

        .admin-panel {
            border: 1px solid rgba(255,255,255,0.10);
            background: #050505;
        }

        .admin-input,
        .admin-select {
            border: 1px solid rgba(255,255,255,0.12);
            background: #030303;
            color: rgba(255,255,255,0.78);
            outline: none;
            transition: border-color 180ms ease, background-color 180ms ease;
        }

        .admin-input:focus,
        .admin-select:focus {
            border-color: rgba(255,255,255,0.36);
            background: #080808;
        }

        .admin-table {
            width: 100%;
            text-align: left;
        }

        .admin-table thead {
            border-bottom: 1px solid rgba(255,255,255,0.10);
            background: rgba(255,255,255,0.035);
            color: rgba(255,255,255,0.36);
            text-transform: uppercase;
            letter-spacing: 0.2em;
            font-size: 10px;
        }

        .admin-table th {
            padding: 1rem;
            font-weight: 400;
        }

        .admin-table td {
            padding: 1rem;
        }

        .admin-table tbody {
            color: rgba(255,255,255,0.72);
            font-size: 12px;
            letter-spacing: 0.05em;
        }

        .admin-table tbody tr {
            border-bottom: 1px solid rgba(255,255,255,0.06);
            transition: background-color 160ms ease;
        }

        .admin-table tbody tr:hover {
            background: rgba(255,255,255,0.035);
        }

        .admin-terminal table:not(.admin-table) {
            width: 100%;
            text-align: left;
        }

        .admin-terminal table:not(.admin-table) thead {
            border-bottom: 1px solid rgba(255,255,255,0.10);
            background: rgba(255,255,255,0.035);
            color: rgba(255,255,255,0.36);
        }

        .admin-terminal table:not(.admin-table) tbody tr {
            border-bottom: 1px solid rgba(255,255,255,0.06);
            transition: background-color 160ms ease;
        }

        .admin-terminal table:not(.admin-table) tbody tr:hover {
            background: rgba(255,255,255,0.035) !important;
        }

        .admin-terminal input:not([type="checkbox"]):not([type="file"]),
        .admin-terminal textarea,
        .admin-terminal select {
            border-color: rgba(255,255,255,0.12) !important;
            background-color: #030303 !important;
        }

        .admin-terminal input:not([type="checkbox"]):not([type="file"]):focus,
        .admin-terminal textarea:focus,
        .admin-terminal select:focus {
            border-color: rgba(255,255,255,0.36) !important;
            outline: none !important;
        }

        .admin-terminal :is(input:not([type="checkbox"]):not([type="radio"]):not([type="file"]):not([type="submit"]):not([type="button"]), textarea, select) {
            font-size: 16px !important;
            line-height: 1.5rem !important;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            border: 1px solid currentColor;
            padding: 0.38rem 0.65rem;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.16em;
        }

        .status-pill::before {
            content: "";
            width: 0.45rem;
            height: 0.45rem;
            background: currentColor;
        }

        .status-new { color: #93c5fd; background: rgba(59,130,246,0.10); }
        .status-pending { color: #facc15; background: rgba(250,204,21,0.10); }
        .status-processing { color: #38bdf8; background: rgba(56,189,248,0.10); }
        .status-paid { color: #22c55e; background: rgba(34,197,94,0.10); }
        .status-shipped { color: #c084fc; background: rgba(192,132,252,0.10); }
        .status-delivered { color: #2dd4bf; background: rgba(45,212,191,0.10); }
        .status-cancelled { color: #f87171; background: rgba(248,113,113,0.10); }

        @media (max-width: 900px) {
            body.admin-terminal {
                overflow: auto !important;
            }

            .admin-layout {
                min-height: 100vh;
                flex-direction: column;
                overflow: visible;
            }

            .admin-sidebar {
                width: 100%;
                height: auto;
            }

            .admin-nav {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>
</head>

{{-- Класс admin-terminal служит щитом для стилей --}}

<body class="admin-terminal selection:bg-white selection:text-black relative">


    {{-- САЙДБАР --}}
    <div class="admin-layout flex h-screen overflow-hidden">
    <aside class="admin-sidebar w-64 flex flex-col border-r border-zinc-800 relative z-10 shrink-0">
        {{-- Логотип --}}
        <div class="p-6 border-b border-zinc-800/80">
            <div class="text-[10px] text-zinc-500 tracking-[0.3em] mb-1 uppercase">{{ __('ui.admin.access') }}</div>
            <h1 class="text-2xl font-black tracking-tight text-white flex items-center">
                DIGI<span class="mx-1 text-zinc-600">_</span>STORE
            </h1>
        </div>

        {{-- Навигация --}}
        <nav class="admin-nav flex-1 overflow-y-auto p-4 space-y-1 text-sm tracking-widest">
            <a href="{{ route('admin.dashboard') }}" wire:navigate class="admin-nav-link">
                <span class="opacity-40 text-xs">[01]</span> {{ __('ui.admin.dashboard') }}
            </a>
            <a href="{{ route('admin.categories') }}" wire:navigate class="admin-nav-link">
                <span class="opacity-40 text-xs">[02]</span> {{ __('ui.admin.categories') }}
            </a>
            <a href="{{ route('admin.products') }}" wire:navigate class="admin-nav-link">
                <span class="opacity-40 text-xs">[03]</span> {{ __('ui.admin.products') }}
            </a>

            <a href="{{ route('admin.orders') }}" wire:navigate class="admin-nav-link">
                <span class="opacity-40 text-xs">[04]</span> {{ __('ui.admin.orders') }}
            </a>
            <a href="{{ route('admin.users') }}" wire:navigate class="admin-nav-link">
                <span class="opacity-40 text-xs">[05]</span> {{ __('ui.admin.users') }}
            </a>
        </nav>

        {{-- Инфо о пользователе --}}
        <div class="p-4 border-t border-zinc-800/80 bg-black">
            <div class="flex items-center justify-between">
                <div class="overflow-hidden pr-2">
                    <div class="text-[9px] text-zinc-500 tracking-widest uppercase mb-1">{{ __('ui.admin.active_user') }}</div>
                    <div class="text-xs text-white truncate font-bold">{{ auth()->user()->name ?? 'SYS_ADMIN' }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="ui-btn ui-btn-danger px-3 py-1 text-[10px] tracking-widest text-zinc-300">
                        {{ __('ui.admin.exit') }}
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ОСНОВНОЙ КОНТЕНТ --}}
    <main class="admin-shell flex-1 flex flex-col relative z-10 overflow-hidden">

        {{-- Верхняя статус-панель --}}
        <header
            class="h-11 border-b border-zinc-800/80 flex items-center justify-between px-6 bg-black/80 text-[10px] tracking-widest text-zinc-500 uppercase">
            <div class="flex space-x-8">
                <span class="flex items-center"><span
                        class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2 animate-pulse"></span> {{ __('ui.admin.db_connected') }}</span>
                {{-- Здесь кину на сайт --}}
                <a href="/" wire:navigate target="_blank" class="flex items-center text-white/40 hover:text-500/50 hover:text-white transition-all group">
                <span class="w-[1px] h-3 bg-white/10 mx-2"></span> {{-- Разделитель --}}
                <svg class="w-3.5 h-3.5 transition-transform duration-700 group-hover:rotate-180" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="ml-2 text-[9px] font-bold tracking-[0.3em] opacity-0 group-hover:opacity-100 transition-opacity">{{ __('ui.admin.storefront') }}</span>
            </a>
            </div>
            <div>{{ __('ui.admin.local_time') }} // {{ now(config('app.timezone'))->format('H:i:s') }}</div>
        </header>

        {{-- Контейнер для Livewire страниц --}}
        <div class="flex-1 overflow-y-auto p-6 md:p-10">
            <div class="max-w-7xl mx-auto">
                {{ $slot }}
            </div>
        </div>
    </main>
    </div>

</body>

</html>
