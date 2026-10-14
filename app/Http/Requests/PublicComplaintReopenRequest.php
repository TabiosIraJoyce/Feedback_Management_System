<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicComplaintReopenRequest extends FormRequest
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
            'reason' => ['required', 'string', 'min:10', 'max:1500'],
        ];
    }
}
