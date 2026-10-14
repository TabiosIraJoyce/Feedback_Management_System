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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.page-header','data' => ['title' => $complaint->subject,'description' => $complaint->reference_no.' - '.$complaint->branch->name,'icon' => 'file-warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($complaint->subject),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($complaint->reference_no.' - '.$complaint->branch->name),'icon' => 'file-warning']); ?>
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

    <div class="grid gap-6 xl:grid-cols-[1.3fr,0.7fr]">
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-950">Complaint Details</h3>
            <dl class="mt-5 grid gap-4 md:grid-cols-2">
                <div><dt class="text-sm font-medium text-slate-500">Customer</dt><dd class="mt-1 text-sm text-slate-900"><?php echo e($complaint->customer_name); ?></dd></div>
                <div><dt class="text-sm font-medium text-slate-500">Email</dt><dd class="mt-1 text-sm text-slate-900"><?php echo e($complaint->customer_email ?? 'N/A'); ?></dd></div>
                <div><dt class="text-sm font-medium text-slate-500">Phone</dt><dd class="mt-1 text-sm text-slate-900"><?php echo e($complaint->customer_phone ?? 'N/A'); ?></dd></div>
                <div><dt class="text-sm font-medium text-slate-500">Priority</dt><dd class="mt-1 text-sm text-slate-900"><?php echo e(ucfirst($complaint->priority)); ?></dd></div>
                <div><dt class="text-sm font-medium text-slate-500">Department</dt><dd class="mt-1 text-sm text-slate-900"><?php echo e($complaint->department?->name ?? 'Unassigned'); ?></dd></div>
                <div><dt class="text-sm font-medium text-slate-500">Assigned To</dt><dd class="mt-1 text-sm text-slate-900"><?php echo e($complaint->assignee?->name ?? 'Unassigned'); ?></dd></div>
                <div><dt class="text-sm font-medium text-slate-500">Due Date</dt><dd class="mt-1 text-sm text-slate-900"><?php echo e($complaint->due_at?->format('M d, Y h:i A') ?? 'Not set'); ?></dd></div>
                <div><dt class="text-sm font-medium text-slate-500">Public Reopen</dt><dd class="mt-1 text-sm text-slate-900"><?php echo e($complaint->allow_public_reopen ? 'Allowed' : 'Disabled'); ?></dd></div>
            </dl>

            <div class="mt-6 rounded-2xl bg-slate-50 p-5 text-sm leading-7 text-slate-700">
                <?php echo e($complaint->message); ?>

            </div>

            <div class="mt-6 rounded-2xl bg-cyan-50 p-5 text-sm leading-7 text-slate-700">
                <p class="font-semibold text-slate-900">Public Resolution Summary</p>
                <p class="mt-2"><?php echo e($complaint->resolution_summary ?: 'No public summary added yet.'); ?></p>
            </div>

            <div class="mt-6">
                <h4 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Attachments</h4>
                <div class="mt-4">
                    <?php if (isset($component)) { $__componentOriginal7467de49c876471f32e800acec04270a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7467de49c876471f32e800acec04270a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.attachments.list','data' => ['attachments' => $complaint->attachments,'canDelete' => auth()->user()->can('update', $complaint)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('attachments.list'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['attachments' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($complaint->attachments),'can-delete' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user()->can('update', $complaint))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7467de49c876471f32e800acec04270a)): ?>
<?php $attributes = $__attributesOriginal7467de49c876471f32e800acec04270a; ?>
<?php unset($__attributesOriginal7467de49c876471f32e800acec04270a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7467de49c876471f32e800acec04270a)): ?>
<?php $component = $__componentOriginal7467de49c876471f32e800acec04270a; ?>
<?php unset($__componentOriginal7467de49c876471f32e800acec04270a); ?>
<?php endif; ?>
                </div>
            </div>
        </section>

        <aside class="space-y-4">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('assign complaints')): ?>
                <form method="POST" action="<?php echo e(route('complaints.assign', $complaint)); ?>" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <h3 class="text-lg font-semibold text-slate-950">Assign Complaint</h3>
                    <select name="assigned_to_user_id" class="mt-4 block w-full rounded-xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                        <?php $__currentLoopData = $assignableUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php if($complaint->assigned_to_user_id === $user->id): echo 'selected'; endif; ?>><?php echo e($user->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['class' => 'mt-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-4']); ?>Assign <?php echo $__env->renderComponent(); ?>
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
            <?php endif; ?>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-950">Actions</h3>
                <div class="mt-4 flex flex-wrap gap-3">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $complaint)): ?>
                        <a href="<?php echo e(route('complaints.edit', $complaint)); ?>" class="rounded-xl border border-cyan-200 bg-cyan-50 px-4 py-2 text-sm font-medium text-cyan-700">Edit</a>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('close complaints')): ?>
                        <form method="POST" action="<?php echo e(route('complaints.close', $complaint)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Close <?php echo $__env->renderComponent(); ?>
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
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reopen complaints')): ?>
                        <form method="POST" action="<?php echo e(route('complaints.reopen', $complaint)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <?php if (isset($component)) { $__componentOriginal3b0e04e43cf890250cc4d85cff4d94af = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.secondary-button','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('secondary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Reopen <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af)): ?>
<?php $attributes = $__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af; ?>
<?php unset($__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b0e04e43cf890250cc4d85cff4d94af)): ?>
<?php $component = $__componentOriginal3b0e04e43cf890250cc4d85cff4d94af; ?>
<?php unset($__componentOriginal3b0e04e43cf890250cc4d85cff4d94af); ?>
<?php endif; ?>
                        </form>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $complaint)): ?>
                        <form method="POST" action="<?php echo e(route('complaints.destroy', $complaint)); ?>" onsubmit="return confirm('Archive this complaint?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <?php if (isset($component)) { $__componentOriginal656e8c5ea4d9a4fa173298297bfe3f11 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal656e8c5ea4d9a4fa173298297bfe3f11 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.danger-button','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('danger-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Archive <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal656e8c5ea4d9a4fa173298297bfe3f11)): ?>
<?php $attributes = $__attributesOriginal656e8c5ea4d9a4fa173298297bfe3f11; ?>
<?php unset($__attributesOriginal656e8c5ea4d9a4fa173298297bfe3f11); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal656e8c5ea4d9a4fa173298297bfe3f11)): ?>
<?php $component = $__componentOriginal656e8c5ea4d9a4fa173298297bfe3f11; ?>
<?php unset($__componentOriginal656e8c5ea4d9a4fa173298297bfe3f11); ?>
<?php endif; ?>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </aside>
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
<?php /**PATH D:\SynologyDrive\systems\Feedback_Management_System\resources\views/complaints/show.blade.php ENDPATH**/ ?>