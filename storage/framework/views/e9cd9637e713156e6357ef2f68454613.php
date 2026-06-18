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
    <div class="flex items-center justify-between">
        <span>Reportes</span>
        <a x-data="{ theme: localStorage.getItem('theme') || 'indigo' }"
            :href="`<?php echo e(route('admin.reports.pdf')); ?>?theme=${theme}`"
            target="_blank"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-xs font-medium">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Descargar PDF
        </a>
    </div>
 <?php $__env->endSlot(); ?>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ventas Totales</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?php echo e($totalSales); ?></p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ingresos Totales</p>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">₲ <?php echo e(number_format($totalRevenue, 0, ',', '.')); ?></p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ingresos del Mes</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">₲ <?php echo e(number_format($monthlyRevenue, 0, ',', '.')); ?></p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Clientes</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?php echo e($totalCustomers); ?></p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ventas Diarias (30 días)</h3>
        <div class="relative" style="height: 250px;">
            <canvas id="dailyChart"></canvas>
        </div>
    </div>

    
    <div class="hidden md:block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detalle por Fecha</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ventas</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <?php $__currentLoopData = $dailySales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-2.5 text-sm text-gray-900 dark:text-white"><?php echo e(\Carbon\Carbon::parse($sale->date)->format('d/m/Y')); ?></td>
                        <td class="px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 text-right"><?php echo e($sale->count); ?></td>
                        <td class="px-4 py-2.5 text-sm text-gray-900 dark:text-white text-right font-medium">₲ <?php echo e(number_format($sale->total, 0, ',', '.')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="block md:hidden space-y-2">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Detalle por Fecha</h3>
        <?php $__empty_1 = true; $__currentLoopData = $dailySales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white"><?php echo e(\Carbon\Carbon::parse($sale->date)->format('d/m/Y')); ?></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($sale->count); ?> venta<?php echo e($sale->count !== 1 ? 's' : ''); ?></p>
                </div>
                <p class="text-sm font-bold text-indigo-600 dark:text-indigo-400">₲ <?php echo e(number_format($sale->total, 0, ',', '.')); ?></p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
            <p class="text-sm text-gray-400 dark:text-gray-500">Sin datos de ventas</p>
        </div>
        <?php endif; ?>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('dailyChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($dailySales->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))); ?>,
                datasets: [{
                    label: 'Ventas (₲)',
                    data: <?php echo json_encode($dailySales->pluck('total')); ?>,
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgb(99, 102, 241)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
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
<?php endif; ?>
<?php /**PATH C:\xampp\Proyectos\cantcome_system\resources\views/admin/reports.blade.php ENDPATH**/ ?>