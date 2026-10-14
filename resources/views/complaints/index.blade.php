<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Complaint Management" description="Track, assign, close, and review complaints by role and branch scope." icon="badge-alert">
            @can('submit complaints')
                <a href="{{ route('complaints.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-cyan-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-700">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    New Complaint
                </a>
            @endcan
        </x-admin.page-header>
    </x-slot>

    <div class="space-y-4">
        @foreach ($complaints as $complaint)
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <h3 class="text-lg font-semibold text-slate-950">{{ $complaint->subject }}</h3>
                            <x-admin.status-badge :value="$complaint->status" />
                        </div>
                        <p class="mt-2 text-sm text-slate-500">{{ $complaint->reference_no }} • {{ $complaint->branch->name }} • Priority: {{ ucfirst($complaint->priority) }}</p>
                        <p class="mt-3 max-w-3xl text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($complaint->message, 180) }}</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('complaints.show', $complaint) }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">View</a>
                        @can('update', $complaint)
                            <a href="{{ route('complaints.edit', $complaint) }}" class="rounded-xl border border-cyan-200 bg-cyan-50 px-4 py-2 text-sm font-medium text-cyan-700 hover:bg-cyan-100">Edit</a>
                        @endcan
                    </div>
                </div>

                <div class="mt-5 grid gap-4 xl:grid-cols-3">
                    <div class="rounded-2xl bg-slate-50 p-4 text-sm">
                        <p class="font-semibold text-slate-900">Submitter</p>
                        <p class="mt-1 text-slate-600">{{ $complaint->customer_name }}</p>
                        <p class="text-slate-500">{{ $complaint->customer_email }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4 text-sm">
                        <p class="font-semibold text-slate-900">Assigned To</p>
                        <p class="mt-1 text-slate-600">{{ $complaint->assignee?->name ?? 'Unassigned' }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4 text-sm">
                        <p class="font-semibold text-slate-900">Due Date</p>
                        <p class="mt-1 text-slate-600">{{ $complaint->due_at?->format('M d, Y h:i A') ?? 'No deadline set' }}</p>
                    </div>
                </div>
            </div>
        @endforeach

        {{ $complaints->links() }}
    </div>
</x-app-layout>
