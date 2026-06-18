<section>
    <header>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Información del Perfil</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Actualiza tu foto, nombre y correo electrónico.</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div x-data="{
            photoName: '',
            cameraOpen: false,
            stream: null,
            initCamera() {
                if (!navigator.mediaDevices?.getUserMedia) { alert('Tu dispositivo no soporta acceso a la cámara.'); return }
                navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                    .then(s => { this.stream = s; this.cameraOpen = true; this.$nextTick(() => { const v = document.getElementById('profile-camera-preview'); if (v) v.srcObject = s }) })
                    .catch(() => alert('No se pudo acceder a la cámara. Verifica los permisos.'))
            },
            stopCamera() {
                if (this.stream) { this.stream.getTracks().forEach(t => t.stop()); this.stream = null }
                this.cameraOpen = false
            },
            capturePhoto() {
                const v = document.getElementById('profile-camera-preview')
                if (!v) return
                const c = document.createElement('canvas')
                c.width = v.videoWidth; c.height = v.videoHeight
                c.getContext('2d').drawImage(v, 0, 0)
                c.toBlob(b => {
                    const f = new File([b], 'foto-capturada.jpg', { type: 'image/jpeg' })
                    const dt = new DataTransfer(); dt.items.add(f)
                    document.getElementById('profile_photo_result').files = dt.files
                    this.photoName = f.name
                    this.stopCamera()
                }, 'image/jpeg', 0.9)
            }
        }" class="flex items-start gap-6 mb-6">
            <div class="shrink-0">
                @if ($user->profile_photo_url)
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                @else
                    <div class="w-20 h-20 rounded-full bg-indigo-600 text-white flex items-center justify-center text-2xl font-bold border-2 border-gray-200">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <x-input-label value="Foto de perfil" />
                <div class="mt-1 flex flex-wrap items-center gap-2">
                    <button type="button" @click="initCamera()"
                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Tomar foto
                    </button>
                    <button type="button"
                        onclick="document.getElementById('profile_file_input').click()"
                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Seleccionar archivo
                    </button>
                    <span class="text-xs text-gray-400 dark:text-gray-500" x-text="photoName || 'Sin cambios'"></span>
                </div>
                <input id="profile_file_input" type="file" name="profile_photo" accept="image/jpeg,image/png,image/webp"
                    class="hidden" @change="photoName = $event.target.files[0]?.name || ''">
                <input id="profile_photo_result" type="file" name="profile_photo" accept="image/jpeg,image/png,image/webp" class="hidden">
                <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
                <p class="text-xs text-gray-400 mt-1">JPG, PNG o WEBP. Máximo 8MB.</p>
            </div>

            {{-- Camera modal --}}
            <div x-show="cameraOpen" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4"
                @keydown.escape.window="stopCamera()">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl overflow-hidden w-full max-w-lg" @click.outside="stopCamera()">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tomar foto</h3>
                        <button type="button" @click="stopCamera()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="relative bg-black">
                        <video id="profile-camera-preview" autoplay playsinline class="w-full aspect-[4/3] object-cover"></video>
                    </div>
                    <div class="p-4 flex justify-center gap-3">
                        <button type="button" @click="capturePhoto()"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition-colors text-sm font-medium shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Capturar
                        </button>
                        <button type="button" @click="stopCamera()"
                            class="inline-flex items-center gap-2 px-4 py-3 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-white rounded-full hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors text-sm font-medium">
                            Cancelar
                        </button>
                    </div>
                </div>
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
