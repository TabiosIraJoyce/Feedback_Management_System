<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label',
    'value',
    'icon' => 'bar-chart-3',
    'tone' => 'cyan',
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
    'label',
    'value',
    'icon' => 'bar-chart-3',
    'tone' => 'cyan',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $tones = [
        'cyan' => 'bg-cyan-500/10 text-cyan-700',
        'emerald' => 'bg-emerald-500/10 text-emerald-700',
        'amber' => 'bg-amber-500/10 text-amber-700',
        'rose' => 'bg-rose-500/10 text-rose-700',
    ];
?>

<?php
    $bgMap = [
        'cyan' => 'from-soft-blue to-soft-blue/80',
        'emerald' => 'from-soft-green to-soft-green/80',
        'amber' => 'from-soft-yellow to-soft-yellow/80',
        'rose' => 'from-soft-pink to-soft-pink/80',
    ];
    $shade = $bgMap[$tone] ?? $bgMap['cyan'];
?>

<div class="rounded-3xl border border-card-border bg-card-lavender p-5 shadow-neumorph dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-secondary-text"><?php echo e(__($label)); ?></p>
            <p class="mt-3 text-3xl font-extrabold text-primary-text"><?php echo e($value); ?></p>
        </div>

        <span class="inline-flex h-14 w-14 items-center justify-center rounded-3xl bg-gradient-to-br <?php echo e($shade); ?> text-primary-text dark:from-slate-700 dark:to-slate-900 dark:text-slate-100 shadow-soft">
            <i data-lucide="<?php echo e($icon); ?>" class="h-6 w-6"></i>
        </span>
    </div>
</div>
<?php /**PATH D:\SynologyDrive\systems\Feedback_Management_System\resources\views/components/admin/stat-card.blade.php ENDPATH**/ ?>