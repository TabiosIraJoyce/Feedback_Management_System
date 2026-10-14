<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <?php if (isset($component)) { $__componentOriginalcb19cb35a534439097b02b8af91726ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcb19cb35a534439097b02b8af91726ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.page-header','data' => ['title' => 'QR Feedback Generator','description' => 'Manage branch and department QR feedback entry points, downloads, print layouts, and scan analytics.','icon' => 'qr-code']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'QR Feedback Generator','description' => 'Manage branch and department QR feedback entry points, downloads, print layouts, and scan analytics.','icon' => 'qr-code']); ?>
            <a href="<?php echo e(route('admin.qr.index')); ?>" class="inline-flex items-center gap-2 rounded-2xl bg-cyan-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700">
                <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                Refresh QR Records
            </a>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcb19cb35a534439097b02b8af91726ee)): ?>
<?php $attributes = $__attributesOriginalcb19cb35a534439097b02b8af91726ee; ?>
<?php unset($__attributesOriginalcb19cb35a534439097b02b8af91726ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcb19cb35a534439097b02b8af91726ee)): ?>
<?php $component = $__componentOriginalcb19cb35a534439097b02b8af91726ee; ?>
<?php unset($__componentOriginalcb19cb35a534439097b02b8af91726ee); ?>
<?php endif; ?>
     <?php $__env->endSlot(); ?>

    <div class="space-y-6">
        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <?php if (isset($component)) { $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stat-card','data' => ['label' => 'Total QR Codes','value' => $stats['total'],'icon' => 'qr-code','tone' => 'rose']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Total QR Codes','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stats['total']),'icon' => 'qr-code','tone' => 'rose']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $attributes = $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $component = $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stat-card','data' => ['label' => 'Active QR Codes','value' => $stats['active'],'icon' => 'shield-check','tone' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Active QR Codes','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stats['active']),'icon' => 'shield-check','tone' => 'emerald']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $attributes = $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $component = $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stat-card','data' => ['label' => 'Total Scans','value' => $stats['scans'],'icon' => 'scan-line','tone' => 'amber']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Total Scans','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stats['scans']),'icon' => 'scan-line','tone' => 'amber']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $attributes = $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $component = $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stat-card','data' => ['label' => 'Today Scans','value' => $stats['today_scans'],'icon' => 'activity','tone' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Today Scans','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stats['today_scans']),'icon' => 'activity','tone' => 'emerald']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $attributes = $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $component = $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stat-card','data' => ['label' => 'Downloads','value' => $stats['downloads'],'icon' => 'download','tone' => 'rose']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Downloads','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stats['downloads']),'icon' => 'download','tone' => 'rose']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $attributes = $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $component = $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
        </section>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <form method="GET" action="<?php echo e(route('admin.qr.index')); ?>" class="grid gap-3 xl:grid-cols-[minmax(0,1fr),220px,180px,180px,auto]">
                <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['type' => 'search','name' => 'search','placeholder' => 'Search code, path, branch, or department','class' => 'w-full','value' => ''.e(request('search')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'search','name' => 'search','placeholder' => 'Search code, path, branch, or department','class' => 'w-full','value' => ''.e(request('search')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>

                <select name="branch" class="rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                    <option value="">All branches</option>
                    <?php $__currentLoopData = $branchOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($branch->id); ?>" <?php if(request('branch') == $branch->id): echo 'selected'; endif; ?>><?php echo e($branch->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <select name="type" class="rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                    <option value="all" <?php if($type === 'all'): echo 'selected'; endif; ?>>All types</option>
                    <option value="branch" <?php if($type === 'branch'): echo 'selected'; endif; ?>>Branch QR</option>
                    <option value="department" <?php if($type === 'department'): echo 'selected'; endif; ?>>Department QR</option>
                </select>

                <select name="status" class="rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                    <option value="all" <?php if($status === 'all'): echo 'selected'; endif; ?>>All statuses</option>
                    <option value="active" <?php if($status === 'active'): echo 'selected'; endif; ?>>Active</option>
                    <option value="inactive" <?php if($status === 'inactive'): echo 'selected'; endif; ?>>Inactive</option>
                </select>

                <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Apply Filters <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
            </form>
        </section>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
            <section class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $qrCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qrCode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                        <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">
                            <div class="flex gap-4">
                                <div class="rounded-3xl border border-slate-200 bg-white p-3 dark:border-slate-800 dark:bg-slate-900">
                                    <img src="<?php echo e($qrCode->svg_preview_url); ?>" alt="<?php echo e($qrCode->code); ?> QR code" class="h-32 w-32" />
                                </div>

                                <div class="space-y-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100"><?php echo e($qrCode->code); ?></h3>
                                        <?php if (isset($component)) { $__componentOriginal72ffe10338c4ec71bdf1582010227fb9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal72ffe10338c4ec71bdf1582010227fb9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.status-badge','data' => ['value' => $qrCode->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($qrCode->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal72ffe10338c4ec71bdf1582010227fb9)): ?>
<?php $attributes = $__attributesOriginal72ffe10338c4ec71bdf1582010227fb9; ?>
<?php unset($__attributesOriginal72ffe10338c4ec71bdf1582010227fb9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal72ffe10338c4ec71bdf1582010227fb9)): ?>
<?php $component = $__componentOriginal72ffe10338c4ec71bdf1582010227fb9; ?>
<?php unset($__componentOriginal72ffe10338c4ec71bdf1582010227fb9); ?>
<?php endif; ?>
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700 dark:bg-slate-900 dark:text-slate-300"><?php echo e($qrCode->type); ?></span>
                                    </div>

                                    <div class="text-sm text-slate-500 dark:text-slate-400">
                                        <p><?php echo e($qrCode->branch?->name); ?></p>
                                        <?php if($qrCode->department): ?>
                                            <p><?php echo e($qrCode->department->name); ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3 text-sm dark:border-slate-800 dark:bg-slate-900">
                                        <div class="font-semibold text-slate-900 dark:text-slate-100">Public path</div>
                                        <a href="<?php echo e($qrCode->public_url); ?>" target="_blank" rel="noreferrer" class="mt-1 block break-all text-cyan-600 hover:text-cyan-500 dark:text-cyan-300"><?php echo e($qrCode->public_url); ?></a>
                                    </div>

                                    <div class="grid gap-3 sm:grid-cols-3">
                                        <div class="rounded-2xl bg-slate-50 p-3 text-sm text-slate-600 dark:bg-slate-900 dark:text-slate-300">Scans: <span class="font-semibold text-slate-900 dark:text-slate-100"><?php echo e($qrCode->scan_count); ?></span></div>
                                        <div class="rounded-2xl bg-slate-50 p-3 text-sm text-slate-600 dark:bg-slate-900 dark:text-slate-300">Downloads: <span class="font-semibold text-slate-900 dark:text-slate-100"><?php echo e($qrCode->download_count); ?></span></div>
                                        <div class="rounded-2xl bg-slate-50 p-3 text-sm text-slate-600 dark:bg-slate-900 dark:text-slate-300">Last scan: <span class="font-semibold text-slate-900 dark:text-slate-100"><?php echo e($qrCode->last_scanned_at?->diffForHumans() ?? 'Never'); ?></span></div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3 xl:max-w-[360px] xl:justify-end">
                                <a href="<?php echo e($qrCode->public_url); ?>" target="_blank" rel="noreferrer" class="inline-flex items-center gap-2 rounded-2xl bg-cyan-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700">
                                    <i data-lucide="external-link" class="h-4 w-4"></i>
                                    Open
                                </a>
                                <a href="<?php echo e(route('admin.qr.download', ['qrCode' => $qrCode, 'format' => 'png'])); ?>" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                                    <i data-lucide="image-down" class="h-4 w-4"></i>
                                    PNG
                                </a>
                                <a href="<?php echo e(route('admin.qr.download', ['qrCode' => $qrCode, 'format' => 'svg'])); ?>" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                                    <i data-lucide="file-image" class="h-4 w-4"></i>
                                    SVG
                                </a>
                                <a href="<?php echo e(route('admin.qr.print', $qrCode)); ?>" target="_blank" rel="noreferrer" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                    <i data-lucide="printer" class="h-4 w-4"></i>
                                    Print
                                </a>
                                <form method="POST" action="<?php echo e(route('admin.qr.toggle-status', $qrCode)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                        <i data-lucide="<?php echo e($qrCode->status === 'active' ? 'toggle-right' : 'toggle-left'); ?>" class="h-4 w-4"></i>
                                        <?php echo e($qrCode->status === 'active' ? 'Disable' : 'Enable'); ?>

                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-400">
                        No QR codes matched the selected filters.
                    </div>
                <?php endif; ?>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <?php echo e($qrCodes->withQueryString()->links()); ?>

                </div>
            </section>

            <aside class="space-y-6">
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">7-Day Scan Trend</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Recent QR scan activity across all public feedback links.</p>
                    </div>

                    <div class="space-y-3">
                        <?php $maxTrend = max($trend['values'] ?: [1]); ?>
                        <?php $__currentLoopData = $trend['labels']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <div class="mb-1 flex items-center justify-between text-sm">
                                    <span class="text-slate-600 dark:text-slate-300"><?php echo e($label); ?></span>
                                    <span class="font-semibold text-slate-900 dark:text-slate-100"><?php echo e($trend['values'][$index]); ?></span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-200 dark:bg-slate-800">
                                    <div class="h-2 rounded-full bg-cyan-500" style="width: <?php echo e($maxTrend > 0 ? max(6, round(($trend['values'][$index] / $maxTrend) * 100)) : 0); ?>%;"></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Top Scanned</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Most visited QR entry points.</p>
                    </div>

                    <div class="space-y-3">
                        <?php $__empty_1 = true; $__currentLoopData = $topScanned; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-900 dark:text-slate-100"><?php echo e($item->code); ?></p>
                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400"><?php echo e($item->department?->name ?? $item->branch?->name); ?></p>
                                    </div>
                                    <span class="rounded-full bg-cyan-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-200"><?php echo e($item->scan_count); ?> scans</span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-sm text-slate-500 dark:text-slate-400">No scan data yet.</p>
                        <?php endif; ?>
                    </div>
                </section>
            </aside>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH D:\SynologyDrive\systems\Feedback_Management_System\resources\views/admin/qr/index.blade.php ENDPATH**/ ?>