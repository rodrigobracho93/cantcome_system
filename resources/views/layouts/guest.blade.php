<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'CantCome') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .auth-card { animation: fadeIn 0.3s ease-out; }
            @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 pt-6 sm:pt-0 bg-gradient-to-br from-gray-50 to-indigo-50">
            <div class="w-full sm:max-w-md auth-card">
                <div class="bg-white rounded-2xl shadow-lg shadow-indigo-100/50 border border-gray-100 px-6 py-8 sm:px-8">
                    {{ $slot }}
                </div>
            </div>
            <p class="mt-6 text-xs text-gray-400">CantCome &mdash; Sistema de Gestión de Cantina</p>
        </div>
    </body>
</html>
