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
        </section>

        <aside class="space-y-4">
            @can('assign complaints')
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
            @endcan

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-950">Actions</h3>
                <div class="mt-4 flex flex-wrap gap-3">
                    @can('update', $complaint)
                        <a href="{{ route('complaints.edit', $complaint) }}" class="rounded-xl border border-cyan-200 bg-cyan-50 px-4 py-2 text-sm font-medium text-cyan-700">Edit</a>
                    @endcan

                    @can('close complaints')
                        <form method="POST" action="{{ route('complaints.close', $complaint) }}">
                            @csrf
                            @method('PATCH')
                            <x-primary-button>Close</x-primary-button>
                        </form>
                    @endcan

                    @can('reopen complaints')
                        <form method="POST" action="{{ route('complaints.reopen', $complaint) }}">
                            @csrf
                            @method('PATCH')
                            <x-secondary-button>Reopen</x-secondary-button>
                        </form>
                    @endcan

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
