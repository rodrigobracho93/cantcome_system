<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $systemName ?? config('app.name', 'CantCome') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/png" href="{{ asset($systemLogo ?? 'logo.png') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            if (localStorage.getItem('darkMode') === 'true') document.documentElement.classList.add('dark');
            const theme = localStorage.getItem('theme') || 'indigo';
            document.documentElement.setAttribute('data-theme', theme);
        </script>
        <style>
            .auth-card { animation: fadeIn 0.3s ease-out; }
            @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        </style>
    </head>
    <body class="font-sans text-gray-900 dark:text-white antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 pt-6 sm:pt-0 bg-gradient-to-br from-gray-50 to-indigo-50 dark:from-gray-900 dark:to-indigo-950">
            <div class="w-full sm:max-w-md auth-card">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg shadow-indigo-100/50 dark:shadow-none border border-gray-100 dark:border-gray-700 px-6 py-8 sm:px-8">
                    {{ $slot }}
                </div>
            </div>
            <p class="mt-6 text-xs text-gray-400 dark:text-gray-500">{{ $systemName ?? 'CantCome' }} - Sistema de Gestión de Cantina / Comedor</p>
        </div>
    </body>
</html>
