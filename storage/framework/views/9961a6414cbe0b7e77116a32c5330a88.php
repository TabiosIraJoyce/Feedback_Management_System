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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.page-header','data' => ['title' => 'Dashboard & Analytics','description' => 'Monitor complaints, SLA performance, and branch operations in one live dashboard.','icon' => 'layout-dashboard']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Dashboard & Analytics','description' => 'Monitor complaints, SLA performance, and branch operations in one live dashboard.','icon' => 'layout-dashboard']); ?>
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

    <div x-data="dashboardAnalytics()" x-init="init()" x-bind:aria-busy="isLoading" class="relative space-y-6">
        <div x-show="isLoading" class="pointer-events-none absolute inset-0 z-40 bg-white/80 dark:bg-slate-950/90">
            <div class="flex h-full items-center justify-center p-6">
                <div class="space-y-4 text-center">
                    <div class="h-3 w-44 animate-pulse rounded-full bg-slate-200 dark:bg-slate-800"></div>
                    <div class="mx-auto grid max-w-2xl gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="h-24 rounded-3xl bg-slate-200 dark:bg-slate-800"></div>
                        <div class="h-24 rounded-3xl bg-slate-200 dark:bg-slate-800"></div>
                        <div class="h-24 rounded-3xl bg-slate-200 dark:bg-slate-800"></div>
                        <div class="h-24 rounded-3xl bg-slate-200 dark:bg-slate-800"></div>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-400"><?php echo e(__('Refreshing analytics and dashboard metrics...')); ?></p>
                </div>
            </div>
        </div>

        <div>
            <?php if (isset($component)) { $__componentOriginal23043423615a1cac4a2df90a9533a6c9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal23043423615a1cac4a2df90a9533a6c9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.welcome-banner','data' => ['user' => auth()->user()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.welcome-banner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['user' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user())]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal23043423615a1cac4a2df90a9533a6c9)): ?>
<?php $attributes = $__attributesOriginal23043423615a1cac4a2df90a9533a6c9; ?>
<?php unset($__attributesOriginal23043423615a1cac4a2df90a9533a6c9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal23043423615a1cac4a2df90a9533a6c9)): ?>
<?php $component = $__componentOriginal23043423615a1cac4a2df90a9533a6c9; ?>
<?php unset($__componentOriginal23043423615a1cac4a2df90a9533a6c9); ?>
<?php endif; ?>
        </div>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100"><?php echo e(__('Interactive Analytics')); ?></h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400"><?php echo e(__('Live metrics, fast filtering, and status visibility designed to reduce decision time.')); ?></p>
                </div>

                <div class="flex flex-wrap items-center gap-3 text-sm">
                    <label class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 dark:border-slate-800 dark:bg-slate-900">
                        <input type="checkbox" x-model="autoRefresh" class="rounded border-slate-300 text-cyan-600 shadow-sm focus:ring-cyan-500">
                        <span class="text-slate-700 dark:text-slate-200"><?php echo e(__('Auto-refresh')); ?></span>
                    </label>
                    <button type="button" @click="refresh()" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 font-semibold text-slate-700 hover:border-slate-300 hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                        <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                        <?php echo e(__('Refresh now')); ?>

                    </button>
                    <div class="rounded-full bg-emerald-100 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200">
                        <?php echo e(__('Updated')); ?> <span x-text="formattedGeneratedAt()"></span>
                    </div>
                </div>
            </div>
        </section>

        <div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_minmax(420px,520px)]">
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <template x-for="card in statCards()" :key="card.label">
                    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500" x-text="card.label"></p>
                        <p class="mt-3 text-3xl font-semibold text-slate-950 dark:text-slate-100" x-text="card.value"></p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400" x-text="card.helper"></p>
                    </div>
                </template>
            </div>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:bg-slate-950 dark:border-slate-800">
                <div class="mb-6 flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100"><?php echo e(__('Live Filter')); ?></h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400"><?php echo e(__('Narrow analytics by branch, product, status, category, or date range.')); ?></p>
                    </div>
                    <button type="button" @click="resetFilters()" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-slate-300 hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                        <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
                        <?php echo e(__('Reset')); ?>

                    </button>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="block text-sm text-slate-600 dark:text-slate-300">
                        <?php echo e(__('Branch')); ?>

                        <select x-model="filters.branch" @change="refresh()" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100">
                            <option value=""><?php echo e(__('All branches')); ?></option>
                            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($branch->id); ?>"><?php echo e($branch->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </label>

                    <label class="block text-sm text-slate-600 dark:text-slate-300">
                        <?php echo e(__('Product / Service')); ?>

                        <select x-model="filters.product" @change="refresh()" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100">
                            <option value=""><?php echo e(__('All products')); ?></option>
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($product->id); ?>"><?php echo e($product->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </label>

                    <label class="block text-sm text-slate-600 dark:text-slate-300">
                        <?php echo e(__('Status')); ?>

                        <select x-model="filters.status" @change="refresh()" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100">
                            <option value=""><?php echo e(__('All statuses')); ?></option>
                            <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </label>

                    <label class="block text-sm text-slate-600 dark:text-slate-300">
                        <?php echo e(__('Category')); ?>

                        <select x-model="filters.category" @change="refresh()" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100">
                            <option value=""><?php echo e(__('All categories')); ?></option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category); ?>"><?php echo e(ucfirst($category)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </label>

                    <label class="block text-sm text-slate-600 dark:text-slate-300">
                        <?php echo e(__('From')); ?>

                        <input x-model="filters.from_date" @change="refresh()" type="date" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100" />
                    </label>

                    <label class="block text-sm text-slate-600 dark:text-slate-300">
                        <?php echo e(__('To')); ?>

                        <input x-model="filters.to_date" @change="refresh()" type="date" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100" />
                    </label>
                </div>
            </section>
        </div>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_420px]">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:bg-slate-950 dark:border-slate-800">
                <div class="mb-4 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100"><?php echo e(__('Complaint Trend')); ?></h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400"><?php echo e(__('Weekly complaints by creation date.')); ?></p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700 dark:bg-slate-900 dark:text-slate-300"><?php echo e(__('Updated live')); ?></span>
                </div>

                <div class="grid h-64 grid-cols-7 items-end gap-3">
                    <template x-for="(label, index) in dashboard.analytics.trend.labels" :key="label">
                        <div class="flex h-full flex-col justify-end gap-3">
                            <div class="flex-1 rounded-3xl bg-slate-100 p-2 dark:bg-slate-900">
                                <div class="flex h-full items-end">
                                    <div class="w-full rounded-2xl bg-cyan-500 transition-all duration-500" :style="`height: ${barHeight(index)}%`"></div>
                                </div>
                            </div>
                            <div class="text-center text-xs text-slate-500 dark:text-slate-400">
                                <p x-text="label"></p>
                                <p class="mt-1 font-semibold text-slate-700 dark:text-slate-200" x-text="dashboard.analytics.trend.values[index]"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:bg-slate-950 dark:border-slate-800">
                <div class="mb-4 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100"><?php echo e(__('SLA Performance')); ?></h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400"><?php echo e(__('Open SLA risk, overdue items, and hit rate.')); ?></p>
                    </div>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200">
                        <span x-text="dashboard.analytics.sla.sla_hit_rate"></span>% <?php echo e(__('hit rate')); ?>

                    </span>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500"><?php echo e(__('Open SLA')); ?></p>
                        <p class="mt-3 text-3xl font-semibold text-slate-950 dark:text-slate-100" x-text="dashboard.analytics.sla.open_sla"></p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400"><?php echo e(__('Active items tied to an SLA window.')); ?></p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500"><?php echo e(__('Overdue')); ?></p>
                        <p class="mt-3 text-3xl font-semibold text-slate-950 dark:text-slate-100" x-text="dashboard.analytics.sla.overdue"></p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400"><?php echo e(__('Complaints currently beyond the target time.')); ?></p>
                    </div>
                </div>

                <div class="mt-5 rounded-3xl border border-cyan-200 bg-cyan-50 p-4 text-sm text-cyan-900 dark:border-cyan-900/30 dark:bg-cyan-950/30 dark:text-cyan-100">
                    <p class="font-semibold"><?php echo e(__('Usability insight')); ?></p>
                    <p class="mt-2 leading-7" x-text="insightSummary()"></p>
                </div>
            </section>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100"><?php echo e(__('Branch Performance')); ?></h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400"><?php echo e(__('Top branches by complaint volume and average resolution time.')); ?></p>
                </div>
                <div class="space-y-4">
                    <template x-for="row in dashboard.analytics.branch_performance" :key="row.name">
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-slate-900 dark:text-slate-100" x-text="row.name"></p>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400"><span x-text="row.total_complaints"></span> <?php echo e(__('complaints')); ?></p>
                                </div>
                                <span class="rounded-full bg-cyan-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-200">
                                    <span x-text="row.avg_resolution_days"></span><?php echo e(__('d avg')); ?>

                                </span>
                            </div>
                        </div>
                    </template>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100"><?php echo e(__('Product / Service Analytics')); ?></h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400"><?php echo e(__('Complaint distribution across products.')); ?></p>
                </div>
                <div class="space-y-4">
                    <template x-for="row in dashboard.analytics.product" :key="row.name">
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-slate-900 dark:text-slate-100" x-text="row.name"></p>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400"><span x-text="row.complaint_count"></span> <?php echo e(__('complaints')); ?></p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100"><?php echo e(__('Category Analytics')); ?></h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400"><?php echo e(__('Most common complaint topics.')); ?></p>
                </div>
                <div class="space-y-4">
                    <template x-for="row in dashboard.analytics.category" :key="row.category">
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-slate-900 dark:text-slate-100" x-text="titleCase(row.category)"></p>
                                </div>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700 dark:bg-slate-800 dark:text-slate-300" x-text="row.total"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </section>
        </div>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_420px]">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:bg-slate-950 dark:border-slate-800">
                <div class="mb-4 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100"><?php echo e(__('Status Distribution')); ?></h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400"><?php echo e(__('Complaint status breakdown by selected filters.')); ?></p>
                    </div>
                </div>

                <div class="space-y-3">
                    <template x-for="status in dashboard.analytics.status_distribution" :key="status.status">
                        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-sm font-medium text-slate-900 dark:text-slate-100" x-text="titleCase(status.status)"></span>
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-200" x-text="status.total"></span>
                            </div>
                            <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-800">
                                <div class="h-full rounded-full bg-cyan-500" :style="`width: ${distributionWidth(status.total)}%`"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:bg-slate-950 dark:border-slate-800">
                <div class="mb-4 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100"><?php echo e(__('Recent Complaints')); ?></h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400"><?php echo e(__('Latest complaints in your scope, with quick access links.')); ?></p>
                    </div>
                    <a href="<?php echo e(route('complaints.index')); ?>" class="text-sm font-medium text-cyan-700 hover:text-cyan-800 dark:text-cyan-300"><?php echo e(__('View all')); ?></a>
                </div>

                <div class="space-y-3">
                    <template x-if="recentComplaints.length === 0">
                        <p class="rounded-3xl border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400"><?php echo e(__('No recent complaints found.')); ?></p>
                    </template>

                    <template x-for="complaint in recentComplaints" :key="complaint.url">
                        <a :href="complaint.url" class="block rounded-3xl border border-slate-200 bg-white p-4 transition hover:border-cyan-300 hover:bg-cyan-50/30 dark:border-slate-800 dark:bg-slate-950 dark:hover:border-cyan-500">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-slate-950 dark:text-slate-100" x-text="complaint.subject"></p>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400"><span x-text="complaint.reference_no"></span> • <span x-text="complaint.branch_name"></span></p>
                                </div>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700 dark:bg-slate-800 dark:text-slate-300" x-text="complaint.status_label"></span>
                            </div>
                        </a>
                    </template>
                </div>
            </section>
        </div>
    </div>

    <script>
        function dashboardAnalytics() {
            return {
                dashboard: <?php echo json_encode($dashboard, 15, 512) ?>,
                recentComplaints: <?php echo json_encode($recentComplaintItems, 15, 512) ?>,
                isLoading: false,
                autoRefresh: true,
                translations: <?php echo \Illuminate\Support\Js::from([
                    'totalComplaints' => __('Total Complaints'),
                    'overallVolumeInCurrentView' => __('Overall volume in the current view.'),
                    'pendingComplaints' => __('Pending Complaints'),
                    'openWorkWaitingForAction' => __('Open work waiting for action.'),
                    'resolvedComplaints' => __('Resolved Complaints'),
                    'casesCompletedInFilteredView' => __('Cases completed in the filtered view.'),
                    'escalatedComplaints' => __('Escalated Complaints'),
                    'higherPriorityResponse' => __('Items that needed a higher-priority response.'),
                    'dueSoon' => __('Due Soon'),
                    'approachingSlaWindow' => __('Complaints approaching their SLA window.'),
                    'avgResolution' => __('Avg Resolution'),
                    'averageTimeToClose' => __('Average time to close resolved complaints.'),
                    'justNow' => __('just now'),
                    'dashboardMetricsUpdated' => __('Dashboard metrics updated.'),
                    'statusMap' => [
                        'open' => __('Open'),
                        'in_progress' => __('In Progress'),
                        'resolved' => __('Resolved'),
                        'closed' => __('Closed'),
                        'reopened' => __('Reopened'),
                        'escalated' => __('Escalated'),
                        'under_review' => __('Under Review'),
                        'assigned' => __('Assigned'),
                    ],
                ])->toHtml() ?>,
                filters: {
                    branch: '',
                    product: '',
                    status: '',
                    category: '',
                    from_date: '',
                    to_date: '',
                },
                autoRefreshHandle: null,
                init() {
                    const params = new URLSearchParams(window.location.search);
                    this.filters.branch = params.get('branch') ?? '';
                    this.filters.product = params.get('product') ?? '';
                    this.filters.status = params.get('status') ?? '';
                    this.filters.category = params.get('category') ?? '';
                    this.filters.from_date = params.get('from_date') ?? '';
                    this.filters.to_date = params.get('to_date') ?? '';

                    this.autoRefreshHandle = window.setInterval(() => {
                        if (this.autoRefresh) {
                            this.refresh(false);
                        }
                    }, 30000);
                },
                statCards() {
                    return [
                        { label: this.translations.totalComplaints, value: this.dashboard.stats.total_complaints, helper: this.translations.overallVolumeInCurrentView },
                        { label: this.translations.pendingComplaints, value: this.dashboard.stats.pending_complaints, helper: this.translations.openWorkWaitingForAction },
                        { label: this.translations.resolvedComplaints, value: this.dashboard.stats.resolved_complaints, helper: this.translations.casesCompletedInFilteredView },
                        { label: this.translations.escalatedComplaints, value: this.dashboard.stats.escalated_complaints, helper: this.translations.higherPriorityResponse },
                        { label: this.translations.dueSoon, value: this.dashboard.stats.due_soon_complaints, helper: this.translations.approachingSlaWindow },
                        { label: this.translations.avgResolution, value: `${this.dashboard.stats.avg_resolution_days}d`, helper: this.translations.averageTimeToClose },
                    ];
                },
                formattedGeneratedAt() {
                    if (!this.dashboard.generated_at) {
                        return this.translations.justNow;
                    }

                    return new Date(this.dashboard.generated_at).toLocaleTimeString();
                },
                barHeight(index) {
                    const values = this.dashboard.analytics.trend.values;
                    const max = Math.max(...values, 1);

                    return Math.max(8, Math.round((values[index] / max) * 100));
                },
                distributionWidth(total) {
                    const denominator = this.dashboard.stats.total_complaints || 1;
                    return Math.round((total / denominator) * 100);
                },
                titleCase(value) {
                    return this.translations.statusMap[value] || String(value || '').replaceAll('_', ' ').replace(/\b\w/g, (character) => character.toUpperCase());
                },
                insightSummary() {
                    if (this.dashboard.analytics.sla.overdue > 0) {
                        return `<?php echo e(__('There are')); ?> ${this.dashboard.analytics.sla.overdue} <?php echo e(__('overdue complaints in the filtered view, so the dashboard is surfacing SLA risk early for faster intervention.')); ?>`;
                    }

                    if (this.dashboard.stats.pending_complaints > 0) {
                        return `<?php echo e(__('There are')); ?> ${this.dashboard.stats.pending_complaints} <?php echo e(__('active complaints in progress. Live refresh keeps the queue visible without forcing manual reloads.')); ?>`;
                    }

                    return <?php echo \Illuminate\Support\Js::from(__('No urgent SLA risks are visible right now. The dashboard is still monitoring for new complaint activity in the background.'))->toHtml() ?>;
                },
                async refresh(pushHistory = true) {
                    this.isLoading = true;

                    try {
                        const params = new URLSearchParams(this.filters);
                        const response = await fetch(`<?php echo e(route('dashboard.metrics')); ?>?${params.toString()}`, {
                            headers: {
                                Accept: 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });

                        if (!response.ok) {
                            return;
                        }

                        const payload = await response.json();
                        this.dashboard = payload;
                        this.recentComplaints = payload.recent_complaints || [];

                        if (pushHistory) {
                            const url = new URL(window.location.href);
                            url.search = params.toString();
                            window.history.replaceState({}, '', url.toString());
                        }

                        window.FMSHci.announce(this.translations.dashboardMetricsUpdated);
                    } finally {
                        this.isLoading = false;
                    }
                },
                resetFilters() {
                    this.filters = { branch: '', product: '', status: '', category: '', from_date: '', to_date: '' };
                    this.refresh();
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
<?php /**PATH D:\SynologyDrive\systems\Feedback_Management_System\resources\views/dashboard.blade.php ENDPATH**/ ?>