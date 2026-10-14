<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'attachments',
    'canDelete' => false,
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
    'attachments',
    'canDelete' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="space-y-3">
    <?php $__empty_1 = true; $__currentLoopData = $attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-slate-700 shadow-sm dark:bg-slate-950 dark:text-slate-200">
                            <i data-lucide="<?php echo e($attachment->is_image ? 'image' : ($attachment->is_pdf ? 'file-text' : 'file')); ?>" class="h-4 w-4"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-slate-900 dark:text-slate-100"><?php echo e($attachment->original_name); ?></p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400"><?php echo e(strtoupper($attachment->extension)); ?> | <?php echo e($attachment->human_size); ?> | Uploaded <?php echo e($attachment->created_at->diffForHumans()); ?></p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    <?php if($attachment->is_previewable): ?>
                        <a href="<?php echo e(route('attachments.preview', $attachment)); ?>" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                            <i data-lucide="eye" class="h-3.5 w-3.5"></i>
                            Preview
                        </a>
                    <?php endif; ?>

                    <a href="<?php echo e(route('attachments.download', $attachment)); ?>" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                        <i data-lucide="download" class="h-3.5 w-3.5"></i>
                        Download
                    </a>

                    <?php if($canDelete): ?>
                        <form method="POST" action="<?php echo e(route('attachments.destroy', $attachment)); ?>" onsubmit="return confirm('Delete this attachment?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 transition hover:bg-rose-100 dark:border-rose-900/40 dark:bg-rose-950/30 dark:text-rose-200">
                                <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                Delete
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="rounded-2xl border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">No attachments uploaded yet.</p>
    <?php endif; ?>
</div>
<?php /**PATH D:\SynologyDrive\systems\Feedback_Management_System\resources\views/components/attachments/list.blade.php ENDPATH**/ ?>