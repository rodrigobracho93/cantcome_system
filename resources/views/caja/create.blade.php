<x-app-layout>
    <x-slot name="header">Abrir Caja</x-slot>

    <div class="max-w-lg mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Nueva Apertura de Caja</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Ingresa el monto inicial para abrir la caja</p>
                </div>
            </div>

            <form action="{{ route('caja.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <x-input-label value="Monto Inicial (₲)" />
                    <x-text-input name="monto_inicial" type="number" step="0.01" min="0" class="mt-1 block w-full" placeholder="0.00" required />
                </div>
                <div class="mb-6">
                    <x-input-label value="Observaciones (opcional)" />
                    <textarea name="observaciones" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Notas adicionales..."></textarea>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('caja.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">Cancelar</a>
                    <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors">Abrir Caja</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>