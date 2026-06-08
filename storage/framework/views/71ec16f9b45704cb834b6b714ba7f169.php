<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        Dashboard
     <?php $__env->endSlot(); ?>

    
    <div class="relative overflow-hidden mb-8 rounded-2xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-indigo-900 p-6 sm:p-8">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-1/3 w-48 h-48 bg-indigo-500/20 rounded-full translate-y-1/2"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <h2 class="text-xl sm:text-2xl font-bold text-white">¡Hola, <?php echo e(Auth::user()->name); ?>!</h2>
                    <?php $activeRole = session('active_role', Auth::user()->role); ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold <?php echo e($activeRole === 'admin' ? 'bg-amber-400/20 text-amber-300' : 'bg-emerald-400/20 text-emerald-300'); ?>">
                        <?php echo e($activeRole === 'admin' ? 'Administrador' : 'Cantina'); ?>

                    </span>
                </div>
                <p class="text-sm text-indigo-200"><?php echo e(now()->locale('es')->isoFormat('dddd, D [de] MMMM [del] YYYY')); ?></p>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <p class="text-[10px] font-medium text-indigo-300 uppercase tracking-wider">Inicio de sesión</p>
                    <p class="text-xs font-semibold text-white"><?php echo e(now()->format('H:i')); ?></p>
                </div>
                <div class="w-10 h-10 rounded-full ring-2 ring-white/30 overflow-hidden shrink-0">
                    <?php if(Auth::user()->profile_photo_url): ?>
                        <img src="<?php echo e(Auth::user()->profile_photo_url); ?>" alt="<?php echo e(Auth::user()->name); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full bg-indigo-500 text-white flex items-center justify-center text-sm font-bold">
                            <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-700 transition-all duration-200">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center justify-center w-11 h-11 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <?php if($data['salesTrend'] != 0): ?>
                <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-md text-[10px] font-semibold <?php echo e($data['salesTrend'] > 0 ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400'); ?>">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($data['salesTrend'] > 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3'); ?>"/>
                    </svg>
                    <?php echo e(abs($data['salesTrend'])); ?>%
                </span>
                <?php endif; ?>
            </div>
            <p class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-0.5">Ventas Hoy</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($data['salesToday']); ?></p>
            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">vs. ayer</p>
        </div>

        
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md hover:border-emerald-200 dark:hover:border-emerald-700 transition-all duration-200">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center justify-center w-11 h-11 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <?php if($data['revenueTrend'] != 0): ?>
                <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-md text-[10px] font-semibold <?php echo e($data['revenueTrend'] > 0 ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400'); ?>">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($data['revenueTrend'] > 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3'); ?>"/>
                    </svg>
                    <?php echo e(abs($data['revenueTrend'])); ?>%
                </span>
                <?php endif; ?>
            </div>
            <p class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-0.5">Ingresos Hoy</p>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">₲ <?php echo e(number_format($data['revenueToday'], 0, ',', '.')); ?></p>
            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">vs. ayer</p>
        </div>

        
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md hover:border-violet-200 dark:hover:border-violet-700 transition-all duration-200">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center justify-center w-11 h-11 rounded-2xl bg-gradient-to-br from-violet-500 to-violet-600 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
            <p class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-0.5">Productos Activos</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($data['totalProducts']); ?></p>
            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">En catálogo</p>
        </div>

        
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md hover:border-amber-200 dark:hover:border-amber-700 transition-all duration-200">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center justify-center w-11 h-11 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <?php if($data['pendingSales'] > 0): ?>
                <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-md text-[10px] font-semibold bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/>
                    </svg>
                    Pendiente<?php echo e($data['pendingSales'] !== 1 ? 's' : ''); ?>

                </span>
                <?php endif; ?>
            </div>
            <p class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-0.5">Ventas Pendientes</p>
            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400"><?php echo e($data['pendingSales']); ?></p>
            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">Requieren atención</p>
        </div>
    </div>

    
    <?php if($data['lowStockProducts']->isNotEmpty()): ?>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8">
        <div class="flex items-center gap-3 mb-5">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-red-600 shadow-sm">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Alertas de Stock</h3>
                <p class="text-xs text-gray-400 dark:text-gray-500"><?php echo e($data['lowStockProducts']->count()); ?> producto<?php echo e($data['lowStockProducts']->count() !== 1 ? 's' : ''); ?> con stock bajo</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <?php $__currentLoopData = $data['lowStockProducts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="relative bg-red-50 dark:bg-red-900/10 rounded-xl p-4 border border-red-100 dark:border-red-900/30">
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 truncate"><?php echo e($product->name); ?></p>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase">Stock</p>
                        <p class="text-lg font-bold <?php echo e($product->stock == 0 ? 'text-red-600 dark:text-red-400' : 'text-amber-600 dark:text-amber-400'); ?>"><?php echo e($product->stock); ?></p>
                    </div>
                    <div class="w-16">
                        <div class="bg-red-200 dark:bg-red-900/40 rounded-full h-1.5 overflow-hidden">
                            <div class="h-full rounded-full <?php echo e($product->stock == 0 ? 'bg-red-500' : 'bg-amber-500'); ?>" style="width: <?php echo e(min(($product->stock / 5) * 100, 100)); ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if(Auth::user()->isAdmin() && isset($data['weeklySales'])): ?>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Ventas Semanales</h3>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500">Últimos 7 días</p>
                    </div>
                </div>
                <span class="text-xs text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 px-2 py-1 rounded-lg">
                    <?php echo e($data['weeklySales']->sum('total') > 0 ? '₲ '.number_format($data['weeklySales']->sum('total'), 0, ',', '.') : 'Sin datos'); ?>

                </span>
            </div>
            <div class="relative" style="height: 220px;">
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>

        
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Productos Más Vendidos</h3>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500">Top 5</p>
                    </div>
                </div>
            </div>
            <?php $maxQty = $data['topProducts']->max('total_qty') ?: 1; ?>
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $data['topProducts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="relative">
                    <div class="flex items-center justify-between mb-1.5">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="shrink-0 w-6 h-6 flex items-center justify-center rounded-lg text-xs font-bold <?php echo e($i === 0 ? 'bg-amber-400 text-white' : ($i === 1 ? 'bg-gray-300 text-gray-600 dark:bg-gray-600 dark:text-gray-300' : ($i === 2 ? 'bg-amber-700 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500'))); ?>"><?php echo e($i + 1); ?></span>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate"><?php echo e($product->name); ?></span>
                        </div>
                        <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 shrink-0 ml-3"><?php echo e($product->total_qty); ?> vendidos</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500 <?php echo e($i === 0 ? 'bg-gradient-to-r from-amber-400 to-amber-500' : ($i === 1 ? 'bg-gradient-to-r from-gray-400 to-gray-500' : ($i === 2 ? 'bg-gradient-to-r from-amber-700 to-amber-800' : 'bg-indigo-400'))); ?>" style="width: <?php echo e(($product->total_qty / $maxQty) * 100); ?>%"></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-sm text-gray-400 dark:text-gray-500 py-8 text-center">Sin datos de ventas aún</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if(Auth::user()->isAdmin() && isset($data['salesByPayment']) && $data['salesByPayment']->isNotEmpty()): ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Métodos de Pago</h3>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Últimos 30 días</p>
                </div>
            </div>
            <div class="space-y-3">
                <?php
                    $paymentLabels = ['efectivo' => 'Efectivo', 'tarjeta' => 'Tarjeta', 'transferencia' => 'Transferencia'];
                    $paymentColors = ['efectivo' => 'from-emerald-400 to-emerald-500', 'tarjeta' => 'from-blue-400 to-blue-500', 'transferencia' => 'from-purple-400 to-purple-500'];
                    $paymentBgColors = ['efectivo' => 'bg-emerald-100 dark:bg-emerald-900/30', 'tarjeta' => 'bg-blue-100 dark:bg-blue-900/30', 'transferencia' => 'bg-purple-100 dark:bg-purple-900/30'];
                    $paymentTextColors = ['efectivo' => 'text-emerald-600 dark:text-emerald-400', 'tarjeta' => 'text-blue-600 dark:text-blue-400', 'transferencia' => 'text-purple-600 dark:text-purple-400'];
                    $totalPayments = $data['salesByPayment']->sum('count');
                ?>
                <?php $__currentLoopData = $data['salesByPayment']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $pct = $totalPayments > 0 ? round(($payment->count / $totalPayments) * 100) : 0; ?>
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e($paymentLabels[$payment->payment_type] ?? $payment->payment_type); ?></span>
                        <span class="text-xs font-semibold <?php echo e($paymentTextColors[$payment->payment_type] ?? 'text-gray-600'); ?>"><?php echo e($payment->count); ?> ventas</span>
                    </div>
                    <div class="w-full <?php echo e($paymentBgColors[$payment->payment_type] ?? 'bg-gray-100 dark:bg-gray-700'); ?> rounded-full h-2.5 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r <?php echo e($paymentColors[$payment->payment_type] ?? 'from-gray-400 to-gray-500'); ?>" style="width: <?php echo e($pct); ?>%"></div>
                    </div>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">₲ <?php echo e(number_format($payment->total, 0, ',', '.')); ?> (<?php echo e($pct); ?>%)</p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Acciones Rápidas</h3>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Accesos directos</p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="<?php echo e(route('sales.create')); ?>" class="group relative flex items-center gap-4 p-4 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-xl hover:from-indigo-600 hover:to-indigo-700 transition-all duration-200 hover:scale-[1.02] hover:shadow-lg hover:shadow-indigo-200 dark:hover:shadow-none">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-white/20 shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-sm">Nueva Venta</p>
                        <p class="text-[11px] text-indigo-100/70">Registrar una venta</p>
                    </div>
                </a>
                <a href="<?php echo e(route('sales.index')); ?>" class="group relative flex items-center gap-4 p-4 bg-gradient-to-r from-gray-700 to-gray-800 text-white rounded-xl hover:from-gray-800 hover:to-gray-900 transition-all duration-200 hover:scale-[1.02] hover:shadow-lg">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-white/20 shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-sm">Ver Ventas</p>
                        <p class="text-[11px] text-gray-300/70">Historial de ventas</p>
                    </div>
                </a>
                <?php if(Auth::user()->isAdmin()): ?>
                <a href="<?php echo e(route('products.index')); ?>" class="group relative flex items-center gap-4 p-4 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 hover:scale-[1.02] hover:shadow-lg hover:shadow-emerald-200 dark:hover:shadow-none">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-white/20 shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-sm">Gestionar Productos</p>
                        <p class="text-[11px] text-emerald-100/70">Administrar catálogo</p>
                    </div>
                </a>
                <a href="<?php echo e(route('admin.users')); ?>" class="group relative flex items-center gap-4 p-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all duration-200 hover:scale-[1.02] hover:shadow-lg">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-white/20 shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-sm">Usuarios</p>
                        <p class="text-[11px] text-purple-100/70">Gestionar usuarios</p>
                    </div>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php else: ?>
    
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8">
        <div class="flex items-center gap-3 mb-5">
            <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Acciones Rápidas</h3>
                <p class="text-[11px] text-gray-400 dark:text-gray-500">Accesos directos</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <a href="<?php echo e(route('sales.create')); ?>" class="group relative flex items-center gap-4 p-4 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-xl hover:from-indigo-600 hover:to-indigo-700 transition-all duration-200 hover:scale-[1.02] hover:shadow-lg hover:shadow-indigo-200 dark:hover:shadow-none">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-white/20 shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-sm">Nueva Venta</p>
                    <p class="text-[11px] text-indigo-100/70">Registrar una venta</p>
                </div>
            </a>
            <a href="<?php echo e(route('sales.index')); ?>" class="group relative flex items-center gap-4 p-4 bg-gradient-to-r from-gray-700 to-gray-800 text-white rounded-xl hover:from-gray-800 hover:to-gray-900 transition-all duration-200 hover:scale-[1.02] hover:shadow-lg">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-white/20 shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-sm">Ver Ventas</p>
                    <p class="text-[11px] text-gray-300/70">Historial de ventas</p>
                </div>
            </a>
        </div>
    </div>
    <?php endif; ?>

    <?php $__env->startPush('scripts'); ?>
    <?php if(Auth::user()->isAdmin() && isset($data['weeklySales'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('weeklyChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 220);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.4)');
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0.05)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($data['weeklySales']->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->isoFormat('dddd'))); ?>,
                datasets: [{
                    label: 'Ventas (₲)',
                    data: <?php echo json_encode($data['weeklySales']->pluck('total')); ?>,
                    backgroundColor: gradient,
                    borderColor: 'rgb(99, 102, 241)',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e1b4b',
                        titleColor: '#e0e7ff',
                        bodyColor: '#c7d2fe',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '₲ ' + context.parsed.y.toLocaleString('es-PY');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(99, 102, 241, 0.08)' },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 11 },
                            callback: function(value) { return '₲ ' + value.toLocaleString('es-PY'); }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 11 },
                            maxRotation: 45,
                        }
                    }
                }
            }
        });
    </script>
    <?php endif; ?>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\xampp\Proyectos\cantcome_system\resources\views/dashboard.blade.php ENDPATH**/ ?>