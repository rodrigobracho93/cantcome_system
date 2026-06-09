<x-app-layout>
    <x-slot name="header">Ventas por Cliente</x-slot>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 text-sm">{{ $errors->first() }}</div>
    @endif

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Cliente</label>
                <select name="customer_id" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todos los clientes</option>
                    @foreach($customers as $c)
                    <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tipo de pago</label>
                <select name="payment_type" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todos</option>
                    <option value="contado" {{ request('payment_type') === 'contado' ? 'selected' : '' }}>Contado</option>
                    <option value="credito" {{ request('payment_type') === 'credito' ? 'selected' : '' }}>Crédito</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Desde</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Hasta</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">Filtrar</button>
                <a href="{{ route('customer-sales.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">Limpiar</a>
            </div>
        </form>
    </div>

    @if($customer)
    {{-- Customer header --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $customer->name }}</h2>
                    @if($customer->document || $customer->phone)
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        @if($customer->document) Cédula/RUC: {{ $customer->document }} @endif
                        @if($customer->phone) · Tel: {{ $customer->phone }} @endif
                    </p>
                    @endif
                </div>
            </div>
            <div class="flex flex-wrap gap-3 text-sm">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl px-4 py-2.5 text-center min-w-[100px]">
                    <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total</p>
                    <p class="text-base font-bold text-gray-900 dark:text-white">₲ {{ number_format($totalGeneral, 0, ',', '.') }}</p>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl px-4 py-2.5 text-center min-w-[100px]">
                    <p class="text-[10px] font-medium text-emerald-500 dark:text-emerald-400 uppercase tracking-wider">Cobrado</p>
                    <p class="text-base font-bold text-emerald-600 dark:text-emerald-400">₲ {{ number_format($totalCobrado, 0, ',', '.') }}</p>
                </div>
                <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl px-4 py-2.5 text-center min-w-[100px]">
                    <p class="text-[10px] font-medium text-amber-500 dark:text-amber-400 uppercase tracking-wider">Pendiente</p>
                    <p class="text-base font-bold text-amber-600 dark:text-amber-400">₲ {{ number_format($totalPendiente, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Desktop: table --}}
    <div class="hidden md:block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vendedor</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Productos</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pago</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-20">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $sale->payment_type === 'credito' && !$sale->paid_at ? 'bg-amber-50/30 dark:bg-amber-900/10' : '' }}">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">#{{ $sale->id }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $sale->user->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 max-w-[200px]">
                            <div class="truncate">
                                @foreach($sale->items as $i => $item)
                                    {{ $item->product->name }}<span class="text-gray-400"> ({{ $item->quantity }})</span>{{ !$loop->last ? ',' : '' }}
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $sale->payment_type === 'contado' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300' }}">
                                {{ ucfirst($sale->payment_type) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-semibold">₲ {{ number_format($sale->total, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            @if($sale->paid_at)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Cobrado
                            </span>
                            @elseif($sale->payment_type === 'contado')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Pagado
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Pendiente
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('sales.show', $sale) }}" class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Ver detalle">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @if($sale->payment_type === 'credito' && !$sale->paid_at)
                                <form action="{{ route('customer-sales.pay', $sale) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Marcar como cobrado">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </button>
                                </form>
                                @elseif($sale->payment_type === 'credito' && $sale->paid_at)
                                <form action="{{ route('customer-sales.unpay', $sale) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors" title="Revertir cobro">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <p class="text-sm text-gray-400 dark:text-gray-500">No se encontraron ventas para este cliente</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($totalGeneral !== null)
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-end gap-6 text-sm font-semibold">
                <span class="text-gray-500 dark:text-gray-400">Total: <span class="text-gray-900 dark:text-white">₲ {{ number_format($totalGeneral, 0, ',', '.') }}</span></span>
                <span class="text-emerald-600 dark:text-emerald-400">Cobrado: ₲ {{ number_format($totalCobrado, 0, ',', '.') }}</span>
                <span class="text-amber-600 dark:text-amber-400">Pendiente: ₲ {{ number_format($totalPendiente, 0, ',', '.') }}</span>
            </div>
        </div>
        @endif
    </div>

    {{-- Mobile: cards --}}
    <div class="block md:hidden space-y-3">
        @forelse($sales as $sale)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 {{ $sale->payment_type === 'credito' && !$sale->paid_at ? 'border-l-4 border-l-amber-400' : '' }}">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-gray-400">#{{ $sale->id }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium {{ $sale->payment_type === 'contado' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300' }}">
                        {{ ucfirst($sale->payment_type) }}
                    </span>
                </div>
                @if($sale->paid_at)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">
                    Cobrado
                </span>
                @elseif($sale->payment_type === 'credito')
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300">
                    Pendiente
                </span>
                @else
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                    Pagado
                </span>
                @endif
            </div>
            <div class="grid grid-cols-2 gap-1.5 text-xs mb-3">
                <div><span class="text-gray-400 dark:text-gray-500">Fecha:</span> <span class="text-gray-700 dark:text-gray-300">{{ $sale->created_at->format('d/m/Y H:i') }}</span></div>
                <div><span class="text-gray-400 dark:text-gray-500">Vendedor:</span> <span class="text-gray-700 dark:text-gray-300">{{ $sale->user->name }}</span></div>
            </div>
            <div class="text-xs text-gray-600 dark:text-gray-400 mb-3">
                @foreach($sale->items as $item)
                <span>{{ $item->product->name }} <span class="text-gray-400">({{ $item->quantity }})</span>{{ !$loop->last ? ',' : '' }}</span>
                @endforeach
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                <p class="text-base font-bold text-gray-900 dark:text-white">₲ {{ number_format($sale->total, 0, ',', '.') }}</p>
                <div class="flex items-center gap-1">
                    <a href="{{ route('sales.show', $sale) }}" class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Ver detalle">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                    @if($sale->payment_type === 'credito' && !$sale->paid_at)
                    <form action="{{ route('customer-sales.pay', $sale) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Marcar como cobrado">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </button>
                    </form>
                    @elseif($sale->payment_type === 'credito' && $sale->paid_at)
                    <form action="{{ route('customer-sales.unpay', $sale) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors" title="Revertir cobro">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-sm text-gray-400 dark:text-gray-500">No se encontraron ventas</p>
        </div>
        @endforelse
    </div>

    @endif

    @if(!$customer)
    {{-- No customer selected --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Seleccioná un cliente</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Usá los filtros de arriba para ver las ventas de un cliente específico y administrar sus cobros.</p>
    </div>
    @endif

    @if($customer && !($sales instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator))
    <div class="mt-6 text-center">
        <a href="{{ route('customer-sales.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">← Volver al listado general</a>
    </div>
    @elseif(!$customer)
    <div class="mt-6">{{ $sales->links() }}</div>
    @endif
</x-app-layout>
