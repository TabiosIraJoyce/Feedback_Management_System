<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Submit Feedback" description="{{ auth()->user()->hasRole('Customer') ? 'Tell us what you think.' : 'Share internal process suggestions or customer experience notes.' }}" icon="message-circle-plus" />
    </x-slot>

    <div class="hci-panel p-6 sm:p-8">
        <form method="POST" action="{{ route('feedback.store') }}" enctype="multipart/form-data">
            @csrf
            @include('feedback.partials.form', ['branches' => $branches, 'departments' => $departments, 'submitLabel' => 'Submit Feedback'])
        </form>
    </div>
</x-app-layout>
