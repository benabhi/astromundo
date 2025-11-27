<x-game-layout>
    <x-slot name="systemName">{{ $system->name }} - Espacio Profundo</x-slot>

    <div class="max-w-6xl mx-auto space-y-8">
        
        <!-- HUD Header -->
        <header class="flex justify-between items-end border-b border-white/10 pb-4">
            <div>
                <h1 class="text-3xl font-bold text-white font-['Orbitron'] uppercase tracking-widest">
                    Navegación Espacial
                </h1>
                <p class="text-sm text-blue-400 font-mono">
                    Sistema: {{ $system->name }} [{{ $system->coords_x }}, {{ $system->coords_y }}]
                </p>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-500 font-mono uppercase">Estado de Sensores</p>
                <p class="text-emerald-400 font-bold">ACTIVO</p>
            </div>
        </header>

        <!-- Main Viewport -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Panel: Navigation & Scanner -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Scanner Results -->
                <div class="bg-black/40 border border-blue-500/30 rounded-lg p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-2 opacity-20">
                        <svg class="w-32 h-32 text-blue-500 animate-spin-slow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 18a6 6 0 100-12 6 6 0 000 12z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 14a2 2 0 100-4 2 2 0 000 4z" />
                        </svg>
                    </div>

                    <h2 class="text-xl font-['Rajdhani'] font-bold text-blue-300 mb-4 uppercase">Objetos en Rango de Escáner</h2>
                    
                    <div class="space-y-3 relative z-10">
                        @forelse($objectsInScannerRange as $object)
                            <div class="flex items-center justify-between bg-slate-800/50 p-3 rounded border border-slate-700 hover:border-blue-500 transition-colors group">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-white font-bold">{{ $object['name'] }}</span>
                                        <span class="text-xs px-2 py-0.5 rounded bg-slate-700 text-slate-300">{{ $object['type'] }}</span>
                                    </div>
                                    <div class="text-xs text-slate-400 font-mono mt-1">
                                        Distancia: {{ number_format($object['distance'], 2) }} UA
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <form action="{{ route('system.space.travel', $system) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="destination_type" value="{{ $object['type'] }}">
                                        <input type="hidden" name="destination_id" value="{{ $object['id'] }}">
                                        <button type="submit" class="px-3 py-1 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold uppercase rounded transition-colors">
                                            Viajar
                                        </button>
                                    </form>
                                    @if($object['type'] === 'station' && $object['distance'] < 0.1)
                                        <form action="{{ route('station.dock', ['station' => $object['id']]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold uppercase rounded transition-colors">
                                                Atracar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-slate-500 font-mono">
                                No se detectan objetos cercanos.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Right Panel: Ship Status -->
            <div class="space-y-6">
                <div class="bg-slate-900/80 border border-slate-700 rounded-lg p-6">
                    <h3 class="text-lg font-['Rajdhani'] font-bold text-white mb-4 uppercase border-b border-slate-700 pb-2">Estado de la Nave</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-xs text-slate-400 mb-1">
                                <span>Integridad del Casco</span>
                                <span>100%</span>
                            </div>
                            <div class="w-full bg-slate-800 rounded-full h-2">
                                <div class="bg-emerald-500 h-2 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between text-xs text-slate-400 mb-1">
                                <span>Combustible</span>
                                <span>75%</span>
                            </div>
                            <div class="w-full bg-slate-800 rounded-full h-2">
                                <div class="bg-amber-500 h-2 rounded-full" style="width: 75%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between text-xs text-slate-400 mb-1">
                                <span>Energía</span>
                                <span>90%</span>
                            </div>
                            <div class="w-full bg-slate-800 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 90%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/80 border border-slate-700 rounded-lg p-6">
                    <h3 class="text-lg font-['Rajdhani'] font-bold text-white mb-4 uppercase border-b border-slate-700 pb-2">Ubicación Actual</h3>
                    <div class="text-sm text-slate-300 font-mono space-y-2">
                        <p>Sistema: <span class="text-white">{{ $system->name }}</span></p>
                        <p>Sector: <span class="text-white">Alfa</span></p>
                        <p>Amenaza: <span class="text-emerald-400">Baja</span></p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-700">
                        <a href="{{ route('system.show', $system) }}" class="block w-full text-center px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-xs font-bold uppercase rounded transition-colors">
                            Volver a Vista del Sistema
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-game-layout>
