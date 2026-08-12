<x-guest-layout>
    <div class="text-center mb-6">
        <div class="login-logo-wrap mx-auto mb-3 w-fit">
            <div class="login-logo-ring"></div>
            <img src="{{ asset($systemLogo ?? 'logo.png') }}" alt="{{ $systemName ?? 'CantCome' }}" class="login-logo h-16 w-auto">
        </div>
        <h1 class="login-name text-xl font-bold">¿Olvidaste tu contraseña?</h1>
        <div class="login-dots">
            <span></span><span></span><span></span>
        </div>
        <p class="login-sub text-sm text-gray-500 dark:text-gray-400 mt-2">Ingresa tu correo y te enviaremos un enlace para restablecerla.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" placeholder="tu@correo.com" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6 space-y-3">
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Enviar enlace
            </button>

            <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">Volver al inicio de sesión</a>
            </p>
        </div>
    </form>
</x-guest-layout>
