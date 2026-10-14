<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Edit Employee Feedback" description="Update the feedback details before the review workflow advances." icon="file-pen-line" />
    </x-slot>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <form method="POST" action="{{ route('employee-feedback.update', $feedback) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            <x-employee-feedback.form-fields :feedback="$feedback" :departments="$departments" :categories="$categories" :priorities="$priorities" />
            <div class="flex flex-wrap gap-3">
                <x-primary-button>Save Changes</x-primary-button>
                <a href="{{ route('employee-feedback.show', $feedback) }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">Back</a>
            </div>
        </form>
    </section>
</x-app-layout>
