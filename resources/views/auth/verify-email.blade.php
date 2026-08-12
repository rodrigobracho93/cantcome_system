<x-guest-layout>
    <div class="text-center mb-6">
        <div class="login-logo-wrap mx-auto mb-3 w-fit">
            <div class="login-logo-ring"></div>
            <img src="{{ asset($systemLogo ?? 'logo.png') }}" alt="{{ $systemName ?? 'CantCome' }}" class="login-logo h-16 w-auto">
        </div>
        <h1 class="login-name text-xl font-bold">Verificar correo</h1>
        <div class="login-dots">
            <span></span><span></span><span></span>
        </div>
        <p class="login-sub text-sm text-gray-500 dark:text-gray-400 mt-2">Gracias por registrarte. Verifica tu correo electrónico con el enlace que te enviamos.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-sm font-medium text-emerald-600">
            Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                Reenviar correo de verificación
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cerrar sesión
            </button>
        </form>
    </div>
</x-guest-layout>
