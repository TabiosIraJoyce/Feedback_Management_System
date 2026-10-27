<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Dashboard & Analytics" description="Monitor complaints, SLA performance, and branch operations in one live dashboard." icon="layout-dashboard" />
    </x-slot>

    <div x-data="dashboardAnalytics()" x-init="init()" x-bind:aria-busy="isLoading" class="relative space-y-6">
        <div x-show="isLoading" class="pointer-events-none absolute inset-0 z-40 bg-white/80 dark:bg-slate-950/90">
            <div class="flex h-full items-center justify-center p-6">
                <div class="space-y-4 text-center">
                    <div class="h-3 w-44 animate-pulse rounded-full bg-slate-200 dark:bg-slate-800"></div>
                    <div class="mx-auto grid max-w-5xl grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-6">
                        <div class="h-32 rounded-3xl bg-slate-200 dark:bg-slate-800"></div>
                        <div class="h-32 rounded-3xl bg-slate-200 dark:bg-slate-800"></div>
                        <div class="h-32 rounded-3xl bg-slate-200 dark:bg-slate-800"></div>
                        <div class="h-32 rounded-3xl bg-slate-200 dark:bg-slate-800"></div>
                        <div class="h-32 rounded-3xl bg-slate-200 dark:bg-slate-800"></div>
                        <div class="h-32 rounded-3xl bg-slate-200 dark:bg-slate-800"></div>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('Refreshing analytics and dashboard metrics...') }}</p>
                </div>
            </div>
        </div>

        <div>
            <x-admin.welcome-banner :user="auth()->user()" />
        </div>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">{{ __('Interactive Analytics') }}</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Live metrics, fast filtering, and status visibility designed to reduce decision time.') }}</p>
                </div>

                <div class="flex flex-wrap items-center gap-3 text-sm">
                    <label class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 dark:border-slate-800 dark:bg-slate-900">
                        <input type="checkbox" x-model="autoRefresh" class="rounded border-slate-300 text-cyan-600 shadow-sm focus:ring-cyan-500">
                        <span class="text-slate-700 dark:text-slate-200">{{ __('Auto-refresh') }}</span>
                    </label>
                    <button type="button" @click="refresh()" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 font-semibold text-slate-700 hover:border-slate-300 hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                        <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                        {{ __('Refresh now') }}
                    </button>
                    <div class="rounded-full bg-emerald-100 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200">
                        {{ __('Updated') }} <span x-text="formattedGeneratedAt()"></span>
                    </div>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-6">
            <template x-for="card in statCards()" :key="card.label">
                <article class="min-h-32 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-slate-300 hover:bg-slate-50 sm:p-6">
                    <p class="text-xs font-medium uppercase tracking-[0.12em] text-blue-600 sm:text-sm" x-text="card.label"></p>
                    <p class="mt-4 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl" x-text="card.value"></p>
                </article>
            </template>
        </div>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div class="mb-6 flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">{{ __('Live Filter') }}</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Narrow analytics by branch, product, status, category, or date range.') }}</p>
                    </div>
                    <button type="button" @click="resetFilters()" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-slate-300 hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                        <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
                        {{ __('Reset') }}
                    </button>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="block text-sm text-slate-600 dark:text-slate-300">
                        {{ __('Branch') }}
                        <select x-model="filters.branch" @change="refresh()" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100">
                            <option value="">{{ __('All branches') }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block text-sm text-slate-600 dark:text-slate-300">
                        {{ __('Product / Service') }}
                        <select x-model="filters.product" @change="refresh()" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100">
                            <option value="">{{ __('All products') }}</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block text-sm text-slate-600 dark:text-slate-300">
                        {{ __('Status') }}
                        <select x-model="filters.status" @change="refresh()" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100">
                            <option value="">{{ __('All statuses') }}</option>
                            @foreach ($statusOptions as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block text-sm text-slate-600 dark:text-slate-300">
                        {{ __('Category') }}
                        <select x-model="filters.category" @change="refresh()" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100">
                            <option value="">{{ __('All categories') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block text-sm text-slate-600 dark:text-slate-300">
                        {{ __('From') }}
                        <input x-model="filters.from_date" @change="refresh()" type="date" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100" />
                    </label>

                    <label class="block text-sm text-slate-600 dark:text-slate-300">
                        {{ __('To') }}
                        <input x-model="filters.to_date" @change="refresh()" type="date" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-slate-950 dark:border-slate-800 dark:text-slate-100" />
                    </label>
                </div>
        </section>

        <div class="grid gap-6">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:bg-slate-950 dark:border-slate-800">
                <div class="mb-4 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">{{ __('SLA Performance') }}</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Open SLA risk, overdue items, and hit rate.') }}</p>
                    </div>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200">
                        <span x-text="dashboard.analytics.sla.sla_hit_rate"></span>% {{ __('hit rate') }}
                    </span>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Open SLA') }}</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-950 dark:text-slate-100" x-text="dashboard.analytics.sla.open_sla"></p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ __('Active items tied to an SLA window.') }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Overdue') }}</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-950 dark:text-slate-100" x-text="dashboard.analytics.sla.overdue"></p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ __('Complaints currently beyond the target time.') }}</p>
                    </div>
                </div>

                <div class="mt-5 rounded-3xl border border-cyan-200 bg-cyan-50 p-4 text-sm text-cyan-900 dark:border-cyan-900/30 dark:bg-cyan-950/30 dark:text-cyan-100">
                    <p class="font-semibold">{{ __('Usability insight') }}</p>
                    <p class="mt-2 leading-7" x-text="insightSummary()"></p>
                </div>
            </section>
        </div>

    </div>

    <script>
        function dashboardAnalytics() {
            return {
                dashboard: @json($dashboard),
                recentComplaints: @json($recentComplaintItems),
                isLoading: false,
                autoRefresh: true,
                translations: @js([
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
                ]),
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
                        return `{{ __('There are') }} ${this.dashboard.analytics.sla.overdue} {{ __('overdue complaints in the filtered view, so the dashboard is surfacing SLA risk early for faster intervention.') }}`;
                    }

                    if (this.dashboard.stats.pending_complaints > 0) {
                        return `{{ __('There are') }} ${this.dashboard.stats.pending_complaints} {{ __('active complaints in progress. Live refresh keeps the queue visible without forcing manual reloads.') }}`;
                    }

                    return @js(__('No urgent SLA risks are visible right now. The dashboard is still monitoring for new complaint activity in the background.'));
                },
                async refresh(pushHistory = true) {
                    this.isLoading = true;

                    try {
                        const params = new URLSearchParams(this.filters);
                        const response = await fetch(`{{ route('dashboard.metrics') }}?${params.toString()}`, {
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
</x-app-layout>
