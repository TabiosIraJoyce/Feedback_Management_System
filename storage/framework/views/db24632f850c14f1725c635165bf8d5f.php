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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.page-header','data' => ['title' => 'Form Builder','description' => 'Manage public branch forms and the fields that collect customer feedback.','icon' => 'layout-grid']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Form Builder','description' => 'Manage public branch forms and the fields that collect customer feedback.','icon' => 'layout-grid']); ?>
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
        <?php if(session('status')): ?>
            <div class="rounded-3xl border border-cyan-200 bg-cyan-50 p-4 text-sm text-cyan-900">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <div class="grid gap-6 lg:grid-cols-[1.1fr,0.9fr]">
            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Form catalog</h2>
                            <p class="mt-2 text-sm text-slate-500">View and manage feedback forms for branches that use the public QR submission flow.</p>
                        </div>
                        <a href="<?php echo e(route('admin.forms.create')); ?>" class="inline-flex items-center gap-2 rounded-3xl bg-cyan-500 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-400">
                            Create new form
                        </a>
                    </div>
                </div>

                <?php $__currentLoopData = $forms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $form): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-slate-900"><?php echo e($form->name); ?></h3>
                                <p class="mt-2 text-sm text-slate-500"><?php echo e($form->description ?? 'No description provided.'); ?></p>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 text-sm">
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-700">Branch: <?php echo e($form->branch?->name ?? 'Unassigned'); ?></span>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-700">Fields: <?php echo e($form->fields->count()); ?></span>
                                <span class="rounded-full <?php echo e($form->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'); ?> px-3 py-1"><?php echo e($form->is_active ? 'Published' : 'Draft'); ?></span>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            <?php $__currentLoopData = $form->fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700"><?php echo e($field->label); ?> (<?php echo e($field->type); ?>)</span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="<?php echo e(route('admin.forms.edit', $form)); ?>" class="inline-flex items-center gap-2 rounded-3xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-900 hover:bg-slate-50">Edit</a>
                            <form method="POST" action="<?php echo e(route('admin.forms.destroy', $form)); ?>" onsubmit="return confirm('Delete this form?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="inline-flex items-center gap-2 rounded-3xl bg-rose-500 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-400">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">How it works</h3>
                    <p class="mt-3 text-sm text-slate-500">Each public form is linked to a branch and will appear on the branch feedback page. Use this builder to define the fields your customers see after scanning a branch QR code.</p>
                </div>
            </div>
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
<?php /**PATH D:\SynologyDrive\systems\Feedback_Management_System\resources\views/admin/forms/index.blade.php ENDPATH**/ ?>