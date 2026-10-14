<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class UpdateFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage forms') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $fields = collect($this->input('fields', []))->map(function ($field) {
            $field = is_array($field) ? $field : [];
            if (isset($field['options']) && is_string($field['options'])) {
                $field['options'] = array_values(array_filter(array_map('trim', explode(',', $field['options']))));
            }
            $field['required'] = isset($field['required']) ? (bool) $field['required'] : false;
            return $field;
        })->all();

        $this->merge([
            'fields' => $fields,
            'is_active' => filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'branch_id' => ['required', 'exists:branches,id'],
            'is_active' => ['sometimes', 'boolean'],
            'fields' => ['required', 'array', 'min:1'],
            'fields.*.label' => ['required', 'string', 'max:255'],
            'fields.*.name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_\-]+$/'],
            'fields.*.type' => ['required', Rule::in(['text', 'textarea', 'select', 'radio', 'checkbox', 'email', 'tel', 'number', 'date'])],
            'fields.*.placeholder' => ['nullable', 'string', 'max:255'],
            'fields.*.options' => ['nullable', 'array'],
            'fields.*.options.*' => ['string', 'max:255'],
            'fields.*.required' => ['boolean'],
            'fields.*.order' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
