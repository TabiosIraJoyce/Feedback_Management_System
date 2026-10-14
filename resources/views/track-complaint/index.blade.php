<x-guest-layout>
    <div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(34,211,238,0.16),_transparent_35%),linear-gradient(180deg,#020617_0%,#0f172a_100%)] px-4 py-12 text-slate-100 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl space-y-8">
            <section class="rounded-[2rem] border border-slate-800 bg-slate-900/95 p-8 shadow-2xl shadow-slate-950/20 backdrop-blur-xl sm:p-10">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="space-y-3">
                        <p class="text-sm uppercase tracking-[0.4em] text-cyan-300/80">{{ __('GLEDCO Complaint Tracking') }}</p>
                        <h1 class="text-3xl font-semibold text-white sm:text-4xl">{{ __('Track your complaint in real time') }}</h1>
                        <p class="max-w-3xl text-sm text-slate-400">{{ __('Enter your complaint tracking number to view the current timeline, SLA condition, public updates, resolution summary, and follow-up actions.') }}</p>
                    </div>

                    <div class="inline-flex items-center gap-3 rounded-3xl bg-slate-950 px-5 py-4 text-sm text-slate-300 shadow-lg shadow-slate-950/20">
                        <i data-lucide="scan-search" class="h-5 w-5"></i>
                        {{ __('Protected public lookup') }}
                    </div>
                </div>

                <form method="POST" action="{{ route('track-complaint.search') }}" class="mt-10 grid gap-4 sm:grid-cols-[minmax(0,1fr),auto]">
                    @csrf
                    <div>
                        <label for="tracking_number" class="mb-2 block text-sm font-semibold text-slate-200">{{ __('Tracking Number') }}</label>
                        <input id="tracking_number" name="tracking_number" type="text" value="{{ old('tracking_number') }}" placeholder="{{ __('GLD-SOL-000001 or GLD-2026-000001') }}" class="w-full rounded-3xl border border-slate-700 bg-slate-950 px-4 py-4 text-sm text-slate-100 shadow-sm outline-none ring-1 ring-slate-900 transition focus:border-cyan-400 focus:ring-cyan-500/20" required />
                        <x-input-error :messages="$errors->get('tracking_number')" class="mt-2 text-sm text-rose-300" />
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-3xl bg-cyan-500 px-6 py-4 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400">
                        <i data-lucide="arrow-right" class="h-4 w-4"></i>
                        {{ __('Track Complaint') }}
                    </button>
                </form>
            </section>

            <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-5">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Search') }}</p>
                    <p class="mt-2 text-sm text-slate-300">{{ __('Use the complaint tracking number provided after submission.') }}</p>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-5">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Timeline') }}</p>
                    <p class="mt-2 text-sm text-slate-300">{{ __('Review public-safe milestone updates from submission to closure.') }}</p>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-5">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Resolution') }}</p>
                    <p class="mt-2 text-sm text-slate-300">{{ __('See SLA state, escalation information, and the latest resolution summary.') }}</p>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-5">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ __('Follow-up') }}</p>
                    <p class="mt-2 text-sm text-slate-300">{{ __('Submit a satisfaction rating after closure or request a reopen when policy allows.') }}</p>
                </div>
            </section>
        </div>
    </div>
</x-guest-layout>
