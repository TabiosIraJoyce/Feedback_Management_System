<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeFeedbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'department_id' => ['required', 'exists:departments,id'],
            'employee_name' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'in:suggestion,operational_concern,incident_report,internal_feedback,other'],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf,doc,docx', 'max:5120'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'is_anonymous' => ['nullable', 'boolean'],
        ];
    }
}
