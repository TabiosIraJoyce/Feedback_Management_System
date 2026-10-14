@props([
    'label',
    'value',
    'icon' => 'bar-chart-3',
    'tone' => 'cyan',
])

@php
    $tones = [
        'cyan' => 'bg-cyan-500/10 text-cyan-700',
        'emerald' => 'bg-emerald-500/10 text-emerald-700',
        'amber' => 'bg-amber-500/10 text-amber-700',
        'rose' => 'bg-rose-500/10 text-rose-700',
    ];
@endphp

@php
    $bgMap = [
        'cyan' => 'from-soft-blue to-soft-blue/80',
        'emerald' => 'from-soft-green to-soft-green/80',
        'amber' => 'from-soft-yellow to-soft-yellow/80',
        'rose' => 'from-soft-pink to-soft-pink/80',
    ];
    $shade = $bgMap[$tone] ?? $bgMap['cyan'];
@endphp

<div class="rounded-3xl border border-card-border bg-card-lavender p-5 shadow-neumorph dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-secondary-text">{{ __($label) }}</p>
            <p class="mt-3 text-3xl font-extrabold text-primary-text">{{ $value }}</p>
        </div>

        <span class="inline-flex h-14 w-14 items-center justify-center rounded-3xl bg-gradient-to-br {{ $shade }} text-primary-text dark:from-slate-700 dark:to-slate-900 dark:text-slate-100 shadow-soft">
            <i data-lucide="{{ $icon }}" class="h-6 w-6"></i>
        </span>
    </div>
</div>
