@extends('layouts.app')

@section('content')
    {{-- Просто вызываем компонент, он сам создаст нужный контейнер --}}
    <livewire:cart-view />
@endsection