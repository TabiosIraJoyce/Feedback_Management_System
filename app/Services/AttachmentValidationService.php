<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class AttachmentValidationService
{
    private const DEFAULT_MAX_UPLOAD_SIZE_MB = 5;

    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'doc', 'docx'];

    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];

    public function maxUploadSizeMb(): int
    {
        return max(1, (int) SettingsService::get('max_upload_size_mb', self::DEFAULT_MAX_UPLOAD_SIZE_MB));
    }

    public function maxUploadSizeKb(): int
    {
        return $this->maxUploadSizeMb() * 1024;
    }

    public function allowedExtensions(): array
    {
        return self::ALLOWED_EXTENSIONS;
    }

    public function allowedMimeTypes(): array
    {
        return self::ALLOWED_MIME_TYPES;
    }

    public function rulesForFiles(string $field = 'attachments.*'): array
    {
        return [
            $field => [
                'file',
                'max:'.$this->maxUploadSizeKb(),
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! $value instanceof UploadedFile) {
                        return;
                    }

                    $extension = strtolower($value->getClientOriginalExtension());
                    if (! in_array($extension, $this->allowedExtensions(), true)) {
                        $fail('The uploaded file type is not supported.');
                    }

                    $mimeType = (string) $value->getMimeType();
                    if (! in_array($mimeType, $this->allowedMimeTypes(), true)) {
                        $fail('The uploaded file type is not supported.');
                    }

                    if ($value->getSize() > ($this->maxUploadSizeMb() * 1024 * 1024)) {
                        $fail($this->sizeExceededMessage());
                    }
                },
            ],
        ];
    }

    public function validateUploadedFile(UploadedFile $file): void
    {
        $validator = validator(
            ['attachment' => $file],
            ['attachment' => $this->rulesForFiles('attachment')['attachment']],
            ['attachment.max' => $this->sizeExceededMessage()],
        );

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
    }

    public function uploadHelpText(): string
    {
        return 'Supported files: JPG, JPEG, PNG, WEBP, PDF, DOC, DOCX. Maximum '.$this->maxUploadSizeMb().' MB each.';
    }

    public function sizeExceededMessage(): string
    {
        return 'The uploaded file exceeds the maximum allowed size of '.$this->maxUploadSizeMb().' MB.';
    }
}
