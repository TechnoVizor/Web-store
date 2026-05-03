<x-layouts.app>
    <div class="min-h-[70vh] flex flex-col items-center justify-center px-6">
        <h1 class="text-9xl font-bold tracking-[-0.05em] text-white/5 mono">404</h1>
        <div class="mt-4 text-center">
            <h2 class="text-sm font-bold text-white uppercase tracking-[0.3em] mono">Page_Not_Found</h2>
            <p class="mt-6 text-[11px] text-zinc-500 uppercase tracking-[0.15em] max-w-xs mx-auto leading-relaxed">
                The requested resource could not be located within the current infrastructure.
            </p>
            <div class="mt-10">
                <a href="/" wire:navigate.hover class="border border-white/20 px-10 py-4 text-[10px] mono uppercase tracking-[0.2em] hover:bg-white hover:text-black transition-all">
                    Return_to_Main_Terminal
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>