<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Services\AttachmentValidationService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFeedbackRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(array_map(fn ($value) => is_string($value) ? trim(strip_tags($value)) : $value, $this->all()));
    }

    public function authorize(): bool
    {
        return $this->user()?->can('manage feedback') || $this->user()?->can('submit feedback') || false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'branch_id' => ['nullable', 'exists:branches,id'],
            'department_id' => [
                'nullable',
                Rule::exists('departments', 'id')->where(
                    fn ($query) => $query->where('branch_id', $this->input('branch_id'))
                ),
            ],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:internal,customer'],
            'status' => ['required', 'in:submitted,reviewed,archived'],
            'message' => ['required', 'string', 'min:10'],
            'sentiment' => ['nullable', 'in:positive,negative'],
            'attachments' => ['nullable', 'array'],
            ...app(AttachmentValidationService::class)->rulesForFiles('attachments.*'),
        ];
    }

    public function messages(): array
    {
        return [
            'department_id.exists' => 'The selected department must belong to the selected branch.',
            'attachments.*.max' => app(AttachmentValidationService::class)->sizeExceededMessage(),
        ];
    }
}
