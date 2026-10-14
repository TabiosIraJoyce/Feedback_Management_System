<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Edit Feedback" description="Update visibility and resolution state for this feedback item." icon="message-square-diff" />
    </x-slot>

    <div class="hci-panel p-6 sm:p-8">
        <form method="POST" action="{{ route('feedback.update', ['feedback' => $feedback->id]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('feedback.partials.form', ['feedback' => $feedback, 'branches' => $branches, 'departments' => $departments, 'submitLabel' => 'Save Changes'])
        </form>
    </div>
</x-app-layout>
