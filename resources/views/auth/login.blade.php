@extends('layouts.app')

@section('content')
<div class="min-h-[82vh] px-6 py-14 md:py-20">
    <div class="mx-auto grid max-w-6xl gap-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
        <section class="hidden lg:block">
            <div class="border-l border-white/25 pl-8">
                <p class="mono mb-4 text-[10px] uppercase tracking-[0.35em] text-white/35">Digi Store Account</p>
                <h1 class="max-w-md text-5xl font-black uppercase leading-[0.95] tracking-tight text-white/80">
                    Your wardrobe, orders, and saved pieces.
                </h1>
                <p class="mt-6 max-w-sm text-sm leading-7 text-white/50">
                    Sign in with phone, email, or Google. Guest orders can be linked later by phone number.
                </p>
            </div>

            <div class="mt-10 grid max-w-md grid-cols-3 border border-white/8 bg-white/[0.015]">
                <div class="border-r border-white/8 p-4">
                    <p class="mono text-[10px] uppercase tracking-[0.22em] text-white/35">Orders</p>
                    <p class="mt-2 text-sm font-bold text-white/75">Tracked</p>
                </div>
                <div class="border-r border-white/8 p-4">
                    <p class="mono text-[10px] uppercase tracking-[0.22em] text-white/35">Phone</p>
                    <p class="mt-2 text-sm font-bold text-white/75">Linked</p>
                </div>
                <div class="p-4">
                    <p class="mono text-[10px] uppercase tracking-[0.22em] text-white/35">Wishlist</p>
                    <p class="mt-2 text-sm font-bold text-white/75">Saved</p>
                </div>
            </div>
        </section>

        <div class="mx-auto w-full max-w-[520px] border border-white/10 bg-[#060606] p-5 shadow-[0_30px_90px_rgba(0,0,0,0.45)] sm:p-8">
            <livewire:auth.auth-form />
        </div>
    </div>
</div>
@endsection
