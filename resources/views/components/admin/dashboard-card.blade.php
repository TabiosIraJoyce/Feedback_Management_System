@props([ 'title', 'subtitle' => null, 'icon' => 'layout-dashboard', 'badge' => null ])

<section class="rounded-3xl border border-card-border bg-white p-6 shadow-neumorph dark:bg-slate-950 dark:border-slate-800">
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <div class="flex items-center gap-3 text-slate-900 dark:text-slate-100">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-card-lavender text-primary-text dark:bg-slate-800 dark:text-slate-100">
                    <i data-lucide="{{ $icon }}" class="h-5 w-5"></i>
                </span>
                <div>
                    <h3 class="text-base font-semibold tracking-tight">{{ __($title) }}</h3>
                    @if ($subtitle)
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __($subtitle) }}</p>
                    @endif
                </div>
            </div>
        </div>

        @if ($badge)
            <div class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                {{ $badge }}
            </div>
        @endif
    </div>

    {{ $slot }}
</section>
