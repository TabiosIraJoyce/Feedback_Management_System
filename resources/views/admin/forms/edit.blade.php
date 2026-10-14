<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Edit Form" description="Update field definitions, branch assignment, and publish status for this form." icon="edit-2" />
    </x-slot>

    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-3xl border border-cyan-200 bg-cyan-50 p-4 text-sm text-cyan-900">
                {{ session('status') }}
            </div>
        @endif

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.forms.update', $form) }}" class="space-y-6">
                @csrf
                @method('PATCH')
                @include('admin.forms.partials.form-builder')

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.forms.index') }}" class="inline-flex items-center gap-2 rounded-3xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-900 hover:bg-slate-50">Back</a>
                    <x-primary-button>Save changes</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
