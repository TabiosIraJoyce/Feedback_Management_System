<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Search Results" description="Find complaints and branches from one place." icon="search" />
    </x-slot>

    <div class="space-y-6">
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <form action="{{ route('search') }}" method="GET" class="flex flex-col gap-3 sm:flex-row">
                <input
                    type="search"
                    name="query"
                    value="{{ $query }}"
                    placeholder="Search complaints, branches..."
                    class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                />
                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-cyan-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700">
                    Search
                </button>
            </form>

            @if ($query === '')
                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Enter a keyword to search complaints and branches.</p>
            @else
                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">
                    Showing results for <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $query }}</span>.
                </p>
            @endif
        </section>

        <div class="grid gap-6 xl:grid-cols-2">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Complaints</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $complaints->count() }} result(s)</p>
                    </div>
                    <a href="{{ route('complaints.index') }}" class="text-sm font-medium text-cyan-700 hover:text-cyan-800 dark:text-cyan-300">Open complaints</a>
                </div>

                <div class="space-y-3">
                    @forelse ($complaints as $complaint)
                        <a href="{{ route('complaints.show', $complaint) }}" class="block rounded-2xl border border-slate-200 p-4 transition hover:border-cyan-300 hover:bg-cyan-50/40 dark:border-slate-800 dark:hover:border-cyan-500 dark:hover:bg-cyan-500/5">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-slate-950 dark:text-slate-100">{{ $complaint->subject }}</p>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $complaint->reference_no }} | {{ $complaint->branch->name }}</p>
                                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ \Illuminate\Support\Str::limit($complaint->message, 120) }}</p>
                                </div>
                                <x-admin.status-badge :value="$complaint->status" />
                            </div>
                        </a>
                    @empty
                        <p class="rounded-2xl border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">No complaint results found.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Branches</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $branches->count() }} result(s)</p>
                    </div>
                    @can('manage branches')
                        <a href="{{ route('branches.index') }}" class="text-sm font-medium text-cyan-700 hover:text-cyan-800 dark:text-cyan-300">Open branches</a>
                    @endcan
                </div>

                <div class="space-y-3">
                    @forelse ($branches as $branch)
                        @can('manage branches')
                            <a href="{{ route('branches.index', ['search' => $branch->name]) }}" class="block rounded-2xl border border-slate-200 p-4 transition hover:border-cyan-300 hover:bg-cyan-50/40 dark:border-slate-800 dark:hover:border-cyan-500 dark:hover:bg-cyan-500/5">
                                <p class="font-semibold text-slate-950 dark:text-slate-100">{{ $branch->name }}</p>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $branch->code }} | No business type</p>
                                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $branch->address ?: ($branch->description ?: 'No additional branch details available.') }}</p>
                            </a>
                        @else
                            <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-800">
                                <p class="font-semibold text-slate-950 dark:text-slate-100">{{ $branch->name }}</p>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $branch->code }} | No business type</p>
                                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $branch->address ?: ($branch->description ?: 'No additional branch details available.') }}</p>
                            </div>
                        @endcan
                    @empty
                        <p class="rounded-2xl border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">No branch results found.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
