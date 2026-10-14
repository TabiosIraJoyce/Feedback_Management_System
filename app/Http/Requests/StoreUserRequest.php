<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage users') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'role' => ['required', 'exists:roles,name'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator): void {
                if ($this->input('role') === 'Department Head' && ! $this->input('branch_id')) {
                    $validator->errors()->add('branch_id', 'Department Head accounts must be assigned to a department.');
                }
            },
        ];
    }
}
