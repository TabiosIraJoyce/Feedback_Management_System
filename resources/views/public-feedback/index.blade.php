<x-guest-layout>
    <div class="min-h-screen w-full bg-[radial-gradient(circle_at_top,_rgba(34,211,238,0.18),_transparent_35%),linear-gradient(180deg,#020617_0%,#0f172a_100%)] px-4 py-10 text-slate-100 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl space-y-8">
            <section class="rounded-[2rem] border border-slate-800 bg-slate-900/90 p-8 shadow-2xl shadow-slate-950/20 backdrop-blur-xl sm:p-10">
                <div class="space-y-3 text-center">
                    <p class="text-sm uppercase tracking-[0.45em] text-cyan-300/80">GLEDCO Public Feedback</p>
                    <h1 class="text-3xl font-semibold tracking-tight text-white sm:text-4xl">Submit feedback for your branch</h1>
                    <p class="mx-auto max-w-2xl text-sm text-slate-300">Choose the branch or department you want to send feedback to. No login required.</p>
                </div>
            </section>

            @if ($branches->isEmpty())
                <section class="rounded-[2rem] border border-slate-800 bg-slate-900/90 p-8 shadow-xl shadow-slate-950/20">
                    <p class="text-center text-slate-300">No active branches are available for public feedback at this time.</p>
                </section>
            @else
                @foreach ($branches as $branch)
                    <section class="rounded-[2rem] border border-slate-800 bg-slate-900/90 p-8 shadow-xl shadow-slate-950/20">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="text-2xl font-semibold text-white">{{ $branch->name }}</h2>
                                <p class="mt-2 text-sm text-slate-400">{{ $branch->description ?? 'Submit public feedback to this branch.' }}</p>
                            </div>
                            <div class="space-x-3">
                                <a href="{{ route('feedback.public.show', ['branch' => $branch->slug]) }}" class="inline-flex items-center gap-2 rounded-3xl bg-cyan-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400">
                                    Open branch feedback
                                </a>
                            </div>
                        </div>

                        @if ($branch->departments->isNotEmpty())
                            <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($branch->departments as $department)
                                    <a href="{{ route('feedback.public.show', ['branch' => $branch->slug, 'department' => $department->slug]) }}" class="rounded-3xl border border-slate-700 bg-slate-950/80 px-5 py-4 text-sm text-slate-200 transition hover:border-cyan-500 hover:bg-slate-900">
                                        <p class="font-semibold text-white">{{ $department->name }}</p>
                                        <p class="mt-1 text-slate-400">{{ $department->description ?? 'Department feedback form' }}</p>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </section>
                @endforeach
            @endif
        </div>
    </div>
</x-guest-layout>
