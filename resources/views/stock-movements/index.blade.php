<x-app-layout>
    <x-slot name="header">Control de Stock</x-slot>

    <div class="flex justify-end mb-6">
        <a href="{{ route('stock-movements.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors text-sm font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Agregar Stock
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500">Total Movimientos</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($stats['total']) }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500">Unidades Añadidas</p>
                <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($stats['units']) }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500">Productos Afectados</p>
                <p class="text-lg font-bold text-amber-600 dark:text-amber-400">{{ $stats['products'] }}</p>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div x-data="{
        query: '',
        results: [],
        selectedId: {{ request('product_id') ?: 'null' }},
        selectedName: '{{ optional(App\Models\Product::find(request('product_id')))->name ?? '' }}',
        open: false,
        loading: false,
        async search() {
            if (this.query.length < 2) { this.results = []; this.open = false; return; }
            this.loading = true;
            const res = await fetch('{{ route("products.search") }}?q=' + encodeURIComponent(this.query));
            this.results = await res.json();
            this.loading = false;
            this.open = true;
        },
        select(p) {
            this.selectedId = p.id;
            this.selectedName = p.name;
            this.query = p.name;
            this.open = false;
        },
        clear() {
            this.selectedId = null;
            this.selectedName = '';
            this.query = '';
            this.results = [];
        }
    }" class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
        <form method="GET" class="flex flex-col sm:flex-row items-end gap-3">
            <div class="w-full sm:w-64 relative">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Producto</label>
                <div class="relative">
                    <input type="text" x-model="query" @input.debounce.300ms="search" @focus="if(results.length) open = true" placeholder="Buscar producto..."
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm pr-8">
                    <input type="hidden" name="product_id" :value="selectedId">
                    <button type="button" x-show="selectedId" @click="clear()" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div x-show="open && results.length > 0" x-cloak @click.outside="open = false"
                    class="absolute z-20 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-auto">
                    <template x-for="p in results" :key="p.id">
                        <button type="button" @click="select(p)" class="w-full text-left px-3 py-2 text-sm hover:bg-indigo-50 dark:hover:bg-gray-700 transition-colors border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div class="font-medium text-gray-900 dark:text-white" x-text="p.name"></div>
                            <div class="text-xs" :class="p.stock > 0 ? 'text-emerald-500' : 'text-red-400'">Stock: <span x-text="p.stock"></span></div>
                        </button>
                    </template>
                </div>
            </div>
            <div class="w-full sm:w-auto">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Mes</label>
                <select name="month" class="w-full sm:w-40 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="">Todos</option>
                    @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ Carbon\Carbon::create()->month($m)->locale('es')->isoFormat('MMMM') }}</option>
                    @endfor
                </select>
            </div>
            <div class="w-full sm:w-auto">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Año</label>
                <select name="year" class="w-full sm:w-32 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="">Todos</option>
                    @foreach($years as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">Filtrar</button>
                @if(request()->hasAny(['month', 'year', 'product_id']))
                <a href="{{ route('stock-movements.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium">Limpiar</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Desktop: table --}}
    <div class="hidden md:block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
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
                    @forelse($movements as $m)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-2.5 text-sm font-medium text-gray-900 dark:text-white">{{ $m->product->name }}</td>
                        <td class="px-4 py-2.5 text-sm text-emerald-600 dark:text-emerald-400 text-right font-semibold">+{{ number_format($m->quantity) }}</td>
                        <td class="px-4 py-2.5 text-sm text-gray-500 dark:text-gray-400">{{ $m->motivo ?? '-' }}</td>
                        <td class="px-4 py-2.5 text-sm text-gray-500 dark:text-gray-400">{{ $m->user?->name ?? '-' }}</td>
                        <td class="px-4 py-2.5 text-sm text-gray-500 dark:text-gray-400 text-right whitespace-nowrap">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2.5 text-sm text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('stock-movements.edit', $m) }}" class="p-1.5 text-indigo-500 hover:text-indigo-700 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('stock-movements.destroy', $m) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este movimiento? Se restarán {{ $m->quantity }} del stock de {{ $m->product->name }}.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            <svg class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Sin movimientos registrados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700">
            {{ $movements->links() }}
        </div>
    </div>

    {{-- Mobile: cards --}}
    <div class="block md:hidden space-y-3">
        @forelse($movements as $m)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $m->product->name }}</span>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">+{{ number_format($m->quantity) }}</span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                <div>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider">Motivo</p>
                    <p class="font-medium text-gray-700 dark:text-gray-300">{{ $m->motivo ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider">Registrado por</p>
                    <p class="text-gray-700 dark:text-gray-300">{{ $m->user?->name ?? '-' }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $m->created_at->format('d/m/Y H:i') }}</p>
                <div class="flex items-center gap-2">
                    <a href="{{ route('stock-movements.edit', $m) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg text-xs font-semibold hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors">
                        Editar
                    </a>
                    <form action="{{ route('stock-movements.destroy', $m) }}" method="POST" onsubmit="return confirm('¿Eliminar este movimiento? Se restarán {{ $m->quantity }} del stock.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Eliminar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <p class="text-sm text-gray-400 dark:text-gray-500">Sin movimientos registrados</p>
        </div>
        @endforelse
    </div>
</x-app-layout>
