<?php

namespace App\Http\Requests;

use App\Services\AttachmentValidationService;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(array_map(fn ($value) => is_string($value) ? trim(strip_tags($value)) : $value, $this->all()));
    }

    public function authorize(): bool
    {
        return $this->user()?->can('submit complaints') || $this->user()?->can('manage complaints') || false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'department_id' => [
                'nullable',
                Rule::exists('departments', 'id')->where(
                    fn ($query) => $query->where('branch_id', $this->input('branch_id'))
                ),
            ],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'priority' => ['required', 'in:low,normal,high,urgent'],
            'message' => ['required', 'string', 'min:10'],
            'sentiment' => [Rule::requiredIf($this->user()?->hasRole('Customer')), 'in:positive,negative'],
            'resolution_summary' => ['nullable', 'string', 'max:5000'],
            'assigned_to_user_id' => ['nullable', 'exists:users,id'],
            'due_at' => ['nullable', 'date'],
            'allow_public_reopen' => ['nullable', 'boolean'],
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
