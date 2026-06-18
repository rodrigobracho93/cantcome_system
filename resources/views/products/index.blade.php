<x-app-layout>
    <x-slot name="header">Productos</x-slot>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500">Total</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500">Categorías</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['categories'] }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500">Stock Bajo</p>
                <p class="text-lg font-bold text-amber-600 dark:text-amber-400">{{ $stats['lowStock'] }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-red-50 dark:bg-red-900/30 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500">Sin Stock</p>
                <p class="text-lg font-bold text-red-600 dark:text-red-400">{{ $stats['outOfStock'] }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4 flex items-center gap-3 {{ Auth::user()->isAdmin() ? 'cursor-pointer hover:shadow-md transition-shadow' : '' }}">
            <div class="w-10 h-10 rounded-lg bg-orange-50 dark:bg-orange-900/30 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500">Pendientes</p>
                <p class="text-lg font-bold text-orange-600 dark:text-orange-400">{{ $stats['pendingPrices'] }}</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Add Product Button --}}
    <div class="flex items-center justify-end gap-2 mb-4">
        <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium shadow-sm shadow-indigo-200 dark:shadow-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nuevo Producto
        </a>
        @if(Auth::user()->isAdmin())
        <button @click="$dispatch('open-import-modal')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm shadow-emerald-200 dark:shadow-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
            </svg>
            Importar Excel
        </button>
        @endif
    </div>

    {{-- Search & Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-6">
        <form method="GET" action="{{ route('products.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, código o descripción..." class="w-full pl-9 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <select name="category_id" class="w-full sm:w-44 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Todas las categorías</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            @if(Auth::user()->isAdmin())
            <select name="price_status" class="w-full sm:w-36 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Todo estado</option>
                <option value="pending" {{ request('price_status') == 'pending' ? 'selected' : '' }}>Pendientes</option>
                <option value="approved" {{ request('price_status') == 'approved' ? 'selected' : '' }}>Aprobados</option>
            </select>
            @endif
            <select name="stock" class="w-full sm:w-32 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Todo stock</option>
                <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Stock bajo</option>
                <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Sin stock</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">Filtrar</button>
                @if(request('search') || request('category_id') || request('price_status') || request('stock'))
                <a href="{{ route('products.index') }}" class="px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium">Limpiar</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Products --}}
    @if($products->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <p class="text-gray-400 dark:text-gray-500 text-sm">No se encontraron productos</p>
            @if(request('search') || request('category_id') || request('price_status') || request('stock'))
                <a href="{{ route('products.index') }}" class="text-indigo-600 dark:text-indigo-400 text-sm font-medium mt-2 inline-block">Limpiar filtros</a>
            @else
                <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Crear primer producto
                </a>
            @endif
        </div>
    @else
        {{-- Desktop: table --}}
        <div class="hidden md:block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Categoría</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Código</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Precio</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stock</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $product->isPricePending() ? 'border-l-4 border-l-orange-400' : '' }}">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                <div class="flex items-center gap-2">
                                    {{ $product->name }}
                                    @if($product->description)
                                    <span class="text-xs text-gray-400 dark:text-gray-500 font-normal">- {{ Str::limit($product->description, 40) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $product->category?->name ?? 'Sin categoría' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-400 dark:text-gray-500 font-mono">{{ $product->barcode ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-bold {{ $product->isPriceApproved() ? 'text-indigo-600 dark:text-indigo-400' : 'text-orange-600 dark:text-orange-400' }}">₲ {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <span class="inline-flex items-center gap-1 text-sm font-bold {{ $product->stock > 10 ? 'text-emerald-600 dark:text-emerald-400' : ($product->stock > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400') }}">
                                    {{ $product->stock }}
                                </span>
                                <div class="w-16 h-1.5 mx-auto mt-1 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-300 {{ $product->stock > 10 ? 'bg-emerald-500' : ($product->stock > 0 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ min(($product->stock / 50) * 100, 100) }}%"></div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($product->isPricePending())
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 border border-orange-200 dark:border-orange-800">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Pendiente
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Aprobado
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1">
                                    @if(Auth::user()->isAdmin() && $product->isPricePending())
                                    <form action="{{ route('products.approve-price', $product) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1.5 text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Aprobar precio">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                    @if(Auth::user()->isAdmin())
                                    <a href="{{ route('products.edit', $product) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar {{ $product->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile: cards --}}
        <div class="block md:hidden space-y-3">
            @foreach($products as $product)
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden {{ $product->isPricePending() ? 'border-l-4 border-l-orange-400 dark:border-l-orange-500' : '' }}">
                <div class="px-4 pt-4 pb-3">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $product->name }}</h3>
                                @if($product->isPricePending())
                                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-semibold bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 border border-orange-200 dark:border-orange-800">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Pendiente
                                </span>
                                @else
                                <span class="shrink-0 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Aprobado
                                </span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 truncate">{{ $product->category?->name ?? 'Sin categoría' }}</p>
                        </div>
                        <div class="flex gap-1 ml-2 shrink-0">
                            @if(Auth::user()->isAdmin() && $product->isPricePending())
                            <form action="{{ route('products.approve-price', $product) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="p-1.5 text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Aprobar precio">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </form>
                            @endif
                            @if(Auth::user()->isAdmin())
                            <a href="{{ route('products.edit', $product) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Editar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar {{ $product->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Eliminar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                    @if($product->barcode)
                    <p class="text-[10px] text-gray-300 dark:text-gray-600 font-mono mb-2">{{ $product->barcode }}</p>
                    @endif

                    @if($product->description)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3 line-clamp-2">{{ $product->description }}</p>
                    @endif

                    @if($product->creator && $product->isPricePending())
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-2">Creado por {{ $product->creator->name }}</p>
                    @endif

                    <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-700">
                        <div>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider">Precio</p>
                            <p class="text-base font-bold {{ $product->isPriceApproved() ? 'text-indigo-600 dark:text-indigo-400' : 'text-orange-600 dark:text-orange-400' }}">₲ {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider">Stock</p>
                            <span class="inline-flex items-center gap-1 text-sm font-bold {{ $product->stock > 10 ? 'text-emerald-600 dark:text-emerald-400' : ($product->stock > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400') }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                {{ $product->stock }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="h-1 w-full bg-gray-100 dark:bg-gray-700">
                    <div class="h-full transition-all duration-300 {{ $product->stock > 10 ? 'bg-emerald-500' : ($product->stock > 0 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ min(($product->stock / 50) * 100, 100) }}%"></div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @endif

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
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Importar Productos</h3>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400">Subí un archivo Excel con los productos</p>
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
                        <p>Columnas: <strong>Nombre*</strong>, <strong>Categoría</strong>, <strong>Precio*</strong>, <strong>Stock*</strong>, Código de barras, Descripción</p>
                        <p class="mt-1">Las categorías deben coincidir con las existentes en el sistema.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-5">
                    <x-input-label value="Archivo Excel (.xlsx, .xls, .csv)" />
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                        class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50 file:cursor-pointer cursor-pointer border border-gray-300 dark:border-gray-600 rounded-lg">
                </div>
                <div class="flex items-center justify-between">
                    <a href="{{ route('products.import.template') }}" class="inline-flex items-center gap-1.5 text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
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
</x-app-layout>