<x-game-layout>
    <x-slot name="systemName">{{ $station->moon->planet->solarSystem->name }}</x-slot>

    <div class="max-w-4xl mx-auto space-y-12">
        
        <!-- Header: Location Log -->
        <header class="space-y-2">
            <div class="flex items-center gap-4 text-xs font-mono text-slate-500 uppercase tracking-widest">
                <span>System: <span class="text-white">{{ $station->moon->planet->solarSystem->name }}</span></span>
                <span>//</span>
                <span>Orbit: <span class="text-white">{{ $station->moon->name }}</span></span>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-white font-['Orbitron'] tracking-tight uppercase">
                {{ $station->name }}
            </h1>
            <div class="flex items-center gap-4 text-sm font-['Rajdhani'] text-blue-400 uppercase tracking-wider">
                <span>{{ $station->type }} Class Station</span>
                <span class="w-1 h-1 bg-blue-500 rounded-full"></span>
                <span>Population: {{ number_format($station->attributes['population'] ?? 0) }}</span>
            </div>
        </header>

        <!-- Main Terminal: Current Module -->
        <section class="relative group">
            <!-- Decorative "Terminal" Line -->
            <div class="absolute left-0 top-0 bottom-0 w-px bg-gradient-to-b from-blue-500 via-white/10 to-transparent"></div>
            
            <div class="pl-8 md:pl-12 space-y-8">
                
                <!-- Module Header -->
                <div class="relative">
                    <div class="absolute -left-[41px] md:-left-[57px] top-1.5 w-3 h-3 bg-black border-2 border-blue-500 rounded-full z-10"></div>
                    <h2 class="text-2xl font-bold text-white font-['Rajdhani'] uppercase tracking-wide">
                        <span class="text-blue-500 mr-2">></span> {{ $currentModule->name }}
                    </h2>
                    <p class="text-xs text-slate-500 font-mono mt-1 uppercase tracking-widest">Module ID: {{ $currentModule->id }} // Status: ACTIVE</p>
                </div>

                <!-- Narrative Description -->
                <div class="prose prose-invert max-w-none">
                    <p class="text-lg text-slate-300 font-['Space_Mono'] leading-relaxed">
                        {{ $currentModule->description }}
                    </p>
                </div>

                <!-- Command Interface (Actions) -->
                <div class="space-y-4 pt-4">
                    <h3 class="text-xs text-slate-500 font-bold uppercase tracking-widest border-b border-white/10 pb-2 mb-4 w-max">Available Commands</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($currentModule->type === 'Habitation')
                            <button class="group flex items-center gap-3 text-left hover:bg-white/5 p-2 -ml-2 rounded transition-all">
                                <span class="text-blue-500 font-mono group-hover:text-white transition-colors">[ > REST_PROTOCOL ]</span>
                                <span class="text-sm text-slate-400 group-hover:text-blue-200">Restore Energy (+10%)</span>
                            </button>
                            <button class="group flex items-center gap-3 text-left hover:bg-white/5 p-2 -ml-2 rounded transition-all">
                                <span class="text-blue-500 font-mono group-hover:text-white transition-colors">[ > ACCESS_LOGS ]</span>
                                <span class="text-sm text-slate-400 group-hover:text-blue-200">View Personal Messages</span>
                            </button>
                        @elseif($currentModule->type === 'Market')
                            <button class="group flex items-center gap-3 text-left hover:bg-white/5 p-2 -ml-2 rounded transition-all">
                                <span class="text-emerald-500 font-mono group-hover:text-white transition-colors">[ > OPEN_MARKET ]</span>
                                <span class="text-sm text-slate-400 group-hover:text-emerald-200">View Commodities</span>
                            </button>
                        @endif
                    </div>
                </div>

            </div>
        </section>

        <!-- Transit Network (Secondary Terminal) -->
        <section class="relative group pt-8">
            <div class="absolute left-0 top-0 bottom-0 w-px bg-gradient-to-b from-slate-700 via-white/5 to-transparent"></div>
            
            <div class="pl-8 md:pl-12 space-y-6">
                <div class="relative">
                    <div class="absolute -left-[41px] md:-left-[57px] top-1.5 w-3 h-3 bg-black border-2 border-slate-700 rounded-full z-10"></div>
                    <h3 class="text-xl font-bold text-slate-400 font-['Rajdhani'] uppercase tracking-wide">
                        Station Transit Network
                    </h3>
                </div>

                <div class="flex flex-wrap gap-4">
                    @foreach($station->modules as $module)
                        <form action="{{ route('station.move', $module->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm font-mono uppercase tracking-wider {{ $module->id === $currentModule->id ? 'text-blue-500 cursor-default' : 'text-slate-500 hover:text-white hover:underline decoration-blue-500 underline-offset-4 transition-all' }}">
                                @if($module->id === $currentModule->id)
                                    <span class="mr-1">*</span>
                                @endif
                                {{ $module->name }}
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Scanner Results (Pilots) -->
        <section class="relative group pt-8 pb-12">
            <div class="absolute left-0 top-0 bottom-0 w-px bg-gradient-to-b from-slate-800 via-transparent to-transparent"></div>
            
            <div class="pl-8 md:pl-12 space-y-6">
                <div class="relative">
                    <div class="absolute -left-[41px] md:-left-[57px] top-1.5 w-3 h-3 bg-black border-2 border-slate-800 rounded-full z-10"></div>
                    <h3 class="text-sm font-bold text-slate-500 font-['Rajdhani'] uppercase tracking-widest">
                        Local Scanner Results
                    </h3>
                </div>

                <div class="space-y-2">
                    @forelse($localPilots as $pilot)
                        <div class="flex items-center gap-4 text-sm font-mono text-slate-400">
                            <span class="text-blue-900">ID:{{ str_pad($pilot->id, 4, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-white">{{ $pilot->first_name }} {{ $pilot->last_name }}</span>
                            <span class="text-slate-600">[{{ $pilot->role ?? 'PILOT' }}]</span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-600 font-mono italic">No other signals detected in immediate vicinity.</p>
                    @endforelse
                </div>
            </div>
        </section>

    </div>
</x-game-layout>
