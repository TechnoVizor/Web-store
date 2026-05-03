<div class="min-h-screen bg-black text-white mono antialiased pb-24">
    <header class="container mx-auto px-6 py-12 lg:py-20 border-b border-white/5">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-8">
            <div class="space-y-4">
                <div class="flex items-center space-x-3 text-white/40 text-[9px] tracking-[0.4em] uppercase">
                    <span class="w-8 h-px bg-white/10"></span>
                    <span>System_Archive // Node_Labs</span>
                </div>
                <h1 class="text-4xl lg:text-6xl font-bold tracking-[0.2em] uppercase leading-none">
                    Research & <br class="hidden lg:block"> Development
                </h1>
            </div>
            <div class="text-left md:text-right space-y-2">
                <p class="text-[9px] text-white/40 tracking-[0.2em] uppercase">Access_Level: Authorized_Personnel</p>
                <p class="text-[9px] text-white/20 tracking-[0.2em] uppercase">Local_Time: {{ now()->format('H:i:s') }}</p>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-6 py-8 lg:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
            
            <div class="lg:col-span-4 space-y-8">
                <div>
                    <h2 class="text-[10px] text-white/20 uppercase tracking-[0.4em] mb-6">Select_Project_File</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-2">
                        @foreach($projects as $project)
                            <button 
                                wire:click="selectProject('{{ $project['id'] }}')"
                                class="w-full group text-left px-5 py-6 border transition-all duration-500 flex justify-between items-center {{ $activeProject['id'] == $project['id'] ? 'border-white bg-white text-black' : 'border-white/5 hover:border-white/20' }}">
                                <div class="space-y-1">
                                    <span class="text-[7px] uppercase tracking-widest opacity-50">{{ $project['id'] }}</span>
                                    <p class="text-[11px] font-bold uppercase tracking-[0.2em]">{{ $project['title'] }}</p>
                                </div>
                                <span class="text-[14px] opacity-20 group-hover:opacity-100 transition-opacity">/</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="hidden lg:block p-6 border border-dashed border-white/5 opacity-40 space-y-3">
                    <p class="text-[7px] uppercase tracking-[0.3em]">> Initializing encryption...</p>
                    <p class="text-[7px] uppercase tracking-[0.3em]">> Node: RIG-LVA-03</p>
                    <div class="h-[1px] w-full bg-white/10 relative overflow-hidden">
                        <div class="absolute inset-0 bg-white/40 animate-pulse"></div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="space-y-12 lg:space-y-16 animate-in fade-in slide-in-from-bottom-4 duration-700" wire:key="{{ $activeProject['id'] }}">
                    
                    <div class="flex flex-col sm:flex-row justify-between items-start border-b border-white/10 pb-8 gap-6">
                        <div class="space-y-4">
                            <h3 class="text-3xl lg:text-4xl font-bold uppercase tracking-[0.15em]">{{ $activeProject['title'] }}</h3>
                            <div class="flex flex-wrap gap-4 text-[9px] tracking-[0.2em] text-white/40 uppercase font-bold">
                                <span class="text-white border border-white/20 px-2 py-1">Status: {{ $activeProject['status'] }}</span>
                                <span class="py-1">Year: {{ $activeProject['year'] }}</span>
                                <span class="py-1">Class: Secure_Asset</span>
                            </div>
                        </div>
                        <div class="text-[32px] lg:text-[40px] font-black text-white/5 leading-none">{{ $activeProject['id'] }}</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <div class="space-y-4">
                            <h4 class="text-[10px] text-white/40 uppercase tracking-[0.3em] font-bold">Project_Overview</h4>
                            <p class="text-[12px] text-zinc-400 leading-loose tracking-[0.1em] uppercase">
                                {{ $activeProject['description'] }}
                            </p>
                        </div>
                        <div class="space-y-4">
                            <h4 class="text-[10px] text-white/40 uppercase tracking-[0.3em] font-bold">Material_Composition</h4>
                            <p class="text-[12px] text-white/80 leading-relaxed tracking-[0.1em] uppercase font-bold">
                                {{ $activeProject['materials'] }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h4 class="text-[10px] text-white/40 uppercase tracking-[0.3em] font-bold">Technical_Specifications</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-px bg-white/5 border border-white/5">
                            @foreach($activeProject['specs'] as $label => $value)
                                <div class="p-5 bg-black space-y-2">
                                    <p class="text-[7px] text-zinc-600 uppercase tracking-widest">{{ $label }}</p>
                                    <p class="text-[12px] text-white uppercase tracking-widest font-bold">{{ $value }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-8">
                        <h4 class="text-[10px] text-white/40 uppercase tracking-[0.3em] font-bold">Chronological_Log</h4>
                        <div class="space-y-0">
                            @foreach($activeProject['log'] as $date => $event)
                                <div class="flex items-start border-l border-white/10 pl-6 pb-8 relative group">
                                    <div class="absolute -left-[2px] top-0 w-1 h-1 bg-zinc-800 group-hover:bg-white transition-colors"></div>
                                    <div class="space-y-1">
                                        <p class="text-[8px] text-white/20 uppercase tracking-widest">{{ $date }}</p>
                                        <p class="text-[10px] text-white/70 uppercase tracking-widest font-medium">{{ $event }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-8 border-t border-white/5 flex justify-start lg:justify-end">
                        <button class="flex items-center space-x-4 group text-white/20 hover:text-white transition-colors">
                            <span class="text-[9px] uppercase tracking-[0.4em]">Request_Technical_Manifesto</span>
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </main>
</div>