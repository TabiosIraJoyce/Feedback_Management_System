<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage branches') ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $departmentId = $this->route('department')?->id;

        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('departments', 'code')
                    ->ignore($departmentId)
                    ->where(fn ($query) => $query->where('branch_id', $this->input('branch_id'))),
            ],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp', 'max:2048'],
        ];
    }
}
