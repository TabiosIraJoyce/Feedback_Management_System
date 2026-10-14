<x-app-layout>
        <x-slot name="header">
        <x-admin.page-header title="Department Management (archived)" description="This module has been removed from the main menu. The code remains available." icon="archive" />
    </x-slot>

    <div class="space-y-6">
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Department Directory (archived)</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">This page is retained but the menu entry is removed. Use for manual management if needed.</p>
                </div>

                <form method="GET" action="{{ route('departments.index') }}" class="grid gap-3 sm:grid-cols-[minmax(0,1fr),auto,auto] lg:w-[780px]">
                    <x-text-input type="search" name="search" placeholder="Search department, code, or QR path" class="w-full" value="{{ request('search') }}" />

                    <select name="branch" class="rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                        <option value="">All branches</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" @selected(request('branch') == $branch->id)>{{ $branch->name }}</option>
                        @endforeach
                    </select>

                    <select name="status" class="rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                        <option value="">All statuses</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    </select>
                </form>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[0.95fr,1.05fr]">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Add Department</h3>

                <form method="POST" action="{{ route('departments.store') }}" enctype="multipart/form-data" class="mt-5 space-y-4">
                    @csrf
                    <x-departments.form-fields :branches="$branches" prefix="create-department" />
                    <x-primary-button>Create Department</x-primary-button>
                </form>
            </section>

            <section class="space-y-4">
                @forelse ($departments as $department)
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                        <div class="mb-4 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div class="space-y-2">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">{{ $department->name }}</h3>
                                    <x-admin.status-badge :value="$department->status" />
                                </div>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $department->code }} • {{ $department->branch?->name ?? 'No branch assigned' }}</p>
                                @if ($department->description)
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $department->description }}</p>
                                @endif

                                <div class="mt-3 rounded-3xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300">
                                    <div class="font-semibold text-slate-900 dark:text-slate-100">Department feedback link</div>
                                    <a href="{{ $department->feedback_url }}" class="break-all text-cyan-600 hover:text-cyan-500 dark:text-cyan-300">{{ $department->feedback_url }}</a>
                                </div>

                                <div class="mt-3 flex items-center gap-3 rounded-3xl border border-slate-200 bg-slate-50 p-3 dark:border-slate-800 dark:bg-slate-900">
                                    <img src="{{ $department->qr_code_url }}" alt="{{ $department->name }} QR code" class="h-20 w-20 rounded-2xl bg-white p-2" />
                                    <div class="text-sm text-slate-600 dark:text-slate-300">This QR routes users directly to the {{ $department->name }} feedback page.</div>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-3">
                                <form method="POST" action="{{ route('departments.toggle-status', $department) }}">
                                    @csrf
                                    @method('PATCH')
                                    <x-secondary-button type="submit">{{ $department->status === 'active' ? 'Deactivate' : 'Activate' }}</x-secondary-button>
                                </form>
                                <form method="POST" action="{{ route('departments.destroy', $department) }}" onsubmit="return confirm('Delete this department?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button>Delete</x-danger-button>
                                </form>
                            </div>
                        </div>

                        <div class="mb-6 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-600 dark:bg-slate-900 dark:text-slate-300">QR path: <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $department->slug }}</span></div>
                            <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-600 dark:bg-slate-900 dark:text-slate-300">Feedback tickets: <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $department->feedback_tickets_count }}</span></div>
                            <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-600 dark:bg-slate-900 dark:text-slate-300">Form submissions: <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $department->form_submissions_count }}</span></div>
                        </div>

                        <form method="POST" action="{{ route('departments.update', $department) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <x-departments.form-fields :branches="$branches" :department="$department" :prefix="'department-'.$department->id" />
                            <div class="flex flex-wrap gap-3">
                                <x-primary-button>Save Changes</x-primary-button>
                                @if ($department->logo)
                                    <div class="flex items-center gap-3 rounded-2xl bg-slate-50 p-3 text-sm text-slate-600 dark:bg-slate-900 dark:text-slate-300">
                                        <img src="{{ Storage::disk('public')->url($department->logo) }}" alt="Department logo" class="h-10 w-10 rounded-xl object-cover" />
                                        <span>Current logo</span>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                @empty
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 text-center text-slate-600 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300">
                        No departments found. Add one to get started.
                    </div>
                @endforelse
            </section>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            {{ $departments->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
