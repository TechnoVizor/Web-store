<x-layouts.app>
    <x-slot:title>{{ __('ui.pages.about_title') }}</x-slot>

    <div class="max-w-4xl mx-auto px-6 py-24 lg:py-32">
        <div class="mb-16 border-l border-white/20 pl-8">
            <h1 class="text-4xl font-bold tracking-[0.2em] text-white uppercase mono">{{ __('ui.pages.about_title') }}</h1>
            <p class="mt-2 text-zinc-500 text-[10px] tracking-[0.3em] uppercase">{{ __('ui.pages.about_subtitle') }}</p>
        </div>
        
        <div class="space-y-12">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <div class="md:col-span-4 mono text-[11px] text-white uppercase tracking-widest">{{ __('ui.pages.about_1_title') }}</div>
                <div class="md:col-span-8 text-zinc-400 font-light leading-relaxed tracking-wide">
                    {{ __('ui.pages.about_1_body') }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <div class="md:col-span-4 mono text-[11px] text-white uppercase tracking-widest">{{ __('ui.pages.about_2_title') }}</div>
                <div class="md:col-span-8 text-zinc-400 font-light leading-relaxed tracking-wide">
                    {{ __('ui.pages.about_2_body') }}
                </div>
            </div>

            <div class="py-12">
                <div class="h-px w-full bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <div class="md:col-span-4 mono text-[11px] text-white uppercase tracking-widest">{{ __('ui.pages.about_3_title') }}</div>
                <div class="md:col-span-8 text-zinc-400 font-light leading-relaxed tracking-wide">
                    {{ __('ui.pages.about_3_body') }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
