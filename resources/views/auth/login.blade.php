<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if(session('success'))
    <x-login-alert type="info" :message="session('success')" class="mb-4" />
    @endif

    @if($errors->has('login'))
    <x-login-alert type="error" :message="$errors->first('login')" class="mb-4" />
    @endif

    <div class="text-center mb-6">
        <div class="login-logo-wrap mx-auto mb-3 w-fit">
            <div class="login-logo-ring"></div>
            <img src="{{ asset($systemLogo ?? 'logo.png') }}" alt="{{ $systemName ?? 'CantCome' }}" class="login-logo h-16 w-auto">
        </div>
        <h1 class="login-name text-xl font-bold">{{ $systemName ?? 'CantCome' }}</h1>
        <div class="login-dots">
            <span></span><span></span><span></span>
        </div>
        <p class="login-sub text-sm text-gray-500 dark:text-gray-400 mt-2">Inicia sesión para continuar</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="login" value="Correo electrónico o cédula" />
            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" placeholder="tu@correo.com o 1234567" required autofocus autocomplete="username" />
        </div>

        <div class="mt-4" x-data="{ show: false }">
            <x-input-label for="password" value="Contraseña" />
            <div class="relative mt-1">
                <input id="password" class="block w-full pr-10 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" x-bind:type="show ? 'text' : 'password'" name="password" placeholder="••••••••" required autocomplete="current-password" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-300">Recordarme</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-600 hover:text-indigo-800 underline" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <div class="mt-6 space-y-3">
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Iniciar Sesión
            </button>

            @if (Route::has('register'))
                <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                    ¿No tienes cuenta?
                    <a href="{{ route('register') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">Registrarse</a>
                </p>
            @endif
        </div>
    </form>
</x-guest-layout>
