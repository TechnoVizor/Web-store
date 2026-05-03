@extends('layouts.app')

@section('content')
    {{-- Мы просто вызываем компонент. Всё остальное теперь живет внутри него --}}
    <livewire:store-index />
@endsection