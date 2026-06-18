<x-app-layout>
    <x-slot name="header">Nuevo Cliente para Almuerzo</x-slot>

    <div class="max-w-lg">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <form method="POST" action="{{ route('almuerzos.store-cliente') }}">
                @csrf
                <input type="hidden" name="fecha" value="{{ request('fecha', today()->format('Y-m-d')) }}">
                <div class="space-y-4">
                    <div>
                        <x-input-label value="Nombre *" />
                        <input type="text" name="name" required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <x-input-label value="Teléfono (opcional)" />
                        <input type="text" name="phone"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                        Guardar y volver
                    </button>
                    <a href="{{ route('almuerzos.index', ['fecha' => request('fecha', today()->format('Y-m-d'))]) }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
