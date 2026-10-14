<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Submit Complaint" description="{{ auth()->user()->hasRole('Customer') ? 'Tell us what happened.' : 'Log a new complaint for customer or internal follow-up.' }}" icon="plus-circle" />
    </x-slot>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('complaints.store') }}" enctype="multipart/form-data">
            @csrf
            @include('complaints.partials.form', ['branches' => $branches, 'departments' => $departments, 'assignableUsers' => $assignableUsers, 'submitLabel' => 'Submit Complaint'])
        </form>
    </div>
</x-app-layout>
