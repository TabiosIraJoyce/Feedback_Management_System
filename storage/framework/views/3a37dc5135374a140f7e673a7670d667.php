<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'compact' => false,
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
    'compact' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div x-data="languageSwitcher({ locale: '<?php echo e(app()->getLocale()); ?>' })" class="flex items-center gap-2">
    <label for="global-language-switcher-<?php echo e($compact ? 'compact' : 'full'); ?>" class="<?php echo e($compact ? 'sr-only' : 'text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400'); ?>">
        <?php echo e(__('Language')); ?>

    </label>
    <select
        id="global-language-switcher-<?php echo e($compact ? 'compact' : 'full'); ?>"
        x-model="locale"
        @change="switchLocale($event.target.value)"
        class="rounded-2xl border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
        aria-label="<?php echo e(__('Language')); ?>"
    >
        <?php $__currentLoopData = ($hciConfig['available_locales'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($locale['value']); ?>"><?php echo e(__($locale['label'])); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>
<?php /**PATH D:\SynologyDrive\systems\Feedback_Management_System\resources\views/components/language-switcher.blade.php ENDPATH**/ ?>