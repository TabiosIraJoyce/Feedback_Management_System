<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicComplaintSurveyRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(array_map(fn ($value) => is_string($value) ? trim(strip_tags($value)) : $value, $this->all()));
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'satisfaction_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'satisfaction_comment' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
