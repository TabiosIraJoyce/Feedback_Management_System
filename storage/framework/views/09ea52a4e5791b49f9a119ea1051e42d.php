<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'href',
    'active' => false,
    'icon' => 'circle',
]));

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

foreach (array_filter(([
    'href',
    'active' => false,
    'icon' => 'circle',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a href="<?php echo e($href); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses([
    'flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition-all duration-200',
    'border border-transparent bg-soft-blue text-primary-text shadow-soft ring-1 ring-sidebar-purple/10 dark:bg-slate-800/80 dark:text-slate-100' => $active,
    'text-primary-text hover:bg-white/80 hover:text-primary-text dark:text-slate-100 dark:hover:bg-slate-800' => ! $active,
]); ?>" aria-current="<?php echo e($active ? 'page' : false); ?>">
    <i data-lucide="<?php echo e($icon); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['h-5 w-5', 'text-sidebar-purple dark:text-cyan-300' => $active]); ?>"></i>
    <span><?php echo e(__(trim(strip_tags($slot)))); ?></span>
</a>
<?php /**PATH D:\SynologyDrive\systems\Feedback_Management_System\resources\views/components/admin/sidebar-link.blade.php ENDPATH**/ ?>