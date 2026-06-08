<section>
    <header>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Información del Perfil</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Actualiza tu foto, nombre y correo electrónico.</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="flex items-center gap-6 mb-6">
            <div class="shrink-0">
                @if ($user->profile_photo_url)
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                @else
                    <div class="w-20 h-20 rounded-full bg-indigo-600 text-white flex items-center justify-center text-2xl font-bold border-2 border-gray-200">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <div>
                <x-input-label for="profile_photo" value="Foto de perfil" />
                <input id="profile_photo" name="profile_photo" type="file" accept="image/jpeg,image/png,image/webp" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
                <p class="text-xs text-gray-400 mt-1">JPG, PNG o WEBP. Máximo 2MB.</p>
            </div>
        </div>

        <div>
            <x-input-label for="name" value="Nombre" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-4 mt-6">
            <x-primary-button>Guardar</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p class="text-sm text-emerald-600 dark:text-emerald-400">Guardado.</p>
            @endif
        </div>
    </form>
</section>
