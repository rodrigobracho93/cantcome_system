<x-app-layout>
    <x-slot name="header">Agregar Stock</x-slot>

    <div class="max-w-lg mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <form method="POST" action="{{ route('stock-movements.store') }}">
                @csrf

                <div x-data="{ query: '', results: [], selected: null, open: false, loading: false }" class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Producto</label>
                    <input type="hidden" name="product_id" :value="selected?.id">
                    <div class="relative">
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
                            @keydown.enter.prevent="if(results.length === 1) { selected = results[0]; query = results[0].name + ' (stock: ' + results[0].stock + ')'; open = false; }"
                            placeholder="Escribir nombre del producto..."
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">

                        <div x-show="loading" x-cloak class="absolute right-3 top-1/2 -translate-y-1/2">
                            <svg class="animate-spin h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        </div>

                        <div x-show="open && results.length > 0" x-cloak
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                            <template x-for="p in results" :key="p.id">
                                <div @click="selected = p; query = p.name + ' (stock: ' + p.stock + ')'; open = false;"
                                    class="px-3 py-2.5 text-sm cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors flex items-center justify-between"
                                    :class="selected?.id === p.id ? 'bg-indigo-50 dark:bg-indigo-900/20' : ''">
                                    <div>
                                        <span class="font-medium text-gray-900 dark:text-white" x-text="p.name"></span>
                                        <span class="text-gray-400 ml-1" x-text="p.barcode ? '(' + p.barcode + ')' : ''"></span>
                                    </div>
                                    <div class="text-xs" :class="p.stock > 0 ? 'text-emerald-500' : 'text-red-400'">
                                        Stock: <span x-text="p.stock"></span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div x-show="open && results.length === 0 && !loading" x-cloak
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg p-3 text-sm text-gray-500 dark:text-gray-400 text-center">
                            Sin resultados
                        </div>
                    </div>
                    <div x-show="selected" x-cloak class="mt-2 flex items-center gap-2 text-sm text-emerald-600 dark:text-emerald-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span x-text="selected?.name"></span>
                        <span class="text-xs text-gray-400">Stock actual: <span x-text="selected?.stock"></span></span>
                        <button type="button" @click="selected = null; query = ''; results = [];" class="ml-1 text-gray-400 hover:text-red-400">&times;</button>
                    </div>
                    @error('product_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cantidad</label>
                    <input type="number" name="quantity" min="1" value="{{ old('quantity') }}" required
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('quantity') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Motivo (opcional)</label>
                    <input type="text" name="motivo" value="{{ old('motivo') }}" placeholder="Ej: Compra a proveedor, reposición..."
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('motivo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                        Agregar Stock
                    </button>
                    <a href="{{ route('stock-movements.index') }}" class="px-5 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
