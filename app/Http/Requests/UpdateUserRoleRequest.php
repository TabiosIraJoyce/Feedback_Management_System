<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage users') ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->route('user')?->id),
            ],
            'password' => ['nullable', 'string', 'min:8'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'department_id' => ['nullable', Rule::exists('departments', 'id')->where(fn ($query) => $query->where('branch_id', $this->input('branch_id')))],
            'role' => ['required', 'exists:roles,name'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator): void {
                if (in_array($this->input('role'), ['Department Head', 'Supervisor'], true) && ! $this->input('branch_id')) {
                    $validator->errors()->add('branch_id', 'Complaint handlers must be assigned to a branch.');
                }
                if ($this->input('role') === 'Department Head' && ! $this->input('department_id')) {
                    $validator->errors()->add('department_id', 'Department Head accounts must be assigned to a department.');
                }
            },
        ];
    }
}
