<x-app-layout>
    <x-slot name="header">Usuarios</x-slot>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Create User Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Nuevo Usuario</h3>
                <p class="text-[11px] text-gray-500 dark:text-gray-400">Crear un nuevo usuario en el sistema</p>
            </div>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <x-input-label value="Nombre" />
                    <x-text-input name="name" class="mt-1 block w-full" placeholder="Nombre completo" required />
                </div>
                <div>
                    <x-input-label value="Email" />
                    <x-text-input name="email" type="email" class="mt-1 block w-full" placeholder="correo@ejemplo.com" required />
                </div>
                <div>
                    <x-input-label value="Cédula" />
                    <x-text-input name="cedula" type="text" class="mt-1 block w-full" placeholder="1234567" maxlength="20" />
                </div>
                <div>
                    <x-input-label value="Teléfono" />
                    <x-text-input name="phone" type="tel" class="mt-1 block w-full" placeholder="+595 981 234 567" />
                </div>
                <div>
                    <x-input-label value="Contraseña" />
                    <x-text-input name="password" type="password" class="mt-1 block w-full" placeholder="Mín. 6 caracteres" required />
                </div>
                <div>
                    <x-input-label value="Rol" />
                    <select name="role" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="cantina">🍽️ Cantina</option>
                        <option value="admin">🛡️ Administrador</option>
                        @if(Auth::user()->isSuperAdmin())
                        <option value="superadmin">👑 Superadmin</option>
                        @endif
                    </select>
                </div>
                <div x-data="{
                    photoName: '',
                    cameraOpen: false,
                    stream: null,
                    photoBlob: null,
                    initCamera() {
                        if (!navigator.mediaDevices?.getUserMedia) { alert('Tu dispositivo no soporta acceso a la cámara.'); return }
                        navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: 'environment' } } })
                            .then(s => { this.stream = s; this.cameraOpen = true; this.$nextTick(() => { const v = document.getElementById('camera-preview'); if (v) v.srcObject = s }) })
                            .catch(e => alert(e.name === 'OverconstrainedError' ? 'No se encontró una cámara trasera. Recarga la página e inténtalo de nuevo.' : 'No se pudo acceder a la cámara. Verifica que el navegador tenga permiso (y usa https:// o localhost).'))
                    },
                    stopCamera() {
                        if (this.stream) { this.stream.getTracks().forEach(t => t.stop()); this.stream = null }
                        this.cameraOpen = false
                    },
                    capturePhoto() {
                        const v = document.getElementById('camera-preview')
                        if (!v) return
                        const c = document.createElement('canvas')
                        c.width = v.videoWidth; c.height = v.videoHeight
                        c.getContext('2d').drawImage(v, 0, 0)
                        c.toBlob(b => {
                            const f = new File([b], 'foto-capturada.jpg', { type: 'image/jpeg' })
                            const dt = new DataTransfer(); dt.items.add(f)
                            document.getElementById('photo_result').files = dt.files
                            this.photoName = f.name
                            this.photoBlob = b
                            this.stopCamera()
                        }, 'image/jpeg', 0.9)
                    }
                }">
                    <x-input-label value="Foto de perfil" />
                    <div class="mt-1 flex items-center gap-2">
                        <template x-if="!photoName">
                            <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                        </template>
                        <template x-if="photoName">
                            <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </template>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" @click="initCamera()"
                                class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Tomar foto
                            </button>
                            <button type="button"
                                onclick="document.getElementById('file_input').click()"
                                class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Seleccionar archivo
                            </button>
                        </div>
                        <span class="text-xs text-gray-400 dark:text-gray-500" x-text="photoName || 'Sin foto'"></span>
                    </div>
                    <input id="file_input" type="file" name="profile_photo" accept="image/*"
                        class="hidden" @change="photoName = $event.target.files[0]?.name || ''">
                    <input id="photo_result" type="file" name="profile_photo" accept="image/*" class="hidden">

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
                                <video id="camera-preview" autoplay playsinline class="w-full aspect-[4/3] object-cover"></video>
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
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Crear Usuario
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Desktop: table --}}
    <div class="hidden md:block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Listado de Usuarios</h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">{{ $users->count() }} usuario{{ $users->count() !== 1 ? 's' : '' }} registrados</p>
                </div>
            </div>
            <form method="GET" action="{{ route('admin.users') }}" class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar usuarios..." class="pl-9 pr-8 py-2 w-56 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                @if(request('search'))
                <a href="{{ route('admin.users') }}" class="absolute right-2 top-1/2 -translate-y-1/2 p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
                @endif
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contacto</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cédula</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rol</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ !$user->is_active ? 'opacity-60' : '' }}">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white text-xs font-bold shrink-0 overflow-hidden">
                                    @if($user->profile_photo_url)
                                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                    {{ substr($user->name, 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-[11px] text-gray-400 dark:text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $user->phone ?? '-' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                            {{ $user->cedula ?? '-' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium {{ $user->role === 'superadmin' ? 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300' : ($user->role === 'admin' ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'bg-sky-50 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300') }}">
                                @if($user->role === 'superadmin')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                @endif
                                {{ $user->role === 'superadmin' ? '👑 Superadmin' : ($user->role === 'admin' ? '🛡️ Administrador' : '🍽️ Cantina') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-1">
                                @if(Auth::user()->isSuperAdmin() || $user->role !== 'superadmin')
                                <button @click="$dispatch('open-edit-modal', { id: {{ $user->id }}, name: '{{ $user->name }}', email: '{{ $user->email }}', cedula: '{{ $user->cedula ?? '' }}', phone: '{{ $user->phone ?? '' }}', role: '{{ $user->role }}' })"
                                    class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @endif
                                @if($user->id !== Auth::id() && (Auth::user()->isSuperAdmin() || $user->role !== 'superadmin'))
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline" onsubmit="return confirm('¿{{ $user->is_active ? 'Desactivar' : 'Activar' }} a {{ $user->name }}?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="p-2 {{ $user->is_active ? 'text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20' : 'text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20' }} rounded-lg transition-colors" title="{{ $user->is_active ? 'Desactivar' : 'Activar' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($user->is_active)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            @endif
                                        </svg>
                                    </button>
                                </form>
                                @endif
                                @if(Auth::user()->isSuperAdmin() && $user->id !== Auth::id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar permanentemente a {{ $user->name }}? Esta acción no se puede deshacer.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Eliminar permanentemente">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <p class="text-sm text-gray-400 dark:text-gray-500">No hay usuarios registrados</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile: cards --}}
    <div class="block md:hidden space-y-3">
        <div class="flex items-center gap-3 mb-1">
            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">Listado de Usuarios</p>
                <p class="text-[11px] text-gray-500 dark:text-gray-400">{{ $users->count() }} usuario{{ $users->count() !== 1 ? 's' : '' }} registrados</p>
            </div>
        </div>
        @forelse($users as $user)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 {{ !$user->is_active ? 'opacity-60' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white text-xs font-bold shrink-0 overflow-hidden">
                        @if($user->profile_photo_url)
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                        {{ substr($user->name, 0, 1) }}
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $user->name }}</p>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 truncate">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    @if(Auth::user()->isSuperAdmin() || $user->role !== 'superadmin')
                    <button @click="$dispatch('open-edit-modal', { id: {{ $user->id }}, name: '{{ $user->name }}', email: '{{ $user->email }}', cedula: '{{ $user->cedula ?? '' }}', phone: '{{ $user->phone ?? '' }}', role: '{{ $user->role }}' })"
                        class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Editar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    @endif
                    @if($user->id !== Auth::id() && (Auth::user()->isSuperAdmin() || $user->role !== 'superadmin'))
                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" onsubmit="return confirm('¿{{ $user->is_active ? 'Desactivar' : 'Activar' }} a {{ $user->name }}?')">
                        @csrf @method('PATCH')
                        <button type="submit" class="p-1.5 {{ $user->is_active ? 'text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20' : 'text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20' }} rounded-lg transition-colors" title="{{ $user->is_active ? 'Desactivar' : 'Activar' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($user->is_active)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @endif
                            </svg>
                        </button>
                    </form>
                    @endif
                    @if(Auth::user()->isSuperAdmin() && $user->id !== Auth::id())
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Eliminar permanentemente a {{ $user->name }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Eliminar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    @if($user->phone)
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $user->phone }}</span>
                    @endif
                    @if($user->cedula)
                    <span class="text-xs text-gray-500 dark:text-gray-400">C.I. {{ $user->cedula }}</span>
                    @endif
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium {{ $user->role === 'superadmin' ? 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300' : ($user->role === 'admin' ? 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300' : 'bg-sky-50 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300') }}">
                        {{ $user->role === 'superadmin' ? '👑 Superadmin' : ($user->role === 'admin' ? '🛡️ Admin' : '🍽️ Cantina') }}
                    </span>
                </div>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                    {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <p class="text-sm text-gray-400 dark:text-gray-500">No hay usuarios registrados</p>
        </div>
        @endforelse
        @if($users->hasPages())
        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    {{-- Edit User Modal --}}
    <div x-data="{ open: false, user: { id: null, name: '', email: '', cedula: '', phone: '', role: 'cantina' } }"
        @open-edit-modal.window="user = $event.detail; open = true"
        x-show="open" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        {{-- Overlay --}}
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="open = false"></div>
        {{-- Modal --}}
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 w-full max-w-lg z-10"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.outside="open = false">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Editar Usuario</h3>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400" x-text="'Editando a ' + user.name"></p>
                    </div>
                </div>
                <button @click="open = false" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            {{-- Body --}}
            <form method="POST" enctype="multipart/form-data" class="p-6" x-bind:action="`{{ url('admin/users') }}/${user.id}`">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="space-y-4">
                    <div>
                        <x-input-label value="Nombre" />
                        <input type="text" name="name" required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm"
                            x-bind:value="user.name">
                    </div>
                    <div>
                        <x-input-label value="Email" />
                        <input type="email" name="email" required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm"
                            x-bind:value="user.email">
                    </div>
                    <div>
                        <x-input-label value="Cédula" />
                        <input type="text" name="cedula" maxlength="20" placeholder="1234567"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm"
                            x-bind:value="user.cedula">
                    </div>
                    <div>
                        <x-input-label value="Teléfono" />
                        <input type="tel" name="phone"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm"
                            x-bind:value="user.phone">
                    </div>
                    <div>
                        <x-input-label value="Nueva Contraseña" />
                        <input type="password" name="password" placeholder="Dejar en blanco para mantener actual"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm">
                    </div>
                    <div>
                        <x-input-label value="Rol" />
                        <select name="role"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm"
                            x-bind:value="user.role">
                            <option value="cantina">🍽️ Cantina</option>
                            <option value="admin">🛡️ Administrador</option>
                            @if(Auth::user()->isSuperAdmin())
                            <option value="superadmin">👑 Superadmin</option>
                            @endif
                        </select>
                    </div>
                    {{-- Edit photo --}}
                    <div x-data="{
                        photoName: '',
                        cameraOpen: false,
                        stream: null,
                        initCamera() {
                            if (!navigator.mediaDevices?.getUserMedia) { alert('Tu dispositivo no soporta acceso a la cámara.'); return }
                            navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: 'environment' } } })
                                .then(s => { this.stream = s; this.cameraOpen = true; this.$nextTick(() => { const v = document.getElementById('edit-camera-preview'); if (v) v.srcObject = s }) })
                                .catch(e => alert(e.name === 'OverconstrainedError' ? 'No se encontró una cámara trasera. Recarga la página e inténtalo de nuevo.' : 'No se pudo acceder a la cámara. Verifica que el navegador tenga permiso (y usa https:// o localhost).'))
                        },
                        stopCamera() {
                            if (this.stream) { this.stream.getTracks().forEach(t => t.stop()); this.stream = null }
                            this.cameraOpen = false
                        },
                        capturePhoto() {
                            const v = document.getElementById('edit-camera-preview')
                            if (!v) return
                            const c = document.createElement('canvas')
                            c.width = v.videoWidth; c.height = v.videoHeight
                            c.getContext('2d').drawImage(v, 0, 0)
                            c.toBlob(b => {
                                const f = new File([b], 'foto-capturada.jpg', { type: 'image/jpeg' })
                                const dt = new DataTransfer(); dt.items.add(f)
                                document.getElementById('edit_photo_result').files = dt.files
                                this.photoName = f.name
                                this.stopCamera()
                            }, 'image/jpeg', 0.9)
                        }
                    }">
                        <x-input-label value="Foto de perfil" />
                        <div class="mt-1 flex items-center gap-2">
                            <template x-if="!photoName">
                                <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                            </template>
                            <template x-if="photoName">
                                <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </template>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" @click="initCamera()"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Tomar foto
                                </button>
                                <button type="button"
                                    onclick="document.getElementById('edit_file_input').click()"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Seleccionar archivo
                                </button>
                            </div>
                            <span class="text-xs text-gray-400 dark:text-gray-500" x-text="photoName || 'Sin foto'"></span>
                        </div>
                        <input id="edit_file_input" type="file" name="profile_photo" accept="image/*"
                            class="hidden" @change="photoName = $event.target.files[0]?.name || ''">
                        <input id="edit_photo_result" type="file" name="profile_photo" accept="image/*" class="hidden">

                        {{-- Camera modal --}}
                        <div x-show="cameraOpen" x-cloak
                            class="fixed inset-0 z-[60] flex items-center justify-center bg-black/70 p-4"
                            @keydown.escape.window="stopCamera()">
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl overflow-hidden w-full max-w-lg" @click.outside="stopCamera()">
                                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tomar foto</h3>
                                    <button type="button" @click="stopCamera()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                <div class="relative bg-black">
                                    <video id="edit-camera-preview" autoplay playsinline class="w-full aspect-[4/3] object-cover"></video>
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
                </div>
                <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" @click="open = false"
                        class="px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
