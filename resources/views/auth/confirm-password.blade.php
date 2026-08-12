<x-guest-layout>
    <div class="text-center mb-6">
        <div class="login-logo-wrap mx-auto mb-3 w-fit">
            <div class="login-logo-ring"></div>
            <img src="{{ asset($systemLogo ?? 'logo.png') }}" alt="{{ $systemName ?? 'CantCome' }}" class="login-logo h-16 w-auto">
        </div>
        <h1 class="login-name text-xl font-bold">Área segura</h1>
        <div class="login-dots">
            <span></span><span></span><span></span>
        </div>
        <p class="login-sub text-sm text-gray-500 dark:text-gray-400 mt-2">Por favor confirma tu contraseña antes de continuar.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <x-input-label for="password" value="Contraseña" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                Confirmar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
