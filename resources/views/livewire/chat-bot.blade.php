<div>
<div x-data="{ 
        open: @entangle('isOpen'),
        showButton: false,
        checkScroll() {
            // Если кнопка уже показана, выходим из функции, чтобы не тратить ресурсы
            if (this.showButton) return;

            let scrollHeight = document.documentElement.scrollHeight;
            let scrollPosition = window.innerHeight + window.scrollY;
            
            // Как только пользователь коснулся низа (за 100px до конца)
            if (scrollPosition >= (scrollHeight - 100)) {
                this.showButton = true;
            }
        }
    }" 
    x-init="window.addEventListener('scroll', () => checkScroll()); checkScroll();"
    class="fixed bottom-6 right-6 z-[60] font-mono">
    
    <button x-show="showButton" 
            x-cloak
            :aria-label="open ? '{{ __('ui.admin.close') }}' : '{{ __('ui.chat.title') }}'"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 scale-50 translate-y-10"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            @click="open = !open" 
            class="ui-btn ui-btn-primary w-10 h-10 flex items-center justify-center shadow-[0_0_20px_rgba(255,255,255,0.16)] focus:outline-none relative">
        
        <svg x-show="!open" x-cloak class="w-6 h-6 absolute" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>

        <svg x-show="open" x-cloak class="w-6 h-6 absolute" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

<div x-show="open" 
     x-cloak
     x-on:click.outside="open = false" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-95 translate-y-10"
     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
     class="absolute bottom-16 right-0 w-[calc(100vw-2.5rem)] sm:w-96 h-[500px] bg-black/95 border border-white/10 backdrop-blur-2xl flex flex-col shadow-2xl overflow-hidden shadow-white/5">
    
    <div class="shrink-0 p-3 border-b border-white/10 flex justify-between items-center bg-white/5">
        <div class="flex items-center space-x-2">
            <div class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></div>
            <span class="text-[9px] font-bold tracking-[0.3em] uppercase">{{ __('ui.chat.title') }}</span>
        </div>
    </div>

    <div id="chat-messages" class="flex-grow p-4 overflow-y-auto space-y-6 scroll-smooth">
        @foreach($messages as $msg)
            <div class="{{ $msg['role'] === 'user' ? 'text-right' : 'text-left' }} animate-in fade-in slide-in-from-bottom-2 duration-500">
                <div class="inline-block {{ $msg['role'] === 'user' ? 'text-white/40' : 'text-white' }}">
                    <p class="text-[11px] leading-relaxed uppercase tracking-tight">
                        {{ $msg['text'] }}
                    </p>
                </div>
            </div>
        @endforeach
        
        @if($isTyping)
            <div class="flex items-center space-x-1 text-white/40 animate-pulse shrink-0">
                <span class="text-[10px]">{{ __('ui.chat.typing') }}</span>
            </div>
        @endif
    </div>

    <div class="shrink-0 px-4 py-2 flex gap-2 border-t border-white/5 bg-black/20 overflow-x-auto no-scrollbar">
        @foreach([__('ui.chat.quick_delivery'), __('ui.chat.quick_payment'), __('ui.chat.quick_returns')] as $cmd)
            <button wire:click="sendMessage('{{ $cmd }}')" 
                    class="ui-btn shrink-0 whitespace-nowrap text-[8px] px-2 py-1">
                {{ $cmd }}
            </button>
        @endforeach
    </div>

    <div class="shrink-0 p-4 border-t border-white/10 bg-black">
        <div class="flex items-center">
            <span class="text-white/20 mr-2">></span>
            <input type="text" 
                   wire:model.defer="userInput" 
                   wire:keydown.enter="sendMessage"
                   placeholder="{{ __('ui.chat.placeholder') }}" 
                   class="w-full bg-transparent border-none outline-none text-[11px] uppercase placeholder:text-white/5 focus:ring-0">
        </div>
    </div>
</div>
</div>
<style>
    /* Плавная прокрутка чата при добавлении сообщений */
    #chat-messages {
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none;  /* IE/Edge */
    }
    #chat-messages::-webkit-scrollbar {
        display: none; /* Chrome/Safari */
    }
</style>
</div>
@script
<script>
    $wire.on('scroll-chat', () => {
        // Даем браузеру один кадр на отрисовку нового сообщения
        requestAnimationFrame(() => {
            const container = document.getElementById('chat-messages');
            if (container) {
                container.scrollTo({
                    top: container.scrollHeight,
                    behavior: 'smooth'
                });
            }
        });
    });
</script>
@endscript
