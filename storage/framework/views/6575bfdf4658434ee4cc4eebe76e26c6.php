<nav
    x-data="{ open: false }"
    class="border-r border-card-border
           bg-[linear-gradient(180deg,#f8fcfb_0%,#e8f4f2_100%)]
           text-slate-900
           lg:w-80
           dark:border-slate-800
           dark:bg-slate-950
           dark:text-slate-100"
>

    
    
    

    <div class="flex items-center justify-between px-5 py-4 lg:px-6">

        <div class="flex items-center gap-3">

            <?php if (isset($component)) { $__componentOriginal8892e718f3d0d7a916180885c6f012e7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8892e718f3d0d7a916180885c6f012e7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.application-logo','data' => ['class' => 'h-12 w-12 rounded-2xl bg-white/80
                       p-1 shadow-soft dark:bg-slate-900/80']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('application-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-12 w-12 rounded-2xl bg-white/80
                       p-1 shadow-soft dark:bg-slate-900/80']); ?>
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

            <div>

                <p
                    class="text-xs font-semibold uppercase
                           tracking-[0.32em]
                           text-slate-700 dark:text-slate-300"
                >
                    GLEDCO
                </p>

                <h1 class="mt-1 text-lg font-semibold">
                    <?php echo e(__('Feedback Management')); ?>

                </h1>

            </div>

        </div>


        
        <button
            @click="open = ! open"
            class="rounded-xl border border-card-border
                   bg-white/80 p-2 text-primary-text shadow-sm
                   lg:hidden
                   dark:border-slate-800
                   dark:bg-slate-900
                   dark:text-slate-200"
        >

            <i
                data-lucide="menu"
                class="h-5 w-5"
            ></i>

        </button>

    </div>


    
    
    

    <div
        :class="{ 'block': open, 'hidden': ! open }"
        class="hidden px-4 pb-6 lg:block lg:px-5"
    >

        
        
        

        <div class="mb-5">

            <?php if (isset($component)) { $__componentOriginal8d3bff7d7383a45350f7495fc470d934 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d3bff7d7383a45350f7495fc470d934 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.language-switcher','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('language-switcher'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
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


        
        
        

        <?php if(auth()->guard()->check()): ?>

            
            
            

            <div
                class="mb-5 hci-panel p-4
                       dark:text-slate-100"
            >

                <p
                    class="text-sm font-semibold
                           text-slate-900
                           dark:text-slate-100"
                >
                    <?php echo e(auth()->user()->name); ?>

                </p>


                <p
                    class="mt-1 text-xs
                           text-slate-700
                           dark:text-slate-400"
                >
                    <?php echo e(auth()->user()->getRoleNames()->implode(', ') ?: __('No role assigned')); ?>

                </p>


                    <p
                        class="mt-2 text-xs
                               text-slate-700
                               dark:text-slate-400"
                    >
                        <?php echo e(auth()->user()->branch?->name ?? __('Unassigned department')); ?>

                    </p>

            </div>


            
            
            

            <div class="space-y-6">


                
                
                

                <div class="space-y-1">

                    <p
                        class="px-4 text-xs font-semibold uppercase
                               tracking-[0.32em]
                               text-slate-500
                               dark:text-slate-400"
                    >
                        <?php echo e(__('Main')); ?>

                    </p>


                    <?php if(
                        auth()->user()->can('view dashboard')
                        ||
                        auth()->user()->can('manage complaints')
                    ): ?>

                        <?php if (isset($component)) { $__componentOriginal25b36b426f30fd196f9d947e60e48c56 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25b36b426f30fd196f9d947e60e48c56 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-link','data' => ['href' => route('dashboard'),'active' => request()->routeIs('dashboard'),'icon' => 'layout-dashboard']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('dashboard')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('dashboard')),'icon' => 'layout-dashboard']); ?>
                            <?php echo e(__('Dashboard')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $attributes = $__attributesOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $component = $__componentOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__componentOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>

                    <?php endif; ?>

                </div>


                
                
                

                <div class="space-y-1">

                    <p
                        class="px-4 text-xs font-semibold uppercase
                               tracking-[0.32em]
                               text-slate-500
                               dark:text-slate-400"
                    >
                        <?php echo e(__('Feedback')); ?>

                    </p>


                    
                    <?php if(
                        auth()->user()->can('track complaints')
                        ||
                        auth()->user()->can('manage complaints')
                    ): ?>

                        <?php if (isset($component)) { $__componentOriginal25b36b426f30fd196f9d947e60e48c56 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25b36b426f30fd196f9d947e60e48c56 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-link','data' => ['href' => route('complaints.index'),'active' => request()->routeIs('complaints.*'),'icon' => 'badge-alert']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('complaints.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('complaints.*')),'icon' => 'badge-alert']); ?>
                            <?php echo e(__('Complaints')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $attributes = $__attributesOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $component = $__componentOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__componentOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>

                    <?php endif; ?>


<?php if(
    auth()->user()->can('submit feedback')
    ||
    auth()->user()->can('manage feedback')
): ?>

    <?php if (isset($component)) { $__componentOriginal25b36b426f30fd196f9d947e60e48c56 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25b36b426f30fd196f9d947e60e48c56 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-link','data' => ['href' => route('feedback.index'),'active' => request()->routeIs('feedback.*'),'icon' => 'messages-square']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('feedback.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('feedback.*')),'icon' => 'messages-square']); ?>
        <?php echo e(__('Feedback')); ?>

     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $attributes = $__attributesOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $component = $__componentOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__componentOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>

    
    <?php if(! auth()->user()->hasRole('Customer')): ?>

        <?php if (isset($component)) { $__componentOriginal25b36b426f30fd196f9d947e60e48c56 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25b36b426f30fd196f9d947e60e48c56 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-link','data' => ['href' => route('employee-feedback.index'),'active' => request()->routeIs('employee-feedback.*'),'icon' => 'message-square-more']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('employee-feedback.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('employee-feedback.*')),'icon' => 'message-square-more']); ?>
            <?php echo e(__('Employee Feedback')); ?>

         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $attributes = $__attributesOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $component = $__componentOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__componentOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>

    <?php endif; ?>

<?php endif; ?>

                
                
                

                <?php if(
                    auth()->user()->can('manage branches')
                ): ?>

                    <div class="space-y-1">

                        <p
                            class="px-4 text-xs font-semibold uppercase
                                   tracking-[0.32em]
                                   text-slate-500
                                   dark:text-slate-400"
                        >
                            <?php echo e(__('Department Management')); ?>

                        </p>


                        
                        <?php if (isset($component)) { $__componentOriginal25b36b426f30fd196f9d947e60e48c56 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25b36b426f30fd196f9d947e60e48c56 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-link','data' => ['href' => route('branches.index'),'active' => request()->routeIs('branches.*'),'icon' => 'building-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('branches.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('branches.*')),'icon' => 'building-2']); ?>
                            <?php echo e(__('Departments')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $attributes = $__attributesOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $component = $__componentOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__componentOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>


                        


                        
                        <?php if (isset($component)) { $__componentOriginal25b36b426f30fd196f9d947e60e48c56 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25b36b426f30fd196f9d947e60e48c56 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-link','data' => ['href' => route('products.index'),'active' => request()->routeIs('products.*'),'icon' => 'box']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('products.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('products.*')),'icon' => 'box']); ?>
                            <?php echo e(__('Products')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $attributes = $__attributesOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $component = $__componentOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__componentOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>


                        

                    </div>

                <?php endif; ?>


                
                
                

                <?php if(
                    auth()->user()->can('manage settings')
                    ||
                    auth()->user()->hasRole('Super Admin')
                ): ?>

                    <div class="space-y-1">

                        <p
                            class="px-4 text-xs font-semibold uppercase
                                   tracking-[0.32em]
                                   text-slate-500
                                   dark:text-slate-400"
                        >
                            <?php echo e(__('Administration')); ?>

                        </p>


                        
                        <?php if(
                            auth()->user()->can('manage settings')
                        ): ?>

                            <?php if (isset($component)) { $__componentOriginal25b36b426f30fd196f9d947e60e48c56 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25b36b426f30fd196f9d947e60e48c56 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-link','data' => ['href' => route('admin.roles.index'),'active' => request()->routeIs('admin.roles.*'),'icon' => 'shield-check']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.roles.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.roles.*')),'icon' => 'shield-check']); ?>
                                <?php echo e(__('Permissions')); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $attributes = $__attributesOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $component = $__componentOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__componentOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>


                            
                            <?php if (isset($component)) { $__componentOriginal25b36b426f30fd196f9d947e60e48c56 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25b36b426f30fd196f9d947e60e48c56 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-link','data' => ['href' => route('admin.settings.index'),'active' => request()->routeIs('admin.settings.*'),'icon' => 'settings']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.settings.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.settings.*')),'icon' => 'settings']); ?>
                                <?php echo e(__('System Settings')); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $attributes = $__attributesOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $component = $__componentOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__componentOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>

                        <?php endif; ?>


                        
                        <?php if(
                            auth()->user()->hasRole('Super Admin')
                        ): ?>

                            <?php if (isset($component)) { $__componentOriginal25b36b426f30fd196f9d947e60e48c56 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25b36b426f30fd196f9d947e60e48c56 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-link','data' => ['href' => route('admin.qr.index'),'active' => request()->routeIs('admin.qr.*'),'icon' => 'qr-code']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.qr.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.qr.*')),'icon' => 'qr-code']); ?>
                                <?php echo e(__('Generate QR Codes')); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $attributes = $__attributesOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $component = $__componentOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__componentOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>

                        <?php endif; ?>

                    </div>

                <?php endif; ?>


                
                
                

                <div class="space-y-1">

                    <p
                        class="px-4 text-xs font-semibold uppercase
                               tracking-[0.32em]
                               text-slate-500
                               dark:text-slate-400"
                    >
                        <?php echo e(__('Utilities')); ?>

                    </p>


                    
                    <?php if(
                        auth()->user()->can('manage reports')
                    ): ?>

                        <?php if (isset($component)) { $__componentOriginal25b36b426f30fd196f9d947e60e48c56 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25b36b426f30fd196f9d947e60e48c56 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-link','data' => ['href' => route('reports.index'),'active' => request()->routeIs('reports.*'),'icon' => 'file-bar-chart']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('reports.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('reports.*')),'icon' => 'file-bar-chart']); ?>
                            <?php echo e(__('Reports & Export')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $attributes = $__attributesOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $component = $__componentOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__componentOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>

                    <?php endif; ?>


                    
                    <?php if(
                        auth()->user()->can('manage users')
                    ): ?>

                        <?php if (isset($component)) { $__componentOriginal25b36b426f30fd196f9d947e60e48c56 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25b36b426f30fd196f9d947e60e48c56 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-link','data' => ['href' => route('admin.users.index'),'active' => request()->routeIs('admin.users.*'),'icon' => 'users-round']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.users.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.users.*')),'icon' => 'users-round']); ?>
                            <?php echo e(__('User Management')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $attributes = $__attributesOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $component = $__componentOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__componentOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>

                    <?php endif; ?>


                    
                    <?php if(
                        auth()->user()->can('manage forms')
                    ): ?>

                        <?php if (isset($component)) { $__componentOriginal25b36b426f30fd196f9d947e60e48c56 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25b36b426f30fd196f9d947e60e48c56 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-link','data' => ['href' => route('admin.forms.index'),'active' => request()->routeIs('admin.forms.*'),'icon' => 'layout-grid']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.forms.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.forms.*')),'icon' => 'layout-grid']); ?>
                            <?php echo e(__('Form Builder')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $attributes = $__attributesOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $component = $__componentOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__componentOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>

                    <?php endif; ?>


                    
                    <?php if (isset($component)) { $__componentOriginal25b36b426f30fd196f9d947e60e48c56 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal25b36b426f30fd196f9d947e60e48c56 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-link','data' => ['href' => route('profile.edit'),'active' => request()->routeIs('profile.*'),'icon' => 'user-cog']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('profile.edit')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('profile.*')),'icon' => 'user-cog']); ?>
                        <?php echo e(__('Profile')); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $attributes = $__attributesOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__attributesOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal25b36b426f30fd196f9d947e60e48c56)): ?>
<?php $component = $__componentOriginal25b36b426f30fd196f9d947e60e48c56; ?>
<?php unset($__componentOriginal25b36b426f30fd196f9d947e60e48c56); ?>
<?php endif; ?>

                </div>

            </div>


            
            
            

            <form
                method="POST"
                action="<?php echo e(route('logout')); ?>"
                class="mt-6"
            >

                <?php echo csrf_field(); ?>

                <button
                    type="submit"
                    class="flex w-full items-center gap-3
                           rounded-2xl border border-rose-200
                           bg-white/80 px-4 py-3
                           text-sm font-semibold
                           text-rose-700 shadow-sm
                           transition hover:bg-rose-50
                           dark:border-rose-900/40
                           dark:bg-rose-950/30
                           dark:text-rose-200"
                >

                    <i
                        data-lucide="log-out"
                        class="h-4 w-4"
                    ></i>

                    <?php echo e(__('Log Out')); ?>


                </button>

            </form>


        <?php else: ?>


            
            
            

            <div
                class="hci-panel p-5
                       dark:text-slate-100"
            >

                <div
                    class="flex items-center gap-3"
                >

                    <div
                        class="flex h-10 w-10 items-center
                               justify-center rounded-xl
                               bg-white shadow-sm
                               dark:bg-slate-900"
                    >

                        <i
                            data-lucide="message-square-heart"
                            class="h-5 w-5 text-primary"
                        ></i>

                    </div>


                    <div>

                        <p
                            class="text-sm font-semibold
                                   text-slate-900
                                   dark:text-slate-100"
                        >
                            <?php echo e(__('Public Feedback')); ?>

                        </p>

                        <p
                            class="mt-1 text-xs
                                   text-slate-600
                                   dark:text-slate-400"
                        >
                            <?php echo e(__('We value your feedback.')); ?>

                        </p>

                    </div>

                </div>


                <div
                    x-data="{
                        items: null,
                        loading: false,
                        error: null,
                        async load() {
                            this.error = null; this.items = null;

                            // Only auto-load on public feedback pages
                            if (! window.location.pathname.includes('/feedback')) {
                                return;
                            }

                            // Try saved references first
                            var refs = JSON.parse(localStorage.getItem('fms-customer-refs') || '[]');
                            if (refs.length === 0) {
                                // Fallback to saved phone lookup
                                var phone = (localStorage.getItem('fms-customer-phone') || '').trim();
                                if (! phone) {
                                    return;
                                }

                                this.loading = true;
                                try {
                                    const res = await fetch('<?php echo e(route('feedback.track')); ?>', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                                        },
                                        body: JSON.stringify({ query: phone })
                                    });

                                    const data = await res.json();
                                    if (! res.ok) {
                                        this.error = data.error || 'No submitted requests found.';
                                    } else {
                                        this.items = data.matches || [];
                                    }
                                } catch (e) {
                                    this.error = 'Unable to contact server.';
                                } finally {
                                    this.loading = false;
                                }

                                return;
                            }

                            // Load details for saved references
                            this.loading = true;
                            try {
                                const promises = refs.slice(0, 5).map(r => fetch('<?php echo e(route('feedback.track')); ?>', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                                    },
                                    body: JSON.stringify({ reference: r.reference_no })
                                }).then(res => res.ok ? res.json() : null).catch(() => null));

                                const results = await Promise.all(promises);
                                this.items = results.filter(Boolean).map(d => ({
                                    reference_no: d.reference_no,
                                    status: d.status,
                                    branch: d.branch,
                                    department: d.department,
                                    created_at: d.created_at
                                }));
                            } catch (e) {
                                this.error = 'Unable to contact server.';
                            } finally {
                                this.loading = false;
                            }
                        },
                        reviewSubmission(item) {
    window.location.href =
        '<?php echo e(route('feedback.submission.show', ['reference' => '__REFERENCE__'])); ?>'
            .replace(
                '__REFERENCE__',
                encodeURIComponent(item.reference_no)
            );
},

clear() {
    localStorage.removeItem('fms-customer-refs');
    localStorage.removeItem('fms-customer-phone');
    this.items = null;
    this.error = 'Saved data cleared.';
}
                    }"
                    x-init="load()"
                    class="mt-4 rounded-xl bg-white/70 p-4 dark:bg-slate-900/60"
                >

                    <template x-if="! items && ! loading">
                        <p class="text-xs leading-5 text-slate-600 dark:text-slate-400">
                            <?php echo e(__('Please complete the feedback form to share your experience, suggestions, or concerns with GLEDCO.')); ?>

                        </p>
                    </template>

                    <template x-if="loading">
                        <p class="text-xs text-gray-500">Loading your submissions…</p>
                    </template>

                    <template x-if="error">
                        <p class="text-xs text-rose-600" x-text="error"></p>
                    </template>

                    <div class="space-y-2">

    <div x-data="{ showSubmissions: false }" class="space-y-2">

    
    <button
        type="button"
        @click="
            showSubmissions = !showSubmissions;
            if (showSubmissions && !items && !loading) {
                load();
            }
        "
        class="w-full flex items-center justify-between text-left
               text-sm font-medium text-slate-800
               hover:text-primary transition"
    >
        <span>Your recent submissions</span>

        <i
            data-lucide="chevron-right"
            class="h-4 w-4 transition-transform duration-200"
            :class="{ 'rotate-90': showSubmissions }"
        ></i>
    </button>

    
    <div
        x-show="showSubmissions"
        x-transition
        class="mt-2 space-y-2"
    >

        
        <template x-if="loading">
            <div class="text-xs text-gray-500">
                Loading your submissions...
            </div>
        </template>

        
        <template x-if="items">
            <div class="space-y-2">

                <template x-if="items.length === 0">
                    <div class="text-xs text-gray-500">
                        No submissions found.
                    </div>
                </template>

                <template x-for="it in items" :key="it.reference_no">

                    
                    <button
                        type="button"
                        @click="reviewSubmission(it)"
                        class="w-full text-left rounded-md bg-slate-50
                               hover:bg-slate-100 p-2 text-xs
                               transition"
                    >
                        <div class="flex items-center justify-between">

                            <div>
                                <div
                                    class="font-medium text-slate-800"
                                    x-text="it.reference_no"
                                ></div>

                                <div
                                    class="text-gray-500"
                                    x-text="
                                        (it.branch || '') +
                                        (it.department ? ' • ' + it.department : '') +
                                        (it.created_at
                                            ? ' • ' + new Date(it.created_at).toLocaleString()
                                            : '')
                                    "
                                ></div>
                            </div>

                            <div
                                class="text-sm text-gray-600"
                                x-text="it.status"
                            ></div>

                        </div>
                    </button>

                </template>

                <div class="pt-2">
                    <button
                        type="button"
                        @click="clear()"
                        class="text-xs text-rose-600 hover:underline"
                    >
                        Clear saved data
                    </button>
                </div>

            </div>
        </template>

    </div>
</div>
                </div>

            </div>

        <?php endif; ?>

    </div>

</nav><?php /**PATH D:\SynologyDrive\systems\Feedback_Management_System\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>