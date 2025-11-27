@props(['station', 'currentModule'])

<x-station.section title="Red de Tránsito de la Estación" border="slate">
    
    <p class="text-sm text-slate-300 mb-6 leading-relaxed">
        Navega entre los diferentes módulos de la estación. Tu ubicación actual está marcada con un asterisco (*).
    </p>

    <div class="flex flex-wrap gap-4">
        @foreach($station->modules as $module)
            <a href="{{ route('station.module', ['station' => $station, 'module' => $module]) }}" class="text-sm font-mono uppercase tracking-wider {{ $module->id === $currentModule->id ? 'text-blue-500 cursor-default' : 'text-slate-400 hover:text-white hover:underline decoration-blue-500 underline-offset-4 transition-all' }}">
                @if($module->id === $currentModule->id)
                    <span class="mr-1">*</span>
                @endif
                {{ $module->name }}
            </a>
        @endforeach
    </div>
</x-station.section>
