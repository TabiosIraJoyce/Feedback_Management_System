@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">SLA Configuration</h2>

    @if(session('status'))
        <div class="mb-4 rounded-md bg-green-50 p-4 text-green-800">{{ session('status') }}</div>
    @endif

    <div class="grid gap-4 md:grid-cols-2">
        @foreach($configs as $cfg)
            <form method="POST" action="{{ route('admin.sla.update', $cfg) }}" class="rounded-2xl border border-slate-200 p-4 bg-white">
                @csrf
                @method('PATCH')
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold">{{ ucwords(str_replace('_',' ', $cfg->category)) }}</p>
                        <p class="text-xs text-slate-500">Current SLA: {{ $cfg->hours }} hours</p>
                    </div>
                    <div class="text-sm">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="enabled" value="1" {{ $cfg->enabled ? 'checked' : '' }} /> Enabled
                        </label>
                    </div>
                </div>

                <div class="mt-3 flex items-center gap-3">
                    <input name="hours" type="number" min="1" value="{{ $cfg->hours }}" class="rounded border px-3 py-2 w-28" />
                    <button class="rounded bg-cyan-600 px-4 py-2 text-white">Save</button>
                </div>
            </form>
        @endforeach
    </div>
</div>
@endsection
