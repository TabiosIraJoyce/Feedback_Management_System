@props([ 'labels' => [], 'values' => [], 'accent' => 'bg-cyan-500' ])

<div class="space-y-3">
    <div class="grid gap-3 sm:grid-cols-3">
        @foreach ($labels as $index => $label)
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-600 dark:bg-slate-900 dark:border-slate-800 dark:text-slate-300">
                <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $label }}</div>
                <div class="mt-1 text-xl font-semibold text-slate-700 dark:text-slate-200">{{ $values[$index] ?? 0 }}</div>
            </div>
        @endforeach
    </div>

    <div class="h-48 overflow-hidden rounded-3xl border border-slate-200 bg-slate-950/5 p-4 dark:border-slate-800 dark:bg-slate-900">
        <div class="relative h-full w-full overflow-hidden rounded-3xl bg-slate-100/80 dark:bg-slate-950">
            <div class="absolute inset-y-0 left-0 flex items-end gap-2 px-3 pb-3">
                @foreach ($values as $value)
                    <div class="relative flex-1 rounded-xl bg-slate-200 dark:bg-slate-800" style="height: {{ max(12, min(100, $value * 3)) }}%;"></div>
                @endforeach
            </div>
        </div>
    </div>
</div>
