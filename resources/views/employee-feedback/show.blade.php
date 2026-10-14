<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Employee Feedback Detail" :description="$feedback->reference_no.' | '.$feedback->display_name" icon="message-square-more" />
    </x-slot>

    <div x-data="employeeFeedbackRealtime({{ auth()->id() }})" x-init="init()" class="space-y-6">
        <template x-if="message">
            <div class="rounded-3xl border border-cyan-200 bg-cyan-50 p-4 text-sm text-cyan-800 dark:border-cyan-500/30 dark:bg-cyan-500/10 dark:text-cyan-200" x-text="message"></div>
        </template>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
            <section class="space-y-6">
                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div class="space-y-3">
                            <div class="flex flex-wrap items-center gap-3">
                                <h2 class="text-2xl font-semibold text-slate-950 dark:text-slate-100">{{ $feedback->subject }}</h2>
                                <x-admin.status-badge :value="$feedback->status" />
                            </div>
                            <div class="flex flex-wrap gap-3 text-sm text-slate-500 dark:text-slate-400">
                                <span>{{ $feedback->display_name }}</span>
                                <span>{{ $feedback->department?->name ?? 'No department' }}</span>
                                <span>{{ $feedback->branch?->name ?? 'No branch' }}</span>
                            </div>
                            <div class="flex flex-wrap gap-3 text-xs uppercase tracking-wide text-slate-400">
                                <span>{{ str_replace('_', ' ', $feedback->priority) }}</span>
                                <span>{{ str_replace('_', ' ', $feedback->category) }}</span>
                                <span>Submitted {{ $feedback->created_at?->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @can('update', $feedback)
                                <a href="{{ route('employee-feedback.edit', $feedback) }}" class="rounded-xl border border-cyan-200 bg-cyan-50 px-4 py-2 text-sm font-medium text-cyan-700 dark:border-cyan-500/30 dark:bg-cyan-500/10 dark:text-cyan-200">Edit</a>
                            @endcan
                            @can('delete', $feedback)
                                <form method="POST" action="{{ route('employee-feedback.destroy', $feedback) }}" onsubmit="return confirm('Archive this employee feedback?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button>Archive</x-danger-button>
                                </form>
                            @endcan
                        </div>
                    </div>

                    <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-5 text-sm leading-7 text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">
                        {{ $feedback->description }}
                    </div>

                    @if ($feedback->attachment_url)
                        <div class="mt-4 rounded-3xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-950">
                            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Attachment</p>
                            <a href="{{ $feedback->attachment_url }}" target="_blank" rel="noreferrer" class="mt-2 inline-flex items-center gap-2 text-sm text-cyan-600 hover:text-cyan-500 dark:text-cyan-300">
                                <i data-lucide="paperclip" class="h-4 w-4"></i>
                                {{ $feedback->attachment_original_name ?? 'Open attachment' }}
                            </a>
                        </div>
                    @endif
                </article>

                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Status History</h3>
                    </div>

                    <div class="space-y-4">
                        @foreach ($feedback->statusHistories as $history)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $history->from_status ? str_replace('_', ' ', $history->from_status).' -> ' : '' }}{{ str_replace('_', ' ', $history->to_status) }}</p>
                                        <p class="mt-1 text-xs uppercase tracking-wide text-slate-400">{{ $history->user?->name ?? 'System' }} | {{ $history->created_at?->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                                @if ($history->note)
                                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">{{ $history->note }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            </section>

            <aside class="space-y-6">
                @canany(['review', 'reopen'], $feedback)
                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Review Workflow</h3>
                        @can('review', $feedback)
                            <form method="POST" action="{{ route('employee-feedback.status.update', $feedback) }}" class="mt-4 space-y-4">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}" @selected($feedback->status === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <textarea name="note" rows="3" placeholder="Add a workflow note" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"></textarea>
                                <x-primary-button>Update Status</x-primary-button>
                            </form>
                        @endcan

                        <div class="mt-4 flex flex-wrap gap-3">
                            @can('reopen', $feedback)
                                @if (in_array($feedback->status, ['resolved', 'closed'], true))
                                    <form method="POST" action="{{ route('employee-feedback.reopen', $feedback) }}">
                                        @csrf
                                        @method('PATCH')
                                        <x-secondary-button>Reopen</x-secondary-button>
                                    </form>
                                @endif
                            @endcan

                            @can('review', $feedback)
                                @if ($feedback->status !== 'closed')
                                    <form method="POST" action="{{ route('employee-feedback.close', $feedback) }}">
                                        @csrf
                                        @method('PATCH')
                                        <x-danger-button>Close</x-danger-button>
                                    </form>
                                @endif
                            @endcan
                        </div>
                    </section>
                @endcanany

                @can('review', $feedback)
                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Internal Notes</h3>
                        <form method="POST" action="{{ route('employee-feedback.notes.update', $feedback) }}" class="mt-4 space-y-4">
                            @csrf
                            @method('PATCH')
                            <textarea name="internal_notes" rows="8" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">{{ old('internal_notes', $feedback->internal_notes) }}</textarea>
                            <x-primary-button>Save Notes</x-primary-button>
                        </form>
                    </section>
                @endcan

                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Tracking Snapshot</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-900">Reference: <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $feedback->reference_no }}</span></div>
                        <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-900">Current status: <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $statuses[$feedback->status] ?? $feedback->status }}</span></div>
                        <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-900">Reopened count: <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $feedback->reopened_count }}</span></div>
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
                            if (notification.employee_feedback_id !== {{ $feedback->id }}) {
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
