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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.page-header','data' => ['title' => 'Edit Employee Feedback','description' => 'Update the feedback details before the review workflow advances.','icon' => 'file-pen-line']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Edit Employee Feedback','description' => 'Update the feedback details before the review workflow advances.','icon' => 'file-pen-line']); ?>
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

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <form method="POST" action="<?php echo e(route('employee-feedback.update', $feedback)); ?>" enctype="multipart/form-data" class="space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <?php if (isset($component)) { $__componentOriginal402478bdcb9e97c096f0d400a5bcfb13 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal402478bdcb9e97c096f0d400a5bcfb13 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.employee-feedback.form-fields','data' => ['feedback' => $feedback,'departments' => $departments,'categories' => $categories,'priorities' => $priorities]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('employee-feedback.form-fields'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['feedback' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($feedback),'departments' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($departments),'categories' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categories),'priorities' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($priorities)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal402478bdcb9e97c096f0d400a5bcfb13)): ?>
<?php $attributes = $__attributesOriginal402478bdcb9e97c096f0d400a5bcfb13; ?>
<?php unset($__attributesOriginal402478bdcb9e97c096f0d400a5bcfb13); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal402478bdcb9e97c096f0d400a5bcfb13)): ?>
<?php $component = $__componentOriginal402478bdcb9e97c096f0d400a5bcfb13; ?>
<?php unset($__componentOriginal402478bdcb9e97c096f0d400a5bcfb13); ?>
<?php endif; ?>
            <div class="flex flex-wrap gap-3">
                <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>Save Changes <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
                <a href="<?php echo e(route('employee-feedback.show', $feedback)); ?>" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">Back</a>
            </div>
        </form>
    </section>
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
<?php /**PATH D:\SynologyDrive\systems\Feedback_Management_System\resources\views/employee-feedback/edit.blade.php ENDPATH**/ ?>