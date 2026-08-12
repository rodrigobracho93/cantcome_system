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

            const themeColors = {
                indigo:  { hex: '#6366f1', r: 99, g: 102, b: 241, n1: '#4f46e5', n2: '#6366f1', n3: '#7c3aed', n1d: '#818cf8', n2d: '#a78bfa', n3d: '#c4b5fd' },
                blue:    { hex: '#3b82f6', r: 59, g: 130, b: 246, n1: '#2563eb', n2: '#3b82f6', n3: '#60a5fa', n1d: '#60a5fa', n2d: '#93c5fd', n3d: '#bfdbfe' },
                green:   { hex: '#22c55e', r: 34, g: 197, b: 94,  n1: '#16a34a', n2: '#22c55e', n3: '#4ade80', n1d: '#4ade80', n2d: '#86efac', n3d: '#bbf7d0' },
                red:     { hex: '#ef4444', r: 239, g: 68, b: 68,  n1: '#dc2626', n2: '#ef4444', n3: '#f87171', n1d: '#f87171', n2d: '#fca5a5', n3d: '#fecaca' },
                purple:  { hex: '#a855f7', r: 168, g: 85, b: 247, n1: '#9333ea', n2: '#a855f7', n3: '#c084fc', n1d: '#c084fc', n2d: '#d8b4fe', n3d: '#e9d5ff' },
                orange:  { hex: '#f97316', r: 249, g: 115, b: 22, n1: '#ea580c', n2: '#f97316', n3: '#fb923c', n1d: '#fb923c', n2d: '#fdba74', n3d: '#fed7aa' },
                teal:    { hex: '#14b8a6', r: 20, g: 184, b: 166, n1: '#0d9488', n2: '#14b8a6', n3: '#2dd4bf', n1d: '#2dd4bf', n2d: '#5eead4', n3d: '#99f6e4' },
                pink:    { hex: '#ec4899', r: 236, g: 72, b: 153, n1: '#db2777', n2: '#ec4899', n3: '#f472b6', n1d: '#f472b6', n2d: '#f9a8d4', n3d: '#fbcfe8' },
                neutro:  { hex: '#64748b', r: 100, g: 116, b: 139, n1: '#475569', n2: '#64748b', n3: '#94a3b8', n1d: '#94a3b8', n2d: '#cbd5e1', n3d: '#e2e8f0' },
                celeste: { hex: '#0ea5e9', r: 14, g: 165, b: 233, n1: '#0284c7', n2: '#0ea5e9', n3: '#38bdf8', n1d: '#38bdf8', n2d: '#7dd3fc', n3d: '#bae6fd' },
            };
            const c = themeColors[theme] || themeColors.indigo;
            const isDark = document.documentElement.classList.contains('dark');
            const root = document.documentElement;
            root.style.setProperty('--login-accent', c.hex);
            root.style.setProperty('--login-accent-light', isDark ? `rgba(${c.r},${c.g},${c.b},.18)` : `rgba(${c.r},${c.g},${c.b},.12)`);
            root.style.setProperty('--login-accent-scan', isDark ? `rgba(${c.r},${c.g},${c.b},.14)` : `rgba(${c.r},${c.g},${c.b},.08)`);
            root.style.setProperty('--login-glow-1', isDark
                ? `drop-shadow(0 0 8px rgba(${c.r},${c.g},${c.b},.4))`
                : `drop-shadow(0 0 6px rgba(${c.r},${c.g},${c.b},.25))`);
            root.style.setProperty('--login-glow-2', isDark
                ? `drop-shadow(0 0 22px rgba(${c.r},${c.g},${c.b},.85)) drop-shadow(0 0 44px rgba(${c.r},${c.g},${c.b},.35))`
                : `drop-shadow(0 0 14px rgba(${c.r},${c.g},${c.b},.5)) drop-shadow(0 0 28px rgba(${c.r},${c.g},${c.b},.15))`);
            root.style.setProperty('--login-name-1', isDark ? c.n1d : c.n1);
            root.style.setProperty('--login-name-2', isDark ? c.n2d : c.n2);
            root.style.setProperty('--login-name-3', isDark ? c.n3d : c.n3);
        </script>
        <style>
            .auth-card { animation: fadeIn 0.3s ease-out; }
            @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

            :root {
                --login-accent: #6366f1;
                --login-accent-light: rgba(99,102,241,.12);
                --login-accent-scan: rgba(99,102,241,.08);
                --login-glow-1: drop-shadow(0 0 6px rgba(99,102,241,.25));
                --login-glow-2: drop-shadow(0 0 14px rgba(99,102,241,.5)) drop-shadow(0 0 28px rgba(99,102,241,.15));
                --login-name-1: #4f46e5;
                --login-name-2: #6366f1;
                --login-name-3: #7c3aed;
            }

            @keyframes loginFloat {
                0%, 100% { transform: translateY(0); }
                50%      { transform: translateY(-6px); }
            }
            @keyframes loginGlow {
                0%, 100% { filter: var(--login-glow-1); }
                50%      { filter: var(--login-glow-2); }
            }
            @keyframes loginScan {
                0%   { top: -100%; }
                100% { top: 100%; }
            }
            @keyframes loginGlitch {
                0%   { opacity: 0; transform: translateX(-8px); clip-path: inset(0 100% 0 0); }
                30%  { opacity: 1; clip-path: inset(0 0 0 0); }
                32%  { transform: translateX(4px) skewX(-4deg); }
                34%  { transform: translateX(-2px) skewX(2deg); }
                36%  { transform: translateX(0); }
                100% { opacity: 1; transform: translateX(0); clip-path: inset(0 0 0 0); }
            }
            @keyframes loginSubReveal {
                0%   { opacity: 0; letter-spacing: .6em; }
                100% { opacity: 1; letter-spacing: normal; }
            }
            @keyframes loginDotPulse {
                0%, 100% { opacity: .3; transform: scale(.8); }
                50%      { opacity: 1;  transform: scale(1.2); }
            }
            @keyframes loginFadeIn {
                from { opacity: 0; transform: scale(.85); }
                to   { opacity: 1; transform: scale(1); }
            }
            @keyframes loginRingPulse {
                0%, 100% { box-shadow: 0 0 0 0 var(--login-accent-light); }
                50%      { box-shadow: 0 0 0 10px transparent; }
            }
            @keyframes loginHueShift {
                0%   { filter: hue-rotate(0deg); }
                100% { filter: hue-rotate(360deg); }
            }

            .login-logo-wrap {
                position: relative;
                display: inline-block;
                animation: loginFloat 3s ease-in-out infinite;
            }
            .login-logo-wrap::before {
                content: '';
                position: absolute; inset: -8px;
                border-radius: 50%;
                background: radial-gradient(circle, var(--login-accent-light) 0%, transparent 70%);
                animation: loginGlow 2.5s ease-in-out infinite;
            }
            .login-logo-wrap::after {
                content: '';
                position: absolute; left: 0; width: 100%; height: 40%;
                background: linear-gradient(180deg, transparent, var(--login-accent-scan), transparent);
                animation: loginScan 2.2s linear infinite;
                pointer-events: none;
            }
            .login-logo-ring {
                position: absolute; inset: -12px;
                border-radius: 50%;
                border: 2px solid var(--login-accent);
                opacity: .3;
                animation: loginRingPulse 2.5s ease-in-out infinite;
            }
            .login-logo {
                position: relative; z-index: 1;
                animation: loginFadeIn .8s ease-out both;
            }

            .login-name {
                animation: loginGlitch .8s ease-out .3s both;
                font-family: 'Courier New', monospace;
                background: linear-gradient(135deg, var(--login-name-1), var(--login-name-2), var(--login-name-3));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            .login-sub {
                animation: loginSubReveal .7s ease-out .6s both;
            }
            .login-dots { display: flex; justify-content: center; gap: 6px; margin-top: 10px; }
            .login-dots span {
                width: 5px; height: 5px; border-radius: 50%;
                background: var(--login-accent);
                animation: loginDotPulse 1.4s ease-in-out infinite;
            }
            .login-dots span:nth-child(2) { animation-delay: .2s; }
            .login-dots span:nth-child(3) { animation-delay: .4s; }
        </style>
    </head>
    <body class="font-sans text-gray-900 dark:text-white antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 pt-6 sm:pt-0 bg-gradient-to-br from-gray-50 to-indigo-50 dark:from-gray-900 dark:to-indigo-950">
            <div class="w-full sm:max-w-md auth-card">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg shadow-indigo-100/50 dark:shadow-none border border-gray-100 dark:border-gray-700 px-6 py-8 sm:px-8">
                    {{ $slot }}
                </div>
            </div>
            <p class="mt-6 text-xs text-gray-400 dark:text-gray-500">{{ $systemName ?? 'CantCome' }} &copy; {{ date('Y') }} &mdash; Todos los derechos reservados</p>
        </div>
    </body>
</html>
