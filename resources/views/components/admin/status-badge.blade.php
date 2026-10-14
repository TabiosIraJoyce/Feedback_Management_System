@props(['value'])

@php
    $palette = match ($value) {
        'open', 'submitted' => 'bg-sky-100 text-sky-700',
        'in_progress', 'reviewed' => 'bg-amber-100 text-amber-700',
        'resolved', 'closed' => 'bg-emerald-100 text-emerald-700',
        'reopened' => 'bg-rose-100 text-rose-700',
        'archived' => 'bg-slate-200 text-slate-700',
        default => 'bg-slate-100 text-slate-600',
    };
@endphp

<span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $palette }}">
    {{ __(str_replace('_', ' ', $value)) }}
</span>
