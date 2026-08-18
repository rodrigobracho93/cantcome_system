<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($systemName ?? config('app.name', 'CantCome')); ?></title>
    <meta name="theme-color" content="#4f46e5">
    <meta name="description" content="Sistema de gestión de ventas y stock para cantinas">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="<?php echo e($systemName ?? 'CantCome'); ?>">
    <link rel="manifest" href="<?php echo e(asset('manifest.json')); ?>">
    <link rel="icon" type="image/png" href="<?php echo e(asset($systemLogo ?? 'logo.png')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset($systemLogo ?? 'logo.png')); ?>">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
    <script>
        if (localStorage.getItem('darkMode') === 'true') document.documentElement.classList.add('dark');
        const theme = localStorage.getItem('theme') || 'indigo';
        document.documentElement.setAttribute('data-theme', theme);
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-collapsed .sidebar-link { flex-direction: column; gap: 0.125rem; padding: 0.625rem 0; text-align: center; font-size: 10px; border-left-width: 0; align-items: center; }
        .sidebar-collapsed .sidebar-link .sidebar-text { display: block; line-height: 1.1; }
        .sidebar-collapsed .sidebar-section { display: none; }
        .sidebar-collapsed .sidebar-link svg { width: 20px; height: 20px; }
    </style>
</head>
<body class="font-sans antialiased" x-data="{ sidebarOpen: false }">

    
    <div x-show="sidebarOpen" x-cloak
        class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden"
        @click="sidebarOpen = false">
    </div>

    
    <header class="fixed top-0 left-0 right-0 z-50 h-12 flex items-center justify-between px-4 border-b border-indigo-800/40" style="background: linear-gradient(to right, var(--color-primary-950), var(--color-primary-950) 50%, var(--color-primary-900));">
        <div class="flex items-center gap-2.5">
            <button @click="sidebarOpen = true" class="text-indigo-300 lg:hidden hover:text-white mr-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <img src="<?php echo e(asset($systemLogo ?? 'logo.png')); ?>" alt="<?php echo e($systemName ?? 'CantCome'); ?>" class="h-8 w-auto">
            <span class="text-base font-bold text-white tracking-tight"><?php echo e($systemName ?? 'CantCome'); ?></span>
        </div>

        <div class="flex items-center gap-1">
            
            <a href="<?php echo e(route('profile.edit')); ?>" class="flex items-center gap-2 pr-2 rounded-lg hover:bg-indigo-900/40 transition-colors">
                <div class="w-7 h-7 rounded-full shrink-0 ring-2 ring-indigo-500/40 overflow-hidden">
                    <?php if(Auth::user()->profile_photo_url): ?>
                        <img src="<?php echo e(Auth::user()->profile_photo_url); ?>" alt="<?php echo e(Auth::user()->name); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full bg-indigo-600 text-white flex items-center justify-center text-xs font-bold">
                            <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                        </div>
                    <?php endif; ?>
                </div>
                <div class="text-left hidden sm:block">
                    <p class="text-xs font-semibold text-white leading-tight"><?php echo e(Auth::user()->name); ?></p>
                    <?php $activeRole = session('active_role', Auth::user()->role); ?>
                    <p class="text-[10px] font-medium text-indigo-200 leading-tight"><?php echo e($activeRole === 'superadmin' ? '👑 Superadmin' : ($activeRole === 'admin' ? '🛡️ Administrador' : '🍽️ Cantina')); ?></p>
                </div>
            </a>

            <?php if(Auth::user()->isAdmin()): ?>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-1 px-1.5 sm:px-2 py-1 text-[11px] font-medium text-indigo-300 rounded hover:bg-indigo-800/40 transition-colors" title="Cambiar rol">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span class="hidden sm:inline"><?php echo e($activeRole === 'superadmin' ? '👑 Super' : ($activeRole === 'admin' ? '🛡️ Admin' : '🍽️ Cantina')); ?></span>
                        <svg class="w-3 h-3 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-cloak @click.outside="open = false" class="absolute right-0 mt-1 w-44 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                        <?php if(Auth::user()->isSuperAdmin()): ?>
                        <form method="POST" action="<?php echo e(route('switch.role', 'superadmin')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm <?php echo e($activeRole === 'superadmin' ? 'text-indigo-600 font-semibold' : 'text-gray-700 dark:text-gray-200'); ?> hover:bg-gray-100 dark:hover:bg-gray-700">👑 Superadmin</button>
                        </form>
                        <?php endif; ?>
                        <form method="POST" action="<?php echo e(route('switch.role', 'admin')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm <?php echo e($activeRole === 'admin' ? 'text-indigo-600 font-semibold' : 'text-gray-700 dark:text-gray-200'); ?> hover:bg-gray-100 dark:hover:bg-gray-700">🛡️ Administrador</button>
                        </form>
                        <form method="POST" action="<?php echo e(route('switch.role', 'cantina')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm <?php echo e($activeRole === 'cantina' ? 'text-indigo-600 font-semibold' : 'text-gray-700 dark:text-gray-200'); ?> hover:bg-gray-100 dark:hover:bg-gray-700">🍽️ Cantina</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            
            <div class="relative" x-data="{ open: false, current: localStorage.getItem('theme') || 'indigo', colors: { indigo: '#6366f1', blue: '#3b82f6', green: '#22c55e', red: '#ef4444', purple: '#a855f7', orange: '#f97316', teal: '#14b8a6', pink: '#ec4899', neutro: '#64748b', celeste: '#0ea5e9' } }">
                <button @click="open = !open" class="p-1.5 text-indigo-300 rounded-lg hover:text-white hover:bg-indigo-800/40 transition-colors" title="Tema de color">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.098 19.902a3.75 3.75 0 005.304 0l6.401-6.402M6.75 21A3.75 3.75 0 013 17.25V4.125C3 3.504 3.504 3 4.125 3h5.25c.621 0 1.125.504 1.125 1.125v4.072M6.75 21a3.75 3.75 0 003.75-3.75V8.197M6.75 21h13.125c.621 0 1.125-.504 1.125-1.125v-5.25c0-.621-.504-1.125-1.125-1.125h-4.072"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak @click.outside="open = false"
                    class="absolute right-0 mt-1 p-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50 grid grid-cols-5 gap-1.5 w-[11.5rem]">
                    <template x-for="t in ['indigo','blue','green','red','purple','orange','teal','pink','neutro','celeste']" :key="t">
                        <button @click="current = t; localStorage.setItem('theme', t); document.documentElement.setAttribute('data-theme', t); open = false"
                            class="w-7 h-7 rounded-full border-2 transition-all hover:scale-110"
                            :class="current === t ? 'border-white ring-2 ring-offset-1 ring-offset-white dark:ring-offset-gray-800' : 'border-transparent'"
                            :style="'background-color: ' + colors[t]"
                            :title="t.charAt(0).toUpperCase() + t.slice(1)">
                        </button>
                    </template>
                </div>
            </div>

            
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

            
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="p-1.5 text-indigo-300 rounded-lg hover:text-red-300 hover:bg-indigo-800/40 transition-colors" title="Cerrar sesión">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </header>

    
    <div class="flex min-h-screen pt-12" x-cloak>

        
        <aside x-data="{ collapsed: localStorage.getItem('sidebarCollapsed') === 'true' }"
            x-init="$watch('collapsed', val => localStorage.setItem('sidebarCollapsed', val))"
            class="fixed inset-y-0 left-0 z-40 flex flex-col pt-12 transform transition-all duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-auto overflow-hidden"
            :class="[sidebarOpen ? 'translate-x-0' : '-translate-x-full', collapsed ? 'w-16 sidebar-collapsed' : 'w-64']">
            <div class="absolute inset-0" style="background: linear-gradient(to bottom, var(--color-primary-950), var(--color-primary-900) 60%, var(--color-primary-800));"></div>

            <div class="relative z-10 flex flex-col flex-1 overflow-hidden">
                
                <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                
                <div class="border-t border-indigo-800/40 p-2 shrink-0">
                    <button @click="collapsed = !collapsed"
                        class="w-full flex items-center justify-center gap-2 p-1.5 text-indigo-300 rounded-lg hover:text-red-300 hover:bg-indigo-800/40 transition-colors text-xs font-medium"
                        :class="collapsed ? 'flex-col' : ''">
                        <svg x-show="!collapsed" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                        </svg>
                        <svg x-show="collapsed" x-cloak class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                        </svg>
                        <span x-show="!collapsed" x-cloak class="text-[10px] whitespace-nowrap">Ocultar menú</span>
                    </button>
                </div>
            </div>
        </aside>

        
        <div class="relative flex flex-col flex-1 min-h-screen bg-gradient-to-br from-gray-50 to-indigo-50 dark:from-gray-900 dark:to-indigo-950">

            
            <?php if(isset($header)): ?>
            <div class="page-header px-4 sm:px-8 lg:px-10 pt-4 sm:pt-6 lg:pt-8 pb-0">
                <h1 class="page-header-title text-lg sm:text-xl font-bold"><?php echo e($header); ?></h1>
            </div>
            <?php endif; ?>

            
            <main class="flex-1 p-4 sm:p-8 lg:p-10">
                <?php echo e($slot); ?>

            </main>
        </div>
    </div>

    <?php if (isset($component)) { $__componentOriginal40c17993d0c21c560a83b65d062854a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal40c17993d0c21c560a83b65d062854a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pwa-install','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pwa-install'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal40c17993d0c21c560a83b65d062854a8)): ?>
<?php $attributes = $__attributesOriginal40c17993d0c21c560a83b65d062854a8; ?>
<?php unset($__attributesOriginal40c17993d0c21c560a83b65d062854a8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal40c17993d0c21c560a83b65d062854a8)): ?>
<?php $component = $__componentOriginal40c17993d0c21c560a83b65d062854a8; ?>
<?php unset($__componentOriginal40c17993d0c21c560a83b65d062854a8); ?>
<?php endif; ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            });
        }
    </script>
</body>
</html>
<?php /**PATH C:\xampp\Proyectos\cantcome_system\resources\views/layouts/app.blade.php ENDPATH**/ ?>