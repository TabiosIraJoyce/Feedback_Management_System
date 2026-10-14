@props([
    'attachments',
    'canDelete' => false,
])

<div class="space-y-3">
    @forelse ($attachments as $attachment)
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-white text-slate-700 shadow-sm dark:bg-slate-950 dark:text-slate-200">
                            <i data-lucide="{{ $attachment->is_image ? 'image' : ($attachment->is_pdf ? 'file-text' : 'file') }}" class="h-4 w-4"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $attachment->original_name }}</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ strtoupper($attachment->extension) }} | {{ $attachment->human_size }} | Uploaded {{ $attachment->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    @if ($attachment->is_previewable)
                        <a href="{{ route('attachments.preview', $attachment) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                            <i data-lucide="eye" class="h-3.5 w-3.5"></i>
                            Preview
                        </a>
                    @endif

                    <a href="{{ route('attachments.download', $attachment) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                        <i data-lucide="download" class="h-3.5 w-3.5"></i>
                        Download
                    </a>

                    @if ($canDelete)
                        <form method="POST" action="{{ route('attachments.destroy', $attachment) }}" onsubmit="return confirm('Delete this attachment?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 transition hover:bg-rose-100 dark:border-rose-900/40 dark:bg-rose-950/30 dark:text-rose-200">
                                <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <p class="rounded-2xl border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">No attachments uploaded yet.</p>
    @endforelse
</div>
