<x-cinematic-layout bg-image="/images/backgrounds/character_creation_lab.png">
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-black/50 backdrop-blur-md p-8 border border-white/10 rounded-lg shadow-2xl">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-white font-['Orbitron'] tracking-widest">
                    {{ __('INITIALIZE') }}
                </h2>
                <p class="mt-2 text-center text-sm text-slate-400 font-['Space_Mono']">
                    {{ __('Create your secure access credentials.') }}
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-6">
                @csrf



                <!-- Email Address -->
                <div class="mt-4">
                    <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">{{ __('Email') }}</label>
                    <input id="email" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-white/10 placeholder-slate-500 text-white bg-black/50 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm font-['Space_Mono']" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">{{ __('Password') }}</label>
                    <input id="password" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-white/10 placeholder-slate-500 text-white bg-black/50 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm font-['Space_Mono']"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">{{ __('Confirm Password') }}</label>
                    <input id="password_confirmation" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-white/10 placeholder-slate-500 text-white bg-black/50 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm font-['Space_Mono']"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mt-8">
                    <a class="text-xs font-bold text-slate-500 hover:text-white uppercase tracking-widest transition-colors" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <button type="submit" class="group relative flex justify-center py-2 px-4 border border-transparent text-sm font-bold rounded-none text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 uppercase tracking-[0.2em] font-['Rajdhani']">
                        {{ __('Register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-cinematic-layout>
