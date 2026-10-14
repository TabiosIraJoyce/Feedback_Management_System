<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['value']));

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

foreach (array_filter((['value']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $palette = match ($value) {
        'open', 'submitted' => 'bg-sky-100 text-sky-700',
        'in_progress', 'reviewed' => 'bg-amber-100 text-amber-700',
        'resolved', 'closed' => 'bg-emerald-100 text-emerald-700',
        'reopened' => 'bg-rose-100 text-rose-700',
        'archived' => 'bg-slate-200 text-slate-700',
        default => 'bg-slate-100 text-slate-600',
    };
?>

<span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide <?php echo e($palette); ?>">
    <?php echo e(__(str_replace('_', ' ', $value))); ?>

</span>
<?php /**PATH D:\SynologyDrive\systems\Feedback_Management_System\resources\views/components/admin/status-badge.blade.php ENDPATH**/ ?>