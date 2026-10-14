<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="New Form" description="Build a public branch form with configurable fields and publish it to a branch." icon="plus-square" />
    </x-slot>

    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-3xl border border-cyan-200 bg-cyan-50 p-4 text-sm text-cyan-900">
                {{ session('status') }}
            </div>
        @endif

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.forms.store') }}" class="space-y-6">
                @csrf
                @include('admin.forms.partials.form-builder')

                <div class="flex justify-end">
                    <x-primary-button>Create form</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
