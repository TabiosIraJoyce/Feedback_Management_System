<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Feedback Overview" description="Monitor member and customer feedback across all branches." icon="messages-square" />
    </x-slot>

    <div x-data="feedbackOverviewDashboard()" x-init="init()" x-bind:aria-busy="isLoading" class="space-y-6">
        <div class="flex flex-wrap items-center justify-end gap-3">
            <span class="text-sm text-slate-500" aria-live="polite">
                {{ __('Updated') }} <span x-text="formattedGeneratedAt()"></span>
            </span>
            <button type="button" @click="refresh()" class="inline-flex min-h-11 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 disabled:opacity-60" :disabled="isLoading">
                <i data-lucide="refresh-cw" class="h-4 w-4" :class="isLoading ? 'animate-spin' : ''"></i>
                {{ __('Refresh') }}
            </button>
        </div>

        <section aria-label="Feedback summary" class="grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-6">
            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-50 text-cyan-700"><i data-lucide="messages-square" class="h-5 w-5"></i></span>
                    <p class="text-sm font-medium text-slate-600">{{ auth()->user()->hasRole('Upper Management') ? __('Assigned Escalations') : __('Total Feedback') }}</p>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-950" x-text="dashboard.stats.total_feedback ?? 0"></p>
            </article>

            <a href="{{ route('complaints.index', ['status' => 'pending']) }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-blue-300 hover:bg-blue-50 sm:p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-700"><i data-lucide="clock-3" class="h-5 w-5"></i></span>
                    <p class="text-sm font-medium text-slate-600">{{ __('Pending Complaints') }}</p>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-950" x-text="dashboard.stats.pending_complaints ?? 0"></p>
            </a>

            <a href="{{ route('complaints.index', ['status' => 'resolved']) }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-teal-300 hover:bg-teal-50 sm:p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-50 text-teal-700"><i data-lucide="circle-check" class="h-5 w-5"></i></span>
                    <p class="text-sm font-medium text-slate-600">{{ __('Resolved Complaints') }}</p>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-950" x-text="dashboard.stats.resolved_complaints ?? 0"></p>
            </a>

            <a href="{{ route('complaints.index', ['status' => 'in_progress']) }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-rose-300 hover:bg-rose-50 sm:p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700"><i data-lucide="refresh-cw" class="h-5 w-5"></i></span>
                    <p class="text-sm font-medium text-slate-600">{{ __('In Progress') }}</p>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-950" x-text="dashboard.stats.in_progress_complaints ?? 0"></p>
            </a>

            <a href="{{ route('complaints.index', ['due' => 'overdue']) }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-rose-300 hover:bg-rose-50 sm:p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-700"><i data-lucide="triangle-alert" class="h-5 w-5"></i></span>
                    <p class="text-sm font-medium text-slate-600">{{ __('Overdue') }}</p>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-950" x-text="dashboard.analytics.sla.overdue ?? 0"></p>
            </a>

            <a href="{{ route('complaints.index', ['due' => 'soon']) }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-orange-300 hover:bg-orange-50 sm:p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-orange-700"><i data-lucide="timer" class="h-5 w-5"></i></span>
                    <p class="text-sm font-medium text-slate-600">{{ __('Due Soon') }}</p>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-950" x-text="dashboard.stats.due_soon_complaints ?? 0"></p>
            </a>
        </section>

        @if (auth()->user()->hasRole('Department Head'))
            <a href="{{ route('complaints.index', ['status' => 'escalated']) }}" class="block rounded-2xl border border-cyan-200 bg-cyan-50 p-4 shadow-sm transition hover:bg-cyan-100 sm:p-5">
                <p class="text-sm font-medium text-cyan-900">{{ __('Newly Escalated · Awaiting Your Decision') }}</p>
                <p class="mt-2 text-3xl font-bold text-slate-950" x-text="dashboard.stats.awaiting_review ?? 0"></p>
            </a>
        @elseif (auth()->user()->hasRole('Upper Management'))
            <a href="{{ route('complaints.index', ['status' => 'escalated']) }}" class="block rounded-2xl border border-violet-200 bg-violet-50 p-4 shadow-sm transition hover:bg-violet-100 sm:p-5">
                <p class="text-sm font-medium text-violet-900">{{ __('Escalated to Upper Management · Awaiting Decision') }}</p>
                <p class="mt-2 text-3xl font-bold text-slate-950" x-text="dashboard.stats.awaiting_review ?? 0"></p>
            </a>
        @elseif (auth()->user()->hasRole('Supervisor'))
            <a href="{{ route('complaints.index') }}" class="block rounded-2xl border border-amber-200 bg-amber-50 p-4 shadow-sm transition hover:bg-amber-100 sm:p-5">
                <p class="text-sm font-medium text-amber-900">{{ __('Complaints Escalated to Department Heads') }}</p>
                <p class="mt-2 text-3xl font-bold text-slate-950" x-text="dashboard.stats.escalated_to_department_head ?? 0"></p>
            </a>
        @endif

        <div class="grid gap-6 {{ auth()->user()->hasRole('Upper Management') ? 'lg:grid-cols-1' : 'lg:grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)]' }}">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950">{{ __('Urgent Complaints') }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ __('Complaints needing prompt attention.') }}</p>
                    </div>
                    <a href="{{ route('complaints.index') }}" class="shrink-0 text-sm font-semibold text-cyan-700 hover:text-cyan-900">{{ __('View all') }}</a>
                </div>

                <div class="space-y-3">
                    <template x-for="complaint in urgentComplaints" :key="complaint.reference_no">
                        <article class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold" :class="complaint.priority === 'urgent' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800'" x-text="complaint.priority_label"></span>
                                        <span class="text-xs font-medium text-slate-500" x-text="complaint.reference_no"></span>
                                    </div>
                                    <a :href="complaint.url" class="mt-3 block break-words font-semibold text-slate-900 hover:text-cyan-800" x-text="complaint.subject"></a>
                                    <p class="mt-1 text-sm text-slate-600" x-text="complaint.branch_name"></p>
                                </div>
                                <div class="flex shrink-0 flex-row items-center justify-between gap-3 sm:flex-col sm:items-end">
                                    <span class="rounded-full bg-white px-2.5 py-1 text-xs font-medium text-slate-700 ring-1 ring-slate-200" x-text="complaint.status_label"></span>
                                    <span class="text-xs text-slate-500"><span>{{ __('Assigned:') }}</span> <span x-text="complaint.assigned_to"></span></span>
                                </div>
                            </div>
                        </article>
                    </template>

                    <div x-show="urgentComplaints.length === 0" x-cloak class="rounded-xl border border-dashed border-slate-300 px-4 py-8 text-center">
                        <p class="font-medium text-slate-700">{{ __('No urgent complaints right now.') }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ __('New urgent or overdue items will appear here.') }}</p>
                    </div>
                </div>
            </section>

            @unless (auth()->user()->hasRole('Upper Management'))
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="mb-5">
                    <h2 class="text-lg font-semibold text-slate-950">{{ __('Feedback Distribution') }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ __('Complaints, suggestions, and compliments by volume.') }}</p>
                </div>

                <div class="space-y-5">
                    <template x-for="item in (dashboard.analytics.feedback_distribution || [])" :key="item.key">
                        <div x-show="item.total > 0">
                            <div class="mb-2 flex items-center justify-between gap-3 text-sm">
                                <span class="font-medium text-slate-700" x-text="item.label"></span>
                                <span class="text-slate-500" x-text="`${item.percent}%`"></span>
                            </div>
                            <div class="h-2.5 overflow-hidden rounded-full bg-slate-100" role="progressbar" :aria-label="item.label" aria-valuemin="0" aria-valuemax="100" :aria-valuenow="item.percent">
                                <div class="h-full rounded-full transition-all duration-500" :class="distributionColor(item.key)" :style="`width: ${item.percent}%`"></div>
                            </div>
                            <p class="mt-1 text-right text-xs text-slate-500" x-text="item.total"></p>
                        </div>
                    </template>

                    <div x-show="(dashboard.analytics.feedback_distribution || []).every(item => item.total === 0)" x-cloak class="rounded-xl border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500">
                        {{ __('There is no feedback category data to display yet.') }}
                    </div>
                </div>
            </section>
            @endunless
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950">{{ auth()->user()->hasRole('Department Head') ? __('New Escalations and Review Queue') : (auth()->user()->hasRole('Upper Management') ? __('Upper Management Review Queue') : __('Assigned Complaints')) }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ __('Cases currently in your authorized workflow scope.') }}</p>
                    </div>
                    <a href="{{ route('complaints.index') }}" class="shrink-0 text-sm font-semibold text-cyan-700 hover:text-cyan-900">{{ __('View all') }}</a>
                </div>
                <div class="space-y-3">
                    <template x-for="complaint in assignedComplaints" :key="`assigned-${complaint.reference_no}`">
                        <a :href="complaint.url" class="block rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-cyan-300 hover:bg-cyan-50">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="break-words font-semibold text-slate-900" x-text="complaint.subject"></p>
                                    <p class="mt-1 text-xs text-slate-500"><span x-text="complaint.reference_no"></span> · <span x-text="complaint.branch_name"></span></p>
                                </div>
                                <span class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-200" x-text="complaint.status_label"></span>
                            </div>
                        </a>
                    </template>
                    <div x-show="assignedComplaints.length === 0" x-cloak class="rounded-xl border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500">{{ __('No active complaints are assigned in your scope.') }}</div>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950">{{ __('Recently Resolved') }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ __('Latest resolved and closed complaints in your scope.') }}</p>
                    </div>
                    <a href="{{ route('complaints.index', ['status' => 'resolved']) }}" class="shrink-0 text-sm font-semibold text-cyan-700 hover:text-cyan-900">{{ __('View all') }}</a>
                </div>
                <div class="space-y-3">
                    <template x-for="complaint in recentResolved" :key="`resolved-${complaint.reference_no}`">
                        <a :href="complaint.url" class="flex items-start justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-teal-300 hover:bg-teal-50">
                            <div class="min-w-0">
                                <p class="break-words font-semibold text-slate-900" x-text="complaint.subject"></p>
                                <p class="mt-1 text-xs text-slate-500"><span x-text="complaint.reference_no"></span> · <span x-text="complaint.branch_name"></span></p>
                            </div>
                            <span class="rounded-full bg-teal-100 px-2.5 py-1 text-xs font-semibold text-teal-800" x-text="complaint.status_label"></span>
                        </a>
                    </template>
                    <div x-show="recentResolved.length === 0" x-cloak class="rounded-xl border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500">{{ __('No resolved complaints yet.') }}</div>
                </div>
            </section>
        </div>

        @if (auth()->user()->hasRole(['Admin', 'Super Admin']))
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="department-highlights-title">
            <div class="mb-5">
                <h2 id="department-highlights-title" class="text-lg font-semibold text-slate-950">{{ __('Department Highlights') }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ __('Departments with the highest complaint volume and positive feedback.') }}</p>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <article class="flex items-start gap-4 rounded-xl border border-rose-100 bg-rose-50/60 p-4">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-rose-700 shadow-sm"><i data-lucide="message-circle-warning" class="h-5 w-5"></i></span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-rose-800">{{ __('Most Complaints') }}</p>
                        <p class="mt-1 truncate text-lg font-semibold text-slate-950" x-text="dashboard.analytics.department_highlights?.most_complaints?.name ?? '{{ __('No data yet') }}'"></p>
                        <p class="mt-1 text-sm text-slate-600">
                            <span x-text="dashboard.analytics.department_highlights?.most_complaints?.count ?? 0"></span>
                            {{ __('complaints') }}
                            <template x-if="dashboard.analytics.department_highlights?.most_complaints?.branch_name">
                                <span>· <span x-text="dashboard.analytics.department_highlights.most_complaints.branch_name"></span></span>
                            </template>
                        </p>
                    </div>
                </article>

                <article class="flex items-start gap-4 rounded-xl border border-teal-100 bg-teal-50/60 p-4">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-teal-700 shadow-sm"><i data-lucide="thumbs-up" class="h-5 w-5"></i></span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-teal-800">{{ __('Most Positive Feedback') }}</p>
                        <p class="mt-1 truncate text-lg font-semibold text-slate-950" x-text="dashboard.analytics.department_highlights?.most_positive_feedback?.name ?? '{{ __('No data yet') }}'"></p>
                        <p class="mt-1 text-sm text-slate-600">
                            <span x-text="dashboard.analytics.department_highlights?.most_positive_feedback?.count ?? 0"></span>
                            {{ __('positive feedback entries') }}
                            <template x-if="dashboard.analytics.department_highlights?.most_positive_feedback?.branch_name">
                                <span>· <span x-text="dashboard.analytics.department_highlights.most_positive_feedback.branch_name"></span></span>
                            </template>
                        </p>
                    </div>
                </article>
            </div>
        </section>
        @endif

        @if (auth()->user()->hasRole(['Admin', 'Super Admin']))
            <div class="grid gap-6 lg:grid-cols-2">
                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <h2 class="text-lg font-semibold text-slate-950">{{ __('Branch Complaint Volume') }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ __('Branches with the most complaints in the selected scope.') }}</p>
                    <div class="mt-5 space-y-4">
                        <template x-for="branch in (dashboard.analytics.branch_performance || [])" :key="branch.name">
                            <div>
                                <div class="mb-1 flex items-center justify-between gap-3 text-sm">
                                    <span class="font-medium text-slate-700" x-text="branch.name"></span>
                                    <span class="text-slate-500" x-text="branch.total_complaints"></span>
                                </div>
                                <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full bg-cyan-600" :style="`width: ${branchBarWidth(branch.total_complaints)}%`"></div>
                                </div>
                            </div>
                        </template>
                        <p x-show="(dashboard.analytics.branch_performance || []).length === 0" x-cloak class="rounded-xl border border-dashed border-slate-300 p-5 text-sm text-slate-500">{{ __('No branch complaint data yet.') }}</p>
                    </div>
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <h2 class="text-lg font-semibold text-slate-950">{{ __('Weekly Complaint Trend') }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ __('Complaints received each day over the last seven days.') }}</p>
                    <div class="mt-5 grid h-48 grid-cols-7 items-end gap-2">
                        <template x-for="(label, index) in (dashboard.analytics.trend?.labels || [])" :key="label">
                            <div class="flex h-full flex-col items-center justify-end gap-2">
                                <span class="text-xs font-medium text-slate-600" x-text="dashboard.analytics.trend.values[index]"></span>
                                <div class="flex h-32 w-full items-end overflow-hidden rounded-lg bg-slate-100">
                                    <div class="w-full rounded-lg bg-teal-500 transition-all" :style="`height: ${trendBarHeight(dashboard.analytics.trend.values[index])}%`"></div>
                                </div>
                                <span class="text-[11px] text-slate-500" x-text="label"></span>
                            </div>
                        </template>
                    </div>
                    <div class="mt-5 border-t border-slate-100 pt-4">
                        <h3 class="text-sm font-semibold text-slate-800">{{ __('Common Complaint Categories') }}</h3>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <template x-for="item in (dashboard.analytics.category || [])" :key="item.category">
                                <span class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700"><span x-text="item.category"></span> · <span x-text="item.total"></span></span>
                            </template>
                            <span x-show="(dashboard.analytics.category || []).length === 0" x-cloak class="text-sm text-slate-500">{{ __('No category data yet.') }}</span>
                        </div>
                    </div>
                </section>
            </div>
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <h2 class="text-lg font-semibold text-slate-950">{{ __('SLA Performance') }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ __('Resolution performance against complaint deadlines.') }}</p>
                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">{{ __('SLA hit rate') }}</p>
                        <p class="mt-1 text-2xl font-bold text-slate-950"><span x-text="dashboard.analytics.sla.sla_hit_rate ?? 0"></span>%</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">{{ __('Resolved on time') }}</p>
                        <p class="mt-1 text-2xl font-bold text-slate-950" x-text="dashboard.analytics.sla.resolved_on_time ?? 0"></p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">{{ __('Average resolution') }}</p>
                        <p class="mt-1 text-2xl font-bold text-slate-950"><span x-text="dashboard.stats.avg_resolution_days ?? 0"></span> {{ __('days') }}</p>
                    </div>
                </div>
            </section>
        @endif
    </div>

    <script>
        function feedbackOverviewDashboard() {
            return {
                dashboard: @json($dashboard),
                urgentComplaints: @json($urgentComplaintItems),
                assignedComplaints: @json($assignedComplaintItems),
                recentResolved: @json($recentResolvedItems),
                isLoading: false,
                refreshHandle: null,
                init() {
                    this.refreshHandle = window.setInterval(() => this.refresh(), 30000);
                },
                formattedGeneratedAt() {
                    return this.dashboard.generated_at
                        ? new Date(this.dashboard.generated_at).toLocaleTimeString()
                        : '{{ __('just now') }}';
                },
                distributionColor(key) {
                    return {
                        complaints: 'bg-rose-500',
                        suggestions: 'bg-amber-500',
                        compliments: 'bg-teal-600',
                    }[key] || 'bg-cyan-600';
                },
                branchBarWidth(value) {
                    const branches = this.dashboard.analytics.branch_performance || [];
                    const maximum = Math.max(1, ...branches.map(branch => Number(branch.total_complaints) || 0));
                    return Math.max(4, Math.round((Number(value) / maximum) * 100));
                },
                trendBarHeight(value) {
                    const values = this.dashboard.analytics.trend?.values || [];
                    const maximum = Math.max(1, ...values.map(Number));
                    return Math.max(4, Math.round((Number(value) / maximum) * 100));
                },
                async refresh() {
                    if (this.isLoading) return;

                    this.isLoading = true;
                    try {
                        const response = await fetch(@json(route('dashboard.metrics')), {
                            headers: {
                                Accept: 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });

                        if (! response.ok) return;

                        const payload = await response.json();
                        this.dashboard = payload;
                        this.urgentComplaints = payload.urgent_complaints || [];
                        this.assignedComplaints = payload.assigned_complaints || [];
                        this.recentResolved = payload.recent_resolved || [];
                        window.FMSHci?.announce?.(@js(__('Dashboard metrics updated.')));
                    } finally {
                        this.isLoading = false;
                    }
                },
                destroy() {
                    if (this.refreshHandle) window.clearInterval(this.refreshHandle);
                },
            };
        }
    </script>
</x-app-layout>
