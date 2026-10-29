<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header :title="$complaint->subject" :description="$complaint->reference_no.' - '.$complaint->branch->name" icon="file-warning">
            <x-admin.status-badge :value="$complaint->status" />
        </x-admin.page-header>
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-[1.3fr,0.7fr]">
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-950">Complaint Details</h3>
            <dl class="mt-5 grid gap-4 md:grid-cols-2">
                <div><dt class="text-sm font-medium text-slate-500">Customer</dt><dd class="mt-1 text-sm text-slate-900">{{ $complaint->customer_name }}</dd></div>
                <div><dt class="text-sm font-medium text-slate-500">Email</dt><dd class="mt-1 text-sm text-slate-900">{{ $complaint->customer_email ?? 'N/A' }}</dd></div>
                <div><dt class="text-sm font-medium text-slate-500">Phone</dt><dd class="mt-1 text-sm text-slate-900">{{ $complaint->customer_phone ?? 'N/A' }}</dd></div>
                <div><dt class="text-sm font-medium text-slate-500">Priority</dt><dd class="mt-1 text-sm text-slate-900">{{ ucfirst($complaint->priority) }}</dd></div>
                <div><dt class="text-sm font-medium text-slate-500">Department</dt><dd class="mt-1 text-sm text-slate-900">{{ $complaint->department?->name ?? 'Unassigned' }}</dd></div>
                <div><dt class="text-sm font-medium text-slate-500">Assigned To</dt><dd class="mt-1 text-sm text-slate-900">{{ $complaint->assignee?->name ?? 'Unassigned' }}</dd></div>
                <div><dt class="text-sm font-medium text-slate-500">Due Date</dt><dd class="mt-1 text-sm text-slate-900">{{ $complaint->due_at?->format('M d, Y h:i A') ?? 'Not set' }}</dd></div>
                <div><dt class="text-sm font-medium text-slate-500">Public Reopen</dt><dd class="mt-1 text-sm text-slate-900">{{ $complaint->allow_public_reopen ? 'Allowed' : 'Disabled' }}</dd></div>
            </dl>

            <div class="mt-6 rounded-2xl bg-slate-50 p-5 text-sm leading-7 text-slate-700">
                {{ $complaint->message }}
            </div>

            <div class="mt-6 rounded-2xl bg-cyan-50 p-5 text-sm leading-7 text-slate-700">
                <p class="font-semibold text-slate-900">Public Resolution Summary</p>
                <p class="mt-2">{{ $complaint->resolution_summary ?: 'No public summary added yet.' }}</p>
            </div>

            <div class="mt-6">
                <h4 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Attachments</h4>
                <div class="mt-4">
                    <x-attachments.list :attachments="$complaint->attachments" :can-delete="auth()->user()->can('update', $complaint)" />
                </div>
            </div>

            <div class="mt-8">
                <h4 class="text-lg font-semibold text-slate-950">{{ __('Complaint History') }}</h4>
                <ol class="mt-4 space-y-3">
                    @foreach ($complaint->statusHistory as $history)
                        <li class="rounded-xl border p-4 {{ $history->visible_to_public ? 'border-slate-200 bg-slate-50' : 'border-amber-200 bg-amber-50' }}">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="font-semibold text-slate-900">{{ $history->title ?? $history->action ?? $history->status }}</p>
                                <time class="text-xs text-slate-500">{{ $history->created_at?->format('M d, Y h:i A') }}</time>
                            </div>
                            <p class="mt-1 text-xs font-medium {{ $history->visible_to_public ? 'text-slate-500' : 'text-amber-800' }}">
                                {{ $history->actor?->name ?? __('System') }}
                                @unless ($history->visible_to_public) · {{ __('Internal') }} @endunless
                            </p>
                            @if ($history->escalation_reason)
                                <p class="mt-3 text-sm text-slate-700"><span class="font-semibold">{{ __('Escalation reason:') }}</span> {{ $history->escalation_reason }}</p>
                            @endif
                            @if ($history->note)
                                <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $history->note }}</p>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>

        <aside class="space-y-4">
            @if (auth()->user()->hasRole(['Admin', 'Super Admin']) && auth()->user()->can('assign complaints'))
                <form method="POST" action="{{ route('complaints.assign', $complaint) }}" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    @csrf
                    @method('PATCH')
                    <h3 class="text-lg font-semibold text-slate-950">Assign Complaint</h3>
                    <select name="assigned_to_user_id" class="mt-4 block w-full rounded-xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                        @foreach ($assignableUsers as $user)
                            <option value="{{ $user->id }}" @selected($complaint->assigned_to_user_id === $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <x-primary-button class="mt-4">Assign</x-primary-button>
                </form>
            @endif

            @if ($canHandleComplaint)
                @if (in_array($complaint->status, ['open', 'reopened'], true))
                    <form method="POST" action="{{ route('complaints.start', $complaint) }}" class="rounded-3xl border border-blue-200 bg-white p-6 shadow-sm">
                        @csrf
                        @method('PATCH')
                        <h3 class="text-lg font-semibold text-slate-950">{{ __('Begin Review') }}</h3>
                        <p class="mt-1 text-sm text-slate-600">{{ __('Mark this complaint as under active investigation.') }}</p>
                        <x-primary-button class="mt-4">{{ __('Start Investigation') }}</x-primary-button>
                    </form>
                @endif

                @if (auth()->user()->hasRole('Supervisor') && (int) $complaint->escalation_level === 0)
                    <form method="POST" action="{{ route('complaints.escalate.department-head', $complaint) }}" class="rounded-3xl border border-amber-200 bg-white p-6 shadow-sm">
                        @csrf
                        <h3 class="text-lg font-semibold text-slate-950">{{ __('Escalate to Department Head') }}</h3>
                        <label class="mt-4 block text-sm font-medium text-slate-700">{{ __('Reason for escalation') }}<textarea name="reason" required minlength="10" maxlength="3000" rows="3" class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm" placeholder="{{ __('Explain why this needs Department Head review.') }}">{{ old('reason') }}</textarea></label>
                        <label class="mt-4 block text-sm font-medium text-slate-700">{{ __('Actions already taken') }}<textarea name="actions_taken" required minlength="10" maxlength="5000" rows="3" class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm" placeholder="{{ __('Summarize the investigation and actions taken so far.') }}">{{ old('actions_taken') }}</textarea></label>
                        <x-primary-button class="mt-4">{{ __('Escalate to Department Head') }}</x-primary-button>
                    </form>
                @elseif (auth()->user()->hasRole('Department Head') && (int) $complaint->escalation_level === 1)
                    <form method="POST" action="{{ route('complaints.escalate.upper-management', $complaint) }}" class="rounded-3xl border border-violet-200 bg-white p-6 shadow-sm">
                        @csrf
                        <h3 class="text-lg font-semibold text-slate-950">{{ __('Escalate to Upper Management') }}</h3>
                        <label class="mt-4 block text-sm font-medium text-slate-700">{{ __('Escalation reason') }}<textarea name="reason" required minlength="10" maxlength="3000" rows="4" class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm">{{ old('reason') }}</textarea></label>
                        <x-primary-button class="mt-4">{{ __('Escalate to Upper Management') }}</x-primary-button>
                    </form>
                    <form method="POST" action="{{ route('complaints.return-to-supervisor', $complaint) }}" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        @csrf
                        <h3 class="text-lg font-semibold text-slate-950">{{ __('Return to Supervisor') }}</h3>
                        <label class="mt-4 block text-sm font-medium text-slate-700">{{ __('Instructions for further action') }}<textarea name="reason" required minlength="10" maxlength="3000" rows="3" class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm">{{ old('reason') }}</textarea></label>
                        <x-secondary-button class="mt-4">{{ __('Return to Supervisor') }}</x-secondary-button>
                    </form>
                @endif

                @if (! in_array($complaint->status, ['resolved', 'closed'], true))
                    <form method="POST" action="{{ route('complaints.resolve', $complaint) }}" class="rounded-3xl border border-teal-200 bg-white p-6 shadow-sm">
                        @csrf
                        <h3 class="text-lg font-semibold text-slate-950">{{ __('Record Resolution') }}</h3>
                        <label class="mt-4 block text-sm font-medium text-slate-700">{{ __('Resolution details') }}<textarea name="resolution" required minlength="10" maxlength="5000" rows="4" class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm">{{ old('resolution') }}</textarea></label>
                        <x-primary-button class="mt-4">{{ __('Resolve Complaint') }}</x-primary-button>
                    </form>
                @endif

                <form method="POST" action="{{ route('complaints.internal-notes.store', $complaint) }}" class="rounded-3xl border border-amber-200 bg-amber-50/50 p-6 shadow-sm">
                    @csrf
                    <h3 class="text-lg font-semibold text-slate-950">{{ __('Internal Note') }}</h3>
                    <p class="mt-1 text-xs text-slate-600">{{ __('Only authorized staff can see internal notes.') }}</p>
                    <label class="mt-4 block text-sm font-medium text-slate-700"><span class="sr-only">{{ __('Internal note') }}</span><textarea name="note" required minlength="3" maxlength="5000" rows="3" class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm">{{ old('note') }}</textarea></label>
                    <x-secondary-button class="mt-4">{{ __('Add Internal Note') }}</x-secondary-button>
                </form>
            @endif

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-950">Actions</h3>
                <div class="mt-4 flex flex-wrap gap-3">
                    @can('update', $complaint)
                        <a href="{{ route('complaints.edit', $complaint) }}" class="rounded-xl border border-cyan-200 bg-cyan-50 px-4 py-2 text-sm font-medium text-cyan-700">Edit</a>
                    @endcan

                    @if ($canHandleComplaint && auth()->user()->can('close complaints'))
                        <form method="POST" action="{{ route('complaints.close', $complaint) }}">
                            @csrf
                            @method('PATCH')
                            <x-primary-button>Close</x-primary-button>
                        </form>
                    @endif

                    @if ($canHandleComplaint && auth()->user()->can('reopen complaints') && in_array($complaint->status, ['resolved', 'closed'], true))
                        <form method="POST" action="{{ route('complaints.reopen', $complaint) }}">
                            @csrf
                            @method('PATCH')
                            <x-secondary-button>Reopen</x-secondary-button>
                        </form>
                    @endif

                    @can('delete', $complaint)
                        <form method="POST" action="{{ route('complaints.destroy', $complaint) }}" onsubmit="return confirm('Archive this complaint?')">
                            @csrf
                            @method('DELETE')
                            <x-danger-button>Archive</x-danger-button>
                        </form>
                    @endcan
                </div>
            </div>
        </aside>
    </div>
</x-app-layout>
