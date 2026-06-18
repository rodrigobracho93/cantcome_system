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
     <?php $__env->slot('header', null, []); ?> Almuerzos Premium <?php $__env->endSlot(); ?>

    <?php if(session('success')): ?>
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg mb-6 text-sm"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 text-sm"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" class="flex items-center gap-3">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Fecha:</label>
                <input type="date" name="fecha" value="<?php echo e($fecha->format('Y-m-d')); ?>" onchange="this.form.submit()"
                    class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <a href="<?php echo e(route('almuerzos.index', ['fecha' => today()->format('Y-m-d')])); ?>" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Hoy</a>
            </form>
            <div class="flex items-center gap-2 ml-auto">
                <a href="<?php echo e(route('almuerzos.reporte')); ?>"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-xs font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Reporte Mensual
                </a>
            </div>
        </div>
    </div>

    
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="flex divide-x divide-gray-200 dark:divide-gray-700">
            <div class="flex-1 px-3 py-3 text-center">
                <p class="text-[10px] font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white"><?php echo e($totalHoy); ?></p>
            </div>
            <div class="flex-1 px-3 py-3 text-center">
                <p class="text-[10px] font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Entregados</p>
                <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400"><?php echo e($entregadosHoy); ?></p>
            </div>
            <div class="flex-1 px-3 py-3 text-center">
                <p class="text-[10px] font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pendientes</p>
                <p class="text-lg font-bold text-amber-600 dark:text-amber-400"><?php echo e($pendientesHoy); ?></p>
            </div>
        </div>
    </div>

    
    <div x-data="{ query: '', results: [], selected: null, open: false, loading: false }" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-6">
        <form method="POST" action="<?php echo e(route('almuerzos.store')); ?>" @submit="if(!selected) { $event.preventDefault(); }">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="fecha" value="<?php echo e($fecha->format('Y-m-d')); ?>">
            <input type="hidden" name="customer_id" :value="selected?.id">
            <div class="flex items-end gap-3">
                <div class="flex-1 relative">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Agregar persona</label>
                    <input type="text"
                        x-model="query"
                        @input.debounce.300ms="
                            if (query.length < 2) { results = []; open = false; return; }
                            loading = true;
                            fetch(`/customers/search?q=${encodeURIComponent(query)}`)
                                .then(r => r.json())
                                .then(data => { results = data; open = true; loading = false; });
                        "
                        @keydown.escape="open = false"
                        @click.outside="open = false"
                        @keydown.enter.prevent="if(results.length === 1) { selected = results[0]; query = results[0].name; open = false; }"
                        placeholder="Escribir nombre..."
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">

                    
                    <div x-show="loading" x-cloak class="absolute right-3 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    </div>

                    
                    <div x-show="open && results.length > 0" x-cloak
                        class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                        <template x-for="c in results" :key="c.id">
                            <div @click="selected = c; query = c.name; open = false;"
                                class="px-3 py-2.5 text-sm cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors flex items-center justify-between"
                                :class="selected?.id === c.id ? 'bg-indigo-50 dark:bg-indigo-900/20' : ''">
                                <div>
                                    <span class="font-medium text-gray-900 dark:text-white" x-text="c.name"></span>
                                    <span class="text-gray-400 ml-1" x-text="c.document ? c.document : ''"></span>
                                </div>
                                <div class="text-xs text-gray-400" x-text="c.phone ? '📞 ' + c.phone : ''"></div>
                            </div>
                        </template>
                    </div>

                    
                    <div x-show="selected" x-cloak class="mt-2 flex items-center gap-2 text-sm text-emerald-600 dark:text-emerald-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span x-text="selected?.name"></span>
                        <button type="button" @click="selected = null; query = ''; results = [];" class="ml-1 text-gray-400 hover:text-red-400">&times;</button>
                    </div>
                </div>
                <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium whitespace-nowrap">
                    Agregar
                </button>
            </div>
        </form>
    </div>

    
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Lista del día</h3>
            <span class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($fecha->locale('es')->isoFormat('dddd, D [de] MMMM [del] YYYY')); ?></span>
        </div>

        <?php if($almuerzos->isEmpty()): ?>
        <div class="p-12 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
            <p class="text-sm text-gray-400 dark:text-gray-500">No hay personas registradas para esta fecha</p>
        </div>
        <?php else: ?>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            <?php $__currentLoopData = $almuerzos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $almuerzo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div x-data="{ openObs: false }">
                <div class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors <?php echo e($almuerzo->entregado ? 'bg-emerald-50/50 dark:bg-emerald-900/10' : ''); ?>">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium <?php echo e($almuerzo->entregado ? 'text-gray-500 dark:text-gray-400 line-through' : 'text-gray-900 dark:text-white'); ?>">
                            <?php echo e($almuerzo->customer->name); ?>

                        </p>
                        <?php
                            $datos = collect();
                            if ($almuerzo->customer->document) $datos->push($almuerzo->customer->document);
                            if ($almuerzo->customer->phone) $datos->push($almuerzo->customer->phone);
                            if ($almuerzo->customer->company) $datos->push($almuerzo->customer->company);
                        ?>
                        <?php if($datos->isNotEmpty()): ?>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5"><?php echo e($datos->implode(' · ')); ?></p>
                        <?php endif; ?>
                        <?php if($almuerzo->observacion): ?>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 italic">“<?php echo e($almuerzo->observacion); ?>”</p>
                        <?php endif; ?>
                    </div>
                    <?php if($almuerzo->entregado): ?>
                    <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">
                        <?php echo e($almuerzo->entregado_at?->format('H:i')); ?>

                    </span>
                    <?php endif; ?>
                    <div class="flex items-center gap-1">
                        <?php if($almuerzo->entregado): ?>
                        <form action="<?php echo e(route('almuerzos.toggle', $almuerzo)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="p-2 rounded-lg transition-colors text-amber-500 hover:text-amber-700 hover:bg-amber-50 dark:hover:bg-amber-900/20" title="Marcar pendiente">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                        <?php else: ?>
                        <button @click="openObs = true" class="p-2 rounded-lg transition-colors text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-900/20" title="Marcar entregado">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </button>
                        <?php endif; ?>
                        <form action="<?php echo e(route('almuerzos.destroy', $almuerzo)); ?>" method="POST" class="inline" onsubmit="return confirm('¿Eliminar <?php echo e($almuerzo->customer->name); ?> de la lista?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Eliminar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>

                
                <div x-show="openObs" x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <div class="fixed inset-0 bg-black/50" @click="openObs = false"></div>
                    <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6 w-full max-w-md" @click.outside="openObs = false">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Marcar entregado</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4"><?php echo e($almuerzo->customer->name); ?></p>
                        <form action="<?php echo e(route('almuerzos.toggle', $almuerzo)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observación (opcional)</label>
                            <textarea name="observacion" rows="3" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: Sin cebolla, sin sal..."></textarea>
                            <div class="flex justify-end gap-2 mt-4">
                                <button type="button" @click="openObs = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Cancelar</button>
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors">Confirmar entrega</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php endif; ?>
<?php /**PATH C:\xampp\Proyectos\cantcome_system\resources\views/almuerzos/index.blade.php ENDPATH**/ ?>