<x-app-layout>
    <x-slot name="header">Venta #{{ $sale->id }}</x-slot>

    <div class="max-w-4xl">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Cantinera</p>
                    <p class="mt-1 text-sm font-medium text-gray-900">{{ $sale->user->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</p>
                    <p class="mt-1 text-sm font-medium text-gray-900">{{ $sale->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</p>
                    <p class="mt-1 text-sm font-medium text-gray-900">{{ $sale->customer?->full_name ?? 'Consumidor Final' }}</p>
                    @if($sale->customer)
                        <p class="text-xs text-gray-500">{{ $sale->customer->document }} {{ $sale->customer->company ? '- '.$sale->customer->company : '' }}</p>
                    @endif
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo de Pago</p>
                    <p class="mt-1">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $sale->payment_type === 'contado' ? 'bg-emerald-50 text-emerald-700' : 'bg-yellow-50 text-yellow-700' }}">
                            {{ ucfirst($sale->payment_type) }}
                        </span>
                    </p>
                </div>
            </div>

            <table class="min-w-full divide-y divide-gray-200 mb-4">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Producto</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Precio</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Cant.</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($sale->items as $item)
                    <tr>
                        <td class="px-4 py-2.5 text-sm text-gray-900">{{ $item->product->name }}</td>
                        <td class="px-4 py-2.5 text-sm text-gray-700 text-right">₲ {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-2.5 text-sm text-gray-700 text-right">{{ $item->quantity }}</td>
                        <td class="px-4 py-2.5 text-sm text-gray-900 text-right font-medium">₲ {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-4 py-2.5 text-sm text-gray-500 text-right">Subtotal</td>
                        <td class="px-4 py-2.5 text-sm text-gray-900 text-right font-medium">₲ {{ number_format($sale->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-4 py-2.5 text-sm text-gray-500 text-right">IVA (10%)</td>
                        <td class="px-4 py-2.5 text-sm text-gray-900 text-right font-medium">₲ {{ number_format($sale->tax, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-4 py-2.5 text-sm text-gray-900 text-right font-semibold">Total</td>
                        <td class="px-4 py-2.5 text-lg text-gray-900 text-right font-bold">₲ {{ number_format($sale->total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            @if($sale->notes)
            <div class="bg-gray-50 rounded-lg p-3 mb-4">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Notas</p>
                <p class="text-sm text-gray-700">{{ $sale->notes }}</p>
            </div>
            @endif
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('sales.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">Volver</a>
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
