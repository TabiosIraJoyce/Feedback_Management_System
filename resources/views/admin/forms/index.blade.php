<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Form Builder" description="Manage public branch forms and the fields that collect customer feedback." icon="layout-grid" />
    </x-slot>

    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-3xl border border-cyan-200 bg-cyan-50 p-4 text-sm text-cyan-900">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[1.1fr,0.9fr]">
            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Form catalog</h2>
                            <p class="mt-2 text-sm text-slate-500">View and manage feedback forms for branches that use the public QR submission flow.</p>
                        </div>
                        <a href="{{ route('admin.forms.create') }}" class="inline-flex items-center gap-2 rounded-3xl bg-cyan-500 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-400">
                            Create new form
                        </a>
                    </div>
                </div>

                @foreach ($forms as $form)
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-slate-900">{{ $form->name }}</h3>
                                <p class="mt-2 text-sm text-slate-500">{{ $form->description ?? 'No description provided.' }}</p>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 text-sm">
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-700">Branch: {{ $form->branch?->name ?? 'Unassigned' }}</span>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-700">Fields: {{ $form->fields->count() }}</span>
                                <span class="rounded-full {{ $form->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }} px-3 py-1">{{ $form->is_active ? 'Published' : 'Draft' }}</span>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($form->fields as $field)
                                <span class="rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700">{{ $field->label }} ({{ $field->type }})</span>
                            @endforeach
                        </div>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ route('admin.forms.edit', $form) }}" class="inline-flex items-center gap-2 rounded-3xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-900 hover:bg-slate-50">Edit</a>
                            <form method="POST" action="{{ route('admin.forms.destroy', $form) }}" onsubmit="return confirm('Delete this form?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-2 rounded-3xl bg-rose-500 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-400">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">How it works</h3>
                    <p class="mt-3 text-sm text-slate-500">Each public form is linked to a branch and will appear on the branch feedback page. Use this builder to define the fields your customers see after scanning a branch QR code.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
