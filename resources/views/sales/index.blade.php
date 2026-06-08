<x-app-layout>
    <x-slot name="header">Historial de Ventas</x-slot>

    <div class="flex justify-between items-center mb-6">
        <p class="text-sm text-gray-500">Total: {{ $sales->total() }} ventas</p>
        <a href="{{ route('sales.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nueva Venta
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Cantinera</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pago</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">#{{ $sale->id }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $sale->user->name }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $sale->customer?->full_name ?? 'Consumidor Final' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $sale->payment_type === 'contado' ? 'bg-emerald-50 text-emerald-700' : 'bg-yellow-50 text-yellow-700' }}">
                                {{ ucfirst($sale->payment_type) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-right font-semibold">₲ {{ number_format($sale->total, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $sale->status === 'completado' ? 'bg-emerald-50 text-emerald-700' : ($sale->status === 'anulado' ? 'bg-red-50 text-red-700' : 'bg-yellow-50 text-yellow-700') }}">
                                {{ ucfirst($sale->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm space-x-2">
                            <a href="{{ route('sales.show', $sale) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Ver</a>
                            @if(Auth::user()->isAdmin() && $sale->status !== 'anulado')
                            <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline" onsubmit="return confirm('¿Anular esta venta?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Anular</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">No hay ventas registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $sales->links() }}</div>
</x-app-layout>
