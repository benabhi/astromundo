<x-game-layout>
    <div class="max-w-4xl mx-auto space-y-8">
        
        <header class="pb-4">
            <div class="flex justify-between items-center mb-1">
                <h1 class="text-2xl md:text-3xl font-bold text-white font-['Orbitron'] tracking-widest uppercase">
                    Matriz de Habilidades
                </h1>
                <!-- Close Button -->
                <x-text-action-button :href="route('dashboard')">CERRAR</x-text-action-button>
            </div>
            
            <div class="text-xs text-slate-500 font-mono uppercase">
                Total Skills: {{ $character->skills->count() }}
            </div>
        </header>

        <div class="space-y-12">
            @foreach(['piloting', 'industry', 'trade', 'science', 'combat'] as $category)
                @if(isset($skills[$category]) && $skills[$category]->count() > 0)
                    <x-station.section :title="ucfirst($category)" border="blue" subtitle="Categoría de Habilidad">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($skills[$category] as $skill)
                                <div class="bg-slate-900/40 border border-white/5 p-4 rounded hover:border-blue-500/30 transition-all group">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <div class="text-white font-bold font-['Rajdhani'] uppercase tracking-wide group-hover:text-blue-400 transition-colors">
                                                {{ $skill->name }}
                                            </div>
                                            <div class="text-xs text-slate-500 mt-1 line-clamp-1">
                                                {{ $skill->description }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-slate-700 font-['Orbitron'] group-hover:text-white transition-colors">
                                                {{ $skill->pivot->level }}<span class="text-sm text-slate-600">/5</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- XP Progress -->
                                    <div class="space-y-1">
                                        <div class="h-1.5 bg-slate-800 w-full rounded-full overflow-hidden">
                                            @php
                                                $nextLevelXP = app(\App\Services\SkillService::class)->calculateRequiredXP($skill->pivot->level + 1, $skill->multiplier, $skill->base_xp);
                                                $progress = min(100, ($skill->pivot->xp / $nextLevelXP) * 100);
                                            @endphp
                                            <div class="h-full bg-blue-500" style="width: {{ $progress }}%"></div>
                                        </div>
                                        
                                        <div class="flex justify-between text-[10px] font-mono text-slate-500 uppercase">
                                            <span>XP: {{ number_format($skill->pivot->xp) }} / {{ number_format($nextLevelXP) }}</span>
                                            <span>Mult: x{{ $skill->multiplier }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-station.section>
                @endif
            @endforeach
        </div>

    </div>
</x-game-layout>
