<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" x-data="languageSwitcher({ locale: '<?php echo e(app()->getLocale()); ?>' })" x-init="init()">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <script>
            (() => {
                const defaults = {
                    darkMode: <?php echo json_encode($hciConfig['dark_mode_enabled'] ?? false, 15, 512) ?>,
                    textSize: 'base',
                    highContrast: false,
                    voiceAssistance: false,
                };

                try {
                    const stored = JSON.parse(localStorage.getItem('fms-hci-preferences') || '{}');
                    const preferences = { ...defaults, ...stored };
                    const root = document.documentElement;

                    root.classList.toggle('dark', !!preferences.darkMode);
                    root.classList.toggle('hci-high-contrast', !!preferences.highContrast);
                    root.dataset.textSize = preferences.textSize || 'base';
                    root.dataset.voiceAssistance = preferences.voiceAssistance ? 'on' : 'off';
                } catch (error) {
                    document.documentElement.classList.toggle('dark', !!defaults.darkMode);
                    document.documentElement.dataset.textSize = defaults.textSize;
                }
            })();
        </script>

        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="bg-light-lavender font-sans text-primary-text antialiased transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[80] focus:rounded-full focus:bg-cyan-500 focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-slate-950">
            <?php echo e(__('hci.toolbar.skip_to_content')); ?>

        </a>
        <div id="global-live-region" aria-live="polite" aria-atomic="true" class="sr-only"></div>

        <div class="fixed right-4 top-4 z-[75]">
            <?php if (isset($component)) { $__componentOriginal8d3bff7d7383a45350f7495fc470d934 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d3bff7d7383a45350f7495fc470d934 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.language-switcher','data' => ['compact' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('language-switcher'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['compact' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8d3bff7d7383a45350f7495fc470d934)): ?>
<?php $attributes = $__attributesOriginal8d3bff7d7383a45350f7495fc470d934; ?>
<?php unset($__attributesOriginal8d3bff7d7383a45350f7495fc470d934); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8d3bff7d7383a45350f7495fc470d934)): ?>
<?php $component = $__componentOriginal8d3bff7d7383a45350f7495fc470d934; ?>
<?php unset($__componentOriginal8d3bff7d7383a45350f7495fc470d934); ?>
<?php endif; ?>
        </div>

        <?php if(request()->routeIs('track-complaint.*') || request()->routeIs('feedback.public.*')): ?>
            <main id="main-content">
                <?php echo e($slot); ?>

            </main>
        <?php else: ?>
            <div class="flex min-h-screen flex-col items-center bg-transparent pt-6 sm:justify-center sm:pt-0">
                <div>
                    <a href="/">
                        <?php if (isset($component)) { $__componentOriginal8892e718f3d0d7a916180885c6f012e7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8892e718f3d0d7a916180885c6f012e7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.application-logo','data' => ['class' => 'h-28 w-28']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('application-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-28 w-28']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $attributes = $__attributesOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $component = $__componentOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__componentOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
                    </a>
                </div>

                <main id="main-content" class="mt-6 w-full overflow-hidden rounded-[1.75rem] border border-card-border bg-white/95 px-6 py-5 shadow-neumorph sm:max-w-md dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                    <?php echo e($slot); ?>

                </main>
            </div>
        <?php endif; ?>

        <?php echo $__env->make('layouts.partials.hci-toolbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </body>
</html>
<?php /**PATH D:\SynologyDrive\systems\Feedback_Management_System\resources\views/layouts/guest.blade.php ENDPATH**/ ?>