<x-cinematic-layout bg-image="/images/backgrounds/login_background.png">
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-black/50 backdrop-blur-md p-8 border border-white/10 rounded-lg shadow-2xl">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-white font-['Orbitron'] tracking-widest">
                    {{ __('ACCESS TERMINAL') }}
                </h2>
                <p class="mt-2 text-center text-sm text-slate-400 font-['Space_Mono']">
                    {{ __('Enter credentials to establish connection.') }}
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">{{ __('Email') }}</label>
                    <input id="email" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-white/10 placeholder-slate-500 text-white bg-black/50 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm font-['Space_Mono']" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">{{ __('Password') }}</label>
                    <input id="password" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-white/10 placeholder-slate-500 text-white bg-black/50 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm font-['Space_Mono']"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 bg-black/50 border-white/10" name="remember">
                        <span class="ms-2 text-sm text-slate-400">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-8">
                    @if (Route::has('password.request'))
                        <a class="text-xs font-bold text-slate-500 hover:text-white uppercase tracking-widest transition-colors" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <button type="submit" class="group relative flex justify-center py-2 px-4 border border-transparent text-sm font-bold rounded-none text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 uppercase tracking-[0.2em] font-['Rajdhani']">
                        {{ __('Log in') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-cinematic-layout>
