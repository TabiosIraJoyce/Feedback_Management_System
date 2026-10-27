<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Feedback Center" description="Capture internal suggestions and customer feedback with visibility based on role." icon="messages-square">
            @canany(['submit feedback', 'manage feedback'])
                <a href="{{ route('feedback.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-cyan-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    New Feedback
                </a>
            @endcanany
        </x-admin.page-header>
    </x-slot>

    <div class="space-y-4">
        @foreach ($feedbackEntries as $entry)
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <h3 class="text-lg font-semibold text-slate-950">{{ $entry->subject }}</h3>
                            <x-admin.status-badge :value="$entry->status" />
                        </div>
                        <p class="mt-2 text-sm text-slate-500">{{ ucfirst($entry->type) }} • {{ $entry->branch?->name ?? 'No branch' }}</p>
                        <p class="mt-3 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($entry->message, 180) }}</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('feedback.show', ['feedback' => $entry->id]) }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700">View</a>
                        @can('update', $entry)
                            <a href="{{ route('feedback.edit', ['feedback' => $entry->id]) }}" class="rounded-xl border border-cyan-200 bg-cyan-50 px-4 py-2 text-sm font-medium text-cyan-700">Edit</a>
                        @endcan
                    </div>
                </div>
            </div>
        @endforeach

        {{ $feedbackEntries->links() }}
    </div>
</x-app-layout>
