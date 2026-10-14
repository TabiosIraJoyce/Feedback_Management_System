@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold tracking-tight text-primary-text dark:text-slate-100']) }}>
    {{ __($value ?? trim(strip_tags($slot))) }}
</label>
