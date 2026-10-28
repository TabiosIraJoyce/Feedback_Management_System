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

        <section aria-label="Feedback summary" class="grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-4">
            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-50 text-cyan-700"><i data-lucide="messages-square" class="h-5 w-5"></i></span>
                    <p class="text-sm font-medium text-slate-600">{{ __('Total Feedback') }}</p>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-950" x-text="dashboard.stats.total_feedback ?? 0"></p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-700"><i data-lucide="clock-3" class="h-5 w-5"></i></span>
                    <p class="text-sm font-medium text-slate-600">{{ __('Pending Complaints') }}</p>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-950" x-text="dashboard.stats.pending_complaints ?? 0"></p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700"><i data-lucide="refresh-cw" class="h-5 w-5"></i></span>
                    <p class="text-sm font-medium text-slate-600">{{ __('In Progress') }}</p>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-950" x-text="dashboard.stats.in_progress_complaints ?? 0"></p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-700"><i data-lucide="triangle-alert" class="h-5 w-5"></i></span>
                    <p class="text-sm font-medium text-slate-600">{{ __('Overdue') }}</p>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-950" x-text="dashboard.analytics.sla.overdue ?? 0"></p>
            </article>
        </section>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)]">
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
        </div>

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
    </div>

    <script>
        function feedbackOverviewDashboard() {
            return {
                dashboard: @json($dashboard),
                urgentComplaints: @json($urgentComplaintItems),
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
