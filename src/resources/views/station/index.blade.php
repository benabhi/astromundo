<x-game-layout>
    <x-slot name="systemName">{{ $station->moon->planet->solarSystem->name }}</x-slot>

    <div class="max-w-4xl mx-auto space-y-12">
        
        <!-- Header: Station Overview -->
        <header class="space-y-2">
            <x-breadcrumb :crumbs="[
                ['label' => $station->moon->planet->solarSystem->name, 'url' => route('system.show', $station->moon->planet->solarSystem), 'extra' => $station->moon->planet->solarSystem->stars->first()->name ?? 'Unknown'],
                ['label' => $station->moon->planet->name, 'url' => route('planet.show', $station->moon->planet)],
                ['label' => $station->moon->name, 'url' => route('moon.show', $station->moon)],
                ['label' => $station->name]
            ]" />
            <h1 class="text-4xl md:text-5xl font-bold text-white font-['Orbitron'] tracking-tight uppercase drop-shadow-lg">
                {{ $station->name }}
            </h1>
            <div class="flex items-center gap-4 text-sm font-['Rajdhani'] text-blue-400 uppercase tracking-wider">
                <span>Estación Clase {{ $station->type }}</span>
                <span class="w-1 h-1 bg-blue-500 rounded-full"></span>
                <span>Población: {{ number_format($station->attributes['population'] ?? 0) }}</span>
            </div>
            <p class="text-sm text-slate-400 font-['Space_Mono'] italic max-w-2xl leading-relaxed pt-2 border-l-2 border-blue-500/30 pl-4">
                "{{ $station->description }}"
            </p>
        </header>

        <!-- Station Info Section -->
        <x-station.section title="Información de la Estación" border="blue" subtitle="Vista Externa // Órbita">
            
            <div class="prose prose-invert max-w-none mb-8">
                <p class="text-lg text-slate-200 font-['Space_Mono'] leading-relaxed drop-shadow-md">
                    @if($isDocked)
                        Tu nave está atracada en esta estación. Puedes acceder a los módulos internos o desatracar para continuar tu viaje.
                    @else
                        Estás en órbita alrededor de esta estación. Puedes solicitar permiso de atraque para acceder a sus instalaciones.
                    @endif
                </p>
            </div>

            <!-- Commands -->
            <div class="space-y-4 pt-4">
                <h3 class="text-xs text-slate-400 font-bold uppercase tracking-widest border-b border-white/10 pb-2 mb-4 w-max">Comandos Disponibles</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($isDocked)
                        <!-- If docked, show module access -->
                        @foreach($station->modules as $module)
                            <a href="{{ route('station.module', ['station' => $station, 'module' => $module]) }}">
                                <x-command-button 
                                    label="ACCEDER_{{ strtoupper(str_replace(' ', '_', $module->name)) }}" 
                                    description="Módulo: {{ $module->type }}" 
                                    color="blue" 
                                />
                            </a>
                        @endforeach
                    @else
                        <!-- If not docked, show docking option -->
                        <form action="{{ route('station.dock', $station) }}" method="POST">
                            @csrf
                            <x-command-button 
                                type="submit"
                                label="SOLICITAR_ATRAQUE" 
                                description="Atracar en el hangar principal" 
                                color="emerald" 
                            />
                        </form>
                        
                        <x-command-button 
                            label="ESCANEAR_ESTACIÓN" 
                            description="Obtener información detallada" 
                            color="blue" 
                        />
                    @endif
                </div>
            </div>

        </x-station.section>

        <!-- Module List -->
        @if($isDocked)
            <x-station.section title="Módulos Disponibles" border="slate" subtitle="Instalaciones Accesibles">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($station->modules as $module)
                        <div class="bg-slate-800/50 border border-slate-700 p-4 rounded hover:border-blue-500/50 transition-colors">
                            <div class="text-white font-bold text-sm uppercase">{{ $module->name }}</div>
                            <div class="text-xs text-slate-400 mt-1">Tipo: {{ $module->type }}</div>
                        </div>
                    @endforeach
                </div>
            </x-station.section>
        @endif

        <!-- Footer -->
        <footer class="text-center py-8 text-[10px] text-slate-700 font-mono uppercase tracking-widest border-t border-white/5 mt-12">
            <span class="opacity-50">Fin de la Transmisión // {{ now()->format('Y') }}</span>
        </footer>

    </div>
</x-game-layout>
