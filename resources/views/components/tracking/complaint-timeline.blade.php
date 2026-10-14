@props(['steps'])

<div class="space-y-4">
    @foreach ($steps as $step)
        <div class="flex items-start gap-4">
            <div class="flex flex-col items-center">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl text-sm font-semibold {{ $step['completed'] ? 'bg-cyan-500 text-slate-950' : 'border border-slate-700 bg-slate-900 text-slate-400' }}">
                    {{ $loop->iteration }}
                </span>
                @if (! $loop->last)
                    <span class="mt-2 h-8 w-px {{ $step['completed'] ? 'bg-cyan-500/70' : 'bg-slate-700' }}"></span>
                @endif
            </div>
            <div class="pt-1">
                <p class="text-sm font-semibold {{ $step['active'] ? 'text-white' : 'text-slate-200' }}">{{ $step['label'] }}</p>
                <p class="mt-1 text-sm text-slate-400">
                    @if ($step['timestamp'])
                        {{ $step['timestamp']->format('M d, Y h:i A') }}
                    @elseif ($step['completed'])
                        {{ __('Completed') }}
                    @else
                        {{ __('Waiting') }}
                    @endif
                </p>
                @if (! empty($step['note']) && $step['active'])
                    <p class="mt-2 text-sm leading-6 text-slate-400">{{ $step['note'] }}</p>
                @endif
            </div>
        </div>
    @endforeach
</div>
