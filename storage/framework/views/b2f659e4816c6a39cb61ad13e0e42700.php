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
     <?php $__env->slot('header', null, []); ?> Libro Diario <?php $__env->endSlot(); ?>

    
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
        <form method="GET" class="flex items-center gap-3">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Fecha:</label>
            <input type="date" name="fecha" value="<?php echo e($fecha); ?>" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">Consultar</button>
        </form>
    </div>

    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <p class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Ventas</p>
            <p class="text-lg font-bold text-gray-900 dark:text-white"><?php echo e($ventas->where('status', '!=', 'anulado')->count()); ?></p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <p class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Total Ventas</p>
            <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">₲ <?php echo e(number_format($totalVentas, 0, ',', '.')); ?></p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <p class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Mov. Ingresos</p>
            <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">₲ <?php echo e(number_format($totalMovimientosIngreso, 0, ',', '.')); ?></p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <p class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Mov. Egresos</p>
            <p class="text-lg font-bold text-red-600 dark:text-red-400">₲ <?php echo e(number_format($totalMovimientosEgreso, 0, ',', '.')); ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Ventas del Día</h3>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $ventas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center justify-between px-5 py-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">#<?php echo e($venta->id); ?> — <?php echo e($venta->customer?->full_name ?? 'Sin cliente'); ?></p>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500"><?php echo e($venta->created_at->format('H:i')); ?> · <?php echo e(ucfirst($venta->payment_type)); ?></p>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">₲ <?php echo e(number_format($venta->total, 0, ',', '.')); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="px-5 py-8 text-center text-sm text-gray-400 dark:text-gray-500">Sin ventas en esta fecha</div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Movimientos de Caja</h3>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $movimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center justify-between px-5 py-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="shrink-0 w-6 h-6 flex items-center justify-center rounded-full text-xs font-bold <?php echo e($mov->tipo === 'ingreso' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400'); ?>">
                            <?php echo e($mov->tipo === 'ingreso' ? '+' : '-'); ?>

                        </span>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-[200px]"><?php echo e($mov->concepto); ?></p>
                                <?php if($mov->referencia_type === 'App\Models\Sale'): ?>
                                <a href="<?php echo e(route('sales.show', $mov->referencia_id)); ?>" class="shrink-0 p-1 text-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Ver venta">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                </a>
                                <?php endif; ?>
                            </div>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500"><?php echo e($mov->created_at->format('H:i')); ?></p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold <?php echo e($mov->tipo === 'ingreso' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400'); ?>">
                        <?php echo e($mov->tipo === 'ingreso' ? '+' : '-'); ?>₲ <?php echo e(number_format($mov->monto, 0, ',', '.')); ?>

                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="px-5 py-8 text-center text-sm text-gray-400 dark:text-gray-500">Sin movimientos en esta fecha</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\xampp\Proyectos\cantcome_system\resources\views/caja/libro-diario.blade.php ENDPATH**/ ?>