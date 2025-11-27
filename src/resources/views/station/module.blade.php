<x-game-layout>
    <x-slot name="systemName">{{ $station->moon->planet->solarSystem->name }}</x-slot>

    <div class="max-w-4xl mx-auto space-y-12">
        
        <!-- Breadcrumb Navigation -->
        <x-breadcrumb :crumbs="[
            ['label' => $station->moon->planet->solarSystem->name, 'url' => route('system.show', $station->moon->planet->solarSystem), 'extra' => $station->moon->planet->solarSystem->stars->first()->name ?? 'Unknown'],
            ['label' => $station->moon->planet->name, 'url' => route('planet.show', $station->moon->planet)],
            ['label' => $station->moon->name, 'url' => route('moon.show', $station->moon)],
            ['label' => $station->name, 'url' => route('station.show', $station)],
            ['label' => $currentModule->name]
        ]" />

        <!-- Module Overview Block -->
        <div class="space-y-4">
            <!-- Header: Module Overview -->
            <header class="space-y-2">
                <h1 class="text-4xl md:text-5xl font-bold text-white font-['Orbitron'] tracking-tight uppercase drop-shadow-lg">
                    {{ $currentModule->name }}
                </h1>
                <div class="flex items-center gap-4 text-sm font-['Rajdhani'] text-blue-400 uppercase tracking-wider">
                    <span>Módulo ID: {{ $currentModule->id }}</span>
                    <span class="w-1 h-1 bg-blue-500 rounded-full"></span>
                    <span>Estado: ACTIVO</span>
                </div>
            </header>

            <!-- Module Image (if available) -->
            @if($currentModule->type === 'quarters')
                <div class="rounded-lg overflow-hidden border border-slate-700/50 shadow-2xl">
                    <img 
                        src="{{ asset('images/modules/habitat-deck.png') }}" 
                        alt="Vista interior de la cubierta de habitación - {{ $station->name }}" 
                        class="w-full h-auto object-cover max-h-96"
                        loading="lazy"
                    >
                </div>
            @endif

            <!-- Module Description -->
            <p class="text-sm text-slate-400 font-['Space_Mono'] italic max-w-2xl leading-relaxed pt-2 border-l-2 border-blue-500/30 pl-4">
                "{{ $currentModule->description }}"
            </p>
        </div>


        <!-- Command Interface Section -->
        <x-station.section title="Acciones Disponibles" border="blue" subtitle="Terminal de Control">
            
            <p class="text-sm text-slate-300 mb-6 leading-relaxed">
                Interactúa con las funciones de este módulo. Selecciona una acción para ejecutar comandos específicos disponibles en esta área.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($currentModule->type === 'quarters')
                    <x-command-button 
                        label="PROTOCOLO_DESCANSO" 
                        description="Restaurar Energía (+10%)" 
                        color="blue" 
                    />
                    <x-command-button 
                        label="ACCEDER_REGISTROS" 
                        description="Ver Mensajes Personales" 
                        color="blue" 
                    />
                @elseif($currentModule->type === 'market')
                    <x-command-button 
                        label="ABRIR_MERCADO" 
                        description="Ver Mercancías" 
                        color="emerald" 
                    />
                @elseif($currentModule->type === 'hangar')
                    @forelse($ships as $ship)
                        <form action="{{ route('station.undock', $station) }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="ship_id" value="{{ $ship->id }}">
                            <x-command-button 
                                type="submit" 
                                label="ABORDAR_NAVE_{{ strtoupper(str_replace(' ', '_', $ship->pivot->name ?? $ship->name)) }}" 
                                description="Clase {{ $ship->class }} // {{ $ship->pivot->integrity }}% INT" 
                                color="blue" 
                            />
                        </form>
                        
                        <x-command-button 
                            label="SISTEMAS_{{ strtoupper(str_replace(' ', '_', $ship->pivot->name ?? $ship->name)) }}" 
                            description="Estado de la nave y carga" 
                            color="blue" 
                        />
                    @empty
                        <div class="col-span-1 md:col-span-2 text-center py-8 border border-dashed border-slate-700 rounded">
                            <p class="text-slate-500 font-mono text-sm">No tienes naves en este hangar.</p>
                        </div>
                    @endforelse
                @endif
            </div>

        </x-station.section>

        <!-- Transit Network -->
        <x-station.transit-network :station="$station" :currentModule="$currentModule" />

        <!-- Scanner Results (Pilots) -->
        <x-station.section title="Escáner de Proximidad" border="emerald" subtitle="Rango: LOCAL // Señales: {{ $localPilots->count() }}">
            
            <p class="text-sm text-slate-300 mb-6 leading-relaxed">
                Detecta señales de vida y actividad en tu sector actual de la estación. Identifica otros pilotos presentes en esta área.
            </p>

            @if($localPilots->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($localPilots as $pilot)
                        <div class="bg-slate-800/50 border border-slate-700 p-4 rounded flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center text-slate-300 font-bold">
                                {{ substr($pilot->first_name, 0, 1) }}
                            </div>
                            <div>
                                <div class="text-white font-bold">{{ $pilot->first_name }} {{ $pilot->last_name }}</div>
                                <div class="text-xs text-slate-400">Piloto</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-slate-500 font-mono text-sm italic">No se detectan otras señales de vida en este sector de la estación.</p>
            @endif
        </x-station.section>

        <!-- Footer -->
        <footer class="text-center py-8 text-[10px] text-slate-700 font-mono uppercase tracking-widest border-t border-white/5 mt-12">
            <span class="opacity-50">Fin de la Transmisión // {{ now()->format('Y') }}</span>
        </footer>

    </div>
</x-game-layout>
