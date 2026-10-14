@props(['complaint'])

@php
    $monitor = app(\App\Services\SlaMonitorService::class);
    $status = $monitor->evaluate($complaint);
    $color = match($status) {
        'On Time' => 'bg-green-500',
        'Near Due' => 'bg-yellow-500',
        'Escalated' => 'bg-red-600',
        default => 'bg-slate-500'
    };
@endphp

<span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold text-white {{ $color }}">
    <i data-lucide="clock" class="h-4 w-4"></i>
    {{ $status }}
</span>
