<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage reports') ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'report' => ['nullable', Rule::in([
                'complaint-summary',
                'branch-performance',
                'product-services',
                'sla-performance',
                'employee-feedback',
                'complaint-trends',
                'reopened-complaints',
                'escalation-reports',
                'branch-rankings',
            ])],
            'branch' => ['nullable', 'exists:branches,id'],
            'product' => ['nullable', 'exists:products,id'],
            'status' => ['nullable', 'string', 'max:50'],
            'category' => ['nullable', 'string', 'max:100'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
            'format' => ['nullable', Rule::in(['pdf', 'csv'])],
            'frequency' => ['nullable', Rule::in(['daily', 'weekly', 'monthly'])],
            'name' => ['nullable', 'string', 'max:255'],
            'recipients' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
