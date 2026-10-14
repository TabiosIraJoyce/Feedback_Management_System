<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header :title="$attachment->original_name" description="Secure attachment preview" icon="paperclip" />
    </x-slot>

    <div class="space-y-6">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('attachments.download', $attachment) }}" class="inline-flex items-center gap-2 rounded-2xl bg-cyan-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-cyan-700">
                <i data-lucide="download" class="h-4 w-4"></i>
                Download
            </a>
            <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Back
            </a>
        </div>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="mb-4 text-sm text-slate-500 dark:text-slate-400">
                {{ strtoupper($attachment->extension) }} | {{ $attachment->human_size }}
            </div>

            @if ($attachment->is_image)
                <img src="{{ $attachment->url }}" alt="{{ $attachment->original_name }}" class="mx-auto max-h-[70vh] rounded-2xl object-contain" />
            @elseif ($attachment->is_pdf)
                <iframe src="{{ $attachment->url }}" title="{{ $attachment->original_name }}" class="h-[75vh] w-full rounded-2xl border border-slate-200 dark:border-slate-800"></iframe>
            @else
                <div class="rounded-2xl border border-dashed border-slate-300 px-6 py-16 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                    This file type cannot be previewed in the browser. Use the download button instead.
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
