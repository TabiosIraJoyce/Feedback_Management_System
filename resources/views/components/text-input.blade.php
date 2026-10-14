@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'hci-form-control rounded-2xl px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 shadow-sm focus:border-purple-button focus:ring-0 dark:text-slate-100']) }}>
