<x-guest-layout>
    <div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(34,211,238,0.16),_transparent_35%),linear-gradient(180deg,#020617_0%,#0f172a_100%)] px-4 py-12 text-slate-100 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl space-y-8">
            <section class="rounded-[2rem] border border-slate-800 bg-slate-900/95 p-8 shadow-2xl shadow-slate-950/20 backdrop-blur-xl sm:p-10">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.4em] text-cyan-300/80">{{ __('Resolution Summary') }}</p>
                        <h1 class="mt-2 text-3xl font-semibold text-white sm:text-4xl">{{ $complaint->reference_no }}</h1>
                    </div>
                    <a href="{{ route('track-complaint.show', $complaint->reference_no) }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:bg-slate-900">
                        <i data-lucide="arrow-left" class="h-4 w-4"></i>
                        {{ __('Back to Tracking') }}
                    </a>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-6">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Status') }}</p>
                    <p class="mt-2 text-xl font-semibold text-white">{{ __(\Illuminate\Support\Str::of($complaint->status)->replace('_', ' ')->title()->toString()) }}</p>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-6">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('SLA') }}</p>
                    <p class="mt-2 text-xl font-semibold text-white">{{ $sla['label'] }}</p>
                    <p class="mt-1 text-sm text-slate-400">{{ $sla['details'] }}</p>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-6">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Escalation') }}</p>
                    <p class="mt-2 text-xl font-semibold text-white">{{ $escalation['is_escalated'] ? __('Escalated') : __('Not Escalated') }}</p>
                    <p class="mt-1 text-sm text-slate-400">{{ __(':count escalation event(s)', ['count' => $escalation['count']]) }}</p>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-800 bg-slate-900/90 p-8">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Public Resolution Note') }}</p>
                <div class="mt-4 rounded-3xl border border-slate-800 bg-slate-950/80 p-6 text-sm leading-8 text-slate-300">
                    {{ $complaint->resolution_summary ?: __('A public resolution summary has not been posted yet.') }}
                </div>

                @if ($escalation['latest_note'])
                    <div class="mt-6 rounded-3xl border border-rose-500/20 bg-rose-500/10 p-6">
                        <p class="font-semibold text-rose-100">{{ __('Latest Escalation Update') }}</p>
                        <p class="mt-2 text-sm leading-7 text-rose-200/90">{{ $escalation['latest_note'] }}</p>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-guest-layout>
