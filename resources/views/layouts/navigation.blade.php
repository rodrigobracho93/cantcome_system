<nav class="flex-1 px-2 pb-4 space-y-1 overflow-y-auto">

    <p class="sidebar-section px-3 mb-2 text-[11px] font-bold text-indigo-400 uppercase tracking-[0.12em]">Principal</p>

    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" sidebar>
        <x-slot name="icon">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </x-slot>
        Dashboard
    </x-nav-link>

    <p class="sidebar-section px-3 mt-6 mb-2 text-[11px] font-bold text-indigo-400 uppercase tracking-[0.12em]">Ventas</p>

    <x-nav-link :href="route('sales.create')" :active="request()->routeIs('sales.create')" sidebar>
        <x-slot name="icon">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
        </x-slot>
        Nueva Venta
    </x-nav-link>

    <x-nav-link :href="route('sales.index')" :active="request()->routeIs('sales.index')" sidebar>
        <x-slot name="icon">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
        </x-slot>
        Historial Ventas
    </x-nav-link>

    <x-nav-link :href="route('customer-sales.index')" :active="request()->routeIs('customer-sales.*')" sidebar>
        <x-slot name="icon">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </x-slot>
        Cuentas por Cobrar
    </x-nav-link>

    @php $activeRole = session('active_role', Auth::user()->role); @endphp

    <p class="sidebar-section px-3 mt-6 mb-2 text-[11px] font-bold text-indigo-400 uppercase tracking-[0.12em]">Caja</p>

    <x-nav-link :href="route('caja.index')" :active="request()->routeIs('caja.*')" sidebar>
        <x-slot name="icon">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
        </x-slot>
        Movimiento Caja
    </x-nav-link>

    <x-nav-link :href="route('caja.libro-diario')" :active="request()->routeIs('caja.libro-diario')" sidebar>
        <x-slot name="icon">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </x-slot>
        Libro Diario
    </x-nav-link>

    <p class="sidebar-section px-3 mt-6 mb-2 text-[11px] font-bold text-indigo-400 uppercase tracking-[0.12em]">Productos</p>

    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" sidebar>
        <x-slot name="icon">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </x-slot>
        Productos
    </x-nav-link>

    <x-nav-link :href="route('categories')" :active="request()->routeIs('categories*')" sidebar>
        <x-slot name="icon">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
        </x-slot>
        Categorías
    </x-nav-link>

    @if(in_array($activeRole, ['admin', 'superadmin']))
        <p class="sidebar-section px-3 mt-6 mb-2 text-[11px] font-bold text-indigo-400 uppercase tracking-[0.12em]">Gestión</p>

        <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')" sidebar>
            <x-slot name="icon">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </x-slot>
            Clientes
        </x-nav-link>

        <p class="sidebar-section px-3 mt-6 mb-2 text-[11px] font-bold text-indigo-400 uppercase tracking-[0.12em]">Reportes</p>

        <x-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')" sidebar>
            <x-slot name="icon">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </x-slot>
            Reportes
        </x-nav-link>

        <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users*')" sidebar>
            <x-slot name="icon">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </x-slot>
            Usuarios
        </x-nav-link>
    @endif
</nav>
