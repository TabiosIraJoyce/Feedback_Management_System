<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Edit Complaint" description="Update complaint details, assignee, and current resolution status." icon="square-pen" />
    </x-slot>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('complaints.update', $complaint) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('complaints.partials.form', ['complaint' => $complaint, 'branches' => $branches, 'departments' => $departments, 'assignableUsers' => $assignableUsers, 'submitLabel' => 'Save Changes'])
        </form>
    </div>
</x-app-layout>
