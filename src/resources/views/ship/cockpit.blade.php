<x-game-layout>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Ship Status -->
        <div class="space-y-6">
            <x-station.section title="Estado de la Nave" icon="spaceship">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400 font-mono text-sm">CLASE</span>
                        <span class="text-white font-bold font-['Rajdhani'] uppercase">{{ $ship->class }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400 font-mono text-sm">INTEGRIDAD</span>
                        <span class="text-emerald-500 font-mono">{{ $userShip->integrity }}%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400 font-mono text-sm">ENERGÍA</span>
                        <span class="text-blue-400 font-mono">{{ $ship->current_energy }} / {{ $ship->energy_capacity }}</span>
                    </div>
                    
                    <!-- Energy Bar -->
                    <div class="w-full bg-slate-800 h-2 rounded-full overflow-hidden">
                        <div class="bg-blue-500 h-full" style="width: {{ ($ship->current_energy / $ship->energy_capacity) * 100 }}%"></div>
                    </div>
                </div>
            </x-station.section>

            <!-- Navigation Computer -->
            <x-station.section title="Computadora de Navegación" icon="radar">
                <div class="space-y-4">
                    <div class="bg-black/40 p-4 rounded border border-slate-700">
                        <p class="text-xs text-slate-500 font-mono mb-1">UBICACIÓN ACTUAL</p>
                        <h3 class="text-xl text-white font-bold font-['Rajdhani'] uppercase">{{ $system->name ?? 'Espacio Profundo' }}</h3>
                    </div>

                    <div class="space-y-2">
                        <h4 class="text-xs text-slate-400 font-bold uppercase tracking-widest border-b border-white/10 pb-2">Destinos Cercanos</h4>
                        
                        @if($system)
                            @foreach($system->stations as $station)
                                <div class="flex justify-between items-center bg-white/5 p-3 rounded hover:bg-white/10 transition-colors group">
                                    <div>
                                        <div class="text-white font-bold text-sm">{{ $station->name }}</div>
                                        <div class="text-xs text-slate-500">Estación Espacial</div>
                                    </div>
                                    
                                    @if($station->getDistanceFrom($userShip) < 50) 
                                        <form action="{{ route('station.dock', $station) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold py-1 px-3 rounded uppercase tracking-widest transition-colors font-['Rajdhani']">
                                                Atracar
                                            </button>
                                        </form>
                                    @else
                                        <button class="bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold py-1 px-3 rounded uppercase tracking-widest transition-colors font-['Rajdhani']">
                                            Viajar
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-slate-500 italic text-sm">Sin datos del sistema.</p>
                        @endif
                    </div>
                </div>
            </x-station.section>
        </div>

        <!-- Center/Right: Viewscreen (Placeholder) -->
        <div class="lg:col-span-2">
            <div class="relative h-96 bg-black rounded-lg border border-slate-700 overflow-hidden flex items-center justify-center group">
                <!-- Starfield Effect (CSS) -->
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-50"></div>
                
                <div class="text-center relative z-10">
                    <h2 class="text-3xl text-white font-['Rajdhani'] font-bold uppercase tracking-widest mb-2">Vista Principal</h2>
                    <p class="text-blue-400 font-mono text-sm">Sensores Activos // Escaneando Sector</p>
                </div>

                <!-- HUD Overlay -->
                <div class="absolute inset-0 border-2 border-blue-500/20 rounded-lg pointer-events-none"></div>
                <div class="absolute top-4 left-4 text-blue-500/50 font-mono text-xs">SYS: {{ $system->name ?? 'UNK' }}</div>
                <div class="absolute bottom-4 right-4 text-blue-500/50 font-mono text-xs">V: 0 m/s</div>
            </div>
            
            <!-- Ship Interior Actions -->
            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                <x-command-button label="INGENIERÍA" description="Gestión de Energía" color="slate" />
                <x-command-button label="CARGA" description="Inventario" color="slate" />
                <x-command-button label="COMUNICACIONES" description="Mensajes" color="slate" />
                <x-command-button label="MAPA GALÁCTICO" description="Navegación Larga Distancia" color="blue" />
            </div>
        </div>

    </div>
</x-game-layout>
