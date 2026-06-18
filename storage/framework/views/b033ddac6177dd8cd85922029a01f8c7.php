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
     <?php $__env->slot('header', null, []); ?> Venta #<?php echo e($sale->id); ?> <?php $__env->endSlot(); ?>

    <div class="max-w-4xl">
        <?php if(session('success')): ?>
            <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg mb-6 text-sm"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8 mb-4">
            
            <div class="flex items-center gap-4 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Venta #<?php echo e($sale->id); ?></h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($sale->created_at->locale('es')->isoFormat('dddd, D [de] MMMM [del] YYYY - H:mm')); ?></p>
                </div>
                <div class="flex items-center gap-2">
                    <?php
                        $cajaMov = $sale->cajaMovimientos->first();
                    ?>
                    <button type="button" onclick="sharePdf()"
                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-300 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-colors" title="Compartir PDF en WhatsApp">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        WhatsApp
                    </button>
                    <?php if($cajaMov): ?>
                    <a href="<?php echo e(route('caja.show', $cajaMov->caja)); ?>" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors" title="Vinculado a caja">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Caja
                    </a>
                    <?php endif; ?>
                    <span class="px-3 py-1.5 rounded-full text-xs font-semibold <?php echo e($sale->status === 'completado' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : ($sale->status === 'anulado' ? 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300' : 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300')); ?>">
                        <?php echo e($sale->status === 'completado' ? 'Completado' : ($sale->status === 'anulado' ? 'Anulado' : 'Pendiente')); ?>

                    </span>
                </div>
            </div>

            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3.5">
                    <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Vendedor</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white"><?php echo e($sale->user->name); ?></p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3.5">
                    <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Cliente</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white"><?php echo e($sale->customer?->full_name ?? 'Consumidor Final'); ?></p>
                    <?php if($sale->customer): ?>
                        <?php if($sale->customer->document): ?>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400">Cédula/RUC: <?php echo e($sale->customer->document); ?></p>
                        <?php endif; ?>
                        <?php if($sale->customer->phone): ?>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400">Tel: <?php echo e($sale->customer->phone); ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3.5">
                    <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Tipo de Pago</p>
                    <p class="mt-1">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?php echo e($sale->payment_type === 'contado' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300'); ?>">
                            <?php echo e($sale->payment_type === 'contado' ? 'Contado' : 'Crédito'); ?>

                        </span>
                    </p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3.5">
                    <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Productos</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white"><?php echo e($sale->items->sum('quantity')); ?> unidades</p>
                </div>
            </div>

            
            <div class="overflow-x-auto mb-4">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Producto</th>
                            <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Precio</th>
                            <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cant.</th>
                            <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-4 py-2.5 text-sm text-gray-900 dark:text-white"><?php echo e($item->product->name); ?></td>
                            <td class="px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 text-right">₲ <?php echo e(number_format($item->unit_price, 0, ',', '.')); ?></td>
                            <td class="px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 text-right"><?php echo e($item->quantity); ?></td>
                            <td class="px-4 py-2.5 text-sm text-gray-900 dark:text-white text-right font-medium">₲ <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            
            <div class="border-t border-gray-100 dark:border-gray-700 pt-4 space-y-1.5">
                <div class="flex justify-end gap-8 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Subtotal</span>
                    <span class="text-gray-900 dark:text-white font-medium w-28 text-right">₲ <?php echo e(number_format($sale->subtotal, 0, ',', '.')); ?></span>
                </div>
                <div class="flex justify-end gap-8 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">IVA (10%)</span>
                    <span class="text-gray-900 dark:text-white font-medium w-28 text-right">₲ <?php echo e(number_format($sale->tax, 0, ',', '.')); ?></span>
                </div>
                <div class="flex justify-end gap-8 text-base pt-2 border-t border-gray-100 dark:border-gray-700">
                    <span class="font-bold text-gray-900 dark:text-white">Total</span>
                    <span class="font-bold text-indigo-600 dark:text-indigo-400 w-28 text-right text-lg">₲ <?php echo e(number_format($sale->total, 0, ',', '.')); ?></span>
                </div>
            </div>

            <?php if($sale->notes): ?>
            <div class="mt-4 p-3.5 bg-gray-50 dark:bg-gray-700/30 rounded-xl">
                <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">Notas</p>
                <p class="text-sm text-gray-700 dark:text-gray-300"><?php echo e($sale->notes); ?></p>
            </div>
            <?php endif; ?>
        </div>

        <div class="flex flex-wrap justify-end gap-3">
            <a href="<?php echo e(route('sales.receipt-pdf', $sale)); ?>" target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Descargar PDF
            </a>
            <button type="button" onclick="sharePdf()"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Compartir WhatsApp
            </button>
            <a href="<?php echo e(route('sales.index')); ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
            <?php if(Auth::user()->isAdmin() && $sale->status !== 'anulado'): ?>
            <form action="<?php echo e(route('sales.destroy', $sale)); ?>" method="POST" onsubmit="return confirm('¿Anular esta venta?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Anular Venta
                </button>
            </form>
            <?php endif; ?>
            <?php if(Auth::user()->isSuperAdmin()): ?>
            <form action="<?php echo e(route('sales.force-destroy', $sale)); ?>" method="POST" onsubmit="return confirm('¿Eliminar permanentemente la venta #<?php echo e($sale->id); ?>? Se restaurará el stock.')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Eliminar
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function sharePdf() {
        fetch('<?php echo e(route("sales.receipt-pdf-url", $sale)); ?>').then(function(r) { return r.json(); }).then(function(data) {
            var msg = 'Recibo #<?php echo e($sale->id); ?> - <?php echo e($sale->customer?->name ?? "Consumidor Final"); ?>\n\n📄 ' + data.url;
            window.open('https://wa.me/?text=' + encodeURIComponent(msg), '_blank');
        });
    }
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\xampp\Proyectos\cantcome_system\resources\views/sales/show.blade.php ENDPATH**/ ?>