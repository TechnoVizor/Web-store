<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SYS_ADMIN // TERMINAL</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Подключаем отдельный шрифт только для админки --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body.admin-terminal {
            margin: 0 !important;
            padding: 0 !important;
            background-color: #0a0a0a !important;
            color: #d4d4d4 !important;
            font-family: 'Fira Code', monospace !important;
        }

        /* 2. Жесткая изоляция всех внутренних элементов */
        .admin-terminal,
        .admin-terminal * {
            font-family: 'Fira Code', monospace !important;
            border-color: rgba(255, 255, 255, 0.1);
            /* Делаем границы мягче по умолчанию */
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

        /* 4. Фикс для того, чтобы фоны из основного сайта не просачивались */
        .admin-terminal main,
        .admin-terminal aside {
            background-color: inherit;
        }
    </style>
</head>

{{-- Класс admin-terminal служит щитом для стилей --}}

<body class="admin-terminal flex h-screen overflow-hidden selection:bg-white selection:text-black relative">


    {{-- САЙДБАР --}}
    <aside class="w-64 flex flex-col border-r border-zinc-800 bg-black relative z-10 shrink-0">
        {{-- Логотип --}}
        <div class="p-6 border-b border-zinc-800">
            <div class="text-[10px] text-zinc-500 tracking-[0.3em] mb-1">SYSTEM_ACCESS</div>
            <h1 class="text-2xl font-bold tracking-widest text-white flex items-center">
                CORE<span class="animate-pulse ml-1 text-zinc-600">_</span>
            </h1>
        </div>

        {{-- Навигация --}}
        <nav class="flex-1 overflow-y-auto p-4 space-y-1 text-sm tracking-widest">
            <a href="{{ route('admin.dashboard') }}" wire:navigate.hover
                class="flex items-center px-4 py-3 text-zinc-400 hover:text-white hover:bg-zinc-900 border-l-2 border-transparent hover:border-white transition-all">
                <span class="mr-3 opacity-40 text-xs">[01]</span> DASHBOARD
            </a>
            <a href="{{ route('admin.categories') }}" wire:navigate.hover
                class="flex items-center px-4 py-3 text-zinc-400 hover:text-white hover:bg-zinc-900 border-l-2 border-transparent hover:border-white transition-all">
                <span class="mr-3 opacity-40 text-xs">[02]</span> CATEGORIES
            </a>
            <a href="{{ route('admin.products') }}" wire:navigate.hover
                class="flex items-center px-4 py-3 text-zinc-400 hover:text-white hover:bg-zinc-900 border-l-2 border-transparent hover:border-white transition-all">
                <span class="mr-3 opacity-40 text-xs">[03]</span> PRODUCTS
            </a>

            <a href="{{ route('admin.orders') }}" wire:navigate.hover
                class="flex items-center px-4 py-3 text-zinc-400 hover:text-white hover:bg-zinc-900 border-l-2 border-transparent hover:border-white transition-all">
                <span class="mr-3 opacity-40 text-xs">[04]</span> ORDERS
            </a>
                        <a href="{{ route('admin.users') }}" wire:navigate.hover
                class="flex items-center px-4 py-3 text-zinc-400 hover:text-white hover:bg-zinc-900 border-l-2 border-transparent hover:border-white transition-all">
                <span class="mr-3 opacity-40 text-xs">[05]</span> USERS
            </a>
        </nav>

        {{-- Инфо о пользователе --}}
        <div class="p-4 border-t border-zinc-800 bg-black">
            <div class="flex items-center justify-between">
                <div class="overflow-hidden pr-2">
                    <div class="text-[9px] text-zinc-500 tracking-widest uppercase mb-1">Active_User</div>
                    <div class="text-xs text-white truncate font-bold">{{ auth()->user()->name ?? 'SYS_ADMIN' }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="text-[10px] tracking-widest uppercase border border-zinc-700 px-3 py-1 hover:bg-white hover:text-black transition-colors">
                        EXIT
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ОСНОВНОЙ КОНТЕНТ --}}
    <main class="flex-1 flex flex-col bg-[#0a0a0a] relative z-10 overflow-hidden">

        {{-- Верхняя статус-панель --}}
        <header
            class="h-10 border-b border-zinc-800 flex items-center justify-between px-6 bg-black text-[10px] tracking-widest text-zinc-500 uppercase">
            <div class="flex space-x-8">
                <span class="flex items-center"><span
                        class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2 animate-pulse"></span> DB_CONNECTED</span>
                <span>SECURE_LINK: ACTIVE</span>
                {{-- Здесь кину на сайт --}}
                <a href="/" wire:navigate.hover target="_blank" class="flex items-center text-white/40 hover:text-500/50 hover:text-white transition-all group">
                <span class="w-[1px] h-3 bg-white/10 mx-2"></span> {{-- Разделитель --}}
                <svg class="w-3.5 h-3.5 transition-transform duration-700 group-hover:rotate-180" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="ml-2 text-[9px] font-bold tracking-[0.3em] opacity-0 group-hover:opacity-100 transition-opacity">WEB_SITE</span>
            </a>
            </div>
            <div>SYS_TIME // {{ now()->format('H:i:s') }}</div>
        </header>

        {{-- Контейнер для Livewire страниц --}}
        <div class="flex-1 overflow-y-auto p-10">
            <div class="max-w-7xl mx-auto">
                {{ $slot }}
            </div>
        </div>
    </main>

</body>

</html>
