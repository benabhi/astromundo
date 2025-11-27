@props(['action' => null, 'color' => 'red', 'type' => 'link'])

@php
    $colors = [
        'red' => 'text-slate-500 hover:text-red-400',
        'blue' => 'text-slate-500 hover:text-blue-400',
        'emerald' => 'text-slate-500 hover:text-emerald-400',
    ];
    
    $baseClasses = "text-[10px] font-bold uppercase tracking-widest transition-colors font-['Rajdhani'] flex items-center gap-2 group " . ($colors[$color] ?? $colors['red']);
@endphp

@if($type === 'button')
    <button {{ $attributes->merge(['class' => $baseClasses]) }}>
        <span class="group-hover:translate-x-1 transition-transform duration-300">[ {{ $slot }} ]</span>
    </button>
@else
    <a {{ $attributes->merge(['class' => $baseClasses]) }}>
        <span class="group-hover:translate-x-1 transition-transform duration-300">[ {{ $slot }} ]</span>
    </a>
@endif
