<x-app-layout>
    <x-slot name="header">Venta #{{ $sale->id }}</x-slot>

    <div class="max-w-4xl">
        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('success') }}</div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8 mb-4">
            {{-- Header --}}
            <div class="flex items-center gap-4 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Venta #{{ $sale->id }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $sale->created_at->locale('es')->isoFormat('dddd, D [de] MMMM [del] YYYY - H:mm') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @php $cajaMov = $sale->cajaMovimientos->first(); @endphp
                    @if($cajaMov)
                    <a href="{{ route('caja.show', $cajaMov->caja) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors" title="Vinculado a caja">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Caja
                    </a>
                    @endif
                    <span class="px-3 py-1.5 rounded-full text-xs font-semibold {{ $sale->status === 'completado' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : ($sale->status === 'anulado' ? 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300' : 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300') }}">
                        {{ $sale->status === 'completado' ? 'Completado' : ($sale->status === 'anulado' ? 'Anulado' : 'Pendiente') }}
                    </span>
                </div>
            </div>

            {{-- Info Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3.5">
                    <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Vendedor</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $sale->user->name }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3.5">
                    <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Cliente</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $sale->customer?->full_name ?? 'Consumidor Final' }}</p>
                    @if($sale->customer)
                        @if($sale->customer->document)
                            <p class="text-[11px] text-gray-500 dark:text-gray-400">Cédula/RUC: {{ $sale->customer->document }}</p>
                        @endif
                        @if($sale->customer->phone)
                            <p class="text-[11px] text-gray-500 dark:text-gray-400">Tel: {{ $sale->customer->phone }}</p>
                        @endif
                    @endif
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3.5">
                    <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Tipo de Pago</p>
                    <p class="mt-1">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $sale->payment_type === 'contado' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300' }}">
                            {{ $sale->payment_type === 'contado' ? 'Contado' : 'Crédito' }}
                        </span>
                    </p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3.5">
                    <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Productos</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $sale->items->sum('quantity') }} unidades</p>
                </div>
            </div>

            {{-- Products Table --}}
            <div class="overflow-x-auto mb-4">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Producto</th>
                            <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Precio</th>
                            <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cant.</th>
                            <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($sale->items as $item)
                        <tr>
                            <td class="px-4 py-2.5 text-sm text-gray-900 dark:text-white">{{ $item->product->name }}</td>
                            <td class="px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 text-right">₲ {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 text-right">{{ $item->quantity }}</td>
                            <td class="px-4 py-2.5 text-sm text-gray-900 dark:text-white text-right font-medium">₲ {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Totals --}}
            <div class="border-t border-gray-100 dark:border-gray-700 pt-4 space-y-1.5">
                <div class="flex justify-end gap-8 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Subtotal</span>
                    <span class="text-gray-900 dark:text-white font-medium w-28 text-right">₲ {{ number_format($sale->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-end gap-8 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">IVA (10%)</span>
                    <span class="text-gray-900 dark:text-white font-medium w-28 text-right">₲ {{ number_format($sale->tax, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-end gap-8 text-base pt-2 border-t border-gray-100 dark:border-gray-700">
                    <span class="font-bold text-gray-900 dark:text-white">Total</span>
                    <span class="font-bold text-indigo-600 dark:text-indigo-400 w-28 text-right text-lg">₲ {{ number_format($sale->total, 0, ',', '.') }}</span>
                </div>
            </div>

            @if($sale->notes)
            <div class="mt-4 p-3.5 bg-gray-50 dark:bg-gray-700/30 rounded-xl">
                <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">Notas</p>
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $sale->notes }}</p>
            </div>
            @endif
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('sales.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
            @if(Auth::user()->isAdmin() && $sale->status !== 'anulado')
            <form action="{{ route('sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('¿Anular esta venta?')">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Anular Venta
                </button>
            </form>
            @endif
        </div>
    </div>
</x-app-layout>