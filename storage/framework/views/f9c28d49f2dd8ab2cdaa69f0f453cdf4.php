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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.page-header','data' => ['title' => 'Employee Feedback','description' => 'Track internal concerns, incident reports, and suggestions with live workflow visibility.','icon' => 'messages-square']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Employee Feedback','description' => 'Track internal concerns, incident reports, and suggestions with live workflow visibility.','icon' => 'messages-square']); ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\EmployeeFeedback::class)): ?>
                <a href="<?php echo e(route('employee-feedback.create')); ?>" class="inline-flex items-center gap-2 rounded-2xl bg-cyan-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    New Internal Feedback
                </a>
            <?php endif; ?>
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

    <div x-data="employeeFeedbackRealtime(<?php echo e(auth()->id()); ?>)" x-init="init()" class="space-y-6">
        <template x-if="message">
            <div class="rounded-3xl border border-cyan-200 bg-cyan-50 p-4 text-sm text-cyan-800 dark:border-cyan-500/30 dark:bg-cyan-500/10 dark:text-cyan-200" x-text="message"></div>
        </template>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <?php if (isset($component)) { $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stat-card','data' => ['label' => 'Total Reports','value' => $overview['total'],'icon' => 'files','tone' => 'rose']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Total Reports','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($overview['total']),'icon' => 'files','tone' => 'rose']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stat-card','data' => ['label' => 'Pending','value' => $overview['pending'],'icon' => 'clock','tone' => 'amber']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Pending','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($overview['pending']),'icon' => 'clock','tone' => 'amber']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stat-card','data' => ['label' => 'In Progress','value' => $overview['in_progress'],'icon' => 'loader-circle','tone' => 'cyan']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'In Progress','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($overview['in_progress']),'icon' => 'loader-circle','tone' => 'cyan']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stat-card','data' => ['label' => 'Resolved','value' => $overview['resolved'],'icon' => 'badge-check','tone' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Resolved','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($overview['resolved']),'icon' => 'badge-check','tone' => 'emerald']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stat-card','data' => ['label' => 'Incidents','value' => $overview['incidents'],'icon' => 'triangle-alert','tone' => 'rose']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Incidents','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($overview['incidents']),'icon' => 'triangle-alert','tone' => 'rose']); ?>
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
        </div>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <form method="GET" action="<?php echo e(route('employee-feedback.index')); ?>" class="grid gap-4 lg:grid-cols-5">
                <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['type' => 'search','name' => 'search','placeholder' => 'Search reference, subject, or description','value' => ''.e($filters['search']).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'search','name' => 'search','placeholder' => 'Search reference, subject, or description','value' => ''.e($filters['search']).'']); ?>
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

                <select name="branch_id" class="rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                    <option value="">All branches</option>
                    <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($branch->id); ?>" <?php if((string) $filters['branch_id'] === (string) $branch->id): echo 'selected'; endif; ?>><?php echo e($branch->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <select name="status" class="rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                    <option value="">All statuses</option>
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>" <?php if($filters['status'] === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <select name="category" class="rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                    <option value="">All categories</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>" <?php if($filters['category'] === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <select name="priority" class="rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                    <option value="">All priorities</option>
                    <?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>" <?php if($filters['priority'] === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </form>
        </section>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
            <section class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $feedbackEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div class="space-y-3">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100"><?php echo e($entry->subject); ?></h3>
                                    <?php if (isset($component)) { $__componentOriginal72ffe10338c4ec71bdf1582010227fb9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal72ffe10338c4ec71bdf1582010227fb9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.status-badge','data' => ['value' => $entry->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($entry->status)]); ?>
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
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700 dark:bg-slate-900 dark:text-slate-300"><?php echo e(str_replace('_', ' ', $entry->priority)); ?></span>
                                </div>
                                <p class="text-sm text-slate-500 dark:text-slate-400"><?php echo e($entry->reference_no); ?> | <?php echo e($categories[$entry->category] ?? $entry->category); ?> | <?php echo e($entry->department?->name ?? 'No department'); ?></p>
                                <p class="text-sm text-slate-600 dark:text-slate-300"><?php echo e(\Illuminate\Support\Str::limit($entry->description, 180)); ?></p>
                                <div class="flex flex-wrap gap-3 text-xs uppercase tracking-wide text-slate-400">
                                    <span>Submitted by <?php echo e($entry->display_name); ?></span>
                                    <span><?php echo e($entry->created_at?->format('M d, Y h:i A')); ?></span>
                                    <span>Reopened <?php echo e($entry->reopened_count); ?>x</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <a href="<?php echo e(route('employee-feedback.show', $entry)); ?>" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 dark:border-slate-700 dark:text-slate-200">View</a>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $entry)): ?>
                                    <a href="<?php echo e(route('employee-feedback.edit', $entry)); ?>" class="rounded-xl border border-cyan-200 bg-cyan-50 px-4 py-2 text-sm font-medium text-cyan-700 dark:border-cyan-500/30 dark:bg-cyan-500/10 dark:text-cyan-200">Edit</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-400">
                        No employee feedback entries matched the selected filters.
                    </div>
                <?php endif; ?>

                <?php echo e($feedbackEntries->links()); ?>

            </section>

            <aside class="space-y-6">
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Status Overview</h3>
                    <div class="mt-4 space-y-3">
                        <?php $__currentLoopData = $statusDistribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-sm font-medium text-slate-900 dark:text-slate-100"><?php echo e($statuses[$item['status']] ?? $item['status']); ?></span>
                                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-200"><?php echo e($item['total']); ?></span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Category Breakdown</h3>
                    <div class="mt-4 space-y-3">
                        <?php $__currentLoopData = $categoryDistribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-sm font-medium text-slate-900 dark:text-slate-100"><?php echo e($categories[$item['category']] ?? $item['category']); ?></span>
                                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-200"><?php echo e($item['total']); ?></span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </section>
            </aside>
        </div>
    </div>

    <script>
        function employeeFeedbackRealtime(userId) {
            return {
                message: '',
                init() {
                    if (!window.Echo || !userId) {
                        return;
                    }

                    window.Echo.private(`App.Models.User.${userId}`)
                        .notification((notification) => {
                            if (!notification.employee_feedback_id) {
                                return;
                            }

                            this.message = notification.message ?? 'Employee feedback updated.';
                            setTimeout(() => window.location.reload(), 1200);
                        });
                },
            }
        }
    </script>
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
<?php /**PATH D:\SynologyDrive\systems\Feedback_Management_System\resources\views/employee-feedback/index.blade.php ENDPATH**/ ?>