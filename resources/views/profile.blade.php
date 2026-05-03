@extends('layouts.app') {{-- Или твой основной шаблон --}}

@section('content')
<main class="container mx-auto px-6 py-20 max-w-6xl">
    <div class="flex items-center space-x-4 mb-16">
        <div class="w-1 h-10 bg-white"></div>
        <div>
            <h1 class="text-3xl font-bold uppercase tracking-tighter">Profile_Settings</h1>
            <p class="mono text-[10px] text-white/30 uppercase tracking-[0.3em]">Access_Level: Authorized_User</p>
        </div>
    </div>

    <livewire:profile.settings />
</main>
@endsection