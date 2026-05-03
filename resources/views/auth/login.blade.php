@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-20 px-6">
    <div class="w-full max-w-md p-10 border border-white/10 bg-[#050505] relative shadow-[0_0_100px_rgba(0,0,0,0.8)]">
        {{-- Декор --}}
        <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
        
        <livewire:auth.auth-form />

        <div class="mt-12 pt-6 border-t border-white/5 flex justify-between items-center opacity-20">
            <span class="mono text-[7px] uppercase tracking-[0.5em]">Auth_Node: 04</span>
            <span class="mono text-[7px] uppercase tracking-[0.5em]">Session: Encrypted</span>
        </div>
    </div>
</div>
@endsection