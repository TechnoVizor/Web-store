@extends('layouts.app')

@section('content')
<div class="py-40 flex items-center justify-center">
    <div class="text-center space-y-8 max-w-lg">
        {{-- Индикатор --}}
        <div class="inline-flex items-center justify-center w-20 h-20 border border-white/5 rounded-full mb-4 bg-white/[0.02]">
            <svg class="w-8 h-8 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <div class="space-y-4">
            <h1 class="text-4xl font-bold uppercase tracking-tighter text-white" 
                x-data="typewriter(@js(__('ui.orders.success_title')), 80)" x-text="displayText">
                {{ __('ui.orders.success_title') }}
            </h1>
            <p class="text-white/40 text-[10px] mono uppercase tracking-[0.2em]">
                {{ __('ui.orders.success_status') }}
            </p>
        </div>

        <div class="pt-10 flex flex-col items-center space-y-6">
            <a href="{{ route('orders.index') }}" wire:navigate 
               class="px-8 py-3 border border-white/10 text-[9px] uppercase tracking-widest text-white/60 hover:bg-white hover:text-black transition-all">
               {{ __('ui.orders.view_history') }}
            </a>
            <a href="{{ route('home') }}" wire:navigate 
               class="mono text-[8px] uppercase tracking-widest text-white/20 hover:text-white transition-colors">
               {{ __('ui.orders.return_home') }}
            </a>
        </div>
    </div>
</div>
@endsection
