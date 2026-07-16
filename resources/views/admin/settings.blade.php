<x-app-layout>
    <x-slot name="header">Configuración</x-slot>

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Branding Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6" x-data="brandingPreview()">
            <div class="flex items-center gap-3 mb-5">
                <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Branding</h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Logo y nombre del sistema</p>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-5 text-center mb-5">
                <img :src="previewUrl || '{{ asset($settings['system_logo']) }}'" class="h-16 w-auto mx-auto mb-3 rounded-lg" alt="Logo actual">
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200" x-text="'{{ $settings['system_name'] }}'"></p>
                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">Logo actual</p>
            </div>

            <form action="{{ route('admin.settings.branding') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre del Sistema</label>
                        <input type="text" name="system_name" value="{{ old('system_name', $settings['system_name']) }}" maxlength="100"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm"
                            placeholder="Ej: Mi Cantina">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Logo del Sistema</label>
                        <label class="mt-1 flex items-center justify-center gap-2 px-4 py-3 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-purple-400 dark:hover:border-purple-500 transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs text-gray-500 dark:text-gray-400" x-text="fileName || 'Subir imagen (PNG, JPG, WebP)'"></span>
                            <input type="file" name="system_logo" accept="image/png,image/jpeg,image/webp" class="hidden"
                                @change="fileName = $event.target.files[0]?.name; preview($event)">
                        </label>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">Máx. 4MB. Se aplicará como logo, favicon e ícono PWA.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 mt-5 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" onclick="if(confirm('¿Restablecer el branding a los valores por defecto?')) document.getElementById('resetForm').submit();"
                        class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-900/20 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Restablecer
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 ml-auto px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm font-medium shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Guardar
                    </button>
                </div>
            </form>
        </div>

        {{-- Date & Time Card --}}
        <div class="lg:col-span-2">
            <form action="{{ route('admin.settings.update') }}" method="POST"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                @csrf
                @method('PUT')

                <div class="flex items-center gap-3 mb-5">
                    <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Fecha y Hora</h3>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400">Zona horaria y formatos de visualización</p>
                    </div>
                </div>

                {{-- Live Clock --}}
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-5 text-center mb-5">
                    <p id="serverClock" class="text-3xl font-bold text-gray-900 dark:text-white font-mono tracking-wider"></p>
                    <p id="serverDate" class="text-sm text-gray-500 dark:text-gray-400 mt-1"></p>
                </div>

                {{-- Timezone --}}
                <div class="mb-5">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Zona Horaria</label>
                    <select name="timezone" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @php
                        $timezones = [
                            'America/Asuncion' => 'Paraguay (Asunción) - UTC-4/-3',
                            'America/Argentina/Buenos_Aires' => 'Argentina (Buenos Aires) - UTC-3',
                            'America/Sao_Paulo' => 'Brasil (São Paulo) - UTC-3',
                            'America/Montevideo' => 'Uruguay (Montevideo) - UTC-3',
                            'America/Santiago' => 'Chile (Santiago) - UTC-4/-3',
                            'America/Lima' => 'Perú (Lima) - UTC-5',
                            'America/Bogota' => 'Colombia (Bogotá) - UTC-5',
                            'America/Mexico_City' => 'México (CDMX) - UTC-6/-5',
                            'America/Panama' => 'Panamá - UTC-5',
                            'America/Havana' => 'Cuba (La Habana) - UTC-5/-4',
                            'America/Caracas' => 'Venezuela (Caracas) - UTC-4',
                            'America/Guayaquil' => 'Ecuador (Guayaquil) - UTC-5',
                            'America/La_Paz' => 'Bolivia (La Paz) - UTC-4',
                            'Europe/Madrid' => 'España (Madrid) - UTC+1/+2',
                            'Europe/London' => 'Reino Unido (Londres) - UTC+0/+1',
                            'America/New_York' => 'EE.UU. (Nueva York) - UTC-5/-4',
                            'America/Chicago' => 'EE.UU. (Chicago) - UTC-6/-5',
                            'America/Los_Angeles' => 'EE.UU. (Los Ángeles) - UTC-8/-7',
                        ];
                        @endphp
                        @foreach($timezones as $tz => $label)
                            <option value="{{ $tz }}" {{ $settings['timezone'] === $tz ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Formats --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Date Format --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Formato de Fecha</label>
                        <div class="space-y-2 mt-1">
                            @php
                            $dateFormats = [
                                'd/m/Y' => ['label' => 'dd/mm/aaaa', 'example' => now()->format('d/m/Y')],
                                'm/d/Y' => ['label' => 'mm/dd/aaaa', 'example' => now()->format('m/d/Y')],
                                'Y-m-d' => ['label' => 'aaaa-mm-dd', 'example' => now()->format('Y-m-d')],
                                'd-m-Y' => ['label' => 'dd-mm-aaaa', 'example' => now()->format('d-m-Y')],
                                'd.m.Y' => ['label' => 'dd.mm.aaaa', 'example' => now()->format('d.m.Y')],
                            ];
                            @endphp
                            @foreach($dateFormats as $fmt => $info)
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border cursor-pointer transition-all
                                {{ $settings['date_format'] === $fmt ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-600' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500' }}">
                                <input type="radio" name="date_format" value="{{ $fmt }}" {{ $settings['date_format'] === $fmt ? 'checked' : '' }}
                                    class="border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                                <div class="flex-1">
                                    <span class="text-xs font-medium text-gray-800 dark:text-gray-200">{{ $info['label'] }}</span>
                                </div>
                                <span class="text-xs text-gray-400 dark:text-gray-500 font-mono">{{ $info['example'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Time Format --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Formato de Hora</label>
                        <div class="space-y-2 mt-1">
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border cursor-pointer transition-all
                                {{ $settings['time_format'] === '24h' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-600' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500' }}">
                                <input type="radio" name="time_format" value="24h" {{ $settings['time_format'] === '24h' ? 'checked' : '' }}
                                    class="border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                                <div class="flex-1">
                                    <span class="text-xs font-medium text-gray-800 dark:text-gray-200">24 horas</span>
                                </div>
                                <span class="text-xs text-gray-400 dark:text-gray-500 font-mono">14:30</span>
                            </label>
                            <label class="flex items-center gap-3 p-2.5 rounded-lg border cursor-pointer transition-all
                                {{ $settings['time_format'] === '12h' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-600' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500' }}">
                                <input type="radio" name="time_format" value="12h" {{ $settings['time_format'] === '12h' ? 'checked' : '' }}
                                    class="border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                                <div class="flex-1">
                                    <span class="text-xs font-medium text-gray-800 dark:text-gray-200">12 horas (AM/PM)</span>
                                </div>
                                <span class="text-xs text-gray-400 dark:text-gray-500 font-mono">2:30 PM</span>
                            </label>

                            <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-1">Vista previa:</p>
                                <p id="formatPreview" class="text-sm font-semibold text-gray-800 dark:text-gray-200 font-mono"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-5 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Guardar Configuración
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(Auth::user()->isSuperAdmin())
    {{-- Backup Section --}}
    <div class="mt-6" x-data="backupManager()">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Backups del Sistema</h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Crear, descargar y restaurar copias de seguridad</p>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.backups.create') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" :disabled="creating"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm disabled:opacity-50">
                            <svg x-show="!creating" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <svg x-show="creating" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span x-text="creating ? 'Creando...' : 'Nuevo Backup'"></span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Backup List --}}
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Archivo</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tamaño</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-if="loading">
                            <tr><td colspan="5" class="px-4 py-8 text-center text-sm text-gray-400 dark:text-gray-500">Cargando backups...</td></tr>
                        </template>
                        <template x-if="!loading && backups.length === 0">
                            <tr><td colspan="5" class="px-4 py-8 text-center text-sm text-gray-400 dark:text-gray-500">No hay backups creados aún</td></tr>
                        </template>
                        <template x-for="b in backups" :key="b.filename">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span x-text="b.filename" class="font-mono text-xs"></span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400" x-text="b.user"></td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400" x-text="b.created_at"></td>
                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400" x-text="b.size"></td>
                                <td class="px-4 py-3 text-right text-sm">
                                    <div class="flex items-center justify-end gap-1">
                                        <a :href="'{{ url('admin/backups') }}/' + b.filename + '/download'"
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Descargar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>
                                        <button @click="if(confirm('¿Eliminar este backup?')) deleteBackup(b.filename)"
                                            class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            {{-- Restore --}}
            <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700">
                <form action="{{ route('admin.backups.restore') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Restaurar desde backup</label>
                        <label class="flex items-center gap-2 px-4 py-2.5 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-emerald-400 dark:hover:border-emerald-500 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Seleccionar archivo .sql</span>
                            <input type="file" name="backup_file" accept=".sql" class="hidden" required
                                @change="restoreName = $event.target.files[0]?.name">
                        </label>
                    </div>
                    <div class="pt-5">
                        <button type="submit" onclick="return confirm('¿Restaurar la base de datos desde este backup? Se sobreescribirán todos los datos actuales.')"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm font-medium shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Restaurar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <form id="resetForm" action="{{ route('admin.settings.branding.reset') }}" method="POST" class="hidden">
        @csrf
        @method('POST')
    </form>

    <script>
        function brandingPreview() {
            return {
                fileName: '',
                previewUrl: '',
                preview(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.previewUrl = URL.createObjectURL(file);
                    }
                }
            };
        }

        const configuredTimezone = @json($settings['timezone']);
        const dayNames = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
        const monthNames = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

        function getConfiguredNow() {
            const str = new Date().toLocaleString('en-US', { timeZone: configuredTimezone });
            return new Date(str);
        }

        function updateClock() {
            const now = getConfiguredNow();
            const fmt = document.querySelector('input[name="date_format"]:checked')?.value || 'd/m/Y';
            const timeFmt = document.querySelector('input[name="time_format"]:checked')?.value || '24h';

            const h = now.getHours();
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            let timeStr;
            if (timeFmt === '12h') {
                const ampm = h >= 12 ? 'PM' : 'AM';
                timeStr = (h % 12 || 12) + ':' + m + ':' + s + ' ' + ampm;
            } else {
                timeStr = String(h).padStart(2, '0') + ':' + m + ':' + s;
            }

            const dd = String(now.getDate()).padStart(2, '0');
            const mm = String(now.getMonth() + 1).padStart(2, '0');
            const yyyy = now.getFullYear();
            let dateStr;
            switch (fmt) {
                case 'd/m/Y': dateStr = dd + '/' + mm + '/' + yyyy; break;
                case 'm/d/Y': dateStr = mm + '/' + dd + '/' + yyyy; break;
                case 'Y-m-d': dateStr = yyyy + '-' + mm + '-' + dd; break;
                case 'd-m-Y': dateStr = dd + '-' + mm + '-' + yyyy; break;
                case 'd.m.Y': dateStr = dd + '.' + mm + '.' + yyyy; break;
                default: dateStr = dd + '/' + mm + '/' + yyyy;
            }
            const dayName = dayNames[now.getDay()];
            const monthName = monthNames[now.getMonth()];
            const longDate = dayName + ', ' + now.getDate() + ' de ' + monthName + ' de ' + yyyy;

            const clockEl = document.getElementById('serverClock');
            const dateEl = document.getElementById('serverDate');
            if (clockEl) clockEl.textContent = timeStr;
            if (dateEl) dateEl.textContent = longDate.charAt(0).toUpperCase() + longDate.slice(1);

            const previewEl = document.getElementById('formatPreview');
            if (previewEl) previewEl.textContent = dateStr + ' ' + timeStr;
        }

        document.querySelectorAll('input[name="date_format"], input[name="time_format"], select[name="timezone"]').forEach(el => {
            el.addEventListener('change', () => updateClock());
        });

        updateClock();
        setInterval(updateClock, 1000);

        function backupManager() {
            return {
                backups: [],
                loading: true,
                creating: false,
                restoreName: '',

                init() {
                    this.loadBackups();
                },

                async loadBackups() {
                    this.loading = true;
                    try {
                        const res = await fetch('{{ route("admin.backups.list") }}', {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        this.backups = await res.json();
                    } catch (e) {
                        console.error('Error loading backups', e);
                    }
                    this.loading = false;
                },

                async deleteBackup(filename) {
                    const token = document.querySelector('meta[name="csrf-token"]')?.content;
                    await fetch('{{ url("admin/backups") }}/' + filename, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    this.loadBackups();
                }
            };
        }
    </script>
</x-app-layout>
