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
     <?php $__env->slot('header', null, []); ?> Ventas por Cliente <?php $__env->endSlot(); ?>

    <?php if(session('success')): ?>
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 text-sm"><?php echo e($errors->first()); ?></div>
    <?php endif; ?>

    
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Cliente</label>
                <select name="customer_id" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todos los clientes</option>
                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c->id); ?>" <?php echo e(request('customer_id') == $c->id ? 'selected' : ''); ?>><?php echo e($c->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tipo de pago</label>
                <select name="payment_type" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="credito" <?php echo e(($paymentType ?? 'credito') === 'credito' ? 'selected' : ''); ?>>Crédito</option>
                    <option value="contado" <?php echo e($paymentType === 'contado' ? 'selected' : ''); ?>>Contado</option>
                    <option value="">Todos</option>
                </select>
            </div>
            <div class="flex items-end">
                <label class="flex items-center gap-2 pb-1.5 cursor-pointer">
                    <input type="checkbox" name="show_all" value="1" <?php echo e($showAll ? 'checked' : ''); ?> class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Mostrar cobrados</span>
                </label>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Desde</label>
                <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Hasta</label>
                <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors text-center">Filtrar</button>
                <a href="<?php echo e(route('customer-sales.pdf', request()->query())); ?>" class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors inline-flex items-center justify-center gap-1.5 text-center" target="_blank">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12v4m0 0l-2-2m2 2l2-2"/></svg>
                    PDF
                </a>
                <a href="<?php echo e(route('customer-sales.index')); ?>" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors text-center">Limpiar</a>
            </div>
        </form>
    </div>

    <?php $displayCustomer = $customer ?? (object)['full_name' => 'Sin cliente', 'document' => null, 'phone' => null, 'name' => 'Sin cliente']; ?>

        
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 px-5 py-4 mb-6">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <?php if($customer || request()->has('customer_id')): ?>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold shrink-0">
                        <?php echo e(substr($displayCustomer->full_name, 0, 1)); ?>

                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white"><?php echo e($displayCustomer->full_name); ?></p>
                        <?php if($displayCustomer->document || $displayCustomer->phone): ?>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400"><?php echo e($displayCustomer->document ?? ''); ?><?php echo e($displayCustomer->document && $displayCustomer->phone ? ' · ' : ''); ?><?php echo e($displayCustomer->phone ?? ''); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php else: ?>
                <span class="text-sm font-semibold text-gray-900 dark:text-white">Resumen general</span>
                <?php endif; ?>
            </div>
            <div class="flex flex-wrap gap-3 text-sm">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl px-4 py-2 text-center min-w-[90px]">
                    <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total</p>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">₲ <?php echo e(number_format($totalGeneral, 0, ',', '.')); ?></p>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl px-4 py-2 text-center min-w-[90px]">
                    <p class="text-[10px] font-medium text-emerald-500 dark:text-emerald-400 uppercase tracking-wider">Cobrado</p>
                    <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400">₲ <?php echo e(number_format($totalCobrado, 0, ',', '.')); ?></p>
                </div>
                <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl px-4 py-2 text-center min-w-[90px]">
                    <p class="text-[10px] font-medium text-amber-500 dark:text-amber-400 uppercase tracking-wider">Pendiente</p>
                    <p class="text-sm font-bold text-amber-600 dark:text-amber-400">₲ <?php echo e(number_format($totalPendiente, 0, ',', '.')); ?></p>
                </div>
            </div>
        </div>
    </div>

    <?php if($customer || request()->has('customer_id')): ?>
    
    <div class="hidden md:block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vendedor</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Productos</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pago</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider w-20">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors <?php echo e($sale->payment_type === 'credito' && !$sale->paid_at ? 'bg-amber-50/30 dark:bg-amber-900/10' : ''); ?>">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">#<?php echo e($sale->id); ?></td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white"><?php echo e($sale->created_at->format('d/m/Y H:i')); ?></td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300"><?php echo e($sale->user->name); ?></td>
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 max-w-[200px]">
                            <div class="truncate">
                                <?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($item->product->name); ?><span class="text-gray-400"> (<?php echo e($item->quantity); ?>)</span><?php echo e(!$loop->last ? ',' : ''); ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium <?php echo e($sale->payment_type === 'contado' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300'); ?>">
                                <?php echo e(ucfirst($sale->payment_type)); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-semibold">₲ <?php echo e(number_format($sale->total, 0, ',', '.')); ?></td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <?php if($sale->paid_at): ?>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Cobrado
                            </span>
                            <?php elseif($sale->payment_type === 'contado'): ?>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Pagado
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Pendiente
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="<?php echo e(route('sales.show', $sale)); ?>" class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Ver detalle">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <?php if($sale->payment_type === 'credito' && !$sale->paid_at): ?>
                                <button @click="$dispatch('open-confirm-pay', {
                                    id: <?php echo e($sale->id); ?>,
                                    customer: '<?php echo e($sale->customer?->full_name ?? 'Sin cliente'); ?>',
                                    total: '<?php echo e(number_format($sale->total, 0, ',', '.')); ?>',
                                    date: '<?php echo e($sale->created_at->format('d/m/Y')); ?>',
                                    action: '<?php echo e(route('customer-sales.pay', $sale)); ?>'
                                })" class="p-2 text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Marcar como cobrado">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </button>
                                <?php elseif($sale->payment_type === 'credito' && $sale->paid_at && Auth::user()->isSuperAdmin()): ?>
                                <form action="<?php echo e(route('customer-sales.unpay', $sale)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="p-2 text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors" title="Revertir cobro">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                                <?php endif; ?>
                                <?php if(Auth::user()->isSuperAdmin()): ?>
                                <form action="<?php echo e(route('sales.force-destroy', $sale)); ?>" method="POST" class="inline" onsubmit="return confirm('¿Eliminar permanentemente esta venta #<?php echo e($sale->id); ?>? Se restaurará el stock.')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Eliminar permanentemente">
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
                            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <p class="text-sm text-gray-400 dark:text-gray-500">No se encontraron ventas para este cliente</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="block md:hidden space-y-3">
        <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 <?php echo e($sale->payment_type === 'credito' && !$sale->paid_at ? 'border-l-4 border-l-amber-400' : ''); ?>">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-gray-400">#<?php echo e($sale->id); ?></span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium <?php echo e($sale->payment_type === 'contado' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300'); ?>">
                        <?php echo e(ucfirst($sale->payment_type)); ?>

                    </span>
                </div>
                <?php if($sale->paid_at): ?>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">
                    Cobrado
                </span>
                <?php elseif($sale->payment_type === 'credito'): ?>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300">
                    Pendiente
                </span>
                <?php else: ?>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                    Pagado
                </span>
                <?php endif; ?>
            </div>
            <div class="grid grid-cols-2 gap-1.5 text-xs mb-3">
                <div><span class="text-gray-400 dark:text-gray-500">Fecha:</span> <span class="text-gray-700 dark:text-gray-300"><?php echo e($sale->created_at->format('d/m/Y H:i')); ?></span></div>
                <div><span class="text-gray-400 dark:text-gray-500">Vendedor:</span> <span class="text-gray-700 dark:text-gray-300"><?php echo e($sale->user->name); ?></span></div>
            </div>
            <div class="text-xs text-gray-600 dark:text-gray-400 mb-3">
                <?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span><?php echo e($item->product->name); ?> <span class="text-gray-400">(<?php echo e($item->quantity); ?>)</span><?php echo e(!$loop->last ? ',' : ''); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                <p class="text-base font-bold text-gray-900 dark:text-white">₲ <?php echo e(number_format($sale->total, 0, ',', '.')); ?></p>
                <div class="flex items-center gap-1">
                    <a href="<?php echo e(route('sales.show', $sale)); ?>" class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Ver detalle">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                    <?php if($sale->payment_type === 'credito' && !$sale->paid_at): ?>
                    <button @click="$dispatch('open-confirm-pay', {
                        id: <?php echo e($sale->id); ?>,
                        customer: '<?php echo e($sale->customer?->full_name ?? 'Sin cliente'); ?>',
                        total: '<?php echo e(number_format($sale->total, 0, ',', '.')); ?>',
                        date: '<?php echo e($sale->created_at->format('d/m/Y')); ?>',
                        action: '<?php echo e(route('customer-sales.pay', $sale)); ?>'
                    })" class="p-2 text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Marcar como cobrado">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </button>
                    <?php elseif($sale->payment_type === 'credito' && $sale->paid_at && Auth::user()->isSuperAdmin()): ?>
                    <form action="<?php echo e(route('customer-sales.unpay', $sale)); ?>" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="p-2 text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors" title="Revertir cobro">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </form>
                    <?php endif; ?>
                    <?php if(Auth::user()->isSuperAdmin()): ?>
                    <form action="<?php echo e(route('sales.force-destroy', $sale)); ?>" method="POST" class="inline" onsubmit="return confirm('¿Eliminar permanentemente esta venta #<?php echo e($sale->id); ?>?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Eliminar permanentemente">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-sm text-gray-400 dark:text-gray-500">No se encontraron ventas</p>
        </div>
        <?php endif; ?>
    </div>

    <div class="mt-6 text-center">
        <a href="<?php echo e(route('customer-sales.index')); ?>" class="inline-flex items-center gap-1 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Volver al resumen general
        </a>
    </div>

    <?php else: ?>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <?php $__empty_1 = true; $__currentLoopData = $porCliente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nombre => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <a href="<?php echo e(route('customer-sales.index', array_merge(request()->query(), ['customer_id' => $data['customer']?->id ?? '0']))); ?>" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-700 transition-all group">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white text-sm font-bold shrink-0">
                    <?php echo e(substr($data['customer']?->full_name ?? 'Sin cliente', 0, 1)); ?>

                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors"><?php echo e($data['customer']?->full_name ?? 'Sin cliente'); ?></p>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500"><?php echo e($data['count']); ?> venta<?php echo e($data['count'] !== 1 ? 's' : ''); ?></p>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-gray-400 dark:text-gray-500">Pendiente</span>
                <span class="text-base font-bold text-amber-600 dark:text-amber-400">₲ <?php echo e(number_format($data['total'], 0, ',', '.')); ?></span>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-sm text-gray-400 dark:text-gray-500">No hay cuentas pendientes</p>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <div x-data="{ open: false, sale: { id: null, customer: '', total: '', date: '', action: '' } }"
        x-on:open-confirm-pay.window="open = true; sale = $event.detail"
        x-show="open" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75" @click="open = false"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 w-full max-w-md p-6"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">
            <div class="flex items-center gap-4 mb-5">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30 shrink-0">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Confirmar Cobro</h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Esta acción no se puede deshacer</p>
                </div>
                <button @click="open = false" class="ml-auto p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4 mb-5">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="text-xs text-amber-700 dark:text-amber-300">
                        <p class="font-medium mb-1">¿Confirmás que se cobró este crédito?</p>
                        <p>Una vez confirmado, no podrás revertirlo a menos que tengas permisos de superadmin.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 text-sm mb-5">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg px-3 py-2">
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider">Venta #</p>
                    <p class="font-semibold text-gray-900 dark:text-white" x-text="'#' + sale.id"></p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg px-3 py-2">
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider">Fecha</p>
                    <p class="font-semibold text-gray-900 dark:text-white" x-text="sale.date"></p>
                </div>
                <div class="col-span-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg px-3 py-2">
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider">Cliente</p>
                    <p class="font-semibold text-gray-900 dark:text-white" x-text="sale.customer"></p>
                </div>
                <div class="col-span-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg px-3 py-2">
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total</p>
                    <p class="text-base font-bold text-emerald-600 dark:text-emerald-400" x-text="'Gs. ' + sale.total"></p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">Cancelar</button>
                <form :action="sale.action" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors">Sí, confirmar cobro</button>
                </form>
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
<?php endif; ?>
<?php /**PATH C:\xampp\Proyectos\cantcome_system\resources\views/customer-sales/index.blade.php ENDPATH**/ ?>