<x-app-layout>
    <x-slot name="header">Historial de Ventas</x-slot>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <p class="text-sm text-gray-500 dark:text-gray-400">Total: {{ $sales->total() }} ventas</p>
        <a href="{{ route('sales.create') }}" class="self-stretch sm:self-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium shadow-sm shadow-indigo-200 dark:shadow-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nueva Venta
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Desktop: table --}}
    <div class="hidden md:block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vendedor</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pago</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex items-center gap-1.5">
                                #{{ $sale->id }}
                                @if($sale->relationLoaded('cajaMovimientos') && $sale->cajaMovimientos->isNotEmpty())
                                <span class="text-indigo-400 dark:text-indigo-500" title="Vinculado a caja">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $sale->user->name }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $sale->customer?->full_name ?? 'Consumidor Final' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $sale->payment_type === 'contado' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300' }}">
                                {{ ucfirst($sale->payment_type) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-semibold">₲ {{ number_format($sale->total, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $sale->status === 'completado' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : ($sale->status === 'anulado' ? 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300' : 'bg-yellow-50 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300') }}">
                                {{ $sale->status === 'completado' ? 'Completado' : ($sale->status === 'anulado' ? 'Anulado' : 'Pendiente') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('sales.show', $sale) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">Ver</a>
                                @if(Auth::user()->isSuperAdmin() && $sale->status !== 'anulado')
                                <button @click="$dispatch('open-edit-sale', { id: {{ $sale->id }}, payment: '{{ $sale->payment_type }}', notes: '{{ $sale->notes ?? '' }}' })"
                                    class="text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 font-medium">Editar</button>
                                @endif
                                @if(Auth::user()->isAdmin() && $sale->status !== 'anulado')
                                <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline" onsubmit="return confirm('¿Anular esta venta? Se restaurará el stock.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-medium">Anular</button>
                                </form>
                                @endif
                                @if(Auth::user()->isSuperAdmin())
                                <form action="{{ route('sales.force-destroy', $sale) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar permanentemente la venta #{{ $sale->id }}? Esta acción no se puede deshacer.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 font-medium" title="Eliminar permanentemente">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No hay ventas registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile: cards --}}
    <div class="block md:hidden space-y-3">
        @forelse($sales as $sale)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-400">
                    <div class="flex items-center gap-1.5">
                        #{{ $sale->id }}
                        @if($sale->relationLoaded('cajaMovimientos') && $sale->cajaMovimientos->isNotEmpty())
                        <span class="text-indigo-400 dark:text-indigo-500" title="Vinculado a caja">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </span>
                        @endif
                    </div>
                </span>
                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $sale->status === 'completado' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : ($sale->status === 'anulado' ? 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300' : 'bg-yellow-50 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300') }}">
                    {{ $sale->status === 'completado' ? 'Completado' : ($sale->status === 'anulado' ? 'Anulado' : 'Pendiente') }}
                </span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                <div>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider">Fecha</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $sale->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider">Vendedor</p>
                    <p class="text-gray-700 dark:text-gray-300">{{ $sale->user->name }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider">Cliente</p>
                    <p class="text-gray-700 dark:text-gray-300 truncate">{{ $sale->customer?->full_name ?? 'Consumidor Final' }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider">Pago</p>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $sale->payment_type === 'contado' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300' }}">
                        {{ ucfirst($sale->payment_type) }}
                    </span>
                </div>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                <p class="text-lg font-bold text-gray-900 dark:text-white">₲ {{ number_format($sale->total, 0, ',', '.') }}</p>
                <div class="flex items-center gap-2">
                    <a href="{{ route('sales.show', $sale) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg text-xs font-semibold hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors">
                        Ver
                    </a>
                    @if(Auth::user()->isSuperAdmin() && $sale->status !== 'anulado')
                    <button @click="$dispatch('open-edit-sale', { id: {{ $sale->id }}, payment: '{{ $sale->payment_type }}', notes: '{{ $sale->notes ?? '' }}' })"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-lg text-xs font-semibold hover:bg-amber-100 dark:hover:bg-amber-900/50 transition-colors">
                        Editar
                    </button>
                    @endif
                    @if(Auth::user()->isAdmin() && $sale->status !== 'anulado')
                    <form action="{{ route('sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('¿Anular esta venta? Se restaurará el stock.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Anular">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <p class="text-sm text-gray-400 dark:text-gray-500">No hay ventas registradas</p>
        </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $sales->links() }}</div>

    {{-- Edit Sale Modal --}}
    <div x-data="{ open: false, form: { id: null, payment: 'contado', notes: '' } }"
        x-on:open-edit-sale.window="open = true; form.id = $event.detail.id; form.payment = $event.detail.payment; form.notes = $event.detail.notes"
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
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Editar Venta</h3>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400">Modificar forma de pago o notas</p>
                    </div>
                </div>
                <button @click="open = false" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form :action="`/sales/${form.id}`" method="POST">
                @csrf @method('PUT')
                <div class="mb-4">
                    <x-input-label value="Tipo de Pago" />
                    <select x-model="form.payment" name="payment_type" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="contado">Contado</option>
                        <option value="credito">Crédito</option>
                    </select>
                </div>
                <div class="mb-5">
                    <x-input-label value="Notas" />
                    <textarea x-model="form.notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Notas adicionales..."></textarea>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">Cancelar</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>