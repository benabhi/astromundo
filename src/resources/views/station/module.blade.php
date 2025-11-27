<x-game-layout>
    <x-slot name="systemName">{{ $station->moon->planet->solarSystem->name }}</x-slot>

    <div class="max-w-4xl mx-auto space-y-12">
        
        <!-- Breadcrumb Navigation -->
        <x-breadcrumb :crumbs="[
            ['label' => $station->moon->planet->solarSystem->name, 'url' => route('system.show', $station->moon->planet->solarSystem), 'extra' => $station->moon->planet->solarSystem->stars->first()->name ?? 'Unknown'],
            ['label' => $station->moon->planet->name, 'url' => route('planet.show', $station->moon->planet)],
            ['label' => $station->moon->name, 'url' => route('moon.show', $station->moon)],
            ['label' => $station->name, 'modal' => 'undock-modal'],
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
                <div class="relative group cursor-pointer">
                    <!-- Clipped Container for Image & Effects -->
                    <div class="relative overflow-hidden border-2 border-blue-500 group-hover:border-blue-300 transition-all duration-300 shadow-[0_0_15px_rgba(59,130,246,0.5)] group-hover:shadow-[0_0_30px_rgba(59,130,246,0.8)]" 
                         style="clip-path: polygon(0 0, calc(100% - 20px) 0, 100% 20px, 100% 100%, 20px 100%, 0 calc(100% - 20px));">
                        
                        <!-- Image with Zoom (Added block to remove bottom gap) -->
                        <img 
                            src="{{ asset('images/modules/habitat-deck.png') }}" 
                            alt="Vista interior de la cubierta de habitación - {{ $station->name }}" 
                            class="w-full h-auto object-cover max-h-96 opacity-90 filter grayscale group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700 ease-out block"
                            loading="lazy"
                        >
                        
                        <!-- Tech Grid Overlay -->
                        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(59, 130, 246, 0.1) 2px, rgba(59, 130, 246, 0.1) 4px), repeating-linear-gradient(90deg, transparent, transparent 2px, rgba(59, 130, 246, 0.1) 2px, rgba(59, 130, 246, 0.1) 4px);"></div>
                        
                        <!-- Tech Distortion Scanline (Hover Only) -->
                        <div class="absolute left-0 w-full h-24 z-30 opacity-0 group-hover:opacity-100 transition-opacity duration-200 animate-scan-tech pointer-events-none"
                             style="background: linear-gradient(to bottom, transparent, rgba(59, 130, 246, 0.6), transparent); 
                                    backdrop-filter: hue-rotate(180deg) blur(2px) contrast(2.0);
                                    -webkit-backdrop-filter: hue-rotate(180deg) blur(2px) contrast(2.0);">
                             
                             <!-- Bright Leading Edge -->
                             <div class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-400 shadow-[0_0_15px_rgba(96,165,250,1)]"></div>
                             
                             <!-- Scanline Noise Pattern -->
                             <div class="absolute inset-0 opacity-30" 
                                  style="background-image: repeating-linear-gradient(90deg, transparent, transparent 2px, rgba(255, 255, 255, 0.5) 2px, rgba(255, 255, 255, 0.5) 3px);">
                             </div>
                        </div>

                        <!-- Tech Labels (Inside Clipped Container) -->
                        <div class="absolute top-2 left-2 text-[10px] font-mono text-blue-400 bg-black/50 px-2 py-1 backdrop-blur-sm z-50 pointer-events-none">
                            CAM-{{ $currentModule->id }} // FEED ACTIVO
                        </div>
                        <div class="absolute bottom-0 right-0 text-[10px] font-mono text-blue-400 bg-black/50 px-2 py-1 backdrop-blur-sm z-50 pointer-events-none" style="transform: translateY(-0.5rem);">
                            {{ now()->format('H:i:s') }}
                        </div>
                    </div>
                </div>
                
                <!-- Add animation CSS -->
                <style>
                    @keyframes scan {
                        0% { transform: translateY(-100%); }
                        100% { transform: translateY(400%); }
                    }
                    .animate-scan {
                        animation: scan 3s linear infinite;
                    }
                </style>
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

        <!-- Undock Modal -->
        <x-modal name="undock-modal" title="Desatracar de la Estación">
            <div class="space-y-6">
                <p class="text-slate-300 leading-relaxed">
                    Para ver la estación desde órbita necesitas abordar una de tus naves y desatracar.
                </p>

                @forelse($ships as $ship)
                    <div class="border border-blue-500/30 bg-slate-800/50 p-4 rounded-lg">
                        <div class="flex justify-between items-center mb-3">
                            <div>
                                <h4 class="text-white font-bold font-['Rajdhani'] uppercase">{{ $ship->pivot->name ?? $ship->name }}</h4>
                                <p class="text-xs text-slate-400">Clase {{ $ship->class }} // Integridad: {{ $ship->pivot->integrity }}%</p>
                            </div>
                        </div>
                        
                        <form action="{{ route('station.undock', $station) }}" method="POST">
                            @csrf
                            <input type="hidden" name="ship_id" value="{{ $ship->id }}">
                            <x-command-button 
                                type="submit"
                                label="ABORDAR Y DESATRACAR" 
                                description="Iniciar procedimiento de desatraque" 
                                color="emerald" 
                            />
                        </form>
                    </div>
                @empty
                    <div class="border border-red-500/30 bg-slate-800/50 p-6 rounded-lg text-center">
                        <p class="text-slate-300 mb-2">No tienes naves en esta estación.</p>
                        <p class="text-xs text-slate-500">Necesitas una nave para poder salir al espacio.</p>
                    </div>
                @endforelse
            </div>
        </x-modal>

        <!-- Footer -->
        <footer class="text-center py-8 text-[10px] text-slate-700 font-mono uppercase tracking-widest border-t border-white/5 mt-12">
            <span class="opacity-50">Fin de la Transmisión // {{ now()->format('Y') }}</span>
        </footer>

    </div>
</x-game-layout>
