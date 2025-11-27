<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-black">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Astromundo') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=orbitron:400,500,600,700,800,900|rajdhani:300,400,500,600,700|inter:300,400,500,600|space-mono:400,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* Atmospheric Effects */
        @keyframes scanline {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100%); }
        }
        .scanline::before {
            content: " ";
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background: linear-gradient(to bottom, transparent 50%, rgba(0, 0, 0, 0.3) 51%);
            background-size: 100% 4px;
            z-index: 50;
            pointer-events: none;
        }
        .vignette {
            background: radial-gradient(circle, transparent 40%, rgba(0,0,0,0.8) 90%);
        }
        .text-glow {
            text-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
        }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-300 bg-black overflow-hidden selection:bg-blue-500 selection:text-white">
    
    <!-- Background Layer (Matches Home Page Style) -->
    <!-- Changed z-[-1] to z-0 to prevent body background from covering it -->
    <div class="fixed inset-0 z-0">
        <img src="{{ $bgImage ?? '/images/backgrounds/station_bg.png' }}" alt="Background" class="w-full h-full object-cover opacity-50">
        <div class="absolute inset-0 bg-gradient-to-b from-black via-transparent to-black"></div>
        <div class="absolute inset-0 vignette"></div>
        <div class="absolute inset-0 scanline opacity-10 pointer-events-none"></div>
    </div>

    <div class="flex flex-col h-screen overflow-hidden font-['Rajdhani']" x-data="{ mobileMenuOpen: false }">
        
        <!-- TOP NAVIGATION: Shared Component -->
        <x-main-header>
            <x-slot name="nav">
                <a href="{{ route('dashboard') }}" class="hover:text-white transition-colors {{ request()->routeIs('station.*') ? 'text-white' : '' }}">Estación</a>
                <a href="#" class="hover:text-white transition-colors">Nave</a>
                <a href="#" class="hover:text-white transition-colors">Mapa Estelar</a>
                <a href="#" class="hover:text-white transition-colors">Comunicaciones</a>
            </x-slot>

            <x-slot name="right">
                <span class="text-xs font-bold text-slate-400 font-['Orbitron'] tracking-widest">{{ now()->format('H:i') }}</span>
                <span class="text-slate-600">/</span>
                <div class="flex items-center gap-4 group">
                    <span class="text-xs font-bold text-blue-500 hover:text-blue-400 uppercase tracking-widest transition-colors font-['Rajdhani']">{{ Auth::user()->character->first_name }}</span>
                    
                    <!-- Minimal Logout Link -->
                    <form method="POST" action="{{ route('logout') }}" class="flex items-center">
                        @csrf
                        <button type="submit" class="text-[10px] font-bold text-slate-600 hover:text-red-400 uppercase tracking-widest transition-colors font-['Rajdhani']">
                            [ SALIR ]
                        </button>
                    </form>
                </div>
            </x-slot>
        </x-main-header>

        <!-- MAIN CONTENT WRAPPER -->
        <main class="flex-grow relative z-10 pt-32 pb-20 px-6 overflow-y-auto custom-scrollbar" @scroll="$dispatch('header-scroll', $el.scrollTop > 20)">
            <div class="max-w-5xl mx-auto h-full flex flex-col md:flex-row gap-12">
                
                <!-- HUD OVERLAY (Left - Floating) -->
                <aside class="hidden md:block w-64 flex-shrink-0 pt-8">
                    <!-- Bio-Monitor Widget (Textual / Minimal) -->
                    <div class="space-y-6 pl-2">
                        <h3 class="text-[10px] text-slate-500 font-bold tracking-[0.2em] uppercase mb-6">BIO-TELEMETRÍA</h3>
                        
                        <!-- Stats List -->
                        <div class="space-y-4 font-['Rajdhani']">
                            
                            <!-- Energy -->
                            <div class="group">
                                <div class="flex items-baseline justify-between text-slate-400 group-hover:text-blue-400 transition-colors">
                                    <span class="text-xs uppercase tracking-widest">Energía</span>
                                    <span class="text-xl font-bold font-['Orbitron']">{{ Auth::user()->character->energy }}%</span>
                                </div>
                                <div class="h-px w-full bg-slate-800 mt-1 group-hover:bg-blue-500/50 transition-colors"></div>
                            </div>

                            <!-- Integrity -->
                            <div class="group">
                                <div class="flex items-baseline justify-between text-slate-400 group-hover:text-emerald-400 transition-colors">
                                    <span class="text-xs uppercase tracking-widest">Integridad</span>
                                    <span class="text-xl font-bold font-['Orbitron']">{{ Auth::user()->character->integrity }}%</span>
                                </div>
                                <div class="h-px w-full bg-slate-800 mt-1 group-hover:bg-emerald-500/50 transition-colors"></div>
                            </div>

                            <!-- Morale -->
                            <div class="group">
                                <div class="flex items-baseline justify-between text-slate-400 group-hover:text-amber-400 transition-colors">
                                    <span class="text-xs uppercase tracking-widest">Moral</span>
                                    <span class="text-xl font-bold font-['Orbitron']">{{ Auth::user()->character->happiness }}%</span>
                                </div>
                                <div class="h-px w-full bg-slate-800 mt-1 group-hover:bg-amber-500/50 transition-colors"></div>
                            </div>

                        </div>
                    </div>

                    <!-- Quick Nav (Secondary) -->
                    <nav class="mt-8 space-y-2">
                        <a href="#" class="block px-4 py-2 text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 border-l-2 border-transparent hover:border-white/20 transition-all uppercase tracking-widest">
                            Activos
                        </a>
                        <a href="#" class="block px-4 py-2 text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 border-l-2 border-transparent hover:border-white/20 transition-all uppercase tracking-widest">
                            Billetera
                        </a>
                        <a href="#" class="block px-4 py-2 text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 border-l-2 border-transparent hover:border-white/20 transition-all uppercase tracking-widest">
                            Corporación
                        </a>
                        <a href="#" class="block px-4 py-2 text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 border-l-2 border-transparent hover:border-white/20 transition-all uppercase tracking-widest">
                            Mercado
                        </a>
                        <a href="#" class="block px-4 py-2 text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 border-l-2 border-transparent hover:border-white/20 transition-all uppercase tracking-widest">
                            Diario
                        </a>
                        
                        <!-- Logout (Sidebar Duplicate) -->
                        <form method="POST" action="{{ route('logout') }}" class="pt-4 mt-4 border-t border-white/5">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-xs font-bold text-red-400/70 hover:text-red-300 hover:bg-red-500/10 border-l-2 border-transparent hover:border-red-500/30 transition-all uppercase tracking-widest">
                                Cerrar Sesión
                            </button>
                        </form>
                    </nav>
                </aside>

                <!-- CENTER STAGE (The View Content) -->
                <div class="flex-1">
                    {{ $slot }}
                </div>

            </div>

            <!-- FOOTER: Static at bottom of content -->

        </main>

        <!-- MOBILE DRAWER -->
        <div 
            x-show="mobileMenuOpen" 
            class="fixed inset-0 z-50 flex md:hidden"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <!-- Backdrop -->
            <div @click="mobileMenuOpen = false" class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>
            
            <!-- Drawer Content -->
            <div 
                class="relative w-64 h-full bg-black border-r border-white/10 p-6 overflow-y-auto"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
            >
                <div class="space-y-8">
                    <!-- Mobile Nav Links -->
                    <nav class="space-y-4 border-b border-white/10 pb-6">
                        <a href="{{ route('dashboard') }}" class="block text-sm font-bold text-white uppercase tracking-widest font-['Rajdhani']">Estación</a>
                        <a href="#" class="block text-sm font-bold text-slate-400 hover:text-white uppercase tracking-widest font-['Rajdhani']">Nave</a>
                        <a href="#" class="block text-sm font-bold text-slate-400 hover:text-white uppercase tracking-widest font-['Rajdhani']">Mapa Estelar</a>
                        <a href="#" class="block text-sm font-bold text-slate-400 hover:text-white uppercase tracking-widest font-['Rajdhani']">Comunicaciones</a>
                    </nav>

                    <!-- Bio-Telemetry (Mobile Copy) -->
                    <div class="space-y-4">
                        <h3 class="text-[10px] text-slate-500 font-bold tracking-[0.2em] uppercase">BIO-TELEMETRÍA</h3>
                        <div class="space-y-4 font-['Rajdhani']">
                            <!-- Energy -->
                            <div class="flex items-baseline justify-between text-slate-400">
                                <span class="text-xs uppercase tracking-widest">Energía</span>
                                <span class="text-xl font-bold font-['Orbitron'] text-blue-400">{{ Auth::user()->character->energy }}%</span>
                            </div>
                            <!-- Integrity -->
                            <div class="flex items-baseline justify-between text-slate-400">
                                <span class="text-xs uppercase tracking-widest">Integridad</span>
                                <span class="text-xl font-bold font-['Orbitron'] text-emerald-400">{{ Auth::user()->character->integrity }}%</span>
                            </div>
                            <!-- Morale -->
                            <div class="flex items-baseline justify-between text-slate-400">
                                <span class="text-xs uppercase tracking-widest">Moral</span>
                                <span class="text-xl font-bold font-['Orbitron'] text-amber-400">{{ Auth::user()->character->happiness }}%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Links (Mobile Copy) -->
                    <nav class="space-y-2">
                        <a href="#" class="block py-2 text-xs font-bold text-slate-400 hover:text-white uppercase tracking-widest">Activos</a>
                        <a href="#" class="block py-2 text-xs font-bold text-slate-400 hover:text-white uppercase tracking-widest">Billetera</a>
                        <a href="#" class="block py-2 text-xs font-bold text-slate-400 hover:text-white uppercase tracking-widest">Corporación</a>
                        <a href="#" class="block py-2 text-xs font-bold text-slate-400 hover:text-white uppercase tracking-widest">Mercado</a>
                        <a href="#" class="block py-2 text-xs font-bold text-slate-400 hover:text-white uppercase tracking-widest">Diario</a>
                    </nav>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
