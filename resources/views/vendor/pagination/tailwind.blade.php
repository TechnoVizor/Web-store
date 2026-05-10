@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="mono text-[10px] uppercase tracking-[0.2em] text-white/42">
            @if ($paginator->firstItem())
                {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }} / {{ $paginator->total() }}
            @else
                {{ $paginator->count() }} / {{ $paginator->total() }}
            @endif
            <span class="ml-2 text-white/20">{{ __('ui.pagination.results') }}</span>
        </div>

        <div class="flex items-center gap-1.5 rtl:flex-row-reverse">
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}"
                    class="flex h-11 w-11 items-center justify-center border border-white/8 bg-white/[0.015] text-white/18">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" wire:navigate
                    aria-label="{{ __('pagination.previous') }}"
                    class="ui-btn flex h-11 w-11 p-0 text-white/58">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @endif

            <div class="hidden items-center gap-1.5 sm:flex">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span aria-disabled="true"
                            class="flex h-11 min-w-11 items-center justify-center border border-white/8 bg-white/[0.015] px-3 mono text-[10px] font-bold text-white/22">
                            {{ $element }}
                        </span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page"
                                    class="flex h-11 min-w-11 items-center justify-center border border-white/70 bg-white/80 px-3 mono text-[10px] font-black text-black shadow-[0_0_24px_rgba(255,255,255,0.08)]">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" wire:navigate
                                    aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                    class="ui-btn flex h-11 min-w-11 px-3 mono text-[10px] font-bold text-white/58">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            <div class="flex items-center border border-white/10 bg-black/40 px-4 py-3 mono text-[10px] font-bold uppercase tracking-[0.18em] text-white/55 sm:hidden">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </div>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" wire:navigate
                    aria-label="{{ __('pagination.next') }}"
                    class="ui-btn flex h-11 w-11 p-0 text-white/58">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}"
                    class="flex h-11 w-11 items-center justify-center border border-white/8 bg-white/[0.015] text-white/18">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
