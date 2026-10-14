<x-guest-layout>
    <div
        x-data="publicComplaintTracker({
            endpoint: '{{ route('track-complaint.status-feed', $complaint->reference_no) }}',
            initial: @js($trackingSnapshot),
        })"
        x-init="init()"
        class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(34,211,238,0.16),_transparent_35%),linear-gradient(180deg,#020617_0%,#0f172a_100%)] px-4 py-12 text-slate-100 sm:px-6 lg:px-8"
    >
        <div class="mx-auto max-w-7xl space-y-8">
            <section class="rounded-[2rem] border border-slate-800 bg-slate-900/95 p-8 shadow-2xl shadow-slate-950/20 backdrop-blur-xl sm:p-10">
                <div class="flex flex-col gap-6 xl:flex-row xl:items-start xl:justify-between">
                    <div class="space-y-3">
                        <p class="text-sm uppercase tracking-[0.4em] text-cyan-300/80">{{ __('Complaint Status') }}</p>
                        <h1 class="text-3xl font-semibold text-white sm:text-4xl">{{ $complaint->reference_no }}</h1>
                        <p class="text-sm text-slate-400">{{ __('Public status view for the complaint submitted to :branch.', ['branch' => $complaint->branch->name]) }}</p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button type="button" @click="toggleMonitoring()" class="inline-flex items-center gap-2 rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:bg-slate-900">
                            <i data-lucide="activity" class="h-4 w-4"></i>
                            <span x-text="monitoring ? translations.liveMonitoringOn : translations.resumeLiveMonitoring"></span>
                        </button>
                        <button type="button" @click="enableBrowserAlerts()" class="inline-flex items-center gap-2 rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:bg-slate-900">
                            <i data-lucide="bell-ring" class="h-4 w-4"></i>
                            {{ __('Browser alerts') }}
                        </button>
                        <a href="{{ route('track-complaint.summary', $complaint->reference_no) }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:bg-slate-900">
                            <i data-lucide="file-text" class="h-4 w-4"></i>
                            {{ __('Resolution Summary') }}
                        </a>
                        @if ($canSubmitSurvey)
                            <a href="{{ route('track-complaint.survey', $complaint->reference_no) }}" class="inline-flex items-center gap-2 rounded-2xl bg-cyan-500 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400">
                                <i data-lucide="star" class="h-4 w-4"></i>
                                {{ __('Satisfaction Survey') }}
                            </a>
                        @endif
                    </div>
                </div>

                <div class="mt-6 grid gap-4 lg:grid-cols-[minmax(0,1fr)_280px]">
                    <div class="rounded-3xl border border-cyan-500/20 bg-cyan-500/10 p-4 text-sm text-cyan-100">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="font-semibold">{{ __('Visibility of system status') }}</p>
                                <p class="mt-1 text-cyan-50/90">{{ __('This page checks for complaint updates automatically and refreshes if the public status changes.') }}</p>
                            </div>
                            <div class="text-right text-xs uppercase tracking-[0.3em] text-cyan-200/80">
                                <p x-text="monitoring ? translations.monitoringActive : translations.monitoringPaused"></p>
                                <p class="mt-2 normal-case tracking-normal text-cyan-100" x-text="status.updated_human ? `${translations.lastUpdate} ${status.updated_human}` : translations.waitingForFirstUpdate"></p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-4 text-sm text-slate-300">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Estimated Resolution') }}</p>
                        <p class="mt-2 text-lg font-semibold text-white">{{ $estimatedResolution['date'] ?? __('Not available yet') }}</p>
                        <p class="mt-1 text-sm text-slate-400">{{ $estimatedResolution['source'] ?? __('The team will update this as more information becomes available.') }}</p>
                    </div>
                </div>

                @if (session('status'))
                    <div class="mt-6 rounded-3xl border border-cyan-500/20 bg-cyan-500/10 p-4 text-sm text-cyan-100">
                        {{ session('status') }}
                    </div>
                @endif
            </section>

            <div class="grid gap-8 xl:grid-cols-[minmax(0,1.1fr)_360px]">
                <section class="space-y-8">
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-5">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Current Status') }}</p>
                            <p class="mt-2 text-lg font-semibold text-white" x-text="status.status_label">{{ __(\Illuminate\Support\Str::of($complaint->status)->replace('_', ' ')->title()->toString()) }}</p>
                        </div>
                        <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-5">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('SLA') }}</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ $sla['label'] }}</p>
                            <p class="mt-1 text-sm text-slate-400">{{ $sla['details'] }}</p>
                        </div>
                        <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-5">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Escalation') }}</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ $escalation['is_escalated'] ? __('Escalated') : __('Normal') }}</p>
                            <p class="mt-1 text-sm text-slate-400">{{ __(':count event(s)', ['count' => $escalation['count']]) }}</p>
                        </div>
                        <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-5">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Assigned Department') }}</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ $complaint->department?->name ?? __('Not assigned yet') }}</p>
                        </div>
                    </div>

                    <section class="rounded-3xl border border-slate-800 bg-slate-900/90 p-6">
                        <div class="mb-5 flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Timeline') }}</p>
                                <h2 class="mt-2 text-2xl font-semibold text-white">{{ __('Complaint Progress') }}</h2>
                                <p class="mt-1 text-sm text-slate-400">{{ __('The step timeline makes system status visible even for first-time users.') }}</p>
                            </div>
                            <button type="button" @click="readStatusSummary()" class="rounded-full border border-slate-700 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-slate-300">{{ __('Read status') }}</button>
                        </div>

                        <x-tracking.complaint-timeline :steps="$timelineSteps" />
                    </section>

                    <section class="rounded-3xl border border-slate-800 bg-slate-900/90 p-6">
                        <div class="mb-5 flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Complaint Details') }}</p>
                                <h2 class="mt-2 text-2xl font-semibold text-white">{{ $complaint->subject }}</h2>
                            </div>
                            <i data-lucide="file-search" class="h-5 w-5 text-cyan-300"></i>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-5">
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Category') }}</p>
                                <p class="mt-2 text-sm text-white">{{ __(ucfirst($complaint->category)) }}</p>
                            </div>
                            <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-5">
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Priority') }}</p>
                                <p class="mt-2 text-sm text-white">{{ __(ucfirst($complaint->priority)) }}</p>
                            </div>
                            <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-5">
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Submitted') }}</p>
                                <p class="mt-2 text-sm text-white">{{ $complaint->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-5">
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Estimated Resolution') }}</p>
                                <p class="mt-2 text-sm text-white">{{ $estimatedResolution['relative'] ?? __('Not available yet') }}</p>
                            </div>
                        </div>

                        <div class="mt-4 rounded-3xl border border-slate-800 bg-slate-950/80 p-5 text-sm leading-7 text-slate-300">
                            {{ $complaint->message }}
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-800 bg-slate-900/90 p-6">
                        <div class="mb-5 flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Public History') }}</p>
                                <h2 class="mt-2 text-2xl font-semibold text-white">{{ __('Latest Updates') }}</h2>
                            </div>
                            <i data-lucide="history" class="h-5 w-5 text-cyan-300"></i>
                        </div>

                        <div class="space-y-4">
                            @foreach ($historyEntries as $entry)
                                <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-5">
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                        <p class="font-semibold text-white">{{ $entry['title'] }}</p>
                                        <p class="text-sm text-slate-400">{{ $entry['created_at']->format('M d, Y h:i A') }}</p>
                                    </div>
                                    <p class="mt-3 text-sm leading-7 text-slate-300">{{ $entry['note'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </section>

                <aside class="space-y-6">
                    <section class="rounded-3xl border border-slate-800 bg-slate-900/90 p-6">
                        <div class="flex items-center gap-3">
                            <i data-lucide="building-2" class="h-5 w-5 text-cyan-300"></i>
                            <h2 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">{{ __('Branch & Department') }}</h2>
                        </div>
                        <div class="mt-5 space-y-4 text-sm text-slate-300">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Branch') }}</p>
                                <p class="mt-2 text-white">{{ $complaint->branch->name }}</p>
                                <p class="mt-1 text-slate-400">{{ $complaint->branch->address }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Department') }}</p>
                                <p class="mt-2 text-white">{{ $complaint->department?->name ?? __('Not assigned yet') }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Contact') }}</p>
                                <p class="mt-2 text-white">{{ $complaint->branch->contact_number ?? __('Not available') }}</p>
                                <p class="mt-1 text-slate-400">{{ $complaint->branch->email ?? __('No email listed') }}</p>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-800 bg-slate-900/90 p-6">
                        <div class="flex items-center gap-3">
                            <i data-lucide="shield-check" class="h-5 w-5 text-cyan-300"></i>
                            <h2 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">{{ __('Resolution Status') }}</h2>
                        </div>
                        <div class="mt-5 space-y-4 text-sm text-slate-300">
                            <div class="rounded-2xl border border-slate-800 bg-slate-950/80 p-4">
                                <p class="font-semibold text-white">{{ __('Summary') }}</p>
                                <p class="mt-2 text-slate-400">{{ $complaint->resolution_summary ?: __('A resolution summary will appear here once the complaint has been resolved.') }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-800 bg-slate-950/80 p-4">
                                <p class="font-semibold text-white">{{ __('Estimated completion') }}</p>
                                <p class="mt-2 text-slate-400">{{ $estimatedResolution['date'] ?? __('Not available yet') }}</p>
                                <p class="mt-2 text-xs text-slate-500">{{ $estimatedResolution['source'] ?? '' }}</p>
                            </div>
                            @if ($escalation['latest_note'])
                                <div class="rounded-2xl border border-rose-500/20 bg-rose-500/10 p-4">
                                    <p class="font-semibold text-rose-100">{{ __('Escalation Update') }}</p>
                                    <p class="mt-2 text-rose-200/90">{{ $escalation['latest_note'] }}</p>
                                </div>
                            @endif
                        </div>
                    </section>

                    @if ($canPublicReopen)
                        <section x-data="{ open: false }" class="rounded-3xl border border-slate-800 bg-slate-900/90 p-6">
                            <div class="flex items-center gap-3">
                                <i data-lucide="rotate-ccw" class="h-5 w-5 text-cyan-300"></i>
                                <h2 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">{{ __('Need More Help?') }}</h2>
                            </div>
                            <p class="mt-4 text-sm leading-7 text-slate-400">{{ __('If the issue is still unresolved, you can request a reopen within the allowed policy window.') }}</p>
                            <button type="button" @click="open = !open" class="mt-4 inline-flex items-center gap-2 rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:bg-slate-900">
                                <i data-lucide="message-square-warning" class="h-4 w-4"></i>
                                {{ __('Reopen Complaint') }}
                            </button>
                            <form x-show="open" x-transition method="POST" action="{{ route('track-complaint.reopen', $complaint->reference_no) }}" class="mt-4 space-y-3" style="display: none;">
                                @csrf
                                <textarea name="reason" rows="4" class="block w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-sm text-slate-100 focus:border-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-500/20" placeholder="{{ __('Tell us why this complaint should be reopened.') }}" required>{{ old('reason') }}</textarea>
                                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-cyan-500 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400">
                                    <i data-lucide="send" class="h-4 w-4"></i>
                                    {{ __('Submit Reopen Request') }}
                                </button>
                            </form>
                        </section>
                    @endif
                </aside>
            </div>
        </div>
    </div>

    <script>
        function publicComplaintTracker(config) {
            return {
                status: config.initial,
                monitoring: true,
                alertsEnabled: false,
                intervalHandle: null,
                translations: @js([
                    'liveMonitoringOn' => __('Live monitoring on'),
                    'resumeLiveMonitoring' => __('Resume live monitoring'),
                    'monitoringActive' => __('Monitoring active'),
                    'monitoringPaused' => __('Monitoring paused'),
                    'waitingForFirstUpdate' => __('Waiting for first update'),
                    'lastUpdate' => __('Last update'),
                    'liveMonitoringResumed' => __('Live monitoring resumed.'),
                    'liveMonitoringPaused' => __('Live monitoring paused.'),
                    'browserAlertsUnsupported' => __('Browser alerts are not supported on this device.'),
                    'browserAlertsEnabled' => __('Browser alerts enabled for complaint updates.'),
                    'browserAlertsNotEnabled' => __('Browser alerts were not enabled.'),
                    'complaintNowStatusRefreshing' => __('Complaint :reference is now :status. Refreshing the page.'),
                    'complaintUpdate' => __('Complaint Update'),
                    'complaintNowStatus' => __(':reference is now :status.'),
                    'complaintCurrentlyStatus' => __('Complaint :reference is currently :status.'),
                    'lastVisibleUpdateWas' => __('Last visible update was :time.'),
                    'recently' => __('recently'),
                ]),
                init() {
                    this.intervalHandle = window.setInterval(() => {
                        if (this.monitoring) {
                            this.checkForUpdates();
                        }
                    }, 20000);
                },
                toggleMonitoring() {
                    this.monitoring = !this.monitoring;
                    window.FMSHci.announce(this.monitoring ? this.translations.liveMonitoringResumed : this.translations.liveMonitoringPaused);
                },
                async enableBrowserAlerts() {
                    if (!('Notification' in window)) {
                        window.FMSHci.announce(this.translations.browserAlertsUnsupported);
                        return;
                    }

                    const permission = await Notification.requestPermission();
                    this.alertsEnabled = permission === 'granted';
                    window.FMSHci.announce(this.alertsEnabled ? this.translations.browserAlertsEnabled : this.translations.browserAlertsNotEnabled);
                },
                async checkForUpdates() {
                    try {
                        const response = await fetch(config.endpoint, {
                            headers: {
                                Accept: 'application/json',
                            },
                        });

                        if (!response.ok) {
                            return;
                        }

                        const latest = await response.json();

                        if (latest.updated_at && latest.updated_at !== this.status.updated_at) {
                            const message = this.translations.complaintNowStatusRefreshing
                                .replace(':reference', latest.reference_no)
                                .replace(':status', latest.status_label);
                            window.FMSHci.announce(message);

                            if (this.alertsEnabled && Notification.permission === 'granted') {
                                new Notification(this.translations.complaintUpdate, {
                                    body: this.translations.complaintNowStatus
                                        .replace(':reference', latest.reference_no)
                                        .replace(':status', latest.status_label),
                                });
                            }

                            window.setTimeout(() => window.location.reload(), 1500);
                            return;
                        }

                        this.status = latest;
                    } catch (error) {
                        // Ignore intermittent polling errors.
                    }
                },
                readStatusSummary() {
                    const summary = [
                        this.translations.complaintCurrentlyStatus
                            .replace(':reference', this.status.reference_no)
                            .replace(':status', this.status.status_label),
                        this.translations.lastVisibleUpdateWas
                            .replace(':time', this.status.updated_human || this.translations.recently),
                    ].join(' ');

                    window.FMSHci.speakText(summary);
                },
            };
        }
    </script>
</x-guest-layout>
