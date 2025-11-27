@props(['label', 'description', 'color' => 'blue'])

@php
    $colors = [
        'blue' => [
            'label' => 'text-blue-500',
            'desc_hover' => 'group-hover:text-blue-200',
        ],
        'emerald' => [
            'label' => 'text-emerald-500',
            'desc_hover' => 'group-hover:text-emerald-200',
        ],
        'amber' => [
            'label' => 'text-amber-500',
            'desc_hover' => 'group-hover:text-amber-200',
        ],
        'red' => [
            'label' => 'text-red-500',
            'desc_hover' => 'group-hover:text-red-200',
        ],
    ];
    
    $c = $colors[$color] ?? $colors['blue'];
@endphp

<button {{ $attributes->merge(['type' => 'button', 'class' => 'group flex items-center gap-3 text-left hover:bg-white/5 p-2 -ml-2 rounded transition-all w-full']) }}>
    <span class="{{ $c['label'] }} font-mono group-hover:text-white transition-colors whitespace-nowrap">[ > {{ $label }} ]</span>
    <span class="text-sm text-slate-300 {{ $c['desc_hover'] }}">{{ $description }}</span>
</button>
