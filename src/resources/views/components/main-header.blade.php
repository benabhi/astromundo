<header 
    class="fixed top-0 w-full z-50 transition-all duration-500 group hover:bg-black/80 hover:backdrop-blur-md border-b border-transparent hover:border-white/5" 
    x-data="{ scrolled: false }" 
    @header-scroll.window="scrolled = $event.detail"
    :class="{ 'bg-black/80 backdrop-blur-md border-white/5': scrolled }"
>
    <div class="max-w-5xl mx-auto px-6 h-16 flex items-center justify-between">
        
        <!-- Left: Brand & Mobile Toggle -->
        <div class="flex items-center gap-4">
            <!-- Mobile Menu Toggle -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-slate-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <a href="{{ route('dashboard') }}" class="text-lg font-bold text-white tracking-[0.3em] font-['Orbitron'] opacity-50 hover:opacity-100 transition-opacity">
                ASTROMUNDO
            </a>
        </div>

        <!-- Center: Navigation Slot -->
        <nav class="hidden md:flex gap-8 text-xs font-bold uppercase tracking-widest text-slate-500 font-['Rajdhani']">
            {{ $nav ?? '' }}
        </nav>

        <!-- Right: User/Actions Slot -->
        <div class="flex items-center gap-4">
            {{ $right ?? '' }}
        </div>
    </div>
</header>
