@props([
    'compact' => false,
])

<div x-data="languageSwitcher({ locale: '{{ app()->getLocale() }}' })" class="flex items-center gap-2">
    <label for="global-language-switcher-{{ $compact ? 'compact' : 'full' }}" class="{{ $compact ? 'sr-only' : 'text-xs font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400' }}">
        {{ __('Language') }}
    </label>
    <select
        id="global-language-switcher-{{ $compact ? 'compact' : 'full' }}"
        x-model="locale"
        @change="switchLocale($event.target.value)"
        class="rounded-2xl border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
        aria-label="{{ __('Language') }}"
    >
        @foreach (($hciConfig['available_locales'] ?? []) as $locale)
            <option value="{{ $locale['value'] }}">{{ __($locale['label']) }}</option>
        @endforeach
    </select>
</div>
