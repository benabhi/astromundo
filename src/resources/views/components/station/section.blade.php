@props(['title', 'icon' => null, 'border' => 'blue'])

@php
    $borders = [
        'blue' => 'border-blue-500',
        'slate' => 'border-slate-700',
        'emerald' => 'border-emerald-500',
        'amber' => 'border-amber-500',
        'red' => 'border-red-500',
    ];
    $borderColor = $borders[$border] ?? $borders['blue'];
    
    $dots = [
        'blue' => 'bg-blue-500',
        'slate' => 'bg-slate-700',
        'emerald' => 'bg-emerald-500',
        'amber' => 'bg-amber-500',
        'red' => 'bg-red-500',
    ];
    $dotColor = $dots[$border] ?? $dots['blue'];
@endphp

<section {{ $attributes->merge(['class' => 'relative group']) }}>
    <!-- Decorative Line -->
    <div class="absolute left-0 top-0 bottom-0 w-px bg-gradient-to-b from-{{ $border === 'slate' ? 'slate-700' : $border . '-500' }} via-white/10 to-transparent"></div>
    
    <div class="pl-8 md:pl-12 space-y-8">
        
        <!-- Header -->
        <div class="relative">
            <div class="absolute -left-[41px] md:-left-[57px] top-1.5 w-3 h-3 bg-black border-2 {{ $borderColor }} rounded-full z-10"></div>
            <h2 class="text-2xl font-bold text-white font-['Rajdhani'] uppercase tracking-wide drop-shadow-md">
                @if($icon)
                    <span class="text-{{ $border === 'slate' ? 'slate-400' : $border . '-500' }} mr-2">{{ $icon }}</span>
                @endif
                {{ $title }}
            </h2>
            @if(isset($subtitle))
                <div class="text-xs text-slate-400 font-mono mt-1 uppercase tracking-widest">{{ $subtitle }}</div>
            @endif
        </div>

        <!-- Content -->
        <div>
            {{ $slot }}
        </div>

    </div>
</section>
