@props([
    'title',
    'description' => null,
    'icon' => 'panel-top',
])

<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="hci-eyebrow">{{ __('Focused Workspace') }}</p>
        <div class="mt-2 flex items-center gap-3">
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-soft-blue text-sidebar-purple shadow-soft dark:bg-slate-800 dark:text-slate-100">
                <i data-lucide="{{ $icon }}" class="h-5 w-5"></i>
            </span>
            <div>
                <h2 class="text-2xl font-semibold tracking-tight text-slate-950 dark:text-slate-100">{{ __($title) }}</h2>
                @if ($description)
                    <p class="mt-1 max-w-2xl text-sm text-slate-500 dark:text-slate-400">{{ __($description) }}</p>
                @endif
            </div>
        </div>
    </div>

    @if (trim($slot))
        <div class="flex items-center gap-3">
            {{ $slot }}
        </div>
    @endif
</div>
