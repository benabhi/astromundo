<x-game-layout>
    <div class="max-w-4xl mx-auto space-y-8">

        <!-- Header -->
        <header class="pb-4">
            <div class="flex justify-between items-center mb-2">
                <h1 class="text-2xl md:text-3xl font-bold text-white font-['Orbitron'] tracking-widest uppercase">
                    Licencia de Piloto
                </h1>
                <!-- Close Button -->
                <x-text-action-button :href="route('dashboard')">CERRAR</x-text-action-button>
            </div>
            
            <div class="flex items-center gap-4 text-xs font-mono text-slate-500 uppercase">
                <span>Reg. ID: {{ strtoupper(substr($character->first_name, 0, 1) . substr($character->last_name, 0, 1)) }}-{{ str_pad($character->id, 12, '0', STR_PAD_LEFT) }}</span>
                <span>//</span>
                <span>Federación Unida de Comercio</span>
            </div>
        </header>

        <!-- Main Data Block -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            
            <!-- Column 1: Personal Data -->
            <div class="md:col-span-2 space-y-12">
                
                <!-- Identity Section -->
                <x-station.section title="Identidad del Sujeto" border="blue" subtitle="Datos Biométricos">
                    <div class="grid grid-cols-2 gap-y-6 gap-x-8">
                        <div>
                            <div class="text-[10px] text-slate-600 font-mono uppercase mb-1">Apellido</div>
                            <div class="text-lg text-white font-['Space_Mono'] uppercase tracking-wide">
                                {{ $character->last_name }}
                            </div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-600 font-mono uppercase mb-1">Nombre</div>
                            <div class="text-lg text-white font-['Space_Mono'] uppercase tracking-wide">
                                {{ $character->first_name }}
                            </div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-600 font-mono uppercase mb-1">Fecha Nacimiento</div>
                            <div class="text-sm text-slate-300 font-mono">
                                {{ $character->date_of_birth->format('Y.m.d') }}
                            </div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-600 font-mono uppercase mb-1">Edad Biológica</div>
                            <div class="text-sm text-slate-300 font-mono">
                                {{ $character->age }} Ciclos
                            </div>
                        </div>
                    </div>
                </x-station.section>

                <!-- Classification Section -->
                <x-station.section title="Clasificación y Rango" border="emerald" subtitle="Estatus Federal">
                    <div class="space-y-4">
                        <div class="flex justify-between items-end border-b border-white/5 pb-2">
                            <span class="text-sm text-slate-400 font-mono">Clase de Ciudadanía</span>
                            <span class="text-white font-bold font-['Orbitron'] tracking-wider">TIPO C</span>
                        </div>
                        <div class="flex justify-between items-end border-b border-white/5 pb-2">
                            <span class="text-sm text-slate-400 font-mono">Estado de Licencia</span>
                            <span class="text-emerald-400 font-bold font-mono uppercase">[ ACTIVO ]</span>
                        </div>
                        <div class="flex justify-between items-end border-b border-white/5 pb-2">
                            <span class="text-sm text-slate-400 font-mono">Nivel de Acceso</span>
                            <span class="text-white font-mono">ESTÁNDAR</span>
                        </div>
                    </div>
                </x-station.section>

            </div>

            <!-- Column 2: Digital Signature / Meta -->
            <div class="space-y-8">
                


                <!-- Meta Data -->
                <div class="text-xs font-mono text-slate-500 space-y-2">
                    <div class="flex justify-between">
                        <span>Expedido:</span>
                        <span class="text-slate-400">{{ $character->created_at->format('Y.m.d') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Vencimiento:</span>
                        <span class="text-slate-400">PERMANENTE</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Sector:</span>
                        <span class="text-slate-400">001 (Helios)</span>
                    </div>
                </div>

            </div>

        </div>

        <!-- Footer Note -->
        <div class="mt-12 pt-6 border-t border-white/10 text-[10px] text-slate-600 font-mono leading-relaxed max-w-2xl">
            <span class="text-blue-500 font-bold">AVISO:</span> Esta licencia digital es propiedad de la Federación Unida de Comercio. La alteración de estos datos constituye un delito federal de Clase A. El portador está autorizado para operar naves espaciales de clase civil y comercial dentro de los sectores designados.
        </div>

    </div>
</x-game-layout>
