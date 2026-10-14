<button <?php echo e($attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 rounded-2xl border border-transparent bg-rose-600 px-5 py-3 text-sm font-semibold text-white shadow-soft transition duration-150 hover:bg-rose-500 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 active:translate-y-px disabled:cursor-not-allowed disabled:opacity-60'])); ?>>
    <?php echo e(__(trim(strip_tags($slot)))); ?>

</button>
<?php /**PATH D:\SynologyDrive\systems\Feedback_Management_System\resources\views/components/danger-button.blade.php ENDPATH**/ ?>