<x-guest-layout>
    <div x-data="complaintSurveyExperience()" class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(34,211,238,0.16),_transparent_35%),linear-gradient(180deg,#020617_0%,#0f172a_100%)] px-4 py-12 text-slate-100 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl space-y-8">
            <section class="rounded-[2rem] border border-slate-800 bg-slate-900/95 p-8 shadow-2xl shadow-slate-950/20 backdrop-blur-xl sm:p-10">
                <div class="space-y-3">
                    <p class="text-sm uppercase tracking-[0.4em] text-cyan-300/80">{{ __('Satisfaction Survey') }}</p>
                    <h1 class="text-3xl font-semibold text-white sm:text-4xl">{{ $complaint->reference_no }}</h1>
                    <p class="text-sm text-slate-400">{{ __('Let us know how we handled your complaint. The emoji scale reduces effort while the follow-up prompt adapts to your rating.') }}</p>
                </div>

                <form method="POST" action="{{ route('track-complaint.survey.store', $complaint->reference_no) }}" class="mt-8 space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-slate-200">{{ __('Overall satisfaction') }}</label>
                        <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-5">
                            @foreach ([5 => ["\u{1F929}", __('Excellent')], 4 => ["\u{1F642}", __('Good')], 3 => ["\u{1F610}", __('Neutral')], 2 => ["\u{1F641}", __('Poor')], 1 => ["\u{1F621}", __('Very Poor')]] as $value => [$emoji, $label])
                                <label class="cursor-pointer rounded-3xl border border-slate-700 bg-slate-950 px-4 py-4 text-center transition hover:border-cyan-400">
                                    <input type="radio" name="satisfaction_rating" value="{{ $value }}" class="sr-only" x-model="selectedRating" @checked((string) old('satisfaction_rating') === (string) $value) required />
                                    <div class="text-3xl">{{ $emoji }}</div>
                                    <div class="mt-2 text-sm font-semibold text-white">{{ $label }}</div>
                                    <div class="mt-1 text-xs uppercase tracking-[0.25em] text-slate-400">{{ $value }}/5</div>
                                </label>
                            @endforeach
                        </div>
                        <p class="mt-4 text-sm text-cyan-200" x-text="prompt()"></p>
                        <x-input-error :messages="$errors->get('satisfaction_rating')" class="mt-2 text-sm text-rose-300" />
                    </div>

                    <div>
                        <label for="satisfaction_comment" class="block text-sm font-semibold text-slate-200">{{ __('Comments') }}</label>
                        <textarea id="satisfaction_comment" name="satisfaction_comment" rows="5" class="mt-2 block w-full rounded-3xl border border-slate-700 bg-slate-950 px-4 py-3 text-sm text-slate-100 focus:border-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-500/20" placeholder="{{ __('Share what helped, what was difficult, or how the team can improve.') }}">{{ old('satisfaction_comment') }}</textarea>
                        <p class="mt-2 text-xs text-slate-500">{{ __('Adaptive guidance supports usability by helping you know what kind of response is most helpful.') }}</p>
                        <x-input-error :messages="$errors->get('satisfaction_comment')" class="mt-2 text-sm text-rose-300" />
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-cyan-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400">
                            <i data-lucide="send" class="h-4 w-4"></i>
                            {{ __('Submit Survey') }}
                        </button>
                        <a href="{{ route('track-complaint.show', $complaint->reference_no) }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-700 bg-slate-950 px-5 py-3 text-sm font-semibold text-slate-200 transition hover:bg-slate-900">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            {{ __('Back to Tracking') }}
                        </a>
                    </div>
                </form>
            </section>
        </div>
    </div>

    <script>
        function complaintSurveyExperience() {
            return {
                selectedRating: '{{ old('satisfaction_rating') }}',
                prompts: @js([
                    'high' => __('Thank you. Tell us what the team did well so the same good practice can continue.'),
                    'mid' => __('A balanced rating is useful too. Tell us what felt average or unclear.'),
                    'low' => __('We are sorry the experience fell short. Please describe what made the resolution difficult.'),
                    'default' => __('Choose the emoji that best reflects your overall complaint experience.'),
                ]),
                prompt() {
                    if (Number(this.selectedRating) >= 4) {
                        return this.prompts.high;
                    }

                    if (Number(this.selectedRating) === 3) {
                        return this.prompts.mid;
                    }

                    if (Number(this.selectedRating) > 0) {
                        return this.prompts.low;
                    }

                    return this.prompts.default;
                },
            };
        }
    </script>
</x-guest-layout>
