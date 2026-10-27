<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="QR Feedback Generator" description="Manage branch and department QR feedback entry points, downloads, and print layouts." icon="qr-code">
            <a href="{{ route('admin.qr.index') }}" class="inline-flex items-center gap-2 rounded-2xl bg-cyan-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700">
                <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                Refresh QR Records
            </a>
        </x-admin.page-header>
    </x-slot>

    <div class="space-y-6">
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <form method="GET" action="{{ route('admin.qr.index') }}" class="grid gap-3 xl:grid-cols-[minmax(0,1fr),220px,180px,180px,auto]">
                <x-text-input type="search" name="search" placeholder="Search code, path, branch, or department" class="w-full" value="{{ request('search') }}" />

                <select name="branch" class="rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                    <option value="">All branches</option>
                    @foreach ($branchOptions as $branch)
                        <option value="{{ $branch->id }}" @selected(request('branch') == $branch->id)>{{ $branch->name }}</option>
                    @endforeach
                </select>

                <select name="type" class="rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                    <option value="all" @selected($type === 'all')>All types</option>
                    <option value="branch" @selected($type === 'branch')>Branch QR</option>
                    <option value="department" @selected($type === 'department')>Department QR</option>
                </select>

                <select name="status" class="rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                    <option value="all" @selected($status === 'all')>All statuses</option>
                    <option value="active" @selected($status === 'active')>Active</option>
                    <option value="inactive" @selected($status === 'inactive')>Inactive</option>
                </select>

                <x-primary-button>Apply Filters</x-primary-button>
            </form>
        </section>

        <section class="space-y-4">
                @forelse ($qrCodes as $qrCode)
                    <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                        <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">
                            <div class="flex gap-4">
                                <div class="rounded-3xl border border-slate-200 bg-white p-3 dark:border-slate-800 dark:bg-slate-900">
                                    <img src="{{ $qrCode->svg_preview_url }}" alt="{{ $qrCode->code }} QR code" class="h-32 w-32" />
                                </div>

                                <div class="space-y-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">{{ $qrCode->code }}</h3>
                                        <x-admin.status-badge :value="$qrCode->status" />
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700 dark:bg-slate-900 dark:text-slate-300">{{ $qrCode->type }}</span>
                                    </div>

                                    <div class="text-sm text-slate-500 dark:text-slate-400">
                                        <p>{{ $qrCode->branch?->name }}</p>
                                        @if ($qrCode->department)
                                            <p>{{ $qrCode->department->name }}</p>
                                        @endif
                                    </div>

                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3 text-sm dark:border-slate-800 dark:bg-slate-900">
                                        <div class="font-semibold text-slate-900 dark:text-slate-100">Public path</div>
                                        <a href="{{ $qrCode->public_url }}" target="_blank" rel="noreferrer" class="mt-1 block break-all text-cyan-600 hover:text-cyan-500 dark:text-cyan-300">{{ $qrCode->public_url }}</a>
                                    </div>

                                    <div class="grid gap-3 sm:grid-cols-3">
                                        <div class="rounded-2xl bg-slate-50 p-3 text-sm text-slate-600 dark:bg-slate-900 dark:text-slate-300">Scans: <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $qrCode->scan_count }}</span></div>
                                        <div class="rounded-2xl bg-slate-50 p-3 text-sm text-slate-600 dark:bg-slate-900 dark:text-slate-300">Downloads: <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $qrCode->download_count }}</span></div>
                                        <div class="rounded-2xl bg-slate-50 p-3 text-sm text-slate-600 dark:bg-slate-900 dark:text-slate-300">Last scan: <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $qrCode->last_scanned_at?->diffForHumans() ?? 'Never' }}</span></div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3 xl:max-w-[360px] xl:justify-end">
                                <a href="{{ $qrCode->public_url }}" target="_blank" rel="noreferrer" class="inline-flex items-center gap-2 rounded-2xl bg-cyan-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700">
                                    <i data-lucide="external-link" class="h-4 w-4"></i>
                                    Open
                                </a>
                                <a href="{{ route('admin.qr.download', ['qrCode' => $qrCode, 'format' => 'png']) }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                                    <i data-lucide="image-down" class="h-4 w-4"></i>
                                    PNG
                                </a>
                                <a href="{{ route('admin.qr.download', ['qrCode' => $qrCode, 'format' => 'svg']) }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                                    <i data-lucide="file-image" class="h-4 w-4"></i>
                                    SVG
                                </a>
                                <a href="{{ route('admin.qr.print', $qrCode) }}" target="_blank" rel="noreferrer" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                    <i data-lucide="printer" class="h-4 w-4"></i>
                                    Print
                                </a>
                                <form method="POST" action="{{ route('admin.qr.toggle-status', $qrCode) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                        <i data-lucide="{{ $qrCode->status === 'active' ? 'toggle-right' : 'toggle-left' }}" class="h-4 w-4"></i>
                                        {{ $qrCode->status === 'active' ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-400">
                        No QR codes matched the selected filters.
                    </div>
                @endforelse

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    {{ $qrCodes->withQueryString()->links() }}
                </div>
        </section>
    </div>
</x-app-layout>
