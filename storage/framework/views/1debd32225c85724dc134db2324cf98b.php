<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title',
    'description' => null,
    'icon' => 'panel-top',
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
    'title',
    'description' => null,
    'icon' => 'panel-top',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="hci-eyebrow"><?php echo e(__('Focused Workspace')); ?></p>
        <div class="mt-2 flex items-center gap-3">
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-soft-blue text-sidebar-purple shadow-soft dark:bg-slate-800 dark:text-slate-100">
                <i data-lucide="<?php echo e($icon); ?>" class="h-5 w-5"></i>
            </span>
            <div>
                <h2 class="text-2xl font-semibold tracking-tight text-slate-950 dark:text-slate-100"><?php echo e(__($title)); ?></h2>
                <?php if($description): ?>
                    <p class="mt-1 max-w-2xl text-sm text-slate-500 dark:text-slate-400"><?php echo e(__($description)); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if(trim($slot)): ?>
        <div class="flex items-center gap-3">
            <?php echo e($slot); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH D:\SynologyDrive\systems\Feedback_Management_System\resources\views/components/admin/page-header.blade.php ENDPATH**/ ?>