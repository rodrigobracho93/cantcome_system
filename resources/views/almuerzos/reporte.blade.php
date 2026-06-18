<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <span>Reporte Mensual de Almuerzos</span>
            <div class="flex items-center gap-2">
                <a x-data="{ theme: localStorage.getItem('theme') || 'indigo' }"
                    :href="`{{ route('almuerzos.reporte-pdf', ['mes' => $mes, 'anio' => $anio]) }}&theme=${theme}`"
                    target="_blank"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-xs font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    PDF
                </a>
                <a href="{{ route('almuerzos.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors text-xs font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Month picker --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
        <form method="GET" class="flex items-center gap-3">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Mes:</label>
            <select name="mes" onchange="this.form.submit()" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                @foreach(range(1, 12) as $m)
                <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->locale('es')->monthName }}</option>
                @endforeach
            </select>
            <select name="anio" onchange="this.form.submit()" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                @foreach(range(now()->year - 2, now()->year + 1) as $y)
                <option value="{{ $y }}" {{ $anio == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- Total card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-6 text-center">
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de platos entregados</p>
        <p class="text-4xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">{{ $totalPlatos }}</p>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
            {{ \Carbon\Carbon::create()->month($mes)->locale('es')->monthName }} {{ $anio }}
        </p>
    </div>

    {{-- Por día --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Platos por Día</h3>
        </div>
        @if($porDia->isEmpty())
        <div class="p-8 text-center text-sm text-gray-400 dark:text-gray-500">Sin entregas este mes</div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Platos</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($porDia as $dia)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-2.5 text-sm text-gray-900 dark:text-white">{{ $dia['fecha']->locale('es')->isoFormat('dddd D [de] MMM') }}</td>
                        <td class="px-4 py-2.5 text-sm text-gray-900 dark:text-white text-right font-semibold">{{ $dia['cantidad'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Por cliente --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Platos por Cliente</h3>
        </div>
        @if($porCliente->isEmpty())
        <div class="p-8 text-center text-sm text-gray-400 dark:text-gray-500">Sin entregas este mes</div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Platos</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($porCliente as $cliente)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-2.5 text-sm text-gray-900 dark:text-white">{{ $cliente['customer']->name }}</td>
                        <td class="px-4 py-2.5 text-sm text-gray-900 dark:text-white text-right font-semibold">{{ $cliente['cantidad'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</x-app-layout>
