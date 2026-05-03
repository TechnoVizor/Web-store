<x-layouts.app>
    @if(isset($slot))
        {{ $slot }}
    @else
        @yield('content')
    @endif
</x-layouts.app>