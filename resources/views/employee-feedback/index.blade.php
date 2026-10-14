<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Employee Feedback" description="Track internal concerns, incident reports, and suggestions with live workflow visibility." icon="messages-square">
            @can('create', \App\Models\EmployeeFeedback::class)
                <a href="{{ route('employee-feedback.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-cyan-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    New Internal Feedback
                </a>
            @endcan
        </x-admin.page-header>
    </x-slot>

    <div x-data="employeeFeedbackRealtime({{ auth()->id() }})" x-init="init()" class="space-y-6">
        <template x-if="message">
            <div class="rounded-3xl border border-cyan-200 bg-cyan-50 p-4 text-sm text-cyan-800 dark:border-cyan-500/30 dark:bg-cyan-500/10 dark:text-cyan-200" x-text="message"></div>
        </template>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <x-admin.stat-card label="Total Reports" :value="$overview['total']" icon="files" tone="rose" />
            <x-admin.stat-card label="Pending" :value="$overview['pending']" icon="clock" tone="amber" />
            <x-admin.stat-card label="In Progress" :value="$overview['in_progress']" icon="loader-circle" tone="cyan" />
            <x-admin.stat-card label="Resolved" :value="$overview['resolved']" icon="badge-check" tone="emerald" />
            <x-admin.stat-card label="Incidents" :value="$overview['incidents']" icon="triangle-alert" tone="rose" />
        </div>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <form method="GET" action="{{ route('employee-feedback.index') }}" class="grid gap-4 lg:grid-cols-5">
                <x-text-input type="search" name="search" placeholder="Search reference, subject, or description" value="{{ $filters['search'] }}" />

                <select name="branch_id" class="rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                    <option value="">All branches</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" @selected((string) $filters['branch_id'] === (string) $branch->id)>{{ $branch->name }}</option>
                    @endforeach
                </select>

                <select name="status" class="rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>

                <select name="category" class="rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                    <option value="">All categories</option>
                    @foreach ($categories as $value => $label)
                        <option value="{{ $value }}" @selected($filters['category'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>

                <select name="priority" class="rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                    <option value="">All priorities</option>
                    @foreach ($priorities as $value => $label)
                        <option value="{{ $value }}" @selected($filters['priority'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
        </section>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
            <section class="space-y-4">
                @forelse ($feedbackEntries as $entry)
                    <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div class="space-y-3">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">{{ $entry->subject }}</h3>
                                    <x-admin.status-badge :value="$entry->status" />
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700 dark:bg-slate-900 dark:text-slate-300">{{ str_replace('_', ' ', $entry->priority) }}</span>
                                </div>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $entry->reference_no }} | {{ $categories[$entry->category] ?? $entry->category }} | {{ $entry->department?->name ?? 'No department' }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-300">{{ \Illuminate\Support\Str::limit($entry->description, 180) }}</p>
                                <div class="flex flex-wrap gap-3 text-xs uppercase tracking-wide text-slate-400">
                                    <span>Submitted by {{ $entry->display_name }}</span>
                                    <span>{{ $entry->created_at?->format('M d, Y h:i A') }}</span>
                                    <span>Reopened {{ $entry->reopened_count }}x</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('employee-feedback.show', $entry) }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 dark:border-slate-700 dark:text-slate-200">View</a>
                                @can('update', $entry)
                                    <a href="{{ route('employee-feedback.edit', $entry) }}" class="rounded-xl border border-cyan-200 bg-cyan-50 px-4 py-2 text-sm font-medium text-cyan-700 dark:border-cyan-500/30 dark:bg-cyan-500/10 dark:text-cyan-200">Edit</a>
                                @endcan
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-400">
                        No employee feedback entries matched the selected filters.
                    </div>
                @endforelse

                {{ $feedbackEntries->links() }}
            </section>

            <aside class="space-y-6">
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Status Overview</h3>
                    <div class="mt-4 space-y-3">
                        @foreach ($statusDistribution as $item)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $statuses[$item['status']] ?? $item['status'] }}</span>
                                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $item['total'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Category Breakdown</h3>
                    <div class="mt-4 space-y-3">
                        @foreach ($categoryDistribution as $item)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $categories[$item['category']] ?? $item['category'] }}</span>
                                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $item['total'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </aside>
        </div>
    </div>

    <script>
        function employeeFeedbackRealtime(userId) {
            return {
                message: '',
                init() {
                    if (!window.Echo || !userId) {
                        return;
                    }

                    window.Echo.private(`App.Models.User.${userId}`)
                        .notification((notification) => {
                            if (!notification.employee_feedback_id) {
                                return;
                            }

                            this.message = notification.message ?? 'Employee feedback updated.';
                            setTimeout(() => window.location.reload(), 1200);
                        });
                },
            }
        }
    </script>
</x-app-layout>
