<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <span>Control de Stock</span>
            <a href="{{ route('stock-movements.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-xs font-medium">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Agregar Stock
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('success') }}</div>
    @endif

    {{-- Filter --}}
    <div x-data="{ query: '', results: [], selectedId: {{ request('product_id') ?: 'null' }}, open: false, loading: false }" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
        <form method="GET" class="flex items-center gap-3">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Producto:</label>
            <input type="hidden" name="product_id" :value="selectedId">
            <div class="relative flex-1 max-w-xs">
                <input type="text"
                    x-model="query"
                    @input.debounce.300ms="
                        if (query.length < 2) { results = []; open = false; return; }
                        loading = true;
                        fetch(`/products/search?q=${encodeURIComponent(query)}`)
                            .then(r => r.json())
                            .then(data => { results = data; open = true; loading = false; });
                    "
                    @keydown.escape="open = false"
                    @click.outside="open = false"
                    @keydown.enter.prevent="if(results.length === 1) { selectedId = results[0].id; query = results[0].name; open = false; $el.closest('form').submit(); }"
                    placeholder="Buscar producto..."
                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">

                <div x-show="loading" x-cloak class="absolute right-3 top-1/2 -translate-y-1/2">
                    <svg class="animate-spin h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                </div>

                <div x-show="open && results.length > 0" x-cloak
                    class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                    <template x-for="p in results" :key="p.id">
                        <div @click="selectedId = p.id; query = p.name; open = false; $el.closest('form').submit();"
                            class="px-3 py-2.5 text-sm cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors flex items-center justify-between"
                            :class="selectedId === p.id ? 'bg-indigo-50 dark:bg-indigo-900/20' : ''">
                            <span class="font-medium text-gray-900 dark:text-white" x-text="p.name"></span>
                            <span class="text-xs text-gray-400" x-text="'stock: ' + p.stock"></span>
                        </div>
                    </template>
                </div>
            </div>
            @if(request('product_id'))
            <a href="{{ route('stock-movements.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Limpiar filtro</a>
            @endif
        </form>
    </div>

    {{-- Movements table --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Movimientos</h3>
        </div>

        @if($movements->isEmpty())
        <div class="p-12 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <p class="text-sm text-gray-400 dark:text-gray-500">Sin movimientos registrados</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Producto</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cantidad</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Motivo</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Registrado por</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($movements as $m)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-2.5 text-sm font-medium text-gray-900 dark:text-white">{{ $m->product->name }}</td>
                        <td class="px-4 py-2.5 text-sm text-emerald-600 dark:text-emerald-400 text-right font-semibold">+{{ $m->quantity }}</td>
                        <td class="px-4 py-2.5 text-sm text-gray-500 dark:text-gray-400">{{ $m->motivo ?? '-' }}</td>
                        <td class="px-4 py-2.5 text-sm text-gray-500 dark:text-gray-400">{{ $m->user?->name ?? '-' }}</td>
                        <td class="px-4 py-2.5 text-sm text-gray-500 dark:text-gray-400 text-right whitespace-nowrap">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2.5 text-sm text-center">
                            <div class="flex items-center justify-center gap-1">
                                <button @click="editId = {{ $m->id }}; editProduct = '{{ $m->product->name }}'; editQuantity = {{ $m->quantity }}; editMotivo = '{{ str_replace("'", "\'", $m->motivo ?? '') }}'; openEdit = true"
                                    class="p-1.5 text-indigo-500 hover:text-indigo-700 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="{{ route('stock-movements.destroy', $m) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este movimiento? Se restarán {{ $m->quantity }} del stock de {{ $m->product->name }}.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700">
            {{ $movements->links() }}
        </div>
        @endif
    </div>

    {{-- Edit modal --}}
    <div x-data="{ openEdit: false, editId: null, editProduct: '', editQuantity: 1, editMotivo: '' }" x-show="openEdit" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-black/50" @click="openEdit = false"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6 w-full max-w-md" @click.outside="openEdit = false">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Editar movimiento</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4" x-text="editProduct"></p>
            <form method="POST" :action="`/stock-movements/${editId}`">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cantidad</label>
                    <input type="number" name="quantity" x-model.number="editQuantity" min="1" required
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Motivo</label>
                    <input type="text" name="motivo" x-model="editMotivo"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" @click="openEdit = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Cancelar</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
