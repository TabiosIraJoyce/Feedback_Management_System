@props(['user'])

@php
    $hour = now()->hour;
    if ($hour < 12) {
        $greeting = __('Good Morning');
        $emoji = "\u{1F324}\u{FE0F}";
    } elseif ($hour < 17) {
        $greeting = __('Good Afternoon');
        $emoji = "\u{2600}\u{FE0F}";
    } else {
        $greeting = __('Good Evening');
        $emoji = "\u{1F319}";
    }
@endphp

<div class="rounded-3xl border border-card-border bg-card-lavender p-6 shadow-neumorph text-primary-text dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100">
    <div class="flex items-center gap-6">
        <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-full bg-white p-2 ring-4 ring-white/60">
            <img src="{{ asset('images/feedback-logo.png') }}" alt="GLEDCO Feedback Management System Logo" class="h-full w-full object-contain" />
        </div>

        <div class="flex-1">
            <h2 class="text-2xl font-extrabold leading-tight text-slate-900">{{ $greeting }}, {{ explode(' ', $user->name)[0] }}! <span class="ml-2">{{ $emoji }}</span></h2>
            <p class="mt-1 text-sm text-secondary-text">{{ __("Here's the latest summary of complaints and feedback across your branches.") }}</p>
        </div>

        <div class="ml-auto space-y-2 text-right">
            <button class="inline-flex items-center gap-2 rounded-full bg-purple-button px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-purple-hover dark:bg-purple-button/90 dark:hover:bg-purple-hover/90">{{ __('Create Complaint') }}</button>
            <button class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/10 px-4 py-2 text-sm font-medium text-primary-text transition hover:brightness-105">{{ __('Export') }}</button>
        </div>
    </div>
</div>
