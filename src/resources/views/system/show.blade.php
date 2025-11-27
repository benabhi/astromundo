<x-game-layout>
    <x-slot name="systemName">{{ $system->name }}</x-slot>

    <div class="max-w-4xl mx-auto space-y-12">
        
        <!-- Header: System Info -->
        <header class="space-y-2">
            <div class="flex flex-wrap items-center gap-2 text-xs font-mono text-slate-400 uppercase tracking-widest">
                <span class="text-white font-bold">{{ $system->name }}</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-white font-['Orbitron'] tracking-tight uppercase drop-shadow-lg">
                Sistema {{ $system->name }}
            </h1>
            <div class="flex items-center gap-4 text-sm font-['Rajdhani'] text-blue-400 uppercase tracking-wider">
                <span>Coordenadas: [{{ $system->x_coordinate }}, {{ $system->y_coordinate }}]</span>
                <span class="w-1 h-1 bg-blue-500 rounded-full"></span>
                <span>Estrellas: {{ $system->stars->count() }}</span>
            </div>
        </header>

        <!-- Navigation Actions -->
        <section class="relative group">
            <div class="absolute left-0 top-0 bottom-0 w-px bg-gradient-to-b from-blue-500 via-white/10 to-transparent"></div>
            
            <div class="pl-8 md:pl-12 space-y-8">
                
                <!-- Action Header -->
                <div class="relative">
                    <div class="absolute -left-[41px] md:-left-[57px] top-1.5 w-3 h-3 bg-black border-2 border-blue-500 rounded-full z-10"></div>
                    <h2 class="text-2xl font-bold text-white font-['Rajdhani'] uppercase tracking-wide drop-shadow-md">
                        <span class="text-blue-500 mr-2">></span> Panel de Navegación
                    </h2>
                </div>

                <!-- Launch to Space -->
                <div class="p-4 bg-white/5 rounded border border-white/10">
                    <h3 class="text-lg font-bold text-white mb-2">Espacio Abierto</h3>
                    <p class="text-sm text-slate-400 mb-4">Navegar manualmente por el sistema, escanear anomalías y viajar entre cuerpos celestes.</p>
                    <a href="{{ route('system.space.index', $system) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Lanzar al Espacio
                    </a>
                </div>

                <!-- Planets List -->
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-slate-200 font-['Rajdhani'] uppercase">Cuerpos Celestes</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($system->planets as $planet)
                            <div class="p-4 bg-slate-800/50 rounded border border-slate-700">
                                <h4 class="text-lg font-bold text-white">{{ $planet->name }}</h4>
                                <p class="text-xs text-slate-400 mb-2">Tipo: {{ $planet->type }}</p>
                                <div class="text-sm text-slate-300">
                                    Lunas: {{ $planet->moons->count() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Stargates List -->
                @if($system->stargates->count() > 0)
                <div class="space-y-4 pt-4">
                    <h3 class="text-xl font-bold text-slate-200 font-['Rajdhani'] uppercase">Portales de Salto</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($system->stargates as $gate)
                            <div class="p-4 bg-indigo-900/30 rounded border border-indigo-500/30">
                                <h4 class="text-lg font-bold text-indigo-300">{{ $gate->name }}</h4>
                                <p class="text-xs text-slate-400 mb-2">Destino: {{ $gate->destinationSystem->name ?? 'Desconocido' }}</p>
                                <!-- Future: Add jump action -->
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </section>

    </div>
</x-game-layout>
