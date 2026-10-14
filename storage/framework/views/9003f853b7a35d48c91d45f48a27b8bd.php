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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.page-header','data' => ['title' => 'Complaint Management','description' => 'Track, assign, close, and review complaints by role and branch scope.','icon' => 'badge-alert']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Complaint Management','description' => 'Track, assign, close, and review complaints by role and branch scope.','icon' => 'badge-alert']); ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('submit complaints')): ?>
                <a href="<?php echo e(route('complaints.create')); ?>" class="inline-flex items-center gap-2 rounded-2xl bg-cyan-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-700">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    New Complaint
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

    <div class="space-y-4">
        <?php $__currentLoopData = $complaints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $complaint): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <h3 class="text-lg font-semibold text-slate-950"><?php echo e($complaint->subject); ?></h3>
                            <?php if (isset($component)) { $__componentOriginal72ffe10338c4ec71bdf1582010227fb9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal72ffe10338c4ec71bdf1582010227fb9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.status-badge','data' => ['value' => $complaint->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($complaint->status)]); ?>
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
                        </div>
                        <p class="mt-2 text-sm text-slate-500"><?php echo e($complaint->reference_no); ?> • <?php echo e($complaint->branch->name); ?> • Priority: <?php echo e(ucfirst($complaint->priority)); ?></p>
                        <p class="mt-3 max-w-3xl text-sm text-slate-600"><?php echo e(\Illuminate\Support\Str::limit($complaint->message, 180)); ?></p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="<?php echo e(route('complaints.show', $complaint)); ?>" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">View</a>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $complaint)): ?>
                            <a href="<?php echo e(route('complaints.edit', $complaint)); ?>" class="rounded-xl border border-cyan-200 bg-cyan-50 px-4 py-2 text-sm font-medium text-cyan-700 hover:bg-cyan-100">Edit</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mt-5 grid gap-4 xl:grid-cols-3">
                    <div class="rounded-2xl bg-slate-50 p-4 text-sm">
                        <p class="font-semibold text-slate-900">Submitter</p>
                        <p class="mt-1 text-slate-600"><?php echo e($complaint->customer_name); ?></p>
                        <p class="text-slate-500"><?php echo e($complaint->customer_email); ?></p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4 text-sm">
                        <p class="font-semibold text-slate-900">Assigned To</p>
                        <p class="mt-1 text-slate-600"><?php echo e($complaint->assignee?->name ?? 'Unassigned'); ?></p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4 text-sm">
                        <p class="font-semibold text-slate-900">Due Date</p>
                        <p class="mt-1 text-slate-600"><?php echo e($complaint->due_at?->format('M d, Y h:i A') ?? 'No deadline set'); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php echo e($complaints->links()); ?>

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
<?php /**PATH D:\SynologyDrive\systems\Feedback_Management_System\resources\views/complaints/index.blade.php ENDPATH**/ ?>