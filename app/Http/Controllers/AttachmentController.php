<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Complaint;
use App\Models\Feedback;
use App\Services\AttachmentUploadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttachmentController extends Controller
{
    public function preview(Attachment $attachment): View
    {
        $this->authorizeAttachmentAccess($attachment, 'view');
        $this->logDownloadActivity($attachment, 'previewed');

        return view('attachments.preview', [
            'attachment' => $attachment,
        ]);
    }

    public function download(Attachment $attachment): StreamedResponse
    {
        $this->authorizeAttachmentAccess($attachment, 'view');
        $this->logDownloadActivity($attachment, 'downloaded');

        return Storage::disk($attachment->disk)->download($attachment->path, $attachment->original_name);
    }

    public function destroy(Attachment $attachment, AttachmentUploadService $uploads): RedirectResponse
    {
        $this->authorizeAttachmentAccess($attachment, 'update');

        $uploads->delete($attachment, request()->user());

        return back()->with('status', 'Attachment deleted successfully.');
    }

    private function authorizeAttachmentAccess(Attachment $attachment, string $ability): void
    {
        $attachable = $attachment->attachable;

        abort_unless($attachable, 404);

        if ($attachable instanceof Complaint) {
            $this->authorize($ability, $attachable);
            return;
        }

        if ($attachable instanceof Feedback) {
            $this->authorize($ability, $attachable);
            return;
        }

        abort(403);
    }

    private function logDownloadActivity(Attachment $attachment, string $action): void
    {
        $attachable = $attachment->attachable;

        if ($attachable) {
            activity()
                ->causedBy(request()->user())
                ->performedOn($attachable)
                ->withProperties([
                    'attachment_id' => $attachment->id,
                    'original_name' => $attachment->original_name,
                    'action' => $action,
                ])
                ->log('Attachment '.$action.'.');
        }
    }
}
