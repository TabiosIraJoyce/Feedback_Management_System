@props([
    'href',
    'active' => false,
    'icon' => 'circle',
])

<a href="{{ $href }}" @class([
    'flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-semibold transition-all duration-200',
    'border border-transparent bg-soft-blue text-primary-text shadow-soft ring-1 ring-sidebar-purple/10 dark:bg-slate-800/80 dark:text-slate-100' => $active,
    'text-primary-text hover:bg-white/80 hover:text-primary-text dark:text-slate-100 dark:hover:bg-slate-800' => ! $active,
]) aria-current="{{ $active ? 'page' : false }}">
    <i data-lucide="{{ $icon }}" @class(['h-5 w-5', 'text-sidebar-purple dark:text-cyan-300' => $active])></i>
    <span>{{ __(trim(strip_tags($slot))) }}</span>
</a>
