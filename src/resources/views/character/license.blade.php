<x-game-layout>
    <div class="max-w-4xl mx-auto space-y-8">

        <!-- Header -->
        <header class="border-b border-white/10 pb-4">
            <h1 class="text-4xl font-bold text-white font-['Orbitron'] tracking-widest uppercase mb-2">
                Licencia de Piloto
            </h1>
            <div class="flex items-center gap-4 text-xs font-mono text-slate-500 uppercase">
                <span>Reg. ID: {{ str_pad($character->id, 12, '0', STR_PAD_LEFT) }}</span>
                <span>//</span>
                <span>Federación Unida de Comercio</span>
            </div>
        </header>

        <!-- Main Data Block -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            
            <!-- Column 1: Personal Data -->
            <div class="md:col-span-2 space-y-8">
                
                <!-- Identity Section -->
                <section>
                    <h3 class="text-sm font-bold text-blue-400 font-['Rajdhani'] uppercase tracking-widest mb-4 border-l-2 border-blue-500 pl-3">
                        Identidad del Sujeto
                    </h3>
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
                </section>

                <!-- Classification Section -->
                <section>
                    <h3 class="text-sm font-bold text-emerald-400 font-['Rajdhani'] uppercase tracking-widest mb-4 border-l-2 border-emerald-500 pl-3">
                        Clasificación y Rango
                    </h3>
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
                </section>

            </div>

            <!-- Column 2: Digital Signature / Meta -->
            <div class="space-y-8">
                
                <!-- Digital Avatar Placeholder -->
                <div class="border border-white/10 bg-black p-2">
                    <div class="aspect-square bg-slate-900 flex flex-col items-center justify-center relative overflow-hidden">
                        <!-- ASCII Art or Minimal Representation -->
                        <div class="text-6xl font-bold text-slate-800 font-['Orbitron'] select-none">
                            {{ substr($character->first_name, 0, 1) }}
                        </div>
                        <div class="absolute inset-0 opacity-20 pointer-events-none" 
                             style="background-image: repeating-linear-gradient(0deg, transparent, transparent 2px, #000 2px, #000 4px);">
                        </div>
                    </div>
                    <div class="mt-2 text-center text-[10px] text-slate-600 font-mono uppercase">
                        Img_Ref: {{ substr(md5($character->id), 0, 8) }}
                    </div>
                </div>

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

                <!-- Barcode / Signature -->
                <div class="pt-4 border-t border-white/5">
                    <div class="h-12 w-full opacity-50" 
                         style="background-image: repeating-linear-gradient(90deg, #334155, #334155 2px, transparent 2px, transparent 4px, #334155 4px, #334155 8px, transparent 8px, transparent 9px);">
                    </div>
                    <div class="text-[8px] text-center text-slate-700 font-mono mt-1 tracking-[0.2em]">
                        {{ strtoupper(uuid_create()) }}
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
