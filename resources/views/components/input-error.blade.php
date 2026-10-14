@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'space-y-1 rounded-2xl border border-rose-200 bg-rose-50 px-3 py-2 text-sm font-medium text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/30 dark:text-rose-200']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
