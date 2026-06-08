<x-app-layout>
    <x-slot name="header">{{ $product->name }}</x-slot>

    <div class="max-w-2xl">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8">
            <div class="flex items-start gap-5 mb-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $product->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        {{ $product->category?->name ?? 'Sin categoría' }}
                        @if($product->barcode)
                            <span class="mx-2">·</span>
                            <span class="font-mono text-xs">{{ $product->barcode }}</span>
                        @endif
                    </p>
                </div>
                <div class="flex gap-2 shrink-0">
                    <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </a>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>

            {{-- Details --}}
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">Precio</p>
                    <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400">₲ {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">Stock</p>
                    <p class="text-xl font-bold {{ $product->stock > 10 ? 'text-emerald-600 dark:text-emerald-400' : ($product->stock > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400') }}">{{ $product->stock }} unidades</p>
                </div>
            </div>

            @if($product->description)
            <div class="mb-6">
                <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Descripción</p>
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $product->description }}</p>
            </div>
            @endif

            {{-- Metadata --}}
            <div class="text-xs text-gray-400 dark:text-gray-500 border-t border-gray-100 dark:border-gray-700 pt-4">
                <p>Creado: {{ $product->created_at->locale('es')->isoFormat('D [de] MMMM [del] YYYY, H:mm') }}</p>
                @if($product->updated_at != $product->created_at)
                <p class="mt-1">Actualizado: {{ $product->updated_at->locale('es')->isoFormat('D [de] MMMM [del] YYYY, H:mm') }}</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>