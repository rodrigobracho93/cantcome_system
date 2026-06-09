<x-app-layout>
    <x-slot name="header">Caja — {{ $caja->fecha_apertura->format('d/m/Y') }}</x-slot>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 text-sm">{{ $errors->first() }}</div>
    @endif

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <p class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Inicial</p>
            <p class="text-lg font-bold text-gray-900 dark:text-white">₲ {{ number_format($caja->monto_inicial, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <p class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Ingresos</p>
            <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">₲ {{ number_format($caja->total_ingresos, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <p class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Egresos</p>
            <p class="text-lg font-bold text-red-600 dark:text-red-400">₲ {{ number_format($caja->total_egresos, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <p class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Esperado</p>
            <p class="text-lg font-bold text-gray-900 dark:text-white">₲ {{ number_format($caja->monto_final_esperado, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <p class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Estado</p>
            <p class="text-lg font-bold {{ $caja->estado === 'abierta' ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-600 dark:text-gray-400' }}">{{ $caja->estado === 'abierta' ? 'Abierta' : 'Cerrada' }}</p>
        </div>
    </div>

    @if($caja->diferencia !== null)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
        <div class="flex items-center justify-between">
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Diferencia</span>
            <span class="text-lg font-bold {{ $caja->diferencia >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                {{ $caja->diferencia >= 0 ? '+' : '' }}₲ {{ number_format($caja->diferencia, 0, ',', '.') }}
            </span>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Movements --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Movimientos</h3>
            </div>
            @if($caja->estado === 'abierta')
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
                <div class="flex items-center gap-1.5 mb-2 text-[11px] text-gray-400 dark:text-gray-500">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Las ventas se registran automáticamente. Usa esto solo para movimientos manuales.
                </div>
                <form action="{{ route('caja.movimiento.store', $caja) }}" method="POST" class="flex flex-wrap items-end gap-2">
                    @csrf
                    <select name="tipo" class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="ingreso">Ingreso</option>
                        <option value="egreso">Egreso</option>
                    </select>
                    <input type="text" name="concepto" placeholder="Concepto" required class="flex-1 min-w-[120px] text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    <input type="number" name="monto" placeholder="Monto" step="0.01" min="0.01" required class="w-28 text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    <button type="submit" class="px-3 py-2 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">Agregar</button>
                </form>
            </div>
            @endif
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($caja->movimientos as $mov)
                <div class="flex items-center justify-between px-5 py-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="shrink-0 w-6 h-6 flex items-center justify-center rounded-full text-xs font-bold {{ $mov->tipo === 'ingreso' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' }}">
                            {{ $mov->tipo === 'ingreso' ? '+' : '-' }}
                        </span>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-[200px]">{{ $mov->concepto }}</p>
                                @if($mov->referencia_type === 'App\Models\Sale' && $mov->referencia)
                                <a href="{{ route('sales.show', $mov->referencia) }}" class="shrink-0 p-1 text-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Ver venta">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                </a>
                                @endif
                            </div>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500">{{ $mov->created_at->format('H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="text-sm font-semibold {{ $mov->tipo === 'ingreso' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $mov->tipo === 'ingreso' ? '+' : '-' }}₲ {{ number_format($mov->monto, 0, ',', '.') }}
                        </span>
                        @if($caja->estado === 'abierta' && Auth::user()->isAdmin() && !$mov->referencia_type)
                        <form action="{{ route('caja.movimiento.destroy', [$caja, $mov]) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este movimiento?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1 text-gray-300 hover:text-red-500 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-sm text-gray-400 dark:text-gray-500">Sin movimientos</div>
                @endforelse
            </div>
        </div>

        {{-- Close Caja --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            @if($caja->estado === 'abierta')
            <div class="flex items-center gap-3 mb-5">
                <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Cerrar Caja</h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Ingresa el monto final contado para cerrar</p>
                </div>
            </div>
            <form action="{{ route('caja.close', $caja) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <x-input-label value="Monto Final Real (₲)" />
                    <x-text-input name="monto_final_real" type="number" step="0.01" min="0" class="mt-1 block w-full" placeholder="0.00" required />
                </div>
                <div class="mb-4">
                    <x-input-label value="Observaciones (opcional)" />
                    <textarea name="observaciones" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Notas..."></textarea>
                </div>
                <button type="submit" class="w-full px-4 py-2.5 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-lg transition-colors">Cerrar Caja</button>
            </form>
            @else
            <div class="text-center py-6">
                <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm font-medium text-gray-900 dark:text-white">Caja Cerrada</p>
                @if($caja->fecha_cierre)
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Cerrada el {{ $caja->fecha_cierre->format('d/m/Y H:i') }}</p>
                @endif
            </div>
            @endif
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('caja.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">← Volver al listado</a>
    </div>
</x-app-layout>