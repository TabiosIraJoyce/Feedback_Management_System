<?php

namespace App\Services;

use App\Models\Attachment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttachmentUploadService
{
    public function __construct(private AttachmentValidationService $validation)
    {
    }

    /**
     * @param  UploadedFile[]  $files
     * @return \Illuminate\Support\Collection<int, Attachment>
     */
    public function uploadMany(Model $attachable, array $files, ?User $user = null)
    {
        return collect($files)
            ->filter(fn ($file) => $file instanceof UploadedFile)
            ->map(fn (UploadedFile $file) => $this->upload($attachable, $file, $user));
    }

    public function upload(Model $attachable, UploadedFile $file, ?User $user = null): Attachment
    {
        $this->validation->validateUploadedFile($file);

        $extension = strtolower($file->getClientOriginalExtension());
        $directory = $this->directoryFor($attachable);
        $storedName = Str::uuid()->toString().'.'.$extension;
        $path = $file->storeAs($directory, $storedName, 'public');

        $attachment = DB::transaction(function () use ($attachable, $user, $directory, $path, $storedName, $file, $extension): Attachment {
            /** @var Attachment $attachment */
            $attachment = $attachable->attachments()->create([
                'uploaded_by_user_id' => $user?->id,
                'disk' => 'public',
                'directory' => $directory,
                'path' => $path,
                'stored_name' => $storedName,
                'original_name' => $file->getClientOriginalName(),
                'extension' => $extension,
                'mime_type' => (string) $file->getMimeType(),
                'size_bytes' => (int) $file->getSize(),
            ]);

            activity()
                ->causedBy($user)
                ->performedOn($attachable)
                ->withProperties([
                    'attachment_id' => $attachment->id,
                    'original_name' => $attachment->original_name,
                    'path' => $attachment->path,
                    'size_bytes' => $attachment->size_bytes,
                ])
                ->log('Attachment uploaded.');

            return $attachment;
        });

        return $attachment;
    }

    public function delete(Attachment $attachment, ?User $user = null): void
    {
        $attachable = $attachment->attachable;
        $properties = [
            'attachment_id' => $attachment->id,
            'original_name' => $attachment->original_name,
            'path' => $attachment->path,
        ];

        $attachment->delete();

        if ($attachable) {
            activity()
                ->causedBy($user)
                ->performedOn($attachable)
                ->withProperties($properties)
                ->log('Attachment deleted.');
        }
    }

    private function directoryFor(Model $attachable): string
    {
        $base = Str::kebab(class_basename($attachable));

        return 'attachments/'.$base.'/'.now()->format('Y/m');
    }
}
