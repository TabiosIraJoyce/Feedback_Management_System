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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.page-header','data' => ['title' => $feedback->subject,'description' => ucfirst($feedback->type) . ' feedback','icon' => 'message-square-more']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($feedback->subject),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(ucfirst($feedback->type) . ' feedback'),'icon' => 'message-square-more']); ?>
            <?php if (isset($component)) { $__componentOriginal72ffe10338c4ec71bdf1582010227fb9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal72ffe10338c4ec71bdf1582010227fb9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.status-badge','data' => ['value' => $feedback->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($feedback->status)]); ?>
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

        
        
        

        <section
            class="rounded-3xl border border-slate-200
                   bg-white p-6 shadow-sm"
        >

            <h3 class="text-lg font-semibold text-slate-950">
                Feedback Details
            </h3>


            <dl class="mt-5 grid gap-4 md:grid-cols-2">

                
                <div>
                    <dt class="text-sm font-medium text-slate-500">
                        Name
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        <?php echo e($feedback->name ?: 'Anonymous'); ?>

                    </dd>
                </div>


                
                <div>
                    <dt class="text-sm font-medium text-slate-500">
                        Email
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        <?php echo e($feedback->email ?? 'N/A'); ?>

                    </dd>
                </div>


                
                <div>
                    <dt class="text-sm font-medium text-slate-500">
                        Contact Number
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        <?php echo e($feedback->phone ?? 'N/A'); ?>

                    </dd>
                </div>


                
                <div>
                    <dt class="text-sm font-medium text-slate-500">
                        Age
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        <?php echo e($feedback->age ?? 'N/A'); ?>

                    </dd>
                </div>


                
                <div>
                    <dt class="text-sm font-medium text-slate-500">
                        Type
                    </dt>

                    <dd class="mt-1 text-sm font-semibold text-slate-900">
                        <?php echo e(ucfirst($feedback->type)); ?>

                    </dd>
                </div>


                
                <div>
                    <dt class="text-sm font-medium text-slate-500">
                        Branch
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        <?php echo e($feedback->branch?->name ?? 'No branch'); ?>

                    </dd>
                </div>


                
                <div>
                    <dt class="text-sm font-medium text-slate-500">
                        Department
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        <?php echo e($feedback->department?->name ?? 'N/A'); ?>

                    </dd>
                </div>


                
                <div>
                    <dt class="text-sm font-medium text-slate-500">
                        Category
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        <?php echo e($feedback->category
                            ? ucfirst($feedback->category)
                            : 'N/A'); ?>

                    </dd>
                </div>


                
                <div>
                    <dt class="text-sm font-medium text-slate-500">
                        Submitted By
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        <?php echo e($feedback->user?->name ?? 'Anonymous'); ?>

                    </dd>
                </div>

            </dl>


            
            
            

            <?php if($feedback->type === 'customer'): ?>

                <div class="mt-8 border-t border-slate-200 pt-6">

                    <h4 class="text-lg font-semibold text-slate-950">
                        Customer Satisfaction
                    </h4>


                    
                    <div class="mt-5">

                        <p class="text-sm font-medium text-slate-500">
                            Experience
                        </p>

                        <div class="mt-2">

                            <?php if($feedback->sentiment === 'positive'): ?>

                                <span
                                    class="inline-flex items-center gap-2
                                           rounded-xl bg-green-50
                                           px-4 py-2 text-sm font-semibold
                                           text-green-700"
                                >
                                    👍 Good
                                </span>

                            <?php elseif($feedback->sentiment === 'negative'): ?>

                                <span
                                    class="inline-flex items-center gap-2
                                           rounded-xl bg-red-50
                                           px-4 py-2 text-sm font-semibold
                                           text-red-700"
                                >
                                    👎 Needs Improvement
                                </span>

                            <?php else: ?>

                                <span class="text-sm text-slate-400">
                                    No response
                                </span>

                            <?php endif; ?>

                        </div>

                    </div>


                    
                    <div class="mt-5">

                        <p class="text-sm font-medium text-slate-500">
                            Satisfaction Rating
                        </p>


                        <?php if($feedback->rating): ?>

                            <div class="mt-2 flex items-center gap-1">

                                <?php for($i = 1; $i <= 5; $i++): ?>

                                    <span
                                        class="text-2xl
                                        <?php echo e($i <= $feedback->rating
                                            ? 'text-yellow-400'
                                            : 'text-slate-300'); ?>"
                                    >
                                        ★
                                    </span>

                                <?php endfor; ?>

                                <span class="ml-2 text-sm text-slate-500">
                                    <?php echo e($feedback->rating); ?>/5
                                </span>

                            </div>

                        <?php else: ?>

                            <p class="mt-2 text-sm text-slate-400">
                                No rating provided
                            </p>

                        <?php endif; ?>

                    </div>

                </div>

            <?php endif; ?>


            
            
            

            <div class="mt-8">

                <h4 class="text-lg font-semibold text-slate-950">

                    <?php if($feedback->type === 'customer'): ?>
                        Feedback
                    <?php else: ?>
                        Description
                    <?php endif; ?>

                </h4>


                <div
                    class="mt-4 rounded-2xl bg-slate-50
                           p-5 text-sm leading-7 text-slate-700"
                >
                    <?php echo e($feedback->message); ?>

                </div>

            </div>


            
            
            

            <div class="mt-8">

                <h4
                    class="text-lg font-semibold
                           text-slate-950 dark:text-slate-100"
                >
                    Attachments
                </h4>

                <div class="mt-4">

                    <?php if (isset($component)) { $__componentOriginal7467de49c876471f32e800acec04270a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7467de49c876471f32e800acec04270a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.attachments.list','data' => ['attachments' => $feedback->attachments,'canDelete' => auth()->user()->can(
                            'update',
                            $feedback
                        )]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('attachments.list'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['attachments' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($feedback->attachments),'can-delete' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user()->can(
                            'update',
                            $feedback
                        ))]); ?>
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


        
        
        

        <aside
            class="rounded-3xl border border-slate-200
                   bg-white p-6 shadow-sm"
        >

            <h3 class="text-lg font-semibold text-slate-950">
                Actions
            </h3>


            <div class="mt-4 flex flex-wrap gap-3">


                
                
                

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $feedback)): ?>

                    <a
                        href="<?php echo e(route(
                            'feedback.edit',
                            ['feedback' => $feedback->id ?? request()->route('feedback')]
                        )); ?>"
                        class="rounded-xl border border-cyan-200
                               bg-cyan-50 px-4 py-2
                               text-sm font-medium text-cyan-700
                               transition hover:bg-cyan-100"
                    >
                        Edit
                    </a>

                <?php endif; ?>


                
                
                

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $feedback)): ?>

                    <form
                        method="POST"
                        action="<?php echo e(route(
                            'feedback.destroy',
                            ['feedback' => $feedback->id ?? request()->route('feedback')]
                        )); ?>"
                        onsubmit="return confirm(
                            'Archive this feedback?'
                        )"
                    >

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
<?php $component->withAttributes([]); ?>
                            Archive
                         <?php echo $__env->renderComponent(); ?>
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
<?php endif; ?><?php /**PATH D:\SynologyDrive\systems\Feedback_Management_System\resources\views/feedback/show.blade.php ENDPATH**/ ?>