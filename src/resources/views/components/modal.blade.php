@props(['name', 'title', 'show' => false])

<div
    x-data="{ show: @js($show) }"
    x-on:open-modal.window="if ($event.detail.name === '{{ $name }}') show = true"
    x-on:close-modal.window="if ($event.detail.name === '{{ $name }}') show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto px-4 py-6 sm:px-0"
    style="display: none;"
>
    <!-- Backdrop with Blur -->
    <div 
        x-show="show" 
        class="fixed inset-0 bg-black/60 backdrop-blur-sm transform transition-all"
        x-on:click="show = false"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>

    <!-- Modal Content -->
    <div 
        x-show="show"
        class="relative w-full max-w-2xl transform overflow-hidden bg-black/80 backdrop-blur-md border border-white/10 rounded-lg shadow-2xl transition-all sm:mx-auto"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        <!-- Header -->
        <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center">
            <h3 class="text-xl font-extrabold text-white font-['Orbitron'] tracking-widest uppercase">
                {{ $title }}
            </h3>
            <button x-on:click="show = false" class="text-slate-400 hover:text-white transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-8">
            {{ $slot }}
        </div>
    </div>
</div>
