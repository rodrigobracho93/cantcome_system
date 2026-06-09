<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['active', 'sidebar' => false]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['active', 'sidebar' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
if ($sidebar) {
    $classes = ($active ?? false)
        ? 'sidebar-link flex items-center gap-3 pl-3 py-2.5 text-sm font-medium text-white bg-indigo-800/60 border-l-4 border-indigo-400 transition duration-150'
        : 'sidebar-link flex items-center gap-3 pl-4 py-2.5 text-sm font-medium text-white/80 hover:text-white hover:bg-indigo-800/30 border-l-4 border-transparent transition duration-150';
}
?>

<a <?php echo e($attributes->merge(['class' => $classes])); ?>>
    <?php if($sidebar && isset($icon)): ?>
        <span class="shrink-0"><?php echo e($icon); ?></span>
    <?php endif; ?>
    <span class="sidebar-text"><?php echo e($slot); ?></span>
</a>
<?php /**PATH C:\xampp\Proyectos\cantcome_system\resources\views/components/nav-link.blade.php ENDPATH**/ ?>