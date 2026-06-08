<section>
    <header>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Cambiar Contraseña</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Usa una contraseña larga y segura.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="current_password" value="Contraseña actual" />
            <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Nueva contraseña" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmar contraseña" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 mt-6">
            <x-primary-button>Guardar</x-primary-button>

            @if (session('status') === 'password-updated')
                <p class="text-sm text-emerald-600 dark:text-emerald-400">Guardado.</p>
            @endif
        </div>
    </form>
</section>
