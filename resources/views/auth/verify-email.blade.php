<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Gracias por registrarte. Antes de continuar, verifica tu correo electrónico con el enlace que te enviamos.
        Si no recibiste el correo, te enviaremos otro.
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
            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cerrar sesión
            </button>
        </form>
    </div>
</x-guest-layout>
