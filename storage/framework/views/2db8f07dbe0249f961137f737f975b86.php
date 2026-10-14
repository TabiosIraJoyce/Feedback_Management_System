<div x-data="hciPreferences(<?php echo \Illuminate\Support\Js::from($hciConfig ?? [])->toHtml() ?>)" x-init="init()" class="fixed bottom-4 right-4 z-[70] max-w-sm">
    <div x-show="firstVisit" x-transition class="mb-3 rounded-3xl border border-cyan-200 bg-white/95 p-4 text-sm text-slate-700 shadow-xl dark:border-slate-800 dark:bg-slate-950/95 dark:text-slate-200">
        <div class="flex items-start justify-between gap-3">
            <div>
                <p class="font-semibold text-slate-900 dark:text-slate-100"><?php echo e(__('hci.toolbar.quick_access')); ?></p>
                <p class="mt-1 text-xs leading-6 text-slate-500 dark:text-slate-400"><?php echo e(__('hci.toolbar.quick_access_help')); ?></p>
            </div>
            <button type="button" @click="dismissTour()" class="rounded-full p-1 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-900">
                <i data-lucide="x" class="h-4 w-4"></i>
            </button>
        </div>
    </div>

    <div x-show="open" x-transition.origin.bottom.right class="mb-3 rounded-[2rem] border border-slate-200 bg-white/95 p-5 shadow-2xl backdrop-blur dark:border-slate-800 dark:bg-slate-950/95">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-sm font-semibold text-slate-950 dark:text-slate-100"><?php echo e(__('hci.toolbar.title')); ?></p>
                <p class="text-xs text-slate-500 dark:text-slate-400"><?php echo e(__('hci.toolbar.subtitle')); ?></p>
            </div>
            <button type="button" @click="open = false" class="rounded-full p-2 text-slate-500 transition hover:bg-slate-100 dark:hover:bg-slate-900">
                <i data-lucide="x" class="h-4 w-4"></i>
            </button>
        </div>

        <div class="mt-5 space-y-4">
            <label class="block text-sm text-slate-700 dark:text-slate-300">
                <?php echo e(__('hci.toolbar.language')); ?>

                <select :value="locale" @change="changeLocale($event.target.value)" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                    <?php $__currentLoopData = ($hciConfig['available_locales'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($locale['value']); ?>" <?php if(($hciConfig['locale'] ?? 'en') === $locale['value']): echo 'selected'; endif; ?>><?php echo e(__($locale['label'])); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </label>

            <div>
                <p class="text-sm font-medium text-slate-700 dark:text-slate-300"><?php echo e(__('hci.toolbar.text_size')); ?></p>
                <div class="mt-2 grid grid-cols-3 gap-2">
                    <button type="button" @click="setTextSize('base')" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm dark:border-slate-800"><?php echo e(__('hci.toolbar.text_normal')); ?></button>
                    <button type="button" @click="setTextSize('large')" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm dark:border-slate-800"><?php echo e(__('hci.toolbar.text_large')); ?></button>
                    <button type="button" @click="setTextSize('x-large')" class="rounded-2xl border border-slate-300 px-3 py-2 text-sm dark:border-slate-800"><?php echo e(__('hci.toolbar.text_xlarge')); ?></button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <button type="button" @click="toggleDarkMode()" class="rounded-2xl border border-slate-300 px-4 py-3 text-sm font-medium dark:border-slate-800">
                    <?php echo e(__('hci.toolbar.dark_mode')); ?>

                </button>
                <button type="button" @click="toggleHighContrast()" class="rounded-2xl border border-slate-300 px-4 py-3 text-sm font-medium dark:border-slate-800">
                    <?php echo e(__('hci.toolbar.high_contrast')); ?>

                </button>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <button type="button" @click="toggleVoiceAssistance()" class="rounded-2xl border border-slate-300 px-4 py-3 text-sm font-medium dark:border-slate-800">
                    <?php echo e(__('hci.toolbar.voice_ready')); ?>

                </button>
                <button type="button" @click="readPage()" class="rounded-2xl border border-cyan-300 bg-cyan-50 px-4 py-3 text-sm font-medium text-cyan-900 dark:border-cyan-900/40 dark:bg-cyan-950/30 dark:text-cyan-100">
                    <?php echo e(__('hci.toolbar.read_page')); ?>

                </button>
            </div>

            <button type="button" @click="stopReading()" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm font-medium dark:border-slate-800">
                <?php echo e(__('hci.toolbar.stop_reading')); ?>

            </button>

            <div class="rounded-2xl border border-dashed border-slate-300 p-3 text-xs leading-6 text-slate-500 dark:border-slate-700 dark:text-slate-400">
                <?php echo e(__('hci.toolbar.keyboard_tip')); ?>

            </div>
        </div>
    </div>

    <button type="button" @click="open = !open" class="inline-flex w-full items-center justify-center gap-3 rounded-full bg-cyan-500 px-5 py-4 text-sm font-semibold text-slate-950 shadow-2xl transition hover:bg-cyan-400">
        <i data-lucide="accessibility" class="h-5 w-5"></i>
        <?php echo e(__('hci.toolbar.open')); ?>

    </button>
</div>
<?php /**PATH D:\SynologyDrive\systems\Feedback_Management_System\resources\views/layouts/partials/hci-toolbar.blade.php ENDPATH**/ ?>