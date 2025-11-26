<x-cinematic-layout>
    <div class="max-w-3xl mx-auto px-6 space-y-24">
        
        <!-- Hero: The Hook -->
        <section class="min-h-[60vh] flex flex-col justify-center text-center space-y-8" x-data="{ show: false }" x-init="setTimeout(() => show = true, 500)">
            <div class="space-y-2">
                <p class="text-blue-500 font-mono text-xs tracking-widest uppercase opacity-0 transition-opacity duration-1000 delay-300" :class="{ 'opacity-100': show }">
                    {{ __('Incoming Transmission...') }}
                </p>
                <h1 class="text-5xl md:text-7xl font-black text-white font-['Orbitron'] tracking-tight leading-none opacity-0 transition-all duration-1000 delay-700 transform translate-y-4" :class="{ 'opacity-100 translate-y-0': show }">
                    {{ __('FORGE YOUR') }} <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-b from-white to-slate-500">{{ __('LEGACY') }}</span>
                </h1>
            </div>

            <p class="text-lg md:text-xl text-slate-400 font-['Space_Mono'] leading-relaxed max-w-xl mx-auto opacity-0 transition-all duration-1000 delay-1000" :class="{ 'opacity-100': show }">
                {{ __('The galaxy is not what it used to be. The corporations rule the void, and the pirates rule the lanes.') }}
                <span class="text-white">{{ __('Who will you become in the chaos?') }}</span>
            </p>

            <div class="pt-8 opacity-0 transition-all duration-1000 delay-[1500ms]" :class="{ 'opacity-100': show }">
                <a href="{{ route('register') }}" class="group relative inline-flex items-center gap-3 px-8 py-3 bg-transparent border border-white/20 hover:border-white/50 text-white text-sm font-bold uppercase tracking-[0.2em] transition-all hover:bg-white/5">
                    <span class="w-1 h-1 bg-blue-500 rounded-full group-hover:animate-ping"></span>
                    {{ __('Begin Simulation') }}
                </a>
            </div>
        </section>

        <!-- Narrative Feed: News & Updates -->
        <section class="space-y-16 border-l border-white/10 pl-8 md:pl-12 relative">
            <!-- Timeline Line -->
            <div class="absolute left-0 top-0 bottom-0 w-px bg-gradient-to-b from-blue-500 via-white/10 to-transparent"></div>

            <!-- News Item 1 -->
            <article class="relative group">
                <div class="absolute -left-[41px] md:-left-[57px] top-2 w-3 h-3 bg-black border-2 border-blue-500 rounded-full z-10 group-hover:scale-125 transition-transform duration-300"></div>
                <div class="space-y-4">
                    <header class="flex items-center gap-4">
                        <span class="text-blue-400 font-mono text-xs">{{ __('SECTOR 7 // UPDATE') }}</span>
                        <span class="h-px w-12 bg-blue-900/50"></span>
                        <time class="text-slate-600 font-mono text-xs">2025.11.26</time>
                    </header>
                    <h2 class="text-3xl font-bold text-white font-['Rajdhani'] group-hover:text-blue-200 transition-colors cursor-pointer">
                        {{ __('Expansion Protocol Initiated') }}
                    </h2>
                    <p class="text-slate-400 leading-loose font-light text-lg max-w-2xl">
                        {{ __('The Galactic Council has officially ratified the expansion into Sector 7.') }} 
                        {{ __('Initial scans indicate') }} <span class="text-white font-medium">{{ __('high-yield titanium deposits') }}</span> {{ __('in the outer rim.') }} 
                        {{ __('Mining guilds are already mobilizing fleets. The rush is on.') }}
                    </p>
                    <a href="#" class="inline-block text-xs font-bold text-slate-500 hover:text-white uppercase tracking-widest transition-colors border-b border-transparent hover:border-white pb-0.5">
                        {{ __('Read Full Report') }}
                    </a>
                </div>
            </article>

            <!-- News Item 2 -->
            <article class="relative group">
                <div class="absolute -left-[41px] md:-left-[57px] top-2 w-3 h-3 bg-black border-2 border-slate-700 group-hover:border-yellow-500 rounded-full z-10 group-hover:scale-125 transition-transform duration-300"></div>
                <div class="space-y-4">
                    <header class="flex items-center gap-4">
                        <span class="text-yellow-600 font-mono text-xs">{{ __('ECONOMY // ALERT') }}</span>
                        <span class="h-px w-12 bg-yellow-900/30"></span>
                        <time class="text-slate-600 font-mono text-xs">2025.11.25</time>
                    </header>
                    <h2 class="text-3xl font-bold text-white font-['Rajdhani'] group-hover:text-yellow-200 transition-colors cursor-pointer">
                        {{ __('Market Volatility Detected') }}
                    </h2>
                    <p class="text-slate-400 leading-loose font-light text-lg max-w-2xl">
                        {{ __('A massive surplus delivery by the "Red Star" transport guild has crashed Titanium prices in Alpha Centauri.') }} 
                        {{ __('Traders are advised to') }} <span class="text-white font-medium">{{ __('hold stock') }}</span> {{ __('or divert to secondary markets immediately.') }}
                    </p>
                </div>
            </article>

            <!-- Live Stats "Interruption" -->
            <div class="relative py-8">
                <div class="absolute -left-[41px] md:-left-[57px] top-1/2 -translate-y-1/2 w-3 h-3 bg-black border-2 border-green-500 rounded-full z-10 animate-pulse"></div>
                <div class="bg-white/5 border border-white/10 p-6 backdrop-blur-sm rounded-sm max-w-md">
                    <h3 class="text-xs font-bold text-green-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                        {{ __('Live Telemetry') }}
                    </h3>
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <div class="text-2xl font-bold text-white font-['Rajdhani']">1,245</div>
                            <div class="text-[10px] text-slate-500 uppercase tracking-widest">{{ __('Active Pilots') }}</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-white font-['Rajdhani']">{{ now()->format('H:i') }}</div>
                            <div class="text-[10px] text-slate-500 uppercase tracking-widest">{{ __('Server Time') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Community Feed -->
            <article class="relative group">
                <div class="absolute -left-[41px] md:-left-[57px] top-2 w-3 h-3 bg-black border-2 border-purple-500 rounded-full z-10 group-hover:scale-125 transition-transform duration-300"></div>
                <div class="space-y-4">
                    <header class="flex items-center gap-4">
                        <span class="text-purple-400 font-mono text-xs">{{ __('RECRUITMENT // OPEN') }}</span>
                    </header>
                    <h2 class="text-3xl font-bold text-white font-['Rajdhani'] group-hover:text-purple-200 transition-colors cursor-pointer">
                        {{ __('New Pilots Manifest') }}
                    </h2>
                    <div class="flex flex-wrap gap-2 max-w-xl">
                        <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-full text-xs text-slate-300">John Doe (Miner)</span>
                        <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-full text-xs text-slate-300">Alice Smith (Trader)</span>
                        <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-full text-xs text-slate-300">Bob Rogers (Hunter)</span>
                        <span class="px-3 py-1 text-xs text-slate-500">+ 9 others</span>
                    </div>
                </div>
            </article>

        </section>

        <!-- Footer CTA -->
        <section class="text-center space-y-6 pt-12 pb-24">
            <h3 class="text-2xl font-bold text-white font-['Orbitron']">{{ __('READY TO JOIN?') }}</h3>
            <div class="flex justify-center gap-8 text-sm font-bold uppercase tracking-widest font-['Rajdhani']">
                <a href="#" class="text-blue-500 hover:text-white transition-colors">{{ __('Discord') }}</a>
                <a href="#" class="text-blue-500 hover:text-white transition-colors">{{ __('Manifesto') }}</a>
                <a href="{{ route('register') }}" class="text-blue-500 hover:text-white transition-colors">{{ __('Register') }}</a>
            </div>
        </section>

    </div>
</x-cinematic-layout>
