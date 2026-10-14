<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSystemSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage settings') ?? false;
    }

    public function rules(): array
    {
        return [
            'company_name' => ['nullable', 'string', 'max:255'],
            'system_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'max_upload_limit' => ['nullable', 'integer', 'min:0', 'max:51200'],
            'max_upload_size_mb' => ['nullable', 'integer', 'min:1', 'max:100'],
            'default_sla_hours' => ['nullable', 'integer', 'min:0', 'max:8760'],
            'enable_notifications' => ['nullable', 'boolean'],
            'enable_dark_mode' => ['nullable', 'boolean'],
            'default_language' => ['nullable', 'string', 'in:en,fil,ilo'],
            'qr_defaults' => ['nullable', 'array'],
            'qr_defaults.*' => ['string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'enable_notifications' => $this->has('enable_notifications') ? true : false,
            'enable_dark_mode' => $this->has('enable_dark_mode') ? true : false,
        ]);
    }
}
