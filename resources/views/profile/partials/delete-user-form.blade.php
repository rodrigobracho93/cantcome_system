<section>
    <header>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Desactivar Cuenta</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Desactiva tu cuenta para ocultar tu acceso. Puedes reactivarla después contactando al administrador.</p>
    </header>

    <button type="button" @click="show = true"
        class="mt-6 inline-flex items-center px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
        Desactivar cuenta
    </button>

    <div x-data="{ show: false }">
        <div x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="fixed inset-0 bg-gray-900/50" @click="show = false"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">¿Estás seguro?</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Se desactivará tu cuenta y no podrás acceder hasta que un administrador la reactive. Ingresa tu contraseña para confirmar.</p>

                <form method="post" action="{{ route('profile.destroy') }}" class="mt-4">
                    @csrf
                    @method('delete')

                    <x-input-label for="password" value="Contraseña" />
                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" placeholder="••••••••" />

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="show = false" class="inline-flex items-center px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium">Cancelar</button>
                        <x-danger-button>Desactivar cuenta</x-danger-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
