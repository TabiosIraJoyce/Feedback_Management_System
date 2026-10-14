<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Reports and Export" description="Generate operational and analytics reports with filters, exports, audit logs, and schedules." icon="file-bar-chart">
            <a href="{{ route('reports.export', ['reportType' => $filters['report'], 'format' => 'pdf'] + array_filter($filters)) }}" class="inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-950">
                <i data-lucide="file-text" class="h-4 w-4"></i>
                Export PDF
            </a>
            <a href="{{ route('reports.export', ['reportType' => $filters['report'], 'format' => 'csv'] + array_filter($filters)) }}" class="inline-flex items-center gap-2 rounded-2xl bg-cyan-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700">
                <i data-lucide="sheet" class="h-4 w-4"></i>
                Export CSV
            </a>
        </x-admin.page-header>
    </x-slot>

    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <x-admin.stat-card label="Complaints" :value="$widgets['total_complaints']" icon="badge-alert" tone="rose" />
            <x-admin.stat-card label="Resolution Rate" :value="$widgets['resolution_rate'].'%'" icon="badge-check" tone="emerald" />
            <x-admin.stat-card label="Escalation Rate" :value="$widgets['escalation_rate'].'%'" icon="trending-up" tone="amber" />
            <x-admin.stat-card label="SLA Hit Rate" :value="$widgets['sla_hit_rate'].'%'" icon="timer-reset" />
            <x-admin.stat-card label="Feedback Entries" :value="$widgets['feedback_count']" icon="messages-square" tone="amber" />
        </div>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <form method="GET" action="{{ route('reports.index') }}" class="space-y-5">
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <label class="block text-sm text-slate-600 dark:text-slate-300">
                        Report
                        <select name="report" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                            @foreach ($definitions as $key => $label)
                                <option value="{{ $key }}" @selected($filters['report'] === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block text-sm text-slate-600 dark:text-slate-300">
                        Branch
                        <select name="branch" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                            <option value="">All branches</option>
                            @foreach ($options['branches'] as $branch)
                                <option value="{{ $branch->id }}" @selected((string) $filters['branch'] === (string) $branch->id)>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block text-sm text-slate-600 dark:text-slate-300">
                        Product / Service
                        <select name="product" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                            <option value="">All products</option>
                            @foreach ($options['products'] as $product)
                                <option value="{{ $product->id }}" @selected((string) $filters['product'] === (string) $product->id)>{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block text-sm text-slate-600 dark:text-slate-300">
                        Status
                        <select name="status" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                            <option value="">All statuses</option>
                            @foreach ($options['statuses'] as $key => $label)
                                <option value="{{ $key }}" @selected($filters['status'] === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block text-sm text-slate-600 dark:text-slate-300">
                        Category
                        <select name="category" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                            <option value="">All categories</option>
                            @foreach ($options['categories'] as $category)
                                <option value="{{ $category }}" @selected($filters['category'] === $category)>{{ ucfirst($category) }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block text-sm text-slate-600 dark:text-slate-300">
                        From
                        <input type="date" name="from_date" value="{{ $filters['from_date'] }}" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" />
                    </label>

                    <label class="block text-sm text-slate-600 dark:text-slate-300">
                        To
                        <input type="date" name="to_date" value="{{ $filters['to_date'] }}" class="mt-2 block w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" />
                    </label>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-cyan-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700">
                        <i data-lucide="filter" class="h-4 w-4"></i>
                        Generate Report
                    </button>
                    <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">
                        <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
                        Reset
                    </a>
                </div>
            </form>
        </section>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">{{ $report['title'] }}</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Live operational data with filtering, pagination, and export support.</p>
                    </div>
                </div>

                <x-reports.data-table :columns="$report['columns']" :rows="$report['rows']" />

                @if ($report['rows'] instanceof \Illuminate\Contracts\Pagination\Paginator)
                    <div class="mt-5">
                        {{ $report['rows']->links() }}
                    </div>
                @endif
            </section>

            <aside class="space-y-6">
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Schedule This Report</h3>
                    <form method="POST" action="{{ route('reports.schedules.store') }}" class="mt-5 space-y-4">
                        @csrf
                        <input type="hidden" name="report" value="{{ $filters['report'] }}">
                        <input type="hidden" name="branch" value="{{ $filters['branch'] }}">
                        <input type="hidden" name="product" value="{{ $filters['product'] }}">
                        <input type="hidden" name="status" value="{{ $filters['status'] }}">
                        <input type="hidden" name="category" value="{{ $filters['category'] }}">
                        <input type="hidden" name="from_date" value="{{ $filters['from_date'] }}">
                        <input type="hidden" name="to_date" value="{{ $filters['to_date'] }}">

                        <div>
                            <x-input-label for="schedule-name" value="Schedule Name" />
                            <x-text-input id="schedule-name" name="name" class="mt-1 block w-full" value="{{ $definitions[$filters['report']] }} Schedule" required />
                        </div>
                        <div>
                            <x-input-label for="schedule-format" value="Format" />
                            <select id="schedule-format" name="format" class="mt-1 block w-full rounded-2xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                                @foreach ($options['formats'] as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="schedule-frequency" value="Frequency" />
                            <select id="schedule-frequency" name="frequency" class="mt-1 block w-full rounded-2xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                                @foreach ($options['frequencies'] as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="schedule-recipients" value="Recipients (optional)" />
                            <textarea id="schedule-recipients" name="recipients" rows="3" class="mt-1 block w-full rounded-2xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" placeholder="manager@gledco.com, ops@gledco.com"></textarea>
                        </div>
                        <label class="flex items-center gap-3 text-sm font-medium text-slate-700 dark:text-slate-200">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                            Active schedule
                        </label>
                        <x-primary-button>Create Schedule</x-primary-button>
                    </form>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Scheduled Reports</h3>
                    <div class="mt-4 space-y-4">
                        @forelse ($scheduledReports as $scheduled)
                            <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-800">
                                <form method="POST" action="{{ route('reports.schedules.update', $scheduled) }}" class="space-y-3">
                                    @csrf
                                    @method('PATCH')
                                    <div>
                                        <x-input-label :for="'scheduled-name-'.$scheduled->id" value="Name" />
                                        <x-text-input :id="'scheduled-name-'.$scheduled->id" name="name" class="mt-1 block w-full" :value="$scheduled->name" required />
                                    </div>
                                    <input type="hidden" name="report" value="{{ $scheduled->report_type }}">
                                    <input type="hidden" name="branch" value="{{ $scheduled->filters['branch'] ?? '' }}">
                                    <input type="hidden" name="product" value="{{ $scheduled->filters['product'] ?? '' }}">
                                    <input type="hidden" name="status" value="{{ $scheduled->filters['status'] ?? '' }}">
                                    <input type="hidden" name="category" value="{{ $scheduled->filters['category'] ?? '' }}">
                                    <input type="hidden" name="from_date" value="{{ $scheduled->filters['from_date'] ?? '' }}">
                                    <input type="hidden" name="to_date" value="{{ $scheduled->filters['to_date'] ?? '' }}">
                                    <div class="grid gap-3 sm:grid-cols-2">
                                        <div>
                                            <x-input-label :for="'scheduled-format-'.$scheduled->id" value="Format" />
                                            <select id="{{ 'scheduled-format-'.$scheduled->id }}" name="format" class="mt-1 block w-full rounded-2xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                                                @foreach ($options['formats'] as $key => $label)
                                                    <option value="{{ $key }}" @selected($scheduled->format === $key)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label :for="'scheduled-frequency-'.$scheduled->id" value="Frequency" />
                                            <select id="{{ 'scheduled-frequency-'.$scheduled->id }}" name="frequency" class="mt-1 block w-full rounded-2xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                                                @foreach ($options['frequencies'] as $key => $label)
                                                    <option value="{{ $key }}" @selected($scheduled->frequency === $key)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <x-input-label :for="'scheduled-recipients-'.$scheduled->id" value="Recipients" />
                                        <textarea id="{{ 'scheduled-recipients-'.$scheduled->id }}" name="recipients" rows="2" class="mt-1 block w-full rounded-2xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">{{ collect($scheduled->recipients)->implode(', ') }}</textarea>
                                    </div>
                                    <label class="flex items-center gap-3 text-sm font-medium text-slate-700 dark:text-slate-200">
                                        <input type="checkbox" name="is_active" value="1" @checked($scheduled->is_active) class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                                        Active
                                    </label>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Next run: {{ $scheduled->next_run_at?->format('Y-m-d H:i') ?? 'Not set' }}</p>
                                    <x-primary-button>Save</x-primary-button>
                                </form>
                                <form method="POST" action="{{ route('reports.schedules.destroy', $scheduled) }}" class="mt-3" onsubmit="return confirm('Delete this scheduled report?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button>Delete</x-danger-button>
                                </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 dark:text-slate-400">No scheduled reports yet.</p>
                        @endforelse
                    </div>

                    <div class="mt-5">{{ $scheduledReports->links() }}</div>
                </section>
            </aside>
        </div>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Report Audit Log</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Tracks manual exports and scheduled report generation activity.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">When</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Action</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Report</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Format</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">User</th>
                            <th class="px-4 py-3 text-left font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-900">
                        @forelse ($auditLogs as $log)
                            <tr>
                                <td class="px-4 py-3 text-slate-700 dark:text-slate-200">{{ $log->generated_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-700 dark:text-slate-200">{{ str_replace('_', ' ', ucfirst($log->action)) }}</td>
                                <td class="px-4 py-3 text-slate-700 dark:text-slate-200">{{ $definitions[$log->report_type] ?? $log->report_type }}</td>
                                <td class="px-4 py-3 text-slate-700 dark:text-slate-200">{{ strtoupper($log->format ?? '-') }}</td>
                                <td class="px-4 py-3 text-slate-700 dark:text-slate-200">{{ $log->user?->name ?? 'System' }}</td>
                                <td class="px-4 py-3 text-slate-700 dark:text-slate-200">{{ ucfirst($log->status) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">No audit entries recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-5">{{ $auditLogs->links() }}</div>
        </section>
    </div>
</x-app-layout>
