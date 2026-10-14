<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignBranchProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => [
                'required',
                Rule::exists('branches', 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.exists' => 'The selected branch must belong to the same business type as this product.',
        ];
    }
}
