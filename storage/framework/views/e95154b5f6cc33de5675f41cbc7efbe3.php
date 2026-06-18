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
     <?php $__env->slot('header', null, []); ?> Movimiento de Caja <?php $__env->endSlot(); ?>

    <?php if(session('success')): ?>
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg mb-6 text-sm"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 text-sm"><?php echo e($errors->first()); ?></div>
    <?php endif; ?>

    
    <?php if($cajaAbierta): ?>
    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300 px-4 py-3 rounded-lg mb-6 text-sm flex flex-wrap items-center gap-x-2 gap-y-1">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <span>Caja abierta desde <?php echo e($cajaAbierta->fecha_apertura->format('d/m/Y H:i')); ?></span>
        <span class="hidden sm:inline text-amber-500">·</span>
        <span class="font-medium"><?php echo e($cajaAbierta->ventas_count ?? 0); ?> venta<?php echo e(($cajaAbierta->ventas_count ?? 0) !== 1 ? 's' : ''); ?> vinculada<?php echo e(($cajaAbierta->ventas_count ?? 0) !== 1 ? 's' : ''); ?></span>
        <div class="flex items-center gap-2 ml-auto">
            <a href="<?php echo e(route('caja.show', $cajaAbierta)); ?>" class="font-semibold underline">Ir a la caja</a>
            <a href="<?php echo e(route('caja.show', $cajaAbierta) . '#cerrar'); ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-amber-700 bg-amber-100 dark:text-amber-300 dark:bg-amber-900/40 hover:bg-amber-200 dark:hover:bg-amber-900/60 rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10"/></svg>
                Cerrar Caja
            </a>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="hidden md:block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Historial de Caja</h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400"><?php echo e($cajas->count()); ?> registro<?php echo e($cajas->count() !== 1 ? 's' : ''); ?></p>
                </div>
            </div>
            <?php if(!$cajaAbierta): ?>
            <a href="<?php echo e(route('caja.create')); ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Abrir Caja
            </a>
            <?php endif; ?>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Inicial</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ingresos</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Egresos</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Esperado</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Real</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $cajas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $caja): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white"><?php echo e($caja->fecha_apertura->format('d/m/Y H:i')); ?></td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?php echo e($caja->user->name); ?></td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">₲ <?php echo e(number_format($caja->monto_inicial, 0, ',', '.')); ?></td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-emerald-600 dark:text-emerald-400">₲ <?php echo e(number_format($caja->total_ingresos, 0, ',', '.')); ?></td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-red-600 dark:text-red-400">₲ <?php echo e(number_format($caja->total_egresos, 0, ',', '.')); ?></td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">₲ <?php echo e(number_format($caja->monto_final_esperado, 0, ',', '.')); ?></td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right <?php echo e($caja->diferencia && $caja->diferencia != 0 ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-gray-900 dark:text-white'); ?>">
                            <?php echo e($caja->monto_final_real ? '₲ '.number_format($caja->monto_final_real, 0, ',', '.') : '-'); ?>

                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium <?php echo e($caja->estado === 'abierta' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'); ?>">
                                <span class="w-1.5 h-1.5 rounded-full <?php echo e($caja->estado === 'abierta' ? 'bg-emerald-500' : 'bg-gray-400'); ?>"></span>
                                <?php echo e($caja->estado === 'abierta' ? 'Abierta' : 'Cerrada'); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="<?php echo e(route('caja.show', $caja)); ?>" class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Ver detalle">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <?php if(Auth::user()->isSuperAdmin()): ?>
                                <form action="<?php echo e(route('caja.destroy', $caja)); ?>" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este registro de caja?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mb-2">No hay registros de caja</p>
                            <?php if(!$cajaAbierta): ?>
                            <a href="<?php echo e(route('caja.create')); ?>" class="text-sm text-indigo-600 dark:text-indigo-400 font-medium hover:underline">Abrir primera caja</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="block md:hidden space-y-3">
        <?php if(!$cajaAbierta): ?>
        <a href="<?php echo e(route('caja.create')); ?>" class="flex items-center justify-center gap-2 w-full py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Abrir Caja
        </a>
        <?php endif; ?>
        <?php $__empty_1 = true; $__currentLoopData = $cajas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $caja): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-gray-900 dark:text-white"><?php echo e($caja->fecha_apertura->format('d/m/Y H:i')); ?></span>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium <?php echo e($caja->estado === 'abierta' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'); ?>">
                        <?php echo e($caja->estado === 'abierta' ? 'Abierta' : 'Cerrada'); ?>

                    </span>
                </div>
                <a href="<?php echo e(route('caja.show', $caja)); ?>" class="text-indigo-600 dark:text-indigo-400 text-xs font-medium hover:underline">Ver</a>
            </div>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div><span class="text-gray-400 dark:text-gray-500">Usuario:</span> <span class="text-gray-700 dark:text-gray-300"><?php echo e($caja->user->name); ?></span></div>
                <div class="text-right"><span class="text-gray-400 dark:text-gray-500">Inicial:</span> <span class="text-gray-700 dark:text-gray-300">₲ <?php echo e(number_format($caja->monto_inicial, 0, ',', '.')); ?></span></div>
                <div><span class="text-emerald-600 dark:text-emerald-400">Ingresos:</span> ₲ <?php echo e(number_format($caja->total_ingresos, 0, ',', '.')); ?></div>
                <div class="text-right"><span class="text-red-600 dark:text-red-400">Egresos:</span> ₲ <?php echo e(number_format($caja->total_egresos, 0, ',', '.')); ?></div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            <p class="text-sm text-gray-400 dark:text-gray-500">No hay registros de caja</p>
        </div>
        <?php endif; ?>
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
<?php endif; ?><?php /**PATH C:\xampp\Proyectos\cantcome_system\resources\views/caja/index.blade.php ENDPATH**/ ?>