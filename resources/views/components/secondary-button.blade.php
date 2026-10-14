<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 rounded-2xl border border-card-border bg-white/95 px-5 py-3 text-sm font-semibold text-primary-text shadow-sm transition duration-150 hover:border-lavender hover:bg-soft-blue/40 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60 dark:border-slate-800 dark:bg-slate-950/90 dark:text-slate-100 dark:hover:bg-slate-900']) }}>
    {{ __(trim(strip_tags($slot))) }}
</button>
