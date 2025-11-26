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
        
        <!-- TOP NAVIGATION: Matches Home Page Exactly -->
        <header class="fixed top-0 w-full z-50 transition-all duration-500 group hover:bg-black/80 hover:backdrop-blur-md border-b border-transparent hover:border-white/5" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)" :class="{ 'bg-black/80 backdrop-blur-md border-white/5': scrolled }">
            <div class="max-w-5xl mx-auto px-6 h-16 flex items-center justify-between">
                
                <!-- Left: Brand -->
                <a href="{{ route('dashboard') }}" class="text-lg font-bold text-white tracking-[0.3em] font-['Orbitron'] opacity-50 hover:opacity-100 transition-opacity">
                    ASTROMUNDO
                </a>

                <!-- Center: Navigation -->
                <nav class="hidden md:flex gap-8 text-xs font-bold uppercase tracking-widest text-slate-500 font-['Rajdhani']">
                    <a href="{{ route('dashboard') }}" class="hover:text-white transition-colors {{ request()->routeIs('station.*') ? 'text-white' : '' }}">Station</a>
                    <a href="#" class="hover:text-white transition-colors">Ship</a>
                    <a href="#" class="hover:text-white transition-colors">Starmap</a>
                    <a href="#" class="hover:text-white transition-colors">Comms</a>
                </nav>

                <!-- Right: User & Time -->
                <div class="flex items-center gap-4">
                    <span class="text-xs font-bold text-slate-500 font-['Orbitron'] tracking-widest">{{ now()->format('H:i') }}</span>
                    <span class="text-slate-700">/</span>
                    <div class="flex items-center gap-2 group cursor-pointer">
                        <span class="text-xs font-bold text-blue-500 hover:text-blue-400 uppercase tracking-widest transition-colors font-['Rajdhani']">{{ Auth::user()->character->first_name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-[10px] text-slate-600 hover:text-red-400 uppercase tracking-widest transition-colors ml-2">[X]</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN CONTENT WRAPPER -->
        <main class="flex-grow relative z-10 pt-32 pb-20 px-6 overflow-y-auto custom-scrollbar">
            <div class="max-w-5xl mx-auto h-full flex flex-col md:flex-row gap-12">
                
                <!-- HUD OVERLAY (Left - Floating) -->
                <aside class="hidden md:block w-64 flex-shrink-0 pt-8">
                    <!-- Bio-Monitor Widget -->
                    <div class="bg-black/40 backdrop-blur-md border border-white/10 p-4 rounded-sm space-y-4">
                        <h3 class="text-[10px] text-slate-500 font-bold tracking-widest uppercase mb-2">BIO-TELEMETRY</h3>
                        
                        <!-- Energy -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-[10px] uppercase">
                                <span class="text-blue-400 font-bold">Energy</span>
                                <span class="text-white">{{ Auth::user()->character->energy }}%</span>
                            </div>
                            <div class="h-1 w-full bg-gray-800/50">
                                <div class="h-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.8)]" style="width: {{ Auth::user()->character->energy }}%"></div>
                            </div>
                        </div>

                        <!-- Integrity -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-[10px] uppercase">
                                <span class="text-emerald-400 font-bold">Integrity</span>
                                <span class="text-white">{{ Auth::user()->character->integrity }}%</span>
                            </div>
                            <div class="h-1 w-full bg-gray-800/50">
                                <div class="h-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.8)]" style="width: {{ Auth::user()->character->integrity }}%"></div>
                            </div>
                        </div>

                        <!-- Morale -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-[10px] uppercase">
                                <span class="text-amber-400 font-bold">Morale</span>
                                <span class="text-white">{{ Auth::user()->character->happiness }}%</span>
                            </div>
                            <div class="h-1 w-full bg-gray-800/50">
                                <div class="h-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.8)]" style="width: {{ Auth::user()->character->happiness }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Nav (Secondary) -->
                    <nav class="mt-8 space-y-2">
                        @foreach(['Assets', 'Wallet', 'Corp', 'Market', 'Log'] as $item)
                        <a href="#" class="block px-4 py-2 text-xs font-bold text-slate-500 hover:text-white hover:bg-white/5 border-l-2 border-transparent hover:border-white/20 transition-all uppercase tracking-widest">
                            {{ $item }}
                        </a>
                        @endforeach
                        
                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}" class="pt-4 mt-4 border-t border-white/5">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-xs font-bold text-red-500/70 hover:text-red-400 hover:bg-red-500/10 border-l-2 border-transparent hover:border-red-500/30 transition-all uppercase tracking-widest">
                                Terminate Session
                            </button>
                        </form>
                    </nav>
                </aside>

                <!-- CENTER STAGE (The View Content) -->
                <div class="flex-1">
                    {{ $slot }}
                </div>

            </div>
        </main>

        <!-- FOOTER: Minimal -->
        <footer class="fixed bottom-0 w-full py-8 text-center text-[10px] text-slate-700 font-mono uppercase tracking-widest z-40 pointer-events-none">
            <span class="opacity-50">System: {{ $systemName ?? 'Unknown' }} // Connection Stable</span>
        </footer>

    </div>
</body>
</html>
