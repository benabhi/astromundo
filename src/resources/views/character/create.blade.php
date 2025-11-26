<x-cinematic-layout>
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-black/50 backdrop-blur-md p-8 border border-white/10 rounded-lg shadow-2xl">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-white font-['Orbitron'] tracking-widest">
                    {{ __('PILOT LICENSE') }}
                </h2>
                <p class="mt-2 text-center text-sm text-slate-400 font-['Space_Mono']">
                    {{ __('Initialize your identity in the database.') }}
                </p>
            </div>

            <form class="mt-8 space-y-6" action="{{ route('character.store') }}" method="POST" x-data="{ 
                firstName: '{{ explode(' ', $initialName)[0] }}', 
                lastName: '{{ explode(' ', $initialName)[1] }}',
                role: 'miner',
                reroll() {
                    fetch('{{ route('api.name-generator') }}')
                        .then(res => res.json())
                        .then(data => {
                            let parts = data.name.split(' ');
                            this.firstName = parts[0];
                            this.lastName = parts[1];
                        });
                }
            }">
                @csrf
                <input type="hidden" name="first_name" :value="firstName">
                <input type="hidden" name="last_name" :value="lastName">

                <!-- Name Display -->
                <div class="bg-white/5 p-4 rounded border border-white/10 text-center relative group">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">{{ __('Identity') }}</label>
                    <div class="text-2xl font-bold text-white font-['Rajdhani'] tracking-wider">
                        <span x-text="firstName"></span> <span x-text="lastName" class="text-blue-400"></span>
                    </div>
                    <button type="button" @click="reroll()" class="absolute right-2 top-2 text-slate-600 hover:text-white transition-colors" title="Reroll Identity">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>

                <!-- Age Selection -->
                <div>
                    <label for="age" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">{{ __('Age') }}</label>
                    <input id="age" name="age" type="number" min="18" max="60" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-white/10 placeholder-slate-500 text-white bg-black/50 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm font-['Space_Mono']" value="25">
                </div>

                <!-- Role Selection -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">{{ __('Career Path') }}</label>
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Miner -->
                        <label class="relative flex items-center justify-between p-4 border border-white/10 rounded cursor-pointer hover:bg-white/5 transition-colors" :class="{ 'border-blue-500 bg-blue-500/10': role === 'miner' }">
                            <div class="flex items-center">
                                <input type="radio" name="role" value="miner" x-model="role" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <div class="ml-3">
                                    <span class="block text-sm font-bold text-white uppercase tracking-wider">{{ __('Miner') }}</span>
                                    <span class="block text-xs text-slate-400">{{ __('Station Alpha // Mole-class Excavator') }}</span>
                                </div>
                            </div>
                        </label>

                        <!-- Transporter -->
                        <label class="relative flex items-center justify-between p-4 border border-white/10 rounded cursor-pointer hover:bg-white/5 transition-colors" :class="{ 'border-blue-500 bg-blue-500/10': role === 'transporter' }">
                            <div class="flex items-center">
                                <input type="radio" name="role" value="transporter" x-model="role" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <div class="ml-3">
                                    <span class="block text-sm font-bold text-white uppercase tracking-wider">{{ __('Transporter') }}</span>
                                    <span class="block text-xs text-slate-400">{{ __('Sector 9 // Mule-class Hauler') }}</span>
                                </div>
                            </div>
                        </label>

                        <!-- Hunter -->
                        <label class="relative flex items-center justify-between p-4 border border-white/10 rounded cursor-pointer hover:bg-white/5 transition-colors" :class="{ 'border-blue-500 bg-blue-500/10': role === 'hunter' }">
                            <div class="flex items-center">
                                <input type="radio" name="role" value="hunter" x-model="role" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <div class="ml-3">
                                    <span class="block text-sm font-bold text-white uppercase tracking-wider">{{ __('Bounty Hunter') }}</span>
                                    <span class="block text-xs text-slate-400">{{ __('Deep Void // Dart-class Interceptor') }}</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-none text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 uppercase tracking-[0.2em] font-['Rajdhani']">
                        {{ __('Issue License') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-cinematic-layout>
