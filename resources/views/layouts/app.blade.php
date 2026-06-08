<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CantCome') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <script>
        if (localStorage.getItem('darkMode') === 'true') document.documentElement.classList.add('dark');
    </script>
    <style>
        .sidebar-collapsed .sidebar-link { flex-direction: column; gap: 0.125rem; padding: 0.625rem 0; text-align: center; font-size: 10px; border-left-width: 0; align-items: center; }
        .sidebar-collapsed .sidebar-link .sidebar-text { display: block; line-height: 1.1; }
        .sidebar-collapsed .sidebar-section { display: none; }
        .sidebar-collapsed .sidebar-link svg { width: 20px; height: 20px; }
    </style>
</head>
<body class="font-sans antialiased" x-data="{ sidebarOpen: false }">

    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen" x-cloak
        class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden"
        @click="sidebarOpen = false">
    </div>

    {{-- Top Bar: full width, 2cm height (h-12) --}}
    <header class="fixed top-0 left-0 right-0 z-50 h-12 bg-indigo-950 border-b border-indigo-800/40 flex items-center justify-between px-4">
        <div class="flex items-center gap-2.5">
            <button @click="sidebarOpen = true" class="text-indigo-300 lg:hidden hover:text-white mr-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="flex items-center justify-center w-7 h-7 bg-indigo-500 rounded-lg">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <span class="text-base font-bold text-white tracking-tight">CantCome</span>
        </div>

        <div class="flex items-center gap-1">
            {{-- Avatar --}}
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 pr-2 rounded-lg hover:bg-indigo-900/40 transition-colors">
                <div class="w-7 h-7 rounded-full shrink-0 ring-2 ring-indigo-500/40 overflow-hidden">
                    @if (Auth::user()->profile_photo_url)
                        <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-indigo-600 text-white flex items-center justify-center text-xs font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="text-left hidden sm:block">
                    <p class="text-xs font-semibold text-white leading-tight">{{ Auth::user()->name }}</p>
                    @php $activeRole = session('active_role', Auth::user()->role); @endphp
                    <p class="text-[10px] font-medium text-indigo-300/70 leading-tight">{{ $activeRole === 'admin' ? 'Administrador' : 'Cantina' }}</p>
                </div>
            </a>

            @if(Auth::user()->isAdmin())
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-1 px-2 py-1 text-[11px] font-medium text-indigo-300 rounded hover:bg-indigo-800/40 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span>{{ $activeRole === 'admin' ? 'Admin' : 'Cantina' }}</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-cloak @click.outside="open = false" class="absolute right-0 mt-1 w-40 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                        <form method="POST" action="{{ route('switch.role', 'admin') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm {{ $activeRole === 'admin' ? 'text-indigo-600 font-semibold' : 'text-gray-700 dark:text-gray-200' }} hover:bg-gray-100 dark:hover:bg-gray-700">Administrador</button>
                        </form>
                        <form method="POST" action="{{ route('switch.role', 'cantina') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm {{ $activeRole === 'cantina' ? 'text-indigo-600 font-semibold' : 'text-gray-700 dark:text-gray-200' }} hover:bg-gray-100 dark:hover:bg-gray-700">Cantina</button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Dark mode --}}
            <div x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }">
                <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode); document.documentElement.classList.toggle('dark')" class="p-1.5 text-indigo-300 rounded-lg hover:text-white hover:bg-indigo-800/40 transition-colors" title="Modo oscuro">
                    <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="darkMode" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-1.5 text-indigo-300 rounded-lg hover:text-red-300 hover:bg-indigo-800/40 transition-colors" title="Cerrar sesión">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </header>

    {{-- Sidebar + Main wrapper --}}
    <div class="flex min-h-screen pt-12">

        {{-- Sidebar --}}
        <aside x-data="{ collapsed: localStorage.getItem('sidebarCollapsed') === 'true' }"
            x-init="$watch('collapsed', val => localStorage.setItem('sidebarCollapsed', val))"
            class="fixed inset-y-0 left-0 z-40 bg-indigo-950 flex flex-col pt-12 transform transition-all duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-auto overflow-hidden"
            :class="[sidebarOpen ? 'translate-x-0' : '-translate-x-full', collapsed ? 'w-16 sidebar-collapsed' : 'w-64']">

            {{-- Navigation --}}
            @include('layouts.navigation')

            {{-- Collapse toggle --}}
            <div class="border-t border-indigo-800/40 p-2 shrink-0">
                <button @click="collapsed = !collapsed" class="w-full flex items-center justify-center gap-2 py-2 text-indigo-300/70 hover:text-white hover:bg-indigo-800/30 rounded-lg transition-colors text-xs font-medium"
                    :class="collapsed ? 'flex-col' : ''">
                    <svg class="w-4 h-4 shrink-0" :class="collapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                    </svg>
                    <span x-show="!collapsed" x-cloak class="text-[10px] whitespace-nowrap">Ocultar menú</span>
                </button>
            </div>
        </aside>

        {{-- Main content --}}
        <div class="flex flex-col flex-1 min-h-screen bg-gray-50 dark:bg-gray-900">

            {{-- Content header --}}
            <header class="sticky top-12 z-30 flex items-center h-12 px-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
                <button @click="sidebarOpen = true" class="text-gray-400 lg:hidden hover:text-gray-600 mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                    @isset($header)
                        {{ $header }}
                    @endisset
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 p-6 sm:p-8 lg:p-10">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
