<x-app-layout>
    <x-slot name="header">Clientes</x-slot>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('error') }}</div>
    @endif

    <div x-data="{ selected: [], openBulkDelete: false }">

    <div class="flex items-center justify-end gap-2 mb-4">
        <button @click="$dispatch('open-import-modal')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm shadow-emerald-200 dark:shadow-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
            </svg>
            Importar Excel
        </button>
    </div>

    {{-- Add Customer Form --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-6">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Nuevo Cliente</h3>
        <form action="{{ route('customers.store') }}" method="POST" class="flex flex-wrap gap-3">
            @csrf
            <x-text-input name="name" placeholder="Nombre *" class="flex-1 min-w-[180px]" required />
            <x-text-input name="document" placeholder="Cédula/RUC" class="flex-1 min-w-[140px]" />
            <x-text-input name="company" placeholder="Empresa" class="flex-1 min-w-[140px]" />
            <x-text-input name="phone" placeholder="Teléfono" class="flex-1 min-w-[140px]" />
            <x-text-input name="email" placeholder="Email" type="email" class="flex-1 min-w-[180px]" />
            <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">Guardar</button>
        </form>
    </div>

    {{-- Search + bulk delete --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
        <div class="flex items-center gap-3">
            <form method="GET" class="flex items-center gap-3 flex-1 flex-wrap">
                <div class="relative flex-1 min-w-[200px] max-w-md">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar..."
                        class="w-full pl-8 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <select name="company" onchange="this.form.submit()"
                    class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todas las empresas</option>
                    @foreach($companies as $c)
                    <option value="{{ $c }}" {{ request('company') == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">Buscar</button>
                @if(request('q') || request('company'))
                <a href="{{ route('customers.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Limpiar</a>
                @endif
            </form>

            @if(Auth::user()->isSuperAdmin())
            <div x-show="selected.length > 0" x-cloak class="flex items-center gap-2 shrink-0">
                <span class="text-xs text-amber-600 dark:text-amber-400 font-medium" x-text="selected.length + ' seleccionado(s)'"></span>
                <button @click="openBulkDelete = true" class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-xs font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Eliminar
                </button>
            </div>
            @endif
        </div>
    </div>

    {{-- Desktop: table --}}
    <div class="hidden md:block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        @if(Auth::user()->isSuperAdmin())
                        <th class="px-2 py-3 text-center w-10">
                            <input type="checkbox" :checked="selected.length === {{ $customers->count() }} && {{ $customers->count() }} > 0" @change="selected = $event.target.checked ? {{ $customers->pluck('id') }} : []"
                                class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                        </th>
                        @endif
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cédula/RUC</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Empresa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Teléfono</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors" :class="selected.includes({{ $customer->id }}) ? 'bg-indigo-50/50 dark:bg-indigo-900/10' : ''">
                        @if(Auth::user()->isSuperAdmin())
                        <td class="px-2 py-3 text-center">
                            <input type="checkbox" value="{{ $customer->id }}" x-model="selected"
                                class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                        </td>
                        @endif
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $customer->full_name }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $customer->document ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $customer->company ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $customer->phone ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $customer->email ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button @click="$dispatch('open-edit-modal', { id: {{ $customer->id }}, name: '{{ $customer->name }}', document: '{{ $customer->document ?? '' }}', company: '{{ $customer->company ?? '' }}', phone: '{{ $customer->phone ?? '' }}', email: '{{ $customer->email ?? '' }}' })"
                                    class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @if(Auth::user()->isSuperAdmin())
                                <button @click="$dispatch('open-delete-modal', { id: {{ $customer->id }}, name: '{{ $customer->full_name }}' })"
                                    class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Eliminar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No hay clientes registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile: cards --}}
    <div class="block md:hidden space-y-3">
        @forelse($customers as $customer)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <div class="flex items-center gap-3 mb-3">
                @if(Auth::user()->isSuperAdmin())
                <input type="checkbox" value="{{ $customer->id }}" x-model="selected"
                    class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500 shrink-0">
                @endif
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white text-sm font-bold shrink-0">
                    {{ substr($customer->full_name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $customer->full_name }}</p>
                    @if($customer->document)
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">{{ $customer->document }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <button @click="$dispatch('open-edit-modal', { id: {{ $customer->id }}, name: '{{ $customer->name }}', document: '{{ $customer->document ?? '' }}', company: '{{ $customer->company ?? '' }}', phone: '{{ $customer->phone ?? '' }}', email: '{{ $customer->email ?? '' }}' })"
                        class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Editar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    @if(Auth::user()->isSuperAdmin())
                    <button @click="$dispatch('open-delete-modal', { id: {{ $customer->id }}, name: '{{ $customer->full_name }}' })"
                        class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Eliminar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2 text-sm">
                @if($customer->phone)
                <div>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider">Teléfono</p>
                    <p class="text-gray-700 dark:text-gray-300">{{ $customer->phone }}</p>
                </div>
                @endif
                @if($customer->company)
                <div>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider">Empresa</p>
                    <p class="text-gray-700 dark:text-gray-300 truncate">{{ $customer->company }}</p>
                </div>
                @endif
                @if($customer->email)
                <div class="{{ $customer->phone && $customer->company ? 'col-span-2' : '' }}">
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider">Email</p>
                    <p class="text-gray-700 dark:text-gray-300 truncate">{{ $customer->email }}</p>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-sm text-gray-400 dark:text-gray-500">No hay clientes registrados</p>
        </div>
        @endforelse
    </div>

    {{-- Edit Modal --}}
    <div x-data="{ open: false, form: { id: null, name: '', document: '', company: '', phone: '', email: '' } }"
        x-on:open-edit-modal.window="open = true; form.id = $event.detail.id; form.name = $event.detail.name; form.document = $event.detail.document; form.company = $event.detail.company; form.phone = $event.detail.phone; form.email = $event.detail.email"
        x-show="open" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75" @click="open = false"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 w-full max-w-lg p-6"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Editar Cliente</h3>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400" x-text="form.name"></p>
                    </div>
                </div>
                <button @click="open = false" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form :action="`/customers/${form.id}`" method="POST">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <x-input-label value="Nombre" />
                        <x-text-input x-model="form.name" name="name" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label value="Cédula/RUC" />
                        <x-text-input x-model="form.document" name="document" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label value="Empresa" />
                        <x-text-input x-model="form.company" name="company" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label value="Teléfono" />
                        <x-text-input x-model="form.phone" name="phone" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label value="Email" />
                        <x-text-input x-model="form.email" name="email" type="email" class="mt-1 block w-full" />
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 mt-6">
                    <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">Cancelar</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-data="{ open: false, customer: { id: null, name: '' } }"
        x-on:open-delete-modal.window="open = true; customer.id = $event.detail.id; customer.name = $event.detail.name"
        x-show="open" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75" @click="open = false"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 w-full max-w-md p-6"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">
            <div class="flex items-center gap-4 mb-5">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 shrink-0">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Eliminar Cliente</h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Esta acción no se puede deshacer</p>
                </div>
                <button @click="open = false" class="ml-auto p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                ¿Estás seguro de eliminar a <strong class="text-gray-900 dark:text-white" x-text="customer.name"></strong>? Se perderán todos sus datos y no podrás recuperarlos.
            </p>
            <div class="flex items-center justify-end gap-3">
                <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">Cancelar</button>
                <form :action="`/customers/${customer.id}`" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">Eliminar</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Import Excel Modal --}}
    <div x-data="{ open: false }"
        x-on:open-import-modal.window="open = true"
        x-show="open" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75" @click="open = false"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 w-full max-w-lg p-6"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Importar Clientes</h3>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400">Subí un archivo Excel con los clientes</p>
                    </div>
                </div>
                <button @click="open = false" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl p-4 mb-5">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="text-xs text-emerald-700 dark:text-emerald-300">
                        <p class="font-medium mb-1">Formato esperado:</p>
                        <p>Columnas: <strong>Nombre*</strong>, Cédula/RUC, Empresa, Teléfono, Email</p>
                        <p class="mt-1">Si ya existe un cliente con la misma cédula/RUC o email, se ignorará.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-5">
                    <x-input-label value="Archivo Excel (.xlsx, .xls, .csv)" />
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                        class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50 file:cursor-pointer cursor-pointer border border-gray-300 dark:border-gray-600 rounded-lg">
                </div>
                <div class="flex items-center justify-between">
                    <a href="{{ route('customers.import.template') }}" class="inline-flex items-center gap-1.5 text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
                        Descargar plantilla
                    </a>
                    <div class="flex items-center gap-3">
                        <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">Cancelar</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors">Importar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(Auth::user()->isSuperAdmin())
    {{-- Bulk delete confirmation modal --}}
    <div x-show="openBulkDelete" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75" @click="openBulkDelete = false"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 w-full max-w-md p-6"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">
            <div class="flex items-center gap-4 mb-5">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 shrink-0">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Eliminar clientes</h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Esta acción no se puede deshacer</p>
                </div>
                <button @click="openBulkDelete = false" class="ml-auto p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                ¿Estás seguro de eliminar permanentemente <strong class="text-gray-900 dark:text-white" x-text="selected.length"></strong> cliente(s)? Se perderán todos sus datos y no podrás recuperarlos.
            </p>
            <div class="flex items-center justify-end gap-3">
                <button type="button" @click="openBulkDelete = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">Cancelar</button>
                <form method="POST" action="{{ route('customers.destroy-multiple') }}" @submit="openBulkDelete = false">
                    @csrf
                    <template x-for="id in selected" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
    @endif

    </div>
</x-app-layout>