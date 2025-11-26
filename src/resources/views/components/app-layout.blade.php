<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
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
        /* Custom Cinematic Animations */
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
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-300 bg-black overflow-x-hidden selection:bg-blue-500 selection:text-white">
    
    <!-- Cinematic Background -->
    <div class="fixed inset-0 z-[-1]">
        <img src="/images/header-bg.jpg" alt="Space" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-b from-black via-transparent to-black"></div>
        <div class="absolute inset-0 vignette"></div>
        <!-- Scanlines -->
        <div class="absolute inset-0 scanline opacity-10 pointer-events-none"></div>
    </div>

    <div class="min-h-full flex flex-col relative">
        
        <!-- Minimalist Top Nav (Fades in on hover/scroll) -->
        <header class="fixed top-0 w-full z-50 transition-all duration-500 group hover:bg-black/80 hover:backdrop-blur-md border-b border-transparent hover:border-white/5" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)" :class="{ 'bg-black/80 backdrop-blur-md border-white/5': scrolled }">
            <div class="max-w-5xl mx-auto px-6 h-16 flex items-center justify-between">
                <a href="/" class="text-lg font-bold text-white tracking-[0.3em] font-['Orbitron'] opacity-50 hover:opacity-100 transition-opacity">
                    ASTROMUNDO
                </a>

                <nav class="hidden md:flex gap-8 text-xs font-bold uppercase tracking-widest text-slate-500 font-['Rajdhani']">
                    <a href="#" class="hover:text-white transition-colors">Chronicles</a>
                    <a href="#" class="hover:text-white transition-colors">Database</a>
                    <a href="#" class="hover:text-white transition-colors">Comms</a>
                </nav>

                <div class="flex items-center gap-4">
                    <a href="#" class="text-xs font-bold text-slate-500 hover:text-white uppercase tracking-widest transition-colors font-['Rajdhani']">Login</a>
                    <span class="text-slate-700">/</span>
                    <a href="#" class="text-xs font-bold text-blue-500 hover:text-blue-400 uppercase tracking-widest transition-colors font-['Rajdhani']">Initialize</a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow relative z-10 pt-32 pb-20">
            {{ $slot }}
        </main>

        <!-- Minimal Footer -->
        <footer class="text-center py-8 text-[10px] text-slate-700 font-mono uppercase tracking-widest">
            <span class="opacity-50">Transmission End // {{ now()->format('Y') }}</span>
        </footer>
    </div>
</body>
</html>