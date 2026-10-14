<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['user']));

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

foreach (array_filter((['user']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $hour = now()->hour;
    if ($hour < 12) {
        $greeting = __('Good Morning');
        $emoji = "\u{1F324}\u{FE0F}";
    } elseif ($hour < 17) {
        $greeting = __('Good Afternoon');
        $emoji = "\u{2600}\u{FE0F}";
    } else {
        $greeting = __('Good Evening');
        $emoji = "\u{1F319}";
    }
?>

<div class="rounded-3xl border border-card-border bg-card-lavender p-6 shadow-neumorph text-primary-text dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100">
    <div class="flex items-center gap-6">
        <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-full bg-white p-2 ring-4 ring-white/60">
            <img src="<?php echo e(asset('images/feedback-logo.png')); ?>" alt="GLEDCO Feedback Management System Logo" class="h-full w-full object-contain" />
        </div>

        <div class="flex-1">
            <h2 class="text-2xl font-extrabold leading-tight text-slate-900"><?php echo e($greeting); ?>, <?php echo e(explode(' ', $user->name)[0]); ?>! <span class="ml-2"><?php echo e($emoji); ?></span></h2>
            <p class="mt-1 text-sm text-secondary-text"><?php echo e(__("Here's the latest summary of complaints and feedback across your branches.")); ?></p>
        </div>

        <div class="ml-auto space-y-2 text-right">
            <button class="inline-flex items-center gap-2 rounded-full bg-purple-button px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-purple-hover dark:bg-purple-button/90 dark:hover:bg-purple-hover/90"><?php echo e(__('Create Complaint')); ?></button>
            <button class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/10 px-4 py-2 text-sm font-medium text-primary-text transition hover:brightness-105"><?php echo e(__('Export')); ?></button>
        </div>
    </div>
</div>
<?php /**PATH D:\SynologyDrive\systems\Feedback_Management_System\resources\views/components/admin/welcome-banner.blade.php ENDPATH**/ ?>