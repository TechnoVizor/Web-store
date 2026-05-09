<div>
    <button wire:click.stop="toggle"
            aria-label="{{ $isWished ? __('ui.profile.remove') : __('ui.profile.saved_items') }}"
            class="wishlist-heart {{ $isWished ? 'is-active' : '' }}">
        <svg class="w-4 h-4 transition-all duration-300"
             viewBox="0 0 24 24" 
             stroke="currentColor" 
             stroke-width="2">
            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
    </button>
</div>
