<x-app-layout>
    <x-slot name="header">{{ isset($product) ? 'Editar Producto' : 'Nuevo Producto' }}</x-slot>

    <div class="max-w-3xl">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8">
            @if(!Auth::user()->isAdmin() && !isset($product))
            <div class="flex items-start gap-3 mb-6 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">Precio pendiente de aprobación</p>
                    <p class="text-xs text-amber-700 dark:text-amber-400 mt-0.5">El producto se creará con el precio que ingreses, pero quedará pendiente de aprobación por un administrador. No estará disponible en ventas hasta que sea aprobado.</p>
                </div>
            </div>
            @endif

            @if(isset($product) && $product->isPricePending())
            <div class="flex items-start gap-3 mb-6 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">Este producto tiene el precio pendiente de aprobación</p>
                    <p class="text-xs text-amber-700 dark:text-amber-400 mt-0.5">Al guardar los cambios, el precio deberá ser aprobado nuevamente por un administrador.</p>
                </div>
            </div>
            @endif

            <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST">
                @csrf
                @if(isset($product)) @method('PUT') @endif

                {{-- Nombre y Categoría --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
                    <div class="sm:col-span-2">
                        <x-input-label for="name" value="Nombre del Producto" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product->name ?? '')" placeholder="Ej: Empanada de Carne" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="category_id" value="Categoría" />
                        <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Sin categoría</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ (old('category_id', $product->category_id ?? '') == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Descripción --}}
                <div class="mb-5">
                    <x-input-label for="description" value="Descripción" />
                    <textarea id="description" name="description" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Descripción opcional del producto...">{{ old('description', $product->description ?? '') }}</textarea>
                </div>

                {{-- Precio, Stock, Código --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div>
                        <x-input-label for="price" value="Precio (₲)" />
                        <div class="relative mt-1">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 font-semibold text-sm">₲</span>
                            <input id="price" name="price" type="number" step="0" min="0" class="pl-8 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('price', $product->price ?? '') }}" placeholder="0" required>
                        </div>
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">Precio en Guaraníes</p>
                    </div>
                    <div>
                        <x-input-label for="stock" value="Stock" />
                        <input id="stock" name="stock" type="number" min="0" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('stock', $product->stock ?? '') }}" placeholder="0" required>
                        <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="barcode" value="Código de Barras" />
                        <x-text-input id="barcode" name="barcode" type="text" class="mt-1 block w-full" :value="old('barcode', $product->barcode ?? '')" placeholder="Opcional" />
                        <x-input-error :messages="$errors->get('barcode')" class="mt-2" />
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-semibold shadow-sm shadow-indigo-200 dark:shadow-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ isset($product) ? 'Actualizar Producto' : 'Guardar Producto' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>