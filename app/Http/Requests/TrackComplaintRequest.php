<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackComplaintRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'tracking_number' => strtoupper(trim((string) $this->input('tracking_number'))),
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tracking_number' => ['required', 'string', 'regex:/^GLD-(?:[A-Z]{3}|\d{4})-\d{6}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'tracking_number.regex' => 'Tracking number must match the format GLD-SOL-000001 or GLD-2026-000001.',
        ];
    }
}
